<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet', function (Blueprint $table) {
            $table->id('pet_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('pet_name', 100);
            $table->enum('species', ['DOG', 'CAT', 'BIRD', 'RABBIT', 'OTHER']);
            $table->string('breed', 50)->nullable();
            $table->integer('age')->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->text('special_notes')->nullable();
            $table->timestamps();
            $table->index('species');
            $table->foreign('customer_id')->references('customer_id')->on('customer')->cascadeOnUpdate()->cascadeOnDelete();
        });
        DB::statement('ALTER TABLE pet ADD CONSTRAINT chk_pet_age CHECK (age IS NULL OR age >= 0)');
        DB::statement('ALTER TABLE pet ADD CONSTRAINT chk_pet_weight CHECK (weight_kg IS NULL OR weight_kg > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('pet');
    }
};
