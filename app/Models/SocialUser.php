<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SocialUser extends Authenticatable
{
    use Notifiable;

    protected $table = "users";

    protected $fillable = [
        'username',
        'email',
        'github_id',
        'token',
        'avatar_url'
    ];
}
