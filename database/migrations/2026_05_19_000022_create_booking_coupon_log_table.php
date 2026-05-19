<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_coupon_log', function (Blueprint $table) {
            $table->id('booking_coupon_log_id');
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('coupon_id');
            $table->timestamp('applied_at')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['booking_id', 'coupon_id', 'applied_at']);
            $table->foreign('booking_id')->references('booking_id')->on('booking')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('coupon_id')->references('coupon_id')->on('coupon')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_coupon_log');
    }
};
