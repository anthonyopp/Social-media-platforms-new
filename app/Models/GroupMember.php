<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;

    protected $fillable = ['group_id', 'user_id'];

    // 关联群组
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    // 关联用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

