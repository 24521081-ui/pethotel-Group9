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
        Schema::create('users', function (Blueprint $table) {
            // Khóa chính và các cột theo thiết kế app_user của bạn
            $table->string('user_id', 10)->primary();
            $table->string('employee_id', 10)->nullable();
            $table->string('customer_id', 10)->nullable();
            $table->string('username', 254)->unique('uq_app_user_username');
            $table->string('password'); // Laravel tự hiểu đây là password_hash

            // Phân quyền theo chuẩn ck_app_user_role
            $table->enum('role_emp', ['0', '1', '2', '3', '4', '5']);

            $table->tinyInteger('is_active')->default(1);
            $table->dateTime('last_login')->nullable();
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('employee_id', 'fk_app_user_employee')->references('employee_id')->on('employee');
            $table->foreign('customer_id', 'fk_app_user_customer')->references('customer_id')->on('customer');
        });

        // -- CÁC BẢNG MẶC ĐỊNH ĐI KÈM CỦA LARAVEL --
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();

            // Đã sửa lại thành string(10) để khớp với user_id ở trên
            $table->string('user_id', 10)->nullable()->index();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
