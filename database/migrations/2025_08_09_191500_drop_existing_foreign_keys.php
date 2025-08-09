<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Get all foreign keys from information_schema
        $foreignKeys = DB::select("
            SELECT 
                TABLE_NAME,
                CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE REFERENCED_TABLE_SCHEMA = DATABASE()
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        // Drop each foreign key
        foreach ($foreignKeys as $fk) {
            try {
                DB::statement("ALTER TABLE `{$fk->TABLE_NAME}` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
                echo "Dropped foreign key: {$fk->CONSTRAINT_NAME} from table {$fk->TABLE_NAME}\n";
            } catch (Exception $e) {
                echo "Failed to drop {$fk->CONSTRAINT_NAME}: " . $e->getMessage() . "\n";
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't recreate foreign keys - keep flexible
    }
};