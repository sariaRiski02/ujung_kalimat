<?php

namespace App\Services;

use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;

class ArticleService
{

    public function store(StoreArticleRequest $request){
        $article = Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'is_premium' => $request->is_premium,
            'status' => $request->status,
        ]);

        if($request->hasFile('image')){
            $article->image()->create([
                'url' => $request->file('image')->store('articles/covers', 'public')
            ]);
        }


    }
    public function update(Article $article, StoreArticleRequest $request){
        $data = $request->except('image');
        $article->update($data);
        
    }
    public function delete(){}

}
