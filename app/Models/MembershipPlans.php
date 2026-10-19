<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPlans extends Model
{
    protected $fillable = [
        'name',
        'duration_months',
        'price',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'duration_months' => 'integer',
            'price' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
