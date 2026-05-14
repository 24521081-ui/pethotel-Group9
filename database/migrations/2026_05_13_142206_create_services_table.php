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
        Schema::create('services', function (Blueprint $table) {
            $table->string('service_id', 10)->primary();
            $table->string('service_category_id', 10);
            $table->string('service_name', 120);
            $table->string('species', 20);
            $table->text('description_sv')->nullable();
            $table->decimal('base_price', 12, 2)->default(0);
            $table->integer('duration_minutes')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->foreign('service_category_id', 'fk_services_category')
                ->references('service_category_id')
                ->on('category_services');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
