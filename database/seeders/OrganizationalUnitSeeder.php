<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrganizationalUnit;

class OrganizationalUnitSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // BAGIAN
            ['name' => 'Bagian Umum dan IT', 'code' => 'BAG-UMUM-IT', 'type' => 'bagian'],
            ['name' => 'Bagian Administrasi Akademik dan Beasiswa', 'code' => 'BAG-AAK', 'type' => 'bagian'],
            ['name' => 'Bagian Administrasi Keuangan, SDM, dan Kesekretariatan', 'code' => 'BAG-AKSDM', 'type' => 'bagian'],
            // UNIT
            ['name' => 'Unit CDC dan Alumni', 'code' => 'UNIT-CDC', 'type' => 'unit'],
            ['name' => 'Unit UP2M', 'code' => 'UNIT-UP2M', 'type' => 'unit'],
            ['name' => 'Unit Inkubasi Bisnis dan TEFA', 'code' => 'UNIT-IBT', 'type' => 'unit'],
            ['name' => 'Unit LSP', 'code' => 'UNIT-LSP', 'type' => 'unit'],
            ['name' => 'Unit Kerjasama', 'code' => 'UNIT-KERJASAMA', 'type' => 'unit'],
            ['name' => 'Unit PMB', 'code' => 'UNIT-PMB', 'type' => 'unit'],
            ['name' => 'Unit Kemahasiswaan', 'code' => 'UNIT-KEMAHASISWAAN', 'type' => 'unit'],
            ['name' => 'Unit UPMPI', 'code' => 'UNIT-UPMPI', 'type' => 'unit'],
            ['name' => 'Unit Perpustakaan', 'code' => 'UNIT-PERPUS', 'type' => 'unit'],
            // PRODI
            ['name' => 'Prodi PMPHP', 'code' => 'PRODI-PMPHP', 'type' => 'prodi'],
            ['name' => 'Prodi AKT', 'code' => 'PRODI-AKT', 'type' => 'prodi'],
            ['name' => 'Prodi BTP', 'code' => 'PRODI-BTP', 'type' => 'prodi'],
            ['name' => 'Prodi PPN', 'code' => 'PRODI-PPN', 'type' => 'prodi'],
            ['name' => 'Prodi TRKI', 'code' => 'PRODI-TRKI', 'type' => 'prodi'],
            ['name' => 'Prodi TRMIP', 'code' => 'PRODI-TRMIP', 'type' => 'prodi'],
            ['name' => 'Prodi TRPL', 'code' => 'PRODI-TRPL', 'type' => 'prodi'],
        ];

        foreach ($data as $item) {
            OrganizationalUnit::create($item);
        }

        $this->command->info('Data Unit/Bagian/Prodi berhasil ditambahkan.');
    }
}