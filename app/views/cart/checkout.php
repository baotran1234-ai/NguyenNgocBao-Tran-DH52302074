<?php // app/views/cart/checkout.php ?>
<section style="background:var(--bg-section);padding:20px 0">
  <div class="container"><nav class="breadcrumb">
    <a href="<?= url('') ?>">Trang Chủ</a><span class="breadcrumb-sep">/</span>
    <a href="<?= url('cart') ?>">Giỏ hàng</a><span class="breadcrumb-sep">/</span>
    <span>Thanh toán</span>
  </nav></div>
</section>

<section class="section">
<div class="container">
  <h1 style="font-family:var(--font-display);font-size:2rem;margin-bottom:32px">Thanh Toán</h1>
  <form method="POST" action="<?= url('cart/confirm') ?>">
    <?= csrfField() ?>
    <div style="display:grid;grid-template-columns:1fr 380px;gap:32px;align-items:start">

      <!-- Shipping Info -->
      <div style="background:var(--bg-card);border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow-sm)">
        <h3 style="font-family:var(--font-display);font-size:1.3rem;margin-bottom:20px">
          <i class="fas fa-map-marker-alt" style="color:var(--primary)"></i> Thông Tin Giao Hàng
        </h3>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Họ và tên *</label>
            <input type="text" name="name" class="form-control" value="<?= e($user['name'] ?? '') ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label">Số điện thoại *</label>
            <input type="tel" name="phone" class="form-control" value="<?= e($user['phone'] ?? '') ?>" required>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Email *</label>
          <input type="email" name="email" class="form-control" value="<?= e($user['email'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Địa chỉ *</label>
          <input type="text" name="address" class="form-control" placeholder="Số nhà, tên đường..." required>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Tỉnh/Thành phố</label>
            <input type="text" name="city" class="form-control" placeholder="TP. Hồ Chí Minh">
          </div>
          <div class="form-group">
            <label class="form-label">Quận/Huyện</label>
            <input type="text" name="district" class="form-control" placeholder="Quận 1">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Ghi chú</label>
          <textarea name="note" class="form-control" rows="3" placeholder="Ghi chú thêm về đơn hàng..."></textarea>
        </div>

        <h3 style="font-family:var(--font-display);font-size:1.3rem;margin:24px 0 16px">
          <i class="fas fa-credit-card" style="color:var(--primary)"></i> Phương Thức Thanh Toán
        </h3>
        <?php foreach ([
          ['cod','fas fa-money-bill-wave','Thanh Toán Khi Nhận Hàng (COD)','An toàn, không cần trả trước'],
          ['bank','fas fa-university','Chuyển Khoản Ngân Hàng','Chuyển khoản theo thông tin bên dưới'],
          ['momo','fas fa-wallet','Ví MoMo','Quét mã QR để thanh toán']
        ] as $pm): ?>
        <label style="display:flex;align-items:center;gap:16px;padding:16px;border:1.5px solid var(--border);border-radius:var(--radius-md);cursor:pointer;margin-bottom:10px;transition:border-color 0.3s">
          <input type="radio" name="payment_method" value="<?= $pm[0] ?>" <?= $pm[0]==='cod'?'checked':'' ?> style="accent-color:var(--primary);width:18px;height:18px">
          <i class="<?= $pm[1] ?>" style="color:var(--primary);font-size:1.2rem;width:24px"></i>
          <div><strong style="font-size:0.9rem"><?= $pm[2] ?></strong><br><span style="font-size:0.8rem;color:var(--text-muted)"><?= $pm[3] ?></span></div>
        </label>
        <?php endforeach; ?>
      </div>

      <!-- Summary -->
      <div style="background:var(--bg-card);border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow-sm);position:sticky;top:160px">
        <h3 style="font-family:var(--font-display);font-size:1.2rem;margin-bottom:16px">Đơn Hàng</h3>
        <?php foreach ($cartItems as $item): ?>
        <div style="display:flex;gap:12px;align-items:center;margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid var(--border)">
          <img src="<?= $item['thumbnail'] ? uploadUrl($item['thumbnail']) : 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=60' ?>"
               style="width:52px;height:52px;object-fit:cover;border-radius:var(--radius-sm)">
          <div style="flex:1;min-width:0">
            <p style="font-size:0.8rem;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= e($item['name']) ?></p>
            <p style="font-size:0.75rem;color:var(--text-muted)">x<?= $item['quantity'] ?></p>
          </div>
          <span style="font-weight:600;color:var(--primary);font-size:0.9rem;flex-shrink:0">
            <?= formatPrice($item['price'] * $item['quantity']) ?>
          </span>
        </div>
        <?php endforeach; ?>
        <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:16px">
          <div style="display:flex;justify-content:space-between;font-size:0.85rem">
            <span style="color:var(--text-muted)">Tạm tính</span><span><?= formatPrice($subtotal) ?></span>
          </div>
          <?php if ($discount > 0): ?>
          <div style="display:flex;justify-content:space-between;font-size:0.85rem">
            <span style="color:var(--text-muted)">Giảm giá</span>
            <span style="color:#e53e3e">-<?= formatPrice($discount) ?></span>
          </div>
          <?php endif; ?>
          <div style="display:flex;justify-content:space-between;font-size:0.85rem">
            <span style="color:var(--text-muted)">Phí vận chuyển</span>
            <span><?= $shippingFee > 0 ? formatPrice($shippingFee) : '<span style="color:#4caf50">Miễn phí</span>' ?></span>
          </div>
          <div style="border-top:1px solid var(--border);padding-top:10px;display:flex;justify-content:space-between;font-size:1.1rem;font-weight:700">
            <span>Tổng</span><span style="color:var(--primary)"><?= formatPrice($total) ?></span>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">
          <i class="fas fa-check-circle"></i> Đặt Hàng
        </button>
      </div>
    </div>
  </form>
</div>
</section>
<style>@media(max-width:768px){div[style*="grid-template-columns:1fr 380px"]{grid-template-columns:1fr!important;}}</style>
