<?php

namespace App\Chatbot\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ChatClient
{
    private static $URL = "https://chat.googleapis.com/v1/spaces/AAAAOLZQFGY/messages";

    private $key;
    private $token;

    public function __construct()
    {
        $this->key = env('G_CHAT_KEY');
        $this->token = env('G_CHAT_TOKEN');
    }

    public function sendRequest(array $body)
    {
        try {
            $client = new Client();
            $response = $client->request('POST',
                self::$URL . "?key=$this->key" . "&token=$this->token",
                [
                    'json' => $body
                ]
            );
            $response = $response->getBody();

        } catch (GuzzleException $e) {
            $response = $e->getMessage();
        }

        return json_decode($response, true);
    }

}
