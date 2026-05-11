<?php // app/views/user/wishlist.php ?>
<h2 style="font-family:var(--font-display);font-size:1.5rem;margin-bottom:24px;border-bottom:1px solid var(--border);padding-bottom:16px">Sản Phẩm Yêu Thích</h2>

<?php if (empty($products)): ?>
<div style="text-align:center;padding:40px 20px;color:var(--text-muted)">
  <i class="far fa-heart" style="font-size:3rem;margin-bottom:16px;opacity:0.2"></i>
  <p>Danh sách yêu thích của bạn Ä‘ang trá»‘ng.</p>
  <a href="<?= url('products') ?>" class="btn btn-primary" style="margin-top:16px">Khám phá sản phẩm</a>
</div>
<?php else: ?>
<div class="product-grid" style="grid-template-columns:repeat(auto-fill, minmax(200px, 1fr))">
  <?php foreach ($products as $p): ?>
  <?php include APP_PATH . '/views/products/_card.php'; ?>
  <?php endforeach; ?>
</div>
<?php endif; 