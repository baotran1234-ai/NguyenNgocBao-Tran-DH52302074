<?php // app/views/user/profile.php ?>
<h2 style="font-family:var(--font-display);font-size:1.5rem;margin-bottom:24px;border-bottom:1px solid var(--border);padding-bottom:16px">Hồ Sơ Của Tôi</h2>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:40px">
  <!-- Cập nhật thông tin -->
  <div>
    <h3 style="font-size:1.1rem;margin-bottom:16px">Thông Tin Cá Nhân</h3>
    <form method="POST">
      <div class="form-group">
        <label class="form-label">Email đăng nhập</label>
        <input type="email" class="form-control" value="<?= e($user['email']??'') ?>" disabled style="background:var(--bg-section);color:var(--text-muted)">
      </div>
      <div class="form-group">
        <label class="form-label">Họ và tên *</label>
        <input type="text" name="name" class="form-control" value="<?= e($user['name']??'') ?>" required>
      </div>
      <div class="form-group">
        <label class="form-label">Số điện thoại</label>
        <input type="tel" name="phone" class="form-control" value="<?= e($user['phone']??'') ?>">
      </div>
      <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu Thay Đổi</button>
    </form>
  </div>

  <!-- Đổi mật khẩu -->
  <div>
    <h3 style="font-size:1.1rem;margin-bottom:16px">Đổi Mật Khẩu</h3>
    <form method="POST" action="<?= url('user/password') ?>">
      <div class="form-group">
        <label class="form-label">Mật khẩu hiện tại</label>
        <input type="password" name="old_password" class="form-control" required>
      </div>
      <div class="form-group">
        <label class="form-label">Mật khẩu mới</label>
        <input type="password" name="new_password" class="form-control" required>
      </div>
      <div class="form-group">
        <label class="form-label">Xác nhận mật khẩu mới</label>
        <input type="password" name="confirm_password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-outline"><i class="fas fa-key"></i> Đổi Mật Khẩu</button>
    </form>
  </div>
</div>
