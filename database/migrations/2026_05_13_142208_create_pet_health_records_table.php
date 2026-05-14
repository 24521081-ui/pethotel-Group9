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
        Schema::create('pet_health_record', function (Blueprint $table) {
            $table->string('health_record_id', 10)->primary();
            $table->string('pet_id', 10);
            $table->string('booking_id', 10);
            $table->timestamp('recorded_at')->useCurrent(); // Theo SQL gốc
            $table->text('note')->nullable();
            $table->tinyInteger('status')->default(1);

            // Chú ý: Ở đây bạn đặt tên constraint hơi khác (hpr_fk_...), mình giữ nguyên cho giống thiết kế của bạn
            $table->foreign('pet_id', 'fk_hpr_pet_01')->references('pet_id')->on('pet');
            $table->foreign('booking_id', 'fk_hpr_booking_01')->references('booking_id')->on('booking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_health_records');
    }
};
