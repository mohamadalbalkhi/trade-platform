<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $fillable = [
        'user_name',
        'pair',
        'type',
        'amount',
        'price'
    ];
}