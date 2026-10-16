<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class CommentReplyNotification extends Notification
{
    public function __construct(public Comment $reply) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'reply',
            'message' => "{$this->reply->user->name} membalas komentarmu",
            'article_title' => strip_tags($this->reply->article->title),
            'article_slug' => $this->reply->article->slug,
            'comment_id' => $this->reply->id,
            'excerpt' => Str::limit($this->reply->body, 80),
        ];
    }
}
