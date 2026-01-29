<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Friend;
use App\Models\User;
// use App\Models\FriendRequest;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    // 发送好友请求
    public function add(Request $request)
    {
        $data = $request->validate([
            'identifier' => 'required|string',
            'message'    => 'nullable|string|max:200',
        ]);

        $friend = User::where('email', $data['identifier'])
            ->orWhere('name', $data['identifier'])
            ->orWhere('user_id', $data['identifier'])
            ->first();

        if (!$friend) {
            return response()->json(['message' => '用户不存在'], 404);
        }

        if ($friend->id == Auth::id()) {
            return response()->json(['message' => '不能添加自己'], 400);
        }

        // 检查是否已有记录
        $exists = Friend::where('user_id', Auth::id())
            ->where('friend_id', $friend->id)
            ->first();

        if ($exists) {
            return response()->json(['message' => '已发送过请求或已是好友'], 400);
        }

        // 创建请求 (pending)
        Friend::create([
            'user_id'   => Auth::id(),
            'friend_id' => $friend->user_id, // ✅ 用 user_id
            'status'    => 'pending',
            'message'   => $data['message'] ?? null, // ✅ 存储验证消息
        ]);

        return response()->json(['message' => '好友请求已发送，等待对方确认']);
    }

    // 接受好友请求
    public function accept($id)
{
    $request = Friend::where('id', $id)
        ->where('friend_id', Auth::id())
        ->where('status', 'pending')
        ->firstOrFail();

    // 更新原请求为 accepted
    $request->update(['status' => 'accepted']);

    // 确保反向关系也存在（如果不存在就创建）
    $reverse = Friend::where('user_id', Auth::id())
        ->where('friend_id', $request->user_id)
        ->first();

    if (!$reverse) {
        Friend::create([
            'user_id'   => Auth::id(),
            'friend_id' => $request->user_id,
            'status'    => 'accepted',
        ]);
    } else {
        $reverse->update(['status' => 'accepted']);
    }

    return redirect()->back()->with('success', '已接受好友请求');
}

    // 拒绝好友请求
    public function reject($id)
    {
        $request = Friend::where('id', $id)
            ->where('friend_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $request->update(['status' => 'rejected']);

        return redirect()->back()->with('info', '已拒绝好友请求');
    }

public function destroy($friendId)
{
    $userId = auth()->id();

    // 删除双方关系（双向）
    \DB::table('friends')
        ->where(function ($q) use ($userId, $friendId) {
            $q->where('user_id', $userId)
              ->where('friend_id', $friendId);
        })
        ->orWhere(function ($q) use ($userId, $friendId) {
            $q->where('user_id', $friendId)
              ->where('friend_id', $userId);
        })
        ->delete();

    return back()->with('success', '好友已删除');
}

}
