<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Article;
use App\Models\Comment;
use App\Notifications\CommentReplyNotification;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Article $article): RedirectResponse
    {
        $comment = $article->comments()->create([
            'user_id' => $request->user()->id,
            'parent_id' => $request->validated('parent_id'),
            'body' => $request->validated('body'),
        ]);

        $this->notifyRecipients($comment->setRelation('article', $article)->load('parent.user', 'user'));

        return redirect(route('article.show', $article).'#comment-'.$comment->id);
    }

    public function destroy(Request $request, Comment $comment): RedirectResponse
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return redirect(route('article.show', $comment->article).'#comments');
    }

    private function notifyRecipients(Comment $comment): void
    {
        $parentAuthor = $comment->parent?->user;

        if ($parentAuthor && $parentAuthor->isNot($comment->user)) {
            $parentAuthor->notify(new CommentReplyNotification($comment));
        }

        $articleAuthor = $comment->article->user;

        if ($articleAuthor->isNot($comment->user) && $articleAuthor->isNot($parentAuthor)) {
            $articleAuthor->notify(new NewCommentNotification($comment));
        }
    }
}
