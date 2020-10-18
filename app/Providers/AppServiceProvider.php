<?php

namespace App\Providers;

use App\Contracts\Interfaces\ResponseInterface;
use App\Contracts\Repositories\JsonResponseRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            ResponseInterface::class, // the logger interface
            JsonResponseRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
