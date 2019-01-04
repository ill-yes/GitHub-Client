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

    /**
     * @param $data
     * @return mixed
     */
    private function getDecode ($data)
    {
        return json_decode($data->getBody());
    }

}
