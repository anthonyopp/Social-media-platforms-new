<?php

namespace App\Listeners;
// namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

// class SetUserOnline extends ServiceProvider
class SetUserOnline
{
    public function handle(Login $event)
    {
        $user = $event->user;

        // ✅ 只对 App\Models\User 更新，不影响 Admin
        if ($user instanceof \App\Models\User) {
            $user->is_online = true;
            $user->save();
        }
    }
    // public function handle(Login $event)
    // {
    //     $user = $event->user;
    //     $user->is_online = true;
    //     // $user->last_seen = now();
    //     $user->save();
    // }
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
