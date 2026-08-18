<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            if (!Schema::hasColumn('surats', 'tanggal_surat')) {
                // Tanggal surat (diisi bebas oleh staff, khusus dokumen unit non-Akademik)
                $table->date('tanggal_surat')->nullable()->after('nomor_surat');
            }
            if (!Schema::hasColumn('surats', 'total_halaman')) {
                $table->unsignedInteger('total_halaman')->nullable()->after('file_surat');
            }
        });
    }

    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropColumn(['tanggal_surat', 'total_halaman']);
        });
    }
};
