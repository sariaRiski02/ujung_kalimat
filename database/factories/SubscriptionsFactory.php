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
        $startedAt = fake()->dateTimeBetween('-1 year', 'now');

        return [
            'user_id' => User::factory(),
            'membership_plan_id' => null,
            'started_at' => $startedAt,
            'ended_at' => Carbon::parse($startedAt)->addMonth(),
        ];
    }

    public function expired(): static
    {
        return $this->state(fn () => [
            'started_at' => now()->subYear(),
            'ended_at' => now()->subMonth(),
        ]);
    }

    public function active(): static
    {
        return $this->state(fn () => [
            'started_at' => now()->subDays(5),
            'ended_at' => now()->addMonth(),
        ]);
    }
}
