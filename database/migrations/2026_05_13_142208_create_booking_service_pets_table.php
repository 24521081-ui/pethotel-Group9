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
        Schema::create('booking_services_pet', function (Blueprint $table) {
            $table->string('booking_service_id', 10)->primary();
            $table->string('booking_id', 10);
            $table->string('service_id', 10)->nullable();
            $table->string('employee_id', 10)->nullable();
            $table->string('pet_id', 10);
            $table->dateTime('scheduled_at')->nullable();

            // Trạng thái của dịch vụ (VD: PENDING, IN_PROGRESS, COMPLETED)
            $table->string('status', 20);

            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('booking_id', 'fk_bks_booking')->references('booking_id')->on('booking');
            $table->foreign('service_id', 'fk_bks_service')->references('service_id')->on('services');
            $table->foreign('employee_id', 'fk_bks_employee')->references('employee_id')->on('employee');
            $table->foreign('pet_id', 'fk_bks_pet')->references('pet_id')->on('pet');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_service_pets');
    }
};
