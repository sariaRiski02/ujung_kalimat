<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private function makeArticle(User $author, string $title, string $status = 'published'): Article
    {
        $this->actingAs($author);

        return Article::create([
            'title' => $title,
            'content' => [['type' => 'paragraph', 'text' => 'Isi '.$title]],
            'is_premium' => 0,
            'status' => $status,
        ]);
    }

    public function test_guest_is_redirected(): void
    {
        $this->get(route('workspace.dashboard'))->assertRedirect(route('signin'));
    }

    public function test_sidebar_has_back_to_home_link_that_keeps_user_signed_in(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('workspace.dashboard'))
            ->assertSee('Back to Home')
            ->assertSee('href="'.route('welcome').'"', false);

        $this->get(route('welcome'))->assertOk();
        $this->assertAuthenticatedAs($user);
    }

    public function test_stats_reflect_real_data(): void
    {
        $user = User::factory()->create();
        $article = $this->makeArticle($user, 'Artikel A');
        $this->makeArticle($user, 'Artikel B', 'draft');
        $other = $this->makeArticle(User::factory()->create(), 'Milik Lain');
        Article::whereKey($article->id)->update(['views' => 1500]);

        Comment::factory()->count(3)->create(['article_id' => $article->id]);
        Comment::factory()->count(5)->create(['article_id' => $other->id]);
        User::factory()->count(2)->create()->each(fn (User $follower) => $follower->follow($user));
        $user->follow(User::factory()->create());

        $response = $this->actingAs($user)->get(route('workspace.dashboard'))->assertOk();

        $this->assertSame(2, $response->viewData('articlesCount'));
        $this->assertSame(1500, $response->viewData('readersCount'));
        $this->assertSame(3, $response->viewData('commentsCount'));
        $this->assertSame(2, $response->viewData('followersCount'));
        $this->assertSame(1, $response->viewData('followingCount'));
        $response->assertSee('1.5K');
    }

    public function test_recent_articles_lists_only_own_latest_five(): void
    {
        $user = User::factory()->create();
        foreach (range(1, 6) as $i) {
            $this->makeArticle($user, "Tulisan Nomor $i", $i === 2 ? 'draft' : 'published');
        }
        $this->makeArticle(User::factory()->create(), 'Tulisan Orang Lain');

        $this->actingAs($user)->get(route('workspace.dashboard'))
            ->assertOk()
            ->assertSee('Tulisan Nomor 6')
            ->assertSee('Tulisan Nomor 2')
            ->assertDontSee('Tulisan Nomor 1')
            ->assertDontSee('Tulisan Orang Lain')
            ->assertSee('Draft')
            ->assertSee('Publikasi');
    }

    public function test_empty_state_for_new_user(): void
    {
        $this->actingAs(User::factory()->create())->get(route('workspace.dashboard'))
            ->assertOk()
            ->assertSee('Belum ada artikel');
    }

    public function test_article_view_is_counted_once_per_session_and_not_for_author(): void
    {
        $author = User::factory()->create();
        $article = $this->makeArticle($author, 'Dilihat');
        auth()->logout();

        $this->get(route('article.show', $article));
        $this->get(route('article.show', $article));
        $this->assertSame(1, $article->fresh()->views);

        $this->actingAs($author)->get(route('article.show', $article));
        $this->assertSame(1, $article->fresh()->views);

        $this->flushSession();
        auth()->logout();
        $this->get(route('article.show', $article));
        $this->assertSame(2, $article->fresh()->views);
    }

    public function test_view_does_not_change_updated_at(): void
    {
        $article = $this->makeArticle(User::factory()->create(), 'Waktu');
        auth()->logout();
        $before = $article->fresh()->updated_at;

        $this->travel(2)->hours();
        $this->get(route('article.show', $article));

        $this->assertTrue($before->equalTo($article->fresh()->updated_at));
    }
}
