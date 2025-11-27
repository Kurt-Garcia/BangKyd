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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // login, create, update, delete, etc.
            $table->string('model')->nullable(); // SalesOrder, Order, AccountReceivable, etc.
            $table->unsignedBigInteger('model_id')->nullable(); // ID of the affected record
            $table->text('description'); // Human-readable description
            $table->json('changes')->nullable(); // Optional: store before/after values
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
