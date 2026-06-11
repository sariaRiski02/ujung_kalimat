<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(){
    $articles = Article::with(['user','image'])->latest()->take(10)->get();

        return view('pages.index', compact('articles'));
    }

    public function show(Article $article){
        $article->load(['user', 'image']);
        $articles = Article::with(['user', 'image'])->latest()->take(5)->get();
        return view('pages.pageArticle.show', compact('article', 'articles'));
    }

    public function signin(){
        return view('pages.auth.signin');
    }

    public function signup(){
        return view('pages.auth.signup');
    }
}
