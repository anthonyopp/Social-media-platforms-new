@extends('layouts.app')

@section('title', '聊天')

@section('content')
<div class="container-fluid">
<div class="chat-page mx-auto d-flex" style="max-width:1200px; height:80vh;">    {{-- 左侧好友列表 --}}
    <div class="chat-sidebar" id="chatSidebar">
      <div class="d-flex align-items-center mb-2">
        <h5 class="mb-0 me-auto">好友</h5>
        <button class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#addFriendModal">
          + 添加
        </button>
      </div>

      <div class="friend-search">
        <input id="friendSearch" class="form-control form-control-sm" placeholder="搜索好友或账号">
      </div>

      <div id="friendsList" class="list-group" style="overflow:auto; max-height:58vh;">
        {{-- Blade: 循环好友（后端传 $friends） --}}
       @forelse($friends ?? [] as $friend)
    <div class="list-group-item friend-item d-flex align-items-center mb-2"
     data-id="{{ $friend->user_id }}"   {{-- ✅ 用 user_id --}}
     data-name="{{ $friend->name }}"
     data-avatar="{{ $friend->profile && $friend->profile->profile_picture && $friend->profile->profile_picture !== 'defaultaaa.webp'
                      ? asset('storage/images/avatar/' . $friend->profile->profile_picture)
                      : asset('images/defaultaaa.webp') }}">


        {{-- 头像 --}}
        <img
            src="{{ $friend->profile && $friend->profile->profile_picture && $friend->profile->profile_picture !== 'defaultaaa.webp'
                ? asset('storage/images/avatar/' . $friend->profile->profile_picture)
                : asset('images/defaultaaa.webp') }}"
            onerror="this.onerror=null; this.src='{{ asset('images/defaultaaa.webp') }}';"
            style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;"
            class="friend-avatar me-2"
            alt="avatar"
        />

        {{-- 名字 + 状态 + 删除按钮 --}}
        <div class="flex-grow-1 d-flex align-items-center justify-content-between">

            {{-- 左侧: 名字 + 上次在线 + 状态 --}}
            <div class="me-2" style="min-width: 0;">
                <div class="d-flex align-items-center">
                    <strong class="me-2" style="font-size:.98rem;">{{ $friend->name }}</strong>
                    <small class="text-white-50 ms-auto">{{ $friend->last_seen ? $friend->last_seen->diffForHumans() : '' }}</small>
                </div>
                <div class="text-truncate" style="max-width: 200px;">
                    <span class="status-dot {{ $friend->is_online ? 'status-online' : 'status-offline' }}"></span>
                    <small class="{{ isset($friend->is_online)
                        ? ($friend->is_online ? 'text-info' : 'text-muted')
                        : 'text-muted' }}">
                        {{ isset($friend->is_online)
                            ? ($friend->is_online ? '在线' : '离线')
                            : '离线' }}
                    </small>
                </div>
            </div>

            {{-- 右侧: 删除按钮 --}}
            <form action="{{ route('friends.destroy', $friend->user_id) }}" method="POST" onsubmit="return confirm('确定要删除好友吗？');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">删除</button>
            </form>

        </div>
    </div>
@empty
    <div class="text-center text-white-50 p-3">
        还没有好友，点击 “+ 添加” 去添加第一个好友
    </div>
@endforelse


      </div>
    </div>

    {{-- 右侧聊天主区 --}}
  <div class="chat-main d-flex flex-column flex-grow-1" style="min-width:0; min-height:0;">
  {{-- 头部 --}}
  <div class="chat-header d-flex align-items-center justify-content-between px-2 py-2 border-bottom">

    {{-- 左侧：头像 + 名字 + 状态 --}}
    <div class="d-flex align-items-center">
      {{-- 默认头像，点击后替换 --}}
      <img
          id="chatHeaderAvatar"
          src="{{ asset('images/defaultaaa.webp') }}"
          onerror="this.onerror=null; this.src='{{ asset('images/defaultaaa.webp') }}';"
          style="width:48px; height:48px; border-radius:50%; object-fit:cover; border:2px solid #ddd;"
          class="me-2"
          alt="avatar"
      />

      <div class="d-flex flex-column">
        {{-- 好友名字 --}}
        <div id="chatHeaderName">
          <strong>{{ $currentFriend->name ?? '请选择一个好友' }}</strong>
        </div>
        {{-- 在线状态 --}}
        <div id="chatHeaderStatus" class="text-white-50">
    @if(isset($currentFriend))
        {{ $currentFriend->is_online ? '在线' : '离线' }}
    @else
        未选择好友
    @endif
</div>

      </div>
    </div>

    {{-- 右侧：按钮 --}}
    {{-- <div class="ms-auto">
      <button id="toggleSidebarBtn" class="btn btn-sm btn-outline-light d-md-none">好友</button>
    </div> --}}
  </div>

  {{-- 消息区域 --}}
    <div id="messages" class="messages flex-grow-1 p-2" style="overflow-y:auto; min-height:0; scrollbar-width:none; -ms-overflow-style:none;">
    @if(isset($messages) && count($messages))
    @foreach($messages as $m)
  <div class="msg {{ $m->from_user_id == auth()->id() ? 'outgoing' : 'incoming' }}" data-id="{{ $m->id }}">
    <div style="font-size:.92rem;">{!! nl2br(e($m->content)) !!}</div>
    <div class="text-end text-white-50 d-flex justify-content-between align-items-center" style="font-size:.72rem; margin-top:6px;">
      <span>{{ $m->created_at->format('Y-m-d H:i') }}</span>

      @if($m->from_user_id == auth()->id())
        <button class="btn btn-sm btn-outline-danger btn-delete-msg" data-id="{{ $m->id }}">删除</button>
      @endif
    </div>
  </div>
@endforeach
      {{-- @foreach($messages as $m)
        <div class="msg {{ $m->from_user_id == auth()->id() ? 'outgoing' : 'incoming' }}" data-id="{{ $m->id }}">
          <div style="font-size:.92rem;">{!! nl2br(e($m->content)) !!}</div>
          <div class="text-end text-white-50" style="font-size:.72rem; margin-top:6px;">
            {{ $m->created_at->format('Y-m-d H:i') }}
          </div>
        </div>
      @endforeach --}}
    @else
      <div class="text-center text-white-50 p-3">尚无消息 — 选一个好友开始聊天吧</div>
    @endif
  </div>

  {{-- 输入区域 --}}
  <div class="chat-input border-top p-2">
    <form id="sendMessageForm" class="w-100 d-flex">
      @csrf
      <input type="hidden" name="to_user_id" id="toUserId" value="{{ $currentFriend->id ?? '' }}">
      <textarea id="messageText" name="content" class="form-control textarea-resize" rows="2" placeholder="输入消息..." required></textarea>
      <button type="submit" class="btn btn-primary ms-2">发送</button>
    </form>
  </div>
</div>

  </div>
</div>

<!-- 添加好友 Modal -->
<div class="modal fade" id="addFriendModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="addFriendForm" class="modal-content star-modal" data-url="{{ route('friends.add') }}">
      @csrf
      <div class="modal-header border-0">
        <h5 class="modal-title text-light">添加好友</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="关闭"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label for="friendIdentifier" class="form-label text-light">好友账号或邮箱</label>
          <input id="friendIdentifier" name="identifier" class="form-control star-input" placeholder="输入用户名或邮箱" required>
          <div><br></div>
          {{-- <div class="form-text text-green">输入对方用户名或用户邮箱即可发送好友请求。</div> --}}
        </div>
        <div class="mb-2">
          <label for="friendNote" class="form-label text-light">消息（可选）</label>
          <input id="friendNote" name="message" class="form-control star-input" placeholder="你好，我们认识吗？">
        </div>
      </div>

      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-light star-btn" data-bs-dismiss="modal">取消</button>
        <button type="submit" class="btn btn-primary star-btn">发送请求</button>
      </div>
    </form>
  </div>
</div>

<!-- From Uiverse.io by neerajbaniwal -->
{{-- <a href="#" class="btn-shine">Get early access</a> --}}

<canvas id="chatBackgroundCanvas"></canvas>

<style>
    /* Modal 背景渐变+星点 */
.star-modal {
    background: radial-gradient(circle at bottom, #0b0c2a, #1b1d3a);
    border-radius: 1rem;
    overflow: hidden;
    position: relative;
}

.star-modal::before {
    content: "";
    position: absolute;
    inset: 0;
    /* background: transparent url('https://i.ibb.co/7vYbR8F/starfield.png') repeat; */
    opacity: 0.3;
    pointer-events: none;
    z-index: 0;
}

/* 输入框样式 */
.star-input {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.3);
    color: #fff;
}
.star-input::placeholder {
    color: rgba(255,255,255,0.5);
}

/* 按钮风格 */
.star-btn {
    background: linear-gradient(135deg, #6b5bff, #ff5fa0);
    border: none;
    color: #fff;
    transition: 0.3s;
}
.star-btn:hover {
    background: linear-gradient(135deg, #ff5fa0, #6b5bff);
    transform: scale(1.05);
}

/* 标题文字 */
.star-modal .modal-title {
    z-index: 1;
}

/* Form Text */
.star-modal .form-text {
    z-index: 1;
}
    /* 半透明 + 毛玻璃风格整体 */
.chat-page {
  min-height: 80vh;
  padding: 2rem 1rem;
  background: linear-gradient(135deg, rgba(255,255,255,0.03), rgba(0,0,0,0.03));
  display: flex;
  align-items: stretch;
  gap: 1.25rem;
}

/* 左侧好友列表 */
.chat-sidebar {
  width: 320px;
  backdrop-filter: blur(8px);
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.06);
  border-radius: 12px;
  padding: 1rem;
  display: flex;
  flex-direction: column;
}

/* 右侧聊天主区 */
.chat-main {
  flex: 1;
  backdrop-filter: blur(10px);
  background: rgba(255,255,255,0.03);
  border: 1px solid rgba(255,255,255,0.05);
  border-radius: 12px;
  padding: 0;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

/* 搜索 */
.friend-search {
  margin-bottom: 0.75rem;
}

/* 好友项 */
.friend-item {
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 8px;
  transition: background .12s;
}
.friend-item:hover {
    background: rgba(255, 255, 255, 0.03);
    color: #ffffff; /* 父元素文字 */
}

.friend-item:hover small {
    color: #ffffff !important; /* 强制覆盖 text-muted / text-info */
}



/* 头像 */
.friend-avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid rgba(255,255,255,0.06);
}

/* 在线状态点 */
.status-dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  display:inline-block;
  margin-right: .4rem;
  vertical-align: middle;
}
.status-online { background: #4caf50; box-shadow: 0 0 8px rgba(76,175,80,0.25); }
.status-offline { background: #bdbdbd; }

/* 聊天头部 */
.chat-header {
  padding: 0.8rem 1rem;
  display:flex;
  gap: .8rem;
  align-items:center;
  border-bottom: 1px solid rgba(255,255,255,0.02);
}

/* 消息窗体 */
.messages {
  padding: 1rem;
  overflow-y: auto;
  flex: 1;
  background:
    linear-gradient(180deg, rgba(255,255,255,0.00), rgba(255,255,255,0.00));
}
#messages {
  flex: 1;
  min-height: 0;
  overflow-y: auto;
}

/* 单条消息气泡 */
.msg {
  max-width: 72%;
  padding: .55rem .75rem;
  border-radius: 14px;
  margin-bottom: .6rem;
  line-height: 1.25;
  word-break: break-word;
  box-shadow: 0 1px 0 rgba(0,0,0,0.02);
}
.msg.incoming { background: rgba(255,255,255,0.04); border-top-left-radius: 4px; }
.msg.outgoing { background: rgba(35, 115, 255, 0.12); color: #eaf2ff; margin-left: auto; border-top-right-radius: 4px; }

/* 输入区 */
.chat-input {
  padding: .75rem;
  border-top: 1px solid rgba(255,255,255,0.02);
  display:flex;
  gap:.5rem;
  align-items:center;
  background: rgba(255,255,255,0.01);
}
.textarea-resize {
  resize: none;
}

/* 小屏自适应 */
@media (max-width: 900px) {
  .chat-sidebar { width: 100%; display: none; } /* 默认隐藏侧栏，小屏需要提供按钮显示 */
  .chat-page { flex-direction: column; }
  .chat-main { border-radius: 10px; }
}
    /* From Uiverse.io by neerajbaniwal */
.btn-shine {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  padding: 12px 48px;
  color: #fff;
  background: linear-gradient(to right, #9f9f9f 0, #fff 10%, #868686 20%);
  background-position: 0;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: shine 3s infinite linear;
  animation-fill-mode: forwards;
  -webkit-text-size-adjust: none;
  font-weight: 600;
  font-size: 16px;
  text-decoration: none;
  white-space: nowrap;
  font-family: "Poppins", sans-serif;
}
@-moz-keyframes shine {
  0% {
    background-position: 0;
  }
  60% {
    background-position: 180px;
  }
  100% {
    background-position: 180px;
  }
}
@-webkit-keyframes shine {
  0% {
    background-position: 0;
  }
  60% {
    background-position: 180px;
  }
  100% {
    background-position: 180px;
  }
}
@-o-keyframes shine {
  0% {
    background-position: 0;
  }
  60% {
    background-position: 180px;
  }
  100% {
    background-position: 180px;
  }
}
@keyframes shine {
  0% {
    background-position: 0;
  }
  60% {
    background-position: 180px;
  }
  100% {
    background-position: 180px;
  }
}

    /* Canvas 背景 */
#chatBackgroundCanvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1; /* 确保背景在内容下方 */
    background: radial-gradient(ellipse at bottom, #0a0f29, #000); /* 星空背景色 */
}
</style>

<script>
    /*
  前端交互脚本
  - 点击好友：切换当前聊天（会尝试调用后端路由 /chat/{id} 获取消息）
  - 发送消息：调用 /chat/send
  - 添加好友：调用 /friends/add
  这些路由只是示范，请根据你项目路由替换：
    - GET  /chat/{id}           -> 返回 { friend: {...}, messages: [...] }
    - POST /chat/send           -> body: { to_user_id, content }
    - POST /friends/add         -> body: { identifier, message }
*/

document.addEventListener('DOMContentLoaded', function () {
  const messagesEl = document.getElementById('messages');
  const chatHeaderName = document.getElementById('chatHeaderName');
  const chatHeaderStatus = document.getElementById('chatHeaderStatus');
  const chatHeaderAvatar = document.getElementById('chatHeaderAvatar');
  const toUserIdInput = document.getElementById('toUserId');
  const sendForm = document.getElementById('sendMessageForm');
  const messageText = document.getElementById('messageText');
  const friendSearch = document.getElementById('friendSearch');
  const friendsList = document.getElementById('friendsList');
  const addFriendForm = document.getElementById('addFriendForm');
  const toggleSidebarBtn = document.getElementById('toggleSidebarBtn');
  const chatSidebar = document.getElementById('chatSidebar');

    const defaultAvatar = "{{ asset('images/defaultaaa.webp') }}";

  // 小屏幕切换侧栏
  toggleSidebarBtn && toggleSidebarBtn.addEventListener('click', () => {
    if (chatSidebar.style.display === 'block') {
      chatSidebar.style.display = 'none';
    } else {
      chatSidebar.style.display = 'block';
    }
  });

  // 好友点击事件（委托6）
    // 在 Blade 模板里注入当前用户 id
const authUserId = {{ auth()->id() }};


function escapeHtml(text) {
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;',
  };
  return text.replace(/[&<>"']/g, m => map[m]);
}

friendsList && friendsList.addEventListener('click', function (e) {
  const item = e.target.closest('.friend-item');
  if (!item) return;

  const friendId = item.getAttribute('data-id');
  const friendName = item.getAttribute('data-name') || '好友';
  const friendAvatar = item.dataset.avatar || defaultAvatar;

  if (!friendId) {
    console.warn("缺少 friendId，无法加载聊天记录");
    return;
  }

  // 更新 header
  if (chatHeaderName) chatHeaderName.innerHTML = `<strong>${friendName}</strong>`;
  if (toUserIdInput) toUserIdInput.value = friendId;

  if (chatHeaderAvatar) {
    chatHeaderAvatar.src = friendAvatar || defaultAvatar;
    chatHeaderAvatar.onerror = () => {
      chatHeaderAvatar.onerror = null;
      chatHeaderAvatar.src = defaultAvatar;
    };
  }

  // 请求后端获取消息
  fetch(`/chat/${friendId}`, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
    .then(r => {
      if (!r.ok) throw new Error('无法加载聊天记录');
      return r.json();
    })
    .then(data => {
      if (chatHeaderStatus) {
        chatHeaderStatus.textContent = data.friend?.is_online ? '在线' : '离线';
      }

      if (messagesEl) {
        messagesEl.innerHTML = '';
        if (Array.isArray(data.messages) && data.messages.length > 0) {
          data.messages.forEach(msg => {
            const div = document.createElement('div');
            div.className = 'msg ' + (msg.from_user_id === authUserId ? 'outgoing' : 'incoming');
            div.setAttribute('data-id', msg.id);
            div.innerHTML = `
              <div class="d-flex align-items-start justify-content-between">
    <div style="font-size:.92rem; word-break:break-word;">
      ${escapeHtml(msg.content).replace(/\n/g,'<br>')}
    </div>

    <!-- 小的更多按钮 -->
    <div class="dropdown ms-2">
      <button class="btn btn-sm btn-link text-white-50 p-0" type="button"
              data-bs-toggle="dropdown" aria-expanded="false" style="font-size:1.2rem;">
        ⋮
      </button>
      <ul class="dropdown-menu dropdown-menu-end">
        ${msg.from_user_id === authUserId
            ? `<li><a href="#" class="dropdown-item btn-delete-msg" data-id="${msg.id}">删除</a></li>`
            : ''}
        <li><a href="#" class="dropdown-item btn-copy-msg" data-id="${msg.id}">复制</a></li>
      </ul>
    </div>
  </div>

  <div class="text-end text-white-50" style="font-size:.72rem; margin-top:6px;">
    ${msg.created_at}
  </div>
            `;
            messagesEl.appendChild(div);
          });
          scrollToBottom(messagesEl);
        } else {
          messagesEl.innerHTML = '<div class="text-center text-white-50 p-3">尚无消息 — 发送第一条吧</div>';
        }
      }

      // 移动端自动收起好友栏
      if (window.innerWidth <= 900 && chatSidebar) {
        chatSidebar.style.display = 'none';
      }
    })
    .catch(err => {
      console.error(err);
      if (messagesEl) {
        messagesEl.innerHTML = '<div class="text-center text-muted mt-4">无法加载服务器消息，请检查后端接口 /chat/{id}。</div>';
      }
    });
});

  // 发送消息
  if (sendForm) {
  sendForm.addEventListener('submit', function (e) {
    e.preventDefault();

    if (sendForm.dataset.locked === "1") return; // 避免重复提交
    sendForm.dataset.locked = "1"; // 上锁

    const toUserId = toUserIdInput.value;
    const content = messageText.value.trim();

    if (!toUserId) {
      alert('请先选择好友再发送消息');
      sendForm.dataset.locked = "0";
      return;
    }
    if (!content) {
      sendForm.dataset.locked = "0";
      return;
    }

    fetch('/chat/send', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({
        to_user_id: toUserId,   // ✅ 保持和后端一致
        content: content        // ✅ 关键点：字段名改成 content
      })
    })
    .then(r => {
      if (!r.ok) throw new Error('发送失败');
      return r.json();
    })
    .then(data => {
      appendMessageToDOM({
        id: data.message?.id || ('tmp-' + Date.now()),
        content: data.message?.content ?? content,
        from_user_id: {{ auth()->id() }},
        created_at: data.message?.created_at ?? (new Date()).toLocaleString()
      }, true);
      messageText.value = '';
      messageText.focus();
    })
    .catch(err => {
      console.warn('后端发送失败，前端模拟：', err);
      appendMessageToDOM({
        id: 'tmp-' + Date.now(),
        content: content,
        from_user_id: {{ auth()->id() }},
        created_at: (new Date()).toLocaleString()
      }, true);
      messageText.value = '';
    })
    .finally(() => {
      sendForm.dataset.locked = "0"; // 解锁
    });
  });
}
document.addEventListener('click', function(e) {
  // 删除
  if (e.target.classList.contains('btn-delete-msg')) {
    const msgId = e.target.dataset.id;
    if (!confirm('确定要删除这条消息吗？')) return;

    // 临时消息直接删DOM
    if (msgId.startsWith('tmp-')) {
      const msgDiv = document.querySelector(`.msg[data-id="${msgId}"]`);
      if (msgDiv) msgDiv.remove();
      return;
    }

    // 数据库消息删除请求
    fetch(`/chat/message/${msgId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(r => {
      if (!r.ok) throw new Error('删除失败');
      return r.json();
    })
    .then(data => {
      if (data.success) {
        const msgDiv = document.querySelector(`.msg[data-id="${msgId}"]`);
        if (msgDiv) msgDiv.remove();
      }
    })
    .catch(err => {
      console.error(err);
      alert('删除失败，请稍后再试');
    });
  }

  // 复制
  if (e.target.classList.contains('btn-copy-msg')) {
    e.preventDefault();
    const msgId = e.target.dataset.id;
    const msgDiv = document.querySelector(`.msg[data-id="${msgId}"]`);
    if (msgDiv) {
      const text = msgDiv.querySelector('div[style*="word-break"]').innerText;
      navigator.clipboard.writeText(text)
        .then(() => {
          alert('消息已复制到剪贴板');
        })
        .catch(err => {
          console.error(err);
          alert('复制失败');
        });
    }
  }
});



// 每隔 2 秒刷新消息
// sendForm && sendForm.addEventListener('submit', function (e) {
//     e.preventDefault();
//     const toUserId = toUserIdInput?.value;
//     const content = messageText.value.trim();

//     if (!toUserId) {
//       alert('请先选择好友再发送消息');
//       return;
//     }
//     if (!content) return;

//     fetch('/chat/send', {
//       method: 'POST',
//       headers: {
//         'Content-Type': 'application/json',
//         'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
//         'X-Requested-With': 'XMLHttpRequest'
//       },
//       body: JSON.stringify({ to_user_id: toUserId, content })
//     })
//       .then(r => {
//         if (!r.ok) throw new Error('发送失败');
//         return r.json();
//       })
//       .then(data => {
//         appendMessageToDOM({
//           id: data.message?.id || ('tmp-' + Date.now()),
//           content: data.message?.content ?? content,
//           from_user_id: {{ auth()->id() }},
//           created_at: data.message?.created_at ?? (new Date()).toLocaleString()
//         }, true);
//         messageText.value = '';
//         messageText.focus();
//       })
//       .catch(err => {
//         console.warn('后端发送失败，前端模拟：', err);
//         appendMessageToDOM({
//           id: 'tmp-' + Date.now(),
//           content: content,
//           from_user_id: {{ auth()->id() }},
//           created_at: (new Date()).toLocaleString()
//         }, true);
//         messageText.value = '';
//       });
//   });
// setInterval(() => {
//     const toUserId = document.querySelector('#toUserId').value;
//     fetchMessages(toUserId);
// }, 2000);


  // 添加好友提交
  if (addFriendForm) {
  addFriendForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    const fd = new FormData(addFriendForm);
    const url = addFriendForm.dataset.url;

    try {
      const r = await fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: fd
      });

      const data = await r.json();

      // 不依赖抛异常显示错误，而是统一处理
      if (!r.ok) {
        let msg = '发送请求失败，请稍后重试';
        if (r.status === 422) msg = '查无此人，请检查用户名、邮箱或用户ID是否正确';
        else if (r.status === 400) msg = data.message || '请求无效';
        alert(msg);
        return; // 结束逻辑
      }

      // 请求成功逻辑
      alert(data.message || '好友请求已发送');

      const modal = bootstrap.Modal.getInstance(document.getElementById('addFriendModal'));
      modal.hide();
      addFriendForm.reset();

      if (data.friend) {
        const node = createFriendNode(data.friend);
        friendsList.prepend(node);
      }

    } catch (err) {
      // 捕获网络错误或其他异常，但不让浏览器显示红色报错
      console.log('请求异常:', err); // 可选，调试用
      alert('发送请求失败，请检查网络或稍后重试');
    }
  });
}

  // 搜索好友（前端简单过滤）
  friendSearch && friendSearch.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.friend-item').forEach(item => {
      const name = item.getAttribute('data-name')?.toLowerCase() || '';
      if (name.includes(q)) item.style.display = '';
      else item.style.display = 'none';
    });
  });

  // 工具函数
  function appendMessageToDOM(msg, outgoing = false) {
    const div = document.createElement('div');
    div.className = 'msg ' + (outgoing ? 'outgoing' : 'incoming');
    div.setAttribute('data-id', msg.id);
    div.innerHTML = `
    <div class="d-flex align-items-start justify-content-between">
    <div style="font-size:.92rem; word-break:break-word;">
      ${escapeHtml(msg.content).replace(/\n/g,'<br>')}
    </div>

    <!-- 小的更多按钮 -->
    <div class="dropdown ms-2">
      <button class="btn btn-sm btn-link text-white-50 p-0" type="button"
              data-bs-toggle="dropdown" aria-expanded="false" style="font-size:1.2rem;">
        ⋮
      </button>
      <ul class="dropdown-menu dropdown-menu-end">
        ${msg.from_user_id === authUserId
            ? `<li><a href="#" class="dropdown-item btn-delete-msg" data-id="${msg.id}">删除</a></li>`
            : ''}
        <li><a href="#" class="dropdown-item btn-copy-msg" data-id="${msg.id}">复制</a></li>
      </ul>
    </div>
  </div>

  <div class="text-end text-white-50" style="font-size:.72rem; margin-top:6px;">
    ${msg.created_at}
  </div>
  `;
    messagesEl.appendChild(div);
    scrollToBottom(messagesEl);
  }
  messagesEl.addEventListener('click', function(e) {
  const target = e.target;
  if (target.classList.contains('btn-delete-msg')) {
    e.preventDefault();
    const msgId = target.getAttribute('data-id');
    // deleteMessage(msgId);
  }
});


  function createFriendNode(friend) {
    const wrapper = document.createElement('div');
    wrapper.className = 'list-group-item friend-item d-flex align-items-center';
    wrapper.setAttribute('data-id', friend.id);
    wrapper.setAttribute('data-name', friend.name || '好友');
    wrapper.innerHTML = `<img src="${friend.avatar || 'https://via.placeholder.com/48?text=U'}" class="friend-avatar me-2">
      <div class="flex-grow-1">
        <div class="d-flex align-items-center">
          <strong class="me-2" style="font-size:.98rem;">${friend.name}</strong>
          <small class="text-text-white-50 ms-auto">${friend.last_seen || ''}</small>
        </div>
        <div class="text-truncate" style="max-width:200px;">
          <span class="status-dot ${friend.is_online ? 'status-online' : 'status-offline'}"></span>
          <small class="text-muted">${friend.status_message || '在线'}</small>
        </div>
      </div>`;
    return wrapper;
  }

  function scrollToBottom(el) {
    el.scrollTop = el.scrollHeight;
  }

  function escapeHtml(unsafe) {
    return (unsafe + '')
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");function createFriendNode(friend) {
  const wrapper = document.createElement('div');
  wrapper.className = 'list-group-item friend-item d-flex align-items-center';
  wrapper.setAttribute('data-id', friend.id);
  wrapper.setAttribute('data-name', friend.name || '好友');
  wrapper.setAttribute('data-avatar', friend.avatar || "{{ asset('images/defaultaaa.webp') }}");

  wrapper.innerHTML = `
    <img
      src="${friend.avatar || "{{ asset('images/defaultaaa.webp') }}"}"
      onerror="this.onerror=null; this.src='{{ asset('images/defaultaaa.webp') }}';"
      style="width:48px; height:48px; border-radius:50%; object-fit:cover; border:2px solid #ddd;"
      class="friend-avatar me-2"
      alt="avatar"
    />

    <div class="flex-grow-1 d-flex align-items-center justify-content-between">

      <!-- 左侧: 名字 + 上次在线 + 状态 -->
      <div class="me-2" style="min-width:0;">
        <div class="d-flex align-items-center">
          <strong class="me-2" style="font-size:.98rem;">${friend.name}</strong>
          <small class="text-muted ms-auto">${friend.last_seen || ''}</small>
        </div>
        <div class="text-truncate" style="max-width:200px;">
          <span class="status-dot ${friend.is_online ? 'status-online' : 'status-offline'}"></span>
          <small class="${friend.is_online ? 'text-info' : 'text-muted'}">
            ${friend.is_online ? '在线' : '离线'}
          </small>
        </div>
      </div>

      <!-- 右侧: 删除按钮 -->
      <form action="/friends/${friend.id}" method="POST" onsubmit="return confirm('确定要删除好友吗？');">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="DELETE">
        <button type="submit" class="btn btn-sm btn-outline-danger">删除</button>
      </form>

    </div>
  `;
  return wrapper;
}

  }

});

const canvas = document.getElementById('chatBackgroundCanvas');
const ctx = canvas.getContext('2d');

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

const particles = [];
const shootingStars = [];

// 初始化星星
for (let i = 0; i < 150; i++) {
    particles.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        radius: Math.random() * 2 + 1,
        dx: Math.random() * 0.5 - 0.25,
        dy: Math.random() * 0.5 - 0.25,
        color: Math.random() > 0.5 ? '#8b5cf6' : '#4f46e5'
    });
}

// 生成流星
function createShootingStar() {
    const length = Math.random() * 80 + 50;
    shootingStars.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height * 0.4, // 从顶部生成
        length: length,
        speed: Math.random() * 3 + 4, // 基础速度提升
        opacity: 1,
        angle: Math.random() * 10 + 30, // 30-40 度角
        life: length * 1.5, // 持续时间
        tail: [] // 用于存储拖尾点
    });
}

// 更新星星
function updateParticles() {
    for (const particle of particles) {
        particle.x += particle.dx;
        particle.y += particle.dy;

        if (particle.x < 0 || particle.x > canvas.width) particle.dx *= -1;
        if (particle.y < 0 || particle.y > canvas.height) particle.dy *= -1;
    }
}

// 绘制星星
function drawParticles() {
    for (const particle of particles) {
        ctx.beginPath();
        ctx.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2);
        ctx.fillStyle = particle.color;
        ctx.shadowBlur = 10;
        ctx.shadowColor = particle.color;
        ctx.fill();
    }
}

// 更新流星
function updateShootingStars() {
    for (let i = shootingStars.length - 1; i >= 0; i--) {
        const star = shootingStars[i];
        const radians = (star.angle * Math.PI) / 180;

        star.x -= star.speed * Math.cos(radians);
        star.y += star.speed * Math.sin(radians);
        star.life -= 1;

        // 添加尾巴坐标
        star.tail.unshift({ x: star.x, y: star.y });

        // 控制尾巴长度
        if (star.tail.length > 30) star.tail.pop();

        // 移除消失的流星
        if (star.life <= 0) shootingStars.splice(i, 1);
    }
}

// 绘制流星
function drawShootingStars() {
    for (const star of shootingStars) {
        // 绘制流星头部
        ctx.beginPath();
        ctx.arc(star.x, star.y, 3, 0, Math.PI * 2);
        ctx.fillStyle = 'rgba(255, 255, 255, 0.9)';
        ctx.shadowBlur = 20;
        ctx.shadowColor = 'rgba(255, 255, 255, 1)';
        ctx.fill();

        // 绘制流星尾迹（渐变消失效果）
        for (let j = 0; j < star.tail.length; j++) {
            const opacity = (1 - j / star.tail.length) * star.opacity;
            ctx.beginPath();
            ctx.arc(star.tail[j].x, star.tail[j].y, 1.5, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(255, 255, 255, ${opacity})`;
            ctx.fill();
        }
    }
}

// 动画循环
function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    drawParticles();
    drawShootingStars();
    updateParticles();
    updateShootingStars();

    // 控制流星生成频率
    if (Math.random() < 0.03) createShootingStar();

    requestAnimationFrame(animate);
}

animate();

// 响应窗口大小变化
window.addEventListener('resize', () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
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
@endsection
