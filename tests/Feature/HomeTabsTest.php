<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTabsTest extends TestCase
{
    use RefreshDatabase;

    private function makeArticle(User $author, string $title, string $status = 'published', int $love = 0): Article
    {
        $this->actingAs($author);

        $article = Article::create([
            'title' => $title,
            'content' => [['type' => 'paragraph', 'text' => 'Isi '.$title]],
            'is_premium' => 0,
            'status' => $status,
        ]);
        $article->forceFill(['love' => $love])->save();

        return $article;
    }

    public function test_guest_sees_all_published_articles_without_tabs(): void
    {
        $this->makeArticle(User::factory()->create(), 'Artikel Satu');
        $this->makeArticle(User::factory()->create(), 'Artikel Draf', 'draft');
        auth()->logout();

        $this->get(route('welcome'))
            ->assertOk()
            ->assertSee('Artikel Satu')
            ->assertDontSee('Artikel Draf')
            ->assertDontSee('Featured');
    }

    public function test_for_you_shows_only_followed_authors(): void
    {
        $viewer = User::factory()->create();
        $followed = User::factory()->create();
        $this->makeArticle($followed, 'Dari Yang Diikuti');
        $this->makeArticle(User::factory()->create(), 'Dari Orang Asing');
        $viewer->follow($followed);

        $this->actingAs($viewer)->get(route('welcome'))
            ->assertOk()
            ->assertSee('For You')
            ->assertSee('Dari Yang Diikuti')
            ->assertDontSee('Dari Orang Asing');
    }

    public function test_for_you_falls_back_to_latest_when_following_nobody(): void
    {
        $this->makeArticle(User::factory()->create(), 'Artikel Terbaru');

        $this->actingAs(User::factory()->create())->get(route('welcome'))
            ->assertOk()
            ->assertSee('Artikel Terbaru');
    }

    public function test_featured_orders_by_love_and_comments_and_ignores_follows(): void
    {
        $viewer = User::factory()->create();
        $author = User::factory()->create();
        $quiet = $this->makeArticle($author, 'Artikel Sepi');
        $loved = $this->makeArticle($author, 'Artikel Populer', 'published', 5);
        $discussed = $this->makeArticle($author, 'Artikel Ramai');
        Comment::factory()->count(8)->create(['article_id' => $discussed->id]);
        $viewer->follow(User::factory()->create());

        $this->actingAs($viewer)->get(route('welcome', ['tab' => 'featured']))
            ->assertOk()
            ->assertSeeInOrder(['Artikel Ramai', 'Artikel Populer', 'Artikel Sepi']);
    }

    public function test_active_tab_is_marked(): void
    {
        $viewer = User::factory()->create();

        $this->actingAs($viewer)->get(route('welcome', ['tab' => 'featured']))
            ->assertSee('aria-current="page"', false)
            ->assertSee(route('welcome', ['tab' => 'featured']), false);
    }
}
