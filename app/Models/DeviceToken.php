<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'token_hash',
        'platform',
    ];

    protected static function booted(): void
    {
        static::saving(function (DeviceToken $deviceToken) {
            if ($deviceToken->token) {
                $deviceToken->token_hash = hash('sha256', $deviceToken->token);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
