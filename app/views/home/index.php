<?php
// app/views/home/index.php - LUXE Beauty Homepage
$catIcons = ['💆','💄','👁️','🌸','🌺','💇','🧼','☀️'];
?>

<!-- ===== HERO SLIDER ===== -->
<section class="hero-slider" id="heroSlider" style="margin-top:0">
  <div class="slider-track" id="sliderTrack">

    <?php if (!empty($banners)): ?>
      <?php foreach ($banners as $i => $b): ?>
      <div class="slide <?= $i===0?'active':'' ?>">
        <img src="<?= uploadUrl($b['image']) ?>" alt="<?= e($b['title']??'') ?>" class="slide-bg lazy">
        <div class="slide-overlay">
          <div class="slide-content">
            <p class="slide-tag">✨ Bộ sưu tập mới</p>
            <h1 class="slide-title"><?= e($b['title']??'LUXE Beauty') ?></h1>
            <p class="slide-desc"><?= e($b['subtitle']??'Nâng tầm vẻ đẹp Việt') ?></p>
            <div class="slide-btns">
              <a href="<?= url('products') ?>" class="btn btn-primary btn-lg">Mua Ngay</a>
              <a href="<?= url('products?sale=1') ?>" class="btn-ghost-white">Xem Sale 🔥</a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    <?php else: ?>

      <div class="slide active">
        <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=1400&q=80"
             alt="LUXE Beauty" class="slide-bg">
        <div class="slide-overlay">
          <div class="slide-content">
            <p class="slide-tag">✨ Bộ sưu tập Hè 2025</p>
            <h1 class="slide-title">Vẻ Đẹp Đích Thực<br>Từ Thiên Nhiên</h1>
            <p class="slide-desc">Khám phá hàng ngàn sản phẩm mỹ phẩm cao cấp, chính hãng tại LUXE Beauty</p>
            <div class="slide-btns">
              <a href="<?= url('products') ?>" class="btn btn-primary btn-lg">Mua Ngay</a>
              <a href="<?= url('products?sale=1') ?>" class="btn-ghost-white">Xem Sale 🔥</a>
            </div>
          </div>
        </div>
      </div>

      <div class="slide">
        <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=1400&q=80"
             alt="Skincare" class="slide-bg">
        <div class="slide-overlay">
          <div class="slide-content">
            <p class="slide-tag">🌿 Chăm sóc da</p>
            <h1 class="slide-title">Làn Da Rạng Rỡ<br>Mỗi Ngày</h1>
            <p class="slide-desc">Bộ sưu tập dưỡng da cao cấp từ các thương hiệu hàng đầu thế giới</p>
            <div class="slide-btns">
              <a href="<?= url('category/cham-soc-da-mat') ?>" class="btn btn-primary btn-lg">Khám Phá Ngay</a>
            </div>
          </div>
        </div>
      </div>

      <div class="slide">
        <img src="https://images.unsplash.com/photo-1487412947147-5cebf100d7fb?w=1400&q=80"
             alt="Makeup" class="slide-bg">
        <div class="slide-overlay">
          <div class="slide-content">
            <p class="slide-tag">💄 Trang điểm</p>
            <h1 class="slide-title">Tỏa Sáng Mỗi<br>Khoảnh Khắc</h1>
            <p class="slide-desc">Son môi, kem nền, phấn mắt — tất cả chính hãng với giá tốt nhất</p>
            <div class="slide-btns">
              <a href="<?= url('products?category=son-moi') ?>" class="btn btn-primary btn-lg">Xem Ngay</a>
            </div>
          </div>
        </div>
      </div>

    <?php endif; ?>
  </div>

  <button class="slider-btn slider-prev" id="sliderPrev"><i class="fas fa-chevron-left"></i></button>
  <button class="slider-btn slider-next" id="sliderNext"><i class="fas fa-chevron-right"></i></button>
  <div class="slider-dots" id="sliderDots"></div>
</section>

<!-- ===== FEATURES STRIP ===== -->
<section style="background:#1a1814;padding:20px 0">
  <div class="container">
    <div class="features-grid">
      <?php foreach([
        ['fas fa-shipping-fast','Miễn Phí Giao Hàng','Cho đơn từ '.formatPrice(FREE_SHIPPING_OVER)],
        ['fas fa-certificate',  'Hàng Chính Hãng',    '100% Authentic đảm bảo'],
        ['fas fa-undo',         'Đổi Trả Dễ Dàng',    'Trong vòng 7 ngày'],
        ['fas fa-headset',      'Hỗ Trợ 24/7',        'Tư vấn tận tâm'],
      ] as $f): ?>
      <div class="feature-item">
        <i class="<?= $f[0] ?>" style="font-size:1.6rem;color:var(--primary);flex-shrink:0"></i>
        <div>
          <div style="font-weight:600;font-size:0.875rem;color:#fff"><?= $f[1] ?></div>
          <div style="font-size:0.75rem;color:rgba(255,255,255,0.45)"><?= $f[2] ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== CATEGORIES ===== -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">Danh Mục</span>
      <h2 class="section-title">Danh Mục Nổi Bật</h2>
      <div class="section-line"></div>
      <p class="section-desc" style="margin-top:10px">Khám phá thế giới làm đẹp theo cách của bạn</p>
    </div>
    <div class="category-grid">
      <?php foreach ($categories as $i => $cat): ?>
      <a href="<?= url('products?category='.$cat['slug']) ?>" class="category-card">
        <div class="category-icon"><?= $catIcons[$i % count($catIcons)] ?></div>
        <h3 class="category-name"><?= e($cat['name']) ?></h3>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== NEW PRODUCTS ===== -->
<section id="new-products" class="section" style="background:var(--bg-section)">
  <div class="container">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:36px;flex-wrap:wrap;gap:16px">
      <div>
        <span class="section-tag">Mới Nhất</span>
        <h2 class="section-title" style="margin-bottom:0">Sản Phẩm Mới</h2>
      </div>
      <a href="<?= url('products?sort=newest') ?>" class="btn btn-outline">Xem tất cả <i class="fas fa-arrow-right"></i></a>
    </div>
    
    <div class="product-grid" style="margin-bottom:30px">
      <?php foreach ($newProducts as $p): ?>
        <?php include APP_PATH . '/views/products/_card.php'; ?>
      <?php endforeach; ?>
    </div>

    <!-- Phân trang -->
    <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
    <div class="pagination" style="justify-content:center;margin-top:20px">
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
  </div>
</section>

<!-- ===== PROMO BANNER ===== -->
<section class="section">
  <div class="container">
    <div class="promo-grid">
      <!-- Main promo -->
      <div class="promo-banner" style="min-height:300px">
        <img src="<?= uploadUrl('skincare_promo.png') ?>" alt="Skincare">
        <div class="promo-content">
          <span class="promo-tag"><i class="fas fa-star" style="color:#f6c90e"></i> Best Seller</span>
          <h3 class="promo-title">Bộ Dưỡng Da<br>Cao Cấp</h3>
          <p style="margin-bottom:16px;font-size:0.85rem;opacity:0.8">Giảm đến 30% cho combo chăm sóc da</p>
          <a href="<?= url('products?category=cham-soc-da-mat') ?>" class="btn btn-primary">Mua Ngay</a>
        </div>
      </div>
      <!-- Side promos -->
      <div style="display:flex;flex-direction:column;gap:16px">
        <div class="promo-banner" style="min-height:160px;background:linear-gradient(135deg,#c9a96e,#e8b4c8)">
          <div class="promo-content" style="background:none">
            <span class="promo-tag"><i class="fas fa-magic" style="color:var(--primary)"></i> Son môi</span>
            <h3 class="promo-title" style="font-size:1.3rem">Son Lì Giảm 30%</h3>
            <a href="<?= url('products?category=son-moi') ?>" class="btn btn-sm" style="color:#fff;border:1px solid rgba(255,255,255,0.6);border-radius:999px;padding:8px 18px;margin-top:8px;display:inline-flex;align-items:center">Xem ngay</a>
          </div>
        </div>
        <div class="promo-banner" style="min-height:160px;background:linear-gradient(135deg,#1a4a2e,#2e7d50)">
          <div class="promo-content" style="background:none">
            <span class="promo-tag"><i class="fas fa-sun" style="color:#f6c90e"></i> Chống nắng</span>
            <h3 class="promo-title" style="font-size:1.3rem">SPF 50+ Mua 2 Tặng 1</h3>
            <a href="<?= url('products?category=chong-nang') ?>" class="btn btn-sm" style="color:#fff;border:1px solid rgba(255,255,255,0.6);border-radius:999px;padding:8px 18px;margin-top:8px;display:inline-flex;align-items:center">Xem ngay</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== SALE / FEATURED ===== -->
<section id="hot-sale" class="section" style="background:var(--bg-section)">
  <div class="container">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:36px;flex-wrap:wrap;gap:16px">
      <div>
        <span class="section-tag" style="background:#fee2e2;color:#e53e3e">Hot Sale</span>
        <h2 class="section-title" style="margin-bottom:0">Đang Giảm Giá 🔥</h2>
      </div>
      <a href="<?= url('products?sale=1') ?>" class="btn btn-outline">Săn ngay <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="product-grid" style="margin-bottom:30px">
      <?php foreach ($featuredProducts as $p): ?>
        <?php include APP_PATH . '/views/products/_card.php'; ?>
      <?php endforeach; ?>
    </div>

    <!-- Phân trang Đang Giảm Giá -->
    <?php if (isset($featuredPagination) && $featuredPagination['total_pages'] > 1): ?>
    <div class="pagination" style="justify-content:center;margin-top:20px">
      <?php if ($featuredPagination['has_prev']): ?>
      <a href="<?= sprintf($featuredPagination['url_pattern'], $featuredPagination['prev_page']) ?>" class="page-btn">
        <i class="fas fa-chevron-left"></i>
      </a>
      <?php endif; ?>

      <?php for ($i = max(1, $featuredPagination['current']-2); $i <= min($featuredPagination['total_pages'], $featuredPagination['current']+2); $i++): ?>
      <a href="<?= sprintf($featuredPagination['url_pattern'], $i) ?>"
         class="page-btn <?= $i === $featuredPagination['current'] ? 'active' : '' ?>">
        <?= $i ?>
      </a>
      <?php endfor; ?>

      <?php if ($featuredPagination['has_next']): ?>
      <a href="<?= sprintf($featuredPagination['url_pattern'], $featuredPagination['next_page']) ?>" class="page-btn">
        <i class="fas fa-chevron-right"></i>
      </a>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">Đánh Giá</span>
      <h2 class="section-title">Khách Hàng Nói Gì?</h2>
      <div class="section-line"></div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px">
      <?php foreach ([
        ['Nguyễn Thị Mai',  'TP. Hồ Chí Minh', 'Sản phẩm rất tốt, giao hàng nhanh. Mình sẽ tiếp tục ủng hộ shop!',5],
        ['Trần Hương Giang','Hà Nội',           'Tư vấn nhiệt tình, hàng chính hãng 100%. Rất yên tâm khi mua ở LUXE.',5],
        ['Lê Thúy Hằng',   'Đà Nẵng',          'Đóng gói cẩn thận, sản phẩm đúng mô tả. Sẽ giới thiệu bạn bè!',5],
      ] as $t): ?>
      <div style="background:var(--bg-card);padding:28px;border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);border-top:3px solid var(--primary)">
        <div style="color:#f6c90e;font-size:1rem;margin-bottom:12px;letter-spacing:2px"><?= str_repeat('★', $t[3]) ?></div>
        <p style="font-size:0.9rem;line-height:1.8;color:var(--text-muted);margin-bottom:18px;font-style:italic">"<?= $t[2] ?>"</p>
        <div style="display:flex;align-items:center;gap:12px">
          <div style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1rem;flex-shrink:0">
            <?= mb_strtoupper(mb_substr($t[0],0,1,'UTF-8'),'UTF-8') ?>
          </div>
          <div>
            <div style="font-weight:600;font-size:0.9rem"><?= $t[0] ?></div>
            <div style="font-size:0.75rem;color:var(--text-muted)"><?= $t[1] ?></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== NEWSLETTER ===== -->
<section style="background:linear-gradient(135deg,#1a1814 0%,#2d2018 50%,#1a1814 100%);padding:70px 0">
  <div class="container" style="text-align:center;max-width:600px">
    <p style="color:var(--primary);font-size:0.75rem;letter-spacing:4px;text-transform:uppercase;margin-bottom:12px">Ưu Đãi Độc Quyền</p>
    <h2 style="font-family:var(--font-display);font-size:2.2rem;color:#fff;margin-bottom:14px">Đăng Ký Nhận Ưu Đãi</h2>
    <p style="color:rgba(255,255,255,0.45);margin-bottom:32px;font-size:0.9rem;line-height:1.7">
      Nhận ngay mã giảm <strong style="color:var(--primary)">10%</strong> cho đơn hàng đầu tiên khi đăng ký email
    </p>
    <form id="nlForm" style="display:flex;gap:10px;max-width:440px;margin:0 auto">
      <input type="email" placeholder="Nhập email của bạn..." required
        style="flex:1;padding:14px 20px;border-radius:999px;border:1px solid rgba(255,255,255,0.15);background:rgba(255,255,255,0.07);color:#fff;font-size:0.9rem;outline:none">
      <button type="submit" class="btn btn-primary" style="flex-shrink:0;padding:14px 24px">Đăng Ký</button>
    </form>
  </div>
</section>

<!-- Ghost button CSS + Newsletter -->
<style>
.btn-ghost-white {
  display:inline-flex;align-items:center;gap:8px;
  padding:16px 36px;border-radius:999px;
  border:2px solid rgba(255,255,255,0.55);
  color:#fff;font-size:1rem;font-weight:600;
  transition:all 0.3s ease;
  backdrop-filter:blur(4px);
}
.btn-ghost-white:hover {
  background:rgba(255,255,255,0.15);
  border-color:#fff;
}
@media(max-width:768px){
  .testimonials-row { grid-template-columns:1fr!important; }
  .promo-grid { grid-template-columns:1fr!important; }
}
</style>

<script>
document.getElementById('nlForm')?.addEventListener('submit', function(e){
  e.preventDefault();
  if(typeof showToast === 'function') showToast('Cảm ơn bạn đã đăng ký! Kiểm tra email nhé 💌', 'success');
  this.reset();
});
</script>