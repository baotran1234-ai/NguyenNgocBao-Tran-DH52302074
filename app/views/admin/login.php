<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $pageTitle ?? 'Admin - LUXE Beauty' ?></title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
<style>
body{padding-top:0!important}
.admin-login-wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#1a1814 0%,#2d2420 50%,#1a1814 100%);position:relative;overflow:hidden}
.admin-login-wrap::before{content:'LUXE';position:absolute;font-family:'Playfair Display',serif;font-size:20rem;font-weight:700;color:rgba(201,169,110,0.03);top:50%;left:50%;transform:translate(-50%,-50%);letter-spacing:20px;pointer-events:none}
.admin-login-box{background:rgba(255,255,255,0.03);backdrop-filter:blur(20px);border:1px solid rgba(201,169,110,0.2);border-radius:20px;padding:48px;width:100%;max-width:420px;box-shadow:0 40px 80px rgba(0,0,0,0.5)}
.admin-login-box .form-control{background:rgba(255,255,255,0.05);border-color:rgba(201,169,110,0.2);color:#f0ebe3}
.admin-login-box .form-control:focus{border-color:#c9a96e;background:rgba(255,255,255,0.08)}
.admin-login-box .form-control::placeholder{color:rgba(255,255,255,0.3)}
.admin-login-box .form-label{color:rgba(255,255,255,0.7)}
</style>
</head>
<body>
<div class="admin-login-wrap">
  <div class="admin-login-box">
    <div style="text-align:center;margin-bottom:32px">
      <div style="width:64px;height:64px;background:linear-gradient(135deg,#c9a96e,#d4849a);border-radius:50%;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;color:#fff;font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:700">L</div>
      <h1 style="font-family:'Playfair Display',serif;color:#f0ebe3;font-size:1.8rem;margin-bottom:4px">Admin Portal</h1>
      <p style="color:rgba(255,255,255,0.4);font-size:0.85rem">LUXE Beauty Management</p>
    </div>

    <?php if (!empty($error)): ?>
    <div style="background:rgba(229,83,83,0.15);border:1px solid rgba(229,83,83,0.3);color:#fc8181;padding:12px 16px;border-radius:12px;margin-bottom:20px;font-size:0.875rem">
      <i class="fas fa-exclamation-circle"></i> <?= e($error) ?>
    </div>
    <?php endif; ?>

    <?php 
    $successMsg = $_SESSION['admin_login_success'] ?? '';
    unset($_SESSION['admin_login_success']);
    if (!empty($successMsg)): ?>
    <div style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);color:#86efac;padding:12px 16px;border-radius:12px;margin-bottom:20px;font-size:0.875rem">
      <i class="fas fa-check-circle"></i> <?= e($successMsg) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?= url('admin/do-login') ?>">
      <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="admin@beautyshop.vn" required autofocus value="admin@beautyshop.vn">
      </div>
      <div class="form-group">
        <label class="form-label">Mật khẩu</label>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required value="Admin@123">
      </div>
      <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top:8px">
        <i class="fas fa-sign-in-alt"></i> Đăng nhập Admin
      </button>
    </form>
    <p style="text-align:center;color:rgba(255,255,255,0.3);font-size:0.75rem;margin-top:24px">
      Demo: admin@beautyshop.vn / Admin@123
    </p>
  </div>
</div>
</body></html>
