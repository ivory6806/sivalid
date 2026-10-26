<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'nip', 'jabatan', 'is_active', 'is_superadmin',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_superadmin' => 'boolean',
        ];
    }

    public function surats()
    {
        return $this->hasMany(Surat::class);
    }

    public function validatedSurats()
    {
        return $this->hasMany(Surat::class, 'validated_by');
    }

    // Keanggotaan unit (kepala/staff) milik user ini, lintas banyak unit
    public function unitMemberships()
    {
        return $this->hasMany(UnitUser::class);
    }

    public function units()
    {
        return $this->belongsToMany(Unit::class, 'unit_user')
            ->withPivot(['id', 'role', 'is_active'])
            ->withTimestamps();
    }

    // Daftar unit tempat user ini jadi Kepala (aktif)
    public function unitsAsKepala()
    {
        return $this->units()->wherePivot('role', 'kepala')->wherePivot('is_active', true);
    }

    // Daftar unit tempat user ini jadi Staff (aktif)
    public function unitsAsStaff()
    {
        return $this->units()->wherePivot('role', 'staff')->wherePivot('is_active', true);
    }

    /**
     * NOTE TRANSISI: isSuperadmin() masih mengecek kolom `role` LAMA di samping
     * `is_superadmin` yang baru, supaya sistem login yang berjalan sekarang
     * tetap konsisten sampai alur login/pilih-unit dirombak di batch berikutnya.
     */
    public function isSuperadmin(): bool { return (bool) $this->is_superadmin || $this->role === 'superadmin'; }
    public function isKepala(): bool { return $this->role === 'kepala'; }
    public function isStaff(): bool { return $this->role === 'staff'; }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'superadmin' => 'Superadmin',
            'kepala' => 'Kepala Staff',
            'staff' => 'Staff',
            default => 'Unknown',
        };
    }

    public function getRoleColorAttribute(): string
    {
        return match ($this->role) {
            'superadmin' => 'warning',
            'kepala' => 'success',
            'staff' => 'info',
            default => 'secondary',
        };
    }

    /**
     * Override pengiriman email reset password.
     * Hanya kirim email jika user statusnya aktif.
     */
    public function sendPasswordResetNotification($token)
    {
        if ($this->is_active) {
            parent::sendPasswordResetNotification($token); // Kirim email
        }
        // Jika is_active = 0, maka tidak melakukan apa-apa (email tidak dikirim)
    }
}