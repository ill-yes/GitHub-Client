<?php

namespace App\Chatbot\Repositories;

use App\Chatbot\Client\ChatClient;
use App\Chatbot\Contracts\ChatInteractionContract;

class ChatInteractionRepository implements ChatInteractionContract
{

    public function sendText(string $text)
    {
        $client = new ChatClient();
        $response = $client->sendRequest(['text' => $text]);

        return $response;
    }
}
