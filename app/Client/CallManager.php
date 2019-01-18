<?php
/**
 * Created by IntelliJ IDEA.
 * User: ilyestascou
 * Date: 05.11.18
 * Time: 17:13
 */

namespace App\Client;

/**
 * Class CallManager
 * @package App\Client
 */
class CallManager
{
    private $user;
    private $client;

    /**
     * CallManager constructor.
     * @param $username
     * @param $password
     */
    function __construct ($username, $password)
    {
        $token = base64_encode($username . ':' . $password);

        $this->user = new User($username, $password, $token);
        $this->client = new GithubClient($token);
    }

    /**
     * @return string
     */
    public function getUsername ()
    {
        $data = $this->client->requester('/users/' . $this->user->getUsername());
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
        $data = $this->client->requester('/users/' . $this->user->getUsername());
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
        $data = $this->client->requester('/users/' . $this->user->getUsername() . '/repos');
        $jsonData = $this->getDecode($data);

        if ($data->getStatusCode() == 200)
        {
            return $jsonData;
        }
        else {
            return null;
        }
    }

    public function getPullRequests (String $repo, int $endPage)
    {
        $page = 1;
        $results = [];
        do {
            $data = $this->client->requester('/repos/plentymarkets/'. $repo .'/pulls?state=closed&sort=updated&direction=desc&per_page=100&page=' . $page);

            $paginationString = $data->getHeaderLine('Link');
            $jsonData = $this->getDecode($data);

            foreach ($jsonData AS $pullRequest)
            {
                $pull['title'] = $pullRequest->title;
                $pull['branch_name'] = $pullRequest->head->ref;
                $pull['branch_commit_sha'] = $pullRequest->head->sha;
                $pull['pr_link'] = $pullRequest->html_url;
                $pull['merged_at'] = $pullRequest->merged_at;
                $pull['user_login'] = $pullRequest->user->login;

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


    /**
     * @param $data
     * @return mixed
     */
    private function getDecode ($data)
    {
        return json_decode($data->getBody());
    }

}
