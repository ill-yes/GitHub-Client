<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pullrequest extends Model
{
    protected $table = 'pullrequests';

    protected $fillable = [
        'repository',
        'title',
        'pr_link',
        'branch_name',
        'branch_commit_sha',
        'merged_at',
        'merge_commit_sha',
        'user_login',
        'user_url',
        'location'
    ];
}
