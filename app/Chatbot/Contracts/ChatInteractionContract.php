<?php

namespace App\Chatbot\Contracts;

interface ChatInteractionContract
{
    public function sendText(string $text);
}
