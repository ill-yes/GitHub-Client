<?php

namespace App\Kanbanize\Providers;

use App\Kanbanize\Contracts\KanbanizeHandlerContract;
use App\Kanbanize\Repositories\KanbanizeHandlerRepository;
use Illuminate\Support\ServiceProvider;

class KanbanizeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(KanbanizeHandlerContract::class, KanbanizeHandlerRepository::class);
    }
}
