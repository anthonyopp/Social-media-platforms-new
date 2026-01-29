@extends('layouts.app')

@section('title', '个人中心')

@section('content')
<canvas id="profileBackgroundCanvas"></canvas> {{-- 先渲染 canvas 背景 --}}

<div id="profilePage" style="position: relative; z-index: 0;">
    <div class="profile-page">
        <div class="profile-header">
    {{-- 背景上传区域 --}}
    @if(Auth::check() && Auth::id() == $user->user_id)
        {{-- ✅ 自己的页面：可点击上传背景 --}}
        <form id="bgForm"
              action="{{ route('profile.updateImage') }}"
              method="POST"
              enctype="multipart/form-data"
              style="display: inline;">
            @csrf
            @method('PUT')

            <label for="backgroundInput" style="cursor: pointer;">
                <img class="profile-background"
                    src="{{ $user->profile && $user->profile->background_image && $user->profile->background_image !== 'default-bg.jpeg'
                        ? asset('storage/images/bg/' . $user->profile->background_image) . '?v=' . time()
                        : asset('images/default-bg.jpeg') }}"
                    onerror="this.onerror=null; this.src='{{ asset('images/default-bg.jpeg') }}';"
                    alt="背景图">
            </label>
            <input type="file" name="background_image" id="backgroundInput" accept="image/*" hidden>
        </form>
    @else
        {{-- 🚫 别人的页面：只显示背景，不可点击 --}}
        <img class="profile-background"
            src="{{ $user->profile && $user->profile->background_image && $user->profile->background_image !== 'default-bg.jpeg'
                ? asset('storage/images/bg/' . $user->profile->background_image) . '?v=' . time()
                : asset('images/default-bg.jpeg') }}"
            onerror="this.onerror=null; this.src='{{ asset('images/default-bg.jpeg') }}';"
            alt="背景图"
            style="pointer-events: none; cursor: default;">
    @endif
</div>



            {{-- 头像上传区域 --}}
            <!-- 点击头像：显示模态框 -->
            <form id="avatarForm"
      action="{{ route('profile.updateImage') }}"
      method="POST"
      enctype="multipart/form-data"
      style="display: inline;">
    @csrf
    @method('PUT')

    {{-- 判断是否本人 --}}
    @if(Auth::id() === $user->user_id)
        {{-- ✅ 本人可以点击头像触发上传 --}}
        <label for="avatarInput" style="cursor: pointer;">
            <img class="profile-avatar"
                src="{{ $user->profile && $user->profile->profile_picture && $user->profile->profile_picture !== 'defaultaaa.webp'
                    ? asset('storage/images/avatar/' . $user->profile->profile_picture) . '?v=' . time()
                    : asset('images/defaultaaa.webp') }}"
                onerror="this.onerror=null; this.src='{{ asset('images/defaultaaa.webp') }}';"
                alt="头像" />
        </label>
        <input type="file" name="profile_picture" id="avatarInput" accept="image/*" hidden>
    @else
        {{-- ✅ 其他人只能看，不能点 --}}
        <img class="profile-avatar"
            src="{{ $user->profile && $user->profile->profile_picture && $user->profile->profile_picture !== 'defaultaaa.webp'
                ? asset('storage/images/avatar/' . $user->profile->profile_picture) . '?v=' . time()
                : asset('images/defaultaaa.webp') }}"
            onerror="this.onerror=null; this.src='{{ asset('images/defaultaaa.webp') }}';"
            alt="头像"
            style="cursor: default;" />
    @endif
</form>



<!-- Modal -->
<div id="avatarModal" class="modal" style="display: none; overflow: hidden; /* 禁止外部滚动条 */
">
<div class="modal-content" style="width: 800px; max-width: 95%; max-height: 90vh; /* 限制最大高度为视口的90% */
        overflow-y: auto; /* 如果内容超出，内部滚动 */ margin: 10% auto; background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); position: relative;">
   <span id="closeModal"
   style="
     position: absolute;
     top: 15px;
     right: 20px;
     width: 32px;
     height: 32px;
     display: flex;
     align-items: center;
     justify-content: center;
     font-size: 22px;
     font-weight: bold;
     background-color: #f0f0f0;
     color: #333;
     border-radius: 50%;
     cursor: pointer;
     box-shadow: 0 2px 6px rgba(0,0,0,0.15);
     transition: background-color 0.3s ease, transform 0.2s ease;
   "
   onmouseover="this.style.backgroundColor='#e0e0e0'; this.style.transform='scale(1.1)'"
   onmouseout="this.style.backgroundColor='#f0f0f0'; this.style.transform='scale(1)'"
>
 &times;
</span>

   <div style="display: flex; gap: 20px;">
       <!-- 左边：当前头像展示 -->
       <div style="flex: 1; text-align: center;">
           <h4 style="margin-bottom: 10px; color: #000">当前头像</h4>
           <img id="previewCurrentAvatar"
            src="{{ $user->profile && $user->profile->profile_picture && $user->profile->profile_picture !== 'defaultaaa.webp'
                ? asset('storage/images/avatar/' . $user->profile->profile_picture)
                : asset('images/defaultaaa.webp') }}"
            onerror="this.onerror=null; this.src='{{ asset('images/defaultaaa.webp') }}';"
            style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;" />
            {{-- 更换按钮和隐藏字段 --}}
            <div style="display: flex; flex-direction: column; align-items: center; gap: 50px; margin-top: 20px;">
            <form id="presetAvatarForm" action="{{ route('profile.updateImage') }}" method="POST" style="margin-top: 10px;" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="selected_avatar" id="selectedAvatarInput">
                <button type="submit" class="btn btn-primary" style="margin-top: 8px;">更换</button>
            </form>
            <form id="avatarForm" action="{{ route('profile.updateImage') }}" method="POST" enctype="multipart/form-data" style="width: 200px; margin-left: -5px;">
                @csrf
                @method('PUT')
               {{-- 真正的 file input，隐藏 --}}
                <input type="file" name="profile_picture" id="avatarInput" accept="image/*" hidden>

                {{-- 不嵌套在 label，直接监听按钮点击 --}}
                <button type="button" class="btn btn-success" id="customAvatarBtn">上传自定义头像</button>
            </form>
        </div>
       </div>

       <!-- 右边：固定头像选择 -->
       <div style="flex: 2; padding: 20px; background: linear-gradient(135deg, #c3dafe, #e9d8fd); border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h4 style="margin-bottom: 15px; color: #333; text-align: center; font-weight: 600;">选择头像</h4>

        <div style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: center;">
            @for ($i = 1; $i <= 20; $i++)
                <img class="preset-avatar"
                     src="{{ asset('images/preset-avatar/preset-' . $i . '.webp') }}"
                     data-src="{{ asset('images/preset-avatar/preset-' . $i . '.webp') }}"
                     onerror="this.onerror=null; this.src='{{ asset('images/defaultaaa.webp') }}';"
                     style="
                        width: 70px;
                        height: 70px;
                        border-radius: 50%;
                        border: 2px solid transparent;
                        cursor: pointer;
                        object-fit: cover;
                        transition: transform 0.2s ease, border-color 0.2s ease;
                    "
                     onmouseover="this.style.transform='scale(1.1)'; this.style.borderColor='#007bff';"
                     onmouseout="this.style.transform='scale(1)'; this.style.borderColor='transparent';"
                />
            @endfor
        </div>
    </div>

   </div>
</div>
</div>

        <div class="profile-info">
            <div style="display: inline-flex; align-items: center; gap: 8px; margin-left: -50px">
                @if($user->role === 'teacher')
                    <div style="width: 50px; height: 50px; background-color: red; color: white;
                                display: flex; align-items: center; justify-content: center;
                                border-radius: 50%; font-weight: bold; font-size: 24px;">
                        T
                    </div>
                @elseif($user->role === 'student')
                    <div style="width: 50px; height: 50px; background-color: blue; color: white;
                                display: flex; align-items: center; justify-content: center;
                                border-radius: 50%; font-weight: bold; font-size: 24px;">
                        S
                    </div>
                @endif

                <h1 style="margin: 0;">{{ $user->name }}</h1>
            </div>
            <div id="signatureDisplay"
     @if(auth()->id() === $user->user_id)
         onclick="editSignature()" style="cursor: pointer; margin-top: 10px;"
     @endif>

    <p id="signatureText" style="margin-top: 20px; margin-bottom: 50px;">
        @if(auth()->id() === $user->user_id)
            {{-- 当前用户自己 --}}
            {{ $user->profile->signature ?? '点击添加签名' }}
        @else
            {{-- 其他人 --}}
            {{ $user->profile->signature ?? '这个人很懒，什么都没留下' }}
        @endif
    </p>
</div>

@if(auth()->id() === $user->user_id)
    <textarea id="signatureInput"
              style="display:none; width: 100%; padding: 6px; border-radius: 6px; border: 1px solid #ccc; margin-top: 20px;"
              onblur="saveSignature()"
              onkeydown="handleKeyDown(event)">
        {{ $user->profile->signature }}
    </textarea>
@endif
        </div>
        <div class="container mt-4 text-white">
    <!-- 顶部切换按钮 -->
    <div class="d-flex justify-content-around mb-4">
        <button class="btn btn-outline-light" onclick="showSection('posts')">📄 发帖</button>
        <button class="btn btn-outline-light" onclick="showSection('replies')">💬 回复</button>
        <button class="btn btn-outline-light" onclick="showSection('liked')">⭐ 点赞</button>
    </div>

    <!-- 共享内容区 -->
    <div id="shared-content">

        <!-- 默认展示的 Posts 内容 -->
        <div id="section-posts" class="mb-5">
    <h4 class="mb-4 text-primary fw-bold">
        <i class="bi bi-journal-text me-2"></i> 我的帖子
    </h4>

    <!-- 分类 Tabs -->
    <ul class="nav nav-tabs mb-3" id="postTabs">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#normal-posts">普通帖子</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#event-posts">活动帖子</a>
        </li>
    </ul>

    <div class="tab-content">
    <!-- 普通帖子 -->
    <div class="tab-pane fade show active" id="normal-posts">
        @forelse($posts as $post)
           <div class="card shadow-sm border-0 bg-dark text-white mb-4 post-card">
    <div class="card-body">
        <!-- 标题 -->
        <h5 class="card-title fw-bold text-info">
            <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none text-info">
                {{ $post->title ?? '无标题' }}
            </a>
        </h5>

        <!-- 内容预览 -->
        <p class="card-text text-light small">
            {{ Str::limit(strip_tags($post->content), 150, '...') }}
        </p>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <!-- 发布时间 -->
            <small class="text-white">
                <i class="bi bi-clock me-1"></i>
                {{ $post->created_at->diffForHumans() }}
            </small>

            <!-- 操作按钮 -->
            <div>
                <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-outline-light me-2">
                    <i class="bi bi-eye"></i> 查看
                </a>

                {{-- 编辑按钮（可启用） --}}
{{--
                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-outline-warning me-2">
                    <i class="bi bi-pencil-square"></i> 编辑
                </a>
                --}}

                {{-- 删除按钮（可启用） --}}
                {{--
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('确定要删除该帖子吗？')">
                        <i class="bi bi-trash"></i> 删除
                    </button>
                </form>
                --}}
            </div>
        </div>
    </div>
</div>

@empty
<div class="alert alert-secondary text-center" role="alert">
    <i class="bi bi-info-circle me-2"></i>
    你还没有发布过帖子。
</div>

        @endforelse
    </div>

    <!-- 活动帖子 -->
    <div class="tab-pane fade" id="event-posts">
        @forelse($eventPosts as $event)
    <div class="card shadow-sm border-0 bg-dark text-white mb-4 post-card">
        <div class="card-body">

            {{-- 活动封面 --}}
            @if($event->cover_image)
                <div class="mb-3 text-center">
                    <img src="{{ asset('storage/' . $event->cover_image) }}"
                         alt="活动封面"
                         class="img-fluid rounded shadow-sm"
                         style="max-height: 200px; object-fit: cover;">
                </div>
            @endif

            {{-- 标题 --}}
            <h5 class="card-title fw-bold text-primary mb-3">
                🎉 {{ $event->title ?? '无标题' }}
            </h5>

            {{-- 作者 & 发布时间 --}}
            <div class="d-flex align-items-center mb-2">
                <small>
                    {{ $event->user->name ?? '匿名用户' }} · {{ $event->created_at->diffForHumans() }}
                </small>
            </div>

            {{-- 活动描述 --}}
            <p class="card-text mb-3">
                <span class="fw-semibold">📖 描述：</span>
                <span>{{ $event->description ?? strip_tags($event->content) }}</span>
            </p>

            {{-- 活动信息 --}}
            <ul class="list-unstyled mb-3">
                {{-- 时间 --}}
                @if(isset($event->start_time) && isset($event->end_time))
                    <li class="mb-1">
                        <span class="fw-semibold">🕒 时间：</span>
                        <span>
                            {{ $event->start_time->format('Y-m-d H:i') }} - {{ $event->end_time->format('Y-m-d H:i') }}
                        </span>
                    </li>
                @endif

                {{-- 地点 --}}
                @if($event->location)
                    <li class="mb-1">
                        <span class="fw-semibold">📍 地点：</span>
                        <span>{{ $event->location }}</span>
                    </li>
                @endif

                {{-- 电话 --}}
                @if($event->phone)
                    <li class="mb-1">
                        <span class="fw-semibold">📞 联系电话：</span>
                        <span>
                            {{ preg_replace('/(\d{3})(\d{3,4})(\d{4})/', '$1-$2-$3', $event->phone) }}
                        </span>
                    </li>
                @endif

                {{-- 附件 --}}
                @if($event->attachment)
                    <li class="mb-1">
                        <span class="fw-semibold">📎 附件：</span>
                        <a href="{{ asset('storage/' . $event->attachment) }}" target="_blank" class="link-primary">
                            点击下载
                        </a>
                    </li>
                @endif

                {{-- 报名人数 --}}
                @php
                    $current = $event->participants_count ?? $event->users->count();
                    $capacity = $event->capacity;
                    $countClass = 'text-success';
                    if ($capacity) {
                        if ($current >= $capacity) {
                            $countClass = 'text-danger';
                        } elseif ($current >= $capacity / 2) {
                            $countClass = 'text-warning';
                        }
                    }
                @endphp
                <li class="mb-1">
                    <span class="fw-semibold">👥 报名人数：</span>
                    <span id="current-count-{{ $event->id }}" class="fw-bold {{ $countClass }}">
                        {{ $current }}
                    </span>
                    /
                    <span class="badge bg-secondary" id="capacity-{{ $event->id }}">
                        {{ $capacity ?? '不限' }}
                    </span>
                </li>

                {{-- 类型 + 状态 --}}
                <li>
                    <span class="fw-semibold">📌 类型：</span>
                    <span class="badge bg-info text-dark">{{ $event->type ?? '普通活动' }}</span>

                    <span class="fw-semibold ms-3">📊 状态：</span>
                    @php
                        $status = $event->status ?? 'pending';
                    @endphp
                    @if($status == 'pending')
                        <span class="badge bg-warning text-dark">未开始</span>
                    @elseif($status == 'published')
                        <span class="badge bg-success">进行中</span>
                    @elseif($status == 'closed')
                        <span class="badge bg-secondary">已结束</span>
                    @elseif($status == 'canceled')
                        <span class="badge bg-danger">已取消</span>
                    @endif
                </li>
            </ul>

            {{-- 互动区 --}}
            @php
                $isFull = $event->capacity && $event->users->count() >= $event->capacity;
                $isJoined = $event->users->contains(auth()->user()->user_id);
            @endphp

            <div class="d-flex flex-wrap gap-2">
                {{-- 提醒我 --}}
                @if($status === 'pending')
                    <form action="{{ route('events.remind', $event->id) }}" method="POST">
                        @csrf
                        @php
                            $reminded = $event->reminders->contains('user_id', auth()->user()->user_id);
                        @endphp
                        <button type="submit"
                            class="btn btn-sm {{ $reminded ? 'btn-outline-primary' : 'btn-primary' }}">
                            {{ $reminded ? '取消提醒' : '提醒我' }}
                        </button>
                    </form>
                @endif

                {{-- 点赞 / 评论 / 分享 --}}
                {{-- <button class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-hand-thumbs-up"></i> 点赞 ({{ $event->likes_count ?? 0 }})
                </button>
                <button class="btn btn-sm btn-outline-light">
                    <i class="bi bi-chat-dots"></i> 评论 ({{ $event->comments_count ?? 0 }})
                </button> --}}
                <button class="btn btn-sm btn-outline-info"
    onclick="
        const url = window.location.origin + '/events/{{ $event->id }}';
        if (navigator.share) {
            navigator.share({
                title: '{{ $event->title }}',
                url: url
            }).catch(console.error);
        } else {
            alert('当前浏览器不支持系统分享功能，请复制或点击链接：\n' + url);
            window.open(url, '_blank'); // 🔗 自动打开新窗口
        }
    ">
    分享
</button>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-secondary text-center">
        <i class="bi bi-info-circle me-2"></i> 你还没有发布过活动帖子。
    </div>
@endforelse

    </div>
</div>

</div>


        <!-- 回复内容 -->
        <div id="section-replies" style="display:none;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">💬 我的回复</h4>
        <select id="replySort" class="form-select w-auto bg-dark text-white border-secondary" onchange="sortReplies(this.value)">
            <option value="latest" selected>按时间排序</option>
            <option value="title">按帖子标题排序</option>
            <option value="unread">查看是否有新回复</option>
        </select>
    </div>

    <div id="reply-list">
        @forelse($replies as $reply)
            @php
                $post = $reply->post;
                $latestCommentTime = $post->comments->max('created_at');
                $hasNewReply = $latestCommentTime && $latestCommentTime->gt($reply->created_at);
            @endphp

            <div class="card bg-dark border-secondary mb-3 text-white reply-item"
                 data-time="{{ $reply->created_at->timestamp }}"
                 data-title="{{ $post->title ?? '无标题' }}"
                 data-unread="{{ $reply->hasNewReply ? 1 : 0 }}">
                <div class="card-body">
                    <p class="mb-1">
                        <strong>回复：</strong> {{ Str::limit(strip_tags($reply->content), 100) }}
                    </p>

                    <p class="mb-1">
                        <i class="bi bi-chat-dots"></i> 来自帖子：<a href="{{ route('posts.show', $post->id) }}" class="link-light text-decoration-underline">{{ $post->title ?? '无标题' }}</a>
                    </p>

                    <div class="d-flex justify-content-between text-muted small">
                        <div style="color: #9ecbff;">
                            回复于：{{ $reply->created_at->diffForHumans() }}
                            @if($reply->hasNewReply)
                                <span class="badge bg-warning text-dark ms-2">有新回复</span>
                            @endif
                        </div>

                        <div>
                            <a href="{{ route('posts.show', ['id' => $post->id]) }}#reply-{{ $reply->id }}" class="btn btn-sm btn-outline-light">跳转</a>
                            @if($reply->status === 'resolved')
                                <button class="btn btn-sm btn-success" disabled>已解决</button>
                            @elseif($reply->status === 'discussion')
                                <button class="btn btn-sm btn-info" disabled>讨论进行中</button>
                            @endif
                            <button
                                class="btn btn-sm btn-outline-info continue-discussion-btn"
                                data-reply-id="{{ $reply->id }}"
                                data-post-id="{{ $post->id }}">
                                继续讨论
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-secondary">你还没有评论过任何内容。</p>
        @endforelse
    </div>
</div>

        <!-- 点赞内容 -->
        <div id="section-liked" style="display: none;">
    <h4 class="mb-3">⭐ 我点赞的帖子</h4>

    @forelse($likedPosts as $liked)
        <div class="card border-0 bg-gradient bg-opacity-75 text-white mb-4 shadow-sm"
             style="background: linear-gradient(145deg, #1c1c3c, #2a2a4f); border-radius: 1rem; overflow: hidden;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title mb-0">{{ $liked->post->title ?? '无标题' }}</h5>
                    <span class="badge bg-warning text-dark">⭐ 已点赞</span>
                </div>
                <p class="card-text">{{ Str::limit(strip_tags($liked->post->content), 120) }}</p>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small style="color: #9ecbff;">
                        点赞于：{{ $liked->created_at->diffForHumans() }}
                    </small>
                    <a href="{{ route('posts.show', $liked->post->id) }}" class="btn btn-outline-light btn-sm">查看原帖</a>
                </div>
            </div>
        </div>
    @empty
        <p class="text-light">你还没有点赞任何内容。</p>
    @endforelse
</div>


    </div>
    <!-- 评论模态框 -->
<div class="modal fade" id="commentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title">继续讨论</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="commentForm" method="POST" action="">
          @csrf
          <input type="hidden" name="parent_id" id="comment-parent-id">
          <input type="hidden" name="post_id" id="comment-post-id">

          <div class="mb-3">
            <label for="comment-content" class="form-label">回复内容</label>
            <textarea class="form-control bg-secondary text-white" id="comment-content" name="content" rows="4" required></textarea>
          </div>

          <button type="submit" class="btn btn-primary">提交回复</button>
        </form>
      </div>
    </div>
  </div>
</div>

</div>

</div>

</div>


<style>
    @keyframes pulse-glow {
  0% {
    box-shadow: 0 0 0px rgba(0, 123, 255, 0.7);
  }
  50% {
    box-shadow: 0 0 12px 6px rgba(0, 123, 255, 0.4);
  }
  100% {
    box-shadow: 0 0 0px rgba(0, 123, 255, 0.7);
  }
}


.preset-avatar {
    transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
}

.preset-avatar:hover {
    transform: scale(1.1);
    border-color: #007bff;
}

/* 点击选中后专属样式 */
.selected-avatar {
    /* border: 2px solid #007bff !important;
    transform: scale(1.1) !important;
    box-shadow: 0 0 12px rgba(0, 123, 255, 0.5) !important; */
    animation: pulse-glow 1.5s infinite ease-in-out;
    z-index: 1;
}

.profile-page {
    position: relative;
    z-index: 1;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    color: #fff;
}

#profilePage {
    position: relative;
    overflow: hidden;
}

/* canvas 全屏覆盖当前 page div */
#profileBackgroundCanvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    z-index: -1;
    pointer-events: none;
}

.profile-header {
    position: relative;
    height: 300px;
    overflow: hidden;
    border-radius: 10px;
}
/* 头像现在不再被 header 裁剪 */
#avatarForm {
    position: relative;
    top: -40px; /* 移上来对齐背景图底部 */
    z-index: 5;
    text-align: center; /* 居中头像 */
    margin-left: 50%;
}

.profile-background {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
    display: block;
}

.profile-avatar {
    position: absolute;
    bottom: -75px; /* 半叠在背景图下方 */
    left: 50%;
    transform: translateX(-50%);
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: 5px solid #fff;
    object-fit: cover;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    background-color: #fff;
    z-index: 2;
}

/* 留头像的空间 */
.profile-info {
    margin-top: 70px;
    text-align: center;
}

.profile-info h1 {
    font-size: 26px;
    margin-bottom: 8px;
}

.profile-info p {
    font-size: 16px;
    color: rgba(255, 255, 255, 0.7);
}

.profile-bio {
    margin-top: 20px;
    padding: 15px;
    background-color: rgba(0, 0, 0, 0.5);
    border-radius: 10px;
    text-align: center;
    font-size: 14px;
    line-height: 1.6;
}

/* 响应式 */
@media (max-width: 768px) {
    .profile-avatar {
        width: 120px;
        height: 120px;
        bottom: -60px;
    }

    .profile-info {
        margin-top: 80px;
    }

    .profile-info h1 {
        font-size: 22px;
    }

    .profile-bio {
        font-size: 13px;
    }
}

.modal {
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}
.post-card {
    background-color: rgba(0, 0, 0, 0.5); /* 黑色半透明 */
    border-radius: 10px; /* 圆角 */
    backdrop-filter: blur(5px); /* 可选，加个模糊玻璃效果 */
}


</style>

<script>
    const bgInput = document.getElementById('backgroundInput');
if (bgInput) {
    bgInput.addEventListener('change', function() {
        document.getElementById('bgForm').submit();
    });
}


    document.addEventListener('DOMContentLoaded', function () {
    // ✅ 文件上传逻辑：头像
    const customAvatarBtn = document.getElementById('customAvatarBtn');
    const avatarInput = document.getElementById('avatarInput');
    const avatarForm = document.getElementById('avatarForm');

    if (customAvatarBtn && avatarInput && avatarForm) {

        // 点击按钮 -> 触发文件选择
        customAvatarBtn.addEventListener('click', function () {
            avatarInput.click();
        });

        // 选择完文件 -> 自动提交表单
        avatarInput.addEventListener('change', function () {
            if (this.files.length > 0) {
                console.log('📤 选中文件，准备提交头像');
                avatarForm.submit();
            } else {
                console.log('🚫 没有选择文件');
            }
        });
    } else {
        console.warn('⚠️ avatarInput 或 customAvatarBtn 或 avatarForm 未找到，脚本跳过执行');
    }
});




    document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById("avatarModal");
    const trigger = document.getElementById("triggerAvatarModal");
    const closeBtn = document.getElementById("closeModal");
    const presetAvatars = document.querySelectorAll('.preset-avatar');
    const selectedInput = document.getElementById('selectedAvatarInput');

    // 🟢 仅当触发器存在（即当前用户）才绑定
    if (trigger && modal) {
        // 打开模态框
        trigger.addEventListener("click", () => {
            modal.style.display = "block";
            document.body.style.overflow = 'hidden'; // 禁止页面滚动
        });
    }

    // 关闭按钮（有 modal 才能绑定）
    if (closeBtn && modal) {
        closeBtn.addEventListener("click", () => {
            modal.style.display = "none";
            document.body.style.overflow = 'auto'; // 恢复滚动
        });
    }

    // 点击空白关闭
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
            document.body.style.overflow = 'auto'; // 恢复滚动
        }
    });

    // ✅ 固定头像选择逻辑
    if (presetAvatars.length > 0 && selectedInput) {
        presetAvatars.forEach(avatar => {
            avatar.addEventListener('click', () => {
                presetAvatars.forEach(a => {
                    a.classList.remove('selected-avatar');
                    a.style.border = '2px solid transparent';
                    a.style.transform = 'scale(1)';
                    a.style.boxShadow = 'none';
                });

                avatar.classList.add('selected-avatar');
                avatar.style.border = '2px solid #007bff';
                avatar.style.transform = 'scale(1.1)';
                avatar.style.boxShadow = '0 0 12px rgba(0, 123, 255, 0.5)';

                const src = avatar.dataset.src;
                const fileName = src.split('/').pop();
                selectedInput.value = fileName;
            });
        });
    }
});


function editSignature() {
        const display = document.getElementById('signatureDisplay');
        const input = document.getElementById('signatureInput');

        display.style.display = 'none';
        input.style.display = 'block';
        input.focus();
    }

    function handleKeyDown(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault(); // 阻止换行
            saveSignature();
        }
    }

    function saveSignature() {
    const input = document.getElementById('signatureInput');
    const display = document.getElementById('signatureDisplay');
    const text = document.getElementById('signatureText');
    const newSignature = input.value.trim(); // ✅ 去除多余空格
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const isSelf = {{ auth()->id() === $user->user_id ? 'true' : 'false' }}; // ✅ 判断是否本人

    fetch("{{ route('profile.updateSignature') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ signature: newSignature })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // ✅ 根据是否本人显示不同默认文本
            if (newSignature === '') {
                text.innerText = isSelf ? '点击添加签名' : '这个人很懒，什么都没留下';
            } else {
                text.innerText = newSignature;
            }
        } else {
            alert('更新失败');
        }

        // ✅ 无论成功与否都恢复显示状态
        input.style.display = 'none';
        display.style.display = 'block';
    })
    .catch(err => {
        alert('请求出错');
        console.error(err);

        // ✅ 出错时也保持默认文本存在
        if (text.innerText.trim() === '') {
            text.innerText = isSelf ? '点击添加签名' : '这个人很懒，什么都没留下';
        }

        input.style.display = 'none';
        display.style.display = 'block';
    });
}


    document.addEventListener('DOMContentLoaded', function () {
    const commentModal = new bootstrap.Modal(document.getElementById('commentModal'));

    document.querySelectorAll('.continue-discussion-btn').forEach(button => {
        button.addEventListener('click', function () {
            const replyId = this.getAttribute('data-reply-id');
            const postId = this.getAttribute('data-post-id');

            // 设置隐藏字段
            document.getElementById('comment-parent-id').value = replyId;
            document.getElementById('comment-post-id').value = postId;

             // 设置 form 的 action，带上 postId
            document.getElementById('commentForm').action = `/posts/${postId}/comment`;

            // 打开模态框
            commentModal.show();

            // 聚焦到文本框
            setTimeout(() => {
                document.getElementById('comment-content').focus();
            }, 300);
        });
    });
});

function sortReplies(type) {
    let replyList = document.getElementById("reply-list");
    let items = Array.from(replyList.querySelectorAll(".reply-item"));

    items.sort((a, b) => {
        if (type === "latest") {
            return b.dataset.time - a.dataset.time; // 时间倒序
        }
        if (type === "title") {
            return a.dataset.title.localeCompare(b.dataset.title, 'zh'); // 中文标题排序
        }
        if (type === "unread") {
            return b.dataset.unread - a.dataset.unread; // 有新回复的优先
        }
    });

    items.forEach(item => replyList.appendChild(item)); // 重新插入排序后的 DOM
}

// 页面加载默认执行一次（按时间）
document.addEventListener("DOMContentLoaded", () => {
    sortReplies("latest");
});

const canvas = document.getElementById('profileBackgroundCanvas');
const ctx = canvas.getContext('2d');

function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
}

resizeCanvas();
window.addEventListener('resize', resizeCanvas);

// 星云配置
const leftNebula = { x: 100, y: canvas.height / 2, radius: 40, maxRadius: 300, speed: 1 };
const rightNebula = { x: canvas.width - 100, y: canvas.height / 2, radius: 40, maxRadius: 300, speed: 1 };

// 星屑流动
const particles = [];
for (let i = 0; i < 150; i++) {
    particles.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        speedX: Math.random() * 0.6 - 0.3,
        speedY: Math.random() * 0.6 - 0.3,
        radius: Math.random() * 3 + 1,
        opacity: Math.random() * 0.5 + 0.5,
        color: Math.random() > 0.5 ? '#8b5cf6' : '#60a5fa'
    });
}

// 更新星云
function updateNebula(nebula) {
    nebula.radius += nebula.speed;
    if (nebula.radius > nebula.maxRadius) {
        nebula.radius = 40;
    }
}

// 更新星屑
function updateParticles() {
    particles.forEach((p) => {
        p.x += p.speedX;
        p.y += p.speedY;
        if (p.x < -50) p.x = canvas.width + 50;
        if (p.x > canvas.width + 50) p.x = -50;
        if (p.y < -50) p.y = canvas.height + 50;
        if (p.y > canvas.height + 50) p.y = -50;
    });
}

// 绘制星云
function drawNebula(nebula) {
    const gradient = ctx.createRadialGradient(nebula.x, nebula.y, nebula.radius / 5, nebula.x, nebula.y, nebula.radius);
    gradient.addColorStop(0, 'rgba(139, 92, 246, 0.6)');
    gradient.addColorStop(1, 'rgba(96, 165, 250, 0)');
    ctx.beginPath();
    ctx.arc(nebula.x, nebula.y, nebula.radius, 0, Math.PI * 2);
    ctx.fillStyle = gradient;
    ctx.fill();
}

// 绘制星屑
function drawParticles() {
    particles.forEach((p) => {
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(${hexToRgb(p.color)}, ${p.opacity})`;
        ctx.fill();
    });
}

// HEX 转 RGB
function hexToRgb(hex) {
    const bigint = parseInt(hex.replace('#', ''), 16);
    return `${(bigint >> 16) & 255}, ${(bigint >> 8) & 255}, ${bigint & 255}`;
}

// 动画主循环
function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // 绘制星云和星屑
    drawNebula(leftNebula);
    drawNebula(rightNebula);
    drawParticles();

    // 更新状态
    updateNebula(leftNebula);
    updateNebula(rightNebula);
    updateParticles();

    requestAnimationFrame(animate);
}

animate();
</script>


@endsection
@push('scripts')
<script>
    function showSection(section) {
        const sections = ['posts', 'replies', 'liked'];
        sections.forEach(s => {
            const el = document.getElementById(`section-${s}`);
            if (el) el.style.display = (s === section) ? 'block' : 'none';
        });
    }

    // 默认加载 Posts
    document.addEventListener('DOMContentLoaded', () => {
        showSection('posts');
    });

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
@endpush

