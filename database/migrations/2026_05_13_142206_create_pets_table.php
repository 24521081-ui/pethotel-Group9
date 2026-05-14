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
        Schema::create('pet', function (Blueprint $table) {
            $table->string('pet_id', 10)->primary();
            $table->string('customer_id', 10);
            $table->string('pet_name', 20);
            $table->string('species', 30);
            $table->string('breed', 60)->nullable();
            $table->string('sex', 10)->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->text('special_note')->nullable();
            $table->timestamps();

            $table->foreign('customer_id', 'fk_pet_customer')->references('customer_id')->on('customer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
