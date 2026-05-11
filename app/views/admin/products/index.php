<?php // app/views/admin/products/index.php ?>

<div class="admin-card">
  <div class="admin-card-header" style="display:flex;justify-content:space-between;align-items:center;padding:20px 24px">
    <h2 class="admin-card-title" style="font-family:var(--font-display);font-size:1.5rem">Danh Sách Sản Phẩm</h2>
    <a href="<?= url('admin/products/create') ?>" class="btn-admin btn-admin-primary">
      <i class="fas fa-plus"></i> Thêm Sản Phẩm
    </a>
  </div>

  <div style="padding:20px 24px;border-bottom:1px solid var(--border)">
    <form method="GET" action="<?= url('admin/products') ?>" style="display:flex;gap:12px;max-width:500px">
      <input type="text" name="search" class="form-control-admin" placeholder="Tìm kiếm sản phẩm..." value="<?= e($_GET['search'] ?? '') ?>">
      <button type="submit" class="btn-admin btn-admin-info"><i class="fas fa-search"></i></button>
    </form>
  </div>

  <div class="admin-card-body">
    <table class="admin-table">
      <thead>
        <tr>
          <th style="width:60px">ID</th>
          <th style="width:80px">Ảnh</th>
          <th>Tên Sản Phẩm</th>
          <th>Danh Mục</th>
          <th>Thương Hiệu</th>
          <th>Giá</th>
          <th>Trạng Thái</th>
          <th style="width:120px">Thao Tác</th>
        </tr>
      </thead>
      <tbody>
        <?php $index = ($page - 1) * $perPage + 1; foreach ($products as $p): ?>
        <tr>
          <td><?= $index++ ?></td>
          <td>
            <?php
              $defaultImg = 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=100&q=80';
              $thumb = !empty($p['thumbnail']) ? uploadUrl($p['thumbnail']) : $defaultImg;
            ?>
            <img src="<?= $thumb ?>" alt="<?= e($p['name']) ?>" style="width:48px;height:48px;object-fit:cover;border-radius:6px;border:1px solid var(--border)">
          </td>
          <td>
            <div style="font-weight:600;color:var(--text);font-size:0.9rem"><?= e($p['name']) ?></div>
            <div style="font-size:0.75rem;color:var(--text-muted)">Kho: <?= $p['stock'] ?> | Đã bán: <?= $p['sold'] ?></div>
          </td>
          <td><span class="badge" style="background:#f3e5f5;color:#9c27b0"><?= e($p['category_name']) ?></span></td>
          <td><?= e($p['brand_name'] ?? 'Khác') ?></td>
          <td>
            <?php if (!empty($p['sale_price'])): ?>
              <div style="font-weight:600;color:var(--primary)"><?= formatPrice($p['sale_price']) ?></div>
              <div style="font-size:0.75rem;text-decoration:line-through;color:var(--text-muted)"><?= formatPrice($p['price']) ?></div>
            <?php else: ?>
              <div style="font-weight:600;color:var(--primary)"><?= formatPrice($p['price']) ?></div>
            <?php endif; ?>
          </td>
          <td>
            <?php if ($p['is_active']): ?>
              <span class="badge" style="background:#e8f5e9;color:#2e7d32">Đang bán</span>
            <?php else: ?>
              <span class="badge" style="background:#fee2e2;color:#e53e3e">Đã ẩn</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="<?= url('admin/products/edit/'.$p['id']) ?>" class="btn-admin btn-admin-info" style="padding:6px 10px;margin-right:4px" title="Sửa">
              <i class="fas fa-edit"></i>
            </a>
            <a href="<?= url('admin/products/delete/'.$p['id']) ?>" class="btn-admin btn-admin-danger" style="padding:6px 10px" title="Xóa"
               onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
              <i class="fas fa-trash"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>

        <?php if (empty($products)): ?>
        <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">Không tìm thấy sản phẩm nào!</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    
    <?php 
    // Pagination Logic
    $totalPages = ceil($total / $perPage);
    if ($totalPages > 1): 
      $searchQuery = !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '';
    ?>
    <div style="padding: 20px 24px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
      <div style="font-size: 0.85rem; color: var(--text-muted);">
        Hiển thị <?= count($products) ?> / <?= $total ?> sản phẩm
      </div>
      <div style="display: flex; gap: 8px;">
        <?php if ($page > 1): ?>
          <a href="<?= url('admin/products?page='.($page-1).$searchQuery) ?>" class="btn-admin" style="border: 1px solid var(--border); background: var(--bg-card); padding: 6px 12px; color: var(--text);">
            <i class="fas fa-chevron-left"></i>
          </a>
        <?php endif; ?>

        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
          <a href="<?= url('admin/products?page='.$i.$searchQuery) ?>" class="btn-admin" style="padding: 6px 12px; <?= $i === $page ? 'background: var(--primary); color: #fff;' : 'border: 1px solid var(--border); background: var(--bg-card); color: var(--text);' ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
          <a href="<?= url('admin/products?page='.($page+1).$searchQuery) ?>" class="btn-admin" style="border: 1px solid var(--border); background: var(--bg-card); padding: 6px 12px; color: var(--text);">
            <i class="fas fa-chevron-right"></i>
          </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
