<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\MembershipPlans;
use App\Models\Subscriptions;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class showPremiumTest extends TestCase
{
    use RefreshDatabase;

    private const FIRST_PARAGRAPH = 'Paragraf pertama terbuka';

    private const LAST_PARAGRAPH = 'Paragraf terakhir terkunci';

    private function makeArticle(bool $isPremium = true): Article
    {
        $this->actingAs(User::factory()->create());

        $article = Article::create([
            'title' => 'Judul Premium',
            'content' => [
                ['type' => 'paragraph', 'text' => self::FIRST_PARAGRAPH],
                ['type' => 'paragraph', 'text' => 'Paragraf kedua'],
                ['type' => 'paragraph', 'text' => 'Paragraf ketiga'],
                ['type' => 'paragraph', 'text' => 'Paragraf keempat'],
                ['type' => 'paragraph', 'text' => self::LAST_PARAGRAPH],
            ],
            'is_premium' => $isPremium,
            'status' => 'published',
        ]);

        auth()->logout();

        return $article;
    }

    private function makeSubscriber(bool $active = true): User
    {
        $user = User::factory()->create();
        $subscription = Subscriptions::factory()->create(['user_id' => $user->id]);
        $active
            ? $subscription->update(['started_at' => now()->subDay(), 'ended_at' => now()->addMonth()])
            : $subscription->update(['started_at' => now()->subMonths(2), 'ended_at' => now()->subMonth()]);

        return $user;
    }

    public function test_guest_only_sees_teaser_of_premium_article(): void
    {
        $article = $this->makeArticle();

        $this->get(route('article.show', $article->slug))
            ->assertOk()
            ->assertSee(self::FIRST_PARAGRAPH)
            ->assertDontSee(self::LAST_PARAGRAPH)
            ->assertSee('Continue reading this article')
            ->assertSee(route('signin'));
    }

    public function test_user_without_subscription_only_sees_teaser(): void
    {
        $article = $this->makeArticle();

        $this->actingAs(User::factory()->create())
            ->get(route('article.show', $article->slug))
            ->assertDontSee(self::LAST_PARAGRAPH)
            ->assertSee('Continue reading this article')
            ->assertSee(route('subscribe'));
    }

    public function test_user_with_expired_subscription_only_sees_teaser(): void
    {
        $article = $this->makeArticle();

        $this->actingAs($this->makeSubscriber(active: false))
            ->get(route('article.show', $article->slug))
            ->assertDontSee(self::LAST_PARAGRAPH)
            ->assertSee('Continue reading this article');
    }

    public function test_subscriber_sees_full_premium_article(): void
    {
        $article = $this->makeArticle();

        $this->actingAs($this->makeSubscriber())
            ->get(route('article.show', $article->slug))
            ->assertOk()
            ->assertSee(self::FIRST_PARAGRAPH)
            ->assertSee(self::LAST_PARAGRAPH)
            ->assertDontSee('Continue reading this article');
    }

    public function test_author_sees_own_premium_article_without_subscription(): void
    {
        $this->actingAs(User::factory()->create());
        $article = Article::create([
            'title' => 'Tulisan Saya',
            'content' => [['type' => 'paragraph', 'text' => 'a'], ['type' => 'paragraph', 'text' => self::LAST_PARAGRAPH]],
            'is_premium' => true,
            'status' => 'published',
        ]);

        $this->get(route('article.show', $article->slug))
            ->assertSee(self::LAST_PARAGRAPH)
            ->assertDontSee('Continue reading this article');
    }

    public function test_free_article_is_fully_visible_to_guest(): void
    {
        $article = $this->makeArticle(isPremium: false);

        $this->get(route('article.show', $article->slug))
            ->assertSee(self::LAST_PARAGRAPH)
            ->assertDontSee('Continue reading this article');
    }

    public function test_header_shows_subscribed_badge_only_for_active_subscriber(): void
    {
        $this->get(route('welcome'))->assertDontSee('Subscribed');

        $this->actingAs(User::factory()->create())
            ->get(route('welcome'))
            ->assertDontSee('Subscribed')
            ->assertSee('Subscribe');

        $this->actingAs($this->makeSubscriber())
            ->get(route('welcome'))
            ->assertSee('Subscribed');
    }

    public function test_subscribe_page_shows_active_status_and_renewal_for_subscriber(): void
    {
        MembershipPlans::create(['name' => 'monthly', 'price' => 5000, 'duration_months' => 1, 'is_active' => true]);

        $this->actingAs(User::factory()->create())
            ->get(route('subscribe'))
            ->assertOk()
            ->assertDontSee('Langganan premium Anda aktif')
            ->assertSee('Pilih Paket Berlangganan');

        $subscriber = $this->makeSubscriber();

        $this->actingAs($subscriber)
            ->get(route('subscribe'))
            ->assertOk()
            ->assertSee('Langganan premium Anda aktif')
            ->assertSee($subscriber->subscriptionEndsAt()->locale('id')->translatedFormat('d F Y'))
            ->assertSee('Perpanjang Langganan');
    }
}
