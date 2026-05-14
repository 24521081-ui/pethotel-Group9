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
        Schema::create('booking_room', function (Blueprint $table) {
            $table->string('booking_room_id', 10)->primary();
            $table->string('booking_id', 10);
            $table->string('room_id', 10);
            $table->timestamp('assigned_at')->useCurrent();
            $table->text('note')->nullable();

            $table->foreign('booking_id', 'fk_bkr_booking_id')->references('booking_id')->on('booking');
            $table->foreign('room_id', 'fk_bkr_room_id')->references('room_id')->on('room');
            $table->unique(['booking_id', 'room_id'], 'uq_booking_room');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_rooms');
    }
};
