<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Profile extends Model
{
    protected $table = 'user_profile';

    protected $fillable = [
        'user_id',
        'profile_picture',
        'background_image',
        'bio',
        'signature',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
