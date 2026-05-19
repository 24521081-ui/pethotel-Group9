<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee', function (Blueprint $table) {
            $table->id('employee_id');
            $table->foreignId('user_id')->unique()->constrained('users')->restrictOnDelete();
            $table->unsignedBigInteger('branch_id');
            $table->string('full_name', 200);
            $table->enum('position', ['GROOMER', 'RECEPTIONIST', 'MANAGER', 'VET', 'CLEANER', 'OTHER']);
            $table->decimal('salary', 12, 2)->default(0);
            $table->string('phone', 20)->nullable();
            $table->date('hire_date')->nullable();
            $table->string('experience', 50)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('branch_id')->references('branch_id')->on('branch')->cascadeOnUpdate()->restrictOnDelete();
        });
        DB::statement('ALTER TABLE employee ADD CONSTRAINT chk_employee_salary CHECK (salary >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('employee');
    }
};
