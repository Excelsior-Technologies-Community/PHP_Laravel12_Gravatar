<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gravatar;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GravatarController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $sort = $request->sort ?? 'latest';

        $query = Gravatar::when($search, function ($query, $search) {
            return $query->where('email', 'like', '%' . $search . '%');
        });

        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'email_asc':
                $query->orderBy('email', 'asc');
                break;
            case 'email_desc':
                $query->orderBy('email', 'desc');
                break;
            case 'favorites':
                $query->orderByDesc('is_favorite')->latest();
                break;
            default:
                $query->latest();
                break;
        }

        $avatars = $query->paginate(12)->withQueryString();
        $totalCount = Gravatar::count();

        return view('gravatar', compact('avatars', 'search', 'totalCount', 'sort'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'size' => 'nullable|integer|min:40|max:500',
            'rating' => 'nullable|in:g,pg,r,x',
            'default_image' => 'nullable|in:identicon,monsterid,wavatar,retro,robohash,mp,blank'
        ]);

        $email = strtolower(trim($request->email));
        $size = $request->size ?? 200;
        $rating = $request->rating ?? 'g';
        $defaultImage = $request->default_image ?? 'identicon';

        $exists = Gravatar::where('email', $email)->first();

        if ($exists) {
            return redirect('/')->with('error', 'Avatar already exists!');
        }

        $avatarUrl = $this->buildAvatarUrl($email, $size, $rating, $defaultImage);
        $isReal = $this->checkRealGravatar($email);

        Gravatar::create([
            'email' => $email,
            'avatar' => $avatarUrl,
            'size' => $size,
            'rating' => $rating,
            'default_image' => $defaultImage,
            'has_real_gravatar' => $isReal,
            'gravatar_checked_at' => now()
        ]);

        return redirect('/')->with('success', 'Avatar generated successfully!');
    }

    public function delete($id)
    {
        $avatar = Gravatar::findOrFail($id);
        Cache::forget('gravatar_real_' . md5($avatar->email));
        $avatar->delete();
        return redirect('/')->with('success', 'Avatar deleted successfully!');
    }

    public function bulkGenerate(Request $request)
    {
        $request->validate([
            'emails' => 'required|string',
            'bulk_size' => 'nullable|integer|min:40|max:500',
            'bulk_rating' => 'nullable|in:g,pg,r,x',
            'bulk_default_image' => 'nullable|in:identicon,monsterid,wavatar,retro,robohash,mp,blank'
        ]);

        $emailsInput = trim($request->emails);
        $emails = preg_split('/[\s,]+/', $emailsInput);
        $size = $request->bulk_size ?? 200;
        $rating = $request->bulk_rating ?? 'g';
        $defaultImage = $request->bulk_default_image ?? 'identicon';

        $successCount = 0;
        $failedEmails = [];

        foreach ($emails as $email) {
            $email = strtolower(trim($email));

            if (empty($email)) {
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $failedEmails[] = $email . ' (invalid format)';
                continue;
            }

            $exists = Gravatar::where('email', $email)->exists();

            if (!$exists) {
                $avatarUrl = $this->buildAvatarUrl($email, $size, $rating, $defaultImage);
                $isReal = $this->checkRealGravatar($email);

                Gravatar::create([
                    'email' => $email,
                    'avatar' => $avatarUrl,
                    'size' => $size,
                    'rating' => $rating,
                    'default_image' => $defaultImage,
                    'has_real_gravatar' => $isReal,
                    'gravatar_checked_at' => now()
                ]);
                $successCount++;
            } else {
                $failedEmails[] = $email . ' (already exists)';
            }
        }

        $message = $successCount . ' avatar(s) generated successfully!';
        if (!empty($failedEmails)) {
            $message .= ' Failed: ' . implode(', ', array_slice($failedEmails, 0, 5));
            if (count($failedEmails) > 5) {
                $message .= ' and ' . (count($failedEmails) - 5) . ' more';
            }
        }

        return redirect('/')->with('success', $message);
    }

    public function clearAll()
    {
        $count = Gravatar::count();
        Gravatar::truncate();
        return redirect('/')->with('success', 'All ' . $count . ' avatar(s) have been deleted!');
    }

    public function exportCsv()
    {
        $avatars = Gravatar::all();

        $filename = 'gravatar_emails_' . date('Y-m-d') . '.csv';

        $handle = fopen('php://temp', 'w');

        fputcsv($handle, ['Email', 'Size', 'Rating', 'Default Image', 'Favorite', 'Real Gravatar', 'Created At']);

        foreach ($avatars as $avatar) {
            fputcsv($handle, [
                $avatar->email,
                $avatar->size,
                $avatar->rating,
                $avatar->default_image,
                $avatar->is_favorite ? 'Yes' : 'No',
                $avatar->has_real_gravatar ? 'Yes' : 'No',
                $avatar->created_at
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return Response::make($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function preview(Request $request)
    {
        $request->validate([
            'preview_email' => 'required|email'
        ]);

        $email = strtolower(trim($request->preview_email));
        $size = $request->preview_size ?? 200;
        $rating = $request->preview_rating ?? 'g';
        $defaultImage = $request->preview_default_image ?? 'identicon';

        $avatarUrl = $this->buildAvatarUrl($email, $size, $rating, $defaultImage);
        $isReal = $this->checkRealGravatar($email);

        return response()->json([
            'success' => true,
            'avatar_url' => $avatarUrl,
            'email' => $email,
            'exists' => Gravatar::where('email', $email)->exists(),
            'is_real_gravatar' => $isReal
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'size' => 'nullable|integer|min:40|max:500',
            'rating' => 'nullable|in:g,pg,r,x',
            'default_image' => 'nullable|in:identicon,monsterid,wavatar,retro,robohash,mp,blank'
        ]);

        $avatar = Gravatar::findOrFail($id);

        $size = $request->size ?? $avatar->size;
        $rating = $request->rating ?? $avatar->rating;
        $defaultImage = $request->default_image ?? $avatar->default_image;

        $avatarUrl = $this->buildAvatarUrl($avatar->email, $size, $rating, $defaultImage);

        $avatar->update([
            'avatar' => $avatarUrl,
            'size' => $size,
            'rating' => $rating,
            'default_image' => $defaultImage
        ]);

        return redirect('/')->with('success', 'Avatar settings updated successfully!');
    }

    public function toggleFavorite($id)
    {
        $avatar = Gravatar::findOrFail($id);
        $avatar->update(['is_favorite' => !$avatar->is_favorite]);
        return redirect('/')->with('success', $avatar->is_favorite ? 'Added to favorites!' : 'Removed from favorites!');
    }

    public function refreshCache($id)
    {
        $avatar = Gravatar::findOrFail($id);
        Cache::forget('gravatar_real_' . md5($avatar->email));
        $isReal = $this->checkRealGravatar($avatar->email);

        $avatar->update([
            'has_real_gravatar' => $isReal,
            'gravatar_checked_at' => now()
        ]);

        return redirect('/')->with('success', 'Gravatar cache refreshed for ' . $avatar->email);
    }

    public function stats()
    {
        $total = Gravatar::count();
        $recentCount = Gravatar::where('created_at', '>=', now()->subDays(7))->count();
        $realCount = Gravatar::where('has_real_gravatar', true)->count();
        $favoriteCount = Gravatar::where('is_favorite', true)->count();
        $oldest = Gravatar::oldest()->first();
        $newest = Gravatar::latest()->first();

        return response()->json([
            'total' => $total,
            'recent_7_days' => $recentCount,
            'real_gravatars' => $realCount,
            'favorites' => $favoriteCount,
            'oldest_email' => $oldest ? $oldest->email : null,
            'newest_email' => $newest ? $newest->email : null
        ]);
    }

    private function buildAvatarUrl($email, $size = 200, $rating = 'g', $defaultImage = 'identicon')
    {
        $hash = md5(strtolower(trim($email)));
        return "https://www.gravatar.com/avatar/{$hash}?s={$size}&r={$rating}&d={$defaultImage}";
    }

    private function checkRealGravatar($email)
    {
        $hash = md5(strtolower(trim($email)));
        $cacheKey = 'gravatar_real_' . $hash;

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($hash) {
            try {
                $response = Http::timeout(3)->get("https://www.gravatar.com/avatar/{$hash}?d=404");
                return $response->status() === 200;
            } catch (\Exception $e) {
                return false;
            }
        });
    }
}