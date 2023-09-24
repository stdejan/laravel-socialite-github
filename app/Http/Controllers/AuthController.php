<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function callback()
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::updateOrCreate([
            'email' => $githubUser->getEmail(),
        ], [
            'provider_id' => $githubUser->getId(),
            'name' => $githubUser->getName() ?? $githubUser->getNickname(),
            'token' => $githubUser->token,
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
}
