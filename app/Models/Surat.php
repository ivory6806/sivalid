<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'unit_id', 'nama_surat', 'nomor_surat', 'tanggal_surat', 'nama_mahasiswa', 'nim', 'prodi',
        'semester', 'tahun_ajaran', 
        // 'nama_ayah', 'nama_ibu', 'pekerjaan','alamat_orangtua', 
        'file_surat', 'total_halaman', 'file_disahkan',
        'status', 'catatan_revisi', 'validated_by', 'validated_at', 'qr_token',
        'qr_page', 'qr_pos_x', 'qr_pos_y', 'qr_size_mm',
    ];

    protected function casts(): array
    {
        return ['validated_at' => 'datetime', 'tanggal_surat' => 'date'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Jabatan Kepala yang memvalidasi, sesuai UNIT tempat surat ini berada
     * (karena 1 orang bisa punya jabatan berbeda di tiap unit).
     * Fallback ke kolom `jabatan` lama di users kalau tidak ditemukan.
     */
    public function getValidatorJabatanAttribute(): ?string
    {
        if (!$this->validated_by) {
            return null;
        }

        if ($this->unit_id) {
            $unitUser = UnitUser::where('user_id', $this->validated_by)
                ->where('unit_id', $this->unit_id)
                ->where('role', 'kepala')
                ->first();

            if ($unitUser) {
                return $unitUser->display_label;
            }
        }

        return $this->validator->jabatan ?? null;
    }

    // Ditambahkan untuk kesiapan fitur multi-unit (belum dipakai controller lama)
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // Fallback default (posisi lama, hardcoded, dipertahankan agar surat Akademik existing tidak berubah tampilannya)
    public function getEffectiveQrPageAttribute(): int
    {
        return $this->qr_page ?? 1;
    }

    public function getEffectiveQrPosXAttribute(): float
    {
        return (float) ($this->qr_pos_x ?? 77.14); // % lebar halaman, setara 162mm di kertas A4
    }

    public function getEffectiveQrPosYAttribute(): float
    {
        return (float) ($this->qr_pos_y ?? 80.81); // % tinggi halaman, setara 240mm di kertas A4
    }

    public function getEffectiveQrSizeMmAttribute(): float
    {
        return (float) ($this->qr_size_mm ?? 25);
    }

    public function getIsGenericAttribute(): bool
    {
        return $this->unit && $this->unit->code !== 'akademik';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'diajukan' => 'Diajukan',
            'disetujui' => 'Disetujui',
            'direvisi' => 'Direvisi',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'diajukan' => 'warning',
            'disetujui' => 'success',
            'direvisi' => 'danger',
            default => 'secondary',
        };
    }
}