<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    /** The attributes that aren't mass assignable. */
    protected $guarded = [];

    /** One to many relation with news_comments table */
    public function comments()
    {
        return $this->hasMany(NewsComment::class, 'news_id', 'id');
    }
}
