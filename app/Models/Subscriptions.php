<?php

namespace App\Models;

use Database\Factories\SubscriptionsFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscriptions extends Model
{
    /** @use HasFactory<SubscriptionsFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'membership_plan_id',
        'started_at',
        'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(MembershipPlans::class, 'membership_plan_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payments::class, 'subscription_id');
    }

    /**
     * @param  Builder<self>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('started_at', '<=', now())->where('ended_at', '>', now());
    }

    public function isActive(): bool
    {
        return $this->started_at !== null
            && $this->ended_at !== null
            && $this->started_at->isPast()
            && $this->ended_at->isFuture();
    }
}
