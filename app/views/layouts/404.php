<?php // app/views/layouts/404.php ?>
<!DOCTYPE html><html lang="vi" data-theme="light">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>404 - Không Tìm thấy | LUXE Beauty</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head><body>
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:40px">
  <div>
    <div style="font-family:var(--font-display);font-size:8rem;font-weight:700;color:var(--primary);line-height:1;opacity:0.3">404</div>
    <h1 style="font-family:var(--font-display);font-size:2rem;margin-bottom:12px">Trang Không Tìm thấy</h1>
    <p style="color:var(--text-muted);margin-bottom:32px">Trang bạn Ä‘ang Tìm kiếm Không tá»“n tại hoặc Ä‘ã bá»‹ di chuyá»ƒn.</p>
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
      <a href="<?= url('') ?>" class="btn btn-primary"><i class="fas fa-home"></i> Trang Chủ</a>
      <a href="<?= url('products') ?>" class="btn btn-outline">Xem sản phẩm</a>
    </div>
  </div>
</div>
</body></html>
