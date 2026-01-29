<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'owner_id'];

    // 群组的所有者
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // 群组成员
    public function members()
    {
        return $this->hasMany(GroupMember::class);
    }
}
