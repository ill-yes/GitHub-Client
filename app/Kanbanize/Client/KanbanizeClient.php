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

    public function sendRequest(string $endpoint, array $body, $POST )
    {
        try {
            $client = new Client();
            $response = $client->request(self::$URL . $endpoint,
                [
                    'headers' => [
                        'apikey' => $this->apiKey
                    ],
                    'form_params' => $body,
                ]);

        } catch (RequestException $e)
        {
            $response = $e->getResponse();
        }

        return $response;
    }
}
