<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gravatar;

class GravatarController extends Controller
{

    public function index()
    {
        $avatars = Gravatar::latest()->get();
        return view('gravatar', compact('avatars'));
    }

    public function generate(Request $request)
    {
        $email = strtolower(trim($request->email));

        $hash = md5($email);

        $avatar = "https://www.gravatar.com/avatar/".$hash."?s=200&d=identicon";

        Gravatar::create([
            'email'=>$email,
            'avatar'=>$avatar
        ]);

        return redirect('/');
    }
}