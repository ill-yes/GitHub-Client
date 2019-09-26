<?php

namespace App\Http\Controllers;

use App\User;
use Laravel\Socialite\Facades\Socialite;


class AuthController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $githubUser = Socialite::driver('github')->user();

        $user = [
            'username' => $githubUser->nickname,
            'email' => $githubUser->email,
            'github_id' => $githubUser->id,
            'token' => $githubUser->token,
            'avatar_url' => $githubUser->avatar
        ];

        $model = User::firstOrCreate($user);

        auth()->login($model, false);

        return redirect("/");
    }
}
