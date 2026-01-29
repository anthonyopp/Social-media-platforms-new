@extends('layouts.app')

@section('title', '消息中心')

@section('content')
<div class="position-relative" style="height:100vh; overflow:hidden;">

    {{-- 星空背景 canvas --}}
    <canvas id="starfield"
        style="position:absolute; top:0; left:0; width:100%; height:100%; z-index:-1;">
    </canvas>

    <div class="container py-5 text-white">
    <h2 class="mb-4">📩 我的通知</h2>

    {{-- 没有通知 --}}
    @if($notifications->isEmpty() && $friendRequests->isEmpty())
        <div class="alert alert-info">暂无通知</div>
    @else
        {{-- Tab 导航（固定两个 tab） --}}
        <ul class="nav nav-tabs" id="notificationTabs" role="tablist">
            @php
                $unreadCount = $notifications->where('is_read', false)->count();
                $friendCount = $friendRequests->count();
            @endphp

            {{-- 系统通知 Tab --}}
            <li class="nav-item" role="presentation">
                <button class="nav-link active d-flex align-items-center" id="system-tab"
                        data-bs-toggle="tab" data-bs-target="#system" type="button" role="tab">
                    📢 系统通知
                    @if($unreadCount > 0)
                        <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
                    @endif
                </button>
            </li>

            {{-- 好友请求 Tab --}}
            <li class="nav-item" role="presentation">
                <button class="nav-link d-flex align-items-center" id="friend-tab"
                        data-bs-toggle="tab" data-bs-target="#friend" type="button" role="tab">
                    👥 好友请求
                    @if($friendCount > 0)
                        <span class="badge bg-danger ms-2">{{ $friendCount }}</span>
                    @endif
                </button>
            </li>
        </ul>

        {{-- Tab 内容 --}}
        <div class="tab-content mt-3" id="notificationTabsContent">
            {{-- 系统通知 --}}
            <div class="tab-pane fade show active" id="system" role="tabpanel">
                @if(!$notifications->isEmpty())
                    <div class="list-group">
                        @foreach($notifications as $note)
                            <div class="list-group-item d-flex justify-content-between align-items-center
                                        {{ $note->is_read ? '' : 'bg-warning-subtle' }}">
                                <div>
                                    <i class="fas fa-bullhorn me-2 text-primary"></i>
                                    {!! $note->content !!}
                                    <div>
                                        <small class="text-muted">{{ $note->created_at->format('Y-m-d H:i') }}</small>
                                    </div>
                                </div>
                                @if(!$note->is_read)
                                    <span class="badge rounded-pill bg-danger">新</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-secondary">暂无系统通知</div>
                @endif
            </div>

            {{-- 好友请求 --}}
            <div class="tab-pane fade" id="friend" role="tabpanel">
                @if(!$friendRequests->isEmpty())
                    <div class="list-group">
                        @foreach($friendRequests as $request)
                            <div class="list-group-item bg-dark text-white d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-user-plus me-2 text-success"></i>
                                    <strong>{{ $request->sender->name }}</strong> 请求加你为好友
                                    @if($request->message)
                                        <div class="mt-1 text-info">消息: {{ $request->message }}</div>
                                    @endif
                                    <div>
                                        <small class="text-light">{{ $request->created_at->format('Y-m-d H:i') }}</small>
                                    </div>
                                </div>
                                <div>
                                    <form action="{{ route('friends.accept', $request->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success">接受</button>
                                    </form>
                                    <form action="{{ route('friends.reject', $request->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-danger">拒绝</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-secondary">暂无好友请求</div>
                @endif
            </div>
        </div>
    @endif
</div>
</div>

{{-- 星空背景动画 --}}
<script>
    window.addEventListener('beforeunload', function () {
        navigator.sendBeacon("{{ route('notifications.markAllRead') }}",
            new Blob([], { type: 'application/x-www-form-urlencoded' })
        );
    });

    const canvas = document.getElementById("starfield");
    const ctx = canvas.getContext("2d");

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }
    resizeCanvas();
    window.addEventListener("resize", resizeCanvas);

    let stars = [];
    for (let i = 0; i < 150; i++) {
        stars.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            radius: Math.random() * 1.5,
            speed: Math.random() * 0.5 + 0.2
        });
    }

    function drawStars() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = "white";
        stars.forEach(star => {
            ctx.beginPath();
            ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2);
            ctx.fill();

            // 移动星星
            star.y += star.speed;
            if (star.y > canvas.height) {
                star.y = 0;
                star.x = Math.random() * canvas.width;
            }
        });
        requestAnimationFrame(drawStars);
    }
    drawStars();
</script>
@endsection
