<?php 
// app/views/admin/products/form.php
$isEdit = isset($product);
$actionUrl = $isEdit ? url("admin/products/edit/{$product['id']}") : url('admin/products/create');
?>

<div class="admin-card">
  <div class="admin-card-header">
    <h2 class="admin-card-title"><?= $isEdit ? 'Sửa sản phẩm' : 'Thêm sản phẩm Mới' ?></h2>
  </div>
  
  <div class="admin-card-body" style="padding:24px">
    <form method="POST" action="<?= $actionUrl ?>" enctype="multipart/form-data">
      
      <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px">
        <!-- Cột trái: Thông tin chính -->
        <div>
          <div class="form-group" style="margin-bottom:16px">
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Tên sản phẩm <span style="color:red">*</span></label>
            <input type="text" name="name" class="form-control-admin" required value="<?= $isEdit ? e($product['name'] ?? '') : '' ?>">
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div class="form-group">
              <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Danh Mục <span style="color:red">*</span></label>
              <select name="category_id" class="form-control-admin" required>
                <option value="">-- Chọn danh mục --</option>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['id'] ?>" <?= ($isEdit && $product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                    <?= e($cat['name'] ?? '') ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Thương Hiệu</label>
              <select name="brand_id" class="form-control-admin">
                <option value="">-- Chọn thương hiệu --</option>
                <?php foreach ($brands as $b): ?>
                  <option value="<?= $b['id'] ?>" <?= ($isEdit && $product['brand_id'] == $b['id']) ? 'selected' : '' ?>>
                    <?= e($b['name'] ?? '') ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px">
            <div class="form-group">
              <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Giá Bán (VNĐ) <span style="color:red">*</span></label>
              <input type="number" name="price" class="form-control-admin" required value="<?= $isEdit ? $product['price'] : '' ?>">
            </div>
            <div class="form-group">
              <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Giá Khuyến Mãi (VNĐ)</label>
              <input type="number" name="sale_price" class="form-control-admin" value="<?= $isEdit ? $product['sale_price'] : '' ?>">
            </div>
            <div class="form-group">
              <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Tồn Kho</label>
              <input type="number" name="stock" class="form-control-admin" value="<?= $isEdit ? $product['stock'] : '0' ?>">
            </div>
          </div>

          <div class="form-group" style="margin-bottom:16px">
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Mô tả sản phẩm</label>
            <textarea name="description" class="form-control-admin" rows="5"><?= $isEdit ? e($product['description'] ?? '') : '' ?></textarea>
          </div>
          
          <div class="form-group" style="margin-bottom:16px">
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Thành phần</label>
            <textarea name="ingredients" class="form-control-admin" rows="3"><?= $isEdit ? e($product['ingredients'] ?? '') : '' ?></textarea>
          </div>

          <div class="form-group" style="margin-bottom:16px">
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Hướng dẫn sử dụng</label>
            <textarea name="how_to_use" class="form-control-admin" rows="3"><?= $isEdit ? e($product['how_to_use'] ?? '') : '' ?></textarea>
          </div>
        </div>

        <!-- Cột phải: Ảnh và Option -->
        <div>
          <div class="admin-card" style="margin-bottom:20px;box-shadow:none;border:1px solid var(--border)">
            <div class="admin-card-header" style="padding:12px 16px;background:var(--bg)"><h3 style="font-size:0.9rem">Hình Ảnh</h3></div>
            <div class="admin-card-body" style="padding:16px">
              <?php if ($isEdit && !empty($product['thumbnail'])): ?>
                <div style="margin-bottom:12px">
                  <img src="<?= uploadUrl($product['thumbnail']) ?>" alt="Thumbnail" style="width:100%;border-radius:8px;border:1px solid var(--border)">
                </div>
              <?php endif; ?>
              <input type="file" name="thumbnail" class="form-control-admin" accept="image/*" <?= !$isEdit ? 'required' : '' ?>>
              <small style="color:var(--text-muted);display:block;margin-top:6px">Chọn ảnh đại diện cho sản phẩm.</small>
            </div>
          </div>

          <div class="admin-card" style="margin-bottom:20px;box-shadow:none;border:1px solid var(--border)">
            <div class="admin-card-header" style="padding:12px 16px;background:var(--bg)"><h3 style="font-size:0.9rem">Trạng Thái & Nổi Bật</h3></div>
            <div class="admin-card-body" style="padding:16px">
              <label style="display:flex;align-items:center;gap:10px;margin-bottom:12px;cursor:pointer">
                <input type="checkbox" name="is_featured" value="1" <?= ($isEdit && $product['is_featured']) ? 'checked' : '' ?>>
                <span>Sản phẩm Hot / Nổi bật</span>
              </label>
              <label style="display:flex;align-items:center;gap:10px;cursor:pointer">
                <input type="checkbox" name="is_new" value="1" <?= ($isEdit && $product['is_new']) ? 'checked' : (!$isEdit ? 'checked' : '') ?>>
                <span>Sản phẩm Mới</span>
              </label>
            </div>
          </div>

          <div class="admin-card" style="box-shadow:none;border:1px solid var(--border)">
            <div class="admin-card-header" style="padding:12px 16px;background:var(--bg)"><h3 style="font-size:0.9rem">SEO (Tùy chọn)</h3></div>
            <div class="admin-card-body" style="padding:16px">
              <div class="form-group" style="margin-bottom:12px">
                <label style="display:block;margin-bottom:6px;font-size:0.8rem">Đường dẫn (Slug)</label>
                <input type="text" name="slug" class="form-control-admin" value="<?= $isEdit ? e($product['slug'] ?? '') : '' ?>" placeholder="Để trống để tự tạo">
              </div>
              <div class="form-group" style="margin-bottom:12px">
                <label style="display:block;margin-bottom:6px;font-size:0.8rem">SKU (Mã SP)</label>
                <input type="text" name="sku" class="form-control-admin" value="<?= $isEdit ? e($product['sku'] ?? '') : '' ?>">
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div style="margin-top:30px;padding-top:20px;border-top:1px solid var(--border);text-align:right">
        <a href="<?= url('admin/products') ?>" class="btn-admin" style="background:#f1f5f9;color:#475569;margin-right:12px">Hủy Bỏ</a>
        <button type="submit" class="btn-admin btn-admin-primary" style="padding:10px 24px">
          <i class="fas fa-save"></i> <?= $isEdit ? 'Cập Nhật' : 'Lưu sản phẩm' ?>
        </button>
      </div>
    </form>
  </div>
</div>
