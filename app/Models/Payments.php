<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $fillable = [
        'subscription_id',
        'order_id',
        'transaction_id',
        'amount',
        'payment_method',
        'status',
        'paid_at',
    ];
}
