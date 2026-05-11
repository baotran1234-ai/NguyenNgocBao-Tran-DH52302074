<?php // app/views/admin/orders/detail.php ?>
<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px">
  
  <div>
    <!-- Danh sách sản phẩm -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h2 class="admin-card-title">Sản Phẩm Đã Đặt</h2>
      </div>
      <div class="admin-card-body">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Ảnh</th>
              <th>Sản Phẩm</th>
              <th>Đơn Giá</th>
              <th>SL</th>
              <th style="text-align:right">Thành Tiền</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($order['items'] as $item): ?>
            <tr>
              <td><img src="<?= uploadUrl($item['thumbnail']) ?>" style="width:50px;height:50px;object-fit:cover;border-radius:4px"></td>
              <td><div style="font-weight:600;font-size:0.85rem"><?= e($item['name']) ?></div></td>
              <td><?= formatPrice($item['price']) ?></td>
              <td><?= $item['quantity'] ?></td>
              <td style="text-align:right;font-weight:600"><?= formatPrice($item['subtotal']) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4" style="text-align:right;padding:12px">Tạm tính:</td>
              <td style="text-align:right;padding:12px"><?= formatPrice($order['subtotal']) ?></td>
            </tr>
            <?php if ($order['discount'] > 0): ?>
            <tr>
              <td colspan="4" style="text-align:right;padding:12px">Giảm giá:</td>
              <td style="text-align:right;padding:12px;color:red">-<?= formatPrice($order['discount']) ?></td>
            </tr>
            <?php endif; ?>
            <tr>
              <td colspan="4" style="text-align:right;padding:12px">Phí ship:</td>
              <td style="text-align:right;padding:12px"><?= formatPrice($order['shipping_fee']) ?></td>
            </tr>
            <tr style="font-weight:700;font-size:1.1rem;background:#f8fafc">
              <td colspan="4" style="text-align:right;padding:12px">TỔNG CỘNG:</td>
              <td style="text-align:right;padding:12px;color:var(--primary)"><?= formatPrice($order['total']) ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>

  <div>
    <!-- Cập nhật trạng thái -->
    <div class="admin-card" style="margin-bottom:24px">
      <div class="admin-card-header"><h3 style="font-size:1rem">Trạng Thái Đơn Hàng</h3></div>
      <div class="admin-card-body" style="padding:20px">
        <form method="POST">
          <div class="form-group" style="margin-bottom:12px">
            <select name="status" class="form-control-admin">
              <option value="pending" <?= $order['status']=='pending'?'selected':'' ?>>Chờ xử lý</option>
              <option value="confirmed" <?= $order['status']=='confirmed'?'selected':'' ?>>Đã xác nhận</option>
              <option value="shipping" <?= $order['status']=='shipping'?'selected':'' ?>>Đang giao</option>
              <option value="delivered" <?= $order['status']=='delivered'?'selected':'' ?>>Đã giao</option>
              <option value="cancelled" <?= $order['status']=='cancelled'?'selected':'' ?>>Đã hủy</option>
            </select>
          </div>
          <div id="cancel-reason" style="display:<?= $order['status']=='cancelled'?'block':'none' ?>;margin-bottom:12px">
            <label style="font-size:0.8rem">Lý do hủy:</label>
            <textarea name="cancel_reason" class="form-control-admin" rows="2"><?= e($order['cancel_reason']??'') ?></textarea>
          </div>
          <button type="submit" class="btn-admin btn-admin-primary" style="width:100%">Cập Nhật Trạng Thái</button>
        </form>
      </div>
    </div>

    <!-- Thông tin khách hàng -->
    <div class="admin-card">
      <div class="admin-card-header"><h3 style="font-size:1rem">Thông Tin Khách Hàng</h3></div>
      <div class="admin-card-body" style="padding:20px;font-size:0.9rem;line-height:1.8">
        <p><strong>Họ tên:</strong> <?= e($order['name']) ?></p>
        <p><strong>SĐT:</strong> <?= e($order['phone']) ?></p>
        <p><strong>Email:</strong> <?= e($order['email']) ?></p>
        <hr style="margin:12px 0;border-top:1px solid var(--border)">
        <p><strong>Địa chỉ:</strong> <?= e(implode(', ', array_filter([$order['address'], $order['ward'], $order['district'], $order['city']]))) ?></p>
        <?php if($order['note']): ?>
          <p><strong>Ghi chú:</strong> <em><?= e($order['note']) ?></em></p>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>

<script>
document.querySelector('select[name="status"]').addEventListener('change', function() {
  document.getElementById('cancel-reason').style.display = this.value === 'cancelled' ? 'block' : 'none';
});
</script>
