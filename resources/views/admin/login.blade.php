<!DOCTYPE html>
<html lang="zh">
<head>
  <meta charset="UTF-8">
  <title>Admin 登录</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      overflow: hidden; /* 禁止滚动条 */
    }

    /* 背景视频 */
    #bgVideo {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover; /* 保持比例填充，不留黑边 */
      z-index: -1;
      filter: brightness(0.5); /* 暗化一点，凸显登录框 */
    }

    /* 登录卡片居中 */
    .login-card {
      position: relative;
      z-index: 2;
      margin-top: 10vh;
    }

    .card {
      background: rgba(255, 255, 255, 0.9); /* 半透明背景 */
      border-radius: 12px;
    }
  </style>
</head>
<body>
  <!-- 背景视频 -->
  <video autoplay muted loop playsinline id="bgVideo">
    <source src="{{ asset('videos/columbina.mp4') }}" type="video/mp4">
    您的浏览器不支持视频背景
  </video>

  <!-- 登录框 -->
  <div class="container login-card">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow">
          <div class="card-header text-center bg-primary text-white fw-bold">
            管理员登录
          </div>
          <div class="card-body">
            <!-- 错误/成功提示 -->
            <div id="alertBox" class="alert d-none"></div>

            <!-- 登录表单 -->
            <form id="adminLoginForm">
              @csrf
              <div class="mb-3">
                <label>邮箱</label>
                <input type="email" name="email" class="form-control" required autofocus>
              </div>
              <div class="mb-3">
                <label>密码</label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <button type="submit" class="btn btn-primary w-100">登录</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // 登录表单提交
    document.getElementById("adminLoginForm").addEventListener("submit", function(e) {
      e.preventDefault();

      let formData = new FormData(this);
      fetch("{{ route('admin.login.submit') }}", {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: formData
      })
      .then(async res => {
        let data = await res.json();
        console.log("后端返回:", data);

        let alertBox = document.getElementById("alertBox");
        alertBox.classList.remove("d-none", "alert-success", "alert-danger");

        if (data.status === "success") {
          alertBox.classList.add("alert-success");
          alertBox.innerText = data.message;
          setTimeout(() => window.location.href = data.redirect, 1000);
        } else {
          alertBox.classList.add("alert-danger");
          alertBox.innerText = data.message;
        }
      })
      .catch(err => {
        console.error("请求异常:", err);
      });
    });

    // ✅ 用户第一次点击页面时，开启视频声音
    document.addEventListener("click", () => {
      const video = document.getElementById("bgVideo");
      video.muted = false;
      video.play();
    }, { once: true });
  </script>
</body>
</html>
