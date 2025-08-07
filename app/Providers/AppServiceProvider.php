<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Calendar\Repositories\CalendarEventRepositoryInterface;
use App\Infrastructure\Persistence\Calendar\Repositories\CalendarEventRepository;

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
        $this->app->bind(
            \App\Domain\Project\Repositories\TaskRepositoryInterface::class,
            \App\Infrastructure\Persistence\Project\Repositories\TaskRepository::class
        );
        $this->app->bind(
            \App\Domain\Document\Repositories\DocumentRepositoryInterface::class,
            \App\Infrastructure\Persistence\Document\Repositories\DocumentRepository::class
        );
        $this->app->bind(
            \App\Domain\Project\Repositories\TaskLogRepositoryInterface::class,
            \App\Infrastructure\Persistence\Project\Repositories\TaskLogRepository::class
        );
        $this->app->bind(
            \App\Domain\Project\Repositories\KanbanColumnRepositoryInterface::class,
            \App\Infrastructure\Persistence\Project\Repositories\KanbanColumnRepository::class
        );
        $this->app->bind(
            \App\Domain\Chat\Repositories\ConversationRepositoryInterface::class,
            \App\Infrastructure\Persistence\Chat\Repositories\ConversationRepository::class
        );
        $this->app->bind(
            \App\Domain\Chat\Repositories\MessageRepositoryInterface::class,
            \App\Infrastructure\Persistence\Chat\Repositories\MessageRepository::class
        );
        $this->app->bind(
            \App\Domain\User\Repositories\EmployeeRepositoryInterface::class,
            \App\Infrastructure\Repositories\EmployeeRepository::class
        );
        
        $this->app->bind(
            \App\Domain\User\Repositories\AttendanceRepositoryInterface::class,
            \App\Infrastructure\Repositories\AttendanceRepository::class
        );
        
        $this->app->bind(
            \App\Domain\User\Repositories\LeaveRepositoryInterface::class,
            \App\Infrastructure\Persistence\User\Repositories\LeaveRepository::class
        );

        $this->app->bind(
            \App\Domain\User\Repositories\DashboardRepositoryInterface::class,
            \App\Infrastructure\Repositories\DashboardRepository::class
        );

        $this->app->bind(
            \App\Domain\User\Repositories\ProjectRepositoryInterface::class,
            \App\Infrastructure\Repositories\ProjectRepository::class
        );

        $this->app->bind(
            \App\Domain\User\Repositories\ExpenseRepositoryInterface::class,
            \App\Infrastructure\Repositories\ExpenseRepository::class
        );

        $this->app->bind(
            \App\Domain\User\Repositories\PayrollRepositoryInterface::class,
            \App\Infrastructure\Repositories\PayrollRepository::class
        );

        $this->app->bind(
            \App\Domain\User\Repositories\AnalyticsRepositoryInterface::class,
            \App\Infrastructure\Repositories\AnalyticsRepository::class
        );

        // Bind Calendar Repository
        $this->app->bind(CalendarEventRepositoryInterface::class, CalendarEventRepository::class);

        // Bind Guest Repository
        $this->app->bind(
            \App\Domain\Guest\Repositories\GuestRepositoryInterface::class,
            \App\Infrastructure\Repositories\GuestRepository::class
        );

        // Bind Holiday Repository
        $this->app->bind(
            \App\Domain\Holiday\Repositories\HolidayRepositoryInterface::class,
            \App\Infrastructure\Repositories\HolidayRepository::class
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
