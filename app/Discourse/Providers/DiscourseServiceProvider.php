<?php

namespace App\Discourse\Providers;

use App\Discourse\Api\Contracts\ApiHandlerRepositoryContract;
use App\Discourse\Api\Repositories\ApiHandlerRepository;
use Illuminate\Support\ServiceProvider;

class DiscourseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ApiHandlerRepositoryContract::class, ApiHandlerRepository::class);
    }
}
