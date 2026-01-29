@extends('layouts.app')

@section('title', '活动')

@section('content')
    <div class="col mt-5"> <!-- 解决导航栏遮挡问题 -->
        <h2 class="mb-4">最新活动</h2>

    <!-- 活动帖子列表 -->
    @foreach($events as $event)
    <div class="card-wrapper position-relative mb-3">
    <!-- 左侧按钮栏（在 card 外） -->
    <div class="action-bar d-flex flex-column justify-content-center position-absolute">
        {{-- <button class="btn btn-sm text-secondary mb-2" title="回顾">
            <i class="fas fa-history"></i>
        </button> --}}
           @php
            $now = now();
            if ($event->status === 'canceled') {
                $status = 'canceled';
            } elseif ($now->lt($event->start_time)) {
                $status = 'pending'; // 未开始
            } elseif ($now->between($event->start_time, $event->end_time)) {
                $status = 'published'; // 进行中
            } else {
                $status = 'closed'; // 已结束
            }
        @endphp
        @if($status == 'pending' && Auth::id() === $event->user_id)
            <button
                id="cancel-btn-{{ $event->id }}"
                class="btn btn-sm text-secondary mb-2"
                title="取消活动"
                onclick="cancelEvent({{ $event->id }})"
            >
                <i class="fas fa-times-circle"></i>
            </button>
        @endif
        <button class="btn btn-sm text-secondary" title="举报">
            <i class="fas fa-flag"></i>
        </button>
    </div>

    <div class="card mb-3 shadow-sm">
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

            <h5 class="card-title fw-bold text-primary mb-3">
                🎉 活动标题：{{ $event->title }}
            </h5>

            <p class="card-text mb-3">
                <span class="fw-semibold">📖 活动描述：</span>
                <span class="text-body">{{ $event->description }}</span>
            </p>

            <ul class="list-unstyled mb-3">
                {{-- 活动时间 --}}
                <li class="mb-1">
                    <span class="fw-semibold text-dark">🕒 活动时间：</span>
                    <span class="text-muted">
                        {{ $event->start_time->format('Y-m-d H:i') }} - {{ $event->end_time->format('Y-m-d H:i') }}
                    </span>
                </li>

                {{-- 活动地点 --}}
                <li class="mb-1">
                    <span class="fw-semibold text-dark">📍 地点：</span>
                    <span class="text-muted">{{ $event->location }}</span>
                </li>

                {{-- 电话 --}}
                @if($event->phone)
                    <li class="mb-1">
                        <span class="fw-semibold text-dark">📞 联系电话：</span>
                        <span class="text-muted">
                            {{ preg_replace('/(\d{3})(\d{3,4})(\d{4})/', '$1-$2-$3', $event->phone) }}
                        </span>
                    </li>
                @endif

                {{-- 附件 --}}
                @if($event->attachment)
                    <li class="mb-1">
                        <span class="fw-semibold text-dark">📎 附件：</span>
                        <a href="{{ asset('storage/' . $event->attachment) }}" target="_blank" class="link-primary">
                            点击下载
                        </a>
                    </li>
                @endif

                {{-- 活动人数 / 限制 --}}
                @php
                    $current = $event->participants_count ?? $event->users->count();
                    $capacity = $event->capacity;
                    $countClass = 'text-success'; // 默认绿色

                    if ($capacity) {
                        if ($current >= $capacity) {
                            $countClass = 'text-danger'; // 满员
                        } elseif ($current >= $capacity / 2) {
                            $countClass = 'text-warning'; // 超过一半
                        }
                    }
                @endphp

                <li class="mb-1">
    <span class="fw-semibold text-dark">👥 报名人数：</span>
    <span id="current-count-{{ $event->id }}" class="fw-bold {{ $countClass }}">
        {{ $current }}
    </span>
    /
    <span class="badge bg-secondary" id="capacity-{{ $event->id }}">
        {{ $capacity ?? '不限' }}
    </span>
</li>


                {{-- 活动类型 + 状态 --}}
                <li>
                    <span class="fw-semibold text-dark">📌 类型：</span>
                    <span class="badge bg-info text-dark">{{ $event->type ?? '普通活动' }}</span>

                    <span class="fw-semibold text-dark ms-3">📊 状态：</span>

                    @if($status == 'pending')
                        <span id="status-badge-{{ $event->id }}" class="badge bg-warning text-dark">未开始</span>
                    @elseif($status == 'published')
                        <span id="status-badge-{{ $event->id }}" class="badge bg-success">进行中</span>
                    @elseif($status == 'closed')
                        <span id="status-badge-{{ $event->id }}" class="badge bg-secondary">已结束</span>
                    @elseif($status == 'canceled')
                        <span id="status-badge-{{ $event->id }}" class="badge bg-danger">已取消</span>
                    @endif
                </li>
            </ul>


            <!-- 活动互动按钮 -->
            <div class="d-flex flex-wrap gap-2">
@php
    $isFull = $event->capacity && $event->users->count() >= $event->capacity;
    $isJoined = $event->users->contains(auth()->user()->user_id);
@endphp

<button
    class="btn btn-sm
        @if($status === 'canceled' || $status === 'closed' || $status === 'published')
            btn-secondary
        @elseif($isJoined)
            btn-outline-danger
        @elseif($isFull)
            btn-secondary
        @else
            btn-success
        @endif"
    id="join-btn-{{ $event->id }}"
    @if(in_array($status, ['canceled','closed','published']) || ($isFull && !$isJoined)) disabled @endif
    onclick="toggleJoin({{ $event->id }})"
>
    @if($status === 'canceled')
        活动已取消
    @elseif($status === 'closed')
        活动已结束
    @elseif($status === 'published')
        活动进行中
    @elseif($isJoined)
        取消报名
    @elseif($isFull)
        已满员
    @else
        报名
    @endif
</button>

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

                {{-- 点赞 / 取消点赞 --}}
                {{-- <form action="{{ route('events.like', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $event->likedUsers->contains(auth()->id()) ? 'btn-outline-success' : 'btn-success' }}">
                        👍 {{ $event->likes_count ?? 0 }}
                    </button>
                </form> --}}

                {{-- 收藏 --}}
                {{-- <form action="{{ route('events.favorite', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="btn btn-sm {{ $event->favorites->contains('user_id', auth()->id()) ? 'btn-outline-warning' : 'btn-warning' }}">
                        {{ $event->favorites->contains('user_id', auth()->id()) ? '已收藏' : '收藏' }}
                    </button>
                </form> --}}

                {{-- 评论入口 --}}
                {{-- <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-outline-secondary">
                    评论 ({{ $event->comments_count ?? 0 }})
                </a> --}}

                {{-- 举报 --}}
                {{-- <form action="{{ route('events.report', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        举报
                    </button>
                </form> --}}

                {{-- 分享 --}}
                <button class="btn btn-sm btn-outline-info"
                        onclick="navigator.share ? navigator.share({title:'{{ $event->title }}', url: '{{ route('events.show',$event->id) }}'}) : alert('当前浏览器不支持分享')">
                    分享
                </button>

            </div>
        </div>
    </div>
    </div>
@endforeach

    <!-- 如果没有活动 -->
    @if($events->isEmpty())
        <p class="text-muted">暂无活动</p>
    @endif
    </div>

    <div class="bottom-right-container">
        <a href="{{ route('events.create') }}" class="add-post-btn">+</a>
        {{-- <img src="{{ asset('Muelsyse.png') }}" id="bottom-right-image" alt="Firefly Image"> --}}
    </div>

    <canvas id="eventCanvas"></canvas>

<style>
    .card-body {
    position: relative;
    overflow: hidden; /* 避免超出 */
}

.card-wrapper {
    position: relative;
}

.action-bar {
    top: 50%;
    left: -45px;              /* 挪到 card 外面 */
    transform: translateY(-50%); /* 垂直居中 */
    opacity: 0;
    transition: all 0.3s ease;
}

.card-wrapper:hover .action-bar {
    opacity: 1;
    left: -35px;              /* hover 时轻轻滑入 */
}

.action-bar .btn {
    background: transparent;
    border: none;
    font-size: 1.2rem;
    color: #6c757d;           /* 暗灰色 */
}

.action-bar .btn:hover {
    color: #000;              /* hover 高亮 */
}


    /* Canvas 背景 */
    #eventCanvas {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1; /* 确保在背景层 */
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
            right: 0px; /* 距离右侧 20px */
            bottom: 30px; /* 距离底部 30px，调整 margin-bottom */
            width: 350px; /* 增大图片尺寸 */
            height: auto;
            z-index: 1000; /* 确保图片显示在前面 */
            opacity: 0.9; /* 轻微透明度，让图片更融合 */
            transition: transform 0.3s ease-in-out;
        }

        #bottom-right-image:hover {
            transform: scale(1.1);
            opacity: 1;
        }
</style>

<script>
    async function toggleJoin(eventId) {
    let btn = document.getElementById(`join-btn-${eventId}`);
    let currentCountEl = document.getElementById(`current-count-${eventId}`);

    try {
        let response = await fetch(`/events/${eventId}/join`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
        });

        let result = await response.json();

        // ✅ 更新按钮 HTML
        if (result.button) {
            btn.outerHTML = result.button;
        }

        // ✅ 更新人数 + 样式
        if (result.current !== undefined && currentCountEl) {
            currentCountEl.textContent = result.current;
            currentCountEl.className = `fw-bold ${result.countClass}`;
        }

    } catch (error) {
        console.error(error);
        alert('操作失败，请重试');
    }
}

async function cancelEvent(eventId) {
    let btn = document.getElementById(`cancel-btn-${eventId}`);
    if (!btn) return;

    if (!confirm('确定要取消该活动吗？')) return;

    // 禁用按钮避免重复点击
    btn.disabled = true;

    try {
        let response = await fetch(`/events/${eventId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
        });

        let result = await response.json();

        if (result.status === 'success') {
            // ✅ 更新状态 badge
            if (result.badge) {
                document.getElementById(`status-badge-${eventId}`).outerHTML = result.badge;
            }

            // ✅ 更新报名按钮
            if (result.button) {
                let joinBtn = document.getElementById(`join-btn-${eventId}`);
                if (joinBtn) joinBtn.outerHTML = result.button;
            }

            // ✅ 取消按钮自身处理
            if (result.hideCancel) {
                btn.remove();
            }
        } else {
            btn.disabled = false; // 如果失败，恢复可用
        }
    } catch (error) {
        console.error(error);
        btn.disabled = false;
        alert('操作失败，请重试');
    }
}


    const eventCanvas = document.getElementById("eventCanvas");
    const ctx = eventCanvas.getContext("2d");

    eventCanvas.width = window.innerWidth;
    eventCanvas.height = window.innerHeight;

    // 响应式调整
    window.addEventListener("resize", () => {
        eventCanvas.width = window.innerWidth;
        eventCanvas.height = window.innerHeight;
    });

    // 粒子类
    class Particle {
        constructor() {
            this.reset();
        }

        reset() {
            this.x = Math.random() * eventCanvas.width;
            this.y = Math.random() * eventCanvas.height;
            this.radius = Math.random() * 3 + 1;
            this.dx = Math.random() * 1.5 - 0.75; // 速度更慢，适配活动氛围
            this.dy = Math.random() * 1.5 - 0.75;
            this.opacity = Math.random();
            this.flickerSpeed = Math.random() * 0.02 + 0.01; // 闪烁速度
        }

        update() {
            this.x += this.dx;
            this.y += this.dy;

            // 边界反弹
            if (this.x < 0 || this.x > eventCanvas.width) this.dx *= -1;
            if (this.y < 0 || this.y > eventCanvas.height) this.dy *= -1;

            // 闪烁效果
            this.opacity += this.flickerSpeed * (Math.random() > 0.5 ? 1 : -1);
            this.opacity = Math.min(1, Math.max(0.2, this.opacity));

            this.draw();
        }

        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(255, 255, 255, ${this.opacity})`;
            ctx.fill();
        }
    }

    // 流星类
    class Meteor {
        constructor() {
            this.reset();
        }

        reset() {
            this.x = Math.random() * eventCanvas.width;
            this.y = Math.random() * eventCanvas.height * 0.5; // 限制在上半部分
            this.length = Math.random() * 60 + 30;
            this.speed = Math.random() * 4 + 2;
            this.opacity = Math.random() * 0.7 + 0.3;
        }

        update() {
            this.x -= this.speed;
            this.y += this.speed;

            if (this.x < 0 || this.y > eventCanvas.height) {
                this.reset();
            }

            this.draw();
        }

        draw() {
            const gradient = ctx.createLinearGradient(this.x, this.y, this.x + this.length, this.y - this.length);
            gradient.addColorStop(0, `rgba(255, 255, 255, ${this.opacity})`);
            gradient.addColorStop(1, "rgba(0, 0, 0, 0)");

            ctx.beginPath();
            ctx.moveTo(this.x, this.y);
            ctx.lineTo(this.x + this.length, this.y - this.length);
            ctx.strokeStyle = gradient;
            ctx.lineWidth = 2;
            ctx.stroke();
        }
    }

    // 星云背景
    function drawNebula() {
        const gradient = ctx.createRadialGradient(
            eventCanvas.width / 2,
            eventCanvas.height / 2,
            eventCanvas.width * 0.1,
            eventCanvas.width / 2,
            eventCanvas.height / 2,
            eventCanvas.width * 0.5
        );
        gradient.addColorStop(0, "rgba(58, 45, 85, 0.8)");
        gradient.addColorStop(0.5, "rgba(38, 20, 60, 0.5)");
        gradient.addColorStop(1, "rgba(10, 5, 30, 0.2)");

        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, eventCanvas.width, eventCanvas.height);
    }

    // 创建粒子和流星
    const particles = Array.from({ length: 120 }, () => new Particle());
    const meteors = Array.from({ length: 3 }, () => new Meteor());

    // 动画循环
    function animate() {
        ctx.clearRect(0, 0, eventCanvas.width, eventCanvas.height);
        drawNebula(); // 星云背景
        particles.forEach((p) => p.update());
        meteors.forEach((m) => m.update());
        requestAnimationFrame(animate);
    }

    animate();

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
