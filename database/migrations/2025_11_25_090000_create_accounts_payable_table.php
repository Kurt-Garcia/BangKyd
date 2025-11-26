<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts_payable', function (Blueprint $table) {
            $table->id();
            $table->string('ap_number')->unique();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->enum('vendor_type', ['printing', 'press'])->comment('Vendor type (usually printing for print & press combined)');
            $table->integer('quantity')->comment('Number of jerseys');
            $table->decimal('price_per_pcs', 10, 2)->comment('Price per piece: 150 for upper, 160 for lower (includes print & press)');
            $table->decimal('total_amount', 10, 2)->comment('quantity * price_per_pcs');
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->comment('total_amount - paid_amount');
            $table->enum('status', ['pending', 'partial', 'paid'])->default('pending');
            $table->date('due_date')->nullable();
            $table->timestamp('paid_at')->nullable()->comment('When fully paid');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts_payable');
    }
};
