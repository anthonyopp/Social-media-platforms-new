<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id', 'receiver_id', 'status'];

    // 关联发送者
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // 关联接收者
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // 接受好友请求
    public function accept()
    {
        $this->update(['status' => 'accepted']);

        // 添加到好友列表
        $this->receiver->friends()->attach($this->sender_id);
        $this->sender->friends()->attach($this->receiver_id);
    }
}
