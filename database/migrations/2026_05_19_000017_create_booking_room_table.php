<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_room', function (Blueprint $table) {
            $table->id('booking_room_id');
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('room_id');
            $table->dateTime('assigned_at');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['booking_id', 'room_id']);
            $table->foreign('booking_id')->references('booking_id')->on('booking')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('room_id')->references('room_id')->on('room')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_room');
    }
};
