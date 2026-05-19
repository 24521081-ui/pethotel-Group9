<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_product_detail', function (Blueprint $table) {
            $table->id('service_product_detail_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('amount', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['service_id', 'product_id']);
            $table->foreign('service_id')->references('service_id')->on('services')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('product_id')->references('product_id')->on('product')->cascadeOnUpdate()->restrictOnDelete();
        });
        DB::statement('ALTER TABLE service_product_detail ADD CONSTRAINT chk_spd_amount CHECK (amount > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('service_product_detail');
    }
};
