<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'friend_id', 'status', 'message'];

    // 用户的朋友关系
     public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function friend()
    {
        return $this->belongsTo(User::class,'friend_id');
    }
    public function sender()
{
    return $this->belongsTo(User::class, 'user_id');
}
public function receiver()
{
    return $this->belongsTo(User::class, 'friend_id');
}

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // // 朋友的用户信息
    // public function friend()
    // {
    //     return $this->belongsTo(User::class, 'friend_id');
    // }
}

