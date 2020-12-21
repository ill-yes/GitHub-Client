<?php


namespace App\Kanbanize\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class KanbanizeClient
{
    private static $URL = "https://plentymarkets.kanbanize.com/index.php/api/kanbanize/";

    private $apiKey;
    
    public function __construct()
    {
        $this->apiKey = env('KANBANIZE_KEY');
    }

    public function sendRequest(string $method, string $endpoint, array $body)
    {
        try {
            $client = new Client();
            $response = $client->request($method, self::$URL . $endpoint . "/format/json",
                [
                    'headers' => [
                        'apikey' => $this->apiKey
                    ],
                    'json' =>  $body,
                ]);

        } catch (RequestException $e)
        {
            $response = $e->getResponse();
        }

        return json_decode($response->getBody(), true);
    }
}
