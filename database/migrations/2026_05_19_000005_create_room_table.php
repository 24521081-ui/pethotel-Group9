<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room', function (Blueprint $table) {
            $table->id('room_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('type_room_id');
            $table->string('room_number', 20);
            $table->enum('status', ['AVAILABLE', 'IN_USE', 'MAINTENANCE'])->default('AVAILABLE');
            $table->timestamps();
            $table->unique(['branch_id', 'room_number']);
            $table->index('type_room_id');
            $table->index('status');
            $table->foreign('branch_id')->references('branch_id')->on('branch')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('type_room_id')->references('type_room_id')->on('type_room')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room');
    }
};
