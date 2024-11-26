<?php

namespace App\Providers;

use App\Repositories\PostRevisionRepository;
use App\Repositories\IRevisionRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IRevisionRepository::class, PostRevisionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
