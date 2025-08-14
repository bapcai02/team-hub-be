<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Calendar\Repositories\CalendarEventRepositoryInterface;
use App\Infrastructure\Persistence\Calendar\Repositories\CalendarEventRepository;

class CalendarServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CalendarEventRepositoryInterface::class, CalendarEventRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 