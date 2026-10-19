<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\MembershipPlans;
use App\Models\Payments;
use App\Models\User;
use Database\Seeders\DummyDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DummyDataSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_demo_accounts_and_articles_with_correct_access(): void
    {
        $this->seed(DummyDataSeeder::class);

        $this->assertTrue(User::where('email', 'subscriber.demo@example.test')->firstOrFail()->isSubscriber());
        $this->assertFalse(User::where('email', 'expired.demo@example.test')->firstOrFail()->isSubscriber());
        $this->assertFalse(User::where('email', 'free.demo@example.test')->firstOrFail()->isSubscriber());

        $this->assertGreaterThan(0, Article::published()->where('is_premium', true)->count());
        $this->assertGreaterThan(0, Article::published()->where('is_premium', false)->count());
        $this->assertSame(3, Article::distinct()->count('user_id'));
        $this->assertSame(['monthly', 'semiannual'], MembershipPlans::orderBy('name')->pluck('name')->all());
    }

    public function test_seeder_can_run_twice_without_duplicating_data(): void
    {
        $this->seed(DummyDataSeeder::class);
        $counts = [User::count(), Article::count(), Payments::count()];

        $this->seed(DummyDataSeeder::class);

        $this->assertSame($counts, [User::count(), Article::count(), Payments::count()]);
    }

    public function test_demo_users_can_sign_in_with_the_documented_password(): void
    {
        $this->seed(DummyDataSeeder::class);

        $this->post(route('signin.post'), ['email' => 'free.demo@example.test', 'password' => 'password'])
            ->assertRedirect();
        $this->assertAuthenticatedAs(User::where('email', 'free.demo@example.test')->firstOrFail());
    }
}
