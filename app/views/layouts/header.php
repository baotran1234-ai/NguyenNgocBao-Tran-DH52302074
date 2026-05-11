<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $pageDesc ?? 'LUXE Beauty - Mỹ phẩm cao cấp chính hãng, giá tốt nhất' ?>">
    <title><?= $pageTitle ?? 'LUXE Beauty' ?></title>

    <!-- Preconnect fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">

    <link rel="icon" type="image/x-icon" href="<?= asset('images/favicon.ico') ?>">
</head>
<body>

<!-- ===== PRELOADER ===== -->
<div id="preloader">
    <div class="preloader-inner">
        <div class="preloader-logo">LUXE</div>
        <div class="preloader-bar"><div class="preloader-fill"></div></div>
    </div>
</div>

<!-- ===== TOAST NOTIFICATION ===== -->
<div id="toast-container" class="toast-container"></div>

<!-- ===== HEADER ===== -->
<header class="site-header" id="siteHeader">
    <!-- Top bar -->
    <div class="header-topbar">
        <div class="container">
            <div class="topbar-left">
                <span><i class="fas fa-phone-alt"></i> 1800 6868</span>
                <span><i class="fas fa-envelope"></i> dh52302074@student.stu.edu.vn</span>
            </div>
            <div class="topbar-right">
                <a href="<?= url('') ?>">🎁 Miễn phí giao hàng đơn từ <?= formatPrice(FREE_SHIPPING_OVER) ?></a>
                <?php if (isLoggedIn()): ?>
                    <a href="<?= url('user/profile') ?>"><i class="fas fa-user"></i> <?= e($_SESSION['user']['name'] ?? 'Khách') ?></a>
                    <a href="<?= url('auth/logout') ?>"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                <?php else: ?>
                    <a href="<?= url('auth/login') ?>"><i class="fas fa-sign-in-alt"></i> Đăng nhập</a>
                    <a href="<?= url('auth/register') ?>"><i class="fas fa-user-plus"></i> Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main header -->
    <div class="header-main">
        <div class="container">
            <!-- Logo -->
            <a href="<?= url('') ?>" class="site-logo">
                <div class="logo-mark">L</div>
                <div class="logo-text">
                    <span class="logo-name">LUXE</span>
                    <span class="logo-sub">Beauty</span>
                </div>
            </a>

            <!-- Search -->
            <div class="header-search">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Tìm kiếm sản phẩm, thương hiệu..." autocomplete="off">
                    <button class="search-btn" id="searchBtn"><i class="fas fa-search"></i></button>
                    <div class="search-dropdown" id="searchDropdown"></div>
                </div>
            </div>

            <!-- Actions -->
            <div class="header-actions">
                <!-- Dark mode toggle -->
                <button class="btn-icon" id="darkModeToggle" title="Chế độ tối">
                    <i class="fas fa-moon"></i>
                </button>

                <!-- Wishlist -->
                <?php if (isLoggedIn()): ?>
                <a href="<?= url('user/wishlist') ?>" class="btn-icon" title="Yêu thích">
                    <i class="fas fa-heart"></i>
                </a>
                <?php endif; ?>

                <!-- Cart -->
                <a href="<?= url('cart') ?>" class="btn-icon cart-btn" title="Giỏ hàng">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-badge" id="cartBadge"><?= getCartCount() ?: '' ?></span>
                </a>

                <!-- Mobile menu -->
                <button class="btn-icon mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="header-nav" id="headerNav">
        <div class="container">
            <ul class="nav-menu" id="navMenu">
                <li><a href="<?= url('') ?>" class="nav-link">Trang Chủ</a></li>
                <li class="has-dropdown">
                    <a href="<?= url('products') ?>" class="nav-link">Sản Phẩm <i class="fas fa-chevron-down"></i></a>
                    <div class="mega-dropdown">
                        <div class="container">
                            <div class="mega-grid">
                                <?php
                                require_once APP_PATH . '/models/OtherModels.php';
                                $navCategories = (new CategoryModel())->getAll();
                                foreach ($navCategories as $cat): ?>
                                <a href="<?= url('category/' . $cat['slug']) ?>" class="mega-item">
                                    <i class="fas fa-tag"></i>
                                    <?= e($cat['name']) ?>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </li>
                <li><a href="<?= url('products?sort=popular') ?>" class="nav-link">Bán Chạy</a></li>
                <li><a href="<?= url('products?sort=newest') ?>" class="nav-link">Hàng Mới</a></li>
                <li><a href="<?= url('products?sale=1') ?>" class="nav-link nav-sale">🔥 Sale</a></li>
                <li><a href="<?= url('bailabthuchanh/') ?>" class="nav-link" style="color:var(--primary); font-weight:600;">Các bài lab thực hành</a></li>
            </ul>
        </div>
    </nav>
</header>

<!-- Flash message -->
<?php $flash = getFlash(); if ($flash): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof showToast === 'function') {
            showToast('<?= addslashes($flash['message']) ?>', '<?= $flash['type'] ?>');
        }
    });
</script>
<?php endif; ?>

<!-- MAIN CONTENT START -->
<main id="mainContent">
