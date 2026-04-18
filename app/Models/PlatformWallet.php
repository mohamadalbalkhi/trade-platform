<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformWallet extends Model
{
    protected $fillable = [
        'name',
        'wallet_address',
        'private_key',
        'network',
        'is_active',
        'is_main',
    ];

    protected $hidden = [
        'private_key',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_main' => 'boolean',
        ];
    }
}