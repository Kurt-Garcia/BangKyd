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
        Schema::rename('purchase_orders', 'sales_orders');
        Schema::rename('order_submissions', 'sales_order_submissions');
        
        Schema::table('sales_order_submissions', function (Blueprint $table) {
            $table->renameColumn('purchase_order_id', 'sales_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_order_submissions', function (Blueprint $table) {
            $table->renameColumn('sales_order_id', 'purchase_order_id');
        });
        
        Schema::rename('sales_order_submissions', 'order_submissions');
        Schema::rename('sales_orders', 'purchase_orders');
    }
};
