<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ImageStorage\ImageStorageInterface;
use App\Repositories\ImageStorage\PinataImageRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
         $this->app->bind(ImageStorageInterface::class, PinataImageRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
