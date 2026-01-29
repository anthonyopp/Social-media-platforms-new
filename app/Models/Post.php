<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\User;
use App\Models\Like;
use App\Models\Favorite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = ['user_id', 'title', 'content', 'images', 'video', 'tags'];

    // 关联作者
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // 关联点赞（many-to-many）
    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id');
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'likes', 'post_id', 'user_id')->withTimestamps();
         // 'post_user', 'post_id', 'user_id'
    }

    // 关联评论
    public function comments()
{
    return $this->hasMany(Comment::class)->whereNull('parent_id');
}

    // public function comments()
    // {
    //     return $this->hasMany(Comment::class);
    // }
    // Post 模型里
    // public function parentComments()
    // {
    //     return $this->comments()->whereNull('parent_id');
    // }


    // 收藏关系
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'post_id');
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites', 'post_id', 'user_id')->withTimestamps();
         // 'post_user', 'post_id', 'user_id'
    }

    // public function isFavoritedBy($user)
    // {
    //     if (!$user) return false;
    //     return $this->favorites()->where('user_id', $user->id)->exists();
    // }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

}
