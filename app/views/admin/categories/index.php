<?php // app/views/admin/categories/index.php ?>

<div class="admin-card">
  <div class="admin-card-header" style="display:flex;justify-content:space-between;align-items:center;padding:20px 24px">
    <h2 class="admin-card-title" style="font-family:var(--font-display);font-size:1.5rem">Danh Mục Sản Phẩm</h2>
    <a href="<?= url('admin/categories/create') ?>" class="btn-admin btn-admin-primary">
      <i class="fas fa-plus"></i> Thêm Danh Mục
    </a>
  </div>

  <div class="admin-card-body">
    <table class="admin-table">
      <thead>
        <tr>
          <th style="width:60px">ID</th>
          <th>Tên Danh Mục</th>
          <th>Đường Dẫn (Slug)</th>
          <th>Sắp Xếp</th>
          <th>Trạng Thái</th>
          <th style="width:120px">Thao Tác</th>
        </tr>
      </thead>
      <tbody>
        <?php $index = ($page - 1) * $perPage + 1; foreach ($categories as $c): ?>
        <tr>
          <td><?= $index++ ?></td>
          <td>
            <div style="font-weight:600;color:var(--text);font-size:0.9rem"><?= e($c['name']) ?></div>
            <?php if(!empty($c['description'])): ?>
              <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px"><?= e($c['description']) ?></div>
            <?php endif; ?>
          </td>
          <td style="color:var(--text-muted);font-size:0.85rem"><?= e($c['slug']) ?></td>
          <td><?= $c['sort_order'] ?></td>
          <td>
            <?php if ($c['is_active']): ?>
              <span class="badge" style="background:#e8f5e9;color:#2e7d32">Hiển thị</span>
            <?php else: ?>
              <span class="badge" style="background:#fee2e2;color:#e53e3e">Đã ẩn</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="<?= url('admin/categories/edit/'.$c['id']) ?>" class="btn-admin btn-admin-info" style="padding:6px 10px;margin-right:4px" title="Sửa">
              <i class="fas fa-edit"></i>
            </a>
            <a href="<?= url('admin/categories/delete/'.$c['id']) ?>" class="btn-admin btn-admin-danger" style="padding:6px 10px" title="Xóa"
               onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Lưu ý: Các sản phẩm thuộc danh mục này có thể bị lỗi!')">
              <i class="fas fa-trash"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
        
        <?php if (empty($categories)): ?>
        <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted)">Chưa có danh mục nào!</td></tr>
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
        Hiển thị <?= count($categories) ?> / <?= $total ?> danh mục
      </div>
      <div style="display: flex; gap: 8px;">
        <?php if ($page > 1): ?>
          <a href="<?= url('admin/categories?page='.($page-1)) ?>" class="btn-admin" style="border: 1px solid var(--border); background: var(--bg-card); padding: 6px 12px; color: var(--text);">
            <i class="fas fa-chevron-left"></i>
          </a>
        <?php endif; ?>

        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
          <a href="<?= url('admin/categories?page='.$i) ?>" class="btn-admin" style="padding: 6px 12px; <?= $i === $page ? 'background: var(--primary); color: #fff;' : 'border: 1px solid var(--border); background: var(--bg-card); color: var(--text);' ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
          <a href="<?= url('admin/categories?page='.($page+1)) ?>" class="btn-admin" style="border: 1px solid var(--border); background: var(--bg-card); padding: 6px 12px; color: var(--text);">
            <i class="fas fa-chevron-right"></i>
          </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; endif; ?>
  </div>
</div>
