<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Catatan: kolom `role` LAMA di tabel users TIDAK dihapus di migration ini
     * supaya sistem login/role yang sedang berjalan tidak rusak. Kolom `role`
     * akan dipensiunkan pada batch berikutnya (saat alur login & pilih unit
     * dibangun). Untuk sekarang, is_superadmin dipakai berdampingan dengan
     * `role` sebagai penanda superadmin yang baru.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_superadmin')) {
                $table->boolean('is_superadmin')->default(false)->after('jabatan');
            }
        });

        // Backfill: user dengan role lama 'superadmin' -> is_superadmin = true
        if (Schema::hasColumn('users', 'role')) {
            DB::table('users')->where('role', 'superadmin')->update(['is_superadmin' => true]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_superadmin')) {
                $table->dropColumn('is_superadmin');
            }
        });
    }
};
