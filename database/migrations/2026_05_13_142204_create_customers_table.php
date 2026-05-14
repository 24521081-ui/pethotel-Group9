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
        Schema::create('customer', function (Blueprint $table) {
            $table->string('customer_id', 10)->primary();
            $table->string('user_id', 10)->nullable();
            $table->string('full_name', 120);
            $table->string('email', 254)->nullable()->unique('uq_customer_email');
            $table->string('phone', 20)->unique('uq_customer_phone');
            $table->string('address', 120)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
