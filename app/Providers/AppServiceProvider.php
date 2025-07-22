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
        $this->app->bind(
            \App\Domain\User\Repositories\UserRepositoryInterface::class,
            \App\Infrastructure\Persistence\User\Repositories\UserRepository::class
        );
        $this->app->bind(
            \App\Domain\Project\Repositories\ProjectRepositoryInterface::class,
            \App\Infrastructure\Persistence\Project\Repositories\ProjectRepository::class
        );
        $this->app->bind(
            \App\Domain\Project\Repositories\ProjectMemberRepositoryInterface::class,
            \App\Infrastructure\Persistence\Project\Repositories\ProjectMemberRepository::class
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
