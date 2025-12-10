<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use App\Events\PostCreated;
use App\Listeners\LogPostCreated;
use App\Models\Post;
use App\Policies\PostPolicy;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\EloquentUserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
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

        // تسجيل الـ Policy للمقالات
        Gate::policy(Post::class, PostPolicy::class);
    }

}
