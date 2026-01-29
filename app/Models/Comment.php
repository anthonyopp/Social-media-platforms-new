<?php

namespace App\Models;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'post_id', 'content', 'parent_id', 'status'];

    // 关联用户
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 关联帖子
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    // App\Models\Comment.php
// public function likes()
// {
//     return $this->hasMany(CommentVote::class)->where('vote', 'like');
// }

// public function dislikes()
// {
//     return $this->hasMany(CommentVote::class)->where('vote', 'dislike');
// }


 // 评论的子回复
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // 评论的父评论
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // App\Models\Comment.php
public static function boot()
{
    parent::boot();

    static::deleting(function ($comment) {
        // 删除子评论
        foreach ($comment->replies as $reply) {
            $reply->delete(); // 会触发 deleting 事件，继续递归
        }
    });
}

public function getStatusLabelAttribute()
{
    return match ($this->status) {
        'discussion' => '期待讨论',
        'resolved' => '已解决',
        default => null,
    };
}

public function getStatusClassAttribute()
{
    return match ($this->status) {
        'discussion' => 'badge bg-warning text-dark',
        'resolved' => 'badge bg-success',
        default => '',
    };
}


}

