<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{

    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleCallback()
    {
        /** @var UserService $userService */
        $userService = app(UserService::class);

        $githubUser = Socialite::driver('github')->user();

        $inTeam = $userService->checkIfTeamMember($githubUser->getNickname());
        if (!$inTeam)
        {
            return view ('pages.home', [
                'error' => "You're not in the team!"
            ]);
        }

        $user = $userService->createOrGetUser($githubUser);

        auth()->login($user);

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home');
    }

}
