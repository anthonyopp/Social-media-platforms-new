<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // 显示登录页面
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 处理用户登录
   public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ], [
        'password.min' => '密码长度至少为 6 个字符。',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return response()->json([
            'status' => 'success',
            'message' => '登录成功',
            'redirect' => route('home'),
        ], 200);
    }

    return response()->json([
        'status' => 'error',
         'message' => '邮箱或密码错误，请重试 ',
    ], 200); // ✅ 注意这里也返回 200
}


    // 处理用户登出
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', '已成功登出');
    }

    // 显示注册页面
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // 处理注册逻辑
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:teacher,student', // 你可以调整角色规则
        ], [
            'email.unique' => '该邮箱已被注册，请更换邮箱。',
            'password.confirmed' => '两次输入的密码不匹配，请重新输入。',
        ]);
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|unique:user',
        //     'password' => 'required|min:6|confirmed',
        //     'role' => 'required|in:teacher,student', // 确保 role 只能是 teacher 或 student
        // ]);

        // 创建用户
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // 自动登录
        Auth::login($user);

        return redirect()->route('home')->with('success', '注册成功，欢迎使用！');
    }
}

