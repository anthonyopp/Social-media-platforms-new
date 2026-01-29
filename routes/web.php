<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
// routes/web.php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\MessageController;
// use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\URL;

// Route::get('/test-url', function () {
//     return URL::to('/');
// });
// Route::get('/set-locale/{locale}', function ($locale) {
//     if (in_array($locale, ['en', 'zh'])) {
//         Session::put('locale', $locale);
//         App::setLocale($locale);
//     }
//     // return redirect()->route('home')->with('success', '帖子发布成功！');

//     return redirect()->back();
// })->name('set-locale');


Route::get('/', [HomeController::class, 'index'])->name('home'); // 主页
Route::get('/posts/search', [HomeController::class, 'search'])->name('posts.search');
Route::get('/tag/{tag}', [HomeController::class, 'showByTag'])->name('tag.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/post/store', [PostController::class, 'store'])->name('post.store');
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show'); // get 原帖
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like'); // 帖子的点赞
    Route::post('/posts/{post}/comment', [CommentController::class, 'store'])->name('comments.store'); // 父和子评论的发表
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update'); // 评论内容的更新
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy'); // 父和子评论的删除
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like'); // 评论的赞
    Route::post('/comments/{comment}/dislike', [CommentController::class, 'dislike'])->name('comments.dislike'); // 评论的踩
    Route::post('/comments/{comment}/vote', [CommentController::class, 'vote'])->name('comments.vote'); // 赞和踩的按钮高亮
    Route::post('/comments/{comment}/pin', [CommentController::class, 'togglePin'])->name('comments.pin'); // 评论的置顶
    Route::post('/comments/{comment}/status', [CommentController::class, 'updateStatus'])->name('comments.updateStatus');
    Route::post('/posts/{post}/favorite', [PostController::class, 'toggleFavorite'])->name('posts.favorite'); // 帖子的收藏
});

// Route::middleware(['auth'])->post('/posts/{post}/like', [PostController::class, 'toggle'])->name('posts.like');

// Route::post('/posts/{post}/like', [PostController::class, 'toggle'])->name('posts.like');

// Route::get('/post/{id}', [PostController::class, 'show'])->name('post.show'); // 查看单个帖子
// Route::post('/post', [PostController::class, 'store'])->name('post.store'); // 创建帖子
// Route::post('/post/{id}/bookmark', [BookmarkController::class, 'bookmark'])->name('post.bookmark'); // 收藏帖子
// Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks'); // 查看收藏的帖子

Route::get('/tuition', [PaymentController::class, 'index'])->name('tuition'); // 查看学费
Route::post('/tuition/pay', [PaymentController::class, 'pay'])->name('tuition.pay'); // 付款

Route::prefix('admin')->name('admin.')->group(function () {
    // 登录/登出
    Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminController::class, 'login'])->name('login.submit');
    Route::post('logout', [AdminController::class, 'logout'])->name('logout');

    // 仪表盘（加上 auth:admin 中间件保护）
    Route::get('dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard')
        ->middleware('auth:admin');

    // 用户管理
Route::delete('admin/user/{id}', [AdminController::class, 'deleteUser'])->name('user.delete');
Route::post('admin/user/{id}/ban', [AdminController::class, 'banUser'])->name('user.ban');

    // 帖子管理
    Route::delete('post/{id}', [AdminController::class, 'deletePost'])->name('post.delete');

    // 评论管理
    Route::delete('comment/{id}', [AdminController::class, 'deleteComment'])->name('comment.delete');
});
// 显示登录和注册页面
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/events', [EventController::class, 'index'])->name('events'); // 活动列表
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events/store', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{id}/cancel', [EventController::class, 'cancel'])->name('events.cancel');
    Route::post('/events/{event}/join', [EventController::class, 'join'])->name('events.join');
    Route::post('/events/{event}/remind', [EventController::class, 'remind'])->name('events.remind');


// Route::post('/event/{id}/join', [EventController::class, 'join'])->name('event.join'); // 参加活动

Route::middleware('auth')->group(function(){
    // 首页聊天页面
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');

    // 加载指定好友聊天记录
    Route::get('/chat/{friend}', [ChatController::class, 'load'])->name('chat.load');

    // 发送消息
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    // Route::get('/chat/messages/{user}', [MessageController::class, 'getMessages']);
    Route::delete('/chat/message/{id}', [ChatController::class, 'destroy'])->name('chat.message.destroy');

    // 添加好友
    Route::post('/friends/add', [FriendController::class, 'add'])->name('friends.add');
    Route::post('/friends/accept/{id}', [FriendController::class, 'accept'])->name('friends.accept');
    Route::post('/friends/reject/{id}', [FriendController::class, 'reject'])->name('friends.reject');
    Route::delete('/friends/{friend}', [FriendController::class, 'destroy'])->name('friends.destroy');
});

// routes/web.php
// Route::post('/posts/{post}/comment', [CommentController::class, 'store'])->name('comments.store');
// routes/web.php
// Route::delete('/replies/{id}', [CommentController::class, 'destroy'])->name('replies.destroy');


Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])
    ->name('notifications.markAllRead');
});
Route::get('/notifications/unread-check', function () {
    $hasUnread = false;

    if (auth()->check()) {
        // 检查未读通知
        $hasNotification = \App\Models\Notification::where('user_id', auth()->id())
            ->where('is_read', 0)
            ->exists();

        // 检查好友请求
        $hasFriendRequest = \App\Models\Friend::where('friend_id', auth()->id())
            ->where('status', 'pending')
            ->exists();

        // 只要有其中一个，就算有未处理事项
        $hasUnread = $hasNotification || $hasFriendRequest;
    }

    return response()->json(['hasUnread' => $hasUnread]);
})->name('notifications.unread-check');

Route::get('/profile', [ProfileController::class, 'profile'])->name('profile'); // 个人资料
// 自己和他人的 Profile 共用同一个方法
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
Route::put('/profile/update-image', [ProfileController::class, 'updateImage'])->name('profile.updateImage');
Route::post('/profile/update-signature', [ProfileController::class, 'updateSignature'])->name('profile.updateSignature');
Route::get('/profile', [ProfileController::class, 'showProfileContent'])->name('profile'); //post replies liked
// Route::get('/profile', [ProfileController::class, 'profileContent'])->name('profileContent');
// Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update'); // 更新资料
Route::get('/user/favorites', [ProfileController::class, 'showFavorites'])->middleware('auth')->name('user.favorites');

// Route::get('/friends', [FriendController::class, 'index'])->name('friends'); // 好友请求页面
// Route::post('/friend/{id}/accept', [FriendController::class, 'accept'])->name('friend.accept'); // 接受好友请求

Route::get('/settings', function () {
    return view('settings');
})->name('settings'); // 设置页面
Route::get('/change-language/{locale}', [SettingController::class, 'changeLanguage'])->name('changeLanguage');
// Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');
// Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
// // Route::post('/settings/switch-language', [SettingController::class, 'switchLanguage'])->name('settings.change');
// Route::get('/change-language/{locale}', [SettingController::class, 'changeLanguage'])->name('language.change');
// // Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');


