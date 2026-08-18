<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Superadmin',
            'email' => 'ivoryalvadiaskurniawan@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'nip' => '000000000',
            'jabatan' => 'Administrator Sistem',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Arif Hidayat',
            'email' => 'arh@polteklpp.ac.id',
            'password' => Hash::make('password'),
            'role' => 'kepala',
            'nip' => '197505152000121001',
            'jabatan' => 'Kepala BAA dan Beasiswa',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Staff BAA',
            'email' => 'baak@polteklpp.ac.id',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'nip' => '199001012020122001',
            'jabatan' => 'Staff Administrasi Akademik',
            'is_active' => true,
        ]);
    }
}