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
        Schema::create('orders', function (Blueprint $table) {
            $table->string('order_id', 10)->primary();
            $table->string('customer_id', 10);
            $table->string('branch_id', 10);
            $table->string('booking_id', 10);
            $table->string('created_by_emp', 10);

            // Chuyển status thành enum
            $table->enum('status', ['PENDING', 'PAID', 'PARTIAL', 'CANCELLED', 'REFUNDED'])->default('PENDING');

            $table->decimal('subtotal', 12, 2);
            $table->decimal('grand_total', 12, 2);

            $table->timestamp('created_at')->useCurrent();

            $table->foreign('customer_id', 'fk_orders_customer')->references('customer_id')->on('customer');
            $table->foreign('branch_id', 'fk_orders_branch')->references('branch_id')->on('branch');
            $table->foreign('created_by_emp', 'fk_orders_employee')->references('employee_id')->on('employee');
            // Nếu bạn muốn nối với bảng booking thì có thể thêm dòng này:
            $table->foreign('booking_id', 'fk_orders_booking')->references('booking_id')->on('booking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
