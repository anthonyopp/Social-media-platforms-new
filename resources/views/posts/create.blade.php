@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="translate-text">创建新帖子</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label translate-text">标题</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label translate-text">内容</label>
            <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
        </div>

        <!-- 选择帖子类型（标签） -->
        <div class="mb-3">
            <label class="form-label translate-text">选择帖子类型</label>
            <div class="accordion" id="tagAccordion">
                <!-- 学习交流 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button translate-text" type="button" data-bs-toggle="collapse" data-bs-target="#studyTags">
                            📖 学习交流
                        </button>
                    </h2>
                    <div id="studyTags" class="accordion-collapse collapse" data-bs-parent="#tagAccordion">
                        <div class="accordion-body">
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="课程交流"> 课程交流
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="作业求助"> 作业求助
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="考试经验"> 考试经验
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="考研/留学"> 考研/留学
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="实习与就业"> 实习与就业
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 校园生活 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button translate-text" type="button" data-bs-toggle="collapse" data-bs-target="#campusTags">
                            🏫 校园生活
                        </button>
                    </h2>
                    <div id="campusTags" class="accordion-collapse collapse" data-bs-parent="#tagAccordion">
                        <div class="accordion-body">
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="校园新闻"> 校园新闻
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="活动通知"> 活动通知
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="社团招新"> 社团招新
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="宿舍生活"> 宿舍生活
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="失物招领"> 失物招领
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 休闲娱乐 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button translate-text" type="button" data-bs-toggle="collapse" data-bs-target="#leisureTags">
                            🎮 休闲娱乐
                        </button>
                    </h2>
                    <div id="leisureTags" class="accordion-collapse collapse" data-bs-parent="#tagAccordion">
                        <div class="accordion-body">
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="动漫游戏"> 动漫游戏
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="影视剧集"> 影视剧集
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="小说分享"> 小说分享
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="校园八卦"> 校园八卦
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="运动健身"> 运动健身
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 技术交流 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button translate-text" type="button" data-bs-toggle="collapse" data-bs-target="#techTags">
                            💻 技术交流
                        </button>
                    </h2>
                    <div id="techTags" class="accordion-collapse collapse" data-bs-parent="#tagAccordion">
                        <div class="accordion-body">
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="编程交流"> 编程交流
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="硬件DIY"> 硬件DIY
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="黑客技术"> 黑客技术
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="开源项目"> 开源项目
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="AI与机器学习"> AI与机器学习
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 交易专区 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button translate-text" type="button" data-bs-toggle="collapse" data-bs-target="#tradeTags">
                            🛒 交易专区
                        </button>
                    </h2>
                    <div id="tradeTags" class="accordion-collapse collapse" data-bs-parent="#tagAccordion">
                        <div class="accordion-body">
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="二手书籍"> 二手书籍
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="电子产品"> 电子产品
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="服饰美妆"> 服饰美妆
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="拼单团购"> 拼单团购
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="兼职与副业"> 兼职与副业
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 生活交流 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button translate-text" type="button" data-bs-toggle="collapse" data-bs-target="#lifeTags">
                            💬 生活交流
                        </button>
                    </h2>
                    <div id="lifeTags" class="accordion-collapse collapse" data-bs-parent="#tagAccordion">
                        <div class="accordion-body">
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="树洞倾诉"> 树洞倾诉
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="情感咨询"> 情感咨询
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="日常吐槽"> 日常吐槽
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="正能量分享"> 正能量分享
                            </div>
                            <div class="form-check translate-text">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="人生规划"> 人生规划
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="translate-text" id="error-message" style="color: red; display: none;">请至少选择 3 个标签！</p>
        </div>

        <!-- 图片上传（支持多张） -->
        <div class="mb-3">
            <label class="form-label translate-text">上传图片 （最多可上传四张）</label>
            <button type="button" class="btn btn-primary translate-text" onclick="document.getElementById('images').click();">选择图片</button>
            <input type="file" id="images" name="images[]" accept="image/*" style="display: none;" multiple onchange="previewImages(event)">

            <!-- 图片预览区域 -->
            <div id="imagePreviewContainer" class="preview-grid" style="display: none;"></div>

            <!-- 错误提示 -->
            <div id="imageError" class="text-danger mt-2 translate-text" style="display: none;">最多只能上传4张图片！</div>
        </div>

        <!-- 视频上传 -->
        <div class="mb-3">
            <label class="form-label translate-text">上传视频（可选）</label>
            <button type="button" class="btn btn-primary mb-3 translate-text" onclick="document.getElementById('video').click();">选择视频</button>
            <input type="file" id="video" name="video" accept="video/*" style="display: none;" onchange="previewVideo(event)">
            <div id="videoPreviewContainer" style="display: none;">
                <video id="videoPreview" class="preview-media" controls></video>
            </div>
        </div>

        <button type="submit" class="btn btn-primary translate-text" onclick="return validateTags()">发布帖子</button>
    </form>
</div>

<style>
    /* 预览网格布局 */
    .preview-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 12px;
    }

    /* 预览图片容器 */
    .preview-container {
        width: 200px;
        height: 200px;
        border-radius: 8px;
        overflow: hidden;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        cursor: pointer;
        transition: transform 0.2s ease-in-out;
    }

    /* 预览图片 */
    .preview-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* 放大查看的图片样式 */
    .fullscreen-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 999;
        display: none;
        align-items: center;
        justify-content: center;
    }

    .fullscreen-img {
        max-width: 90vw;  /* 最大宽度 90% 视口 */
        max-height: 90vh; /* 最大高度 90% 视口 */
        object-fit: contain; /* 保持图片比例 */
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
        transition: opacity 0.3s ease-in-out;
    }

    /* 视频预览 */
    #videoPreviewContainer {
        width: 400px;
        height: 250px;
        border-radius: 8px;
        overflow: hidden;
        background-color: #f8f9fa;
        margin-top: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* 预览视频 */
    .preview-media {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<!-- 点击放大会显示的全屏背景 -->
<div id="fullscreenOverlay" class="fullscreen-overlay" onclick="hideFullscreen()">
    <img id="fullscreenImg" class="fullscreen-img" src="" alt="Fullscreen Image">
</div>

<script>
    function validateTags() {
        const checkboxes = document.querySelectorAll('input[name="tags[]"]:checked');
        const errorMessage = document.getElementById('error-message');

        if (checkboxes.length < 3) {
            errorMessage.style.display = 'block'; // 显示错误提示
            return false; // 阻止表单提交
        }

        errorMessage.style.display = 'none'; // 隐藏错误提示
        return true; // 允许提交
    }

    function previewImages(event) {
        const input = event.target;
        const previewContainer = document.getElementById("imagePreviewContainer");
        const errorMsg = document.getElementById("imageError");

        // 清空已有的预览和错误提示
        previewContainer.innerHTML = "";
        errorMsg.style.display = "none";

        // 如果没有文件，隐藏预览区
        if (input.files.length === 0) {
            previewContainer.style.display = "none";
            return;
        }

        // 限制上传图片数量
        if (input.files.length > 4) {
            errorMsg.style.display = "block";
            input.value = ""; // 清空文件
            return;
        }

        // 有图片时显示预览区
        previewContainer.style.display = "flex";

        // 遍历并生成图片预览，最多显示4张
        for (let i = 0; i < Math.min(input.files.length, 4); i++) {
            const file = input.files[i];
            const reader = new FileReader();

            // 创建预览容器和图片元素
            const imgContainer = document.createElement("div");
            imgContainer.classList.add("preview-container");

            const img = document.createElement("img");
            img.classList.add("preview-img");

            // 读取图片数据并显示
            reader.onload = function(e) {
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);

            // 点击图片放大功能
            img.onclick = function() {
                showFullscreenImage(img.src);
            };

            // 添加图片到预览区
            imgContainer.appendChild(img);
            previewContainer.appendChild(imgContainer);
        }
    }

    // 显示全屏图片（你需要实现这个函数）
    function showFullscreenImage(src) {
        alert("点击放大功能，图片地址：" + src);
    }

    function previewVideo(event) {
        var input = event.target;
        var previewContainer = document.getElementById("videoPreviewContainer");
        var preview = document.getElementById("videoPreview");

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = "flex";
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // 显示放大图片
    function showFullscreenImage(src) {
        let overlay = document.getElementById("fullscreenOverlay");
        let fullscreenImg = document.getElementById("fullscreenImg");

        fullscreenImg.src = src;
        overlay.style.display = "flex";
    }

    // 关闭放大图片
    function hideFullscreen() {
        document.getElementById("fullscreenOverlay").style.display = "none";
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
