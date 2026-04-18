<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_name',
        'pair',
        'type',
        'btc_amount',
        'price',
        'status'
    ];
}