<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    // App\Providers\EventServiceProvider.php
protected $listen = [
    'Illuminate\Auth\Events\Login' => [
        \App\Listeners\SetUserOnline::class,
    ],
    'Illuminate\Auth\Events\Logout' => [
        \App\Listeners\SetUserOffline::class,
    ],
];

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
