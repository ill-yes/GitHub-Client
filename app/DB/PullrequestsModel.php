<?php
/**
 * Created by IntelliJ IDEA.
 * User: ilyestascou
 * Date: 2019-03-07
 * Time: 09:54
 */

namespace App\DB;


use Illuminate\Database\Eloquent\Model;

class PullrequestsModel extends Model
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
