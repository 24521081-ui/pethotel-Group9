<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_room_pet', function (Blueprint $table) {
            $table->id('booking_room_pet_id');
            $table->unsignedBigInteger('booking_room_id');
            $table->unsignedBigInteger('pet_id');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['booking_room_id', 'pet_id']);
            $table->foreign('booking_room_id')->references('booking_room_id')->on('booking_room')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('pet_id')->references('pet_id')->on('pet')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_room_pet');
    }
};
