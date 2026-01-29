@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="container mt-4 text-light">

    {{-- 帖子卡片 --}}
<div class="card border-glow mb-4">
        <!-- 发帖人信息 -->
        <div class="card-header d-flex align-items-center border-bottom border-secondary">
            <div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden;" class="me-3">
                <img
                    src="{{ $post->user->profile && $post->user->profile->profile_picture && $post->user->profile->profile_picture !== 'defaultaaa.webp'
                        ? asset('storage/images/avatar/' . $post->user->profile->profile_picture) . '?v=' . time()
                        : asset('images/defaultaaa.webp') }}"
                    onerror="this.onerror=null; this.src='{{ asset('images/defaultaaa.webp') }}';"
                    alt="User Avatar"
                    style="width: 100%; height: 100%; object-fit: cover;"
                >
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
                <div class="text-muted small fw-bold translate-text">
                    发布于 {{ $post->created_at->translatedFormat('Y年m月d日 H:i') }}（{{ $post->created_at->diffForHumans() }}）
                </div>
            </div>
        </div>

        <!-- 帖子正文 -->
        <div class="card card-glow">
            <h3 class="mb-3 glow-text">{{ $post->title ?? '无标题' }}</h3>

            <div class="mb-4" style="white-space: pre-line;">
                {!! nl2br(e($post->content)) !!}
            </div>

            <!-- 图片展示 -->
            @if (!empty($post->images))
                @php
                    $images = is_array($post->images) ? $post->images : json_decode($post->images, true);
                    $imageCount = count($images);
                @endphp

                <div class="media-container mb-3 image-count-{{ $imageCount }}">
                    @foreach ($images as $index => $image)
                        <img src="{{ asset('storage/' . $image) }}"
                            class="media-content img-thumbnail me-2 mb-2"
                            style="max-height: 180px; cursor: pointer;"
                            alt="帖子图片"
                            onclick="openFullscreen('{{ asset('storage/' . $image) }}')">
                    @endforeach
                </div>
            @endif

            <!-- 视频展示 -->
            @if ($post->video)
                <div class="media-container mb-3">
                    <video controls class="media-content w-100 rounded border translate-text">
                        <source src="{{ asset('storage/' . $post->video) }}" type="video/mp4">
                        您的浏览器不支持视频播放。
                    </video>
                </div>
            @endif

            <!-- 标签 -->
            @if (!empty($post->tags))
                <div class="mb-3">
                    @foreach (json_decode($post->tags, true) as $tag)
                        <a href="{{ route('home', ['tag' => $tag]) }}"
                           class="badge bg-info text-dark text-decoration-none me-1 translate-text">
                            #{{ $tag }}
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="post-actions mt-3">
    <div class="btn-group flex-wrap d-flex gap-2 justify-content-between align-items-center">

        {{-- ❤️ 点赞按钮 --}}
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

        {{-- 💬 评论按钮 --}}
        <button class="btn btn-outline-primary translate-text" data-bs-toggle="modal" data-bs-target="#commentModal-{{ $post->id }}">
            💬 评论 (<span id="comment-count-{{ $post->id }}">{{ $post->comments->count() }}</span>)
        </button>

        {{-- 评论模态框 --}}


        {{-- ⭐ 收藏按钮 --}}
        <button id="favorite-btn-{{ $post->id }}"
                class="btn btn-outline-warning translate-text"
                onclick="toggleFavorite({{ $post->id }})">
            @if(auth()->check() && auth()->user()->favorites()->where('post_id', $post->id)->exists())
                ⭐ 已收藏 (<span id="favorites-count-{{ $post->id }}">{{ $post->favorites()->count() }}</span>)
            @else
                ☆ 收藏 (<span id="favorites-count-{{ $post->id }}">{{ $post->favorites()->count() }}</span>)
            @endif
        </button>

        {{-- 🔄 分享按钮 --}}
        <button class="btn btn-outline-success translate-text" onclick="showSharePanel()">🔄 分享</button>
    </div>

    {{-- 分享面板 --}}
    <div id="sharePanel" class="share-panel mt-2" style="display: none;">
        <div class="btn-group flex-wrap d-flex gap-2">
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
            <button class="btn btn-outline-danger translate-text" onclick="hideSharePanel()">❌ 关闭</button>
        </div>
    </div>


</div>

        </div>
    </div>

    <div class="modal fade" id="commentModal-{{ $post->id }}" tabindex="-1" aria-labelledby="commentModalLabel-{{ $post->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-light border-secondary"> {{-- ✅ 修复：加深色背景和浅色文字 --}}
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalLabel-{{ $post->id }}">评论区</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div id="comment-list-{{ $post->id }}" class="comment-scroll-container" style="max-height: 300px; overflow-y: auto;">
                        @foreach ($post->comments as $comment)
                            <div class="mb-3" id="comment-{{ $comment->id }}">
                                <strong>{{ $comment->user->name }}</strong>：
                                <p>{{ $comment->content }}</p>
                                <small>{{ $comment->created_at->diffForHumans() }}</small>

                                @if(auth()->id() === $comment->user_id)
                                    <button class="btn btn-danger btn-sm delete-comment" data-id="{{ $comment->id }}">删除</button>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <form class="comment-form mt-3" data-post-id="{{ $post->id }}">
                        @csrf
                        <textarea class="comment-content form-control" rows="3" placeholder="输入你的评论..." required></textarea>
                        <button type="submit" class="btn btn-primary mt-2">发表评论</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 放大图层 -->
    <div id="fullscreenOverlay" class="fullscreen-overlay d-none" onclick="closeFullscreen()"
         style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.9); z-index:1050; display:flex; align-items:center; justify-content:center;">
        <img id="fullscreenImg" class="fullscreen-img" alt="全屏图片"
             style="max-height: 90%; max-width: 90%; border-radius: 12px;">
    </div>

</div>

<style>
/* 🌌 帖子卡片：炫彩动态边框 */
.card.border-glow {
    position: relative;
    border: 2px solid transparent;
    background: #0b0f1a; /* 深蓝黑星空底色 */
    border-radius: 12px;
    overflow: hidden;
    z-index: 1;
}

.card.border-glow::before {
    content: '';
    position: absolute;
    top: -2px; left: -2px;
    width: calc(100% + 4px);
    height: calc(100% + 4px);
    border-radius: 14px;
    background: linear-gradient(60deg, #5ef1f2, #b576f5, #5ef1f2);
    background-size: 400% 400%;
    animation: glowBorder 10s linear infinite;
    z-index: -1;
}

.card.card-glow {
    background: rgba(20, 20, 30, 0.92); /* 深蓝灰暗背景 */
    border: 1px solid rgba(255, 255, 255, 0.08); /* 柔和边框 */
    border-radius: 12px;
    box-shadow:
        inset 0 0 12px rgba(255, 255, 255, 0.02), /* 内阴影柔化边缘 */
        0 0 8px rgba(0, 0, 0, 0.4);              /* 外阴影塑造层次感 */
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    color: #eaeaea;
}

@keyframes glowBorder {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* ✨ 文本颜色优化 */
.card .card-header strong,
.card .badge,
.card .card-body,
.card .card-body h3,
.card .card-body p,
.card .card-body small {
    color: #ffffff !important;
}

.card .card-header .text-muted {
    color: #b0c4ff !important;
}

.card .badge.bg-primary {
    background-color: #3e8eff !important;
}
.card .badge.bg-danger {
    background-color: #f85c5c !important;
}
.card .badge.bg-secondary {
    background-color: #9f9f9f !important;
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



{{-- 简单图片放大 JS --}}
<script>
    function openFullscreen(src) {
        const overlay = document.getElementById('fullscreenOverlay');
        const img = document.getElementById('fullscreenImg');
        img.src = src;
        overlay.classList.remove('d-none');
    }

    function closeFullscreen() {
        const overlay = document.getElementById('fullscreenOverlay');
        const img = document.getElementById('fullscreenImg');
        overlay.classList.add('d-none');
        img.src = '';
    }


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



            // 提交评论
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

                        // 更新评论列表
                        const commentHTML = `
                            <div class="mb-3" id="comment-${data.comment.id}">
                                <strong>${data.comment.user.name}</strong>：
                                <p>${data.comment.content}</p>
                                <small>刚刚</small>
                                <button class="btn btn-danger btn-sm delete-comment" data-id="${data.comment.id}">删除</button>
                            </div>
                        `;
                        commentList.insertAdjacentHTML('beforeend', commentHTML);

                        // 更新评论数量
                        commentCount.textContent = data.commentCount;

                        // 自动滚动到底部
                        commentList.scrollTop = commentList.scrollHeight;
                    })
                    .catch(error => console.error('评论失败:', error));
                });
            });
        });


        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('delete-comment')) {
                    e.preventDefault();

                    const commentId = e.target.getAttribute('data-id');
                    const commentElement = document.querySelector(`#comment-${commentId}`);
                    const postId = e.target.closest('.modal').getAttribute('id').split('-')[1];
                    const commentCountElement = document.querySelector(`#comment-count-${postId}`);

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
                            // 删除评论 DOM 节点
                            commentElement.remove();

                            // 更新评论数量
                            commentCountElement.textContent = data.commentCount;
                        } else {
                            alert('删除失败: ' + data.error);
                        }
                    })
                    .catch(error => console.error('删除错误:', error));
                }
            });
        });

        // 显示分享面板
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
