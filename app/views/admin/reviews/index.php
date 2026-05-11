<?php // app/views/admin/reviews/index.php ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h2 class="admin-card-title" style="font-family:var(--font-display);font-size:1.5rem">Đánh Giá Sản Phẩm</h2>
  </div>

  <div class="admin-card-body">
    <table class="admin-table">
      <thead>
        <tr>
          <th style="width:60px">ID</th>
          <th>Khách Hàng</th>
          <th>Sản Phẩm</th>
          <th>Đánh Giá</th>
          <th>Nội Dung</th>
          <th>Ngày Gửi</th>
          <th>Trạng Thái</th>
          <th style="width:100px">Thao Tác</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reviews as $r): ?>
        <tr>
          <td><?= $r['id'] ?></td>
          <td>
            <div style="font-weight:600"><?= e($r['user_name']) ?></div>
          </td>
          <td>
            <div style="font-size:0.85rem;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="<?= e($r['product_name']) ?>">
              <?= e($r['product_name']) ?>
            </div>
          </td>
          <td>
            <div style="color:#f59e0b">
              <?php for($i=1;$i<=5;$i++): ?>
                <i class="<?= $i <= $r['rating'] ? 'fas' : 'far' ?> fa-star"></i>
              <?php endfor; ?>
            </div>
          </td>
          <td>
            <div style="font-weight:600;font-size:0.8rem"><?= e($r['title'] ?? '') ?></div>
            <div style="font-size:0.75rem;color:var(--text-muted);max-width:300px"><?= e($r['content']) ?></div>
          </td>
          <td style="font-size:0.8rem"><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
          <td>
            <?php if ($r['is_active']): ?>
              <span class="badge" style="background:#e8f5e9;color:#2e7d32">Hiện</span>
            <?php else: ?>
              <span class="badge" style="background:#fee2e2;color:#e53e3e">Ẩn</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="<?= url('admin/reviews/toggle/'.$r['id']) ?>" class="btn-admin btn-admin-info" style="padding:6px 10px;margin-right:4px"
               title="<?= $r['is_active'] ? 'Ẩn' : 'Hiện' ?>">
              <i class="fas fa-eye<?= $r['is_active'] ? '-slash' : '' ?>"></i>
            </a>
            <a href="<?= url('admin/reviews/delete/'.$r['id']) ?>" class="btn-admin btn-admin-danger" style="padding:6px 10px" title="Xóa"
               onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?')">
              <i class="fas fa-trash"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
        
        <?php if (empty($reviews)): ?>
        <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">Chưa có đánh giá nào!</td></tr>
        <?php endif; ?>
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
        Hiển thị <?= count($reviews) ?> / <?= $total ?> đánh giá
      </div>
      <div style="display: flex; gap: 8px;">
        <?php if ($page > 1): ?>
          <a href="<?= url('admin/reviews?page='.($page-1)) ?>" class="btn-admin" style="border: 1px solid var(--border); background: var(--bg-card); padding: 6px 12px; color: var(--text);">
            <i class="fas fa-chevron-left"></i>
          </a>
        <?php endif; ?>

        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
          <a href="<?= url('admin/reviews?page='.$i) ?>" class="btn-admin" style="padding: 6px 12px; <?= $i === $page ? 'background: var(--primary); color: #fff;' : 'border: 1px solid var(--border); background: var(--bg-card); color: var(--text);' ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
          <a href="<?= url('admin/reviews?page='.($page+1)) ?>" class="btn-admin" style="border: 1px solid var(--border); background: var(--bg-card); padding: 6px 12px; color: var(--text);">
            <i class="fas fa-chevron-right"></i>
          </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; endif; ?>
  </div>
</div>
