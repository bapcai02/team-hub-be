<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 55. feedbacks
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['bug', 'feature_request', 'general']);
            $table->text('content');
            $table->enum('status', ['new', 'in_progress', 'resolved'])->default('new');
            $table->timestamps();
        });

        // 56. announcements
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('visible_to', ['all', 'department', 'role']);
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });

        // 57. todos
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->date('due_date')->nullable();
            $table->boolean('is_done')->default(false);
            $table->timestamps();
        });

        // 58. api_tokens
        Schema::create('api_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('token');
            $table->dateTime('last_used_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 59. failed_jobs (Laravel default)
        // Schema::create('failed_jobs', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('uuid')->unique();
        //     $table->text('connection');
        //     $table->text('queue');
        //     $table->longText('payload');
        //     $table->longText('exception');
        //     $table->timestamp('failed_at')->useCurrent();
        // });

        // 60. job_batches (Laravel Horizon)
        // Schema::create('job_batches', function (Blueprint $table) {
        //     $table->uuid('id')->primary();
        //     $table->string('name');
        //     $table->text('jobs');
        //     $table->text('pending_jobs');
        //     $table->integer('total_jobs');
        //     $table->integer('failed_jobs');
        //     $table->longText('failed_job_ids');
        //     $table->text('options');
        //     $table->integer('cancelled_at')->nullable();
        //     $table->integer('created_at');
        //     $table->integer('finished_at')->nullable();
        // });

        // 61. system_settings
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->enum('type', ['string', 'int', 'json']);
            $table->timestamp('updated_at')->useCurrent();
        });

        // 62. currencies
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('symbol');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // 63. tenants
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('database_schema');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 64. tenant_users
        Schema::create('tenant_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->string('role');
            $table->timestamp('joined_at')->useCurrent();
        });

        // 65. tenant_settings
        Schema::create('tenant_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('key');
            $table->text('value');
            $table->enum('type', ['string', 'int', 'json']);
            $table->timestamp('updated_at')->useCurrent();
        });

        // 66. integrations
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['slack', 'jira', 'github', 'zoom']);
            $table->json('config');
            $table->enum('status', ['active', 'inactive']);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });

        // 67. email_logs
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('to');
            $table->string('subject');
            $table->text('body');
            $table->string('status')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->text('error_message')->nullable();
        });

        // 68. login_activities
        Schema::create('login_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('ip_address')->nullable();
            $table->string('device')->nullable();
            $table->dateTime('login_at')->nullable();
            $table->dateTime('logout_at')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
