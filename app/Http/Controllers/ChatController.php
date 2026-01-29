<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Models\Friend;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
{
    $userId = Auth::id();

    // 我加别人并且被接受
    $myFriends = \DB::table('friends')
        ->where('user_id', $userId)
        ->where('status', 'accepted')
        ->pluck('friend_id');

    // 别人加我并且我接受
    $friendOfMine = \DB::table('friends')
        ->where('friend_id', $userId)
        ->where('status', 'accepted')
        ->pluck('user_id');

    // 合并两边
    $allFriendIds = $myFriends->merge($friendOfMine)->unique();

    // 查出好友信息
    $friends = \App\Models\User::whereIn('user_id', $allFriendIds)->get();

    return view('chat.index', [
        'friends' => $friends,
        'currentFriend' => null,
        'messages' => [],
    ]);
}

//     public function index()
// {
//     $friends = Auth::user()
//         ->friends()
//         ->wherePivot('status', 'accepted')
//         ->get()
//         ->unique('id')   // ✅ 根据用户 id 去重
//         ->values();      // ✅ 重建索引，避免 Blade 循环异常

//     return view('chat.index', [
//         'friends' => $friends,
//         'currentFriend' => null,
//         'messages' => [],
//     ]);
// }


    // 加载某个好友的聊天记录
    // 加载某个好友的聊天记录
public function load(User $friend)
{
    if (!$friend) {
        return response()->json([
            'error' => '好友不存在'
        ], 404);
    }

    $userId = Auth::id(); // 当前登录用户的 id

    // 👇 检查 friend 的真实主键字段
    $friendId = $friend->id ?? $friend->user_id;

    $messages = Message::where(function ($q) use ($friendId, $userId) {
            $q->where('from_user_id', $userId)
              ->where('to_user_id', $friendId);
        })
        ->orWhere(function ($q) use ($friendId, $userId) {
            $q->where('from_user_id', $friendId)
              ->where('to_user_id', $userId);
        })
        ->orderBy('created_at', 'asc')
        ->get();

    return response()->json([
        'friend' => [
            'id' => $friendId,
            'name' => $friend->name,
            'avatar' => $friend->avatar ?? asset('images/defaultaaa.webp'),
            'is_online' => (bool) ($friend->is_online ?? false),
        ],
        'messages' => $messages->map(function ($m) {
            return [
                'id' => $m->id,
                'from_user_id' => $m->from_user_id,
                'to_user_id' => $m->to_user_id,
                'content' => $m->content,
                'created_at' => $m->created_at->format('Y-m-d H:i'),
            ];
        })->values(),
    ]);
}

    // 发送消息
    public function send(Request $request)
{
    $validated = $request->validate([
        'to_user_id' => 'required|exists:user,user_id',
        'content' => 'required|string|max:1000',
    ]);

    $message = \App\Models\Message::create([
        'from_user_id' => auth()->id(),
        'to_user_id'   => $validated['to_user_id'],
        'content'      => $validated['content'], // ✅ 注意是 content
    ]);

    return response()->json([
        'success' => true,
        'content' => $message->content
    ]);
}

public function destroy($id)
{
    $msg = Message::where('id', $id)
                  ->where('from_user_id', auth()->id()) // 只能删自己的
                  ->firstOrFail();

    $msg->delete();

    return response()->json(['success' => true]);
}

}
