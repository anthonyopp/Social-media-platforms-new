<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // 关联聊天用户（many-to-many）
    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_users');
    }

    // 关联消息
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
