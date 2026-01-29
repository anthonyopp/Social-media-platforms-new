<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
    public function index()
    {
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        return view('settings');
    }

    // 处理用户提交的设置更新
    // public function update(Request $request)
    // {
    //     // 检查语言设置是否存在
    //     $request->validate([
    //         'locale' => 'required|in:en,zh',
    //     ]);

    //     // 更新语言
    //     $locale = $request->input('locale');
    //     Session::put('locale', $locale);
    //     App::setLocale($locale);

    //     return redirect()->back()->with('success', __('message.save_success'));
    // }

    // 直接切换语言
    public function changeLanguage($locale)
{
    // 检查语言是否支持（中文、英文、马来文）
    if (!in_array($locale, ['en', 'zh', 'ms'])) {
        abort(400); // 返回错误
    }

    // 设置语言并存入 Session
    Session::put('locale', $locale);
    App::setLocale($locale);

    // 跳转到首页
    return redirect()->route('home'); // 假设首页路由命名为 'home'
}


}
