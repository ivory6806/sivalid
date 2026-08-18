<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Kolom-kolom ini spesifik untuk Surat Keterangan Aktif Kuliah (unit Akademik).
     * Dokumen generik di unit lain tidak mengisinya, jadi harus nullable.
     * Pakai raw SQL (bukan ->change()) supaya tidak butuh package doctrine/dbal.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE surats MODIFY nama_mahasiswa VARCHAR(255) NULL');
        DB::statement('ALTER TABLE surats MODIFY nim VARCHAR(255) NULL');
        DB::statement('ALTER TABLE surats MODIFY prodi VARCHAR(255) NULL');
        DB::statement('ALTER TABLE surats MODIFY semester VARCHAR(255) NULL');
        DB::statement('ALTER TABLE surats MODIFY tahun_ajaran VARCHAR(255) NULL');
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE surats MODIFY nama_mahasiswa VARCHAR(255) NOT NULL DEFAULT ''");
        DB::statement("ALTER TABLE surats MODIFY nim VARCHAR(255) NOT NULL DEFAULT ''");
        DB::statement("ALTER TABLE surats MODIFY prodi VARCHAR(255) NOT NULL DEFAULT ''");
        DB::statement("ALTER TABLE surats MODIFY semester VARCHAR(255) NOT NULL DEFAULT ''");
        DB::statement("ALTER TABLE surats MODIFY tahun_ajaran VARCHAR(255) NOT NULL DEFAULT ''");
    }
};
