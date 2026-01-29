<?php

namespace App\Helpers;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Cache;

class Translator
{
    public static function translate($text, $target = 'en')
    {
        // 设置主语言（例如中文）
        $sourceLang = 'zh';

        // 如果目标语言与源语言相同，直接返回原文本
        if ($target === $sourceLang) {
            return $text;
        }

        // 缓存翻译结果，避免重复调用 API
        $cacheKey = "translation:{$sourceLang}_to_{$target}:" . md5($text);
        return Cache::remember($cacheKey, now()->addDays(7), function () use ($text, $target) {
            return GoogleTranslate::trans($text, $target);
        });
    }
}
