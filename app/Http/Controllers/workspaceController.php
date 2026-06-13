<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Support\Facades\Auth;

class workspaceController extends Controller
{


    public function __construct(
        protected ArticleService $service   
    ){}

    public function dashboard(){
        return view('pages.pageWorkspace.dashboard');
    }

    public function write(){
        return view('pages.pageWorkspace.write');
    }

    public function update(Article $article){
        
        $contents = $article->array_content;
        $title = $article->clean_title;
        $article->load('image');

        return view('pages.pageWorkspace.update', compact(
            'article','contents', 'title'
        ));
    }

    public function edit(StoreArticleRequest $request, Article $article){
        $this->service->update($article, $request);
        return redirect()->route('workspace.articles');
    }

    public function store(StoreArticleRequest $request){
        $this->service->store($request);
        return redirect()->route('workspace.articles');
    }


    
    public function monetization(){
        return view('pages.pageWorkspace.monetization');
    }
    public function following(){
        return view('pages.pageWorkspace.following');
    }

    public function followers(){
        return view('pages.pageWorkspace.followers');
    }

    public function profile(){
        return view('pages.pageWorkspace.profile');
    }

    public function analytics(){
        return view('pages.pageWorkspace.analyticDetailArticle');
    }

    
    
}
