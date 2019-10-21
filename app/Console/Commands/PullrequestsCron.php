<?php

namespace App\Console\Commands;

use App\Github\Client\CallManager;
use App\Github\Models\Pullrequest;
use App\Github\Services\FilterCallData;
use Illuminate\Console\Command;

class PullrequestsCron extends Command
{
    protected $signature = 'cron:pullrequests';

    protected $description = 'Running the cron for fetching pull requests';

    public $baseBranches;
    public $repos = [];

    public function __construct()
    {
        parent::__construct();

        $this->baseBranches = [
            'plentymarkets:feature' => true,
            'plentymarkets:early' => true,
            'plentymarkets:beta7' => true,
            'plentymarkets:stable7' => true
        ];

        $pullableRepos = explode(',', env('REPOSITORIES'));
        foreach ($pullableRepos as $repoName)
        {
            $this->repos[] =
                [
                    'repository' => $repoName,
                    'teamId' => env('TEAM_ID'),
                    'days' => env('DAYS'),
                    'token' => env('TOKEN')
                ];
        }
    }

    public function handle()
    {
        $this->info("Starting cron!");

        Pullrequest::truncate();

        $run = 0;
        $timeStart = microtime(true);

        foreach ($this->repos as $repo)
        {
            $this->singleRun($repo, $this->baseBranches);
            $run++;
        }

        $timeEnd = microtime(true);
        $timeRun = $timeEnd - $timeStart;

        $this->info($run . " / " . count($this->repos) . ": Completed after " . $timeRun . " seconds!");
    }

    public function singleRun($cron, $baseBranches)
    {
        try {
            $callMngr = new CallManager();
            $members = $callMngr->getTeamMembers($cron['teamId']);
            $pullRequests = $callMngr->getPullRequests($cron['repository'], $cron['days']);

            if (isset($pullRequests) && isset($members) && count($pullRequests) > 0 && count($members) > 0) {

                $filteredPullsByBranch = FilterCallData::filterPullrequestsByBaseBranch($pullRequests, $baseBranches);
                $filteredPullsByMember = FilterCallData::filterPullrequestsByMembers($filteredPullsByBranch, $members);

                foreach ($filteredPullsByMember as $key => $value)
                {
                    $filteredPullsByMember[$key]['location'] = $callMngr->compareCommitWithBranch($cron['repository'], $value['merge_commit_sha']);

                    // entfernt prs, deren location leer ist (also ausserhalb von beta, early, stable)
                    if (!$filteredPullsByMember[$key]['location']) {
                        unset($filteredPullsByMember[$key]);
                        continue;
                    }
                    //if (!$filteredPullsByMember[$key]['location']) $filteredPullsByMember[$key]['location'] = 'others';

                    // speichert in db
                    Pullrequest::create([
                        'repository' => $cron['repository'],
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
            }
        } catch (\Exception $e) {
            logger($e);
        }
    }
}
