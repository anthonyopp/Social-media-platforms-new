@extends('layouts.app')

@section('title', '添加活动')

@section('content')
<div class="col mt-5">
    <h2 class="mb-4 translate-text">添加新活动</h2>

    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- 活动标题 --}}
    <div class="mb-3">
        <label for="title" class="form-label translate-text">活动标题</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>

    {{-- 活动描述 --}}
    <div class="mb-3">
        <label for="description" class="form-label translate-text">活动描述</label>
        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
    </div>

    {{-- 开始 & 结束时间 --}}
    <div class="row mb-3">
        <div class="col">
            <label for="start_time" class="form-label translate-text">开始时间</label>
            <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
        </div>
        <div class="col">
            <label for="end_time" class="form-label translate-text">结束时间</label>
            <input type="datetime-local" class="form-control" id="end_time" name="end_time" required>
        </div>
    </div>

    {{-- 活动日期（单独保存主要日期） --}}
    {{-- <div class="mb-3">
        <label for="event_date" class="form-label translate-text">活动日期</label>
        <input type="date" class="form-control" id="event_date" name="event_date" required>
    </div> --}}

    {{-- 活动地点 --}}
    <div class="mb-3">
        <label for="location" class="form-label translate-text">活动地点</label>
        <input type="text" class="form-control" id="location" name="location" required>
    </div>

    <div class="mb-3">
    <label for="phone" class="form-label translate-text">联系电话</label>
    <input type="text" class="form-control" id="phone" name="phone"
           placeholder="请输入联系电话（如 012-3456789）">
</div>

    {{-- 活动类型 --}}
    <div class="mb-3 select-wrapper">
    <label for="type" class="form-label translate-text">活动类型</label>
    <select class="form-select custom-select-arrow" id="type" name="type" required>
        <option value="" selected disabled hidden>请选择活动类型</option>
        <option value="讲座">讲座</option>
        <option value="比赛">比赛</option>
        <option value="聚会">聚会</option>
        <option value="志愿活动">志愿活动</option>
        <option value="其他">其他</option>
    </select>
</div>


    {{-- 名额限制 --}}
    <div class="mb-3">
        <label for="capacity" class="form-label translate-text">活动名额</label>
        <input type="number" class="form-control" id="capacity" name="capacity" min="1">
    </div>

    {{-- 报名截止时间 --}}
    {{-- <div class="mb-3">
        <label for="registration_deadline" class="form-label translate-text">报名截止时间</label>
        <input type="datetime-local" class="form-control" id="registration_deadline" name="registration_deadline">
    </div> --}}

    {{-- 是否需要报名 --}}
    {{-- <div class="mb-3">
        <label for="requires_registration" class="form-label translate-text">是否需要报名</label>
        <select class="form-control" id="requires_registration" name="requires_registration">
            <option value="1">需要</option>
            <option value="0">不需要</option>
        </select>
    </div> --}}

    {{-- 海报图片 --}}
    <div class="mb-3">
        <label for="cover_image" class="form-label translate-text">活动封面</label>
        <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
    </div>

    {{-- 附件 --}}
    <div class="mb-3">
        <label for="attachment" class="form-label translate-text">活动附件</label>
        <input type="file" class="form-control" id="attachment" name="attachment">
    </div>

    <button type="submit" class="btn btn-primary translate-text">创建活动</button>
    <a href="{{ route('events') }}" class="btn btn-secondary translate-text">返回</a>
</form>
</div>

<script>
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
