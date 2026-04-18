<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiStrategy extends Model
{
    protected $table = 'ai_strategies';

    protected $fillable = [
        'user_id',
        'user_name',
        'strategy_name',
        'target_pair',
        'amount',
        'target_percent',
        'lock_hours',
        'risk_level',
        'status',
        'order_no',
        'current_profit',
        'started_at',
        'unlock_at',
        'redeem_requested_at',
        'redeem_available_at',
        'closed_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'amount' => 'float',
        'target_percent' => 'float',
        'current_profit' => 'float',
        'lock_hours' => 'integer',
        'started_at' => 'datetime',
        'unlock_at' => 'datetime',
        'redeem_requested_at' => 'datetime',
        'redeem_available_at' => 'datetime',
        'closed_at' => 'datetime',
    ];
}