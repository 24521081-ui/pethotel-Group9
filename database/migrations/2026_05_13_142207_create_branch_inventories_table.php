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
        Schema::create('branch_inventory', function (Blueprint $table) {
            $table->string('branch_id', 10);
            $table->string('product_id', 10);
            $table->integer('quantity_in_stock')->default(0);
            $table->integer('reorder_point')->nullable();

            // Cập nhật timestamp tự động khi có thay đổi (tương đương ON UPDATE CURRENT_TIMESTAMP)
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();

            // Khóa chính kép
            $table->primary(['branch_id', 'product_id'], 'pk_branch_inventory');

            $table->foreign('branch_id', 'fk_inventory_branch')->references('branch_id')->on('branch');
            $table->foreign('product_id', 'fk_inventory_product')->references('product_id')->on('product');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_inventory');
    }
};
