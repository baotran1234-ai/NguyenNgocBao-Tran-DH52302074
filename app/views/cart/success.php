<?php // app/views/cart/success.php ?>
<section style="min-height:70vh;display:flex;align-items:center;padding:60px 0;background:var(--bg-section)">
  <div class="container" style="max-width:640px">
    <div style="background:var(--bg-card);border-radius:var(--radius-lg);padding:50px 40px;box-shadow:var(--shadow-md)">

      <!-- Icon thành công -->
      <div style="text-align:center;margin-bottom:28px">
        <div style="width:88px;height:88px;background:linear-gradient(135deg,#4caf50,#81c784);border-radius:50%;margin:0 auto 20px;display:flex;align-items:center;justify-content:center;font-size:2.2rem;color:#fff;animation:successPop 0.6s cubic-bezier(0.175,0.885,0.32,1.275)">
          <i class="fas fa-check"></i>
        </div>
        <h1 style="font-family:var(--font-display);font-size:2rem;color:#2e7d32;margin-bottom:8px">Đặt Hàng Thành Công! 🎉</h1>
        <p style="color:var(--text-muted)">Cảm ơn bạn đã tin tưởng LUXE Beauty. Đơn hàng đang được xử lý.</p>
      </div>

      <?php if ($order): ?>
      <!-- Thông tin đơn hàng -->
      <div style="background:var(--bg-section);border-radius:var(--radius-md);padding:20px;margin-bottom:24px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
          <div>
            <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">Mã đơn hàng</div>
            <div style="font-weight:700;color:var(--primary);font-size:1rem"><?= e($order['order_code']) ?></div>
          </div>
          <div>
            <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">Tổng tiền</div>
            <div style="font-weight:700;font-size:1rem"><?= formatPrice($order['total']) ?></div>
          </div>
          <div>
            <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">Thanh toán</div>
            <div style="font-weight:500;font-size:0.9rem">
              <?php $pmLabels = ['cod'=>'💵 COD - Khi nhận hàng','bank'=>'🏦 Chuyển khoản','momo'=>'💜 Ví MoMo']; ?>
              <?= $pmLabels[$order['payment_method']] ?? ucfirst($order['payment_method']) ?>
            </div>
          </div>
          <div>
            <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">Trạng thái</div>
            <span style="background:#fff3e0;color:#e65100;padding:4px 12px;border-radius:999px;font-size:0.8rem;font-weight:600">⏳ Đang xử lý</span>
          </div>
        </div>

        <div style="border-top:1px solid var(--border);margin-top:16px;padding-top:16px">
          <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:6px">Giao đến</div>
          <div style="font-size:0.9rem"><?= e($order['name']) ?> · <?= e($order['phone']) ?></div>
          <div style="font-size:0.875rem;color:var(--text-muted);margin-top:2px">
            <?= e($order['address']) ?><?= $order['district'] ? ', ' . e($order['district']) : '' ?><?= $order['city'] ? ', ' . e($order['city']) : '' ?>
          </div>
        </div>
      </div>

      <!-- Sản phẩm đặt -->
      <?php if (!empty($order['items'])): ?>
      <div style="margin-bottom:24px">
        <div style="font-weight:600;font-size:0.9rem;margin-bottom:12px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px">Sản phẩm đã đặt</div>
        <?php foreach ($order['items'] as $item): ?>
        <div style="display:flex;gap:12px;align-items:center;padding:10px 0;border-bottom:1px solid var(--border)">
          <img src="<?= $item['thumbnail'] ? uploadUrl($item['thumbnail']) : 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=60' ?>"
               style="width:48px;height:48px;object-fit:cover;border-radius:var(--radius-sm);flex-shrink:0">
          <div style="flex:1;min-width:0">
            <div style="font-size:0.875rem;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= e($item['name']) ?></div>
            <div style="font-size:0.8rem;color:var(--text-muted)">x<?= $item['quantity'] ?></div>
          </div>
          <div style="font-weight:600;color:var(--primary);font-size:0.9rem;flex-shrink:0"><?= formatPrice($item['subtotal']) ?></div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?php if ($order['payment_method'] === 'bank'): ?>
      <!-- Thông tin chuyển khoản -->
      <div style="background:linear-gradient(135deg,#e3f2fd,#f3e5f5);border-radius:var(--radius-md);padding:20px;margin-bottom:24px;border-left:4px solid #2196f3">
        <div style="font-weight:600;margin-bottom:12px;color:#1565c0">🏦 Thông Tin Chuyển Khoản</div>
        <div style="font-size:0.875rem;display:flex;flex-direction:column;gap:6px">
          <div><span style="color:var(--text-muted)">Ngân hàng:</span> <strong>Vietcombank</strong></div>
          <div><span style="color:var(--text-muted)">Số TK:</span> <strong>1234567890</strong></div>
          <div><span style="color:var(--text-muted)">Chủ TK:</span> <strong>LUXE BEAUTY CO., LTD</strong></div>
          <div><span style="color:var(--text-muted)">Nội dung:</span> <strong style="color:var(--primary)"><?= e($order['order_code']) ?></strong></div>
          <div><span style="color:var(--text-muted)">Số tiền:</span> <strong style="color:#e53e3e"><?= formatPrice($order['total']) ?></strong></div>
        </div>
      </div>
      <?php endif; ?>

      <p style="font-size:0.8rem;color:var(--text-muted);text-align:center;margin-bottom:24px">
        <i class="fas fa-envelope"></i> Xác nhận đơn hàng sẽ gửi về email <strong><?= e($order['email'] ?? '') ?></strong>
      </p>
      <?php endif; ?>

      <!-- Buttons -->
      <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
        <a href="<?= url('') ?>" class="btn btn-primary"><i class="fas fa-home"></i> Về Trang Chủ</a>
        <?php if (isLoggedIn()): ?>
        <a href="<?= url('user/orders') ?>" class="btn btn-outline"><i class="fas fa-list"></i> Xem Đơn Hàng</a>
        <?php endif; ?>
        <a href="<?= url('products') ?>" class="btn btn-outline" style="border-color:var(--border)"><i class="fas fa-shopping-bag"></i> Mua Thêm</a>
      </div>
    </div>
  </div>
</section>
<style>
@keyframes successPop { from{transform:scale(0);opacity:0} to{transform:scale(1);opacity:1} }
</style>
