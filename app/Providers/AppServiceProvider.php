<?php

namespace App\Providers;

use App\Services\ArticlesServiceInterface;
use App\Services\GetArticlesService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ArticlesServiceInterface::class, GetArticlesService::class);
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
