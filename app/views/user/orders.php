<?php // app/views/user/orders.php ?>
<h2 style="font-family:var(--font-display);font-size:1.5rem;margin-bottom:24px;border-bottom:1px solid var(--border);padding-bottom:16px">Lịch Sử Đơn Hàng</h2>

<?php if (empty($orders)): ?>
<div style="text-align:center;padding:40px 20px;color:var(--text-muted)">
  <i class="fas fa-shopping-bag" style="font-size:3rem;margin-bottom:16px;opacity:0.2"></i>
  <p>Bạn chưa có đơn hàng nào.</p>
  <a href="<?= url('products') ?>" class="btn btn-primary" style="margin-top:16px">Mua sắm ngay</a>
</div>
<?php else: ?>
<div style="overflow-x:auto">
  <table style="width:100%;border-collapse:collapse;font-size:0.9rem">
    <thead>
      <tr style="background:var(--bg-section);text-align:left;color:var(--text-muted)">
        <th style="padding:12px;border-bottom:1px solid var(--border)">Mã Đơn</th>
        <th style="padding:12px;border-bottom:1px solid var(--border)">Ngày Đặt</th>
        <th style="padding:12px;border-bottom:1px solid var(--border)">Tổng Tiền</th>
        <th style="padding:12px;border-bottom:1px solid var(--border)">Trạng Thái</th>
        <th style="padding:12px;border-bottom:1px solid var(--border);text-align:right">Thao Tác</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($orders as $order): ?>
      <tr style="border-bottom:1px solid var(--border)">
        <td style="padding:16px 12px"><strong style="color:var(--primary)"><?= e($order['order_code']) ?></strong></td>
        <td style="padding:16px 12px;color:var(--text-muted)"><?= formatDate($order['created_at']) ?></td>
        <td style="padding:16px 12px;font-weight:600"><?= formatPrice($order['total']) ?></td>
        <td style="padding:16px 12px"><?= orderStatusLabel($order['status']) ?></td>
        <td style="padding:16px 12px;text-align:right">
          <a href="<?= url('user/orders/' . $order['id']) ?>" class="btn btn-outline btn-sm">Xem Chi Tiết</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>