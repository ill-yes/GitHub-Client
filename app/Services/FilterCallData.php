<?php
/**
 * Created by IntelliJ IDEA.
 * User: ilyestascou
 * Date: 2019-01-08
 * Time: 11:46
 */

namespace App\Services;


use Carbon\Carbon;

class FilterCallData
{
    public static function filterOwnRepos($repositories)
    {
        $filter = ['name', 'private', 'html_url', 'created_at', 'updated_at', 'language', 'forks'];

        $allRepositories = [];
        foreach($repositories AS $repo)
        {
            $singleRepo = [];
            foreach ($filter as $value)
            {
                if ($value == 'created_at' || $value == 'updated_at')
                {
                    $date = Carbon::createFromFormat(DATE_ISO8601, $repo->{$value})->format('d.m.Y');
                    $singleRepo[$value] = $date;
                }
                else
                {
                    $singleRepo[$value] = $repo->{$value};
                }
            }
            $allRepositories[] = $singleRepo;
        }
        return $allRepositories;
    }

    public static function filterBranchesWithPullRequests(array $pullRequests, array $branches)
    {
        $filteredBranches = [];

        foreach ($pullRequests AS $pull)
        {
            /*if (isset($branches[$pull['branch_name']]) && isset($pull['merged_at']) )
            {
                $filteredBranches[] = $pull;
            }*/
            if (isset($pull['merged_at']))
            {
                foreach ($branches AS $branch)
                {
                    if ($pull['branch_name'] == $branch['name']
                        && $pull['branch_commit_sha'] == $branch['commit_sha'])
                    {
                        $filteredBranches[] = $pull;
                    }
                }
            }
        }
        //$filteredBranches = array_intersect_key($pullRequests['branch_name'], $branches['name']);

        /*$activeItems = array_uintersect($pullRequests, $pullRequests['branch_name'], function($pullRequests, $branches) {
            return ($pullRequests['branch_name'] - $branches['name']);
        });
        dd($activeItems);*/
        return $filteredBranches;

    }

}
