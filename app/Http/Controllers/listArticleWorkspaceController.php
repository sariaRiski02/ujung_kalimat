<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class listArticleWorkspaceController extends Controller
{

    public function index(Request $request){
        $query = Article::query()->where('user_id', Auth::user()->id);
        if($request->filled('search')){
            $searchTerm = $request->search;

            $premiumMap = [
                'premium' => 1,
                'gratis' => 0,
                'free' => 0,
                'berbayar' => 1
            ];
            $premiumValue = $premiumMap[strtolower($searchTerm)] ?? null;

            $query->where(function($q) use ($searchTerm, $premiumValue){
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('content', 'like', "%{$searchTerm}%")
                    ->orWhere('status', 'like', "{$searchTerm}");

                if(!is_null($premiumValue)){
                    $q->orWhere('is_premium', $premiumValue);
                }
            });
        }
        
        $totalArticles = (clone $query)->count();
        $draftCount = (clone $query)->where('status', 'draft')->count();
        $publishedCount = (clone $query)->where('status', 'published')->count();

        if($request->filter == 'draft'){
            $query->where('status', 'draft');
        }elseif($request->filter == 'published'){
            $query->where('status', 'published');
        }

        $articles = $query->latest()->paginate(10)->withQueryString();
    
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
