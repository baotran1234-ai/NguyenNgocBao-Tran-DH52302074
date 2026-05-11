<?php 
// app/views/admin/categories/form.php
$isEdit = isset($category);
$actionUrl = $isEdit ? url("admin/categories/edit/{$category['id']}") : url('admin/categories/create');
?>

<div class="admin-card" style="max-width:800px;margin:0 auto">
  <div class="admin-card-header">
    <h2 class="admin-card-title"><?= $isEdit ? 'Sửa Danh Mục' : 'Thêm Danh Mục Mới' ?></h2>
  </div>
  
  <div class="admin-card-body" style="padding:24px">
    <form method="POST" action="<?= $actionUrl ?>">
      
      <div class="form-group" style="margin-bottom:16px">
        <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Tên Danh Mục <span style="color:red">*</span></label>
        <input type="text" name="name" class="form-control-admin" required value="<?= $isEdit ? e($category['name'] ?? '') : '' ?>">
      </div>

      <div class="form-group" style="margin-bottom:16px">
        <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Đường Dẫn (Slug)</label>
        <input type="text" name="slug" class="form-control-admin" value="<?= $isEdit ? e($category['slug'] ?? '') : '' ?>" placeholder="Để trống để tự tạo từ tên danh mục">
      </div>

      <div class="form-group" style="margin-bottom:16px">
        <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Mô Tả</label>
        <textarea name="description" class="form-control-admin" rows="3"><?= $isEdit ? e($category['description'] ?? '') : '' ?></textarea>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px">
        <div class="form-group">
          <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Thứ Tự Sắp Xếp</label>
          <input type="number" name="sort_order" class="form-control-admin" value="<?= $isEdit ? ($category['sort_order'] ?? 0) : 0 ?>">
          <small style="color:var(--text-muted);display:block;margin-top:6px">Số nhỏ sẽ hiển thị trước.</small>
        </div>
        <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:12px">
          <label style="display:flex;align-items:center;gap:10px;cursor:pointer">
            <input type="checkbox" name="is_active" value="1" <?= ($isEdit && $category['is_active']) ? 'checked' : (!$isEdit ? 'checked' : '') ?>>
            <span style="font-weight:600;font-size:0.875rem">Hiển thị danh mục</span>
          </label>
        </div>
      </div>
      
      <div style="margin-top:30px;padding-top:20px;border-top:1px solid var(--border);text-align:right">
        <a href="<?= url('admin/categories') ?>" class="btn-admin" style="background:#f1f5f9;color:#475569;margin-right:12px">Hủy Bỏ</a>
        <button type="submit" class="btn-admin btn-admin-primary" style="padding:10px 24px">
          <i class="fas fa-save"></i> <?= $isEdit ? 'Cập Nhật' : 'Lưu Danh Mục' ?>
        </button>
      </div>
    </form>
  </div>
</div>
