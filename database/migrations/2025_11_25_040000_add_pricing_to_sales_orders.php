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
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->decimal('price_per_pcs', 10, 2)->after('so_name')->default(0);
        });

        Schema::table('sales_order_submissions', function (Blueprint $table) {
            $table->integer('total_quantity')->after('players')->default(0);
            $table->decimal('total_amount', 10, 2)->after('total_quantity')->default(0);
            $table->decimal('down_payment', 10, 2)->after('total_amount')->default(0);
            $table->decimal('balance', 10, 2)->after('down_payment')->default(0);
            $table->boolean('is_paid')->after('balance')->default(false);
            $table->timestamp('paid_at')->after('is_paid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_order_submissions', function (Blueprint $table) {
            $table->dropColumn(['total_quantity', 'total_amount', 'down_payment', 'balance', 'is_paid', 'paid_at']);
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropColumn('price_per_pcs');
        });
    }
};
