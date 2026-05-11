<?php // app/views/auth/forgot.php
$message = $message ?? '';
$error = $error ?? '';
?>
<section style="min-height:60vh;display:flex;align-items:center;padding:60px 0;background:var(--bg-section)">
  <div class="container" style="max-width:460px">
    <div style="background:var(--bg-card);border-radius:var(--radius-lg);padding:40px;box-shadow:var(--shadow-md)">
      <div style="text-align:center;margin-bottom:28px">
        <div style="width:56px;height:56px;background:linear-gradient(135deg,var(--primary),var(--accent));border-radius:50%;margin:0 auto 12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.4rem"><i class="fas fa-key"></i></div>
        <h1 style="font-family:var(--font-display);font-size:1.7rem;margin-bottom:6px">Quên Mật Khẩu</h1>
        <p style="color:var(--text-muted);font-size:0.875rem">Nhập email để nhận link đặt lại mật khẩu</p>
      </div>

      <?php if (!empty($message)): ?>
      <div style="background:#e8f5e9;color:#2e7d32;padding:14px 16px;border-radius:var(--radius-md);margin-bottom:20px;font-size:0.875rem">
        <i class="fas fa-check-circle"></i> <?= $message ?>
      </div>
      <?php endif; ?>
      <?php if (!empty($error)): ?>
      <div style="background:#fee2e2;color:#c53030;padding:14px 16px;border-radius:var(--radius-md);margin-bottom:20px;font-size:0.875rem">
        <i class="fas fa-exclamation-circle"></i> <?= e($error) ?>
      </div>
      <?php endif; ?>

      <form method="POST">
        <?= csrfField() ?>
        <div class="form-group">
          <label class="form-label">Email đăng ký</label>
          <input type="email" name="email" class="form-control" placeholder="email@example.com" required autofocus>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">
          <i class="fas fa-paper-plane"></i> Gửi Link Đặt Lại
        </button>
      </form>

      <div style="text-align:center;margin-top:20px;font-size:0.875rem">
        <a href="<?= url('auth/login') ?>" style="color:var(--primary)"><i class="fas fa-arrow-left"></i> Quay lại Đăng nhập</a>
      </div>
    </div>
  </div>
</section>
