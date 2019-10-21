<?php


namespace App\Kanbanize\Providers;


use App\Kanbanize\Contracts\ApiHandlerRepositoryContract;
use App\Kanbanize\Repositories\ApiHandlerRepository;
use Illuminate\Support\ServiceProvider;

class KanbanizeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ApiHandlerRepositoryContract::class, ApiHandlerRepository::class);
    }
}
