<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_service_pet', function (Blueprint $table) {
            $table->id('booking_service_pet_id');
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('pet_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->dateTime('scheduled_at');
            $table->enum('status', ['PENDING', 'ASSIGNED', 'IN_PROGRESS', 'DONE', 'CANCELLED'])->default('PENDING');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('status');
            $table->foreign('booking_id')->references('booking_id')->on('booking')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('pet_id')->references('pet_id')->on('pet')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('service_id')->references('service_id')->on('services')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('employee_id')->references('employee_id')->on('employee')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_service_pet');
    }
};
