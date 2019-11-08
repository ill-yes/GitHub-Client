<?php


namespace App\Discourse\Client;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class DiscourseClient
{
    private static $URL = 'https://forum.plentymarkets.com';

    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('DISCOURSE_KEY');
    }

    public function sendRequest(string $method, string $endpoint, int $page, string $additionalParams = "")
    {
        $url = self::$URL . $endpoint . ".json" . "?api_key=" . $this->apiKey . "&page=" . $page;

        if (strlen($additionalParams))
        {
            $url .= $additionalParams;
        }

        try {
            $client = new Client();
            $response = $client->request($method, $url);

        } catch (RequestException $e)
        {
            $response = $e->getResponse();
        }

        return json_decode($response->getBody(), true);
    }

}
