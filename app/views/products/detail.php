<?php // app/views/products/detail.php ?>
<div style="background:var(--bg-section);padding:20px 0">
  <div class="container">
    <nav class="breadcrumb">
      <a href="<?= url('') ?>">Trang Chủ</a><span class="breadcrumb-sep">/</span>
      <a href="<?= url('products') ?>">Sản Phẩm</a><span class="breadcrumb-sep">/</span>
      <?php if (!empty($product['category_name'])): ?>
      <a href="<?= url('category/'.$product['category_slug']) ?>"><?= e($product['category_name']) ?></a><span class="breadcrumb-sep">/</span>
      <?php endif; ?>
      <span><?= e($product['name']) ?></span>
    </nav>
  </div>
</div>

<section class="section" style="padding-top:40px">
  <div class="container">
    <div style="display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:40px;align-items:start">
      
      <!-- Hình ảnh -->
      <div>
        <div style="background:var(--bg-card);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);margin-bottom:16px;aspect-ratio:1">
          <img src="<?= $product['thumbnail'] ? uploadUrl($product['thumbnail']) : 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800' ?>" 
               alt="<?= e($product['name']) ?>" id="mainProductImg" style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s">
        </div>
        <?php if (!empty($product['images'])): $images = json_decode($product['images'], true) ?: []; ?>
        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px">
          <?php array_unshift($images, $product['thumbnail']); ?>
          <?php foreach (array_unique(array_filter($images)) as $img): ?>
          <div style="aspect-ratio:1;border-radius:var(--radius-sm);overflow:hidden;border:2px solid transparent;cursor:pointer" class="gallery-thumb" onclick="document.getElementById('mainProductImg').src=this.querySelector('img').src">
            <img src="<?= uploadUrl($img) ?>" style="width:100%;height:100%;object-fit:cover">
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Thông Tin Sản Phẩm -->
      <div>
        <?php if (!empty($product['brand_name'])): ?>
        <a href="<?= url('brand/'.$product['brand_slug']) ?>" style="font-size:0.875rem;font-weight:600;color:var(--primary);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;display:inline-block">
          <?= e($product['brand_name']) ?>
        </a>
        <?php endif; ?>
        
        <h1 style="font-family:var(--font-display);font-size:2.5rem;line-height:1.2;margin-bottom:16px"><?= e($product['name']) ?></h1>
        
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:24px;font-size:0.9rem">
          <div class="product-rating">
            <div class="stars"><?php $r=round($product['rating']); for($i=1;$i<=5;$i++) echo $i<=$r?'★':'☆'; ?></div>
            <span style="color:var(--text-muted)">(<?= $product['review_count'] ?> đánh giá)</span>
          </div>
          <div style="width:1px;height:16px;background:var(--border)"></div>
          <div style="color:var(--text-muted)">
            Đã bán: <strong style="color:var(--text)"><?= number_format(rand(100, 1000)) ?></strong>
          </div>
          <div style="width:1px;height:16px;background:var(--border)"></div>
          <div style="color:<?= $product['stock']>0?'#4caf50':'#e53e3e' ?>">
            <i class="fas fa-circle" style="font-size:0.5rem;vertical-align:middle;margin-right:4px"></i>
            <?= $product['stock']>0 ? 'Còn hàng' : 'Hết hàng' ?>
          </div>
        </div>

        <div style="margin-bottom:32px;display:flex;align-items:flex-end;gap:16px">
          <div style="font-size:2rem;font-weight:700;color:var(--primary);line-height:1">
            <?= formatPrice($product['sale_price'] ?? $product['price']) ?>
          </div>
          <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
          <div style="font-size:1.1rem;color:var(--text-muted);text-decoration:line-through;margin-bottom:4px">
            <?= formatPrice($product['price']) ?>
          </div>
          <div style="background:#fee2e2;color:#e53e3e;font-size:0.8rem;font-weight:700;padding:4px 8px;border-radius:4px;margin-bottom:4px">
            -<?= discountPercent($product['price'], $product['sale_price']) ?>%
          </div>
          <?php endif; ?>
        </div>

        <!-- Mua Hàng -->
        <div style="background:var(--bg-section);padding:24px;border-radius:var(--radius-md);margin-bottom:40px">
          <div style="display:flex;gap:16px;margin-bottom:16px">
            <div style="flex-shrink:0">
              <label class="form-label" style="margin-bottom:6px">Số lượng</label>
              <div class="qty-wrap" style="display:flex;align-items:center;background:var(--bg-card);border:1.5px solid var(--border);border-radius:var(--radius-md);overflow:hidden;width:max-content">
                <button class="qty-btn" data-action="minus" style="width:40px;height:40px;font-size:1.2rem;color:var(--text-muted)">−</button>
                <input type="number" class="qty-input" value="1" min="1" max="<?= $product['stock'] ?>" readonly style="width:50px;height:40px;text-align:center;border:none;background:none;font-weight:600">
                <button class="qty-btn" data-action="plus" style="width:40px;height:40px;font-size:1.2rem;color:var(--text-muted)">+</button>
              </div>
            </div>
            <div style="flex:1;display:flex;align-items:flex-end">
              <button class="btn btn-primary btn-block" style="height:44px" onclick="addToCart(<?= $product['id'] ?>)" <?= $product['stock']<1?'disabled style="opacity:0.5;cursor:not-allowed"':'' ?>>
                <i class="fas fa-shopping-bag"></i> <?= $product['stock']<1?'HẾT HÀNG':'THÊM VÀO GIỎ' ?>
              </button>
            </div>
          </div>
          <button class="btn btn-outline btn-block wishlist-btn" data-id="<?= $product['id'] ?>">
            <i class="far fa-heart"></i> Thêm vào danh sách yêu thích
          </button>
        </div>

        <div style="font-size:0.9rem;color:var(--text);line-height:1.8">
          <h3 style="font-family:var(--font-display);font-size:1.2rem;margin-bottom:12px">Mô Tả Sản Phẩm</h3>
          <?= nl2br(e($product['description'])) ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Review Section -->
<section class="section section-alt">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px">
      <!-- Ratings Info -->
      <div>
        <h3 style="font-family:var(--font-display);font-size:1.5rem;margin-bottom:24px">Đánh Giá Từ Khách Hàng</h3>
        <div style="display:flex;align-items:center;gap:24px;margin-bottom:32px">
          <div style="font-size:3.5rem;font-weight:700;color:var(--text);line-height:1"><?= number_format($product['rating'], 1) ?></div>
          <div>
            <div class="stars" style="font-size:1.2rem;margin-bottom:4px"><?php $r=round($product['rating']); for($i=1;$i<=5;$i++) echo $i<=$r?'★':'☆'; ?></div>
            <div style="color:var(--text-muted);font-size:0.9rem">Dựa trên <?= $product['review_count'] ?> đánh giá</div>
          </div>
        </div>

        <?php if ($canReview): ?>
        <div style="background:var(--bg-card);padding:24px;border-radius:var(--radius-lg);box-shadow:var(--shadow-sm)">
          <h4 style="margin-bottom:16px">Viết đánh giá của bạn</h4>
          <form id="reviewForm" onsubmit="submitReview(event)">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <div class="form-group">
              <label class="form-label">Điểm đánh giá</label>
              <select name="rating" class="form-control form-select">
                <option value="5">5 Sao - Tuyệt vời</option>
                <option value="4">4 Sao - Rất tốt</option>
                <option value="3">3 Sao - Bình thường</option>
                <option value="2">2 Sao - Kém</option>
                <option value="1">1 Sao - Tệ</option>
              </select>
            </div>
            <div class="form-group">
              <textarea name="content" class="form-control" rows="4" placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Gửi Đánh Giá</button>
          </form>
        </div>
        <?php elseif (!isLoggedIn()): ?>
        <p style="color:var(--text-muted)">Vui lòng <a href="<?= url('auth/login') ?>" style="color:var(--primary);text-decoration:underline">đăng nhập</a> để viết đánh giá.</p>
        <?php endif; ?>
      </div>

      <!-- Review List -->
      <div>
        <?php if (empty($reviews)): ?>
        <div style="text-align:center;padding:40px;color:var(--text-muted)">Chưa có đánh giá nào cho sản phẩm này.</div>
        <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:20px;max-height:600px;overflow-y:auto;padding-right:10px">
          <?php foreach ($reviews as $rev): ?>
          <div style="background:var(--bg-card);padding:20px;border-radius:var(--radius-md);box-shadow:var(--shadow-sm)">
            <div style="display:flex;justify-content:space-between;margin-bottom:12px">
              <div style="display:flex;align-items:center;gap:12px">
                <div style="width:40px;height:40px;border-radius:50%;background:var(--primary-light);display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--text)"><?= strtoupper(substr($rev['user_name'],0,1)) ?></div>
                <div>
                  <div style="font-weight:600;font-size:0.9rem"><?= e($rev['user_name']) ?></div>
                  <div class="stars" style="font-size:0.75rem"><?= str_repeat('★', $rev['rating']) . str_repeat('☆', 5-$rev['rating']) ?></div>
                </div>
              </div>
              <div style="font-size:0.75rem;color:var(--text-muted)"><?= formatDate($rev['created_at']) ?></div>
            </div>
            <p style="font-size:0.9rem;line-height:1.6;color:var(--text)"><?= nl2br(e($rev['content'])) ?></p>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- Related Products -->
<?php if (!empty($related)): ?>
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Sản Phẩm Tương Tự</h2>
      <div class="section-line"></div>
    </div>
    <div class="product-grid">
      <?php foreach ($related as $p): include __DIR__.'/_card.php'; endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<script>
initQuantity();

async function addToCart(id) {
  const qtyInput = document.querySelector('.qty-input');
  const qty = qtyInput ? parseInt(qtyInput.value) : 1;
  const btn = event.currentTarget;
  if (btn.disabled) return;
  
  const orig = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';
  
  try {
    const fd = new FormData();
    fd.append('action', 'add');
    fd.append('product_id', id);
    fd.append('quantity', qty);
    
    const res = await fetch(CART_URL, {method:'POST', body:fd});
    const text = await res.text();
    let data;
    try {
      data = JSON.parse(text);
    } catch (e) {
      console.error('API Error:', text);
      throw new Error('Phản hồi từ máy chủ không hợp lệ');
    }

    if (data.success) {
      showToast(data.message, 'success');
      if (typeof updateCartBadge === 'function') updateCartBadge(data.cart_count);
      btn.innerHTML = '<i class="fas fa-check"></i> ĐÃ THÊM!';
      setTimeout(() => { btn.innerHTML = orig; btn.disabled = false; }, 2000);
    } else {
      showToast(data.message, 'error');
      btn.innerHTML = orig; btn.disabled = false;
    }
  } catch (err) {
    showToast('Lỗi: ' + err.message, 'error');
    btn.innerHTML = orig; btn.disabled = false;
  }
}

async function submitReview(e) {
  e.preventDefault();
  const form = e.target;
  const fd = new FormData(form);
  const btn = form.querySelector('button');
  btn.disabled = true;
  
  const res = await fetch('<?= url('api/review') ?>', {method:'POST', body:fd});
  const data = await res.json();
  if (data.success) {
    showToast(data.message, 'success');
    setTimeout(() => window.location.reload(), 1500);
  } else {
    showToast(data.message, 'error');
    btn.disabled = false;
  }
}

document.querySelectorAll('.gallery-thumb').forEach(t => {
  t.addEventListener('click', function() {
    document.querySelectorAll('.gallery-thumb').forEach(tt => tt.style.borderColor = 'transparent');
    this.style.borderColor = 'var(--primary)';
  });
});
</script>
<style>@media(max-width:768px){div[style*="grid-template-columns:minmax(0,1fr) minmax(0,1fr)"]{grid-template-columns:1fr!important;}}</style>
