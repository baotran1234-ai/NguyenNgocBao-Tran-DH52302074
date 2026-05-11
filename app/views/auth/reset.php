<?php // app/views/auth/reset.php
$error = $error ?? '';
?>
<section style="min-height:60vh;display:flex;align-items:center;padding:60px 0;background:var(--bg-section)">
  <div class="container" style="max-width:460px">
    <div style="background:var(--bg-card);border-radius:var(--radius-lg);padding:40px;box-shadow:var(--shadow-md)">
      <div style="text-align:center;margin-bottom:28px">
        <h1 style="font-family:var(--font-display);font-size:1.7rem;margin-bottom:6px">Đặt Lại Mật Khẩu</h1>
      </div>
      <?php if (!empty($error)): ?>
      <div style="background:#fee2e2;color:#c53030;padding:14px;border-radius:var(--radius-md);margin-bottom:20px;font-size:0.875rem">
        <?= e($error) ?>
      </div>
      <?php endif; ?>
      <form method="POST">
        <?= csrfField() ?>
        <div class="form-group">
          <label class="form-label">Mật khẩu mới</label>
          <input type="password" name="password" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
        </div>
        <div class="form-group">
          <label class="form-label">Xác nhận mật khẩu</label>
          <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">
          <i class="fas fa-lock"></i> Đặt Lại Mật Khẩu
        </button>
      </form>
    </div>
  </div>
</section>
