<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id('service_id');
            $table->unsignedBigInteger('service_category_id');
            $table->string('service_name', 120);
            $table->enum('species', ['DOG', 'CAT', 'BIRD', 'RABBIT', 'ALL', 'OTHER'])->default('ALL');
            $table->text('description_sv')->nullable();
            $table->decimal('base_price', 12, 2)->default(0);
            $table->integer('duration_minutes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('service_name');
            $table->foreign('service_category_id')->references('service_category_id')->on('category_services')->cascadeOnUpdate()->restrictOnDelete();
        });
        DB::statement('ALTER TABLE services ADD CONSTRAINT chk_services_price CHECK (base_price >= 0)');
        DB::statement('ALTER TABLE services ADD CONSTRAINT chk_services_duration CHECK (duration_minutes IS NULL OR duration_minutes > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
