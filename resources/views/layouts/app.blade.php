<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '主页') - 你的网站</title>

    <!-- Bootstrap 样式 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- 自定义 CSS -->
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding-bottom: 50px; /* 预留空间防止内容被 footer 遮住 */
        }
        .navbar {
            background-color: #1f1f1f;
        }
        .container {
            margin-top: 20px;
        }
        .bell-wrapper {
    position: relative;
    display: inline-block; /* 让铃铛和红点在一个相对容器 */
    font-size: 22px;       /* 控制铃铛大小 */
}

.notification-dot {
    width: 10px;
    height: 10px;
    background-color: red;
    border-radius: 50%;
    position: absolute;
    top: 3px;   /* 负值能让红点压住铃铛 */
    right: 2px; /* 越小越往里覆盖 */
    box-shadow: 0 0 2px rgba(0,0,0,0.3);
}

@keyframes pulse {
    0% {
        transform: scale(0.5);
        opacity: 0.6;
    }
    50% {
        transform: scale(1.3);
        opacity: 1;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #1f1f1f;
            color: #aaa;
            padding: 10px 0;
            text-align: center;
        }
    </style>

    @stack('styles') <!-- 页面可额外添加 CSS -->
</head>
<body>

    <!-- 导航栏 -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="/images/123.png" alt="Logo"
            style="height:50px; margin-right:10px; filter: drop-shadow(0 0 6px #4fd1ff);">

        </a>

        <a class="nav-link translate-text" href="{{ route('home') }}">
            Home
        </a>

            {{-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button> --}}
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link translate-text" href="{{ route('login') }}">
                            📅 @auth Logout @else Login @endauth
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link translate-text" href="{{ route('events') }}">🎉 activies</a></li>
                    <li class="nav-item"><a class="nav-link translate-text" href="{{ route('chat') }}">💬 Chat</a></li>
                    <li class="nav-item"><a class="nav-link translate-text" href="{{ route('profile') }}">🙍 profile</a></li>
                    <li class="nav-item"><a class="nav-link translate-text" href="{{ route('settings') }}">⚙️ Setting</a></li>
                    {{-- 通知按钮，放到最后, 需要等10秒才会有红点 --}}
                    <li class="nav-item ms-3 position-relative">
    <a class="nav-link position-relative" href="{{ route('messages') }}">
        <span class="bell-wrapper">
            🔔
            <span id="notification-dot" class="notification-dot d-none"></span>
        </span>
    </a>
</li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- 主体内容 -->
    <div class="container">
        @yield('content')
    </div>

    <!-- 页脚 -->
    {{-- <footer>
        <p>&copy; {{ date('Y') }} 你的网站. All rights reserved.</p>
    </footer> --}}

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @stack('scripts') <!-- 页面可额外添加 JS -->
</body>
</html>
<script>
    var DEFAULT_LANG = 'en'; // 原始语言为中文

    function checkNotifications() {
        $.get("{{ route('notifications.unread-check') }}", function (data) {
            if (data.hasUnread) {
                $("#notification-dot").removeClass("d-none"); // 显示红点
            } else {
                $("#notification-dot").addClass("d-none");   // 隐藏红点
            }
        });
    }

    // 页面加载时检查一次
    checkNotifications();

    // 每1秒检查一次
    setInterval(checkNotifications, 1000);

    async function fetchGoogleTranslate(text, from, to) {
        const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=${from}&tl=${to}&dt=t&q=${encodeURIComponent(text)}`;
        const response = await fetch(url);
        if (!response.ok) throw new Error('翻译请求失败');
        const data = await response.json();
        return data[0].map(item => item[0]).join('');
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
        const userLang = "{{ session('locale', 'zh') }}"; // 默认中文
        if(languageSelector) {
    languageSelector.value = userLang;

    languageSelector.addEventListener('change', async function () {
        const selectedLang = this.value;

        if(selectedLang !== 'zh') {
            await translateAllTexts(selectedLang);
        } else {
            document.querySelectorAll('.translate-text').forEach(el => {
                if(el.dataset.original) el.textContent = el.dataset.original;
            });
        }

        window.location.href = `/change-language/${selectedLang}`;
    });
}
    });
</script>
