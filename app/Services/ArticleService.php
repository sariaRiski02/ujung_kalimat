<?php

namespace App\Services;

use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class ArticleService
{
    public function store(StoreArticleRequest $request)
    {
        $article = Article::create([
            'title' => $request->title,
            'content' => $this->prepareContent($request->validated('content') ?? []),
            'is_premium' => $request->is_premium,
            'status' => $request->status,
        ]);

        if ($request->hasFile('image')) {
            $article->image()->create([
                'url' => $request->file('image')->store('articles/covers', 'public'),
            ]);
        }

    }

    public function update(Article $article, StoreArticleRequest $request)
    {
        $data = Arr::except($request->validated(), ['images', 'content']);
        $data['content'] = $this->prepareContent($request->validated('content') ?? []);
        $article->update($data);

    }

    public function delete() {}

    /**
     * Store uploaded images of the content blocks and return the blocks ready to be rendered.
     *
     * @param  array<int|string, array<string, mixed>>  $blocks
     * @return array<int, array<string, string>>
     */
    private function prepareContent(array $blocks): array
    {
        $prepared = [];

        foreach ($blocks as $block) {
            if ($block['type'] !== 'image') {
                $prepared[] = ['type' => $block['type'], 'text' => $block['text']];

                continue;
            }

            $url = ($block['image'] ?? null) instanceof UploadedFile
                ? Storage::url($block['image']->store('articles/content', 'public'))
                : ($block['url'] ?? null);

            if ($url) {
                $prepared[] = ['type' => 'image', 'url' => $url];
            }
        }

        return $prepared;
    }
}
