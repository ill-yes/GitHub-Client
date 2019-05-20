<?php
/**
 * Created by IntelliJ IDEA.
 * User: ilyestascou
 * Date: 2019-03-07
 * Time: 10:16
 */

namespace App\Console\Commands;


use App\Client\CallManager;
use App\DB\CronModel;
use App\DB\PullrequestsModel;
use App\Services\FilterCallData;
use Illuminate\Console\Command;

class PullrequestsCron extends Command
{

    protected $signature = 'cron:pullrequests';

    protected $description = 'Running the cron for fetching pull requests';

    public function __construct()
    {
        parent::__construct();
    }

    public function addCron($token, $repository, $teamId, $days, $baseBranch)
    {
        CronModel::create([
            'repository' => $repository,
            'teamId' => $teamId,
            'days' => $days,
            'base_branch' => json_encode($baseBranch),
            'token' => $token
        ]);
    }

    public function handle()
    {
        $this->info("Starting cron!");
        $crons = CronModel::all();

        PullrequestsModel::truncate();

        $run = 0;
        $timeStart = microtime(true);
        foreach ($crons as $cron) {
            if ($this->singleRun($cron)) $run++;
        }
        $timeEnd = microtime(true);
        $timeRun = $timeEnd - $timeStart;

        $this->info($run . " / " . CronModel::count() . ": Completed after " . $timeRun . " seconds!");
    }

    public function singleRun($cron)
    {
        try {
            $callMngr = new CallManager($cron->token, true);
            $members = $callMngr->getTeamMembers($cron->teamId);
            $pullRequests = $callMngr->getPullRequests($cron->repository, $cron->days);

            if (isset($pullRequests) && isset($members)) {

                $baseBranches = json_decode($cron->base_branch, true);

                $filteredPullsByBranch = FilterCallData::filterPullrequestsByBaseBranch($pullRequests, $baseBranches);
                $filteredPullsByMember = FilterCallData::filterPullrequestsByMembers($filteredPullsByBranch, $members);

                foreach ($filteredPullsByMember as $key => $value) {
                    $filteredPullsByMember[$key]['location'] = $callMngr->compareCommitWithBranch($cron->repository, $value['merge_commit_sha']);

                    // entfernt prs, deren location leer ist (also ausserhalb von beta, early, stable)
                    if (!$filteredPullsByMember[$key]['location']) {
                        unset($filteredPullsByMember[$key]);
                        continue;
                    }
                    //if (!$filteredPullsByMember[$key]['location']) $filteredPullsByMember[$key]['location'] = 'others';

                    // speichert in db
                    PullrequestsModel::create([
                        'repository' => $cron->repository,
                        'title' => $filteredPullsByMember[$key]['title'],
                        'pr_link' => $filteredPullsByMember[$key]['pr_link'],
                        'branch_name' => $filteredPullsByMember[$key]['branch_name'],
                        'branch_commit_sha' => $filteredPullsByMember[$key]['branch_commit_sha'],
                        'merged_at' => $filteredPullsByMember[$key]['merged_at'],
                        'merge_commit_sha' => $filteredPullsByMember[$key]['merge_commit_sha'],
                        'user_login' => $filteredPullsByMember[$key]['user_login'],
                        'user_url' => $filteredPullsByMember[$key]['user_url'],
                        'location' => $filteredPullsByMember[$key]['location']
                    ]);
                }
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
