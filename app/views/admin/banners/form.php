<?php 
// app/views/admin/banners/form.php
$isEdit = isset($banner);
$actionUrl = $isEdit ? url("admin/banners/edit/{$banner['id']}") : url('admin/banners/create');
?>

<div class="admin-card" style="max-width:800px;margin:0 auto">
  <div class="admin-card-header">
    <h2 class="admin-card-title"><?= $isEdit ? 'Sửa Banner' : 'Thêm Banner Mới' ?></h2>
  </div>
  
  <div class="admin-card-body" style="padding:24px">
    <form method="POST" action="<?= $actionUrl ?>" enctype="multipart/form-data">
      
      <div class="form-group" style="margin-bottom:16px">
        <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Tiêu Đề</label>
        <input type="text" name="title" class="form-control-admin" value="<?= $isEdit ? e($banner['title'] ?? '') : '' ?>">
      </div>

      <div class="form-group" style="margin-bottom:16px">
        <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Tiêu Đề Phụ (Subtitle)</label>
        <input type="text" name="subtitle" class="form-control-admin" value="<?= $isEdit ? e($banner['subtitle'] ?? '') : '' ?>">
      </div>

      <div class="form-group" style="margin-bottom:16px">
        <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Đường Dẫn (Link)</label>
        <input type="text" name="link" class="form-control-admin" value="<?= $isEdit ? e($banner['link'] ?? '') : '' ?>" placeholder="https://...">
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
        <div class="form-group">
          <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Vị Trí</label>
          <select name="position" class="form-control-admin">
            <option value="hero" <?= ($isEdit && $banner['position'] == 'hero') ? 'selected' : '' ?>>Hero Slider (Trang Chủ)</option>
            <option value="middle" <?= ($isEdit && $banner['position'] == 'middle') ? 'selected' : '' ?>>Giữa trang</option>
            <option value="sidebar" <?= ($isEdit && $banner['position'] == 'sidebar') ? 'selected' : '' ?>>Sidebar</option>
          </select>
        </div>
        <div class="form-group">
          <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Thứ Tự Sắp Xếp</label>
          <input type="number" name="sort_order" class="form-control-admin" value="<?= $isEdit ? ($banner['sort_order'] ?? 0) : ($nextSortOrder ?? 0) ?>">
        </div>
      </div>

      <div class="form-group" style="margin-bottom:20px">
        <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Hình Ảnh <span style="color:red">*</span></label>
        <?php if ($isEdit && !empty($banner['image'])): ?>
          <div style="margin-bottom:12px">
            <img src="<?= uploadUrl($banner['image']) ?>" alt="Current Banner" style="width:100%;max-width:300px;border-radius:8px;border:1px solid var(--border)">
          </div>
        <?php endif; ?>
        <input type="file" name="image" class="form-control-admin" accept="image/*" <?= !$isEdit ? 'required' : '' ?>>
      </div>

      <div class="form-group" style="margin-bottom:20px">
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer">
          <input type="checkbox" name="is_active" value="1" <?= ($isEdit && $banner['is_active']) ? 'checked' : (!$isEdit ? 'checked' : '') ?>>
          <span style="font-weight:600;font-size:0.875rem">Hiển thị banner này</span>
        </label>
      </div>
      
      <div style="margin-top:30px;padding-top:20px;border-top:1px solid var(--border);text-align:right">
        <a href="<?= url('admin/banners') ?>" class="btn-admin" style="background:#f1f5f9;color:#475569;margin-right:12px">Hủy Bỏ</a>
        <button type="submit" class="btn-admin btn-admin-primary" style="padding:10px 24px">
          <i class="fas fa-save"></i> <?= $isEdit ? 'Cập Nhật' : 'Lưu Banner' ?>
        </button>
      </div>
    </form>
  </div>
</div>
