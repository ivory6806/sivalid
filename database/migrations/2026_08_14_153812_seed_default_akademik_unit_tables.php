<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buat unit default "Akademik" jika belum ada — ini menaungi fitur
        // Surat Keterangan Aktif Kuliah yang sudah berjalan.
        $unitId = DB::table('units')->where('code', 'akademik')->value('id');

        if (!$unitId) {
            $unitId = DB::table('units')->insertGetId([
                'name' => 'Akademik',
                'code' => 'akademik',
                'jenis_dokumen_label' => 'Surat Keterangan Aktif Kuliah',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Backfill: user dengan role lama 'kepala'/'staff' -> masuk ke unit_user unit Akademik
        if (Schema::hasColumn('users', 'role')) {
            $users = DB::table('users')->whereIn('role', ['kepala', 'staff'])->get(['id', 'role', 'is_active']);

            foreach ($users as $u) {
                $exists = DB::table('unit_user')
                    ->where('user_id', $u->id)
                    ->where('unit_id', $unitId)
                    ->where('role', $u->role)
                    ->exists();

                if (!$exists) {
                    DB::table('unit_user')->insert([
                        'user_id' => $u->id,
                        'unit_id' => $unitId,
                        'role' => $u->role,
                        'is_active' => $u->is_active ?? true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        $unitId = DB::table('units')->where('code', 'akademik')->value('id');
        if ($unitId) {
            DB::table('unit_user')->where('unit_id', $unitId)->delete();
            DB::table('units')->where('id', $unitId)->delete();
        }
    }
};
