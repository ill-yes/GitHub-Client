<?php

namespace Tests\Unit;

use App\Client\CallManager;
use App\Services\FilterCallData;
use Carbon\Carbon;
use Tests\TestCase;

class FilteredPullrequestsTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $cron = [];
        $cron['repository'] = 'php-pl';
        $cron['teamId'] = 2093095;
        $cron['days'] = 5;
        $cron['token'] = env('TOKEN');

        $prBaseBranches['repository'] = [
            'plentymarkets:feature' => true,
            'plentymarkets:early' => true,
            'plentymarkets:beta7' => true,
            'plentymarkets:stable7' => true
        ];

        $callMngr = new CallManager($cron['token'], true);

        // Date Test
        $pulls = $callMngr->getPullRequests($cron['repository'], $cron['days']);

        foreach ($pulls as $pull)
        {
            $date = Carbon::parse($pull['updated_at'])->format('Y-m-d');
            $endDate = Carbon::today()->subDays($cron['days'])->format('Y-m-d');

        }

        // Base Branch Test
        $filteredBranch = FilterCallData::filterPullrequestsByBaseBranch($pulls, $prBaseBranches['repository']);

        foreach ($filteredBranch as $pull)
        {
            $this->assertArrayHasKey($pull['base_label'], $prBaseBranches['repository']);
        }


        // Member Test
        $members = $callMngr->getTeamMembers($cron['teamId']);
        $filteredTeam = FilterCallData::filterPullrequestsByMembers($pulls, $members);

        foreach ($filteredTeam as $pull)
        {
            $this->assertArrayHasKey($pull['user_login'],$members);
        }
    }
}
