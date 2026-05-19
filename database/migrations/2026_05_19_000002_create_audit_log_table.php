<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_log', function (Blueprint $table) {
            $table->id('audit_id');
            $table->string('table_name', 128);
            $table->enum('action_type', ['INSERT', 'UPDATE', 'DELETE']);
            $table->string('row_pk', 255);
            $table->text('detail_text')->nullable();
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('changed_at')->useCurrent();
            $table->index(['table_name', 'changed_at']);
        });
        DB::statement('ALTER TABLE audit_log AUTO_INCREMENT = 1');
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_log');
    }
};
