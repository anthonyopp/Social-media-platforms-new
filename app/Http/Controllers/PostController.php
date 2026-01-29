<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Artisan;
// use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        // 获取固定标签
        $allowedTags = [
            '课程交流', '作业求助', '考试经验', '考研/留学', '实习与就业',
            '校园新闻', '活动通知', '社团招新', '宿舍生活', '失物招领',
            '动漫游戏', '影视剧集', '小说分享', '校园八卦', '运动健身',
            '编程交流', '硬件DIY', '黑客技术', '开源项目', 'AI与机器学习',
            '二手书籍', '电子产品', '服饰美妆', '拼单团购', '兼职与副业',
            '树洞倾诉', '情感咨询', '日常吐槽', '正能量分享', '人生规划'
        ];

        // 验证请求数据
        $validatedData = $request->validate([
            'title'   => 'required|max:255',
            'content' => 'required',
            'tags'    => 'required|array',
            'tags.*' => Rule::in($allowedTags), // tags 必须是固定值
            'images'   => 'nullable|array|max:4', // 允许最多上传 4 张图片
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 每张图片最大 2MB
            // 'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'video'   => 'nullable|mimes:mp4,avi,mov,wmv|max:51200' // 限制50MB
        ]);

        $disk = env('FILESYSTEM_DISK', 'public'); // 读取存储类型

        // 处理多图片上传
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('posts', $disk);
            }
        }

        // 创建帖子
        $post = new Post([
            'user_id' => Auth::id(),
            'title'   => $validatedData['title'],
            'content' => $validatedData['content'],
            'tags'    => json_encode($validatedData['tags']), // 将标签数组转换为 JSON
            'images'   => count($imagePaths) > 0 ? json_encode($imagePaths) : null, // 先确保是数组再编码
            // 'image'   => $request->hasFile('image') ? $request->file('image')->store('posts', $disk) : null,
            'video'   => $request->hasFile('video') ? $request->file('video')->store('videos', $disk) : null,
        ]);

            // 确保 public/storage 符号链接存在
            if (!file_exists(public_path('storage'))) {
                Artisan::call('storage:link');
            }


        // 保存帖子
        $post->save();

        // $tagIds = Tag::whereIn('name', $validatedData['tags'])->pluck('id');
        $tagIds = [];
        foreach ($validatedData['tags'] as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]); // 如果标签不存在，则创建
            $tagIds[] = $tag->id;
        }
        $post->tags()->attach($tagIds);

        return redirect()->route('home')->with('success', '帖子发布成功！');
    }

    public function like(Post $post)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', '请先登录再进行点赞操作');
        }

        $user = auth()->user();

        // 检查用户是否已点赞
        $like = $user->likes()->where('post_id', $post->id)->first();

        if ($like) {
            // 取消点赞
            $like->delete();
            $isLiked = false;
        } else {
            // 添加点赞
            $user->likes()->create(['post_id' => $post->id]);
            $isLiked = true;
        }

        return response()->json([
            'isLiked' => $isLiked,
            'likesCount' => $post->likes()->count(),
        ]);
    }

public function toggleFavorite(Post $post)
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', '请先登录再进行收藏操作');
    }

    $user = auth()->user();

    // 检查用户是否已收藏
    $favorite = $user->favorites()->where('post_id', $post->id)->first();

    if ($favorite) {
        // 已收藏 -> 取消
        $favorite->delete();
        $isFavorited = false;
    } else {
        // 未收藏 -> 添加
        $user->favorites()->create(['post_id' => $post->id]);
        $isFavorited = true;
    }

    return response()->json([
        'isFavorited' => $isFavorited,
        'favoritesCount' => $post->favorites()->count(),
    ]);
}

    public function show($id)
    {
        $post = Post::with(['user', 'comments.user'])->findOrFail($id);

        return view('posts.show', compact('post'));
    }


}
