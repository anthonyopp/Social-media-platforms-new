<?php

namespace App\Models;

use App\Models\User;
use App\Models\Remider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // 可以批量赋值的字段
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'location',
        'phone',   // ✅ 新增
        'type',
        'capacity',
        'registration_deadline',
        'requires_registration',
        'status',
        'cover_image',
        'attachment',
        'registered_count',
        'enable_comments',
        'enable_poll',
        'summary',
    ];
    protected $casts = [
    'start_time' => 'datetime',
    'end_time' => 'datetime',
];


    // 关联报名的用户（many-to-many）
    public function users()
{
    // return $this->belongsToMany(User::class, 'event_user', 'event_id', 'user_id', 'id', 'user_id');
    return $this->belongsToMany(User::class, 'event_user', 'event_id', 'user_id');
}

//   public function registrations()
//     {
//         return $this->hasMany(Registration::class);
//     }

public function reminders() {
    return $this->hasMany(Reminder::class);
}

public function remindedUsers() {
    return $this->belongsToMany(User::class, 'reminders')->withPivot('remind_at')->withTimestamps();
}

    // public function attendees()
    // {
    //     return $this->belongsToMany(User::class, 'event_user');
    // }
}
