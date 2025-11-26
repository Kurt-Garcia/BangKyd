<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add new columns and temp column before dropping old ones
        Schema::table('order_progress', function (Blueprint $table) {
            // Add temporary string column to hold stage value during transition
            $table->string('temp_stage')->nullable()->after('current_stage');
            
            // Add combined print & press timestamps
            $table->timestamp('print_press_started_at')->nullable()->after('total_quantity');
            $table->timestamp('print_press_completed_at')->nullable()->after('print_press_started_at');
        });
        
        // Copy current stage values to temp column and map old values to new ones
        DB::statement("
            UPDATE order_progress 
            SET temp_stage = CASE 
                WHEN current_stage = 'printing' THEN 'print_press'
                WHEN current_stage = 'press' THEN 'print_press'
                WHEN current_stage = 'tailoring' THEN 'tailoring'
                WHEN current_stage = 'completed' THEN 'completed'
                ELSE 'print_press'
            END
        ");
        
        // Copy data from old timestamp columns to new columns
        DB::statement('
            UPDATE order_progress 
            SET print_press_started_at = printing_started_at,
                print_press_completed_at = CASE 
                    WHEN press_completed_at IS NOT NULL THEN press_completed_at
                    WHEN printing_completed_at IS NOT NULL THEN printing_completed_at
                    ELSE NULL
                END
        ');
        
        Schema::table('order_progress', function (Blueprint $table) {
            // Remove quantity tracking columns
            $table->dropColumn([
                'printing_done',
                'press_done',
                'tailoring_done'
            ]);
            
            // Remove separate printing and press timestamp columns
            $table->dropColumn([
                'printing_started_at',
                'printing_completed_at',
                'press_started_at',
                'press_completed_at'
            ]);
            
            // Drop the old enum column
            $table->dropColumn('current_stage');
        });
        
        // Rename temp column to current_stage and convert to new enum
        Schema::table('order_progress', function (Blueprint $table) {
            $table->renameColumn('temp_stage', 'current_stage');
        });
        
        Schema::table('order_progress', function (Blueprint $table) {
            // Convert to enum with new values
            $table->enum('current_stage', ['print_press', 'tailoring', 'completed'])->default('print_press')->change();
            
            // Reorder tailoring timestamps
            $table->timestamp('tailoring_started_at')->nullable()->after('print_press_completed_at')->change();
            $table->timestamp('tailoring_completed_at')->nullable()->after('tailoring_started_at')->change();
        });
    }

    public function down(): void
    {
        Schema::table('order_progress', function (Blueprint $table) {
            // Restore quantity tracking columns
            $table->integer('printing_done')->default(0)->after('total_quantity');
            $table->integer('press_done')->default(0)->after('printing_done');
            $table->integer('tailoring_done')->default(0)->after('press_done');
            
            // Restore separate printing and press columns
            $table->timestamp('printing_started_at')->nullable()->after('tailoring_done');
            $table->timestamp('printing_completed_at')->nullable()->after('printing_started_at');
            $table->timestamp('press_started_at')->nullable()->after('printing_completed_at');
            $table->timestamp('press_completed_at')->nullable()->after('press_started_at');
            
            // Remove combined print_press columns
            $table->dropColumn([
                'print_press_started_at',
                'print_press_completed_at'
            ]);
        });
        
        Schema::table('order_progress', function (Blueprint $table) {
            // Restore original enum
            $table->enum('current_stage', ['printing', 'press', 'tailoring', 'completed'])->default('printing')->change();
        });
    }
};
