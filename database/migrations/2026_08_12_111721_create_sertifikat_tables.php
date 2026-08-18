<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->string('jenis_sertifikat');
            $table->string('nomor_sertifikat')->unique();
            $table->string('file_asli');
            $table->string('file_disahkan')->nullable();
            $table->string('file_original_name')->nullable();
            $table->string('file_type', 50)->nullable();
            $table->bigInteger('file_size')->unsigned()->nullable();
            // $table->unsignedInteger('jumlah_halaman')->nullable();

            $table->enum('status', ['diajukan', 'disetujui', 'direvisi'])->default('diajukan');
            $table->text('catatan_revisi')->nullable();

            // Posisi QR yang diatur kepala secara realtime sebelum generate (persen relatif terhadap halaman)
            $table->decimal('qr_pos_x', 5, 2)->nullable();   // persen dari kiri (0-100)
            $table->decimal('qr_pos_y', 5, 2)->nullable();   // persen dari atas (0-100)
            $table->decimal('qr_size', 5, 2)->nullable();    // persen dari lebar halaman

            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->string('qr_token', 64)->unique();
            $table->timestamp('qr_generated_at')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index('uploaded_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikats');
    }
};
