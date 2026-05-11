<?php // app/views/products/index.php
$categories = $categories ?? [];
$brands = $brands ?? [];
$products = $products ?? [];
$pagination = $pagination ?? ['total'=>0, 'total_pages'=>1, 'current'=>1, 'has_prev'=>false, 'has_next'=>false];
?>
<div style="background:var(--bg-section);padding:20px 0">
  <div class="container">
    <nav class="breadcrumb">
      <a href="<?= url('') ?>">Trang Chủ</a>
      <span class="breadcrumb-sep">/</span>
      <span><?= $pageTitle ?? 'Sản Phẩm' ?></span>
    </nav>
  </div>
</div>

<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:260px 1fr;gap:32px;align-items:start">

      <!-- SIDEBAR LỌC -->
      <aside class="filter-sidebar" id="filterSidebar">
        <div style="background:var(--bg-card);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow-sm)">
          <h3 style="font-family:var(--font-display);font-size:1.2rem;margin-bottom:20px">Bộ Lọc</h3>

          <form id="filterForm" method="GET" action="">
            <!-- Danh mục -->
            <div class="form-group">
              <label class="form-label">Danh Mục</label>
              <?php foreach ($categories as $cat): ?>
              <label style="display:flex;align-items:center;gap:8px;margin-bottom:8px;cursor:pointer;font-size:0.875rem">
                <input type="radio" name="category" value="<?= $cat['slug'] ?>"
                  <?= ($_GET['category'] ?? '') === $cat['slug'] ? 'checked' : '' ?>
                  onchange="this.form.submit()">
                <?= e($cat['name']) ?>
              </label>
              <?php endforeach; ?>
              <label style="display:flex;align-items:center;gap:8px;margin-top:4px;cursor:pointer;font-size:0.875rem">
                <input type="radio" name="category" value="" <?= empty($_GET['category']) ? 'checked' : '' ?> onchange="this.form.submit()">
                Tất cả
              </label>
            </div>

            <!-- Thương hiệu -->
            <div class="form-group">
              <label class="form-label">Thương Hiệu</label>
              <select name="brand" class="form-control form-select" onchange="this.form.submit()">
                <option value="">Tất cả</option>
                <?php foreach ($brands as $b): ?>
                <option value="<?= $b['slug'] ?>" <?= ($_GET['brand']??'') === $b['slug'] ? 'selected' : '' ?>>
                  <?= e($b['name']) ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Giá -->
            <div class="form-group">
              <label class="form-label">Khoảng Giá</label>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                <input type="number" name="min_price" class="form-control" placeholder="Từ" value="<?= $_GET['min_price'] ?? '' ?>">
                <input type="number" name="max_price" class="form-control" placeholder="Đến" value="<?= $_GET['max_price'] ?? '' ?>">
              </div>
            </div>

            <!-- Sắp xếp -->
            <div class="form-group">
              <label class="form-label">Sắp Xếp</label>
              <select name="sort" class="form-control form-select" onchange="this.form.submit()">
                <option value="newest"     <?= ($_GET['sort']??'newest')==='newest'     ? 'selected' : '' ?>>Mới nhất</option>
                <option value="popular"    <?= ($_GET['sort']??'')==='popular'    ? 'selected' : '' ?>>Phổ biến nhất</option>
                <option value="price_asc"  <?= ($_GET['sort']??'')==='price_asc'  ? 'selected' : '' ?>>Giá: Thấp → Cao</option>
                <option value="price_desc" <?= ($_GET['sort']??'')==='price_desc' ? 'selected' : '' ?>>Giá: Cao → Thấp</option>
                <option value="rating"     <?= ($_GET['sort']??'')==='rating'     ? 'selected' : '' ?>>Đánh Giá cao nhất</option>
              </select>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Áp Dụng</button>
            <a href="<?= url('products') ?>" class="btn btn-outline btn-block" style="margin-top:8px">Xóa Bộ Lọc</a>
          </form>
        </div>
      </aside>

      <!-- PRODUCT LIST -->
      <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
          <p style="color:var(--text-muted);font-size:0.9rem">
            Tìm thấy <strong><?= $pagination['total'] ?></strong> sản phẩm
          </p>
        </div>

        <?php if (empty($products)): ?>
        <div style="text-align:center;padding:80px 20px;color:var(--text-muted)">
          <i class="fas fa-search" style="font-size:3rem;margin-bottom:16px;opacity:0.3"></i>
          <p style="font-size:1.1rem">Không tìm thấy sản phẩm phù hợp</p>
          <a href="<?= url('products') ?>" class="btn btn-primary" style="margin-top:16px">Xem Tất Cả</a>
        </div>
        <?php else: ?>
        <div class="product-grid" id="productGrid">
          <?php foreach ($products as $p): ?>
          <?php include __DIR__ . '/_card.php'; ?>
          <?php endforeach; ?>
        </div>

        <!-- PAGINATION -->
        <?php if ($pagination['total_pages'] > 1): ?>
        <div class="pagination">
          <?php if ($pagination['has_prev']): ?>
          <a href="<?= sprintf($pagination['url_pattern'], $pagination['prev_page']) ?>" class="page-btn">
            <i class="fas fa-chevron-left"></i>
          </a>
          <?php endif; ?>

          <?php for ($i = max(1, $pagination['current']-2); $i <= min($pagination['total_pages'], $pagination['current']+2); $i++): ?>
          <a href="<?= sprintf($pagination['url_pattern'], $i) ?>"
             class="page-btn <?= $i === $pagination['current'] ? 'active' : '' ?>">
            <?= $i ?>
          </a>
          <?php endfor; ?>

          <?php if ($pagination['has_next']): ?>
          <a href="<?= sprintf($pagination['url_pattern'], $pagination['next_page']) ?>" class="page-btn">
            <i class="fas fa-chevron-right"></i>
          </a>
          <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<style>
@media(max-width:768px){
  div[style*="grid-template-columns:260px"]{grid-template-columns:1fr!important;}
  #filterSidebar{display:none;}
}
</style>
