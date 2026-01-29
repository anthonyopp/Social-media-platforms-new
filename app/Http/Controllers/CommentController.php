<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
     public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment = $post->comments()->create([
            'user_id'   => auth()->id(),
            'content'   => $request->content,
            'parent_id' => $request->parent_id ?? null,
        ]);
        // 默认最新
$comments = Comment::where('post_id', $post->id)
    ->orderBy('created_at', 'desc')
    ->get();


        $avatar = optional($comment->user->profile)->profile_picture
        ? asset('storage/images/avatar/' . $comment->user->profile->profile_picture) . '?v=' . time()
        : asset('images/defaultaaa.webp');

        return response()->json([
            'success' => true,
            'commentCount' => $post->comments()->count(), // 返回最新数量
            'comment' => [
                'id'        => $comment->id,
                'content'   => $comment->content,
                'parent_id' => $comment->parent_id,
                'user'      => [
                    'id'   => $comment->user->id,
                    'name' => $comment->user->name,
                    'role'   => $comment->user->role, // 直接从 users 表
                    'avatar' => $avatar,              // 从 user_profile 表
                ],
                'commentCount' => $post->comments()->count(),
            ],
        ]);
    }

    public function update(Request $request, Comment $comment)
{
    // 只能评论作者本人修改，并且仅限一天内
    if ($comment->user_id !== auth()->id()) {
        return response()->json(['error' => '无权限'], 403);
    }

    if ($comment->created_at->lt(now()->subDay())) {
        return response()->json(['error' => '评论已超过修改期限'], 403);
    }

    $request->validate([
        'content' => 'required|string|max:500',
    ]);

    $comment->update(['content' => $request->content]);

    return response()->json([
        'success' => true,
        'content' => $comment->content,
    ]);
}

public function updateStatus(Request $request, Comment $comment)
{
    $request->validate([
        'status' => 'nullable|in:discussion,resolved',
    ]);

    $comment->status = $request->status;
    $comment->save();

    return response()->json([
        'success' => true,
        'id' => $comment->id,
        'status' => $comment->status,
        'statusLabel' => $comment->status_label,
        'statusClass' => $comment->status_class,
    ]);
}

    public function destroy($id)
{
    $comment = Comment::find($id);

    if (!$comment) {
        // ✅ 这里返回 success，让前端安全移除 DOM，不报错
        return response()->json([
            'success' => true,
            'message' => '评论已不存在'
        ]);
    }

    // 确保只有作者或管理员能删除
    if (auth()->id() !== $comment->user_id) {
        return response()->json(['error' => '无权删除此评论'], 403);
    }

    $postId = $comment->post_id;
    $comment->delete();

    $remainingComments = Comment::where('post_id', $postId)->count();

    return response()->json([
        'success' => true,
        'message' => '评论删除成功',
        'commentCount' => $remainingComments
    ]);
}


    public function like(Comment $comment)
{
    $comment->increment('likes_count');
    return response()->json(['likes_count' => $comment->likes_count]);
}

public function dislike(Comment $comment)
{
    $comment->increment('dislikes_count');
    return response()->json(['dislikes_count' => $comment->dislikes_count]);
}

public function vote(Comment $comment, Request $request)
{
    $request->validate([
        'vote' => 'required|in:like,dislike',
    ]);

    $userId = auth()->id();
    $likedUsers = $comment->liked_users ? json_decode($comment->liked_users, true) : [];
    $dislikedUsers = $comment->disliked_users ? json_decode($comment->disliked_users, true) : [];

    if ($request->vote === 'like') {
        if (in_array($userId, $likedUsers)) {
            // 取消点赞
            $likedUsers = array_diff($likedUsers, [$userId]);
            $comment->likes_count -= 1;
        } else {
            // 点赞
            $likedUsers[] = $userId;
            $comment->likes_count += 1;

            // 如果点过踩 -> 取消点踩
            if (in_array($userId, $dislikedUsers)) {
                $dislikedUsers = array_diff($dislikedUsers, [$userId]);
                $comment->dislikes_count -= 1;
            }
        }
    } else { // dislike
        if (in_array($userId, $dislikedUsers)) {
            // 取消点踩
            $dislikedUsers = array_diff($dislikedUsers, [$userId]);
            $comment->dislikes_count -= 1;
        } else {
            // 点踩
            $dislikedUsers[] = $userId;
            $comment->dislikes_count += 1;

            // 如果点过赞 -> 取消点赞
            if (in_array($userId, $likedUsers)) {
                $likedUsers = array_diff($likedUsers, [$userId]);
                $comment->likes_count -= 1;
            }
        }
    }

    // 保存更新后的数据
    $comment->liked_users = json_encode(array_values($likedUsers));
    $comment->disliked_users = json_encode(array_values($dislikedUsers));
    $comment->save();

    // 构造头像路径（保证返回时带头像，和 comment() 一致）
    $avatar = $comment->user->profile && $comment->user->profile->profile_picture && $comment->user->profile->profile_picture !== 'default-avatar.webp'
        ? asset('storage/images/avatar/' . $comment->user->profile->profile_picture) . '?v=' . time()
        : asset('images/default-avatar.webp');

    return response()->json([
        'likes_count'   => $comment->likes_count,
        'dislikes_count'=> $comment->dislikes_count,
        'liked'         => in_array($userId, $likedUsers),   // 👍 状态
        'disliked'      => in_array($userId, $dislikedUsers),// 👎 状态
        'user' => [
            'id'     => $comment->user->id,
            'name'   => $comment->user->name,
            'role'   => $comment->user->role,
            'avatar' => $avatar,
        ],
    ]);
}

public function togglePin(Comment $comment)
{
    // 只有帖子作者才能置顶评论
    if (auth()->id() !== $comment->post->user_id) {
        return response()->json(['error' => '无权限'], 403);
    }

    // 切换置顶状态
    $comment->pinned = !$comment->pinned;
    $comment->save();

    return response()->json([
        'success' => true,
        'pinned' => $comment->pinned,
        'id' => $comment->id
    ]);
}


}
