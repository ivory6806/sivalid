<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SertifikatTemplate extends Model
{
    use SoftDeletes;

    protected $table = 'sertifikat_templates';

    protected $fillable = [
        'name',
        'background_path',
        'width',
        'height',
        'elements',
        'qr_position',
        'nomor_format',
    ];

    protected $casts = [
        'elements'    => 'array',
        'qr_position' => 'array',
    ];
}