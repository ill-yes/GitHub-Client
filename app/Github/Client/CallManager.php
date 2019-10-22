<?php

namespace App\Github\Client;

use Carbon\Carbon;

/**
 * Class CallManager
 * @package App\Client
 */
class CallManager
{
    const STABLE = 'stable7';
    const EARLY = 'early';
    const BETA = 'beta7';
    const ORGANISATION = 'plentymarkets';

    private $client;

    function __construct ()
    {
        $this->client = new GithubClient();
    }

    /**
     * Gets all repositories of an organisation
     *
     * @return array|null
     */
    public function getOrgaRepo ()
    {
        $page = 1;
        $results = [];

        do {
            $data = $this->client->requester('/orgs/' . self::ORGANISATION . '/repos?page=' . $page);
            $paginationString = $data->getHeaderLine('Link');
            $jsonData = $this->getDecode($data);

            foreach ($jsonData AS $orgaRepo)
            {
                $results[$orgaRepo->name] = true;
            }

            $page++;
        } while (strpos($paginationString, 'rel="next"'));

        ksort($results);

        if (count($results))
        {
            return $results;
        }

        return null;
    }

    /**
     * Gets all pullrequests
     *
     * @param String $repo
     * @param int $amountOfDays
     * @return array|null
     */
    public function getPullRequests (String $repo, int $amountOfDays)
    {
        $page = 1;
        $results = [];
        do {
            $data = $this->client->requester('/repos/' . self::ORGANISATION . '/'. $repo .'/pulls?state=closed&sort=updated&direction=desc' .
                '&per_page=' . 100 .
                '&page=' . $page);

            $paginationString = $data->getHeaderLine('Link');
            $jsonData = $this->getDecode($data);
            $inDate = false;

            foreach ($jsonData AS $pullRequest)
            {
                $inDate = $this->dateCalc($pullRequest->merged_at, $amountOfDays);
                if (!$inDate) break;

                $pull['title'] = $pullRequest->title;
                $pull['pr_link'] = $pullRequest->html_url;

                $pull['branch_name'] = $pullRequest->head->ref;
                $pull['branch_commit_sha'] = $pullRequest->head->sha;
                $pull['base_label'] = $pullRequest->base->label;

                $pull['updated_at'] = Carbon::parse($pullRequest->updated_at)->format('Y-m-d H:i:s');
                $pull['merged_at'] = Carbon::parse($pullRequest->merged_at)->format('Y-m-d H:i:s');
                $pull['merge_commit_sha'] = $pullRequest->merge_commit_sha;

                $pull['user_login'] = $pullRequest->user->login;
                $pull['user_url'] = $pullRequest->user->html_url;

                $results[] = $pull;
            }
            $page++;
        } while (strpos($paginationString, 'rel="next"') && $inDate);
        //} while (($endPage == 0) ? strpos($paginationString, 'rel="next"') : $page < $endPage);
        //} while ($page < 1);

        if (count($results))
        {
            return $results;
        }

        return null;
    }

    /**
     * Gets all branches
     *
     * @param $repo
     * @return array|null
     */
    public function getBranches($repo)
    {
        $page = 1;
        $results = [];
        do {
            $data = $this->client->requester('/repos/' . self::ORGANISATION . '/'. $repo .'/branches?per_page=100&page=' . $page);

            $paginationString = $data->getHeaderLine('Link');
            $jsonData = $this->getDecode($data);
            foreach ($jsonData AS $branch)
            {
                $tmpBranch['name'] = $branch->name;
                $tmpBranch['commit_sha'] = $branch->commit->sha;

                $results[] = $tmpBranch;
            }

            $page++;
        } while (strpos($paginationString, 'rel="next"'));


        if (count($results))
        {
            return $results;
        }

        return null;
    }

    /**
     * Returns the location of the merged pullrequest
     *
     * @param $repo
     * @param $mergedCommitSha
     * @return string|null
     */
    public function compareCommitWithBranch($repo, $mergedCommitSha)
    {
        $data = $this->client->requester('/repos/' . self::ORGANISATION . '/' . $repo . '/compare/' . self::STABLE . '...' . $mergedCommitSha);
        $jsonData = $this->getDecode($data);

        if ($jsonData->status == 'behind' || $jsonData->status == 'identical') return self::STABLE;

        $data = $this->client->requester('/repos/' . self::ORGANISATION . '/' . $repo . '/compare/' . self::EARLY . '...' . $mergedCommitSha);
        $jsonData = $this->getDecode($data);

        if ($jsonData->status == 'behind' || $jsonData->status == 'identical') return self::EARLY;

        $data = $this->client->requester('/repos/' . self::ORGANISATION . '/' . $repo . '/compare/' . self::BETA . '...' . $mergedCommitSha);
        $jsonData = $this->getDecode($data);

        if ($jsonData->status == 'behind' || $jsonData->status == 'identical') return self::BETA;

        return null;
    }

    /**
     * Gets a list of team-members
     *
     * @param $teamId
     * @return array|null
     */
    public function getTeamMembers($teamId)
    {
        $page = 1;
        $members = [];
        do {
            $data = $this->client->requester('/teams/' . $teamId . '/members');

            $paginationString = $data->getHeaderLine('Link');
            $jsonData = $this->getDecode($data);

            foreach ($jsonData AS $member)
            {
                $members[$member->login] = true;
            }

            $page++;
        } while (strpos($paginationString, 'rel="next"'));

        if (count($members))
        {
            return $members;
        }

        return null;
    }

    /**
     * Calculates whether the date lies within the specified interval.
     *
     * @param $mergedDate
     * @param int $days
     * @return bool
     */
    private function dateCalc($mergedDate, int $days)
    {
        $date = Carbon::parse($mergedDate)->format('Y-m-d');
        $endDate = Carbon::today()->subDays($days)->format('Y-m-d');

        return $endDate <= $date;
    }

    /**
     * @param $data
     * @return mixed
     */
    private function getDecode($data)
    {
        return json_decode($data->getBody());
    }
}
