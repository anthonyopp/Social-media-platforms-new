<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    // 语言切换方法
    public function switch($locale)
    {
        // 支持的语言列表
        $availableLocales = ['en', 'zh'];

        // 如果语言有效，进行切换
        if (in_array($locale, $availableLocales)) {
            Session::put('locale', $locale);
            App::setLocale($locale);
        }

        // 返回上一页
        return redirect()->back();
    }
}
