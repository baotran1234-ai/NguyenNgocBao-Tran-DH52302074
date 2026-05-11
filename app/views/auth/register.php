<?php // app/views/auth/register.php
$errors = $errors ?? [];
$old = $old ?? [];
?>
<section style="min-height:70vh;display:flex;align-items:center;padding:60px 0;background:var(--bg-section)">
  <div class="container" style="max-width:520px">
    <div style="background:var(--bg-card);border-radius:var(--radius-lg);padding:40px;box-shadow:var(--shadow-md)">
      <div style="text-align:center;margin-bottom:32px">
        <div class="logo-mark" style="margin:0 auto 12px;width:56px;height:56px;font-size:1.6rem">L</div>
        <h1 style="font-family:var(--font-display);font-size:1.8rem;margin-bottom:6px">Tạo Tài Khoản</h1>
        <p style="color:var(--text-muted);font-size:0.9rem">Tham gia cộng đồng LUXE Beauty</p>
      </div>

      <?php if (!empty($errors)): ?>
      <div style="background:#fee2e2;color:#c53030;padding:12px 16px;border-radius:var(--radius-md);margin-bottom:20px;font-size:0.875rem">
        <?php foreach ($errors as $err): ?>
        <div><i class="fas fa-exclamation-circle"></i> <?= e($err) ?></div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <form method="POST" action="">
        <?= csrfField() ?>
        <div class="form-group">
          <label class="form-label">Họ và tên *</label>
          <input type="text" name="name" class="form-control" placeholder="Nguyễn Thị Lan"
                 value="<?= e($old['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Email *</label>
          <input type="email" name="email" class="form-control" placeholder="email@example.com"
                 value="<?= e($old['email'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Số điện thoại</label>
          <input type="tel" name="phone" class="form-control" placeholder="0901234567"
                 value="<?= e($old['phone'] ?? '') ?>">
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Mật khẩu *</label>
            <input type="password" name="password" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
          </div>
          <div class="form-group">
            <label class="form-label">Xác nhận mật khẩu *</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu" required>
          </div>
        </div>
        <div style="display:flex;gap:8px;margin-bottom:20px;font-size:0.8rem;color:var(--text-muted);align-items:flex-start">
          <input type="checkbox" required style="margin-top:2px;accent-color:var(--primary)">
          <span>Tôi đồng ý với <a href="#" style="color:var(--primary)">Điều khoản sử dụng</a> và <a href="#" style="color:var(--primary)">Chính sách bảo mật</a></span>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">
          <i class="fas fa-user-plus"></i> Đăng Ký
        </button>
      </form>

      <div style="text-align:center;margin-top:24px;font-size:0.875rem;color:var(--text-muted)">
        Đã có tài khoản?
        <a href="<?= url('auth/login') ?>" style="color:var(--primary);font-weight:600">Đăng nhập</a>
      </div>
    </div>
  </div>
</section>
