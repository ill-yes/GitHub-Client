<?php

namespace App\Services;

use App\Client\CallManager;
use App\Models\SocialUser;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class UserService
{
    public function createOrGetUser(SocialiteUser $user) : SocialUser
    {
        $user = SocialUser::firstOrCreate(
            [
                'email' => $user->getEmail()
            ],
            [
                'email' => $user->getEmail(),
                'username' => $user->getNickname(),
                'github_id' => $user->getId(),
                'token' => $user->token
            ]
        );

        return $user;
    }

    public function checkIfTeamMember(string $username) : bool
    {
        $callManager = new CallManager();
        $members = $callManager->getTeamMembers(env('TEAM_ID'));

        if(isset($members[$username]))
        {
            return true;
        }

        return false;
    }
}
