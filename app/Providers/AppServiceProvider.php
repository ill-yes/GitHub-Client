<?php

namespace App\Providers;

use App\Discourse\Providers\DiscourseServiceProvider;
use App\Kanbanize\Providers\KanbanizeServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(KanbanizeServiceProvider::class);
        $this->app->register(DiscourseServiceProvider::class);
    }
}
