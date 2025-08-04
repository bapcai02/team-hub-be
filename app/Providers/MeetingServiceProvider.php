<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Meeting\Repositories\MeetingRepositoryInterface;
use App\Infrastructure\Persistence\Meeting\Repositories\MeetingRepository;

class MeetingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(MeetingRepositoryInterface::class, MeetingRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 