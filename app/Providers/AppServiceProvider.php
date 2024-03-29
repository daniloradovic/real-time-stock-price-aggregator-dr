<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the StockServiceInterface to the AlphaVantageStockService
        $this->app->bind(
            \App\Contracts\StockServiceInterface::class,
            \App\Services\AlphaVantageStockService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
