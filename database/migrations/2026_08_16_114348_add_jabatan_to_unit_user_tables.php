<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Nama jabatan bisa beda-beda per unit walau role-nya sama ('kepala'),
     * contoh: "Ka. BAA dan Beasiswa" di unit Akademik, "Ka. Prodi TRPL" di unit lain.
     * Kalau kosong, sistem fallback ke label generik "Kepala"/"Staff".
     */
    public function up(): void
    {
        Schema::table('unit_user', function (Blueprint $table) {
            $table->string('jabatan')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('unit_user', function (Blueprint $table) {
            $table->dropColumn('jabatan');
        });
    }
};
