<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitUser extends Model
{
    use HasFactory;

    protected $table = 'unit_user';

    protected $fillable = [
        'user_id',
        'unit_id',
        'role',
        'jabatan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function getRoleLabelAttribute(): string
    {
        return $this->role === 'kepala' ? 'Kepala' : 'Staff';
    }

    // Label yang ditampilkan ke user: jabatan custom kalau diisi, kalau tidak fallback ke role generik
    public function getDisplayLabelAttribute(): string
    {
        return $this->jabatan ?: $this->role_label;
    }
}
