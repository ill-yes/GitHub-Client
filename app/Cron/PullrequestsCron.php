<?php
/**
 * Created by IntelliJ IDEA.
 * User: ilyestascou
 * Date: 2019-03-07
 * Time: 10:16
 */

namespace App\Cron;


use App\Client\CallManager;
use App\DB\CronModel;
use App\DB\PullrequestsModel;
use App\Services\FilterCallData;

class PullrequestsCron
{

    public function addCron($token, $repository, $teamId, $pages)
    {
        CronModel::create([
            'repo' => $repository,
            'teamId' => $teamId,
            'pages' => $pages,
            'token' => $token
        ]);
    }

    public function iterativeStart()
    {
        $crons = CronModel::all();

        PullrequestsModel::truncate();

        foreach ($crons as $cron)
        {
            $this->run($cron);
        }
    }

    private function run ($cron)
    {
        $callMngr       = new CallManager(              $cron->token);
        $members        = $callMngr->getTeamMembers(    $cron->teamId);
        $pullRequests   = $callMngr->getPullRequests(   $cron->repo, $cron->pages);

        if (isset($pullRequests) && isset($members))
        {
            $filteredPulls = FilterCallData::filterPullrequestsWithMembers($pullRequests, $members);

            foreach ($filteredPulls as $key => $value)
            {
                $filteredPulls[$key]['location'] = $callMngr->compareCommitWithBranch($cron->repo, $value['merge_commit_sha']);

                // entfernt prs, dessen location leer ist (also ausserhalb von beta, early, stable
                if (!$filteredPulls[$key]['location']) unset($filteredPulls[$key]);
                //if (!$filteredPulls[$key]['location']) $filteredPulls[$key]['location'] = 'others';

                // speichert in db
                $pullRequests = PullrequestsModel::create([
                    'repository' => $cron->repo,
                    'title' => $filteredPulls[$key]['title'],
                    'pr_link' => $filteredPulls[$key]['pr_link'],
                    'branch_name' => $filteredPulls[$key]['branch_name'],
                    'branch_commit_sha' => $filteredPulls[$key]['branch_commit_sha'],
                    'merged_at' => $filteredPulls[$key]['merged_at'],
                    'merge_commit_sha' => $filteredPulls[$key]['merge_commit_sha'],
                    'user_login' => $filteredPulls[$key]['user_login'],
                    'user_url' => $filteredPulls[$key]['user_url'],
                    'location' => $filteredPulls[$key]['location']
                ]);
            }
        }
    }
}
