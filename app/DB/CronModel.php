<?php

namespace App\DB;


use Illuminate\Database\Eloquent\Model;

class CronModel extends Model
{
    protected $table = 'crons';

    protected $fillable = [
        'repository',
        'teamId',
        'days',
        'base_branch',
        'token'
    ];
}
