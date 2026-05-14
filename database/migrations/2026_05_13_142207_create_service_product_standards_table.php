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
        Schema::create('service_product_standard', function (Blueprint $table) {
            $table->string('standard_id', 10)->primary();
            $table->string('service_id', 10);
            $table->string('product_id', 10);

            // Đã chuyển thành enum theo Check constraint
            $table->enum('species', ['DOG', 'CAT']);
            $table->decimal('min_weight_kg', 5, 2);
            $table->decimal('max_weight_kg', 5, 2);
            $table->decimal('usage_amount', 10, 2);
            $table->enum('usage_unit', ['ML', 'L', 'G', 'KG']);

            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('service_id', 'fk_sps_service')->references('service_id')->on('services');
            $table->foreign('product_id', 'fk_sps_product')->references('product_id')->on('product');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_product_standards');
    }
};
