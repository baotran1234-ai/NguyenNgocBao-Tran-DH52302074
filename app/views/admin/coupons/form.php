<?php 
// app/views/admin/coupons/form.php
$isEdit = isset($coupon);
$actionUrl = $isEdit ? url("admin/coupons/edit/{$coupon['id']}") : url('admin/coupons/create');
?>

<div class="admin-card" style="max-width:800px;margin:0 auto">
  <div class="admin-card-header">
    <h2 class="admin-card-title"><?= $isEdit ? 'Sửa Mã Giảm Giá' : 'Thêm Mã Mới' ?></h2>
  </div>
  
  <div class="admin-card-body" style="padding:24px">
    <form method="POST" action="<?= $actionUrl ?>">
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
        <div class="form-group">
          <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Mã Giảm Giá <span style="color:red">*</span></label>
          <input type="text" name="code" class="form-control-admin" required value="<?= $isEdit ? e($coupon['code']) : '' ?>" placeholder="VD: LUXE2024">
        </div>
        <div class="form-group">
          <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Tên Gợi Nhớ</label>
          <input type="text" name="name" class="form-control-admin" value="<?= $isEdit ? e($coupon['name'] ?? '') : '' ?>" placeholder="VD: Giảm giá hè">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
        <div class="form-group">
          <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Loại Giảm Giá</label>
          <select name="type" class="form-control-admin">
            <option value="percent" <?= ($isEdit && $coupon['type'] == 'percent') ? 'selected' : '' ?>>Phần trăm (%)</option>
            <option value="fixed" <?= ($isEdit && $coupon['type'] == 'fixed') ? 'selected' : '' ?>>Cố định (₫)</option>
          </select>
        </div>
        <div class="form-group">
          <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Giá Trị Giảm <span style="color:red">*</span></label>
          <input type="number" name="value" class="form-control-admin" required value="<?= $isEdit ? $coupon['value'] : '' ?>">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
        <div class="form-group">
          <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Đơn tối thiểu</label>
          <input type="number" name="min_order" class="form-control-admin" value="<?= $isEdit ? $coupon['min_order'] : '0' ?>">
        </div>
        <div class="form-group">
          <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Giảm tối đa (cho %)</label>
          <input type="number" name="max_discount" class="form-control-admin" value="<?= $isEdit ? $coupon['max_discount'] : '' ?>">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
        <div class="form-group">
          <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Ngày bắt đầu</label>
          <input type="date" name="starts_at" class="form-control-admin" value="<?= ($isEdit && $coupon['starts_at']) ? date('Y-m-d', strtotime($coupon['starts_at'])) : '' ?>">
        </div>
        <div class="form-group">
          <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Ngày kết thúc</label>
          <input type="date" name="expires_at" class="form-control-admin" value="<?= ($isEdit && $coupon['expires_at']) ? date('Y-m-d', strtotime($coupon['expires_at'])) : '' ?>">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px">
        <div class="form-group">
          <label style="display:block;margin-bottom:8px;font-weight:600;font-size:0.875rem">Giới hạn lượt dùng</label>
          <input type="number" name="max_use" class="form-control-admin" value="<?= $isEdit ? $coupon['max_use'] : '' ?>" placeholder="Để trống nếu không giới hạn">
        </div>
        <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:12px">
          <label style="display:flex;align-items:center;gap:10px;cursor:pointer">
            <input type="checkbox" name="is_active" value="1" <?= ($isEdit && $coupon['is_active']) ? 'checked' : (!$isEdit ? 'checked' : '') ?>>
            <span style="font-weight:600">Kích hoạt mã</span>
          </label>
        </div>
      </div>
      
      <div style="margin-top:30px;padding-top:20px;border-top:1px solid var(--border);text-align:right">
        <a href="<?= url('admin/coupons') ?>" class="btn-admin" style="background:#f1f5f9;color:#475569;margin-right:12px">Hủy Bỏ</a>
        <button type="submit" class="btn-admin btn-admin-primary" style="padding:10px 24px">
          <i class="fas fa-save"></i> <?= $isEdit ? 'Cập Nhật' : 'Lưu Mã' ?>
        </button>
      </div>
    </form>
  </div>
</div>
