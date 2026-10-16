<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\HelperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function __construct(protected HelperService $helper) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $tab = $user && $request->query('tab') === 'featured' ? 'featured' : 'for-you';

        $query = Article::with(['user', 'image'])->withCount('comments')->published();

        if ($tab === 'featured') {
            $query->orderByRaw('(love + comments_count) desc');
        } elseif ($user && $user->following()->exists()) {
            $query->whereIn('user_id', $user->following()->select('users.id'));
        }

        $articles = $query->latest()->paginate(10)->withQueryString();

        return view('pages.index', compact('articles', 'tab'));
    }

    public function show(Article $article)
    {
        abort_if($article->status !== 'published' && $article->user_id !== Auth::id(), 404);

        $article->load(['user', 'image']);
        $this->recordView($article);
        $comments = $article->comments()->whereNull('parent_id')->with(['user', 'replies.user'])->latest()->get();
        $commentsCount = $article->comments()->count();
        $isFollowingAuthor = Auth::check() && Auth::user()->isFollowing($article->user);

        $partial = $this->helper->getContent($article->content, 40).'.... Subscribe for continue';
        // if($article->is_premium && !Auth::check()){
        //     $article->content = $partial;
        //     return view('pages.pageArticle.show', compact('article', 'comments', 'commentsCount', 'isFollowingAuthor'));
        // }

        // if(Auth::user()->isSubscriber()){
        return view('pages.pageArticle.show', compact('article', 'comments', 'commentsCount', 'isFollowingAuthor'));
        // }

        $article->content = $partial;

        return view('pages.pageArticle.show', compact('article', 'comments', 'commentsCount', 'isFollowingAuthor'));

    }

    public function signin()
    {
        return view('pages.auth.signin');
    }

    public function signup()
    {
        return view('pages.auth.signup');
    }

    private function recordView(Article $article): void
    {
        $viewed = session('viewed_articles', []);

        if ($article->user_id === Auth::id() || in_array($article->id, $viewed, true)) {
            return;
        }

        // Bypass Eloquent so a view does not bump updated_at.
        Article::whereKey($article->id)->toBase()->increment('views');
        session(['viewed_articles' => [...$viewed, $article->id]]);
    }
}
