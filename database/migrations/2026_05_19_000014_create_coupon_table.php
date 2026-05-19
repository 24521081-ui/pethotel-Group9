<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon', function (Blueprint $table) {
            $table->id('coupon_id');
            $table->string('coupon_code', 50)->unique();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->enum('discount_type', ['FIXED', 'PERCENT'])->default('FIXED');
            $table->decimal('discount_value', 10, 2);
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->decimal('min_order_value', 12, 2)->default(0);
            $table->integer('max_uses')->nullable();
            $table->integer('used_count')->default(0);
            $table->dateTime('effective_from');
            $table->dateTime('expired_at');
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['is_active', 'effective_from', 'expired_at']);
            $table->foreign('employee_id')->references('employee_id')->on('employee')->cascadeOnUpdate()->nullOnDelete();
        });
        DB::statement('ALTER TABLE coupon ADD CONSTRAINT chk_coupon_value CHECK (discount_value > 0)');
        DB::statement('ALTER TABLE coupon ADD CONSTRAINT chk_coupon_percent CHECK (discount_type != \'PERCENT\' OR discount_value <= 100)');
        DB::statement('ALTER TABLE coupon ADD CONSTRAINT chk_coupon_min_order CHECK (min_order_value >= 0)');
        DB::statement('ALTER TABLE coupon ADD CONSTRAINT chk_coupon_max_uses CHECK (max_uses IS NULL OR max_uses > 0)');
        DB::statement('ALTER TABLE coupon ADD CONSTRAINT chk_coupon_used_count CHECK (used_count >= 0)');
        DB::statement('ALTER TABLE coupon ADD CONSTRAINT chk_coupon_date_range CHECK (expired_at > effective_from)');
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon');
    }
};
