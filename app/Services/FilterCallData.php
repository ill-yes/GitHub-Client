<?php

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

        return $filteredBranches;
    }

    // TODO: methode implementieren, die pr filtert nach "merged_at" um so "closed"-prs ohne merge zu filtern

    public static function filterPullrequestsByMembers($pullRequests, $members)
    {
        $filteredPulls = [];

        foreach ($pullRequests AS $pull)
        {
            if (isset($members[$pull['user_login']]))
            {
                $filteredPulls[] = $pull;
            }
        }

        return $filteredPulls;
    }

    public static function filterPullrequestsByBaseBranch($pullRequests, $baseBranches)
    {
        $filteredPulls = [];

        foreach ($pullRequests AS $pull)
        {
            if (isset($baseBranches[$pull['base_label']]))
            {
                $filteredPulls[] = $pull;
            }
        }

        return $filteredPulls;
    }

}
