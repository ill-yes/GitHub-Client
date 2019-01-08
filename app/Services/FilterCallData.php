<?php
/**
 * Created by IntelliJ IDEA.
 * User: ilyestascou
 * Date: 2019-01-08
 * Time: 11:46
 */

namespace App\Services;


use App\Client\CallManager;
use Carbon\Carbon;

class FilterCallData
{
    static public function filterOwnRepos($repositories)
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

    static public function filterPullRequestsByBranches($pullRequests, CallManager $callManager)
    {
        $branches = [];
        foreach ($pullRequests as $pull)
        {
            if (isset($pull->merged_at))
            {
                $branch['name'] = $pull->head->ref;
                $branch['pr_link'] = $pull->html_url;
                $branch['merged_at'] = $pull->merged_at;
                //$callManager->checkBranchIfExists($pull->head->ref);
                $branch['exists'] = $callManager->checkBranchIfExists($pull->head->ref);
                //$branch['exists'] = true;

                $branches[] = $branch;
            }
        }

        return $branches;
    }

}
