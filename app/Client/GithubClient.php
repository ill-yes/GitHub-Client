<?php

namespace App\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Class GithubClient
 * @package App\Client
 */
class GithubClient
{
    private static $URL = 'https://api.github.com';

    private $auth;
    private $method;

    /**
     * GithubClient constructor.
     * @param $auth
     * @param bool $token
     */
    function __construct ($auth, bool $token)
    {
        $this->auth = $auth;
        $this->method = "Basic";
        
        if ($token)
        {
            $this->method = "token";
        }
    }

    /**
     * @param $endpoint
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function requester ($endpoint)
    {

        try
        {
            $client = new Client();
            $response = $client->get(self::$URL . $endpoint,
                [
                    'headers' => [
                        'Authorization' => "Token" . ' ' . $this->auth
                    ],
                ]);
        }
        catch (RequestException $exception)
        {
            $response = $exception->getResponse();
        }

        return $response;
    }


}
