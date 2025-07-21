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
        // 31. reports
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['project', 'task', 'time', 'employee']);
            $table->json('filters')->nullable();
            $table->unsignedBigInteger('generated_by');
            $table->dateTime('generated_at');
            $table->text('file_path');
            $table->timestamps();
        });

        // 32. report_templates
        Schema::create('report_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['project', 'task', 'time', 'employee']);
            $table->json('structure');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });

        // 33. notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type');
            $table->json('data')->nullable();
            $table->dateTime('read_at')->nullable();
            $table->timestamps();
        });

        // 34. audit_logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('action');
            $table->string('target_table');
            $table->unsignedBigInteger('target_id');
            $table->json('data')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 35. user_settings
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('language')->default('en');
            $table->string('timezone')->default('UTC');
            $table->json('notification_preferences')->nullable();
            $table->enum('theme', ['light', 'dark'])->default('light');
            $table->timestamps();
        });

        // 36. search_histories
        Schema::create('search_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('query');
            $table->json('filters')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 37. favorites
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['task', 'project', 'document', 'chat']);
            $table->unsignedBigInteger('target_id');
            $table->timestamp('created_at')->useCurrent();
        });

        // 38. widgets
        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('dashboard_id');
            $table->enum('type', ['chart', 'table', 'stat']);
            $table->json('config');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 39. dashboard_layouts
        Schema::create('dashboard_layouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->json('layout_config');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // 40. webhooks
        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('url');
            $table->enum('event', ['task.created', 'payroll.generated']);
            $table->string('secret');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_report_mamament');
    }
};
