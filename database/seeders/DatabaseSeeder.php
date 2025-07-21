<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            RolesTableSeeder::class,
            DepartmentsTableSeeder::class,
            EmployeesTableSeeder::class,
            PayrollsTableSeeder::class,
            ContractsTableSeeder::class,
            LeavesTableSeeder::class,
            ProjectsTableSeeder::class,
            ProjectMembersTableSeeder::class,
            TasksTableSeeder::class,
            TaskCommentsTableSeeder::class,
            TaskTagsTableSeeder::class,
            TaskTagAssignmentsTableSeeder::class,
            TaskChecklistsTableSeeder::class,
            TaskAttachmentsTableSeeder::class,
            TaskLogsTableSeeder::class,
            DocumentsTableSeeder::class,
            DocumentBlocksTableSeeder::class,
            DocumentCommentsTableSeeder::class,
            DocumentVersionsTableSeeder::class,
            UploadsTableSeeder::class,
            ConversationsTableSeeder::class,
            ConversationParticipantsTableSeeder::class,
            MessagesTableSeeder::class,
            MessageAttachmentsTableSeeder::class,
            MessageReactionsTableSeeder::class,
            ReportsTableSeeder::class,
            ReportTemplatesTableSeeder::class,
            NotificationsTableSeeder::class,
            AuditLogsTableSeeder::class,
            FavoritesTableSeeder::class,
            AnnouncementsTableSeeder::class,
            FeedbacksTableSeeder::class,
            TodosTableSeeder::class,
            CurrenciesTableSeeder::class,
            SystemSettingsTableSeeder::class,
            TenantUsersTableSeeder::class,
            IntegrationsTableSeeder::class,
            TrainingParticipantsTableSeeder::class,
            MeetingParticipantsTableSeeder::class,
        ]);
    }
}
