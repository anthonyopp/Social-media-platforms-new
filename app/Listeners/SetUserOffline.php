<?php

namespace App\Listeners;
// namespace App\Providers;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

// class SetUserOffline extends ServiceProvider
class SetUserOffline
{
     public function handle(Logout $event)
    {
        $user = $event->user;

        // ✅ 只处理普通用户，不影响 Admin
        if ($user instanceof \App\Models\User) {
            $user->is_online = false;
            $user->save();
        }
    }
    // public function handle(Logout $event)
    // {
    //     $user = $event->user;
    //     $user->is_online = false;
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
