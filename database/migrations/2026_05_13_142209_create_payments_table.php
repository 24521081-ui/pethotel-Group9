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
        Schema::create('payments', function (Blueprint $table) {
            $table->string('payment_id', 10)->primary();
            $table->string('order_id', 10);

            // Chuyển payment_method và status thành enum
            $table->enum('payment_method', ['CASH', 'BANK_TRANSFER', 'CARD', 'EWALLET']);
            $table->string('provider', 50)->nullable();
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['PENDING', 'SUCCESS', 'FAILED', 'REFUNDED'])->default('PENDING');

            $table->dateTime('paid_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('order_id', 'fk_payments_order')->references('order_id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
