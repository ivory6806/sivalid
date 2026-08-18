<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Ambil semua nilai untuk key tertentu sebagai array
     */
    public static function getOptions($key): array
    {
        return self::where('key', $key)
            ->orderBy('id')
            ->pluck('value')
            ->toArray();
    }

    /**
     * Set ulang opsi (digunakan oleh seeder) - HAPUS semua lalu INSERT baru
     */
    public static function setOptions($key, array $values): void
    {
        // Hapus semua data lama untuk key ini
        self::where('key', $key)->delete();

        // Insert data baru
        foreach ($values as $value) {
            self::create([
                'key'   => $key,
                'value' => $value,
            ]);
        }
    }

    /**
     * Tambah satu opsi baru (dengan cek duplikat)
     */
    public static function addOption($key, $value): self
    {
        // Cek duplikat
        $exists = self::where('key', $key)
            ->where('value', $value)
            ->exists();

        if ($exists) {
            throw new \Exception('Opsi "' . $value . '" sudah ada.');
        }

        return self::create([
            'key'   => $key,
            'value' => $value,
        ]);
    }

    /**
     * Hapus satu opsi
     */
    public static function removeOption($key, $value): bool
    {
        return self::where('key', $key)
            ->where('value', $value)
            ->delete() > 0;
    }
}