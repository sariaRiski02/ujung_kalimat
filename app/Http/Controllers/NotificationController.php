<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function read(Request $request, string $notification): RedirectResponse
    {
        $notification = $request->user()->notifications()->findOrFail($notification);
        $notification->markAsRead();

        $data = $notification->data;

        if ($data['type'] === 'follow') {
            return redirect()->route('workspace.followers');
        }

        if (! Article::where('slug', $data['article_slug'])->exists()) {
            return redirect()->route('welcome');
        }

        return redirect(route('article.show', $data['article_slug']).'#comment-'.$data['comment_id']);
    }

    public function readAll(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return back();
    }
}
