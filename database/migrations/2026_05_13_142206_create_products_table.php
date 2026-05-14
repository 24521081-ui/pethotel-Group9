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
        Schema::create('product', function (Blueprint $table) {
            $table->string('product_id', 10)->primary();
            $table->string('product_category_id', 10);
            $table->string('product_name', 160);
            $table->string('unit', 30)->nullable();

            // cost_price >= 0 nên có thể dùng unsignedDecimal
            $table->decimal('cost_price', 12, 2);
            $table->timestamps();

            $table->foreign('product_category_id', 'fk_product_category')
                ->references('product_category_id')
                ->on('category_product');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
