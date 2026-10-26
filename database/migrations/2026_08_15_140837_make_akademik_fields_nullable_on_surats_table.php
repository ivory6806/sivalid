<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom-kolom ini spesifik untuk Surat Keterangan Aktif Kuliah (unit Akademik).
     * Dokumen generik di unit lain tidak mengisinya, jadi harus nullable.
     * Pakai raw SQL (bukan ->change()) supaya tidak butuh package doctrine/dbal.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            Schema::table('surats', function ($table) {
                $table->string('nama_mahasiswa')->nullable()->change();
                $table->string('nim')->nullable()->change();
                $table->string('prodi')->nullable()->change();
                $table->string('semester')->nullable()->change();
                $table->string('tahun_ajaran')->nullable()->change();
            });

            return;
        }

        DB::statement('ALTER TABLE surats MODIFY nama_mahasiswa VARCHAR(255) NULL');
        DB::statement('ALTER TABLE surats MODIFY nim VARCHAR(255) NULL');
        DB::statement('ALTER TABLE surats MODIFY prodi VARCHAR(255) NULL');
        DB::statement('ALTER TABLE surats MODIFY semester VARCHAR(255) NULL');
        DB::statement('ALTER TABLE surats MODIFY tahun_ajaran VARCHAR(255) NULL');
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            Schema::table('surats', function ($table) {
                $table->string('nama_mahasiswa')->nullable(false)->default('')->change();
                $table->string('nim')->nullable(false)->default('')->change();
                $table->string('prodi')->nullable(false)->default('')->change();
                $table->string('semester')->nullable(false)->default('')->change();
                $table->string('tahun_ajaran')->nullable(false)->default('')->change();
            });

            return;
        }

        DB::statement("ALTER TABLE surats MODIFY nama_mahasiswa VARCHAR(255) NOT NULL DEFAULT ''");
        DB::statement("ALTER TABLE surats MODIFY nim VARCHAR(255) NOT NULL DEFAULT ''");
        DB::statement("ALTER TABLE surats MODIFY prodi VARCHAR(255) NOT NULL DEFAULT ''");
        DB::statement("ALTER TABLE surats MODIFY semester VARCHAR(255) NOT NULL DEFAULT ''");
        DB::statement("ALTER TABLE surats MODIFY tahun_ajaran VARCHAR(255) NOT NULL DEFAULT ''");
    }
};
