<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Models\Post;
use App\Models\Comment;

class ProfileController extends Controller
{
    public function profile($id = null)
{
    // 验证是否登录
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', '请先登录');
    }

    // 如果传了 id，就显示该用户的资料，否则显示自己的
    $user = $id ? User::with('profile')->findOrFail($id) : Auth::user();

    return view('profile.index', compact('user'));
}
public function show($id) {
    $user = User::with('profile')->findOrFail($id);

     // 用户的帖子
    $posts = $user->posts()->latest()->get();

      // 活动帖子（用户自己发过的活动）
    $eventPosts = $user->eventPosts()->latest()->get();

    // 用户的评论，并判断每条评论是否有新回复
    $replies = $user->comments()->with(['post', 'replies'])->latest()->get()->map(function ($comment) {
        // 判断是否有新回复（假设 replies 表有 is_new 字段）
        $comment->hasNewReply = $comment->replies()->exists();
        return $comment;
    });

    // 用户收藏的帖子
    $likedPosts = $user->favorites()->with('post')->latest()->get();

    return view('profile.show', compact('user', 'posts', 'eventPosts', 'replies', 'likedPosts'));
}


//     public function profile()
// {
//     // 验证用户是否已登录
//     if (!Auth::check()) {
//         return redirect()->route('login')->with('error', '请先登录');
//     }

//     $user = Auth::user();

//     return view('profile.index');
// }


    public function updateImage(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'profile_picture' => 'nullable',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $profile = $user->profile ?? $user->profile()->create();

        // 头像处理
        if ($request->hasFile('profile_picture')) {
        $file = $request->file('profile_picture');
        $filename = 'avatar_' . $user->user_id . '.' . 'webp';

        // 删除旧头像（如果存在）
        $oldPath = storage_path('app/public/images/avatar/' . $filename);
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }

        // 存储新头像
        $file->storeAs('images/avatar', $filename);
        $profile->profile_picture = $filename;
        } elseif ($request->filled('selected_avatar')) {
            $selectedAvatar = $request->input('selected_avatar'); // e.g. preset-12.webp
            $extension = pathinfo($selectedAvatar, PATHINFO_EXTENSION);
            $filename = 'avatar_' . $user->user_id . '.' . $extension;

            $sourcePath = public_path('images/preset-avatar/' . $selectedAvatar);
            $destinationPath = storage_path('app/public/images/avatar/' . $filename);

            if (file_exists($sourcePath)) {
                // 确保目标目录存在
                if (!File::exists(dirname($destinationPath))) {
                    File::makeDirectory(dirname($destinationPath), 0755, true);
                }

                File::copy($sourcePath, $destinationPath);
                $profile->profile_picture = $filename;
            } else {
                // 如果预设头像找不到，就用默认头像
                $profile->profile_picture = 'defaultaaa.webp';
            }
        }

        // 背景图处理
        if ($request->hasFile('background_image')) {
            $filename = 'bg_' . $user->user_id . '.' . $request->file('background_image')->extension();

            // 删除旧背景图（如果存在）
            $oldFilePath = storage_path('app/public/images/bg/' . $filename);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath); // 删除旧文件
            }

            // 上传新背景图
            $request->file('background_image')->storeAs('images/bg', $filename);
            $profile->background_image = $filename;
        }

        $profile->save();

        return redirect()->back()->with('success', '头像和背景图已更新！');
    }

    public function updateSignature(Request $request)
    {
        $request->validate([
            'signature' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        if (!$user->profile) {
            $user->profile()->create(['signature' => $request->signature]);
        } else {
            $user->profile->update(['signature' => $request->signature]);
        }

        return response()->json(['success' => true]);
    }

    public function showProfileContent()
{
    // 验证用户是否已登录
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', '请先登录');
    }

    $user = auth()->user();

    // 用户的帖子
    $posts = $user->posts()->latest()->get();

      // 活动帖子（用户自己发过的活动）
    $eventPosts = $user->eventPosts()->latest()->get();

    // 用户的评论，并判断每条评论是否有新回复
    $replies = $user->comments()->with(['post', 'replies'])->latest()->get()->map(function ($comment) {
        // 判断是否有新回复（假设 replies 表有 is_new 字段）
        $comment->hasNewReply = $comment->replies()->exists();
        return $comment;
    });

    // 用户收藏的帖子
    $likedPosts = $user->favorites()->with('post')->latest()->get();

    return view('profile.index', compact('posts', 'eventPosts', 'replies', 'likedPosts'));
}


    // public function profileContent()
    //     {
    //         $user = Auth::user();

    //         return view('profile.content', [
    //             'posts' => $user->posts,
    //             'replies' => $user->comments()->with('post')->latest()->get(),
    //             'likedPosts' => $user->favorites()->with('post')->get()->pluck('post'),
    //         ]);
    //     }

    // public function updateProfile(Request $request)
    // {
    //     $request->validate(['name' => 'required|max:255']);

    //     $user = Auth::user();
    //     $user->name = $request->name;
    //     $user->save();

    //     return back()->with('success', '资料更新成功！');
    // }
}
