<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ListArticleWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    private function makeArticle(User $user, string $title, string $status = 'draft', int $premium = 0): Article
    {
        $this->actingAs($user);

        return Article::create([
            'title' => $title,
            'content' => [['type' => 'paragraph', 'text' => 'Isi '.$title]],
            'is_premium' => $premium,
            'status' => $status,
        ]);
    }

    public function test_page_renders_the_component_with_own_articles_only(): void
    {
        $user = User::factory()->create();
        $this->makeArticle($user, 'Alpha');
        $this->makeArticle(User::factory()->create(), 'Milik orang lain');

        $this->actingAs($user)->get(route('workspace.articles'))
            ->assertOk()
            ->assertSee('Alpha')
            ->assertDontSee('Milik orang lain');
    }

    public function test_search_filters_articles_and_counts(): void
    {
        $user = User::factory()->create();
        $this->makeArticle($user, 'Alpha', 'published');
        $this->makeArticle($user, 'Beta');

        Livewire::actingAs($user)->test('workspace.article-list')
            ->assertSee('Alpha')->assertSee('Beta')
            ->set('search', 'Alpha')
            ->assertSee('Alpha')->assertDontSee('Beta')
            ->assertSet('search', 'Alpha')
            ->set('search', 'tidak-ada')
            ->assertSee('Tidak ada artikel yang cocok.');
    }

    public function test_search_by_premium_keyword(): void
    {
        $user = User::factory()->create();
        $this->makeArticle($user, 'Alpha', 'draft', 1);
        $this->makeArticle($user, 'Beta');

        Livewire::actingAs($user)->test('workspace.article-list')
            ->set('search', 'premium')
            ->assertSee('Alpha')->assertDontSee('Beta');
    }

    public function test_filter_by_status_and_clear_search(): void
    {
        $user = User::factory()->create();
        $this->makeArticle($user, 'Alpha', 'published');
        $this->makeArticle($user, 'Beta');

        Livewire::actingAs($user)->test('workspace.article-list')
            ->call('setFilter', 'draft')
            ->assertSee('Beta')->assertDontSee('Alpha')
            ->call('setFilter', 'bogus')
            ->assertSet('filter', '')
            ->set('search', 'Alpha')
            ->call('clearSearch')
            ->assertSet('search', '')
            ->assertSee('Beta');
    }

    public function test_toggle_status_and_premium(): void
    {
        $user = User::factory()->create();
        $article = $this->makeArticle($user, 'Alpha');

        Livewire::actingAs($user)->test('workspace.article-list')
            ->call('toggleStatus', $article->id)
            ->call('togglePremium', $article->id)
            ->assertDispatched('notify');

        $article->refresh();
        $this->assertSame('published', $article->status);
        $this->assertTrue((bool) $article->is_premium);
    }

    public function test_delete_article(): void
    {
        $user = User::factory()->create();
        $article = $this->makeArticle($user, 'Alpha');

        Livewire::actingAs($user)->test('workspace.article-list')->call('delete', $article->id);

        $this->assertModelMissing($article);
    }

    public function test_cannot_modify_other_users_article(): void
    {
        $article = $this->makeArticle(User::factory()->create(), 'Alpha');

        $this->expectException(ModelNotFoundException::class);

        try {
            Livewire::actingAs(User::factory()->create())->test('workspace.article-list')
                ->call('toggleStatus', $article->id);
        } finally {
            $this->assertSame('draft', $article->fresh()->status);
        }

    }

    public function test_pagination(): void
    {
        $user = User::factory()->create();
        foreach (range(1, 12) as $i) {
            $this->makeArticle($user, "Artikel {$i}");
        }

        Livewire::actingAs($user)->test('workspace.article-list')
            ->assertSee('Artikel 12')->assertDontSee('Artikel 2<', false)
            ->call('gotoPage', 2)
            ->assertSee('Artikel 2<', false)->assertDontSee('Artikel 12');
    }
}
