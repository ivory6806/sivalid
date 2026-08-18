<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateElement extends Model
{
    protected $fillable = [
        'template_id', 'element_key', 'label',
        'x', 'y', 'font_size', 'color', 'font_family', 'is_bold', 'max_width'
    ];

    protected $casts = [
        'x' => 'integer',
        'y' => 'integer',
        'font_size' => 'integer',
        'is_bold' => 'boolean',
        'max_width' => 'integer',
    ];

    public function template()
    {
        return $this->belongsTo(SertifikatTemplate::class, 'template_id');
    }

    /**
     * Cek apakah key elemen ini termasuk field sistem (bukan dari Excel)
     */
    public function isSystemField(): bool
    {
        return in_array($this->element_key, ['nomor_surat', 'nama_penerima', 'email_penerima', 'tahun_ajaran']);
    }
}