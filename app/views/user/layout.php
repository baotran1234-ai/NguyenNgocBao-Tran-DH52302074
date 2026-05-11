<?php // app/views/user/layout.php ?>
<div style="background:var(--bg-section);padding:20px 0">
  <div class="container"><nav class="breadcrumb">
    <a href="<?= url('') ?>">Trang chủ</a><span class="breadcrumb-sep">/</span>
    <span>Tài Khoản Của Tôi</span>
  </nav></div>
</div>

<section class="section">
  <div class="container">

    <!-- User Main Content (full width) -->
    <div style="background:var(--bg-card);border-radius:var(--radius-lg);padding:32px;box-shadow:var(--shadow-sm);min-height:500px">

      <!-- Tab nav -->
      <div style="display:flex;gap:8px;margin-bottom:28px;border-bottom:1px solid var(--border);padding-bottom:0">
        <?php foreach ([
          ['user/profile',  'fas fa-user',        'Hồ Sơ'],
          ['user/orders',   'fas fa-shopping-bag', 'Đơn Hàng'],
          ['user/wishlist', 'fas fa-heart',        'Yêu Thích'],
        ] as $link):
          $active = strpos($_SERVER['REQUEST_URI'], $link[0]) !== false;
        ?>
        <a href="<?= url($link[0]) ?>"
           style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:var(--radius-full) var(--radius-full) 0 0;font-size:0.875rem;font-weight:600;transition:all 0.2s;margin-bottom:-1px;
                  color:<?= $active ? 'var(--primary)' : 'var(--text-muted)' ?>;
                  border-bottom:2px solid <?= $active ? 'var(--primary)' : 'transparent' ?>;
                  background:<?= $active ? 'var(--primary-light)' : 'transparent' ?>">
          <i class="<?= $link[1] ?>"></i> <?= $link[2] ?>
        </a>
        <?php endforeach; ?>
        <a href="<?= url('auth/logout') ?>"
           style="margin-left:auto;display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:var(--radius-full);font-size:0.875rem;font-weight:600;color:#e53e3e;transition:all 0.2s">
          <i class="fas fa-sign-out-alt"></i> Đăng Xuất
        </a>
      </div>

      <?php if ($msg = getFlash('success')): ?>
      <div style="background:#e8f5e9;color:#2e7d32;padding:12px 16px;border-radius:var(--radius-md);margin-bottom:20px;font-size:0.875rem"><i class="fas fa-check-circle"></i> <?= $msg ?></div>
      <?php endif; ?>
      <?php if ($msg = getFlash('error')): ?>
      <div style="background:#fee2e2;color:#c53030;padding:12px 16px;border-radius:var(--radius-md);margin-bottom:20px;font-size:0.875rem"><i class="fas fa-exclamation-circle"></i> <?= $msg ?></div>
      <?php endif; ?>
      <?php if ($msg = getFlash('warning')): ?>
      <div style="background:#fff3e0;color:#e65100;padding:12px 16px;border-radius:var(--radius-md);margin-bottom:20px;font-size:0.875rem"><i class="fas fa-exclamation-triangle"></i> <?= $msg ?></div>
      <?php endif; ?>