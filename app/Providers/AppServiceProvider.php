<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Calendar\Repositories\CalendarEventRepositoryInterface;
use App\Infrastructure\Persistence\Calendar\Repositories\CalendarEventRepository;
use App\Domain\Guest\Repositories\GuestRepositoryInterface;
use App\Infrastructure\Repositories\GuestRepository;
use App\Domain\Holiday\Repositories\HolidayRepositoryInterface;
use App\Infrastructure\Repositories\HolidayRepository;
use App\Domain\Notification\Repositories\NotificationRepositoryInterface;
use App\Domain\Notification\Repositories\NotificationPreferenceRepositoryInterface;
use App\Domain\Notification\Repositories\NotificationTemplateRepositoryInterface;
use App\Infrastructure\Repositories\Notification\NotificationRepository;
use App\Infrastructure\Repositories\Notification\NotificationPreferenceRepository;
use App\Infrastructure\Repositories\Notification\NotificationTemplateRepository;

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
        $this->app->bind(GuestRepositoryInterface::class, GuestRepository::class);

        // Bind Holiday Repository
        $this->app->bind(HolidayRepositoryInterface::class, HolidayRepository::class);
        
        // Notification Repository Bindings
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(NotificationPreferenceRepositoryInterface::class, NotificationPreferenceRepository::class);
        $this->app->bind(NotificationTemplateRepositoryInterface::class, NotificationTemplateRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
