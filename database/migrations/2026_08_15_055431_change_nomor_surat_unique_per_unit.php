<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            // 1. Hapus index lama jika ada
            $indexExists = collect(DB::select(
                "SHOW INDEX FROM surats WHERE Key_name = 'surats_nomor_surat_unique'"
            ))->isNotEmpty();

            if ($indexExists) {
                $table->dropUnique(['nomor_surat']);
            }
        });

        // 2. Tambahkan index composite unik dengan prefix 191 karakter
        // 191 karakter × 4 bytes (utf8mb4) = 764 bytes (aman di bawah batas 767 bytes MySQL lama)
        DB::statement("ALTER TABLE surats ADD UNIQUE INDEX surats_unit_nomor_unique (unit_id, nomor_surat(191))");
    }

    public function down(): void
    {
        // 1. Hapus index composite
        DB::statement("ALTER TABLE surats DROP INDEX surats_unit_nomor_unique");

        // 2. Kembalikan ke index unik global (juga pakai prefix 191 agar tidak error saat rollback)
        DB::statement("ALTER TABLE surats ADD UNIQUE INDEX surats_nomor_surat_unique (nomor_surat(191))");
    }
};