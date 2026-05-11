<?php // app/views/admin/dashboard.php ?>

<!-- Stats Grid -->
<div class="stats-grid">
  <?php
  $statCards = [
    ['label'=>'Tổng Doanh Thu','value'=>formatPrice($stats['total_revenue']),'sub'=>'Đã giao hàng','icon'=>'fas fa-dollar-sign','color'=>'#c9a96e','bg'=>'#fdf5e6'],
    ['label'=>'Hôm Nay','value'=>formatPrice($stats['today_revenue']),'sub'=>$stats['today_orders'].' đơn hôm nay','icon'=>'fas fa-chart-line','color'=>'#4caf50','bg'=>'#e8f5e9'],
    ['label'=>'Tổng Đơn Hàng','value'=>number_format($stats['total_orders']),'sub'=>$stats['pending_orders'].' đơn chờ xử lý','icon'=>'fas fa-shopping-cart','color'=>'#2196f3','bg'=>'#e3f2fd'],
    ['label'=>'Sản Phẩm','value'=>number_format($stats['total_products']),'sub'=>'Sản phẩm đang bán','icon'=>'fas fa-box','color'=>'#9c27b0','bg'=>'#f3e5f5'],
    ['label'=>'Khách Hàng','value'=>number_format($stats['total_users']),'sub'=>'Thành viên đăng ký','icon'=>'fas fa-users','color'=>'#d4849a','bg'=>'#fce4ec'],
  ];
  foreach ($statCards as $card): ?>
  <div class="stat-card">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px">
      <div>
        <p class="stat-label"><?= $card['label'] ?></p>
        <p class="stat-value"><?= $card['value'] ?></p>
        <p class="stat-sub"><?= $card['sub'] ?></p>
      </div>
      <div class="stat-icon" style="background:<?= $card['bg'] ?>;color:<?= $card['color'] ?>">
        <i class="<?= $card['icon'] ?>"></i>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Charts + Quick Actions -->
<div style="display:grid;grid-template-columns:1fr 380px;gap:24px;margin-bottom:24px">

  <!-- Revenue Chart -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Doanh Thu 30 Ngày Qua</h3>
      <a href="<?= url('admin/orders') ?>" class="btn-admin btn-admin-info" style="font-size:0.75rem">Xem tất cả</a>
    </div>
    <div style="padding:20px">
      <canvas id="revenueChart" height="80"></canvas>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="admin-card">
    <div class="admin-card-header"><h3 class="admin-card-title">Thao Tác Nhanh</h3></div>
    <div style="padding:16px;display:flex;flex-direction:column;gap:10px">
      <?php foreach ([
        [url('admin/products/create'),'fas fa-plus-circle','#c9a96e','#fdf5e6','Thêm Sản Phẩm Mới'],
        [url('admin/orders'),'fas fa-list','#2196f3','#e3f2fd','Xem Đơn Hàng Mới'],
        [url('admin/coupons/create'),'fas fa-ticket-alt','#9c27b0','#f3e5f5','Tạo Mã Giảm Giá'],
        [url('admin/banners/create'),'fas fa-images','#4caf50','#e8f5e9','Thêm Banner'],
      ] as $qa): ?>
      <a href="<?= $qa[0] ?>" style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:10px;border:1.5px solid var(--border);transition:all 0.2s;color:var(--text)">
        <div style="width:36px;height:36px;background:<?= $qa[3] ?>;border-radius:8px;display:flex;align-items:center;justify-content:center;color:<?= $qa[2] ?>">
          <i class="<?= $qa[1] ?>"></i>
        </div>
        <span style="font-weight:500;font-size:0.875rem"><?= $qa[4] ?></span>
        <i class="fas fa-chevron-right" style="margin-left:auto;opacity:0.3"></i>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Recent Orders -->
<div class="admin-card">
  <div class="admin-card-header">
    <h3 class="admin-card-title">Đơn Hàng Gần Đây</h3>
    <a href="<?= url('admin/orders') ?>" class="btn-admin btn-admin-primary">Xem Tất Cả</a>
  </div>
  <div class="admin-card-body">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Mã Đơn</th><th>Khách Hàng</th><th>Tổng Tiền</th>
          <th>Thanh Toán</th><th>Trạng Thái</th><th>Ngày Đặt</th><th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recentOrders as $order): ?>
        <tr>
          <td><strong style="color:var(--primary)"><?= e($order['order_code']) ?></strong></td>
          <td>
            <div style="font-weight:500;font-size:0.875rem"><?= e($order['name']) ?></div>
            <div style="font-size:0.75rem;color:var(--text-muted)"><?= e($order['phone']) ?></div>
          </td>
          <td style="font-weight:700;color:var(--primary)"><?= formatPrice($order['total']) ?></td>
          <td>
            <?php $pm=['cod'=>'💵 COD','bank'=>'🏦 Bank','momo'=>'💜 MoMo'][$order['payment_method']] ?? $order['payment_method']; ?>
            <span style="font-size:0.8rem"><?= $pm ?></span>
          </td>
          <td><?= orderStatusLabel($order['status']) ?></td>
          <td style="font-size:0.8rem;color:var(--text-muted)"><?= formatDate($order['created_at'],'d/m/Y H:i') ?></td>
          <td>
            <a href="<?= url('admin/orders/detail/' . $order['id']) ?>" class="btn-admin btn-admin-info">
              <i class="fas fa-eye"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($recentOrders)): ?>
        <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted)">Chưa có đơn hàng nào</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Chart Script -->
<script>
const ctx = document.getElementById('revenueChart');
if (ctx) {
  const chartData = <?= json_encode($revenueChart) ?>;
  const labels = chartData.map(d => {
    const date = new Date(d.date);
    return `${date.getDate()}/${date.getMonth()+1}`;
  });
  const revenues = chartData.map(d => parseFloat(d.revenue));

  new Chart(ctx, {
    type: 'line',
    data: {
      labels,
      datasets: [{
        label: 'Doanh Thu',
        data: revenues,
        borderColor: '#c9a96e',
        backgroundColor: 'rgba(201,169,110,0.1)',
        borderWidth: 2.5,
        fill: true,
        tension: 0.4,
        pointBackgroundColor: '#c9a96e',
        pointRadius: 4,
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: { ticks: { callback: v => (v/1000000).toFixed(1) + 'M₫' }, grid: { color: 'rgba(0,0,0,0.05)' } },
        x: { grid: { display: false } }
      }
    }
  });
}
</script>
