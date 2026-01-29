<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台管理面板</title>
    <!-- 引入 Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #121212; color: white;">

<style>
    .sidebar {
        background: #1f1f1f;
        min-height: 100vh;
        padding: 20px 10px;
        border-right: 1px solid #333;
    }

    .sidebar h4 {
        margin-bottom: 30px;
        color: #ffb84d;
        font-weight: bold;
    }

    .sidebar a {
        display: block;
        color: #ccc;
        text-decoration: none;
        padding: 10px 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        transition: all 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
        background-color: #ffb84d;
        color: #000;
        font-weight: bold;
    }

    .content {
        background-color: #181818;
        min-height: 100vh;
        padding: 30px;
    }

    .card {
        background-color: #222;
        color: #fff;
        border: none;
        border-radius: 12px;
        transition: transform 0.2s ease;
    }

    hr {
        border-color: #333;
    }

    table {
        color: #fff;
    }

    .table thead {
        background-color: #2a2a2a;
    }

    .table tbody tr:hover {
        background-color: #2e2e2e;
    }

    .btn-danger, .btn-warning {
        border-radius: 6px;
        font-size: 0.85rem;
    }

    /* ===== 用户管理样式优化 ===== */
    #users .card {
        background-color: #212529;
        border-radius: 1rem;
    }

    #users .table {
        color: #adb5bd;
        border-collapse: separate;
        border-spacing: 0;
    }

    #users thead tr {
        background-color: rgba(255, 255, 255, 0.05);
    }

    #users tbody tr {
        transition: background-color 0.2s, transform 0.15s;
    }

    #users tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.07);
        transform: scale(1.01);
    }

    #users button {
        transition: all 0.2s ease-in-out;
    }

    #users button:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
    }

    #users .table-row-hover td {
        vertical-align: middle;
    }

    /* ===== 帖子管理样式优化 ===== */
    #posts .card {
        background-color: #212529;
        border-radius: 1rem;
    }

    #posts .table {
        color: #adb5bd;
        border-collapse: separate;
        border-spacing: 0;
    }

    #posts thead tr {
        background-color: rgba(255, 255, 255, 0.05);
    }

    #posts tbody tr {
        transition: background-color 0.2s, transform 0.15s;
    }

    #posts tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.07);
        transform: scale(1.01);
    }

    #posts button {
        transition: all 0.2s ease-in-out;
    }

    #posts button:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
    }

    #posts .table-row-hover td {
        vertical-align: middle;
    }

    /* ===== 评论管理样式优化 ===== */
    #comments .card {
        background-color: #212529;
        border-radius: 1rem;
    }

    #comments .table {
        color: #adb5bd;
        border-collapse: separate;
        border-spacing: 0;
    }

    #comments thead tr {
        background-color: rgba(255, 255, 255, 0.05);
    }

    #comments tbody tr {
        transition: background-color 0.2s, transform 0.15s;
    }

    #comments tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.07);
        transform: scale(1.01);
    }

    #comments button {
        transition: all 0.2s ease-in-out;
    }

    #comments button:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
    }

    #comments .table-row-hover td {
        vertical-align: middle;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- 左侧导航 -->
        <div class="col-md-2 sidebar">
            <h4 class="text-center">管理面板</h4>
            <a href="#" class="menu-link active" data-target="dashboard">📊 仪表盘</a>
            <a href="#" class="menu-link" data-target="users">👥 用户管理</a>
            <a href="#" class="menu-link" data-target="posts">📝 帖子管理</a>
            <a href="#" class="menu-link" data-target="comments">💬 评论管理</a>

            <form method="POST" action="{{ route('admin.logout') }}" class="mt-3 text-center">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm w-100">退出登录</button>
            </form>
        </div>

        <!-- 主体内容 -->
        <div class="col-md-10 content">
            <section id="dashboard" class="content-section">
                <h2>欢迎，管理员 {{ Auth::guard('admin')->user()->name }}</h2>
                <p class="text-muted">这是校园论坛的后台管理仪表盘。</p>

                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card shadow-sm text-center p-4">
                            <h5>用户总数</h5>
                            <h2>{{ $totalUsers }}</h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm text-center p-4">
                            <h5>帖子总数</h5>
                            <h2>{{ $totalPosts }}</h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm text-center p-4">
                            <h5>评论总数</h5>
                            <h2>{{ $totalComments }}</h2>
                        </div>
                    </div>
                </div>
            </section>

            <section id="users" class="content-section d-none">
    <div class="card bg-dark text-light border-0 shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">👥 用户管理</h3>
            <span class="badge bg-secondary fs-6">共 {{ $users->count() }} 位用户</span>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0 rounded-3 overflow-hidden">
                <thead class="bg-secondary bg-opacity-25 text-uppercase small">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">姓名</th>
                        <th scope="col">邮箱</th>
                        <th scope="col">注册时间</th>
                        <th scope="col" class="text-center">操作</th>
                    </tr>
                </thead>
                <tbody>
    @foreach($users as $u)
    <tr class="table-row-hover">
        <td class="fw-semibold text-info">{{ $loop->iteration }}</td>
        <td>{{ $u->name }}</td>
        <td>{{ $u->email }}</td>
        <td>{{ $u->created_at->format('Y-m-d') }}</td>
        <td class="text-center">
            <form method="POST" action="{{ route('admin.user.delete', $u->user_id) }}" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger rounded-pill px-3">
                    <i class="bi bi-trash3"></i> 删除
                </button>
            </form>
            <form method="POST" action="{{ route('admin.user.ban', $u->user_id) }}" class="d-inline ms-2">
                @csrf
                <button class="btn btn-sm btn-outline-warning rounded-pill px-3">
                    <i class="bi bi-slash-circle"></i> 封禁
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</tbody>
            </table>
        </div>
    </div>
</section>

            <section id="posts" class="content-section d-none">
    <div class="card bg-dark text-light border-0 shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">📝 帖子管理</h3>
            <span class="badge bg-secondary fs-6">共 {{ $posts->count() }} 篇帖子</span>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0 rounded-3 overflow-hidden">
                <thead class="bg-secondary bg-opacity-25 text-uppercase small">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">标题</th>
                        <th scope="col">作者</th>
                        <th scope="col">发布时间</th>
                        <th scope="col" class="text-center">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $p)
                    <tr class="table-row-hover">
                        <td class="fw-semibold text-info">{{ $loop->iteration }}</td>
                        <td class="fw-bold">{{ $p->title }}</td>
                        <td class="text-light">{{ $p->user->name }}</td>
                        <td class="text-light">{{ $p->created_at->format('Y-m-d H:i') }}</td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('admin.post.delete', $p->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                    <i class="bi bi-trash3"></i> 删除
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

            <section id="comments" class="content-section d-none">
    <div class="card bg-dark text-light border-0 shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">💬 评论管理</h3>
            <span class="badge bg-secondary fs-6">共 {{ $comments->count() }} 条评论</span>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0 rounded-3 overflow-hidden">
                <thead class="bg-secondary bg-opacity-25 text-uppercase small">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">评论内容</th>
                        <th scope="col">用户</th>
                        <th scope="col">所属帖子</th>
                        <th scope="col">时间</th>
                        <th scope="col" class="text-center">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comments as $c)
                    <tr class="table-row-hover">
                        <td class="fw-semibold text-info">{{ $loop->iteration }}</td>
                        <td class="text-light" style="max-width: 300px; word-wrap: break-word;">{{ $c->content }}</td>
                        <td class="text-light">{{ $c->user->name }}</td>
                        <td class="text-light">{{ $c->post->title }}</td>
                        <td class="text-light">{{ $c->created_at->format('Y-m-d H:i') }}</td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('admin.comment.delete', $c->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                    <i class="bi bi-trash3"></i> 删除
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
        </div>
    </div>
</div>

<!-- Bootstrap & JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.menu-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            document.querySelectorAll('.menu-link').forEach(a => a.classList.remove('active'));
            this.classList.add('active');

            const target = this.getAttribute('data-target');
            document.querySelectorAll('.content-section').forEach(sec => sec.classList.add('d-none'));
            document.getElementById(target).classList.remove('d-none');
        });
    });
</script>

</body>
</html>
