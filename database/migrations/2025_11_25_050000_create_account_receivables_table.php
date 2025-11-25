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
        Schema::create('account_receivables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_submission_id')->constrained()->onDelete('cascade');
            $table->string('ar_number')->unique();
            $table->enum('status', ['pending', 'partial', 'paid'])->default('pending');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('balance', 10, 2);
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ar_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_receivable_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_type'); // 'down_payment', 'partial', 'full'
            $table->text('notes')->nullable();
            $table->timestamp('paid_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ar_payments');
        Schema::dropIfExists('account_receivables');
    }
};
