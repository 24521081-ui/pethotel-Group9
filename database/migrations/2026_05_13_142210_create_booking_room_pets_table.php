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
        Schema::create('booking_room_pet', function (Blueprint $table) {
            $table->string('booking_room_id', 10);
            $table->string('pet_id', 10);
            $table->timestamp('assigned_at')->useCurrent();
            $table->text('note')->nullable();

            $table->primary(['booking_room_id', 'pet_id'], 'pk_brp');
            $table->foreign('pet_id', 'fk_brp_pet')->references('pet_id')->on('pet');
            $table->foreign('booking_room_id', 'fk_brp_booking_room')->references('booking_room_id')->on('booking_room');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_room_pets');
    }
};
