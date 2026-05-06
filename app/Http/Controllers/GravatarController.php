<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gravatar;

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

        return view('gravatar', compact('avatars', 'search'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = strtolower(trim($request->email));

        // Prevent duplicate email
        $exists = Gravatar::where('email', $email)->first();

        if ($exists) {
            return redirect('/')->with('error', 'Avatar already exists!');
        }

        $hash = md5($email);

        $avatar = "https://www.gravatar.com/avatar/" . $hash . "?s=200&d=identicon";

        Gravatar::create([
            'email' => $email,
            'avatar' => $avatar
        ]);

        return redirect('/')->with('success', 'Avatar generated successfully!');
    }

    // Delete function
    public function delete($id)
    {
        Gravatar::findOrFail($id)->delete();
        return redirect('/')->with('success', 'Avatar deleted successfully!');
    }
}
