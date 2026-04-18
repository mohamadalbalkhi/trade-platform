<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'user_name',
        'amount',
        'method',
        'wallet_address',
        'status'
    ];
}