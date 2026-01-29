@extends('layouts.app')

@section('title', '设置')

@section('content')


<h2 class="translate-text">Setting</h2>

<!-- 语言切换按钮 -->
<div class="language-switcher d-flex justify-content-between align-items-center p-3 rounded shadow-sm mb-4">
    <span class="switcher-label translate-text">🌐 Language Switch</span>

    <select id="languageSelector" class="form-select language-select">
        <option class="translate-text" value="en">English</option>
        <option class="translate-text" value="zh">Chinese</option>
        <option class="translate-text" value="ms">Malay</option>
    </select>
</div>

<p class="translate-text">欢迎来到设置页面。你可以在这里更改语言。</p>

<canvas id="abyssCanvas"></canvas>

<style>
    .language-switcher {
    background: linear-gradient(to right, #f8f9fa, #e9ecef);
    max-width: 500px;
    border: 1px solid #dee2e6;
    transition: box-shadow 0.3s ease;
}

.language-switcher:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.switcher-label {
    font-weight: 600;
    font-size: 1.1rem;
    background: linear-gradient(45deg, #007bff, #00c6ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-right: 1rem;
}

.language-select {
    width: auto;
    min-width: 150px;
    border-radius: 0.5rem;
    box-shadow: 0 2px 6px rgba(0, 123, 255, 0.15);
    transition: border-color 0.3s ease;
}

.language-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

    /* 深渊背景样式 */
#abyssCanvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1; /* 确保背景层次在最底部 */
    background: radial-gradient(circle at center, rgba(60, 0, 80, 0.8), #000000 80%);
    overflow: hidden; /* 防止画布溢出 */
}
</style>

<script>
    const canvas = document.getElementById("abyssCanvas");
const ctx = canvas.getContext("2d");

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

// 窗口调整时，动态适配画布
window.addEventListener("resize", () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
});

// 星尘碎片类
class Particle {
    constructor(x, y, size, color, speed) {
        this.x = x;
        this.y = y;
        this.size = size;
        this.color = color;
        this.speed = speed;
    }

    draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fillStyle = this.color;
        ctx.fill();
    }

    update() {
        this.y += this.speed;
        this.x += Math.sin(this.y * 0.02) * 2; // 横向缓慢漂移
        if (this.y > canvas.height) {
            this.y = 0; // 循环效果
            this.x = Math.random() * canvas.width;
        }
    }
}

// 创建星尘碎片
const particles = [];
for (let i = 0; i < 200; i++) {
    particles.push(
        new Particle(
            Math.random() * canvas.width,
            Math.random() * canvas.height,
            Math.random() * 2 + 0.5,
            `rgba(${150 + Math.random() * 105}, 0, ${200 + Math.random() * 55}, 0.8)`,
            Math.random() * 2 + 0.5
        )
    );
}

// 能量漩涡与脉冲
let angle = 0;
function drawVortex() {
    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;
    const radius = 120 + Math.sin(angle * 0.05) * 30;

    // 旋转能量漩涡
    ctx.beginPath();
    ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);
    const gradient = ctx.createRadialGradient(
        centerX,
        centerY,
        40,
        centerX,
        centerY,
        radius
    );
    gradient.addColorStop(0, "rgba(220, 20, 220, 0.9)");
    gradient.addColorStop(0.5, "rgba(80, 0, 120, 0.6)");
    gradient.addColorStop(1, "rgba(0, 0, 0, 0)");
    ctx.fillStyle = gradient;
    ctx.fill();

    // 脉冲波动
    if (Math.random() > 0.98) {
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius + 20, 0, Math.PI * 2);
        ctx.strokeStyle = "rgba(255, 0, 255, 0.5)";
        ctx.lineWidth = 2;
        ctx.stroke();
    }

    angle += 0.03;
}

// 空间裂隙
class Rift {
    constructor() {
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * canvas.height;
        this.width = Math.random() * 100 + 20;
        this.alpha = 1;
        this.speed = Math.random() * 0.5 + 0.2;
    }

    draw() {
        ctx.beginPath();
        ctx.moveTo(this.x, this.y);
        ctx.lineTo(this.x + this.width, this.y + this.width / 2);
        ctx.lineTo(this.x, this.y + this.width);
        ctx.closePath();
        ctx.strokeStyle = `rgba(0, 100, 255, ${this.alpha})`;
        ctx.lineWidth = 1.5;
        ctx.stroke();
    }

    update() {
        this.y += this.speed;
        this.alpha -= 0.01;
    }
}

const rifts = [];
setInterval(() => {
    if (rifts.length < 10) {
        rifts.push(new Rift());
    }
}, 2000);

// 星云光晕
let glowAngle = 0;
function drawNebulaGlow() {
    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;

    ctx.save();
    ctx.translate(centerX, centerY);
    ctx.rotate(glowAngle);
    glowAngle += 0.002;

    const colors = ["rgba(50, 0, 150, 0.3)", "rgba(150, 50, 250, 0.3)"];
    colors.forEach((color, i) => {
        ctx.beginPath();
        ctx.arc(0, 0, 200 + i * 30, 0, Math.PI * 2);
        ctx.strokeStyle = color;
        ctx.lineWidth = 0.8;
        ctx.stroke();
    });

    ctx.restore();
}

// 动画循环
function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // 1. 绘制能量漩涡与脉冲
    drawVortex();

    // 2. 绘制星尘
    particles.forEach((particle) => {
        particle.update();
        particle.draw();
    });

    // 3. 绘制空间裂隙
    for (let i = rifts.length - 1; i >= 0; i--) {
        rifts[i].update();
        rifts[i].draw();
        if (rifts[i].alpha <= 0) rifts.splice(i, 1);
    }

    // 4. 绘制星云光晕
    drawNebulaGlow();

    requestAnimationFrame(animate);
}

animate();

</script>
@endsection

@push('scripts')
<script>
    // 默认原文语言
    var DEFAULT_LANG = 'en'; // ✅ 默认英文
    var userLang = "{{ session('locale', 'en') }}"; // 默认英文，如果 Session 有值则用 Session

    // Google 翻译函数
    async function fetchGoogleTranslate(text, from, to) {
        const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=${from}&tl=${to}&dt=t&q=${encodeURIComponent(text)}`;
        const response = await fetch(url);
        if (!response.ok) throw new Error('翻译请求失败');
        const data = await response.json();
        return data[0].map(item => item[0]).join('');
    }

    // 翻译所有 .translate-text 元素
    async function translateAllTexts(targetLang) {
        document.querySelectorAll('.translate-text').forEach(el => {
            el.dataset.original = el.dataset.original || el.textContent.trim();
        });

        const elements = document.querySelectorAll('.translate-text');
        for (let el of elements) {
            try {
                const translated = await fetchGoogleTranslate(el.dataset.original, DEFAULT_LANG, targetLang);
                el.textContent = translated;
            } catch(err) {
                console.error('翻译失败:', err);
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const languageSelector = document.getElementById('languageSelector');

        // 设置下拉菜单选中
        if(languageSelector) languageSelector.value = userLang;

        // 页面加载时如果不是默认语言，执行翻译
        if(userLang !== DEFAULT_LANG) translateAllTexts(userLang);

        // 下拉菜单切换语言
        if(languageSelector) {
            languageSelector.addEventListener('change', async function() {
                const selectedLang = this.value;

                // 前端翻译
                if(selectedLang !== DEFAULT_LANG) {
                    await translateAllTexts(selectedLang);
                } else {
                    document.querySelectorAll('.translate-text').forEach(el => {
                        if(el.dataset.original) el.textContent = el.dataset.original;
                    });
                }

                // 后端更新 Session 并跳转
                window.location.href = `/change-language/${selectedLang}`;
            });
        }
    });
</script>

@endpush
