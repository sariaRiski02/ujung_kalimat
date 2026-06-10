<?php

namespace App\Models;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Article extends Model
{
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
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
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
