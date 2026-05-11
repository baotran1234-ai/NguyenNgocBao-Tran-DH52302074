<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $pageTitle ?? 'Admin - LUXE Beauty' ?></title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
:root{--primary:#c9a96e;--accent:#d4849a;--primary-light:#e8d5b0;--bg:#f8f6f3;--bg-card:#fff;--text:#2c2c2c;--text-muted:#888;--border:#e8e1d9;--sidebar-w:260px;--font-body:'Inter',sans-serif;--font-display:'Playfair Display',serif;--radius-md:12px;--radius-lg:20px;--shadow-sm:0 2px 8px rgba(0,0,0,0.06);--shadow-md:0 8px 24px rgba(0,0,0,0.1);}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:var(--font-body);background:var(--bg);color:var(--text);display:flex;min-height:100vh}
a{text-decoration:none;color:inherit}
ul{list-style:none}
button{cursor:pointer;border:none;background:none;font-family:inherit}
.admin-sidebar{width:var(--sidebar-w);background:#1a1814;color:#c8bfb0;display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:100;overflow-y:auto}
.sidebar-logo{padding:24px;display:flex;align-items:center;gap:12px;border-bottom:1px solid rgba(255,255,255,0.07)}
.sidebar-logo-mark{width:40px;height:40px;background:linear-gradient(135deg,var(--primary),var(--accent));border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-family:var(--font-display);font-size:1.2rem;font-weight:700}
.sidebar-logo-text .name{font-family:var(--font-display);color:#fff;font-size:1.1rem;letter-spacing:2px}
.sidebar-logo-text .sub{font-size:0.65rem;letter-spacing:3px;opacity:0.4;text-transform:uppercase}
.sidebar-nav{padding:16px 0;flex:1}
.nav-section-label{font-size:0.65rem;letter-spacing:3px;text-transform:uppercase;opacity:0.35;padding:16px 20px 8px}
.sidebar-link{display:flex;align-items:center;gap:12px;padding:11px 20px;color:#c8bfb0;font-size:0.875rem;transition:all 0.3s;position:relative;border-left:3px solid transparent}
.sidebar-link:hover,.sidebar-link.active{color:#fff;background:rgba(201,169,110,0.1);border-left-color:var(--primary)}
.sidebar-link i{width:18px;text-align:center;font-size:1rem;color:var(--primary)}
.sidebar-badge{margin-left:auto;background:var(--accent);color:#fff;font-size:0.65rem;padding:2px 7px;border-radius:99px;font-weight:700}
.sidebar-user{padding:16px;border-top:1px solid rgba(255,255,255,0.07);display:flex;align-items:center;gap:10px}
.sidebar-user-avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.9rem}
.sidebar-user-info .name{color:#fff;font-size:0.85rem;font-weight:600}
.sidebar-user-info .role{font-size:0.7rem;opacity:0.5;text-transform:uppercase;letter-spacing:1px}
.sidebar-user a{margin-left:auto;color:rgba(255,255,255,0.4);font-size:0.85rem}
.sidebar-user a:hover{color:#fff}
.admin-content{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}
.admin-topbar{background:var(--bg-card);border-bottom:1px solid var(--border);padding:0 28px;height:60px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50;box-shadow:var(--shadow-sm)}
.topbar-title{font-family:var(--font-display);font-size:1.2rem;font-weight:700}
.topbar-actions{display:flex;align-items:center;gap:12px}
.admin-main{padding:28px;flex:1}
.stat-card{background:var(--bg-card);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow-sm);border-left:4px solid var(--primary);transition:all 0.3s}
.stat-card:hover{transform:translateY(-3px);box-shadow:var(--shadow-md)}
.stat-label{font-size:0.8rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px}
.stat-value{font-size:1.8rem;font-weight:700;font-family:var(--font-display);color:var(--text);margin-bottom:4px}
.stat-sub{font-size:0.8rem;color:var(--text-muted)}
.stat-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem}
.admin-table{width:100%;border-collapse:collapse;font-size:0.875rem}
.admin-table th{background:var(--bg);padding:10px 16px;text-align:left;font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);border-bottom:1px solid var(--border)}
.admin-table td{padding:12px 16px;border-bottom:1px solid var(--border);vertical-align:middle}
.admin-table tr:last-child td{border-bottom:none}
.admin-table tr:hover td{background:rgba(201,169,110,0.03)}
.admin-card{background:var(--bg-card);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);overflow:hidden}
.admin-card-header{padding:18px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.admin-card-title{font-weight:600;font-size:1rem}
.admin-card-body{padding:0}
.btn-admin{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:0.8rem;font-weight:600;transition:all 0.2s;cursor:pointer;border:1.5px solid transparent}
.btn-admin-primary{background:var(--primary);color:#fff}
.btn-admin-primary:hover{opacity:0.9}
.btn-admin-danger{background:#fee2e2;color:#e53e3e;border-color:transparent}
.btn-admin-danger:hover{background:#e53e3e;color:#fff}
.btn-admin-info{background:#e3f2fd;color:#1976d2}
.btn-admin-info:hover{background:#1976d2;color:#fff}
.btn-admin-success{background:#e8f5e9;color:#2e7d32}
.btn-admin-success:hover{background:#2e7d32;color:#fff}
.btn-admin-warning{background:#fff3e0;color:#e65100}
.btn-admin-warning:hover{background:#e65100;color:#fff}
.form-control-admin{width:100%;padding:9px 13px;border:1.5px solid var(--border);border-radius:8px;font-size:0.875rem;color:var(--text);background:var(--bg-card)}
.form-control-admin:focus{outline:none;border-color:var(--primary)}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:20px;margin-bottom:28px}
.badge{display:inline-block;padding:3px 10px;border-radius:99px;font-size:0.72rem;font-weight:600}
.badge-warning{background:#fff3cd;color:#856404}
.badge-info{background:#cff4fc;color:#055160}
.badge-success{background:#d1e7dd;color:#0a3622}
.badge-danger{background:#f8d7da;color:#842029}
.badge-primary{background:#cfe2ff;color:#084298}
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="admin-sidebar">
  <div class="sidebar-logo">
    <div class="sidebar-logo-mark">L</div>
    <div class="sidebar-logo-text">
      <div class="name">LUXE</div>
      <div class="sub">Admin Panel</div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">Tổng Quan</div>
    <a href="<?= url('admin/dashboard') ?>" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'],'dashboard') !== false ? 'active' : '' ?>">
      <i class="fas fa-th-large"></i> Dashboard
    </a>

    <div class="nav-section-label">Sản Phẩm</div>
    <a href="<?= url('admin/products') ?>" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'],'admin/products') !== false ? 'active' : '' ?>">
      <i class="fas fa-box"></i> Sản Phẩm
    </a>
    <a href="<?= url('admin/categories') ?>" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'],'categories') !== false ? 'active' : '' ?>">
      <i class="fas fa-tags"></i> Danh Mục
    </a>
    <a href="<?= url('admin/banners') ?>" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'],'banners') !== false ? 'active' : '' ?>">
      <i class="fas fa-images"></i> Banners
    </a>

    <div class="nav-section-label">Kinh Doanh</div>
    <a href="<?= url('admin/orders') ?>" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'],'admin/orders') !== false ? 'active' : '' ?>">
      <i class="fas fa-shopping-cart"></i> Đơn Hàng
      <?php $pendingCount = (int)db()->query("SELECT COUNT(*) FROM orders WHERE status='pending'")->fetchColumn(); ?>
      <?php if ($pendingCount > 0): ?><span class="sidebar-badge"><?= $pendingCount ?></span><?php endif; ?>
    </a>
    <a href="<?= url('admin/coupons') ?>" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'],'coupons') !== false ? 'active' : '' ?>">
      <i class="fas fa-ticket-alt"></i> Mã Giảm Giá
    </a>
    <a href="<?= url('admin/reviews') ?>" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'],'reviews') !== false ? 'active' : '' ?>">
      <i class="fas fa-star"></i> Đánh Giá
    </a>

    <div class="nav-section-label">Người Dùng</div>
    <a href="<?= url('admin/users') ?>" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'],'admin/users') !== false ? 'active' : '' ?>">
      <i class="fas fa-users"></i> Khách Hàng
    </a>

    <div class="nav-section-label">Hệ Thống</div>
    <a href="<?= url('') ?>" class="sidebar-link" target="_blank">
      <i class="fas fa-external-link-alt"></i> Xem Website
    </a>
  </nav>

  <div class="sidebar-user">
    <div class="sidebar-user-avatar"><?= strtoupper(substr(currentAdmin()['name'] ?? 'A', 0, 1)) ?></div>
    <div class="sidebar-user-info">
      <div class="name"><?= e(currentAdmin()['name'] ?? 'Admin') ?></div>
      <div class="role"><?= e(currentAdmin()['role'] ?? 'admin') ?></div>
    </div>
    <a href="<?= url('admin/logout') ?>" title="Đăng xuất"><i class="fas fa-sign-out-alt"></i></a>
  </div>
</aside>

<!-- CONTENT -->
<div class="admin-content">
  <div class="admin-topbar">
    <h1 class="topbar-title"><?= $pageTitle ?? 'Dashboard' ?></h1>
    <div class="topbar-actions">
      <span style="font-size:0.8rem;color:var(--text-muted)"><?= date('d/m/Y H:i') ?></span>
    </div>
  </div>
  <main class="admin-main">
  <!-- Toast -->
  <div id="toast-container" style="position:fixed;top:80px;right:20px;z-index:9000;display:flex;flex-direction:column;gap:10px"></div>
