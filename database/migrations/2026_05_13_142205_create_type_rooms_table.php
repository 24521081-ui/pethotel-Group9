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
        Schema::create('type_room', function (Blueprint $table) {
            $table->string('type_room_id', 10)->primary();

            // Đã chuyển thành enum theo ck_type_room_name
            $table->enum('type_name', ['STANDARD', 'PREMIUM', 'SUITE']);

            $table->text('note')->nullable();
            $table->integer('max_pets');
            $table->decimal('max_weight_kg', 5, 2)->nullable();
            $table->decimal('base_price_per_day', 12, 2);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_rooms');
    }
};
