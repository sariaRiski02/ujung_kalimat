<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewCommentNotification extends Notification
{
    public function __construct(public Comment $comment) {}

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
            'type' => 'comment',
            'message' => "{$this->comment->user->name} mengomentari tulisanmu",
            'article_title' => strip_tags($this->comment->article->title),
            'article_slug' => $this->comment->article->slug,
            'comment_id' => $this->comment->id,
            'excerpt' => Str::limit($this->comment->body, 80),
        ];
    }
}
