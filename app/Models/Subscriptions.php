<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    /** @use HasFactory<\Database\Factories\SubscriptionsFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'started_at',
        'ended_at',
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
