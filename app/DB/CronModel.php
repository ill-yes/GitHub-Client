<?php
/**
 * Created by IntelliJ IDEA.
 * User: ilyestascou
 * Date: 2019-03-07
 * Time: 12:49
 */

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
