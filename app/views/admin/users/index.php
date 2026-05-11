<?php // app/views/admin/users/index.php ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h2 class="admin-card-title" style="font-family:var(--font-display);font-size:1.5rem">Quản Lý Khách Hàng</h2>
  </div>

  <div class="admin-card-body">
    <table class="admin-table">
      <thead>
        <tr>
          <th style="width:60px">ID</th>
          <th>Ảnh</th>
          <th>Họ Tên</th>
          <th>Email</th>
          <th>Số Điện Thoại</th>
          <th>Ngày Đăng Ký</th>
          <th>Trạng Thái</th>
          <th style="width:100px">Thao Tác</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
          <td><?= $u['id'] ?></td>
          <td>
            <img src="<?= $u['avatar'] ? uploadUrl($u['avatar']) : 'https://ui-avatars.com/api/?name='.urlencode($u['name']) ?>" style="width:40px;height:40px;object-fit:cover;border-radius:50%;border:1px solid var(--border)">
          </td>
          <td>
            <div style="font-weight:600;color:var(--text)"><?= e($u['name']) ?></div>
          </td>
          <td><?= e($u['email']) ?></td>
          <td><?= e($u['phone'] ?? 'Chưa có') ?></td>
          <td style="font-size:0.8rem"><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
          <td>
            <?php if ($u['is_active']): ?>
              <span class="badge" style="background:#e8f5e9;color:#2e7d32">Hoạt động</span>
            <?php else: ?>
              <span class="badge" style="background:#fee2e2;color:#e53e3e">Bị khóa</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="<?= url('admin/users/toggle/'.$u['id']) ?>" class="btn-admin btn-admin-info" style="padding:6px 10px;margin-right:4px" title="<?= $u['is_active'] ? 'Khóa' : 'Mở khóa' ?>">
              <i class="fas fa-user-<?= $u['is_active'] ? 'slash' : 'check' ?>"></i>
            </a>
            <a href="<?= url('admin/users/delete/'.$u['id']) ?>" class="btn-admin btn-admin-danger" style="padding:6px 10px" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
              <i class="fas fa-trash"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
        
        <?php if (empty($users)): ?>
        <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">Chưa có người dùng nào!</td></tr>
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
        Hiển thị <?= count($users) ?> / <?= $total ?> người dùng
      </div>
      <div style="display: flex; gap: 8px;">
        <?php if ($page > 1): ?>
          <a href="<?= url('admin/users?page='.($page-1)) ?>" class="btn-admin" style="border: 1px solid var(--border); background: var(--bg-card); padding: 6px 12px; color: var(--text);">
            <i class="fas fa-chevron-left"></i>
          </a>
        <?php endif; ?>

        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
          <a href="<?= url('admin/users?page='.$i) ?>" class="btn-admin" style="padding: 6px 12px; <?= $i === $page ? 'background: var(--primary); color: #fff;' : 'border: 1px solid var(--border); background: var(--bg-card); color: var(--text);' ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
          <a href="<?= url('admin/users?page='.($page+1)) ?>" class="btn-admin" style="border: 1px solid var(--border); background: var(--bg-card); padding: 6px 12px; color: var(--text);">
            <i class="fas fa-chevron-right"></i>
          </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; endif; ?>
  </div>
</div>
