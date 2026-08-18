<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class FixSemesterSeeder extends Seeder
{
    public function run()
    {
        // Hapus data semester yang salah (yang mengandung koma)
        Setting::where('key', 'master_semester')
            ->where('value', 'LIKE', '%,%')
            ->delete();

        // Insert per semester
        foreach (range(1, 8) as $sem) {
            Setting::firstOrCreate([
                'key'   => 'master_semester',
                'value' => (string) $sem,
            ]);
        }

        $this->command->info('Semester fixed! Total: ' . Setting::where('key', 'master_semester')->count());
    }
}