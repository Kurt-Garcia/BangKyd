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
        // First drop the enum constraint if it's MySQL/MariaDB
        DB::statement('ALTER TABLE accounts_payable MODIFY vendor_type VARCHAR(255)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE accounts_payable MODIFY vendor_type ENUM('printing', 'press')");
    }
};

