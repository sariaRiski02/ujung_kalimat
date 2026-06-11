<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class listArticleWorkspaceController extends Controller
{

    public function index(Request $request){
    
        

        $articles = Article::latest();
        $statuses = $articles->get()->pluck('status')->countBy();
        $draftCount = $statuses['draft'];
        $publishedCount = $statuses['published'];
        $totalArticles = $articles->count();
        

        if(Auth::check()){
            $articles = Article::where('user_id', Auth::user()->id)->latest();
            $statuses = $articles->get()->pluck('status')->countBy();
            $draftCount = $statuses['draft'];
            $publishedCount = $statuses['published'];
            $totalArticles = $articles->count();
        }
        
        if($request->filled(['filter', 'search']) ){
            
            $articles->where('status', $request->query('filter'))->latest();
            
        }
        $articles = $articles->paginate(10);

    
        return view('pages.pageWorkspace.listArticle', compact('articles','publishedCount', 'draftCount','totalArticles'));
    }

    public function status(Article $article){
        $article->status =  $article->status == 'draft' ? 'published' : 'draft';
        $article->save();
        return redirect()->back()->with('success','status article has changed');
    }

    public function type(Article $article){
        $article->is_premium = !$article->is_premium;
        $article->save();
        return redirect()->back()->with('success', 'type article has changed');
    }

    
}
