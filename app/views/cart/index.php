<?php // app/views/cart/index.php ?>
<div style="background:var(--bg-section);padding:20px 0">
  <div class="container">
    <nav class="breadcrumb">
      <a href="<?= url('') ?>">Trang Chủ</a><span class="breadcrumb-sep">/</span>
      <span>Giỏ Hàng</span>
    </nav>
  </div>
</div>

<section class="section">
  <div class="container">
    <h1 style="font-family:var(--font-display);font-size:2rem;margin-bottom:32px">
      Giỏ Hàng <span style="color:var(--text-muted);font-size:1rem;font-weight:400">(<?= count($cartItems) ?> sản phẩm)</span>
    </h1>

    <?php if (empty($cartItems)): ?>
    <div style="text-align:center;padding:80px 20px">
      <i class="fas fa-shopping-bag" style="font-size:4rem;color:var(--primary);opacity:0.3;margin-bottom:20px"></i>
      <h3 style="font-family:var(--font-display);font-size:1.5rem;margin-bottom:12px">Giỏ hàng trống</h3>
      <p style="color:var(--text-muted);margin-bottom:24px">Thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm nhé!</p>
      <a href="<?= url('products') ?>" class="btn btn-primary btn-lg"><i class="fas fa-shopping-bag"></i> Tiếp Tục Mua Sắm</a>
    </div>
    <?php else: ?>

    <div style="display:grid;grid-template-columns:1fr 360px;gap:32px;align-items:start">
      <!-- Cart Items -->
      <div>
        <div style="background:var(--bg-card);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm)">
          <div style="padding:16px 24px;border-bottom:1px solid var(--border);display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:16px;font-size:0.8rem;font-weight:600;color:var(--text-muted);text-transform:uppercase">
            <span>Sản Phẩm</span><span style="text-align:center">Đơn Giá</span>
            <span style="text-align:center">Số Lượng</span><span style="text-align:right">Thành Tiền</span>
          </div>

          <?php foreach ($cartItems as $item): ?>
          <div class="cart-item" data-id="<?= $item['product_id'] ?>"
               style="padding:20px 24px;border-bottom:1px solid var(--border);display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:16px;align-items:center">
            <div style="display:flex;gap:16px;align-items:center">
              <img src="<?= $item['thumbnail'] ? uploadUrl($item['thumbnail']) : 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=80' ?>"
                   alt="<?= e($item['name']) ?>"
                   style="width:72px;height:72px;object-fit:cover;border-radius:var(--radius-md)">
              <div>
                <a href="<?= url('products/' . $item['slug']) ?>" style="font-weight:600;font-size:0.9rem;display:block;margin-bottom:6px"><?= e($item['name']) ?></a>
                <button class="remove-cart-btn" data-id="<?= $item['product_id'] ?>"
                        style="color:#e53e3e;font-size:0.8rem">
                  <i class="fas fa-trash"></i> Xóa
                </button>
              </div>
            </div>
            <div style="text-align:center;color:var(--primary);font-weight:600"><?= formatPrice($item['price']) ?></div>
            <div style="display:flex;align-items:center;justify-content:center">
              <div class="qty-wrap" style="display:flex;align-items:center;border:1.5px solid var(--border);border-radius:var(--radius-full);overflow:hidden">
                <button class="qty-btn" data-action="minus" data-id="<?= $item['product_id'] ?>"
                         style="padding:6px 12px;font-size:1rem">−</button>
                <span class="qty-value" style="padding:6px 12px;min-width:40px;text-align:center;font-weight:600">
                  <?= $item['quantity'] ?>
                </span>
                <button class="qty-btn" data-action="plus" data-id="<?= $item['product_id'] ?>"
                        data-max="<?= $item['stock'] ?>" style="padding:6px 12px;font-size:1rem">+</button>
              </div>
            </div>
            <div class="item-subtotal" style="text-align:right;font-weight:700;color:var(--primary)">
              <?= formatPrice($item['price'] * $item['quantity']) ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div style="margin-top:16px;display:flex;justify-content:space-between">
          <a href="<?= url('products') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Tiếp Tục Mua</a>
        </div>
      </div>

      <!-- Order Summary -->
      <div style="background:var(--bg-card);border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow-sm);position:sticky;top:160px">
        <h3 style="font-family:var(--font-display);font-size:1.3rem;margin-bottom:20px">Tổng Đơn Hàng</h3>

        <!-- Coupon -->
        <div style="display:flex;gap:8px;margin-bottom:20px">
          <input type="text" id="couponInput" class="form-control" placeholder="Mã giảm giá" style="flex:1">
          <button id="applyCoupon" class="btn btn-outline btn-sm">Áp Dụng</button>
        </div>
        <div id="couponMessage" style="font-size:0.8rem;margin-bottom:12px"></div>

        <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:20px">
          <div style="display:flex;justify-content:space-between;font-size:0.9rem">
            <span style="color:var(--text-muted)">Tạm tính</span>
            <span id="cartSubtotal"><?= formatPrice($total) ?></span>
          </div>
          <div style="display:flex;justify-content:space-between;font-size:0.9rem" id="discountRow" style="display:none">
            <span style="color:var(--text-muted)">Giảm giá</span>
            <span style="color:#e53e3e" id="discountValue">-0₫</span>
          </div>
          <div style="display:flex;justify-content:space-between;font-size:0.9rem">
            <span style="color:var(--text-muted)">Phí vận chuyển</span>
            <span><?= $total >= FREE_SHIPPING_OVER ? '<span style="color:#4caf50">Miễn phí</span>' : formatPrice(SHIPPING_FEE) ?></span>
          </div>
          <div style="border-top:1px solid var(--border);padding-top:12px;display:flex;justify-content:space-between;font-size:1.1rem;font-weight:700">
            <span>Tổng cộng</span>
            <span style="color:var(--primary)" id="cartTotal">
              <?= formatPrice($total >= FREE_SHIPPING_OVER ? $total : $total + SHIPPING_FEE) ?>
            </span>
          </div>
        </div>

        <a href="<?= url('cart/checkout') ?>" class="btn btn-primary btn-block btn-lg">
          <i class="fas fa-credit-card"></i> Thanh Toán
        </a>
        <p style="text-align:center;font-size:0.75rem;color:var(--text-muted);margin-top:12px">
          <i class="fas fa-lock"></i> Thanh toán an toàn & bảo mật
        </p>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<script>
// Cart page interactions
document.querySelectorAll('.qty-btn').forEach(btn => {
  btn.addEventListener('click', async () => {
    const id  = btn.dataset.id;
    const max = parseInt(btn.dataset.max || 999);
    const row = btn.closest('.cart-item');
    const qtyEl = row.querySelector('.qty-value');
    let qty = parseInt(qtyEl.textContent);

    if (btn.dataset.action === 'plus')  qty = Math.min(max, qty + 1);
    if (btn.dataset.action === 'minus') qty = Math.max(1, qty - 1);

    const fd = new FormData();
    fd.append('action', 'update');
    fd.append('product_id', id);
    fd.append('quantity', qty);
    const res = await fetch(CART_URL, { method: 'POST', body: fd });
    const data = await res.json();
    if (data.success) {
      qtyEl.textContent = qty;
      if (data.items && data.items[id]) {
        row.querySelector('.item-subtotal').textContent = data.items[id].subtotal;
      }
      document.getElementById('cartBadge').textContent = data.cart_count || '';
    }
  });
});

document.querySelectorAll('.remove-cart-btn').forEach(btn => {
  btn.addEventListener('click', async () => {
    const id = btn.dataset.id;
    const fd = new FormData();
    fd.append('action', 'remove');
    fd.append('product_id', id);
    const res  = await fetch(CART_URL, { method: 'POST', body: fd });
    const data = await res.json();
    if (data.success) {
      btn.closest('.cart-item').remove();
      document.getElementById('cartBadge').textContent = data.cart_count || '';
      showToast('Đã xóa sản phẩm', 'info');
      if (data.cart_count == 0) location.reload();
    }
  });
});

document.getElementById('applyCoupon')?.addEventListener('click', async () => {
  const code    = document.getElementById('couponInput').value.trim();
  const total   = <?= $total ?>;
  const msgEl   = document.getElementById('couponMessage');
  if (!code) return;
  const fd = new FormData();
  fd.append('code', code);
  fd.append('total', total);
  const res  = await fetch('<?= url('api/coupon') ?>', { method: 'POST', body: fd });
  const data = await res.json();
  msgEl.style.color = data.success ? '#4caf50' : '#e53e3e';
  msgEl.textContent = data.message;
  if (data.success) showToast(data.message, 'success');
});
</script>
