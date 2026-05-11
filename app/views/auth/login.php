<?php // app/views/auth/login.php
$error = $error ?? '';
?>
<section style="min-height:70vh;display:flex;align-items:center;padding:60px 0;background:var(--bg-section)">
  <div class="container" style="max-width:480px">
    <div style="background:var(--bg-card);border-radius:var(--radius-lg);padding:40px;box-shadow:var(--shadow-md)">

      <div style="text-align:center;margin-bottom:32px">
        <div class="logo-mark" style="margin:0 auto 12px;width:56px;height:56px;font-size:1.6rem">L</div>
        <h1 style="font-family:var(--font-display);font-size:1.8rem;margin-bottom:6px">Đăng nhập</h1>
        <p style="color:var(--text-muted);font-size:0.9rem">Chào mừng trở lại LUXE Beauty</p>
      </div>

      <?php if (!empty($error)): ?>
      <div style="background:#fee2e2;color:#c53030;padding:12px 16px;border-radius:var(--radius-md);margin-bottom:20px;font-size:0.875rem">
        <i class="fas fa-exclamation-circle"></i> <?= e($error) ?>
      </div>
      <?php endif; ?>

      <form method="POST" action="">
        <?= csrfField() ?>
        <div class="form-group">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" placeholder="email@example.com"
                 value="<?= e($_POST['email'] ?? '') ?>" required autofocus>
        </div>
        <div class="form-group">
          <label class="form-label" style="display:flex;justify-content:space-between">
            Mật khẩu
            <a href="<?= url('auth/forgot') ?>" style="color:var(--primary);font-weight:400;font-size:0.8rem">Quên mật khẩu?</a>
          </label>
          <div style="position:relative">
            <input type="password" name="password" id="passwordInput" class="form-control" placeholder="••••••••" required>
            <button type="button" onclick="togglePass()" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:var(--text-muted)" id="passToggle">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px">
          <input type="checkbox" name="remember" id="remember" style="width:16px;height:16px;accent-color:var(--primary)">
          <label for="remember" style="font-size:0.875rem;cursor:pointer">Ghi nhớ đăng nhập</label>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">
          <i class="fas fa-sign-in-alt"></i> Đăng nhập
        </button>
      </form>

      <div style="text-align:center;margin-top:24px;font-size:0.875rem;color:var(--text-muted)">
        Chưa có tài khoản?
        <a href="<?= url('auth/register') ?>" style="color:var(--primary);font-weight:600">Đăng ký ngay</a>
      </div>
    </div>
  </div>
</section>
<script>
function togglePass() {
  const i = document.getElementById('passwordInput');
  const b = document.getElementById('passToggle').querySelector('i');
  if (i.type === 'password') { i.type = 'text'; b.className = 'fas fa-eye-slash'; }
  else { i.type = 'password'; b.className = 'fas fa-eye'; }
}
</script>
