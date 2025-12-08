<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Event;
use App\Events\PostCreated;
use App\Listeners\LogPostCreated;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::pattern('id', '[0-9]+');

        // ربط الـ Event بالـ Listener
        Event::listen(
            PostCreated::class,
            LogPostCreated::class
        );
    }
}
