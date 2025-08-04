#!/bin/bash

echo "🐳 Running database seeding in Docker..."

# Run database migrations for finance tables
echo "📦 Running finance migrations..."
docker exec laravel_app php artisan migrate --path=database/migrations/2025_08_03_144602_create_salary_components_table.php
docker exec laravel_app php artisan migrate --path=database/migrations/2025_08_03_144537_create_expenses_table.php

# Drop and recreate payrolls table
echo "🔄 Recreating payrolls table..."
docker exec laravel_app php artisan tinker --execute="Schema::dropIfExists('payrolls');"
docker exec laravel_app php artisan migrate --path=database/migrations/2025_08_03_144525_create_payrolls_table.php

# Run seeders in order
echo "🌿 Running seeders..."
docker exec laravel_app php artisan db:seed --class=DepartmentsTableSeeder
docker exec laravel_app php artisan db:seed --class=EmployeesTableSeeder
docker exec laravel_app php artisan db:seed --class=SalaryComponentSeeder
docker exec laravel_app php artisan db:seed --class=ExpenseSeeder
docker exec laravel_app php artisan db:seed --class=PayrollsTableSeeder

echo "✅ Database seeding completed!"
echo ""
echo "📊 Sample data created:"
echo "   - 10 Salary Components (Allowances, Deductions, Bonuses)"
echo "   - 10 Expenses (Various types and statuses)"
echo "   - 10 Payrolls (Different periods and statuses)"
echo ""
echo "🚀 You can now test the finance modules!" 