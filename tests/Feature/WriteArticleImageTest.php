<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WriteArticleImageTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $content): array
    {
        return [
            'title' => 'Judul',
            'content' => $content,
            'is_premium' => 0,
            'status' => 'draft',
        ];
    }

    public function test_article_is_stored_with_inline_image_and_empty_lines_are_ignored(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('workspace.write.post'), $this->payload([
            0 => ['type' => 'paragraph', 'text' => 'Halo'],
            1 => ['type' => 'image', 'image' => UploadedFile::fake()->image('foto.jpg')],
            2 => ['type' => 'paragraph', 'text' => ''],
        ]));

        $response->assertRedirect(route('workspace.articles'))->assertSessionHasNoErrors();

        $article = Article::firstOrFail();
        $this->assertMatchesRegularExpression('#^<p>Halo</p><img src="/storage/articles/content/[\w.\-]+" alt="">$#', $article->content);
        Storage::disk('public')->assertExists('articles/content/'.basename(explode('"', $article->content)[3]));
    }

    public function test_image_block_without_file_is_rejected(): void
    {
        $response = $this->actingAs(User::factory()->create())->post(route('workspace.write.post'), $this->payload([
            0 => ['type' => 'image'],
        ]));

        $response->assertSessionHasErrors('content.0.image');
        $this->assertDatabaseCount('articles', 0);
    }

    public function test_non_image_file_is_rejected(): void
    {
        $response = $this->actingAs(User::factory()->create())->post(route('workspace.write.post'), $this->payload([
            0 => ['type' => 'image', 'image' => UploadedFile::fake()->create('a.pdf', 10, 'application/pdf')],
        ]));

        $response->assertSessionHasErrors('content.0.image');
    }

    public function test_only_empty_lines_are_rejected(): void
    {
        $response = $this->actingAs(User::factory()->create())->post(route('workspace.write.post'), $this->payload([
            0 => ['type' => 'paragraph', 'text' => ''],
        ]));

        $response->assertSessionHasErrors('content');
    }

    public function test_image_is_kept_when_updating_article(): void
    {
        $user = User::factory()->create();
        $article = Article::create([
            'title' => 'Judul',
            'content' => [
                ['type' => 'paragraph', 'text' => 'Halo'],
                ['type' => 'image', 'url' => '/storage/articles/content/a.jpg'],
            ],
            'is_premium' => 0,
            'status' => 'draft',
        ]);

        $this->actingAs($user)->get(route('workspace.article.update', $article))
            ->assertOk()
            ->assertSee('/storage/articles/content/a.jpg');

        $this->actingAs($user)->put(route('workspace.article.edit', $article), $this->payload([
            0 => ['type' => 'paragraph', 'text' => 'Halo baru'],
            1 => ['type' => 'image', 'url' => '/storage/articles/content/a.jpg'],
        ]))->assertSessionHasNoErrors();

        $this->assertSame('<p>Halo baru</p><img src="/storage/articles/content/a.jpg" alt="">', $article->fresh()->content);
    }

    public function test_update_page_has_insert_menu_and_accepts_new_image(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $article = Article::create([
            'title' => 'Judul',
            'content' => [['type' => 'paragraph', 'text' => 'Halo']],
            'is_premium' => 0,
            'status' => 'draft',
        ]);

        $this->actingAs($user)->get(route('workspace.article.update', $article))
            ->assertOk()
            ->assertSee('insert-menu-template', false)
            ->assertDontSee('coverImage', false);

        $this->actingAs($user)->put(route('workspace.article.edit', $article), $this->payload([
            0 => ['type' => 'paragraph', 'text' => 'Halo'],
            1 => ['type' => 'image', 'image' => UploadedFile::fake()->image('baru.png')],
        ]))->assertSessionHasNoErrors();

        $this->assertStringContainsString('<img src="/storage/articles/content/', $article->fresh()->content);
    }

    public function test_changing_status_and_premium_while_editing_still_renders_content(): void
    {
        $user = User::factory()->create();
        $article = Article::create([
            'title' => 'Judul',
            'content' => [['type' => 'paragraph', 'text' => 'Halo']],
            'is_premium' => 1,
            'status' => 'published',
        ]);

        $this->actingAs($user)->put(route('workspace.article.edit', $article), $this->payload([
            0 => ['type' => 'paragraph', 'text' => 'Baru'],
        ]))->assertSessionHasNoErrors();

        $article->refresh();
        $this->assertSame('<p>Baru</p>', $article->content);
        $this->assertSame('draft', $article->status);
        $this->assertSame(0, (int) $article->is_premium);
    }
}
