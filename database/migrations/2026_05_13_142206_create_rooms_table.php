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
        Schema::create('room', function (Blueprint $table) {
            $table->string('room_id', 10)->primary();
            $table->string('branch_id', 10);
            $table->string('type_room_id', 10);
            $table->string('room_number', 10);
            $table->enum('status', ['AVAILABLE', 'IN_USE', 'MAINTENANCE'])->default('AVAILABLE');
            $table->timestamp('created_at')->useCurrent(); // Bảng này SQL của bạn không có updated_at

            $table->foreign('branch_id', 'fk_room_branch')->references('branch_id')->on('branch');
            $table->foreign('type_room_id', 'fk_room_type')->references('type_room_id')->on('type_room');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
