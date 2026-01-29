@extends('layouts.app')

@section('title', '登录')

@section('content')
<div class="container mt-5"> <!--login-container来设计-->
    <h2 class="text-center">用户登录</h2>

   <form id="loginForm" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">邮箱地址</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">密码</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">登录</button>
    </form>

    <div class="mt-3">
        <a href="{{ route('register') }}">还没有账号？点击注册</a>
    </div>

    @auth
    <!-- 登出按钮（触发模态框） -->
    <button type="button" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#logoutModal">
        登出
    </button>


    <!-- 确认登出模态框 -->
    {{-- data-bs-backdrop="static" → 禁止点击背景关闭
    data-bs-keyboard="false" → 禁止按 ESC 键关闭 --}}
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <h5 class="modal-title w-100" id="logoutModalLabel" style="color: #000000">⚠️ 确认登出</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="关闭"></button>
                </div>
                <div class="modal-body">
                    <p style="color: #000000">您确定要退出当前账户吗？</p>
                    <p class="text-muted small">登出后需要重新登录才能继续使用系统。</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>

                    <!-- 真正的登出表单 -->
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">确认登出</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endauth
    <!-- 管理员入口 -->
<div class="mt-5">
    <div class="card border-danger shadow-sm">
        <div class="card-body text-center">
            <h5 class="card-title text-danger fw-bold">
                <i class="bi bi-shield-lock-fill"></i> 管理员入口
            </h5>
            <p class="text-muted">仅限校园论坛管理员使用</p>
            <a href="{{ route('admin.login') }}" class="btn btn-danger w-100">
                <i class="bi bi-key-fill"></i> 进入管理员登录
            </a>
        </div>
    </div>
</div>


</div>
{{-- 错误提示模态框 --}}
<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">登录失败</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-dark">
         <p id="errorMessage"></p> <!-- ⚡这里会动态写入 -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">关闭</button>
      </div>
    </div>
  </div>
</div>

<canvas id="voidCanvas"></canvas>

<style>
    /* 登录页面 - 虚空背景 */
body, html {
    margin: 0;
    padding: 0;
    overflow: hidden; /* 禁止滚动，固定背景 */
    height: 100%;
}

    /* 登录表单样式 */
.login-container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: #d4aaff;
    font-family: "Arial", sans-serif;
    animation: glow 3s ease-in-out infinite alternate; /* 呼吸效果 */
}

.login-container h1 {
    font-size: 3rem;
    text-shadow: 0 0 20px rgba(180, 100, 255, 0.8);
}

/* label {
    display: block;
    margin: 15px 0 5px;
}

input {
    padding: 10px;
    width: 80%;
    max-width: 400px;
    background: rgba(20, 10, 40, 0.7);
    border: none;
    border-bottom: 2px solid #7b4bc4;
    color: #fff;
    outline: none;
    border-radius: 5px;
}

button {
    margin-top: 20px;
    padding: 10px 20px;
    background: linear-gradient(135deg, #7b4bc4, #d4aaff);
    border: none;
    color: #fff;
    font-size: 1.2rem;
    border-radius: 8px;
    cursor: pointer;
    box-shadow: 0 0 15px rgba(130, 80, 200, 0.8);
    transition: transform 0.3s ease;
}

button:hover {
    transform: scale(1.1);
} */

/* 呼吸动画 */
/* @keyframes glow {
    from {
        text-shadow: 0 0 20px rgba(180, 100, 255, 0.8);
    }
    to {
        text-shadow: 0 0 35px rgba(220, 150, 255, 1);
    }
} */

    #voidCanvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1; /* 将背景置于底层 */
    background: radial-gradient(circle at center, rgba(15, 5, 25, 1), #000000 90%);
}

</style>

<script>
    document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch("{{ route('login') }}", {
    method: "POST",
    headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
    },
    body: formData
})
.then(async res => {
    let data;
    try {
        data = await res.json();
    } catch (e) {
        console.error("返回不是 JSON:", await res.text());
        return;
    }

    console.log("后端返回:", data);

    if (data.status === "success") {
        // ✅ 登录成功
        window.location.href = data.redirect;
    } else if (data.status === "error") {
        // ✅ 登录失败（邮箱或密码错误等）
        document.getElementById("errorMessage").innerText = data.message || "登录失败，请重试";
        let errorModal = new bootstrap.Modal(document.getElementById("errorModal"));
        errorModal.show();
    } else {
        // ✅ 兜底（避免后端没返回 status 或写错）
        document.getElementById("errorMessage").innerText = "发生未知错误，请稍后再试";
        let errorModal = new bootstrap.Modal(document.getElementById("errorModal"));
        errorModal.show();
    }
})
.catch(err => {
    console.error("请求异常:", err);
    document.getElementById("errorMessage").innerText = "请求异常，请检查网络连接";
    let errorModal = new bootstrap.Modal(document.getElementById("errorModal"));
    errorModal.show();
});

});



const canvas = document.getElementById("voidCanvas");
const ctx = canvas.getContext("2d");

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

window.addEventListener("resize", () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
});

// 虚空粒子类
class VoidParticle {
    constructor() {
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * canvas.height;
        this.size = Math.random() * 3 + 1;
        this.speed = Math.random() * 2 + 0.2;
        this.angle = Math.random() * Math.PI * 2;
        this.alpha = Math.random() * 0.5 + 0.3;
        this.color = `rgba(${120 + Math.random() * 80}, 50, 200, ${this.alpha})`;
    }

    draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fillStyle = this.color;
        ctx.fill();
    }

    update() {
        this.x += Math.cos(this.angle) * this.speed;
        this.y += Math.sin(this.angle) * this.speed;

        // 让粒子围绕中心旋转
        const dx = canvas.width / 2 - this.x;
        const dy = canvas.height / 2 - this.y;
        const distance = Math.sqrt(dx * dx + dy * dy);
        this.angle += 0.02;

        // 吸入效果
        this.x += dx * 0.002;
        this.y += dy * 0.002;

        // 重新生成粒子
        if (distance < 20 || this.x < 0 || this.x > canvas.width || this.y < 0 || this.y > canvas.height) {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.angle = Math.random() * Math.PI * 2;
        }
    }
}

// 初始化虚空粒子
const particles = [];
for (let i = 0; i < 300; i++) {
    particles.push(new VoidParticle());
}

// 虚空旋涡
let pulse = 0;
function drawVoidCore() {
    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;
    const radius = 100 + Math.sin(pulse) * 30;

    ctx.beginPath();
    ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);
    const gradient = ctx.createRadialGradient(centerX, centerY, 10, centerX, centerY, radius);
    gradient.addColorStop(0, "rgba(100, 0, 150, 0.8)");
    gradient.addColorStop(0.5, "rgba(30, 0, 60, 0.6)");
    gradient.addColorStop(1, "rgba(0, 0, 0, 0)");
    ctx.fillStyle = gradient;
    ctx.fill();

    pulse += 0.02;
}

// 动画循环
function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // 1. 绘制虚空旋涡
    drawVoidCore();

    // 2. 更新并绘制粒子
    particles.forEach((particle) => {
        particle.update();
        particle.draw();
    });

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
