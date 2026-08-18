<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['kepala', 'staff']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // 1 user bisa punya banyak baris (banyak unit / role),
            // tapi tidak boleh dobel di kombinasi unit+role yang sama.
            $table->unique(['user_id', 'unit_id', 'role'], 'unit_user_unique');
            $table->index('role');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_user');
    }
};
