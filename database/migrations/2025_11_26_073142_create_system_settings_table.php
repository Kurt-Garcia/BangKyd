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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, number, image, etc.
            $table->string('group')->default('general'); // general, payment, business
            $table->timestamps();
        });

        // Insert default settings
        DB::table('system_settings')->insert([
            ['key' => 'business_name', 'value' => 'BangKyd ERP', 'type' => 'text', 'group' => 'business', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'business_address', 'value' => '', 'type' => 'textarea', 'group' => 'business', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'business_phone', 'value' => '', 'type' => 'text', 'group' => 'business', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'business_email', 'value' => '', 'type' => 'text', 'group' => 'business', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'gcash_number', 'value' => '09176461305', 'type' => 'text', 'group' => 'payment', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'gcash_name', 'value' => 'Kurt Gwapo', 'type' => 'text', 'group' => 'payment', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'gcash_qr_image', 'value' => 'img/Sample QR.svg', 'type' => 'image', 'group' => 'payment', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'down_payment_percentage', 'value' => '50', 'type' => 'number', 'group' => 'payment', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
