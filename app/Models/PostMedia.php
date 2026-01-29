<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostMedia extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'media_path'];

    // 媒体属于某个帖子
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
