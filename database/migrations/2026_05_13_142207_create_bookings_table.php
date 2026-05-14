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
        Schema::create('booking', function (Blueprint $table) {
            $table->string('booking_id', 10)->primary();
            $table->string('customer_id', 10);
            $table->string('branch_id', 10);
            $table->dateTime('checkin_expected_at')->nullable();
            $table->dateTime('checkout_expected_at')->nullable();
            $table->enum('status', ['PENDING', 'CONFIRMED', 'CHECKED_IN', 'CHECKED_OUT', 'CANCELLED'])->default('PENDING');
            $table->decimal('deposit_amount', 12, 2)->nullable();
            $table->text('special_note')->nullable();
            $table->timestamps();

            $table->foreign('customer_id', 'fk_booking_customer')->references('customer_id')->on('customer');
            $table->foreign('branch_id', 'fk_booking_branch')->references('branch_id')->on('branch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
