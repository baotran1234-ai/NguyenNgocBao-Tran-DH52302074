<?php
// app/views/products/_card.php - Product card dùng chung
$isWishlisted = false;
if (isLoggedIn() && isset($p['id'])) {
    $wCheck = db()->prepare("SELECT 1 FROM wishlist WHERE user_id=:uid AND product_id=:pid");
    $wCheck->execute([':uid'=>$_SESSION['user_id'],':pid'=>$p['id']]);
    $isWishlisted = (bool)$wCheck->fetch();
}
$effectivePrice = $p['sale_price'] ?? $p['price'];
$hasDiscount    = !empty($p['sale_price']) && $p['sale_price'] < $p['price'];
$discount       = $hasDiscount ? discountPercent($p['price'], $p['sale_price']) : 0;
$thumbnail      = $p['thumbnail'] ? uploadUrl($p['thumbnail'])
                : 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=400&q=80';
?>
<div class="product-card" data-product-id="<?= $p['id'] ?>">
  <div class="product-img-wrap">
    <img src="<?= $thumbnail ?>" data-src="<?= $thumbnail ?>" alt="<?= e($p['name']) ?>" loading="lazy" class="lazy">

    <div class="product-badges">
      <?php if ($hasDiscount): ?><span class="badge badge-sale">-<?= $discount ?>%</span><?php endif; ?>
      <?php if (!empty($p['is_new'])): ?><span class="badge badge-new">Mới</span><?php endif; ?>
      <?php if (!empty($p['is_featured'])): ?><span class="badge badge-hot">Hot</span><?php endif; ?>
      <?php if (($p['stock'] ?? 1) < 1): ?><span class="badge" style="background:#666">Hết hàng</span><?php endif; ?>
    </div>

    <div class="product-actions">
      <button class="action-btn wishlist-btn <?= $isWishlisted ? 'active' : '' ?>"
              data-id="<?= $p['id'] ?>" title="Yêu thích">
        <i class="fa<?= $isWishlisted ? 's' : 'r' ?> fa-heart"></i>
      </button>
      <a href="<?= url('products/' . $p['slug']) ?>" class="action-btn" title="Xem nhanh">
        <i class="fas fa-eye"></i>
      </a>
    </div>
  </div>

  <div class="product-info">
    <?php if (!empty($p['brand_name'])): ?>
    <div class="product-brand"><?= e($p['brand_name']) ?></div>
    <?php endif; ?>

    <h3 class="product-name">
      <a href="<?= url('products/' . $p['slug']) ?>"><?= e($p['name']) ?></a>
    </h3>

    <div class="product-rating">
      <div class="stars">
        <?php
        $r = round($p['rating'] ?? 0);
        for ($s=1;$s<=5;$s++) echo $s<=$r ? '★' : '☆';
        ?>
      </div>
      <span class="rating-count">(<?= $p['review_count'] ?? 0 ?>)</span>
    </div>

    <div class="product-price">
      <span class="price-current"><?= formatPrice($effectivePrice) ?></span>
      <?php if ($hasDiscount): ?>
      <span class="price-old"><?= formatPrice($p['price']) ?></span>
      <span class="price-badge">-<?= $discount ?>%</span>
      <?php endif; ?>
    </div>

    <button class="add-to-cart-btn"
            data-id="<?= $p['id'] ?>"
            <?= ($p['stock'] ?? 1) < 1 ? 'disabled style="opacity:0.5;cursor:not-allowed"' : '' ?>>
      <i class="fas fa-shopping-bag"></i>
      <?= ($p['stock'] ?? 1) < 1 ? 'Hết Hàng' : 'Thêm Vào Giỏ' ?>
    </button>
  </div>
</div>
