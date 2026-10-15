<?php

namespace App\Models;

use App\Services\HelperService;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory;

    use HasSlug;

    protected $fillable = [
        'title',
        'content',
        'status',
        'is_premium',
    ];

    protected function cleanContent(): Attribute
    {
        return Attribute::make(
            get: fn () => strip_tags($this->content)
        );
    }

    protected function arrayContent(): Attribute
    {
        $service = app(HelperService::class);

        return Attribute::make(
            get: fn () => $service->extractTagContent($this->content)
        );
    }

    protected function cleanTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => strip_tags($this->title)
        );
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * @param  array<int, array<string, string>>  $blocks
     */
    protected static function renderContent(array $blocks): string
    {
        return implode('', array_map(fn ($item) => match ($item['type']) {
            'paragraph' => '<p>'.$item['text'].'</p>',
            'image' => '<img src="'.e($item['url']).'" alt="">',
            default => '<blockquote>'.$item['text'].'</blockquote>',
        }, $blocks));
    }

    protected static function booted()
    {
        static::creating(function (Article $article) {

            $article->content = static::renderContent($article->content);
        });

        static::updating(function (Article $article) {

            if (is_array($article->content)) {
                $article->content = static::renderContent($article->content);
            }
        });

        static::creating(function (Article $article) {
            $article->user_id = Auth::user()->id ?? User::first()->id;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function image()
    {
        return $this->hasMany(Image::class);
    }
}
