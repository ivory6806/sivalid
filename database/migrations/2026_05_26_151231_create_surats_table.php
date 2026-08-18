<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nomor_surat')->unique();
            $table->string('nama_mahasiswa');
            $table->string('nim');
            $table->string('prodi');
            $table->string('semester');
            $table->string('tahun_ajaran');
            // $table->string('nama_ayah');
            // $table->string('nama_ibu');
            // $table->string('pekerjaan');
            // $table->text('alamat_orangtua');
            $table->string('file_surat');
            $table->string('file_disahkan')->nullable();
            $table->enum('status', ['diajukan', 'disetujui', 'direvisi'])->default('diajukan');
            $table->text('catatan_revisi')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->string('qr_token', 64)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};