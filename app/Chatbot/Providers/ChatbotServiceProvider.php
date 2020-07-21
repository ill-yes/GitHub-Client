<?php

namespace App\Chatbot\Providers;

use App\Chatbot\Contracts\ChatInteractionContract;
use App\Chatbot\Repositories\ChatInteractionRepository;
use Illuminate\Support\ServiceProvider;

class ChatbotServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ChatInteractionContract::class, ChatInteractionRepository::class);
    }
}
