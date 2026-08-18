<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // Nama unit, misal: "Perpustakaan"
            $table->string('code', 50)->unique();    // Slug unik, misal: "perpustakaan"
            $table->string('jenis_dokumen_label')->default('Pengajuan Surat'); // Label jenis dokumen unit ini
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
