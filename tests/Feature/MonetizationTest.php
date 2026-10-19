<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MonetizationTest extends TestCase
{
    use RefreshDatabase;

    private function makeWriter(int $premiumViews = 0, int $freeViews = 0): User
    {
        $writer = User::factory()->create();
        $this->actingAs($writer);

        foreach ([[true, $premiumViews], [false, $freeViews]] as [$isPremium, $views]) {
            $article = Article::create([
                'title' => 'Artikel',
                'content' => [['type' => 'paragraph', 'text' => 'Isi']],
                'is_premium' => $isPremium,
                'status' => 'published',
            ]);
            $article->forceFill(['views' => $views])->save();
        }

        return $writer;
    }

    public function test_earnings_only_count_premium_views_at_flat_rate(): void
    {
        $writer = $this->makeWriter(premiumViews: 25, freeViews: 900);

        $this->assertSame(25000, $writer->totalEarnings());
        $this->assertSame(25000, $writer->availableBalance());
    }

    public function test_balance_subtracts_pending_and_paid_but_not_rejected_withdrawals(): void
    {
        $writer = $this->makeWriter(premiumViews: 50);

        Withdrawal::factory()->for($writer)->create(['amount' => 10000]);
        Withdrawal::factory()->for($writer)->create(['amount' => 5000, 'status' => Withdrawal::STATUS_PAID]);
        Withdrawal::factory()->for($writer)->create(['amount' => 20000, 'status' => Withdrawal::STATUS_REJECTED]);

        $this->assertSame(15000, $writer->totalWithdrawn());
        $this->assertSame(35000, $writer->availableBalance());
    }

    public function test_monetization_page_shows_balance_and_history(): void
    {
        $writer = $this->makeWriter(premiumViews: 12);
        Withdrawal::factory()->for($writer)->create(['amount' => 10000, 'phone_number' => '089999999999']);

        $this->get(route('workspace.monetization'))
            ->assertOk()
            ->assertSee('Rp 12.000')
            ->assertSee('Rp 2.000')
            ->assertSee('089999999999');
    }

    public function test_user_can_withdraw_to_phone_number(): void
    {
        $writer = $this->makeWriter(premiumViews: 30);

        $this->post(route('workspace.monetization.withdraw'), ['amount' => 20000, 'phone_number' => '0812 3456 7890'])
            ->assertRedirect(route('workspace.monetization'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('withdrawals', [
            'user_id' => $writer->id,
            'amount' => 20000,
            'phone_number' => '081234567890',
            'status' => Withdrawal::STATUS_PENDING,
        ]);
        $this->assertSame(10000, $writer->availableBalance());
    }

    public function test_international_phone_format_is_normalized(): void
    {
        $this->makeWriter(premiumViews: 30);

        $this->post(route('workspace.monetization.withdraw'), ['amount' => 10000, 'phone_number' => '+6281234567890'])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('withdrawals', ['phone_number' => '081234567890']);
    }

    public function test_withdraw_cannot_exceed_balance(): void
    {
        $this->makeWriter(premiumViews: 15);

        $this->post(route('workspace.monetization.withdraw'), ['amount' => 15001, 'phone_number' => '081234567890'])
            ->assertSessionHasErrors('amount');

        $this->assertDatabaseCount('withdrawals', 0);
    }

    public function test_withdraw_cannot_be_below_minimum(): void
    {
        $this->makeWriter(premiumViews: 30);

        $this->post(route('workspace.monetization.withdraw'), ['amount' => 9999, 'phone_number' => '081234567890'])
            ->assertSessionHasErrors('amount');

        $this->assertDatabaseCount('withdrawals', 0);
    }

    public function test_withdraw_requires_valid_phone_number(): void
    {
        $this->makeWriter(premiumViews: 30);

        foreach ([null, 'abc', '12345', '0712345678901'] as $phone) {
            $this->post(route('workspace.monetization.withdraw'), ['amount' => 10000, 'phone_number' => $phone])
                ->assertSessionHasErrors('phone_number');
        }

        $this->assertDatabaseCount('withdrawals', 0);
    }

    public function test_second_withdrawal_cannot_reuse_withdrawn_balance(): void
    {
        $this->makeWriter(premiumViews: 15);

        $this->post(route('workspace.monetization.withdraw'), ['amount' => 15000, 'phone_number' => '081234567890'])
            ->assertSessionHasNoErrors();
        $this->post(route('workspace.monetization.withdraw'), ['amount' => 10000, 'phone_number' => '081234567890'])
            ->assertSessionHasErrors('amount');

        $this->assertDatabaseCount('withdrawals', 1);
    }

    public function test_guest_cannot_withdraw(): void
    {
        $this->post(route('workspace.monetization.withdraw'), ['amount' => 10000, 'phone_number' => '081234567890'])
            ->assertRedirect();

        $this->assertDatabaseCount('withdrawals', 0);
    }
}
