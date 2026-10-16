<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;

class NewFollowerNotification extends Notification
{
    public function __construct(public User $follower) {}

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
            'type' => 'follow',
            'message' => "{$this->follower->name} mulai mengikutimu",
            'follower_id' => $this->follower->id,
        ];
    }
}
