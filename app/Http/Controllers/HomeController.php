<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;
// use App\Models\Event;

class HomeController extends Controller
{
    // 显示首页或按标签筛选帖子
    public function index(Request $request)
    {
        $tagName = $request->query('tag');
        $tags = Tag::all(); // 获取所有标签
        $pageTitle = '📌 最新帖子';
        $posts = Post::with('user')->latest();

        if ($tagName) {
            $tag = Tag::where('name', $tagName)->first();

            if ($tag) {
                // 标签存在，获取对应的帖子
                $posts = $tag->posts()->with('user')->latest()->get();
                $pageTitle = "📖 {$tag->name}";
            } else {
                // 标签不存在，返回空集合
                $posts = collect();
                $pageTitle = "📖 {$tagName}";
            }
        } else {
            $posts = $posts->get(); // 确保最终是集合
        }

        return view('home', compact('posts', 'tags', 'pageTitle'));
    }

    // 根据标签显示帖子
    public function showByTag(Tag $tag)
    {
        $posts = $tag->posts()->with('user')->latest()->get();
        $tags = Tag::all();
        $pageTitle = "📖 {$tag->name}";

        return view('home', compact('posts', 'tags', 'pageTitle'));
    }

    public function search(Request $request)
    {
        // 获取搜索关键词
        $search = $request->input('search');

        // 根据 post title 搜索，按发布时间降序排序
        $posts = Post::when($search, function ($query) use ($search) {
            $query->where('title', 'like', "%{$search}%");
        })->orderBy('created_at', 'desc')->get();

        // 设置页面标题
        $pageTitle = '文章列表';

        // 如果是 AJAX 请求，返回 HTML 片段
        if ($request->ajax()) {
            return view('home', compact('posts', 'search', 'pageTitle'))->render();
        }

        // 返回完整页面
        return view('home', compact('posts', 'search', 'pageTitle'));
    }
}
