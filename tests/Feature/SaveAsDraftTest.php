<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SaveAsDraftTest extends TestCase
{
    use RefreshDatabase;

    private function makeArticle(User $user, string $status): Article
    {
        $this->actingAs($user);

        $article = Article::create([
            'title' => ucfirst($status).' title',
            'content' => [['type' => 'paragraph', 'text' => 'Isi']],
            'is_premium' => 0,
            'status' => $status,
        ]);

        Auth::logout();

        return $article;
    }

    public function test_draft_is_saved_with_content(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('workspace.write.post'), [
            'title' => 'Judul draft',
            'content' => [0 => ['type' => 'paragraph', 'text' => 'Halo']],
            'is_premium' => 0,
            'status' => 'draft',
        ])->assertRedirect(route('workspace.articles'))
            ->assertSessionHas('success', 'article has been saved as draft');

        $this->assertDatabaseHas('articles', ['title' => 'Judul draft', 'status' => 'draft', 'user_id' => $user->id]);
    }

    public function test_draft_can_be_saved_with_empty_content(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('workspace.write.post'), [
            'title' => 'Draft kosong',
            'content' => [0 => ['type' => 'paragraph', 'text' => '']],
            'is_premium' => 0,
            'status' => 'draft',
        ])->assertRedirect(route('workspace.articles'))->assertSessionHasNoErrors();

        $this->assertDatabaseHas('articles', ['title' => 'Draft kosong', 'status' => 'draft', 'content' => '']);

        $this->actingAs($user)
            ->get(route('workspace.article.update', Article::firstOrFail()->slug))
            ->assertOk();
    }

    public function test_draft_still_requires_a_title(): void
    {
        $this->actingAs(User::factory()->create())->post(route('workspace.write.post'), [
            'title' => '',
            'content' => [0 => ['type' => 'paragraph', 'text' => 'Halo']],
            'is_premium' => 0,
            'status' => 'draft',
        ])->assertSessionHasErrors('title');

        $this->assertDatabaseCount('articles', 0);
    }

    public function test_publishing_still_requires_content(): void
    {
        $this->actingAs(User::factory()->create())->post(route('workspace.write.post'), [
            'title' => 'Judul',
            'content' => [0 => ['type' => 'paragraph', 'text' => '']],
            'is_premium' => 0,
            'status' => 'published',
        ])->assertSessionHasErrors('content');

        $this->assertDatabaseCount('articles', 0);
    }

    public function test_published_article_can_be_saved_back_as_draft(): void
    {
        $user = User::factory()->create();
        $article = $this->makeArticle($user, 'published');

        $this->actingAs($user)->put(route('workspace.article.edit', $article->slug), [
            'title' => 'Judul baru',
            'content' => [0 => ['type' => 'paragraph', 'text' => 'Isi']],
            'is_premium' => 0,
            'status' => 'draft',
        ])->assertRedirect(route('workspace.articles'))
            ->assertSessionHas('success', 'article has been saved as draft');

        $this->assertSame('draft', $article->fresh()->status);
    }

    public function test_write_form_buttons_submit_the_status(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('workspace.write'))
            ->assertSee('name="status"', false)
            ->assertSee('value="draft"', false)
            ->assertSee('value="published"', false);
    }

    public function test_drafts_are_hidden_from_the_public_home_page(): void
    {
        $user = User::factory()->create();
        $this->makeArticle($user, 'draft');
        $this->makeArticle($user, 'published');

        $this->get(route('welcome'))
            ->assertOk()
            ->assertSee('Published title')
            ->assertDontSee('Draft title');
    }

    public function test_draft_is_only_visible_to_its_owner(): void
    {
        $owner = User::factory()->create();
        $draft = $this->makeArticle($owner, 'draft');

        $this->get(route('article.show', $draft->slug))->assertNotFound();
        $this->actingAs(User::factory()->create())->get(route('article.show', $draft->slug))->assertNotFound();
        $this->actingAs($owner)->get(route('article.show', $draft->slug))->assertOk();
    }

    public function test_published_article_is_public(): void
    {
        $article = $this->makeArticle(User::factory()->create(), 'published');

        $this->get(route('article.show', $article->slug))->assertOk();
    }
}
