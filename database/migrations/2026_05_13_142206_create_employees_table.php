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
        Schema::create('employee', function (Blueprint $table) {
            $table->string('employee_id', 10)->primary();
            $table->string('user_id', 10)->nullable();
            $table->string('branch_id', 10);
            $table->string('full_name', 120);
            $table->decimal('salary', 12, 2)->nullable();
            $table->string('email', 254)->nullable()->unique('uq_employee_email');
            $table->string('phone', 20)->unique('uq_employee_phone');
            $table->dateTime('hire_date')->nullable();

            // Ràng buộc trạng thái làm việc
            $table->enum('status_code', ['WORKING', 'ON_LEAVE', 'RESIGNED'])->default('WORKING');

            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('branch_id', 'fk_employee_branch')->references('branch_id')->on('branch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
