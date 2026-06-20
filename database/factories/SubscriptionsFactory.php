<?php

namespace Database\Factories;

use App\Models\Subscriptions;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Subscriptions>
 */
class SubscriptionsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
     public function definition(): array
    {
        $plan = fake()->randomElement(['monthly', 'yearly']);
        $startedAt = fake()->dateTimeBetween('-1 year', 'now');

        $expiredAt = $plan === 'monthly'
            ? Carbon::parse($startedAt)->addMonth()
            : Carbon::parse($startedAt)->addYear();

        return [
            'user_id' => User::factory(),
            'plan' => $plan,
            'started_at' => $startedAt,
            'expired_at' => $expiredAt,
            // status ikut logis sesuai apakah sudah lewat expired_at atau belum
            'status' => Carbon::parse($expiredAt)->isPast() ? 'expired' : 'active',
        ];
    }

    // state khusus kalau mau bikin yang pasti expired
    public function expired(): static
    {
        return $this->state(fn () => [
            'started_at' => now()->subYear(),
            'expired_at' => now()->subMonth(),
            'status' => 'expired',
        ]);
    }

    // state khusus kalau mau bikin yang pasti masih aktif
    public function active(): static
    {
        return $this->state(fn () => [
            'started_at' => now()->subDays(5),
            'expired_at' => now()->addMonth(),
            'status' => 'active',
        ]);
    }
}
