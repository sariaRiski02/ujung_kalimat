<?php

namespace App\Models;


use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Article extends Model
{
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'title',
        'content',
        'status',
        'is_premium'
    ];

    protected function cleanContent(): Attribute
    {
        return Attribute::make(
            get: fn () => strip_tags($this->content)
        );
    }

    public function getSlugOptions(): SlugOptions{
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    
    protected static function booted()
    {
        static::creating(function(Article $article){
            $article->content = implode("",$article->content);
        });

        static::creating(function(Article $article){
            $article->user_id = Auth::user()->id ?? User::first()->id;
        });
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function image()
    {
        return $this->hasMany(Image::class);
    }
}
