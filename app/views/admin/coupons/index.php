<?php 
// app/views/admin/coupons/index.php ?>

<div class="admin-card">
  <div class="admin-card-header" style="display:flex;justify-content:space-between;align-items:center;padding:20px 24px">
    <h2 class="admin-card-title" style="font-family:var(--font-display);font-size:1.5rem">Mã Giảm Giá</h2>
    <a href="<?= url('admin/coupons/create') ?>" class="btn-admin btn-admin-primary">
      <i class="fas fa-plus"></i> Thêm Mã
    </a>
  </div>

  <div class="admin-card-body">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Mã</th>
          <th>Tên/Mô Tả</th>
          <th>Loại</th>
          <th>Giá Trị</th>
          <th>Lượt Dùng</th>
          <th>Hết Hạn</th>
          <th>Trạng Thái</th>
          <th style="width:120px">Thao Tác</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($coupons as $c): ?>
        <tr>
          <td style="font-weight:700;color:var(--primary);letter-spacing:1px"><?= e($c['code']) ?></td>
          <td><?= e($c['name'] ?? 'Mã giảm giá') ?></td>
          <td><?= $c['type'] == 'percent' ? 'Phần trăm' : 'Cố định' ?></td>
          <td style="font-weight:600"><?= $c['type'] == 'percent' ? $c['value'].'%' : number_format($c['value']).'₫' ?></td>
          <td><?= $c['used_count'] ?> / <?= $c['max_use'] ?? '∞' ?></td>
          <td style="font-size:0.8rem"><?= $c['expires_at'] ? date('d/m/Y', strtotime($c['expires_at'])) : 'Không' ?></td>
          <td>
            <?php if ($c['is_active']): ?>
              <span class="badge" style="background:#e8f5e9;color:#2e7d32">Kích hoạt</span>
            <?php else: ?>
              <span class="badge" style="background:#fee2e2;color:#e53e3e">Đã ẩn</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="<?= url('admin/coupons/edit/'.$c['id']) ?>" class="btn-admin btn-admin-info" style="padding:6px 10px;margin-right:4px" title="Sửa">
              <i class="fas fa-edit"></i>
            </a>
            <a href="<?= url('admin/coupons/delete/'.$c['id']) ?>" class="btn-admin btn-admin-danger" style="padding:6px 10px" title="Xóa"
               onclick="return confirm('Bạn có chắc chắn muốn xóa mã này?')">
              <i class="fas fa-trash"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <?php 
    // Pagination Logic
    if (isset($total) && isset($perPage)):
        $totalPages = ceil($total / $perPage);
        if ($totalPages > 1): 
    ?>
    <div style="padding: 20px 24px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
      <div style="font-size: 0.85rem; color: var(--text-muted);">
        Hiển thị <?= count($coupons) ?> / <?= $total ?> mã
      </div>
      <div style="display: flex; gap: 8px;">
        <?php if ($page > 1): ?>
          <a href="<?= url('admin/coupons?page='.($page-1)) ?>" class="btn-admin" style="border: 1px solid var(--border); background: var(--bg-card); padding: 6px 12px; color: var(--text);">
            <i class="fas fa-chevron-left"></i>
          </a>
        <?php endif; ?>

        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
          <a href="<?= url('admin/coupons?page='.$i) ?>" class="btn-admin" style="padding: 6px 12px; <?= $i === $page ? 'background: var(--primary); color: #fff;' : 'border: 1px solid var(--border); background: var(--bg-card); color: var(--text);' ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
          <a href="<?= url('admin/coupons?page='.($page+1)) ?>" class="btn-admin" style="border: 1px solid var(--border); background: var(--bg-card); padding: 6px 12px; color: var(--text);">
            <i class="fas fa-chevron-right"></i>
          </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; endif; ?>
  </div>
</div>
