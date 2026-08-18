<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::setOptions('master_prodi', [
            'D2 - Perawatan Mesin Industri Perkebunan',
            'D3 - Akuntansi',
            'D3 - Budidaya Tanaman Perkebunan',
            'D4 - Pengelolaan Perkebunan',
            'D4 - Teknologi Rekayasa Kimia Industri',
            'D4 - Teknologi Rekayasa Mesin Industri Perkebunan',
        ]);

        Setting::setOptions('master_semester', [
            '1', '2', '3', '4', '5', '6', '7', '8',
        ]);

        Setting::setOptions('master_tahun_ajaran', [
            '20241 Ganjil', '20242 Genap', '20251 Ganjil', '20252 Genap',
        ]);
    }
}