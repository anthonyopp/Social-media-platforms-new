@extends('layouts.app')

@section('title', '注册')

@section('content')
<div class="container mt-5">
    <h2 class="text-center">用户注册</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">姓名</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">邮箱地址</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">密码</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">确认密码</label>
            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required>
            @error('password_confirmation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">选择身份</label>
            <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>教师</option>
                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>学生</option>
            </select>
            @error('role')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">注册</button>
    </form>

    <div class="mt-3">
        <a href="{{ route('login') }}">已有账号？点击登录</a>
    </div>
</div>

<canvas id="chaosCanvas"></canvas>
<style>
    #chaosCanvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1; /* 背景置底 */
    background: radial-gradient(circle at center, #100015, #000000 90%);
}
</style>

<script>
    const canvas = document.getElementById("chaosCanvas");
const ctx = canvas.getContext("2d");

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

window.addEventListener("resize", () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
});

// 混沌粒子类
class ChaosParticle {
    constructor() {
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * canvas.height;
        this.size = Math.random() * 3 + 1;
        this.angle = Math.random() * Math.PI * 2;
        this.speed = Math.random() * 0.5 + 0.2;
        this.alpha = Math.random() * 0.7 + 0.3;
        this.color = this.randomColor();
    }

    randomColor() {
        const chaosColors = [
            "rgba(255, 215, 0, 0.8)", // 金色 - 法则裂痕
            "rgba(139, 0, 139, 0.8)", // 紫色 - 混沌能量
            "rgba(173, 216, 230, 0.8)" // 淡蓝 - 诞生之力
        ];
        return chaosColors[Math.floor(Math.random() * chaosColors.length)];
    }

    draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fillStyle = this.color;
        ctx.fill();
    }

    update() {
        this.angle += 0.01;
        this.x += Math.cos(this.angle) * this.speed;
        this.y += Math.sin(this.angle) * this.speed;

        // 重新生成粒子
        if (this.x < 0 || this.x > canvas.width || this.y < 0 || this.y > canvas.height) {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.angle = Math.random() * Math.PI * 2;
        }
    }
}

// 初始化混沌粒子
const chaosParticles = [];
for (let i = 0; i < 300; i++) {
    chaosParticles.push(new ChaosParticle());
}

// 混沌漩涡
let pulse = 0;
function drawChaosCore() {
    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;
    const radius = 120 + Math.sin(pulse) * 40;

    ctx.beginPath();
    ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);
    const gradient = ctx.createRadialGradient(centerX, centerY, 10, centerX, centerY, radius);
    gradient.addColorStop(0, "rgba(128, 0, 128, 0.8)");
    gradient.addColorStop(0.5, "rgba(30, 0, 60, 0.6)");
    gradient.addColorStop(1, "rgba(0, 0, 0, 0)");
    ctx.fillStyle = gradient;
    ctx.fill();

    pulse += 0.02;
}

// 动画循环
function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // 1. 绘制混沌漩涡
    drawChaosCore();

    // 2. 更新并绘制粒子
    chaosParticles.forEach((particle) => {
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
