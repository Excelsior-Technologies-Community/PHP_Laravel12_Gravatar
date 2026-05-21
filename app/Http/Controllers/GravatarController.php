<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gravatar;
use Illuminate\Support\Facades\Response;

class GravatarController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $avatars = Gravatar::when($search, function ($query, $search) {
            return $query->where('email', 'like', '%' . $search . '%');
        })
            ->latest()
            ->get();

        $totalCount = Gravatar::count();

        return view('gravatar', compact('avatars', 'search', 'totalCount'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = strtolower(trim($request->email));
        $size = $request->size ?? 200;

        // Prevent duplicate email
        $exists = Gravatar::where('email', $email)->first();

        if ($exists) {
            return redirect('/')->with('error', 'Avatar already exists!');
        }

        $hash = md5($email);

        $avatar = "https://www.gravatar.com/avatar/" . $hash . "?s=" . $size . "&d=identicon";

        Gravatar::create([
            'email' => $email,
            'avatar' => $avatar,
            'size' => $size
        ]);

        return redirect('/')->with('success', 'Avatar generated successfully!');
    }

    // Delete single avatar
    public function delete($id)
    {
        Gravatar::findOrFail($id)->delete();
        return redirect('/')->with('success', 'Avatar deleted successfully!');
    }

    // NEW: Bulk generate avatars
    public function bulkGenerate(Request $request)
    {
        $request->validate([
            'emails' => 'required|string',
            'bulk_size' => 'nullable|integer|min:40|max:500'
        ]);

        $emailsInput = trim($request->emails);
        $emails = preg_split('/[\s,]+/', $emailsInput);
        $size = $request->bulk_size ?? 200;
        
        $successCount = 0;
        $failedEmails = [];

        foreach ($emails as $email) {
            $email = strtolower(trim($email));
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $failedEmails[] = $email . ' (invalid format)';
                continue;
            }
            
            $exists = Gravatar::where('email', $email)->exists();
            
            if (!$exists) {
                $hash = md5($email);
                $avatar = "https://www.gravatar.com/avatar/" . $hash . "?s=" . $size . "&d=identicon";
                
                Gravatar::create([
                    'email' => $email,
                    'avatar' => $avatar,
                    'size' => $size
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

    // NEW: Clear all avatars
    public function clearAll()
    {
        $count = Gravatar::count();
        Gravatar::truncate();
        return redirect('/')->with('success', 'All ' . $count . ' avatar(s) have been deleted!');
    }

    // NEW: Export to CSV
    public function exportCsv()
    {
        $avatars = Gravatar::all(['email', 'created_at']);
        
        $filename = 'gravatar_emails_' . date('Y-m-d') . '.csv';
        
        $handle = fopen('php://temp', 'w');
        
        // Add CSV headers
        fputcsv($handle, ['Email', 'Created At']);
        
        // Add data rows
        foreach ($avatars as $avatar) {
            fputcsv($handle, [$avatar->email, $avatar->created_at]);
        }
        
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);
        
        return Response::make($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // NEW: Preview avatar
    public function preview(Request $request)
    {
        $request->validate([
            'preview_email' => 'required|email'
        ]);

        $email = strtolower(trim($request->preview_email));
        $hash = md5($email);
        $size = $request->preview_size ?? 200;
        
        $avatarUrl = "https://www.gravatar.com/avatar/" . $hash . "?s=" . $size . "&d=identicon";
        
        return response()->json([
            'success' => true,
            'avatar_url' => $avatarUrl,
            'email' => $email,
            'exists' => Gravatar::where('email', $email)->exists()
        ]);
    }

    // NEW: Get avatar stats
    public function stats()
    {
        $total = Gravatar::count();
        $recentCount = Gravatar::where('created_at', '>=', now()->subDays(7))->count();
        $oldest = Gravatar::oldest()->first();
        $newest = Gravatar::latest()->first();
        
        return response()->json([
            'total' => $total,
            'recent_7_days' => $recentCount,
            'oldest_email' => $oldest ? $oldest->email : null,
            'newest_email' => $newest ? $newest->email : null
        ]);
    }
}