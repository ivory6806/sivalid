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
            if (!Schema::hasColumn('surats', 'unit_id')) {
                $table->foreignId('unit_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('surats', 'nama_surat')) {
                // Nama/jenis surat bebas — dipakai unit selain Akademik. Untuk Akademik
                // nilainya selalu "Surat Keterangan Aktif Kuliah" (di-backfill di bawah).
                $table->string('nama_surat')->nullable()->after('unit_id');
            }
            // Posisi & halaman QR — disimpan sebagai persentase relatif terhadap ukuran
            // halaman supaya konsisten di berbagai ukuran kertas.
            if (!Schema::hasColumn('surats', 'qr_page')) {
                $table->unsignedInteger('qr_page')->nullable()->after('qr_token');
            }
            if (!Schema::hasColumn('surats', 'qr_pos_x')) {
                $table->decimal('qr_pos_x', 5, 2)->nullable()->after('qr_page'); // % dari lebar halaman
            }
            if (!Schema::hasColumn('surats', 'qr_pos_y')) {
                $table->decimal('qr_pos_y', 5, 2)->nullable()->after('qr_pos_x'); // % dari tinggi halaman
            }
            if (!Schema::hasColumn('surats', 'qr_size_mm')) {
                $table->decimal('qr_size_mm', 5, 2)->nullable()->after('qr_pos_y');
            }
        });

        // Backfill data lama -> unit Akademik + nama surat default
        $akademikId = DB::table('units')->where('code', 'akademik')->value('id');
        if ($akademikId) {
            DB::table('surats')->whereNull('unit_id')->update([
                'unit_id' => $akademikId,
                'nama_surat' => 'Surat Keterangan Aktif Kuliah',
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropConstrainedForeignId('unit_id');
            $table->dropColumn(['nama_surat', 'qr_page', 'qr_pos_x', 'qr_pos_y', 'qr_size_mm']);
        });
    }
};
