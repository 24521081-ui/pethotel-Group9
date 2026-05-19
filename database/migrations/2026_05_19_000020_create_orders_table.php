<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('created_by_emp')->nullable();
            $table->foreignId('created_by_user_id')->constrained('users')->restrictOnDelete();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->enum('payment_method', ['CASH', 'BANK_TRANSFER', 'MOMO', 'VNPAY', 'ZALOPAY', 'OTHER']);
            $table->enum('status', ['PENDING', 'PROCESSING', 'COMPLETED', 'CANCELLED', 'REFUNDED'])->default('PENDING');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
            $table->index('status');
            $table->foreign('customer_id')->references('customer_id')->on('customer')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('branch_id')->references('branch_id')->on('branch')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('booking_id')->references('booking_id')->on('booking')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('created_by_emp')->references('employee_id')->on('employee')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('coupon_id')->references('coupon_id')->on('coupon')->cascadeOnUpdate()->nullOnDelete();
        });
        DB::statement('ALTER TABLE orders ADD CONSTRAINT chk_orders_subtotal CHECK (subtotal >= 0)');
        DB::statement('ALTER TABLE orders ADD CONSTRAINT chk_orders_discount CHECK (discount_amount >= 0)');
        DB::statement('ALTER TABLE orders ADD CONSTRAINT chk_orders_grand_total CHECK (grand_total >= 0)');
        DB::statement('ALTER TABLE orders ADD CONSTRAINT chk_orders_grand_calc CHECK (grand_total = subtotal - discount_amount)');
        DB::statement('ALTER TABLE orders ADD CONSTRAINT chk_orders_paid_at CHECK ((status = \'COMPLETED\' AND paid_at IS NOT NULL) OR status != \'COMPLETED\')');
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
