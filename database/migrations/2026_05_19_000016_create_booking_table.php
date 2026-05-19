<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id('booking_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('branch_id');
            $table->dateTime('checkin_expected_at');
            $table->dateTime('checkout_expected_at');
            $table->dateTime('checkin_actual_at')->nullable();
            $table->dateTime('checkout_actual_at')->nullable();
            $table->enum('status', ['PENDING', 'CONFIRMED', 'CHECKED_IN', 'CHECKED_OUT', 'COMPLETED', 'CANCELLED'])->default('PENDING');
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->text('special_notes')->nullable();
            $table->timestamps();
            $table->index('status');
            $table->index('checkin_expected_at');
            $table->foreign('customer_id')->references('customer_id')->on('customer')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('branch_id')->references('branch_id')->on('branch')->cascadeOnUpdate()->restrictOnDelete();
        });
        DB::statement('ALTER TABLE booking ADD CONSTRAINT chk_booking_amount CHECK (total_amount IS NULL OR total_amount >= 0)');
        DB::statement('ALTER TABLE booking ADD CONSTRAINT chk_booking_time_range CHECK (checkout_expected_at > checkin_expected_at)');
    }

    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
