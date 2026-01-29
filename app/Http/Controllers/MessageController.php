<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
{
    $user = auth()->user();

    // 按时间倒序取出通知
    $notifications = \App\Models\Notification::where('user_id', $user->user_id)
        ->orderBy('created_at', 'desc')
        ->get();

    // 用户一旦进入通知页面，就把所有未读的更新为已读
    \App\Models\Notification::where('user_id', $user->user_id)
        ->where('is_read', 0)
        ->update(['is_read' => 1]);

    // 获取好友请求（status = pending，且 friend_id 是自己）
    $friendRequests = \App\Models\Friend::where('friend_id', $user->user_id)
        ->where('status', 'pending')
        ->with('sender') // sender 是发起人关系
        ->get();

    // 返回到视图，同时传递 notifications 和 friendRequests
    return view('message', [
        'notifications'  => $notifications,
        'friendRequests' => $friendRequests,
    ]);
}

    // 显示消息列表
//     public function index()
// {
//     $user = auth()->user();

//     // 按时间倒序取出通知
//     $notifications = \App\Models\Notification::where('user_id', auth()->user()->user_id)
//         ->orderBy('created_at', 'desc')
//         ->get();

//         // 用户一旦进入通知页面，就把所有未读的更新为已读
//     Notification::where('user_id', $user->user_id)
//         ->where('is_read', 0)
//         ->update(['is_read' => 1]);

//     return view('message', compact('notifications'));
// }

public function markAllRead()
{
    $user = auth()->user();

    Notification::where('user_id', $user->id)
        ->where('is_read', 0)
        ->update(['is_read' => 1]);

    return response()->json(['status' => 'success']);
}


//     public function getMessages(User $user)
// {
//     $currentUserId = auth()->id();

//     $messages = Message::where(function($q) use ($currentUserId, $user) {
//         $q->where('from_user_id', $currentUserId)
//           ->where('to_user_id', $user->id);
//     })->orWhere(function($q) use ($currentUserId, $user) {
//         $q->where('from_user_id', $user->id)
//           ->where('to_user_id', $currentUserId);
//     })->orderBy('created_at')->get();

//     return response()->json([
//         'messages' => $messages->map(function($m){
//             return [
//                 'id' => $m->id,
//                 'content' => $m->content,
//                 'from_user_id' => $m->from_user_id,
//                 'created_at' => $m->created_at->format('Y-m-d H:i')
//             ];
//         })
//     ]);
// }

}
