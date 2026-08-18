<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'jenis_dokumen_label',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Semua baris keanggotaan (kepala & staff) di unit ini
    public function members(): HasMany
    {
        return $this->hasMany(UnitUser::class);
    }

    // User yang berperan sebagai Kepala di unit ini (aktif)
    public function kepalas(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'unit_user')
            ->wherePivot('role', 'kepala')
            ->wherePivot('is_active', true)
            ->withPivot(['id', 'role', 'is_active'])
            ->withTimestamps();
    }

    // User yang berperan sebagai Staff di unit ini (aktif)
    public function staffs(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'unit_user')
            ->wherePivot('role', 'staff')
            ->wherePivot('is_active', true)
            ->withPivot(['id', 'role', 'is_active'])
            ->withTimestamps();
    }

    public function surats(): HasMany
    {
        return $this->hasMany(Surat::class);
    }

    public function getIsAkademikAttribute(): bool
    {
        return $this->code === 'akademik';
    }
}
