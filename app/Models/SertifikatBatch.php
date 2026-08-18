<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SertifikatBatch extends Model
{
    use SoftDeletes;

    protected $table = 'sertifikat_batches';

    protected $fillable = [
        'kegiatan',
        'penyelenggara',
        'tempat',
        'tanggal_mulai',
        'tanggal_selesai',
        'judul',
        'template_id',
        'user_id',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function template()
    {
        return $this->belongsTo(SertifikatTemplate::class, 'template_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sertifikats()
    {
        return $this->hasMany(Sertifikat::class, 'batch_id');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'draft'     => '<span class="badge-status" style="background:#f1f5f9;color:#64748b">Draft</span>',
            'submitted' => '<span class="badge-status" style="background:#fffbeb;color:#d97706">Diajukan</span>',
            'approved'  => '<span class="badge-status" style="background:#ecfdf5;color:#059669">Disetujui</span>',
            'rejected'  => '<span class="badge-status" style="background:#fef2f2;color:#ef4444">Ditolak</span>',
            default     => $this->status,
        };
    }
}