<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\HelperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function __construct(protected HelperService $helper)
    {}

    public function index(){
    $articles = Article::with(['user','image'])->latest()->take(10)->get();

        return view('pages.index', compact('articles'));
    }

    public function show(Article $article){
        $article->load(['user', 'image']);
        $articles = Article::with(['user', 'image'])->latest()->take(5)->get();
        
        $partial = $this->helper->getContent($article->content, 40) . ".... Subscribe for continue";
        if($article->is_premium && !Auth::check()){
            $article->content = $partial;
            return view('pages.pageArticle.show', compact('article', 'articles'));
        }

        if(Auth::user()->isSubscriber()){
            return view('pages.pageArticle.show', compact('article', 'articles'));
        }

        $article->content = $partial;
        return view('pages.pageArticle.show', compact('article', 'articles'));

    }

    public function signin(){
        return view('pages.auth.signin');
    }

    public function signup(){
        return view('pages.auth.signup');
    }
}
