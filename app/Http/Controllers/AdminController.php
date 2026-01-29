<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    if (Auth::guard('admin')->attempt($credentials)) {
        $request->session()->regenerate();

        return response()->json([
            'status' => 'success',
            'message' => '管理员登录成功',
            'redirect' => route('admin.dashboard'),
        ]);
    }

    return response()->json([
        'status' => 'error',
        'message' => '邮箱或密码错误，请重试',
    ], 200); // 401 Unauthorized
}


    // 仪表盘
    public function dashboard()
    {
        // 数据统计
        $totalUsers = User::count();
        $totalPosts = Post::count();
        $totalComments = Comment::count();

        // 数据表
        $users = User::latest()->take(10)->get(); // 最近10个用户
        $posts = Post::with('user')->latest()->take(10)->get(); // 最近10个帖子
        $comments = Comment::with(['user','post'])->latest()->take(10)->get(); // 最近10条评论

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPosts',
            'totalComments',
            'users',
            'posts',
            'comments'
        ));
    }

    // 退出登录
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login'); // 退出后跳转到登录页
    }

    // 删除用户
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', '用户已删除');
    }

    // 封禁用户（这里简单实现为更新 status 字段）
    public function banUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'banned';
        $user->save();

        return back()->with('success', '用户已封禁');
    }

    // 删除帖子
    public function deletePost($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return back()->with('success', '帖子已删除');
    }

    // 删除评论
    public function deleteComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return back()->with('success', '评论已删除');
    }
}
