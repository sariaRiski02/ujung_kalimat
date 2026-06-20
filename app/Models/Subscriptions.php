<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    /** @use HasFactory<\Database\Factories\SubscriptionsFactory> */
    use HasFactory;

     protected $casts = [
        'started_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($subscription) {
            // started_at default sekarang kalau belum diisi
            if (!$subscription->started_at) {
                $subscription->started_at = now();
            }

            // expired_at dihitung otomatis sesuai plan
            $subscription->expired_at = match ($subscription->plan) {
                'monthly' => $subscription->started_at->copy()->addMonth(),
                'yearly'  => $subscription->started_at->copy()->addYear(),
                default   => $subscription->started_at->copy()->addMonth(),
            };
        });
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expired_at?->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->expired_at?->isPast() ?? false;
    }


    public function user(){
        return $this->belongsTo(User::class);
    }
}
