@extends('layouts.app')

@section('title', '主页')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- 侧边栏 -->
        <div id="sidebar" class="col-md-3 sidebar">
            <div id="sidebar-toggle" class="sidebar-icon" onclick="toggleSidebar()">☰</div>
            <div id="sidebar-content" class="sidebar-content">
                <h4 class="translate-text">📖 Study & Discussion</h4> <!-- 学习交流 -->
                <ul class="custom-menu">
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '课程交流']) }}">Course Discussion</a></li> <!-- 课程交流 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '作业求助']) }}">Homework Help</a></li> <!-- 作业求助 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '考试经验']) }}">Exam Experience</a></li> <!-- 考试经验 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '考研/留学']) }}">Postgraduate / Study Abroad</a></li> <!-- 考研/留学 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '实习与就业']) }}">Internships & Jobs</a></li> <!-- 实习与就业 -->
                </ul>

                <h4 class="translate-text">🏫 Campus Life</h4> <!-- 校园生活 -->
                <ul class="custom-menu">
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '校园新闻']) }}">Campus News</a></li> <!-- 校园新闻 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '活动通知']) }}">Event Announcements</a></li> <!-- 活动通知 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '社团招新']) }}">Club Recruitment</a></li> <!-- 社团招新 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '宿舍生活']) }}">Dorm Life</a></li> <!-- 宿舍生活 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '失物招领']) }}">Lost & Found</a></li> <!-- 失物招领 -->
                </ul>

                <h4 class="translate-text">🎮 Leisure & Entertainment</h4> <!-- 休闲娱乐 -->
                <ul class="custom-menu">
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '动漫游戏']) }}">Anime & Games</a></li> <!-- 动漫游戏 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '影视剧集']) }}">Movies & TV Series</a></li> <!-- 影视剧集 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '小说分享']) }}">Novel Sharing</a></li> <!-- 小说分享 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '校园八卦']) }}">Campus Gossip</a></li> <!-- 校园八卦 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '运动健身']) }}">Sports & Fitness</a></li> <!-- 运动健身 -->
                </ul>

                <h4 class="translate-text">💻 Tech & Development</h4> <!-- 技术交流 -->
                <ul class="custom-menu">
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '编程交流']) }}">Programming Discussion</a></li> <!-- 编程交流 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '硬件DIY']) }}">Hardware DIY</a></li> <!-- 硬件DIY -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '黑客技术']) }}">Hacking Techniques</a></li> <!-- 黑客技术 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '开源项目']) }}">Open Source Projects</a></li> <!-- 开源项目 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => 'AI与机器学习']) }}">AI & Machine Learning</a></li> <!-- AI与机器学习 -->
                </ul>

                <h4 class="translate-text">🛒 Marketplace</h4> <!-- 交易专区 -->
                <ul class="custom-menu">
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '二手书籍']) }}">Second-hand Books</a></li> <!-- 二手书籍 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '电子产品']) }}">Electronics</a></li> <!-- 电子产品 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '服饰美妆']) }}">Fashion & Beauty</a></li> <!-- 服饰美妆 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '拼单团购']) }}">Group Buying</a></li> <!-- 拼单团购 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '兼职与副业']) }}">Part-time & Side Jobs</a></li> <!-- 兼职与副业 -->
                </ul>

                <h4 class="translate-text">💬 Life & Social</h4> <!-- 生活交流 -->
                <ul class="custom-menu">
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '树洞倾诉']) }}">Confessions</a></li> <!-- 树洞倾诉 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '情感咨询']) }}">Relationship Advice</a></li> <!-- 情感咨询 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '日常吐槽']) }}">Daily Rants</a></li> <!-- 日常吐槽 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '正能量分享']) }}">Positive Sharing</a></li> <!-- 正能量分享 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '人生规划']) }}">Life Planning</a></li> <!-- 人生规划 -->
                </ul>

                <h4 class="translate-text">⚙️ Site Administration</h4> <!-- 站务管理 -->
                <ul class="custom-menu">
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '使用问题']) }}">Usage Issues</a></li> <!-- 使用问题 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '功能建议']) }}">Feature Suggestions</a></li> <!-- 功能建议 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '违规举报']) }}">Report Violations</a></li> <!-- 违规举报 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '版主申请']) }}">Moderator Applications</a></li> <!-- 版主申请 -->
                    <li><a class="translate-text" href="{{ route('home', ['tag' => '公告通知']) }}">Announcements</a></li> <!-- 公告通知 -->
                </ul>
            </div>
        </div>

        <!-- 主要内容区 -->
        <div class="col-md-9" id="main-content">
            <div class="d-flex align-items-center justify-content-between mt-3">
                <h2 class="m-0 translate-text">Latest Posts</h2>

                <!-- 搜索框 -->
                 <!-- 搜索框（去掉搜索按钮，实时更新） -->
                <div class="search-container">
                    <input type="text" id="search-box" name="search" class="search-input translate-text"
                        placeholder="🔍 Real-Time Search..." value="{{ $search ?? '' }}">
                </div>
            </div>

            <div id="post-results" class="mt-0">
            @forelse($posts as $post)
                <div class="card mb-3">
                    <!-- 发帖人信息 -->
                    <div class="card-header d-flex align-items-center">
                        {{-- <img src="{{ asset('anime.jpg' . $post->user->avatar) }}" class="rounded-circle me-2" width="40" height="40" alt="User Avatar"> --}}
                        <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden;" class="me-2">
                           {{-- @php
    $isSelf = $post->user->id === Auth::id();
    $profileRoute = $isSelf
        ? route('profile.me')
        : ($post->user->id ? route('profile.show', ['id' => $post->user->id]) : '#');
@endphp

<a href="{{ $profileRoute }}">
    <img
        src="{{ $post->user->profile && $post->user->profile->profile_picture && $post->user->profile->profile_picture !== 'defaultaaa.webp'
            ? asset('storage/images/avatar/' . $post->user->profile->profile_picture) . '?v=' . time()
            : asset('images/defaultaaa.webp') }}"
        onerror="this.onerror=null; this.src='{{ asset('images/defaultaaa.webp') }}';"
        alt="{{ $post->user->name }} 的头像"
        style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; cursor: pointer;"
    />
</a> --}}





                            <a href="{{ route('profile.show', $post->user->user_id) }}">
    <img
        src="{{ $post->user->profile && $post->user->profile->profile_picture && $post->user->profile->profile_picture !== 'defaultaaa.webp'
            ? asset('storage/images/avatar/' . $post->user->profile->profile_picture) . '?v=' . time()
            : asset('images/defaultaaa.webp') }}"
        onerror="this.onerror=null; this.src='{{ asset('images/defaultaaa.webp') }}';"
        alt="{{ $post->user->name }} 的头像"
        style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; cursor: pointer;"
    />
</a>

                        </div>

                        <div>
                            <strong>{{ $post->user->name }}</strong>
                            <span class="badge
                            @if($post->user->role == 'student') bg-primary
                            @elseif($post->user->role == 'teacher') bg-danger
                            @else bg-secondary
                            @endif">
                            {{ $post->user->role }}
                        </span>
                        <div class="text-muted small translate-text">
                            发布于 {{ $post->created_at->translatedFormat('Y年m月d日 H:i') }} （{{ $post->created_at->diffForHumans() }}）
                        </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- 帖子内容 -->
                        @php
                            $maxLength = 10; // 中文最大字符数
                            $maxWords = 10;  // 英文最大单词数

                            // 判断是否包含中文
                            $isChinese = preg_match('/[\x{4E00}-\x{9FFF}]/u', $post->content);
                            if ($isChinese) {
                                $isLong = mb_strlen($post->content, 'UTF-8') > $maxLength;
                                $shortContent = $isLong ? mb_substr($post->content, 0, $maxLength, 'UTF-8') . '...' : $post->content;
                            } else {
                                $words = explode(' ', $post->content);
                                $isLong = count($words) > $maxWords;
                                $shortContent = $isLong ? implode(' ', array_slice($words, 0, $maxWords)) . '...' : $post->content;
                            }
                        @endphp

                        <h5 property="og:title" class="card-title">
                            {{ $post->title }}

                            {{-- 仅当内容为中文时显示翻译按钮 --}}
                            @if ($isChinese)
                                <a href="javascript:void(0);" id="translate-btn-{{ $post->id }}"
                                class="btn btn-primary btn-sm translate-text"
                                onclick="translateContent({{ $post->id }})">
                                    翻译
                                </a>
                            @endif
                        </h5>

                        <p class="card-text" id="post-content-{{ $post->id }}"
                        data-full-content="{{ htmlspecialchars($post->content, ENT_QUOTES, 'UTF-8') }}"
                        data-translated-content=""
                        data-is-translated="false"
                        data-is-expanded="false">

                            {!! htmlspecialchars($shortContent, ENT_QUOTES, 'UTF-8') !!}

                            {{-- 仅当内容需要展开时显示“查看全文” --}}
                            @if ($isLong)
                                <a class="translate-text" href="javascript:void(0);" id="expand-btn-{{ $post->id }}" onclick="toggleContent({{ $post->id }})">
                                    查看全文
                                </a>
                            @endif
                        </p>

                         <!-- 图片或视频 -->
                         @if (!empty($post->images))
                         @php
                         // 确保 $images 是数组
                         $images = is_array($post->images) ? $post->images : json_decode($post->images, true);
                         $imageCount = count($images);
                     @endphp

                     <div class="media-container mb-3 image-count-{{ $imageCount }}">
                            @foreach ($images as $index => $image)
                            <img src="{{ asset('storage/' . $image) }}"
                                class="media-content img-thumbnail"
                                alt="帖子图片"
                                onclick="openFullscreen('{{ asset('storage/' . $image) }}')">
                        @endforeach
                     </div>
                     @endif

                        <!-- 全屏图片容器 -->
                        <div id="fullscreenOverlay" class="fullscreen-overlay" onclick="closeFullscreen()">
                            <img id="fullscreenImg" class="fullscreen-img" alt="放大查看图片">
                        </div>

                        @if ($post->video)
                            <div class="media-container mb-3">
                                <video controls class="media-content translate-text">
                                    <source src="{{ asset('storage/' . $post->video) }}" type="video/mp4">
                                    您的浏览器不支持视频播放。
                                </video>
                            </div>
                        @endif

                        @if (!empty($post->tags))
                            <p class="mb-2">
                                @foreach (json_decode($post->tags, true) as $tag)
                                    <a href="{{ route('home', ['tag' => $tag]) }}" class="badge bg-primary text-white text-decoration-none translate-text">#{{ $tag }}</a>
                                @endforeach
                            </p>
                        @endif

                        <!-- 互动按钮容器 -->
                        <div class="mt-3">
                            <!-- 第一行：四个互动按钮 -->
                            <div class="d-flex justify-content-between">
                                <!-- 点赞按钮 -->
                                <button id="like-btn-{{ $post->id }}"
                                    class="btn btn-outline-danger position-relative"
                                    onclick="toggleLike({{ $post->id }})">
                                <span id="like-icon-{{ $post->id }}">
                                    @if(auth()->check() && auth()->user()->likes()->where('post_id', $post->id)->exists())
                                        ❤️
                                    @else
                                        🤍
                                    @endif
                                </span>

                                <span class="translate-text">赞</span> (
                                <span id="likes-count-{{ $post->id }}">{{ $post->likes()->count() }}</span>
                                )

                                <!-- 点赞特效 💖 -->
                                <span class="like-effect" id="like-effect-{{ $post->id }}">💖</span>
                                <!-- 取消点赞特效 💔 -->
                                <span class="unlike-effect" id="unlike-effect-{{ $post->id }}">💔</span>
                            </button>

                                {{-- <button id="like-btn-{{ $post->id }}"
                                    class="btn btn-outline-danger position-relative"
                                    onclick="toggleLike({{ $post->id }})">
                                    <span id="like-icon-{{ $post->id }}">
                                        @if(auth()->check() && auth()->user()->likes()->where('post_id', $post->id)->exists())
                                            ❤️
                                        @else
                                            🤍
                                        @endif
                                    </span>
                                    赞 (<span id="likes-count-{{ $post->id }}">{{ $post->likes()->count() }}</span>)

                                    <!-- 点赞特效 💖 -->
                                    <span class="like-effect" id="like-effect-{{ $post->id }}">💖</span>
                                    <!-- 取消点赞特效 💔 -->
                                    <span class="unlike-effect" id="unlike-effect-{{ $post->id }}">💔</span>
                                </button> --}}

                                <!-- 评论按钮 -->
                                @auth
                                <!-- 已登录用户：正常显示评论按钮 -->
                                <a href="#"
                                class="btn btn-outline-primary translate-text"
                                data-bs-toggle="modal"
                                data-bs-target="#commentModal-{{ $post->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="black">
                                        <path d="M21 11.5a8.38 8.38 0 0 1-12.38 7.6L3 21l2-5.62a8.5 8.5 0 1 1 16-.12Z"/>
                                    </svg>
                                    评论 (<span id="comment-count-{{ $post->id }}">{{ $post->comments->count() }}</span>)
                                </a>
                            @endauth

                            @guest
                                <!-- 未登录用户：跳转到登录页面 -->
                                <a href="{{ route('login') }}" class="btn btn-outline-primary translate-text">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="black">
                                        <path d="M21 11.5a8.38 8.38 0 0 1-12.38 7.6L3 21l2-5.62a8.5 8.5 0 1 1 16-.12Z"/>
                                    </svg>
                                评论 (<span id="comment-count-{{ $post->id }}">{{ $post->comments()->count() }}</span>)
                                </a>
                            @endguest

                                <!-- 评论模态框 -->
                                 <div class="modal fade" id="commentModal-{{ $post->id }}" tabindex="-1" aria-labelledby="commentModalLabel-{{ $post->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- 加宽 -->
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
    <h5 class="modal-title me-3 translate-text">评论区</h5>

    <div class="btn-group btn-group-sm" role="group" aria-label="排序方式">
        <button type="button" class="btn btn-primary sort-btn" data-sort="latest">最新</button>
        <button type="button" class="btn btn-outline-secondary sort-btn" data-sort="hot">最热</button>
    </div>

    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
</div>


            <div class="modal-body">
                <!-- 评论列表 -->
                <div id="comment-list-{{ $post->id }}" class="comment-scroll-container">
                    @forelse($post->comments as $comment)
                    {{-- @forelse($post->comments()->whereNull('parent_id')->orderBy('created_at', 'desc')->get() as $comment) --}}
                        <div class="card mb-2 p-2 text-dark
                        {{ $comment->pinned ? 'border-warning pinned-comment bg-warning bg-opacity-10' : 'bg-light' }}"
                        id="comment-{{ $comment->id }}"
                        data-time="{{ strtotime($comment->created_at) }}">
                        @if($comment->pinned)
                            <span class="badge bg-warning text-dark mb-2 pinned-badge">📌 置顶评论</span>
                        @endif
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $comment->user->profile && $comment->user->profile->profile_picture && $comment->user->profile->profile_picture !== 'defaultaaa.webp'
                                ? asset('storage/images/avatar/' . $comment->user->profile->profile_picture) . '?v=' . time()
                                : asset('images/defaultaaa.webp') }}"
                                onerror="this.onerror=null; this.src='{{ asset('images/defaultaaa.webp') }}';"
                                class="rounded-circle me-2"
                                alt="用户头像"
                                style="width:35px; height:35px; object-fit:cover;">

                                <strong>{{ $comment->user->name }}</strong>

                                <span class="badge
                                    @if($comment->user->role === 'student') bg-primary
                                    @elseif($comment->user->role === 'teacher') bg-danger
                                    @else bg-secondary
                                    @endif ms-2">
                                    @if($comment->user->role === 'student') student
                                    @elseif($comment->user->role === 'teacher') teacher
                                    @else 其他
                                    @endif
                                </span>
                                <!-- 状态徽章 (如果有才显示) -->
                                @if ($comment->status === 'discussion')
                                    <a href="javascript:void(0)"
                                    id="status-badge-{{ $comment->id }}"
                                    class="btn-shine btn-discussion">
                                        讨论进行中
                                    </a>
                                @endif

                                <small class="text-muted ms-auto">
                                    @if ($comment->status === 'resolved')
                                        <a href="javascript:void(0)"
                                        id="history-badge-{{ $comment->id }}"
                                        class="btn-shine text-muted"
                                        style="font-size: 0.75rem; margin-left: 8px; padding: 2px 6px;">
                                            ✔ 曾有讨论
                                        </a>
                                    @endif

                                        {{ $comment->created_at->diffForHumans() }}
                                </small>

                            </div>

                            <p class="mb-2 comment-body">{{ $comment->content }}</p>

                            <!-- 操作按钮 -->
                            <div class="d-flex justify-content-between align-items-start mt-2">
    <!-- 左边：回复 + 点赞/点踩 -->
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <button class="btn btn-sm btn-outline-primary reply-btn"
            data-id="{{ $comment->id }}"
            data-post-id="{{ $post->id }}">
            回复
        </button>

        @php
            $likedUsers = $comment->liked_users ? json_decode($comment->liked_users, true) : [];
            $dislikedUsers = $comment->disliked_users ? json_decode($comment->disliked_users, true) : [];

            $likedByMe = auth()->check() && in_array(auth()->id(), $likedUsers);
            $dislikedByMe = auth()->check() && in_array(auth()->id(), $dislikedUsers);
        @endphp

        <button class="btn btn-sm vote-btn {{ $likedByMe ? 'btn-primary' : 'btn-outline-secondary' }}"
                data-id="{{ $comment->id }}" data-vote="like">
            👍 <span id="likes-{{ $comment->id }}">{{ $comment->likes_count }}</span>
        </button>

        <button class="btn btn-sm vote-btn {{ $dislikedByMe ? 'btn-danger' : 'btn-outline-secondary' }}"
                data-id="{{ $comment->id }}" data-vote="dislike">
            👎 <span id="dislikes-{{ $comment->id }}">{{ $comment->dislikes_count }}</span>
        </button>
    </div>

    <!-- 右边：删除 / 已解决 / 置顶 -->
    <div class="d-flex gap-2">
        @if(auth()->id() === $comment->user_id)
            @if ($comment->created_at->gt(now()->subDay()))
                <button class="btn btn-sm btn-primary edit-comment" data-id="{{ $comment->id }}">
                    修改
                </button>
            @endif

            <button class="btn btn-sm btn-danger delete-comment" data-id="{{ $comment->id }}">删除</button>

            {{-- 讨论状态按钮，初始渲染 --}}
            @if ($comment->status === 'discussion')
                <button class="btn btn-sm btn-info status-btn" data-id="{{ $comment->id }}">
                    期待讨论
                </button>
            @elseif ($comment->status === 'resolved')
                @if ($comment->status === 'resolved')
                <button class="btn btn-sm btn-success status-btn" data-id="{{ $comment->id }}" disabled style="cursor: not-allowed; opacity: 0.65;">
                    已解决
                </button>
            @endif

            @endif
        @endif

        @if(auth()->id() === $post->user_id)
            <button class="btn btn-sm btn-warning pin-comment" data-id="{{ $comment->id }}">
                {{ $comment->pinned ? '取消置顶' : '置顶' }}
            </button>
        @endif
    </div>

</div>
<div class="modal fade" id="discussionConfirmModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title">💡 标记为期待讨论？</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>你是否希望这条评论被标记为 <strong>“期待讨论”</strong>？<br>
        这样能让其他同学更快知道你希望得到回应或答案。</p>
      </div>
      <div class="modal-footer justify-content-center">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
    ❌ 不需要，仅作留言
  </button>
  <button type="button" id="markDiscussion" class="btn btn-warning ms-3" data-id="{{ $comment->id }}">
    ✔ 是的，我需要讨论/答案
  </button>
</div>

    </div>
  </div>
</div>

                <!-- 楼中楼回复 -->
            <div class="ms-4 mt-3">
    @foreach($comment->replies ?? [] as $reply)
<div class="card border-0 shadow-sm mb-2 bg-light-subtle"
     id="reply-{{ $reply->id }}"
     data-reply-id="{{ $reply->id }}"
     data-post-id="{{ $post->id }}">
                <div class="card-body p-2 d-flex">
                <!-- 头像 -->
                <img src="{{ $reply->user->profile && $reply->user->profile->profile_picture && $reply->user->profile->profile_picture !== 'defaultaaa.webp'
                    ? asset('storage/images/avatar/' . $reply->user->profile->profile_picture) . '?v=' . time()
                    : asset('images/defaultaaa.webp') }}"
                    class="rounded-circle me-2"
                    style="width:28px; height:28px; object-fit:cover;">

                <!-- 回复内容 -->
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center">
                        <strong class="me-2">{{ $reply->user->name }}</strong>
                        <span class="badge
                                    @if($reply->user->role === 'student') bg-primary
                                    @elseif($reply->user->role === 'teacher') bg-danger
                                    @else bg-secondary
                                    @endif ms-2">
                                    @if($reply->user->role === 'student') student
                                    @elseif($reply->user->role === 'teacher') teacher
                                    @else 其他
                                    @endif
                        </span>
                        <small class="text-muted ms-auto">{{ $reply->created_at->diffForHumans() }}</small>
                    </div>

                    <p class="mb-1 text-dark">{{ $reply->content }}</p>

                    <!-- 回复操作 -->
                    @php
                        $likedUsers = $reply->liked_users ? json_decode($reply->liked_users, true) : [];
                        $dislikedUsers = $reply->disliked_users ? json_decode($reply->disliked_users, true) : [];

                        $likedByMe = auth()->check() && in_array(auth()->id(), $likedUsers);
                        $dislikedByMe = auth()->check() && in_array(auth()->id(), $dislikedUsers);
                    @endphp

                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <!-- 左边：回复 + 点赞/点踩 -->
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <button class="btn btn-sm btn-outline-primary reply-btn"
                                    data-id="{{ $reply->id }}"
                                    data-post-id="{{ $post->id }}">
                                回复
                            </button>

                            <button class="btn btn-sm vote-btn {{ $likedByMe ? 'btn-primary' : 'btn-outline-secondary' }}"
                                    data-id="{{ $reply->id }}" data-vote="like">
                                👍 <span id="likes-{{ $reply->id }}">{{ $reply->likes_count ?? 0 }}</span>
                            </button>

                            <button class="btn btn-sm vote-btn {{ $dislikedByMe ? 'btn-danger' : 'btn-outline-secondary' }}"
                                    data-id="{{ $reply->id }}" data-vote="dislike">
                                👎 <span id="dislikes-{{ $reply->id }}">{{ $reply->dislikes_count ?? 0 }}</span>
                            </button>
                        </div>

                        <!-- 右边：删除按钮 -->
                        <div>
                            @if(auth()->id() === $reply->user_id)
    <button class="btn btn-sm btn-danger delete-comment" data-id="{{ $reply->id }}">删除</button>
@endif


                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

            </div>
                    @empty
                        <p class="text-muted">还没有评论，快来抢沙发吧！</p>
                    @endforelse
                </div>

                <!-- 添加评论 -->
                <form class="comment-form mt-3" data-post-id="{{ $post->id }}">
                    @csrf
                    <textarea class="comment-content form-control" rows="3" placeholder="输入你的评论..." required></textarea>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-muted translate-text">支持 @用户、Emoji 😀</small>
                        <button type="submit" class="btn btn-primary translate-text">发表评论</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



                                <!-- 分享按钮 -->
                                <button class="btn btn-outline-success translate-text" onclick="toggleSharePanel({{ $post->id }})">
                                    🔄 分享
                                </button>

                                <!-- 收藏按钮 -->
                                @auth
                                <button id="favorite-btn-{{ $post->id }}"
                                        class="btn btn-outline-warning translate-text"
                                        onclick="toggleFavorite({{ $post->id }})">
                                    @if(auth()->user()->favorites()->where('post_id', $post->id)->exists())
                                        ⭐ 已收藏 (<span id="favorites-count-{{ $post->id }}">{{ $post->favorites()->count() }}</span>)
                                    @else
                                        ☆ 收藏 (<span id="favorites-count-{{ $post->id }}">{{ $post->favorites()->count() }}</span>)
                                    @endif
                                </button>
                            @endauth

                            @guest
                                <a href="{{ route('login') }}"
                                class="btn btn-outline-warning">
                                    ☆ 收藏 (<span id="favorites-count-{{ $post->id }}">{{ $post->favorites()->count() }}</span>)
                                </a>
                            @endguest

                            </div>

                            <!-- 第二行：分享面板（默认隐藏） -->
                            <div id="sharePanel-{{ $post->id }}" class="mt-2" style="display: none;">
                                <div class="d-flex justify-content-around flex-wrap">
                                    <button class="btn btn-outline-success" onclick="shareToWhatsApp('{{ route('posts.show', $post->id) }}')">
                                        <i class="fa-brands fa-whatsapp"></i> WhatsApp
                                    </button>
                                    <button class="btn btn-outline-primary" onclick="shareToWeibo('{{ route('posts.show', $post->id) }}')">
                                        <i class="fa-brands fa-weibo"></i> 微博
                                    </button>
                                    <button class="btn btn-outline-info" onclick="shareToTwitter('{{ $post->title }}', '{{ route('posts.show', $post->id) }}')">
                                        <i class="fa-brands fa-x-twitter"></i> X（Twitter）
                                    </button>
                                    <button class="btn btn-outline-primary" onclick="shareToFacebook('{{ route('posts.show', $post->id) }}')">
                                        <i class="fa-brands fa-facebook"></i> Facebook
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="toggleSharePanel({{ $post->id }})">❌ 关闭</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="translate-text">暂无相关帖子。</p>
            @endforelse
        {{-- </div> --}}
        </div>
    </div>
</div>

<!-- 底部按钮和图片 -->
<div class="bottom-right-container">
    <a href="{{ route('post.create') }}" class="add-post-btn">+</a>
    {{-- <img src="{{ asset('firefly.png') }}" id="bottom-right-image" alt="Firefly Image"> --}}
</div>


    <!-- Canvas 动画 -->
    <canvas id="backgroundCanvas"></canvas>

    <style>
        /* 基础布局 */
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: Arial, sans-serif;
        }

        /* 确保所有元素的宽度不会被过度压缩 */
        * {
            box-sizing: border-box; /* 包含内边距和边框 */
        }

        /* 让所有内容自适应 */
        #content {
            width: 100%;  /* 内容宽度为视口宽度 */
            padding: 5vw;  /* 内边距使用视口单位，保证内容不紧贴边缘 */
            min-width: 300px; /* 设置最小宽度，避免内容被压缩 */
            height: auto;  /* 内容高度自动调整 */
            font-size: 2vw; /* 字体大小使用视口宽度的百分比 */
        }

        /* 如果内容太大，自动换行 */
        p, h1, h2, h3, h4, h5, h6 {
            word-wrap: break-word; /* 防止长单词或链接溢出 */
            overflow-wrap: break-word;
        }

        /* 针对 PC 屏幕的样式调整 */
        @media (min-width: 1024px) {
            #content {
                padding: 2rem;  /* 更大屏幕上使用较小的固定内边距 */
                font-size: 1.2rem; /* 字体大小适中 */
            }
        }

        /* 针对屏幕宽度较小的设备做适配 */
        @media (max-width: 768px) {
            #content {
                padding: 5%;  /* 更小的内边距，确保内容不被挤压 */
                font-size: 4vw; /* 在小屏设备上使用更大的字体 */
                min-width: 200px; /* 在小屏设备上确保最小宽度 */
            }
        }

        /* 可选：如果有图片或元素，使用百分比宽度 */
        img, .responsive {
            max-width: 100%;  /* 确保图片或元素不会超出其父容器 */
            height: auto;  /* 保持图片比例 */
        }

        /* 基础样式 */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 60px; /* 默认宽度 */
            background: #343a40;
            transition: width 0.3s ease-in-out, background-color 0.3s ease;
            overflow: hidden;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 10px;
            overflow-y: auto; /* 允许滚动 */
            scrollbar-width: none; /* Firefox 隐藏滚动条 */
        }

        /* 在小屏幕设备上，默认收缩 sidebar */
        @media (max-width: 768px) {
            .sidebar {
                width: 50px;  /* 小屏幕上默认宽度更小 */
            }

            .sidebar:hover {
                width: 150px; /* 小屏幕上悬停时宽度增大 */
            }
        }

        /* 侧边栏展开后 */
        .sidebar.expanded {
            width: 250px;
        }

        /* 侧边栏图标 */
        .sidebar-icon {
            font-size: 30px;
            color: white;
            cursor: pointer;
            text-align: center;
            width: 100%;
        }

        /* 侧边栏内容 */
        .sidebar-content {
            width: 100%;
            padding: 20px;
            color: white;
            display: none;
        }

        /* 展开后显示内容 */
        .sidebar.expanded .sidebar-content {
            display: block;
        }

        /* 主要内容左边距，防止被侧边栏遮挡 */
        .col {
            margin-left: 70px;
            transition: margin-left 0.3s ease-in-out;
        }

        /* 侧边栏展开时调整内容区域 */
        .sidebar.expanded ~ .col {
            margin-left: 260px;
        }

        /* 自定义菜单样式，仅作用于 sidebar 内部 */
        .custom-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .custom-menu li {
            margin: 15px 0; /* 间隔控制 */
        }

        .custom-menu a {
            display: block;
            padding: 12px 20px;
            font-size: 16px;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        /* 悬停动画：发光+缩放 */
        .custom-menu a:hover {
            transform: translateX(5px) scale(1.05);
            box-shadow: 0 0 20px rgba(142, 45, 226, 0.7);
        }

        /* 伪元素实现流光特效 */
        .custom-menu a::before {
            content: '';
            position: absolute;
            left: -100%;
            top: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .custom-menu a:hover::before {
            left: 100%;
        }

        /* 当前激活的菜单项（可根据需要动态添加 active 类） */
        .custom-menu a.active {
            background: linear-gradient(135deg, #ff6a00, #ee0979);
            box-shadow: 0 0 25px rgba(255, 105, 135, 0.8);
        }

        /* 适配小屏幕，防止布局溢出 */
        @media (max-width: 768px) {
            .custom-menu a {
                padding: 10px 15px;
                font-size: 14px;
            }
        }

         /* 圆形搜索框样式 */
        .search-container {
            margin-bottom: 20px;
            text-align: center;
        }

        /* 搜索框样式 (圆角) */
        .search-form {
            position: relative;
            width: 300px; /* 可根据需求调整宽度/最少300px */
        }

        .search-input {
            width: 100%;
            padding: 10px 60px 10px 10px; /* 右侧留出按钮空间 */
            border: 1px solid #ccc;
            border-radius: 25px; /* 圆角效果 */
            box-sizing: border-box;
        }

        .search-button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: #007bff;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            cursor: pointer;
        }

        .search-button:hover {
            background: #0056b3;
        }



        /* Canvas 背景 */
        canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .bottom-right-container {
            position: fixed;
            top: 100px;   /* 始终保持在窗口顶部 100px */
            right: 50px;  /* 始终保持在窗口右侧 50px */
            display: inline-block;
            z-index: 99999;  /* 提高层级，确保在所有元素之上 */
            pointer-events: auto;  /* 确保可以点击 */
        }

        /* 防止其他元素遮挡 */
        .bottom-right-container * {
            position: relative;
            z-index: 99999;
        }


        .add-post-btn {
            position: absolute;
            text-decoration: none; /* 移除下划线 */
            top: -15px;
            left: -15px;
            width: 50px;
            height: 50px;
            padding-top: 0px; /* 轻微向上调整 */
            padding-right: 2px;
            background-color: #477bff;
            color: white;
            font-size: 30px;
            font-weight: bold;
            text-align: center;
            line-height: 50px; /* 让 `+` 号垂直居中 */
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s, transform 0.2s;
        }

        .add-post-btn:hover {
            background-color: #e84118;
            transform: scale(1.1);
        }


        /* 右下角固定图片 */
        #bottom-right-image {
            position: fixed;
            right: 2vw; /* 让它距离右侧有一个相对比例距离 */
            bottom: 5vh; /* 让它距离底部保持相对视口的距离 */
            width: 20vw; /* 让图片宽度随着视口宽度动态调整 */
            height: auto; /* 自动调整高度，保持图片比例 */
            z-index: 1000;
            opacity: 0.9;
            transition: transform 0.3s ease-in-out;
            max-width: 350px;  /* 限制最大宽度，防止图片过大 */
            max-height: 50vh; /* 限制最大高度，防止图片过大 */
        }

        #bottom-right-image:hover {
            transform: scale(1.1);
            opacity: 1;
        }

        /* 固定容器大小，宽高自适应 */
    /* 固定容器大小，确保图片和视频完整显示 */
    .media-container {
        width: 100%;
        max-width: 600px; /* 最大宽度 */
        height: 400px;    /* 固定高度 */
        overflow: hidden; /*隐藏溢出内容 */
        display: flex;    /* 使用 Flexbox 居中内容 */
        justify-content: center; /* 水平居中 */
        align-items: center;     /* 垂直居中 */
        border-radius: 12px;     /* 可选，添加圆角 */
        background-color: #000;  /* 设置背景色，防止空白 */
        gap: 4px; /* 图片间距 */
        flex-wrap: wrap; /* 自动换行 */

    }

    /* 图片和视频完整显示，按比例缩放 */
    .media-content {
        object-fit: contain;     /* 保证图片完整显示，不裁剪 */
        border-radius: 8px;      /* 图片圆角 */
        cursor: pointer;          /*鼠标悬停变手型 */
    }

    /* 动态布局样式 */
    /* 只有一张图片时，图片占满整个容器 */
    .image-count-1 .media-content {
        width: 100%;
        height: 100%;
    }

    /* 3 张图片：第一张占满一行，后两张各占 50% */
    .image-count-3 .media-content:first-child {
        width: calc(100% - 4px); /* 第一张大图 */
        height: calc(50% - 4px); /* 与其他图片高度一致 */
    }

    /* 四张图片时，2x2 布局，每张图片占 50% */
    .image-count-3 .media-content,
    .image-count-4 .media-content {
        width: calc(50% - 4px);
        height: calc(50% - 4px);
    }

        /* 缩略图基础样式 */
        .img-thumbnail {
            object-fit: contain; /* 保持图片完整 */
            border-radius: 8px; /* 圆角 */
            cursor: pointer; /* 可点击 */
        }

        /* 放大查看的图片样式 */
        .fullscreen-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .fullscreen-img {
            max-width: 90vw; /* 最大宽度 90% 视口 */
            max-height: 90vh; /* 最大高度 90% 视口 */
            object-fit: contain; /* 图片比例不变 */
            border-radius: 10px; /* 圆角 */
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
            transition: opacity 0.3s ease-in-out; /* 渐入效果 */
        }

        /* 关闭按钮（可选） */
        .close-btn {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 40px;
            color: white;
            cursor: pointer;
        }


    /* 视频样式适配 */
    .media-container video {
        width: 100%;
        border-radius: 8px; /* 视频圆角 */
    }

    /* 评论列表固定高度，允许滚动 */
.comment-scroll-container {
    max-height: 300px; /* 设置评论区的固定高度 */
    overflow-y: auto;  /* 如果评论超出高度，显示滚动条 */
    /* display: none; */
    padding-right: 10px;
}

/* 可选：美化滚动条（仅适配 Webkit 浏览器，如 Chrome、Edge） */
.comment-scroll-container::-webkit-scrollbar {
    width: 8px; /* 滚动条宽度 */
}

.comment-scroll-container::-webkit-scrollbar-thumb {
    background-color: #aaa; /* 滚动条颜色 */
    border-radius: 10px; /* 滚动条圆角 */
}

.comment-scroll-container::-webkit-scrollbar-track {
    background: #f5f5f5; /* 滚动条轨道背景 */
}
.pinned-comment {
    background: #fffbea !important; /* 柔和的黄色背景 */
    box-shadow: 0 0 10px rgba(255, 193, 7, 0.5); /* 发光感 */
}
.btn-shine {
  padding: 12px 48px;
  color: #fff;
  background: linear-gradient(to right, #9f9f9f 0, #fff 10%, #868686 20%);
  background-position: 0;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: shine 3s infinite linear;
  animation-fill-mode: forwards;
  -webkit-text-size-adjust: none;
  font-weight: 600;
  font-size: 16px;
  text-decoration: none;
  white-space: nowrap;
  font-family: "Poppins", sans-serif;
}
@-moz-keyframes shine {
  0% {
    background-position: 0;
  }
  60% {
    background-position: 180px;
  }
  100% {
    background-position: 180px;
  }
}
@-webkit-keyframes shine {
  0% {
    background-position: 0;
  }
  60% {
    background-position: 180px;
  }
  100% {
    background-position: 180px;
  }
}
@-o-keyframes shine {
  0% {
    background-position: 0;
  }
  60% {
    background-position: 180px;
  }
  100% {
    background-position: 180px;
  }
}
@keyframes shine {
  0% {
    background-position: 0;
  }
  60% {
    background-position: 180px;
  }
  100% {
    background-position: 180px;
  }
}



    /* 分享面板样式  也可以选择不要设计，因为默认下会呈现在对应帖子里 */
    .share-panel {
    position: fixed;
    bottom: 250px; /* 向上移动，避免遮挡 */
    right: 20px;
    background: #fff;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    z-index: 9999;
    }

    .like-effect,
.unlike-effect {
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%) scale(0);
    opacity: 0;
    font-size: 24px;
    pointer-events: none;
    z-index: 10;
    transition: transform 0.5s ease, opacity 0.5s ease;
}

/* 点赞动画：放大、亮起 */
.like-effect.activated {
    transform: translateX(-50%) scale(2);
    opacity: 1;
}

/* 取消点赞动画：先放大再淡出 */
.unlike-effect.activated {
    transform: translateX(-50%) scale(2); /* 放大显示 */
    opacity: 1;
    color: #999;
    transition: transform 0.5s ease, opacity 1.2s ease;
}

    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("expanded");
        }

        // 点击侧边栏外关闭
        // 点击外部关闭侧边栏
        document.addEventListener('click', (event) => {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.getElementById('sidebar-toggle');

            // 如果侧边栏已打开，且点击的不是侧边栏或切换按钮，则关闭侧边栏
            if (sidebar.classList.contains('expanded') &&
                !sidebar.contains(event.target) &&
                event.target !== toggleButton) {
                sidebar.classList.remove('expanded');
            }
        });

        // 按下 ESC 键关闭侧边栏
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' || event.keyCode === 27) {
                document.getElementById('sidebar').classList.remove('expanded');
            }
        });

        $(document).ready(function () {
        // 定时器，用于防止输入时频繁请求
        let debounceTimer;

        // 监听搜索框输入事件
        $('#search-box').on('input', function () {
            clearTimeout(debounceTimer);
            let searchValue = $(this).val();

            // 设置防抖，300ms 后执行请求，减少请求次数
            debounceTimer = setTimeout(function () {
                // 发起 AJAX 请求
                $.ajax({
                    url: '{{ route("posts.search") }}',
                    method: 'GET',
                    data: { search: searchValue },
                    success: function (response) {
                        // 更新文章列表
                        $('#post-results').html($(response).find('#post-results').html());
                    },
                    error: function () {
                        alert('搜索失败，请稍后再试。');
                    }
                });
            }, 300);
        });
    });

        // Canvas 动画
        const canvas = document.getElementById("backgroundCanvas");
        const ctx = canvas.getContext("2d");

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        let particles = [];
        for (let i = 0; i < 100; i++) {
            particles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                radius: Math.random() * 3 + 1,
                dx: Math.random() * 2 - 1,
                dy: Math.random() * 2 - 1
            });
        }

        function drawParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => {
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fillStyle = "rgba(255, 255, 255, 0.8)";
                ctx.fill();
                p.x += p.dx;
                p.y += p.dy;

                if (p.x < 0 || p.x > canvas.width) p.dx *= -1;
                if (p.y < 0 || p.y > canvas.height) p.dy *= -1;
            });
            requestAnimationFrame(drawParticles);
        }

        // 处理翻译和原文切换
        // 处理翻译和原文切换
        // 判断文本是否包含中文字符
        // 检测是否包含中文
        function containsChinese(text) {
            return /[\u4e00-\u9fa5]/.test(text);
        }

        // 调用 Google Translate API
        async function fetchGoogleTranslate(text, from, to) {
            const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=${from}&tl=${to}&dt=t&q=${encodeURIComponent(text)}`;
            const response = await fetch(url);
            if (!response.ok) throw new Error('翻译请求失败');
            const data = await response.json();
            return data[0].map(item => item[0]).join('');
        }

        // 翻译内容切换
        async function translateContent(postId) {
            const contentElement = document.getElementById(`post-content-${postId}`);
            const translateBtn = document.getElementById(`translate-btn-${postId}`);
            const fullContent = contentElement.dataset.fullContent;
            const isTranslated = contentElement.dataset.isTranslated === 'true';
            const isExpanded = contentElement.dataset.isExpanded === 'true';

            if (isTranslated) {
                // 切换回原文
                updateContent(contentElement, fullContent, isExpanded);
                contentElement.dataset.isTranslated = 'false';
                translateBtn.innerText = '翻译';
            } else {
                try {
                    let translatedText = contentElement.dataset.translatedContent;
                    if (!translatedText) {
                        translatedText = await fetchGoogleTranslate(fullContent, 'zh', 'en');
                        contentElement.dataset.translatedContent = translatedText;
                    }
                    updateContent(contentElement, translatedText, isExpanded);
                    contentElement.dataset.isTranslated = 'true';
                    translateBtn.innerText = '原文';
                } catch (error) {
                    alert('翻译失败，请稍后再试！');
                    console.error('翻译错误:', error);
                }
            }

            adjustButtons(postId);
        }

        // 展开/收起切换
        function toggleContent(postId) {
            const contentElement = document.getElementById(`post-content-${postId}`);
            const fullContent = contentElement.dataset.fullContent;
            const translatedContent = contentElement.dataset.translatedContent;
            const isTranslated = contentElement.dataset.isTranslated === 'true';
            const isExpanded = contentElement.dataset.isExpanded === 'true';

            // 切换展开/收起状态
            const contentToShow = isTranslated ? translatedContent : fullContent;
            contentElement.dataset.isExpanded = isExpanded ? 'false' : 'true';

            updateContent(contentElement, contentToShow, !isExpanded);
            adjustButtons(postId);
        }

        // 更新内容并追加按钮
        function updateContent(element, content, isExpanded) {
            const shortContent = getShortContent(content);
            element.textContent = isExpanded ? content : shortContent;
        }

        // 调整按钮状态
        function adjustButtons(postId) {
            const contentElement = document.getElementById(`post-content-${postId}`);
            const fullContent = contentElement.dataset.fullContent;
            const translatedContent = contentElement.dataset.translatedContent;
            const isTranslated = contentElement.dataset.isTranslated === 'true';
            const isExpanded = contentElement.dataset.isExpanded === 'true';

            // 处理 "查看全文" 按钮
            const expandBtnId = `expand-btn-${postId}`;
            let expandBtn = document.getElementById(expandBtnId);
            if (checkIfLong(isTranslated ? translatedContent : fullContent) && !isExpanded) {
                if (!expandBtn) {
                    expandBtn = createButton('查看全文', expandBtnId, () => toggleContent(postId));
                    contentElement.appendChild(document.createTextNode(' '));
                    contentElement.appendChild(expandBtn);
                }
            } else if (expandBtn) {
                expandBtn.remove();
            }

            // 处理 "翻译/原文" 按钮
            const translateBtnId = `translate-btn-${postId}`;
            let translateBtn = document.getElementById(translateBtnId);
            if (!translateBtn) {
                translateBtn = createButton('翻译', translateBtnId, () => translateContent(postId));
                contentElement.appendChild(document.createTextNode(' '));
                contentElement.appendChild(translateBtn);
            }
            translateBtn.innerText = isTranslated ? '原文' : '翻译';

            if (!containsChinese(fullContent)) translateBtn.remove();

            // 处理 "收起" 按钮
            const collapseBtnId = `collapse-btn-${postId}`;
            let collapseBtn = document.getElementById(collapseBtnId);
            if (isExpanded && !collapseBtn) {
                collapseBtn = createButton('收起', collapseBtnId, () => toggleContent(postId));
                contentElement.appendChild(document.createTextNode(' '));
                contentElement.appendChild(collapseBtn);
            } else if (!isExpanded && collapseBtn) {
                collapseBtn.remove();
            }
        }

        // 创建按钮
        function createButton(text, id, onClick) {
            const btn = document.createElement('a');
            btn.href = 'javascript:void(0);';
            btn.innerText = text;
            btn.id = id;
            btn.onclick = onClick;
            return btn;
        }

        // 判断内容是否过长
        function checkIfLong(content) {
            const maxLength = 10;
            const maxWords = 10;
            return /[\u4E00-\u9FFF]/.test(content)
                ? content.length > maxLength
                : content.split(' ').length > maxWords;
        }

        // 获取截断内容
        function getShortContent(content) {
            const maxLength = 10;
            const maxWords = 10;
            return /[\u4E00-\u9FFF]/.test(content)
                ? content.length > maxLength ? `${Array.from(content).slice(0, maxLength).join('')}...` : content
                : content.split(' ').length > maxWords ? `${content.split(' ').slice(0, maxWords).join(' ')}...` : content;
        }

        // 打开全屏图片
        function openFullscreen(imageSrc) {
            const overlay = document.getElementById('fullscreenOverlay');
            const img = document.getElementById('fullscreenImg');
            img.src = imageSrc;
            overlay.style.display = 'flex';
        }

        // 关闭全屏图片
        function closeFullscreen() {
            document.getElementById('fullscreenOverlay').style.display = 'none';
        }

        drawParticles();

        function toggleLike(postId) {
    fetch(`/posts/${postId}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url;
            return;
        }

        if (!response.ok) throw new Error('Request failed');

        return response.json();
    })
    .then(data => {
        if (!data) return;

        const icon = document.getElementById(`like-icon-${postId}`);
        const count = document.getElementById(`likes-count-${postId}`);
        const likeEffect = document.getElementById(`like-effect-${postId}`);
        const unlikeEffect = document.getElementById(`unlike-effect-${postId}`);

        icon.textContent = data.isLiked ? '❤️' : '🤍';
        count.textContent = data.likesCount;

        const effect = data.isLiked ? likeEffect : unlikeEffect;
        effect.classList.add('activated');

        setTimeout(() => {
            effect.classList.remove('activated');
        }, 500);
    })
    .catch(error => console.error('Error:', error));
}

function safeUpdateCommentCount(postId, newCount) {
  const el = document.querySelector(`#comment-count-${postId}`);
  if (!el) return;

  // 如果后端没返回合规的数字，就保留当前值（不清空）
  const parsed = Number.isInteger(Number(newCount)) ? Number(newCount) : null;

  if (parsed !== null) {
    el.textContent = parsed;
    el.setAttribute('data-count', parsed);
  } else {
    // fallback：若没有合法 newCount，则尝试从 data-count 读取，或保持现有文本
    const backup = el.getAttribute('data-count');
    if (backup !== null) {
      el.textContent = backup;
    }
  }
}
document.addEventListener('DOMContentLoaded', function () {
    // 当前排序方式（默认最新）
    let currentSort = 'latest';

    // ===== 公共排序函数 =====
    function sortComments(commentList) {
        const comments = Array.from(commentList.children);

        // 拆分置顶 / 非置顶
        const pinned = comments.filter(c => c.classList.contains('pinned-comment'));
        const normal = comments.filter(c => !c.classList.contains('pinned-comment'));

        // 排序非置顶
        if (currentSort === 'latest') {
            normal.sort((a, b) => {
                const aTime = parseInt(a.getAttribute('data-time') || 0);
                const bTime = parseInt(b.getAttribute('data-time') || 0);
                return bTime - aTime; // 新的在前
            });
        } else if (currentSort === 'hot') {
            normal.sort((a, b) => {
                const aLikes = parseInt(a.querySelector('[id^="likes-"]')?.textContent || 0);
                const bLikes = parseInt(b.querySelector('[id^="likes-"]')?.textContent || 0);
                return bLikes - aLikes; // 点赞多的在前
            });
        }

        // 重新组装
        commentList.innerHTML = '';
        pinned.forEach(c => commentList.appendChild(c));
        normal.forEach(c => commentList.appendChild(c));
    }

    // ===== 排序按钮逻辑 =====
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('sort-btn')) {
            const sortType = e.target.getAttribute('data-sort');
            const modal = e.target.closest('.modal');
            const postId = modal.getAttribute('id').split('-')[1];
            const commentList = document.querySelector(`#comment-list-${postId}`);

            // 记录当前排序方式
            currentSort = sortType;

            // 激活按钮样式
            modal.querySelectorAll('.sort-btn').forEach(btn => {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-secondary');
            });
            e.target.classList.remove('btn-outline-secondary');
            e.target.classList.add('btn-primary');

            // 调用排序
            sortComments(commentList);
        }
    });

    // ===== 置顶按钮逻辑 =====
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('pin-comment')) {
            e.preventDefault();

            const btn = e.target;
            const commentId = btn.getAttribute('data-id');

            fetch(`/comments/${commentId}/pin`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const commentEl = document.getElementById(`comment-${data.id}`);
                        const parent = commentEl.parentNode;

                        if (data.pinned) {
                            // 清除旧的置顶
                            document.querySelectorAll('.pinned-comment').forEach(el => {
                                el.classList.remove('border-warning', 'pinned-comment', 'bg-warning');
                                const badge = el.querySelector('.pinned-badge');
                                if (badge) badge.remove();
                                const btnInside = el.querySelector('.pin-comment');
                                if (btnInside) btnInside.textContent = '置顶';
                            });

                            // 设置当前置顶
                            btn.textContent = '取消置顶';
                            commentEl.classList.add('border-warning', 'pinned-comment');
                            if (!commentEl.querySelector('.pinned-badge')) {
                                commentEl.insertAdjacentHTML('afterbegin',
                                    '<span class="badge bg-warning text-dark mb-2 pinned-badge">📌 置顶评论</span>'
                                );
                            }
                        } else {
                            btn.textContent = '置顶';
                            commentEl.classList.remove('border-warning', 'pinned-comment', 'bg-warning');
                            const badge = commentEl.querySelector('.pinned-badge');
                            if (badge) badge.remove();
                        }

                        // 重新调用排序，保证置顶始终在前
                        sortComments(parent);
                    }
                })
                .catch(err => console.error(err));
        }
    });

    // ===== 页面初始时，默认排序一次 =====
    document.querySelectorAll('[id^="comment-list-"]').forEach(list => sortComments(list));
});


            // 提交/发表评论
            document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.comment-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const postId = form.getAttribute('data-post-id');
                    const content = form.querySelector('.comment-content').value;
                    const commentList = document.querySelector(`#comment-list-${postId}`);
                    const commentCount = document.querySelector(`#comment-count-${postId}`);

                    fetch(`/posts/${postId}/comment`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ content: content }),
                    })
                    .then(response => {
                        // 处理未登录跳转
                        if (response.redirected) {
                            window.location.href = response.url;
                            return;
                        }

                        if (!response.ok) {
                            throw new Error(`请求失败，状态码: ${response.status}`);
                        }

                        return response.json();
                    })
                    .then(data => {
                        if (!data) return;

                        // 清空输入框
                        form.querySelector('.comment-content').value = '';

                        const commentList = document.querySelector(`#comment-list-${postId}`);
                        const commentCount = document.querySelector(`#comment-count-${postId}`);

                        // ✅ 如果存在“还没有评论”的提示，先移除
                        const emptyMessage = commentList.querySelector('p.text-muted');
                        if (emptyMessage) {
                            emptyMessage.remove();
                        }

                        // 更新评论列表
                         const commentHTML = `
                        <div class="card mb-2 p-2 bg-light text-dark"
                        id="comment-${data.comment.id}"
                        data-time="${Math.floor(Date.now() / 1000)}">

                            <div class="d-flex align-items-center mb-2">
                                <img src="${data.comment.user.avatar}"
                                    class="rounded-circle me-2"
                                    style="width:35px; height:35px; object-fit:cover;"
                                    onerror="this.onerror=null; this.src='{{ asset('images/defaultaaa.webp') }}';"
                                    alt="${data.comment.user.name} 的头像">

                                <strong>${data.comment.user.name}</strong>

                                <span class="badge ${
                                    data.comment.user.role === "student"
                                        ? "bg-primary"
                                        : data.comment.user.role === "teacher"
                                        ? "bg-danger"
                                        : "bg-secondary"
                                } ms-2">
                                    ${data.comment.user.role}
                                </span>

                                <small class="text-muted ms-auto">刚刚</small>
                            </div>

                            <p class="mb-2">${data.comment.content}</p>

                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <!-- 左边：回复 + 点赞/点踩 -->
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <button class="btn btn-sm btn-outline-primary reply-btn"
                                        data-id="${data.comment.id}" data-post-id="${postId}">
                                        回复
                                    </button>

                                <!--
            <button class="btn btn-sm vote-btn ${data.comment.liked_by_me ? 'btn-primary' : 'btn-outline-secondary'}"
                data-id="${data.comment.id}" data-vote="like">
                👍 <span id="likes-${data.comment.id}">${data.comment.likes ?? 0}</span>
            </button>

            <button class="btn btn-sm vote-btn ${data.comment.disliked_by_me ? 'btn-danger' : 'btn-outline-secondary'}"
                data-id="${data.comment.id}" data-vote="dislike">
                👎 <span id="dislikes-${data.comment.id}">${data.comment.dislikes ?? 0}</span>
            </button>
            -->
                                </div>

                                <!-- 右边：删除按钮 -->
                                <div>
                                    <button class="btn btn-sm btn-danger delete-comment"
                                        data-id="${data.comment.id}">
                                        删除
                                    </button>
                                </div>
                            </div>

                            <!-- 子回复容器 -->
                            <div class="ms-4 mt-3" id="reply-list-${data.comment.id}"></div>
                        </div>
                    `;

                        const pinned = commentList.querySelector('.pinned-comment');
                        if (pinned) {
                            // 插在置顶评论之后
                            pinned.insertAdjacentHTML('afterend', commentHTML);
                        } else {
                            // 没有置顶评论 → 正常插到最前
                            commentList.insertAdjacentHTML('afterbegin', commentHTML);
                        }
                        let newCommentId = data.comment.id;
                        // 打开确认框
                        const modalEl = document.getElementById('discussionConfirmModal');
                        const modal = new bootstrap.Modal(modalEl);

                        // ✅ 设置按钮的 data-id 为新评论 ID
                        document.getElementById('markDiscussion').setAttribute('data-id', newCommentId);

                        modal.show();

                        // 更新评论数量
                        safeUpdateCommentCount(postId, data.commentCount);

                        // 自动滚动到顶部
                        commentList.scrollTop = 0;
                    })
                    .catch(error => console.error('评论失败:', error));
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
    const markBtn = document.getElementById('markDiscussion');
    if (markBtn) {
        markBtn.addEventListener('click', function () {
            const commentId = this.getAttribute('data-id'); // ✅ 获取评论ID

            fetch(`/comments/${commentId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ status: 'discussion' })
            })
            .then(response => {
                if (!response.ok) throw new Error("请求失败");
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const commentCard = document.querySelector(`#comment-${commentId}`);
                    const deleteBtn = commentCard.querySelector('.delete-comment'); // 找到删除按钮

                    // 检查是否已有状态按钮
                    let statusBtn = commentCard.querySelector('.status-btn');
                    if (!statusBtn) {
                        statusBtn = document.createElement('button');
                        statusBtn.className = 'btn btn-sm status-btn ms-1';
                        deleteBtn.insertAdjacentElement('afterend', statusBtn); // ✅ 插在删除按钮旁边
                    }

                    // 更新按钮内容与样式
                    if (data.status === 'discussion') {
                        statusBtn.textContent = '期待讨论';
                        statusBtn.className = 'btn btn-sm btn-info status-btn ms-1';
                    } else if (data.status === 'resolved') {
                        statusBtn.textContent = '已解决';
                        statusBtn.className = 'btn btn-sm btn-success status-btn ms-1';
                    }

                    // ✅ 成功后关闭模态框
                    const modalEl = document.getElementById('discussionConfirmModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                }
            })
            .catch(err => console.error(err));
        });
    }
});
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('status-btn')) {
        const btn = e.target;
        const commentId = btn.getAttribute('data-id');
        const currentStatus = btn.textContent.trim();

        // 已解决后不可切换
        if (currentStatus === '已解决') return;

        // 默认期待讨论 → 切换已解决
        const newStatus = currentStatus === '期待讨论' ? 'resolved' : 'discussion';

        fetch(`/comments/${commentId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => {
            if (!response.ok) throw new Error("请求失败");
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const commentCard = document.getElementById(`comment-${commentId}`);

                // 更新按钮
                if (data.status === 'discussion') {
                    btn.textContent = '期待讨论';
                    btn.className = 'btn btn-sm btn-info status-btn';
                } else if (data.status === 'resolved') {
                    btn.textContent = '已解决';
                    btn.className = 'btn btn-sm btn-success status-btn';
                    btn.disabled = true;
                }

                // 移除“讨论进行中”按钮
                const discussionBtn = commentCard.querySelector('.btn-discussion');
                if (discussionBtn) discussionBtn.remove();

                // 处理“曾有讨论”徽章
                let historyBadge = document.getElementById(`history-badge-${commentId}`);
                const timeEl = commentCard.querySelector('small.text-muted');

                if (data.status === 'resolved') {
                    // 如果不存在就创建
                    if (!historyBadge) {
                        historyBadge = document.createElement('a');
                        historyBadge.id = `history-badge-${commentId}`;
                        historyBadge.className = 'btn-shine text-muted';
                        historyBadge.style.fontSize = '0.75rem';
                        historyBadge.style.marginRight = '8px';
                        historyBadge.style.padding = '2px 6px';
                        historyBadge.style.pointerEvents = 'none';
                        historyBadge.textContent = '✔ 曾有讨论';

                        // 插入到时间左边
                        timeEl.insertAdjacentElement('beforebegin', historyBadge);
                    } else {
                        historyBadge.style.display = 'inline-block';
                    }
                } else if (historyBadge) {
                    historyBadge.style.display = 'none';
                }
            }
        })
        .catch(err => console.error(err));
    }
});


      document.addEventListener("click", function(e) {
    if (e.target.classList.contains("reply-btn")) {
        let commentId = e.target.getAttribute("data-id");
        let postId = e.target.getAttribute("data-post-id");

        // 先检查该评论下是否已有回复框
        let existingForm = e.target.parentElement.querySelector(".reply-form");

        if (existingForm) {
            // 如果已经存在 -> 移除（收回）
            existingForm.remove();
        } else {
            // 移除其他地方的回复框，保证只显示一个
            document.querySelectorAll(".reply-form").forEach(f => f.remove());

            // 插入新回复框
            let formHtml = `
                <form class="reply-form mt-2"
                      data-parent-id="${commentId}"
                      data-post-id="${postId}">
                    <textarea class="form-control mb-2 reply-content"
                              rows="2" placeholder="回复..." required></textarea>
                    <button type="submit" class="btn btn-sm btn-primary">发送</button>
                </form>
            `;

            e.target.insertAdjacentHTML("afterend", formHtml);
        }
    }
});
document.addEventListener("submit", async function(e) {
    if (e.target.classList.contains("reply-form")) {
        e.preventDefault();

        let parentId = e.target.getAttribute("data-parent-id");
        let postId = e.target.getAttribute("data-post-id");
        let content = e.target.querySelector(".reply-content").value;

        try {
            let response = await fetch(`/posts/${postId}/comment`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    content: content,
                    parent_id: parentId
                })
            });

            let data = await response.json();

            if (data.success) {
                location.reload(); // 刷新页面，保证新回复出现
            } else {
                alert("提交失败：" + (data.message || "未知错误"));
            }
        } catch (error) {
            console.error(error);
            alert("网络错误，请稍后再试");
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // 公共函数 - 处理投票请求
    function handleVote(button, type) {
        const commentId = button.getAttribute('data-id');

        fetch(`/comments/${commentId}/${type}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            // 更新点赞 / 点踩数
            document.querySelector(`#like-count-${commentId}`).textContent = data.likes_count;
            document.querySelector(`#dislike-count-${commentId}`).textContent = data.dislikes_count;

            // 按钮样式切换
            const likeBtn = document.querySelector(`.like-btn[data-id="${commentId}"]`);
            const dislikeBtn = document.querySelector(`.dislike-btn[data-id="${commentId}"]`);

            if (type === 'like') {
                likeBtn.classList.add('btn-primary');
                likeBtn.classList.remove('btn-outline-secondary');
                dislikeBtn.classList.remove('btn-danger');
                dislikeBtn.classList.add('btn-outline-secondary');
            } else if (type === 'dislike') {
                dislikeBtn.classList.add('btn-danger');
                dislikeBtn.classList.remove('btn-outline-secondary');
                likeBtn.classList.remove('btn-primary');
                likeBtn.classList.add('btn-outline-secondary');
            }
        })
        .catch(err => console.error(`${type} 失败:`, err));
    }

    // 绑定点赞事件（父 + 子评论都能用）
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function () {
            handleVote(this, 'like');
        });
    });

    // 绑定点踩事件（父 + 子评论都能用）
    document.querySelectorAll('.dislike-btn').forEach(button => {
        button.addEventListener('click', function () {
            handleVote(this, 'dislike');
        });
    });
});
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.vote-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const commentId = this.getAttribute('data-id');
            const vote = this.getAttribute('data-vote');

            fetch(`/comments/${commentId}/vote`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ vote: vote }),
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById(`likes-${commentId}`).textContent = data.likes_count;
                document.getElementById(`dislikes-${commentId}`).textContent = data.dislikes_count;

                const likeBtn = document.querySelector(`.vote-btn[data-id="${commentId}"][data-vote="like"]`);
                const dislikeBtn = document.querySelector(`.vote-btn[data-id="${commentId}"][data-vote="dislike"]`);

                // 重置按钮样式
                likeBtn.classList.remove('btn-primary');
                likeBtn.classList.add('btn-outline-secondary');
                dislikeBtn.classList.remove('btn-danger');
                dislikeBtn.classList.add('btn-outline-secondary');

                // 高亮当前状态
                if (data.liked) {
                    likeBtn.classList.remove('btn-outline-secondary');
                    likeBtn.classList.add('btn-primary');
                }
                if (data.disliked) {
                    dislikeBtn.classList.remove('btn-outline-secondary');
                    dislikeBtn.classList.add('btn-danger');
                }
            })
            .catch(err => console.error('投票失败:', err));
        });
    });
});
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('edit-comment')) {
        e.preventDefault();

        const btn = e.target;
        const commentId = btn.getAttribute('data-id');
        const commentEl = document.querySelector(`#comment-${commentId}`);
        const contentEl = commentEl.querySelector('.comment-body');

        // 原始内容
        const originalContent = contentEl.textContent.trim();

        // 替换为可编辑输入框
        contentEl.innerHTML = `
            <textarea class="form-control edit-textarea">${originalContent}</textarea>
            <button class="btn btn-sm btn-success save-edit mt-2">保存</button>
            <button class="btn btn-sm btn-secondary cancel-edit mt-2">取消</button>
        `;

        // 保存修改
        contentEl.querySelector('.save-edit').addEventListener('click', function () {
            const newContent = contentEl.querySelector('.edit-textarea').value;

            fetch(`/comments/${commentId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ content: newContent })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    contentEl.textContent = data.content; // 替换显示
                }
            })
            .catch(err => console.error(err));
        });

        // 取消修改
        contentEl.querySelector('.cancel-edit').addEventListener('click', function () {
            contentEl.textContent = originalContent;
        });
    }
});

        // 父评论的删除功能
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-comment')) {
            e.preventDefault();

            const commentId = e.target.getAttribute('data-id');
            // ✅ 同时兼容父评论和子评论
            const commentElement = document.querySelector(`#comment-${commentId}`)
                                || document.querySelector(`#reply-${commentId}`);

            const postId = e.target.closest('.modal').getAttribute('id').split('-')[1];
            const commentCountElement = document.querySelector(`#comment-count-${postId}`);
            const commentList = document.querySelector(`#comment-list-${postId}`);

            if (!confirm('确定要删除这条评论吗？')) {
                return;
            }

            fetch(`/comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // ✅ 删除 DOM 节点
                    if (commentElement) {
                        commentElement.remove();
                    }

                    // ✅ 更新评论数
                    if (commentCountElement && data.commentCount !== undefined) {
                        commentCountElement.textContent = data.commentCount;
                    }

                    if (data.commentCount === 0 && commentList) {
                        commentList.innerHTML = `<p class="text-muted">还没有评论，快来抢沙发吧！</p>`;
                    }
                } else {
                    alert('删除失败: ' + data.error);
                }
            })
            .catch(error => console.error('删除错误:', error));
        }
    });
});


        // 显示分享面板
        function toggleSharePanel(postId) {
    let panel = document.getElementById("sharePanel-" + postId);
    panel.style.display = (panel.style.display === "none" ? "block" : "none");
}

        function showSharePanel() {
            document.getElementById('sharePanel').style.display = 'block';
        }

        // 隐藏分享面板
        function hideSharePanel() {
            document.getElementById('sharePanel').style.display = 'none';
        }

        // 分享到Whatsapp
        function shareToWhatsApp(url) {
        const shareUrl = `https://wa.me/?text=${encodeURIComponent(url)}`;
        window.open(shareUrl, '_blank');
        }

        // 分享到微博
        function shareToWeibo(url) {
            const shareUrl = `https://service.weibo.com/share/share.php?url=${encodeURIComponent(url)}`;
            window.open(shareUrl, '_blank');
        }

        // 分享到 Twitter（X）
        function shareToTwitter(title, url) {
            const shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`;
            window.open(shareUrl, '_blank');
        }

        // 分享到 Facebook
        function shareToFacebook(url) {
            const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
            window.open(shareUrl, '_blank');
        }

        function toggleFavorite(postId) {
    fetch(`/posts/${postId}/favorite`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        const favoriteBtn = document.getElementById(`favorite-btn-${postId}`);
        const favoriteCount = document.getElementById(`favorites-count-${postId}`);

        if (data.isFavorited) {
            favoriteBtn.innerHTML = `⭐ 已收藏 (<span id="favorites-count-${postId}">${data.favoritesCount}</span>)`;
        } else {
            favoriteBtn.innerHTML = `☆ 收藏 (<span id="favorites-count-${postId}">${data.favoritesCount}</span>)`;
        }
    })
    .catch(error => console.error('Error:', error));
}

async function translateAllTexts(targetLang) {
        const elements = document.querySelectorAll('.translate-text');
        for (let el of elements) {
            const original = el.dataset.original || el.textContent.trim();
            el.dataset.original = original;
            try {
                const translated = await fetchGoogleTranslate(original, DEFAULT_LANG, targetLang);
                el.textContent = translated;
            } catch (err) {
                console.error('翻译失败:', err);
            }
        }
    }

document.addEventListener('DOMContentLoaded', () => {
    const languageSelector = document.getElementById('languageSelector');
    const userLang = "{{ session('locale', 'zh') }}";

    // 先保存每个 translate-text 元素的原文
    document.querySelectorAll('.translate-text').forEach(el => {
        const original = el.dataset.original || el.textContent.trim();
        el.dataset.original = original;
    });

    if(languageSelector) {
        languageSelector.value = userLang;

        languageSelector.addEventListener('change', async function () {
            const selectedLang = this.value;

            if(selectedLang !== 'zh') {
                await translateAllTexts(selectedLang);
            } else {
                // 恢复原文
                document.querySelectorAll('.translate-text').forEach(el => {
                    if(el.dataset.original) el.textContent = el.dataset.original;
                });
            }

            window.location.href = `/change-language/${selectedLang}`;
        });
    }

    if(userLang !== 'zh') {
        translateAllTexts(userLang);
    }
});

    </script>
@endsection
