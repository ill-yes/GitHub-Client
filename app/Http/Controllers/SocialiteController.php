<?php


namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{

    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function callback()
    {
        $userSocial = Socialite::driver('github')->user();
        $createdUser = User::where(['email' => $userSocial->getEmail()])->first();
        // https://medium.com/@Alabuja/social-login-in-laravel-with-socialite-90dbf14ee0ab
        if ($createdUser)
        {
            Auth::login($createdUser);
            return redirect('/');
        } else {
            $user = User::create([
                'username' => $userSocial->getNickname(),
                'email' => $userSocial->getEmail(),
                'github_id' => $userSocial->getId(),
                'token' => $userSocial->token
            ]);
            return redirect()->route('home');
        }
    }

}
