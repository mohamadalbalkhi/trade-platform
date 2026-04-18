<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',

        'account_id',
        'referral_code',
        'referred_by',

        'verification_status',
        'preferred_language',

        'deposit_trc20_address',
        'deposit_trc20_private_key',
        'deposit_trc20_active',

        'withdraw_wallet_address',
        'withdraw_wallet_network',
        'withdraw_wallet_locked_at',

        'trading_password',

        'google2fa_enabled',
        'google2fa_secret',

        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'deposit_trc20_private_key',
        'trading_password',
        'google2fa_secret',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'withdraw_wallet_locked_at' => 'datetime',
            'deposit_trc20_active' => 'boolean',
            'google2fa_enabled' => 'boolean',
            'password' => 'hashed',
        ];
    }
}