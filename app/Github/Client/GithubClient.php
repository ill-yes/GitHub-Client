<?php

namespace App\Github\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Class GithubClient
 * @package App\Client
 */
class GithubClient
{
    private static $URL = 'https://api.github.com';

    private $apiKey;


    function __construct ()
    {
        $this->apiKey = env('TOKEN');
    }

    /**
     * @param $endpoint
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function requester ($endpoint)
    {
        try
        {
            $client = new Client();
            $response = $client->get(self::$URL . $endpoint,
                [
                    'headers' => [
                        'Authorization' => "Token" . ' ' . $this->apiKey
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
