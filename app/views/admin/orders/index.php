<?php // app/views/admin/orders/index.php ?>

<div class="admin-card">
  <div class="admin-card-header" style="display:flex;justify-content:space-between;align-items:center;padding:20px 24px">
    <h2 class="admin-card-title" style="font-family:var(--font-display);font-size:1.5rem">Đơn Hàng</h2>
    
    <form method="GET" style="display:flex;gap:10px">
      <input type="hidden" name="page" value="1">
      <select name="status" class="form-control-admin" style="width:150px" onchange="this.form.submit()">
        <option value="">Tất cả trạng thái</option>
        <option value="pending" <?= ($_GET['status']??'') == 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
        <option value="confirmed" <?= ($_GET['status']??'') == 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
        <option value="shipping" <?= ($_GET['status']??'') == 'shipping' ? 'selected' : '' ?>>Đang giao</option>
        <option value="delivered" <?= ($_GET['status']??'') == 'delivered' ? 'selected' : '' ?>>Đã giao</option>
        <option value="cancelled" <?= ($_GET['status']??'') == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
      </select>
      <input type="text" name="search" class="form-control-admin" placeholder="Mã ĐH, Tên, SĐT..." value="<?= e($_GET['search']??'') ?>" style="width:200px">
      <button type="submit" class="btn-admin btn-admin-primary"><i class="fas fa-search"></i></button>
    </form>
  </div>

  <div class="admin-card-body">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Mã ĐH</th>
          <th>Khách Hàng</th>
          <th>Ngày Đặt</th>
          <th>Tổng Tiền</th>
          <th>Thanh Toán</th>
          <th>Trạng Thái</th>
          <th style="width:80px">Xem</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $o): ?>
        <?php 
          $statusColors = [
            'pending'   => ['#fef3c7', '#d97706'],
            'confirmed' => ['#e0f2fe', '#0284c7'],
            'shipping'  => ['#f3e8ff', '#9333ea'],
            'delivered' => ['#dcfce7', '#16a34a'],
            'cancelled' => ['#fee2e2', '#dc2626'],
          ];
          $statusLabels = [
            'pending'   => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'shipping'  => 'Đang giao',
            'delivered' => 'Đã giao',
            'cancelled' => 'Đã hủy',
          ];
          $color = $statusColors[$o['status']] ?? ['#f1f5f9', '#475569'];
          $label = $statusLabels[$o['status']] ?? ucfirst($o['status']);
        ?>
        <tr>
          <td style="font-weight:600;color:var(--primary)">#<?= $o['order_code'] ?></td>
          <td>
            <div style="font-weight:600"><?= e($o['name']) ?></div>
            <div style="font-size:0.75rem;color:var(--text-muted)"><?= e($o['phone']) ?></div>
          </td>
          <td style="font-size:0.85rem"><?= date('H:i d/m/Y', strtotime($o['created_at'])) ?></td>
          <td style="font-weight:600;color:var(--primary)"><?= formatPrice($o['total']) ?></td>
          <td style="font-size:0.8rem"><?= strtoupper($o['payment_method']) ?></td>
          <td>
            <span class="badge" style="background:<?= $color[0] ?>;color:<?= $color[1] ?>">
              <?= $label ?>
            </span>
          </td>
          <td>
            <a href="<?= url('admin/orders/detail/'.$o['id']) ?>" class="btn-admin btn-admin-info" style="padding:6px 10px">
              <i class="fas fa-eye"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
        
        <?php if (empty($orders)): ?>
        <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted)">Không tìm thấy đơn hàng nào!</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <?php 
    // Pagination Logic
    if (isset($total) && isset($perPage)):
        $totalPages = ceil($total / $perPage);
        if ($totalPages > 1): 
          $searchQuery = '';
          if (!empty($_GET['search'])) $searchQuery .= '&search=' . urlencode($_GET['search']);
          if (!empty($_GET['status'])) $searchQuery .= '&status=' . urlencode($_GET['status']);
    ?>
    <div style="padding: 20px 24px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
      <div style="font-size: 0.85rem; color: var(--text-muted);">
        Hiển thị <?= count($orders) ?> / <?= $total ?> đơn hàng
      </div>
      <div style="display: flex; gap: 8px;">
        <?php if ($page > 1): ?>
          <a href="<?= url('admin/orders?page='.($page-1).$searchQuery) ?>" class="btn-admin" style="border: 1px solid var(--border); background: var(--bg-card); padding: 6px 12px; color: var(--text);">
            <i class="fas fa-chevron-left"></i>
          </a>
        <?php endif; ?>

        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
          <a href="<?= url('admin/orders?page='.$i.$searchQuery) ?>" class="btn-admin" style="padding: 6px 12px; <?= $i === $page ? 'background: var(--primary); color: #fff;' : 'border: 1px solid var(--border); background: var(--bg-card); color: var(--text);' ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
          <a href="<?= url('admin/orders?page='.($page+1).$searchQuery) ?>" class="btn-admin" style="border: 1px solid var(--border); background: var(--bg-card); padding: 6px 12px; color: var(--text);">
            <i class="fas fa-chevron-right"></i>
          </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; endif; ?>
  </div>
</div>
