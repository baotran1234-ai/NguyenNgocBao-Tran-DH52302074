// LUXE Beauty Main JS
document.addEventListener('DOMContentLoaded', () => {
  const safeInit = (fn, name) => {
    try { fn(); } catch (e) { console.error(`Error in ${name}:`, e); }
  };
  safeInit(initPreloader, 'Preloader');
  safeInit(initSlider,    'Slider');
  safeInit(initDarkMode,  'DarkMode');
  safeInit(initHeader,    'Header');
  safeInit(initSearch,    'Search');
  safeInit(initCart,      'Cart');
  safeInit(initWishlist,  'Wishlist');
  safeInit(initLazyLoad,  'LazyLoad');
  safeInit(initBackToTop, 'BackToTop');
  safeInit(initMobileMenu,'MobileMenu');
  safeInit(initQuantity,  'Quantity');
});

/* ---- Preloader ---- */
function initPreloader() {
  const p = document.getElementById('preloader');
  if (!p) return;
  setTimeout(() => {
    p.style.opacity = '0';
    setTimeout(() => p.style.display = 'none', 500);
  }, 400);
}

/* ---- Hero Slider ---- */
function initSlider() {
  const track  = document.getElementById('sliderTrack');
  const dotsEl = document.getElementById('sliderDots');
  const prevBtn = document.getElementById('sliderPrev');
  const nextBtn = document.getElementById('sliderNext');
  if (!track) return;

  const slides = track.querySelectorAll('.slide');
  if (slides.length === 0) return;

  let current = 0;
  let timer;

  // Create dots
  if (dotsEl) {
    slides.forEach((_, i) => {
      const dot = document.createElement('div');
      dot.className = 'slider-dot' + (i === 0 ? ' active' : '');
      dot.addEventListener('click', () => goTo(i));
      dotsEl.appendChild(dot);
    });
  }

  function goTo(idx) {
    slides[current].classList.remove('active');
    if (dotsEl) dotsEl.children[current]?.classList.remove('active');
    current = (idx + slides.length) % slides.length;
    slides[current].classList.add('active');
    if (dotsEl) dotsEl.children[current]?.classList.add('active');
    track.style.transform = `translateX(-${current * 100}%)`;
  }

  function next() { goTo(current + 1); }
  function prev() { goTo(current - 1); }
  function startAuto() { timer = setInterval(next, 5000); }
  function stopAuto()  { clearInterval(timer); }

  prevBtn?.addEventListener('click', () => { prev(); stopAuto(); startAuto(); });
  nextBtn?.addEventListener('click', () => { next(); stopAuto(); startAuto(); });

  // Touch/swipe
  let startX = 0;
  track.addEventListener('touchstart', e => { startX = e.touches[0].clientX; stopAuto(); }, { passive: true });
  track.addEventListener('touchend', e => {
    const diff = startX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 50) diff > 0 ? next() : prev();
    startAuto();
  });

  startAuto();
}

/* ---- Dark Mode ---- */
function initDarkMode() {
  const btn  = document.getElementById('darkModeToggle');
  const html = document.documentElement;
  const saved = localStorage.getItem('theme') || 'light';
  html.setAttribute('data-theme', saved);
  if (btn) {
    btn.querySelector('i').className = saved === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    btn.addEventListener('click', () => {
      const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      html.setAttribute('data-theme', next);
      localStorage.setItem('theme', next);
      btn.querySelector('i').className = next === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    });
  }
}

/* ---- Header scroll hide/show ---- */
function initHeader() {
  const header = document.getElementById('siteHeader');
  if (!header) return;
  let lastY = 0;
  window.addEventListener('scroll', () => {
    const y = window.scrollY;
    header.classList.toggle('scrolled', y > 50);
    header.classList.toggle('hidden', y > lastY + 5 && y > 200);
    if (y < lastY) header.classList.remove('hidden');
    lastY = y;
  }, { passive: true });
}

/* ---- Search Dropdown ---- */
function initSearch() {
  const input    = document.getElementById('searchInput');
  const dropdown = document.getElementById('searchDropdown');
  const btn      = document.getElementById('searchBtn');
  if (!input || !dropdown) return;

  let debounce;
  input.addEventListener('input', () => {
    clearTimeout(debounce);
    const q = input.value.trim();
    if (q.length < 2) { dropdown.classList.remove('show'); return; }
    debounce = setTimeout(async () => {
      try {
        const res  = await fetch(`${SEARCH_URL}?q=${encodeURIComponent(q)}`);
        const data = await res.json();
        if (!data.products?.length) { dropdown.classList.remove('show'); return; }
        dropdown.innerHTML = data.products.map(p => `
          <a class="search-item" href="${APP_URL}/products/${p.slug}">
            <img src="${p.thumbnail || 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=80'}" alt="">
            <div class="search-item-info">
              <div class="search-item-name">${p.name}</div>
              <div class="search-item-price">${p.price_formatted}</div>
            </div>
          </a>`).join('');
        dropdown.classList.add('show');
      } catch(e) { /* silent */ }
    }, 300);
  });

  document.addEventListener('click', e => {
    if (!input.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.remove('show');
    }
  });

  btn?.addEventListener('click', () => {
    const q = input.value.trim();
    if (q) window.location.href = `${APP_URL}/products?search=${encodeURIComponent(q)}`;
  });

  input.addEventListener('keydown', e => {
    if (e.key === 'Enter') {
      const q = input.value.trim();
      if (q) window.location.href = `${APP_URL}/products?search=${encodeURIComponent(q)}`;
    }
  });
}

/* ---- Add to Cart ---- */
function initCart() {
  document.addEventListener('click', async e => {
    const btn = e.target.closest('.add-to-cart-btn');
    if (!btn || btn.disabled) return;
    const productId = btn.dataset.id;
    if (!productId) return;

    btn.disabled = true;
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';

    try {
      const fd = new FormData();
      fd.append('action', 'add');
      fd.append('product_id', productId);
      fd.append('quantity', 1);

      const res = await fetch(CART_URL, { method: 'POST', body: fd });
      if (!res.ok) throw new Error(`Server ${res.status}`);

      const text = await res.text();
      let data;
      try { data = JSON.parse(text); }
      catch { throw new Error('Phản hồi không hợp lệ'); }

      if (data.success) {
        btn.innerHTML = '<i class="fas fa-check"></i> Đã thêm!';
        updateCartBadge(data.cart_count);
        // Chuyển sang trang giỏ hàng sau 600ms
        setTimeout(() => {
          window.location.href = CART_PAGE_URL;
        }, 600);
      } else {
        showToast(data.message || 'Lỗi thêm giỏ hàng', 'error');
        btn.innerHTML = original;
        btn.disabled = false;
      }
    } catch (err) {
      showToast('Lỗi: ' + err.message, 'error');
      btn.innerHTML = original;
      btn.disabled = false;
    }
  });
}

function updateCartBadge(count) {
  const badge = document.getElementById('cartBadge');
  if (badge) badge.textContent = count > 0 ? count : '';
}

/* ---- Wishlist ---- */
function initWishlist() {
  document.addEventListener('click', async e => {
    const btn = e.target.closest('.wishlist-btn');
    if (!btn) return;
    const productId = btn.dataset.id;
    if (!productId) return;

    try {
      const fd = new FormData();
      fd.append('product_id', productId);
      const res  = await fetch(WISHLIST_URL, { method: 'POST', body: fd });
      const data = await res.json();
      if (data.success) {
        btn.classList.toggle('active', data.added);
        const icon = btn.querySelector('i');
        if (icon) icon.className = data.added ? 'fas fa-heart' : 'far fa-heart';
        showToast(data.message, 'success');
      }
    } catch { /* silent */ }
  });
}

/* ---- Lazy Load ---- */
function initLazyLoad() {
  const imgs = document.querySelectorAll('img.lazy');
  if (!imgs.length) return;
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const img = entry.target;
      if (img.dataset.src) { img.src = img.dataset.src; }
      img.classList.add('loaded');
      observer.unobserve(img);
    });
  }, { rootMargin: '200px' });
  imgs.forEach(img => observer.observe(img));
}

/* ---- Back to Top ---- */
function initBackToTop() {
  const btn = document.getElementById('backToTop');
  if (!btn) return;
  window.addEventListener('scroll', () => btn.classList.toggle('show', window.scrollY > 400), { passive: true });
  btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}


/* ---- Mobile Menu ---- */
function initMobileMenu() {
  const btn = document.getElementById('mobileMenuBtn');
  const nav = document.getElementById('headerNav');
  if (btn && nav) btn.addEventListener('click', () => nav.classList.toggle('open'));
}

/* ---- Quantity Controls ---- */
function initQuantity() {
  document.addEventListener('click', e => {
    const btn = e.target.closest('.qty-btn');
    if (!btn) return;
    const wrap  = btn.closest('.qty-wrap');
    const input = wrap?.querySelector('.qty-input, input[type="number"]');
    if (!input) return;
    const min = parseInt(input.min) || 1;
    const max = parseInt(input.max) || 999;
    let val = parseInt(input.value) || 1;
    if (btn.dataset.action === 'plus')  val = Math.min(val + 1, max);
    if (btn.dataset.action === 'minus') val = Math.max(val - 1, min);
    input.value = val;
    input.dispatchEvent(new Event('change'));
  });
}

/* ---- Toast ---- */
function showToast(message, type = 'info') {
  const container = document.getElementById('toast-container');
  if (!container) { alert(message); return; }
  const icons = { success:'✓', error:'✗', warning:'⚠', info:'ℹ' };
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `<span class="toast-icon">${icons[type]||'ℹ'}</span><span>${message}</span><button class="toast-close" onclick="this.parentElement.remove()">×</button>`;
  container.appendChild(toast);
  setTimeout(() => {
    toast.style.animation = 'toastOut 0.3s ease forwards';
    setTimeout(() => toast.remove(), 300);
  }, 3500);
}