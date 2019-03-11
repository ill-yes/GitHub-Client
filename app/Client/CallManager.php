<?php
/**
 * Created by IntelliJ IDEA.
 * User: ilyestascou
 * Date: 05.11.18
 * Time: 17:13
 */

namespace App\Client;

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

    private $client;
    public $username;

    /**
     * CallManager constructor.
     * @param $username
     * @param $password
     */
    function __construct ($token)
    {
        $this->client = new GithubClient($token);
        $this->username = $this->getUsername();
    }

    /**
     * @return string
     */
    public function getUsername ()
    {
        $data = $this->client->requester('/user');
        $jsonData = $this->getDecode($data);

        if ($data->getStatusCode() == 200)
        {
            return $jsonData->login;
        }
        else {
            return null;
        }
    }

    /**
     * @return mixed|null
     */
    public function getUserInfo ()
    {
        $data = $this->client->requester('/users/' . $this->username);
        $jsonData = $this->getDecode($data);

        if ($data->getStatusCode() == 200)
        {
            return $jsonData;
        }
        else {
            return null;
        }
    }

    /**
     * @return mixed|null
     *
     */
    public function getUserOwnRepo ()
    {
        $data = $this->client->requester('/users/' . $this->username . '/repos');
        $jsonData = $this->getDecode($data);

        if (isset($jsonData))
        {
            return $jsonData;
        }
        else
        {
            return null;
        }
    }

    public function getOrgaRepo ()
    {
        $page = 1;
        $results = [];
        $organisation = 'plentymarkets';

        do {
            $data = $this->client->requester('/orgs/' . $organisation . '/repos?page=' . $page);
            $paginationString = $data->getHeaderLine('Link');
            $jsonData = $this->getDecode($data);

            foreach ($jsonData AS $orgaRepo)
            {
                $results[$orgaRepo->name] = true;
            }

            $page++;
        } while (strpos($paginationString, 'rel="next"'));

        ksort($results);

        if (isset($results))
        {
            return $results;
        }
        else
        {
            return null;
        }
    }

    public function getPullRequests (String $repo, int $amountOfPulls)
    {
        $endPage = (int) ceil($amountOfPulls / 100);

        $page = 1;
        $results = [];
        do {
            $data = $this->client->requester('/repos/plentymarkets/'. $repo .'/pulls?state=closed&sort=updated&direction=desc' .
                '&per_page=' . 100 .
                '&page=' . $page);


            $paginationString = $data->getHeaderLine('Link');
            $jsonData = $this->getDecode($data);

            foreach ($jsonData AS $pullRequest)
            {
                $pull['title'] = $pullRequest->title;
                $pull['pr_link'] = $pullRequest->html_url;

                $pull['branch_name'] = $pullRequest->head->ref;
                $pull['branch_commit_sha'] = $pullRequest->head->sha;

                $pull['merged_at'] = Carbon::parse($pullRequest->merged_at)->format('Y-m-d H:i:s');
                $pull['merge_commit_sha'] = $pullRequest->merge_commit_sha;

                $pull['user_login'] = $pullRequest->user->login;
                $pull['user_url'] = $pullRequest->user->html_url;

                $results[] = $pull;
            }

            $page++;
        } while (($endPage == 0) ? strpos($paginationString, 'rel="next"') : $page < $endPage);

        //} while ($page < 1);
        //} while (strpos($paginationString, 'rel="next"'));

        if (isset($results))
        {
            return $results;
        }
        else
        {
            return null;
        }
    }

    public function getBranches($repo)
    {
        /**
         * TODO: Pagination schlecht geloest. Bessere Loesung finden! Einzige Alternative: String parsen.. auch shit!
         * todo: pagination-doWhile als abstrakt auslagern
         * Filter "branch_name" muss in FilterCallData::Class gezogen werden
         */

        $page = 1;
        $results = [];
        do {
            $data = $this->client->requester('/repos/plentymarkets/'. $repo .'/branches?per_page=100&page=' . $page);

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


        if (isset($results))
        {
            return $results;
        }
        else
        {
            return null;
        }
    }

    public function compareCommitWithBranch($repo, $mergedCommitSha)
    {
        $data = $this->client->requester('/repos/plentymarkets/' . $repo . '/compare/' . self::STABLE . '...' . $mergedCommitSha);
        $jsonData = $this->getDecode($data);

        if ($jsonData->status == 'behind' || $jsonData->status == 'identical') return self::STABLE;

        $data = $this->client->requester('/repos/plentymarkets/' . $repo . '/compare/' . self::EARLY . '...' . $mergedCommitSha);
        $jsonData = $this->getDecode($data);

        if ($jsonData->status == 'behind' || $jsonData->status == 'identical') return self::EARLY;

        $data = $this->client->requester('/repos/plentymarkets/' . $repo . '/compare/' . self::BETA . '...' . $mergedCommitSha);
        $jsonData = $this->getDecode($data);

        if ($jsonData->status == 'behind' || $jsonData->status == 'identical') return self::BETA;

        return null;
    }

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

        if (isset($members))
        {
            return $members;
        }
        else
        {
            return null;
        }
    }


    /**
     * @param $data
     * @return mixed
     */
    private function getDecode ($data)
    {
        return json_decode($data->getBody());
    }

}
