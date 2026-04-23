<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('sales_order_submission_player_checks');

        Schema::create('sales_order_submission_player_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_submission_id');
            $table->unsignedInteger('player_index');
            $table->boolean('is_done')->default(false);
            $table->timestamp('done_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('sales_order_submission_id', 'fk_sos_pc_sos')
                ->references('id')
                ->on('sales_order_submissions')
                ->onDelete('cascade');
            $table->unique(['sales_order_submission_id', 'player_index'], 'sos_player_checks_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_order_submission_player_checks');
    }
};
