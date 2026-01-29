<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
     protected $table = 'messages'; // messages 表名
        protected $fillable = [
        'from_user_id',
        'to_user_id',
        'sender_id',
        'receiver_id',
        'user_id',      // ✅ 接收用户（通知用）
        'event_id',     // ✅ 活动关联（通知用）
        'type',         // ✅ 消息类型：chat / notification
        'content',
        'is_read',
    ];

    // 可选：默认值
    protected $attributes = [
        'is_read' => false,
        'type'    => 'chat',
    ];

    // 关联聊天
     public function fromUser()
    {
        return $this->belongsTo(User::class,'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class,'to_user_id');
    }

     // 如果需要，可以加关联
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
    // public function chat()
    // {
    //     return $this->belongsTo(Chat::class);
    // }

    // // 关联发送者
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}

