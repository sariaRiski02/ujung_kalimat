<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'parent_id' => null,
            'body' => fake()->sentence(),
        ];
    }

    public function replyTo(Comment $comment): static
    {
        return $this->state(fn () => [
            'article_id' => $comment->article_id,
            'parent_id' => $comment->id,
        ]);
    }
}
