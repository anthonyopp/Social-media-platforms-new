<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     return $next($request);
    // }
    public function handle($request, Closure $next)
    {
        // 如果 Session 中存在 locale，则设置语言
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } else {
            // 如果 Session 没有 locale，则使用默认语言
            App::setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
