<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Profile;
use App\Models\Friend;
use App\Models\Message;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'user'; // 指定 user 表，而不是 users
    protected $primaryKey = 'user_id'; // 这里改成 'user_id'
    public $incrementing = true; // 保持自增
    protected $keyType = 'int';

    protected $fillable = ['name', 'email', 'password', 'profile_picture', 'role', 'is_online'];

    protected $hidden = ['password'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // 在用户注册后自动为其创建个人资料
            \App\Models\Profile::create([
                'user_id' => $user->user_id,
                'profile_picture' => 'default-avatar.webp', // 默认头像
                'background_image' => 'default-bg.jpeg', // 默认背景图
            ]);
        });
    }

    // 用户发布的帖子
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'user_id');
    }

    // 用户的点赞
    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    // 用户的评论
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    // 用户的收藏
//     public function favorites()
// {
//     return $this->belongsToMany(Post::class, 'favorites') // 第一个是目标模型，第二个是中间表名
//                 ->withTimestamps(); // ✅ 允许你访问点赞时间
// }

     public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }

    // public function favoritePosts()
    // {
    //     return $this->belongsToMany(Post::class, 'favorites', 'user_id', 'post_id')->withTimestamps();
    // }

    // public function hasFavorited(Post $post)
    // {
    //     return $this->favorites()->where('post_id', $post->id)->exists();
    // }


    // 用户参加的活动
    public function events()
{
    return $this->belongsToMany(Event::class, 'event_user','user_id', 'event_id');
}

public function eventPosts()
{
    return $this->hasMany(Event::class, 'user_id', 'user_id');
}

public function reminders() {
    return $this->hasMany(Reminder::class);
}

public function remindedEvents() {
    return $this->belongsToMany(Event::class, 'reminders')->withPivot('remind_at')->withTimestamps();
}



    // public function events()
    // {
    //     return $this->belongsToMany(Event::class, 'event_user');
    // }

    // 用户的好友（双向关联）
    public function friends()
{
    return $this->belongsToMany(
        User::class,        // 关联到 User 模型
        'friends',          // 中间表
        'user_id',          // 当前用户对应的外键
        'friend_id'         // 朋友对应的外键
    )->withPivot('status')->withTimestamps();
}

// 如果需要反向关系（别人加你为好友）
public function friendOf()
{
    return $this->belongsToMany(
        User::class,
        'friends',
        'friend_id',
        'user_id'
    )->withPivot('status')->withTimestamps();
}

    public function messages()
    {
        return $this->hasMany(Message::class, 'from_user_id');
    }
    // public function friends()
    // {
    //     return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id');
    // }

    // // 用户的好友请求
    // public function friendRequests()
    // {
    //     return $this->hasMany(FriendRequest::class, 'receiver_id');
    // }

    // 用户的聊天记录
    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'chat_users');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }
}
