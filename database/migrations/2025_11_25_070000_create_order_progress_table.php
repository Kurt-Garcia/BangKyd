<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('unique_link')->unique();
            $table->enum('current_stage', ['printing', 'press', 'tailoring', 'completed'])->default('printing');
            
            // Progress tracking
            $table->integer('total_quantity');
            $table->integer('printing_done')->default(0);
            $table->integer('press_done')->default(0);
            $table->integer('tailoring_done')->default(0);
            
            // Timestamps for each stage
            $table->timestamp('printing_started_at')->nullable();
            $table->timestamp('printing_completed_at')->nullable();
            $table->timestamp('press_started_at')->nullable();
            $table->timestamp('press_completed_at')->nullable();
            $table->timestamp('tailoring_started_at')->nullable();
            $table->timestamp('tailoring_completed_at')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_progress');
    }
};
