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
        Schema::create('branch', function (Blueprint $table) {
            $table->string('branch_id', 10)->primary();
            $table->string('branch_name', 120);
            $table->string('phone', 20)->nullable();
            $table->string('email', 254)->nullable()->unique('uq_branch_email');
            $table->string('address', 120);
            $table->tinyInteger('is_active')->default(1);
            // Lưu ý: Ràng buộc CHECK (is_active IN (0,1)) sẽ được xử lý ở FormRequest khi validate
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
