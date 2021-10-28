<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class LoginGithubController extends Controller
{
    public function redirectGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {

        $user = Socialite::driver('github')->user();

        $existsUser = User::where('social_id', $user->id)
            ->where('social_type', 'github')->first();

        if ($existsUser) {
            Auth::login($existsUser);
        } else {

            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'social_id' => $user->id,
                'social_type' => 'github',
            ]);

            Auth::login($newUser);
        }

        return redirect()->route('dashboard');
    }
}
