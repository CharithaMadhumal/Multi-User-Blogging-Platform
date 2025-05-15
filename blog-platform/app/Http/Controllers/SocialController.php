<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            ['name' => $socialUser->getName(), 'password' => bcrypt(Str::random(24))]
        );
        Auth::login($user);
        return redirect('/dashboard');
    }
}

