<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sertifikat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uploaded_by', 'jenis_sertifikat', 'nomor_sertifikat',
        'file_asli', 'file_disahkan', 'file_original_name', 'file_type', 'file_size', 'jumlah_halaman',
        'status', 'catatan_revisi',
        'qr_pos_x', 'qr_pos_y', 'qr_size',
        'validated_by', 'validated_at', 'submitted_at',
        'qr_token', 'qr_generated_at',
    ];

    protected function casts(): array
    {
        return [
            'validated_at'    => 'datetime',
            'submitted_at'    => 'datetime',
            'qr_generated_at' => 'datetime',
            'qr_pos_x'        => 'float',
            'qr_pos_y'        => 'float',
            'qr_size'         => 'float',
        ];
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'diajukan'  => 'Diajukan',
            'disetujui' => 'Disetujui',
            'direvisi'  => 'Direvisi',
            default     => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'diajukan'  => 'warning',
            'disetujui' => 'success',
            'direvisi'  => 'danger',
            default     => 'secondary',
        };
    }
}
