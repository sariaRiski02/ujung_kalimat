<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    private function makeArticle(User $author, string $status = 'published'): Article
    {
        $this->actingAs($author);

        return Article::create([
            'title' => 'Judul Artikel',
            'content' => [['type' => 'paragraph', 'text' => 'Isi artikel']],
            'is_premium' => 0,
            'status' => $status,
        ]);
    }

    public function test_guest_cannot_post_comment(): void
    {
        $article = $this->makeArticle(User::factory()->create());
        auth()->logout();

        $this->post(route('comments.store', $article), ['body' => 'Halo'])
            ->assertRedirect(route('signin'));

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_user_can_post_comment_and_it_is_displayed(): void
    {
        $article = $this->makeArticle(User::factory()->create());
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('comments.store', $article), ['body' => 'Komentar pertama'])
            ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'article_id' => $article->id,
            'user_id' => $user->id,
            'parent_id' => null,
            'body' => 'Komentar pertama',
        ]);

        $this->get(route('article.show', $article))
            ->assertOk()
            ->assertSee('Komentar pertama')
            ->assertSee('Komentar (1)');
    }

    public function test_comment_body_is_required_and_limited(): void
    {
        $article = $this->makeArticle(User::factory()->create());
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('comments.store', $article), ['body' => ''])
            ->assertSessionHasErrors('body');
        $this->actingAs($user)->post(route('comments.store', $article), ['body' => str_repeat('a', 2001)])
            ->assertSessionHasErrors('body');

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_comment_html_is_escaped(): void
    {
        $article = $this->makeArticle(User::factory()->create());
        Comment::factory()->create(['article_id' => $article->id, 'body' => '<script>alert(1)</script>']);

        $this->get(route('article.show', $article))
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }

    public function test_user_can_reply_to_comment(): void
    {
        $article = $this->makeArticle(User::factory()->create());
        $parent = Comment::factory()->create(['article_id' => $article->id]);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('comments.store', $article), ['body' => 'Balasan', 'parent_id' => $parent->id])
            ->assertRedirect();

        $this->assertDatabaseHas('comments', ['parent_id' => $parent->id, 'body' => 'Balasan', 'user_id' => $user->id]);
        $this->get(route('article.show', $article))->assertSee('Balasan')->assertSee('Komentar (2)');
    }

    public function test_cannot_reply_to_a_reply_or_comment_of_another_article(): void
    {
        $author = User::factory()->create();
        $article = $this->makeArticle($author);
        $other = $this->makeArticle($author);
        $parent = Comment::factory()->create(['article_id' => $article->id]);
        $reply = Comment::factory()->replyTo($parent)->create();
        $foreign = Comment::factory()->create(['article_id' => $other->id]);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('comments.store', $article), ['body' => 'x', 'parent_id' => $reply->id])
            ->assertSessionHasErrors('parent_id');
        $this->actingAs($user)
            ->post(route('comments.store', $article), ['body' => 'x', 'parent_id' => $foreign->id])
            ->assertSessionHasErrors('parent_id');
    }

    public function test_cannot_comment_on_someone_elses_draft(): void
    {
        $article = $this->makeArticle(User::factory()->create(), 'draft');

        $this->actingAs(User::factory()->create())
            ->post(route('comments.store', $article), ['body' => 'Halo'])
            ->assertForbidden();

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_comment_owner_can_delete_with_replies(): void
    {
        $article = $this->makeArticle(User::factory()->create());
        $comment = Comment::factory()->create(['article_id' => $article->id]);
        Comment::factory()->replyTo($comment)->create();

        $this->actingAs($comment->user)
            ->delete(route('comments.destroy', $comment))
            ->assertRedirect();

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_article_author_can_delete_any_comment(): void
    {
        $author = User::factory()->create();
        $article = $this->makeArticle($author);
        $comment = Comment::factory()->create(['article_id' => $article->id]);

        $this->actingAs($author)->delete(route('comments.destroy', $comment))->assertRedirect();

        $this->assertModelMissing($comment);
    }

    public function test_other_users_cannot_delete_comment(): void
    {
        $article = $this->makeArticle(User::factory()->create());
        $comment = Comment::factory()->create(['article_id' => $article->id]);

        $this->actingAs(User::factory()->create())
            ->delete(route('comments.destroy', $comment))
            ->assertForbidden();

        $this->assertModelExists($comment);
    }

    public function test_author_avatar_uses_profile_photo_or_initials(): void
    {
        $withPhoto = User::factory()->create(['profile_photo_path' => 'https://example.com/foto.jpg']);
        $withoutPhoto = User::factory()->create(['name' => 'Budi Santoso']);

        $photoArticle = $this->makeArticle($withPhoto);
        $initialsArticle = $this->makeArticle($withoutPhoto);

        $this->get(route('article.show', $photoArticle))
            ->assertSee('https://example.com/foto.jpg', false)
            ->assertDontSee('pravatar.cc', false);
        $this->get(route('article.show', $initialsArticle))
            ->assertSee('BS')
            ->assertDontSee('pravatar.cc', false);
        $this->get(route('welcome'))->assertDontSee('pravatar.cc', false);
    }
}
