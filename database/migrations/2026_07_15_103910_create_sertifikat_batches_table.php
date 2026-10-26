<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikat_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('background_path')->nullable();
            $table->unsignedInteger('width')->default(0);
            $table->unsignedInteger('height')->default(0);
            $table->json('elements')->nullable();
            $table->json('qr_position')->nullable();
            $table->string('nomor_format')->default('SRT/{year}/{month}/{seq}');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('sertifikat_batches', function (Blueprint $table) {
            $table->id();
            $table->string('kegiatan');
            $table->string('penyelenggara');
            $table->string('tempat')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('judul')->nullable();
            $table->foreignId('template_id')->constrained('sertifikat_templates')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->text('catatan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // sertifikats table moved to 2026_08_12_111721_create_sertifikat_tables.php
    }

    public function down(): void
    {
        // Schema::dropIfExists('sertifikats');
        Schema::dropIfExists('sertifikat_batches');
        Schema::dropIfExists('sertifikat_templates');
    }
};