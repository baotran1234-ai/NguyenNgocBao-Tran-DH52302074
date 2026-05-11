<?php // app/views/user/order_detail.php ?>
<div style="display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--border);padding-bottom:16px;margin-bottom:24px">
  <h2 style="font-family:var(--font-display);font-size:1.5rem">Chi Tiết Đơn Hàng #<?= e($order['order_code']) ?></h2>
  <a href="<?= url('user/orders') ?>" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Trá»Ÿ lại</a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:32px;align-items:start">
  <!-- Items -->
  <div>
    <h3 style="font-size:1.1rem;margin-bottom:16px">sản phẩm Đã Mua</h3>
    <div style="background:var(--bg-section);border-radius:var(--radius-md);overflow:hidden">
      <?php foreach ($items as $item): ?>
      <div style="display:flex;align-items:center;gap:16px;padding:16px;border-bottom:1px solid var(--border)">
        <img src="<?= $item['thumbnail'] ? uploadUrl($item['thumbnail']) : 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=80' ?>" style="width:60px;height:60px;object-fit:cover;border-radius:var(--radius-sm)">
        <div style="flex:1">
          <a href="<?= url('products/'.$item['slug']) ?>" style="font-weight:600;font-size:0.9rem;display:block;margin-bottom:4px"><?= e($item['product_name']) ?></a>
          <span style="font-size:0.8rem;color:var(--text-muted)">Sá»‘ lượng: <?= $item['quantity'] ?></span>
        </div>
        <div style="font-weight:600;color:var(--primary)"><?= formatPrice($item['price'] * $item['quantity']) ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Summary Info -->
  <div style="display:flex;flex-direction:column;gap:24px">
    <div style="background:var(--bg-section);padding:24px;border-radius:var(--radius-md)">
      <h3 style="font-size:1.1rem;margin-bottom:16px">Thông Tin Đơn Hàng</h3>
      <div style="display:flex;flex-direction:column;gap:12px;font-size:0.9rem">
        <div style="display:flex;justify-content:space-between"><span style="color:var(--text-muted)">Ngày Ä‘ặt:</span> <span><?= formatDate($order['created_at']) ?></span></div>
        <div style="display:flex;justify-content:space-between"><span style="color:var(--text-muted)">Trạng thái:</span> <span><?= orderStatusLabel($order['status']) ?></span></div>
        <div style="display:flex;justify-content:space-between"><span style="color:var(--text-muted)">Thanh toán:</span> <span><?= $order['payment_method']==='cod'?'COD - Nhận hàng trả tiền':ucfirst($order['payment_method']) ?></span></div>
      </div>
      
      <div style="margin-top:20px;padding-top:20px;border-top:1px solid var(--border)">
        <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:0.9rem"><span style="color:var(--text-muted)">Tạm tính:</span> <span><?= formatPrice($order['subtotal']) ?></span></div>
        <?php if ($order['discount'] > 0): ?>
        <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:0.9rem"><span style="color:var(--text-muted)">Giảm giá:</span> <span style="color:#e53e3e">-<?= formatPrice($order['discount']) ?></span></div>
        <?php endif; ?>
        <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:0.9rem"><span style="color:var(--text-muted)">Phí vận chuyá»ƒn:</span> <span><?= $order['shipping_fee']>0 ? formatPrice($order['shipping_fee']) : 'Miá»…n phí' ?></span></div>
        <div style="display:flex;justify-content:space-between;margin-top:12px;padding-top:12px;border-top:1px solid var(--border);font-size:1.1rem;font-weight:700">
          <span>Tá»•ng Cá»™ng:</span> <span style="color:var(--primary)"><?= formatPrice($order['total']) ?></span>
        </div>
      </div>
    </div>

    <div style="background:var(--bg-section);padding:24px;border-radius:var(--radius-md)">
      <h3 style="font-size:1.1rem;margin-bottom:16px">Thông Tin Nhận Hàng</h3>
      <div style="font-size:0.9rem;line-height:1.6">
        <p><strong>Người nhận:</strong> <?= e($order['name']) ?></p>
        <p><strong>SĐT:</strong> <?= e($order['phone']) ?></p>
        <p><strong>Đá»‹a chá»‰:</strong> <?= e($order['address']) ?></p>
        <?php if (!empty($order['note'])): ?>
        <p><strong>Ghi chú:</strong> <?= nl2br(e($order['note'])) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<style>@media(max-width:768px){div[style*="grid-template-columns:2fr 1fr"]{grid-template-columns:1fr!important;}}</style>
