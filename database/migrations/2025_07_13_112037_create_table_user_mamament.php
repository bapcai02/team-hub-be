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
        // 1. users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended']);
            $table->dateTime('last_login_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // 4. role_user
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('user_id');
        });

        // 5. permission_role
        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
        });

        // 6. departments
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 7. employees
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('department_id');
            $table->string('position');
            $table->decimal('salary', 15, 2);
            $table->enum('contract_type', ['full-time', 'part-time', 'intern']);
            $table->date('hire_date');
            $table->date('dob');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('phone');
            $table->string('address');
            $table->timestamps();
            $table->softDeletes();
        });

        // 8. time_logs
        Schema::create('time_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->dateTime('check_in');
            $table->dateTime('check_out')->nullable();
            $table->date('date');
            $table->timestamps();
        });

        // 9. leaves
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->enum('type', ['paid', 'unpaid', 'sick', 'remote', 'comp-off']);
            $table->date('date_from');
            $table->date('date_to');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
        });

        // 10. payrolls
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('month');
            $table->decimal('base_salary', 15, 2);
            $table->decimal('allowance', 15, 2)->default(0);
            $table->decimal('deduction', 15, 2)->default(0);
            $table->decimal('net_salary', 15, 2);
            $table->enum('status', ['pending', 'approved', 'paid']);
            $table->dateTime('generated_at');
            $table->timestamps();
        });

        // 11. contracts
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->enum('contract_type', ['probation', 'fixed-term', 'permanent']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('salary', 15, 2);
            $table->enum('status', ['active', 'expired', 'terminated']);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 12. salary_histories
        Schema::create('salary_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('effective_date');
            $table->decimal('old_salary', 15, 2);
            $table->decimal('new_salary', 15, 2);
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });

        // 13. employee_evaluations
        Schema::create('employee_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('evaluator_id');
            $table->string('period');
            $table->decimal('score', 5, 2);
            $table->text('feedback')->nullable();
            $table->timestamps();
        });

        // 14. skills
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 15. employee_skills
        Schema::create('employee_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('skill_id');
            $table->tinyInteger('level');
            $table->timestamps();
        });

        // 16. trainings
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('trainer_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('location');
            $table->timestamps();
        });

        // 17. training_participants
        Schema::create('training_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('training_id');
            $table->unsignedBigInteger('employee_id');
            $table->enum('status', ['enrolled', 'completed']);
            $table->decimal('score', 5, 2)->nullable();
            $table->timestamps();
        });

        // 18. rewards
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('title');
            $table->text('reason')->nullable();
            $table->decimal('amount', 15, 2);
            $table->date('date_awarded');
            $table->timestamps();
        });

        // 19. disciplinary_actions
        Schema::create('disciplinary_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->enum('type', ['warning', 'suspension', 'termination']);
            $table->text('reason');
            $table->date('date');
            $table->timestamps();
        });

        // 20. resignations
        Schema::create('resignations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->text('reason')->nullable();
            $table->date('resignation_date');
            $table->date('last_working_day');
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
