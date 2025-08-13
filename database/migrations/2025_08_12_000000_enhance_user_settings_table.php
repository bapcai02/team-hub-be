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
        // Add new fields to user_settings table
        Schema::table('user_settings', function (Blueprint $table) {
            // Profile settings
            $table->string('avatar')->nullable()->after('theme');
            $table->string('phone')->nullable()->after('avatar');
            $table->text('bio')->nullable()->after('phone');
            $table->string('location')->nullable()->after('bio');
            
            // App settings
            $table->enum('layout', ['compact', 'comfortable'])->default('comfortable')->after('location');
            $table->boolean('sidebar_collapsed')->default(false)->after('layout');
            $table->json('dashboard_widgets')->nullable()->after('sidebar_collapsed');
            $table->json('shortcuts')->nullable()->after('dashboard_widgets');
            
            // Security settings
            $table->boolean('two_factor_enabled')->default(false)->after('shortcuts');
            $table->json('login_history')->nullable()->after('two_factor_enabled');
            $table->json('trusted_devices')->nullable()->after('login_history');
            
            // Integration settings
            $table->json('integrations')->nullable()->after('trusted_devices');
            $table->string('calendar_sync')->nullable()->after('integrations');
            $table->string('email_signature')->nullable()->after('calendar_sync');
            
            // Advanced settings
            $table->json('privacy_settings')->nullable()->after('email_signature');
            $table->json('accessibility_settings')->nullable()->after('privacy_settings');
            $table->json('data_export_preferences')->nullable()->after('accessibility_settings');
        });

        // Add new fields to users table for basic profile
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('last_login_at');
            $table->string('phone')->nullable()->after('avatar');
            $table->date('birth_date')->nullable()->after('phone');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('birth_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->dropColumn([
                'avatar', 'phone', 'bio', 'location',
                'layout', 'sidebar_collapsed', 'dashboard_widgets', 'shortcuts',
                'two_factor_enabled', 'login_history', 'trusted_devices',
                'integrations', 'calendar_sync', 'email_signature',
                'privacy_settings', 'accessibility_settings', 'data_export_preferences'
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'phone', 'birth_date', 'gender']);
        });
    }
}; 