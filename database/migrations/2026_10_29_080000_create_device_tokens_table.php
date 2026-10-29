<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('token');
            // Unique index memakai hash SHA-256 (64 char) agar tidak melebihi
            // batas panjang key MySQL lama (1000 byte pada utf8mb4).
            $table->string('token_hash', 64)->unique();
            $table->string('platform', 20)->default('android');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_tokens');
    }
};
