<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /** @use HasFactory<\Database\Factories\ImageFactory> */
    use HasFactory;


    protected $fillable = [
        'url',
        'article_id'
    ];


    public function article()
    {
        return $this->belongsTo(Article::class);
    }

}
