<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Notifications\CommentReplyNotification;
use App\Notifications\NewCommentNotification;
use App\Notifications\NewFollowerNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    private function makeArticle(User $author): Article
    {
        $this->actingAs($author);

        return Article::create([
            'title' => 'Judul Artikel',
            'content' => [['type' => 'paragraph', 'text' => 'Isi artikel']],
            'is_premium' => 0,
            'status' => 'published',
        ]);
    }

    public function test_article_author_is_notified_of_new_comment(): void
    {
        $author = User::factory()->create();
        $article = $this->makeArticle($author);
        $commenter = User::factory()->create(['name' => 'Siti']);

        $this->actingAs($commenter)->post(route('comments.store', $article), ['body' => 'Bagus sekali']);

        $this->assertCount(1, $author->notifications);
        $this->assertSame(NewCommentNotification::class, $author->notifications->first()->type);
        $this->assertSame('Siti mengomentari tulisanmu', $author->notifications->first()->data['message']);
    }

    public function test_author_commenting_on_own_article_sends_no_notification(): void
    {
        $author = User::factory()->create();
        $article = $this->makeArticle($author);

        $this->actingAs($author)->post(route('comments.store', $article), ['body' => 'Catatan sendiri']);

        $this->assertCount(0, $author->notifications);
    }

    public function test_reply_notifies_parent_author_and_article_author_separately(): void
    {
        $author = User::factory()->create();
        $article = $this->makeArticle($author);
        $parent = Comment::factory()->create(['article_id' => $article->id]);
        $replier = User::factory()->create();

        $this->actingAs($replier)
            ->post(route('comments.store', $article), ['body' => 'Setuju', 'parent_id' => $parent->id]);

        $this->assertSame([CommentReplyNotification::class], $parent->user->notifications->pluck('type')->all());
        $this->assertSame([NewCommentNotification::class], $author->notifications->pluck('type')->all());
    }

    public function test_reply_to_article_authors_comment_sends_only_one_notification(): void
    {
        $author = User::factory()->create();
        $article = $this->makeArticle($author);
        $parent = Comment::factory()->create(['article_id' => $article->id, 'user_id' => $author->id]);
        $replier = User::factory()->create();

        $this->actingAs($replier)
            ->post(route('comments.store', $article), ['body' => 'Balasan', 'parent_id' => $parent->id]);

        $this->assertSame([CommentReplyNotification::class], $author->notifications->pluck('type')->all());
    }

    public function test_replying_to_own_comment_does_not_notify_self(): void
    {
        $author = User::factory()->create();
        $article = $this->makeArticle($author);
        $parent = Comment::factory()->create(['article_id' => $article->id]);

        $this->actingAs($parent->user)
            ->post(route('comments.store', $article), ['body' => 'Tambahan', 'parent_id' => $parent->id]);

        $this->assertCount(0, $parent->user->notifications);
        $this->assertCount(1, $author->notifications);
    }

    public function test_follow_notifies_once(): void
    {
        $user = User::factory()->create(['name' => 'Rina']);
        $target = User::factory()->create();

        $this->actingAs($user)->post(route('users.follow', $target));
        $this->actingAs($user)->post(route('users.follow', $target));

        $this->assertCount(1, $target->notifications);
        $this->assertSame(NewFollowerNotification::class, $target->notifications->first()->type);
    }

    public function test_bell_shows_unread_count_and_messages(): void
    {
        $author = User::factory()->create();
        $article = $this->makeArticle($author);
        $commenter = User::factory()->create(['name' => 'Siti']);
        $this->actingAs($commenter)->post(route('comments.store', $article), ['body' => 'Halo penulis']);

        $this->actingAs($author)->get(route('welcome'))
            ->assertOk()
            ->assertSee('Siti mengomentari tulisanmu')
            ->assertSee('Halo penulis')
            ->assertSee('Tandai semua dibaca');
    }

    public function test_bell_shows_empty_state(): void
    {
        $this->actingAs(User::factory()->create())->get(route('welcome'))
            ->assertSee('Belum ada notifikasi')
            ->assertDontSee('Tandai semua dibaca');
    }

    public function test_opening_notification_marks_it_read_and_redirects_to_comment(): void
    {
        $author = User::factory()->create();
        $article = $this->makeArticle($author);
        $comment = Comment::factory()->create(['article_id' => $article->id]);
        $this->actingAs($comment->user)->post(route('comments.store', $article), ['body' => 'Baru']);
        $notification = $author->notifications()->first();

        $this->actingAs($author)->get(route('notifications.read', $notification->id))
            ->assertRedirect(route('article.show', $article).'#comment-'.$notification->data['comment_id']);

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_follow_notification_redirects_to_followers_page(): void
    {
        $user = User::factory()->create();
        $target = User::factory()->create();
        $this->actingAs($user)->post(route('users.follow', $target));

        $this->actingAs($target)
            ->get(route('notifications.read', $target->notifications()->first()->id))
            ->assertRedirect(route('workspace.followers'));
    }

    public function test_cannot_open_someone_elses_notification(): void
    {
        $author = User::factory()->create();
        $article = $this->makeArticle($author);
        $this->actingAs(User::factory()->create())->post(route('comments.store', $article), ['body' => 'Hai']);
        $notification = $author->notifications()->first();

        $this->actingAs(User::factory()->create())
            ->get(route('notifications.read', $notification->id))
            ->assertNotFound();

        $this->assertNull($notification->fresh()->read_at);
    }

    public function test_mark_all_as_read(): void
    {
        $author = User::factory()->create();
        $article = $this->makeArticle($author);
        $this->actingAs(User::factory()->create())->post(route('comments.store', $article), ['body' => 'Satu']);
        $this->actingAs(User::factory()->create())->post(route('comments.store', $article), ['body' => 'Dua']);

        $this->actingAs($author)->post(route('notifications.read-all'))->assertRedirect();

        $this->assertCount(0, $author->unreadNotifications()->get());
        $this->assertCount(2, $author->notifications()->get());
    }

    public function test_guest_cannot_access_notification_routes(): void
    {
        $this->post(route('notifications.read-all'))->assertRedirect(route('signin'));
        $this->get(route('notifications.read', 'x'))->assertRedirect(route('signin'));
    }
}
