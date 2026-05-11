<?php

// ================================================================
// index.php - Entry Point chính của ứng dụng
// ================================================================

// ---- Phục vụ static files cho PHP built-in server ----
if (php_sapi_name() === 'cli-server') {
    $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $filePath    = __DIR__ . $requestPath;

    // Nếu là thư mục trong bailabthuchanh mà không có index, tự động tạo danh sách file
    if (strpos($requestPath, '/bailabthuchanh') === 0 && is_dir($filePath)) {
        if (!file_exists($filePath . '/index.php') && !file_exists($filePath . '/index.html')) {
            $files = array_filter(scandir($filePath), function($f) { return $f !== '.' && $f !== '..'; });
            echo "<html><head><title>Index of $requestPath</title><style>body{font-family:sans-serif;padding:20px}ul{list-style:none;padding:0}li{margin:10px 0}a{text-decoration:none;color:#0066cc}</style></head><body>";
            echo "<h1>Index of $requestPath</h1><hr><ul>";
            echo "<li><a href='../'>[ Parent Directory ]</a></li>";
            foreach ($files as $f) {
                $isDir = is_dir($filePath . '/' . $f);
                echo "<li><a href='$f" . ($isDir ? '/' : '') . "'>" . ($isDir ? '📁 ' : '📄 ') . "$f</a></li>";
            }
            echo "</ul></body></html>";
            exit;
        }
    }

    // Chỉ phục vụ nếu là file thực tế và KHÔNG phải .php
    if ($requestPath !== '/' && is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) !== 'php') {
        $ext  = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mime = [
            'jpg'  => 'image/jpeg', 'jpeg' => 'image/jpeg',
            'png'  => 'image/png',  'gif'  => 'image/gif',
            'webp' => 'image/webp', 'svg'  => 'image/svg+xml',
            'css'  => 'text/css',   'js'   => 'application/javascript',
            'ico'  => 'image/x-icon', 'woff2' => 'font/woff2',
            'woff' => 'font/woff',  'ttf'  => 'font/ttf',
            'pdf'  => 'application/pdf',
        ];
        if (isset($mime[$ext])) {
            header('Content-Type: ' . $mime[$ext]);
        }
        readfile($filePath);
        exit;
    }
}

// Load config
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/helpers.php';

// LáÂºÂ¥y URL táÂ»Â« query string hoáÂºÂ·c REQUEST_URI (Ã„â€˜áÂ»Æ’ háÂ»â€” tráÂ»Â£ PHP built-in server)
$url = $_GET['url'] ?? '';
if (empty($url)) {
    $parsedUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    // BáÂ»Â pháÂºÂ§n base url náÂºÂ¿u cháÂºÂ¡y trong thÃ†Â° máÂ»Â¥c con
    $basePath = parse_url(APP_URL, PHP_URL_PATH) ?? '';
    if ($basePath && strpos($parsedUrl, $basePath) === 0) {
        $parsedUrl = substr($parsedUrl, strlen($basePath));
    }
    $url = ltrim($parsedUrl, '/');
}
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$segments = $url ? explode('/', $url) : [];

$controller = $segments[0] ?? 'home';
$action     = $segments[1] ?? 'index';
$param      = $segments[2] ?? null;

// ---- DEBUG UPLOAD ROUTE (xóa sau khi test xong) ----
if ($controller === 'debug-upload') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ?>
    <!DOCTYPE html><html lang="vi"><head><meta charset="UTF-8"><title>Debug Upload</title>
    <style>body{font-family:Arial;padding:30px;background:#f5f5f5}.box{background:#fff;padding:20px;border-radius:8px;margin-bottom:20px}.ok{color:green;font-weight:bold}.err{color:red;font-weight:bold}table{border-collapse:collapse;width:100%}td,th{border:1px solid #ddd;padding:8px 12px;font-size:.9rem}th{background:#f0f0f0}h2{border-bottom:1px solid #eee;padding-bottom:8px}</style>
    </head><body>
    <div class="box">
    <h2>🔧 Cấu hình PHP Upload</h2>
    <table>
    <tr><th>Cài đặt</th><th>Giá trị</th><th>Trạng thái</th></tr>
    <tr><td>upload_max_filesize</td><td><?= ini_get('upload_max_filesize') ?></td>
        <td><?= (intval(ini_get('upload_max_filesize')) >= 5) ? '<span class="ok">✅ OK</span>' : '<span class="err">❌ Quá nhỏ! Cần sửa php.ini</span>' ?></td></tr>
    <tr><td>post_max_size</td><td><?= ini_get('post_max_size') ?></td>
        <td><?= (intval(ini_get('post_max_size')) >= 5) ? '<span class="ok">✅ OK</span>' : '<span class="err">❌ Quá nhỏ! Cần sửa php.ini</span>' ?></td></tr>
    <tr><td>file_uploads</td><td><?= ini_get('file_uploads') ? 'On' : 'Off' ?></td>
        <td><?= ini_get('file_uploads') ? '<span class="ok">✅ Bật</span>' : '<span class="err">❌ Tắt!</span>' ?></td></tr>
    <tr><td>upload_tmp_dir</td><td><?= ini_get('upload_tmp_dir') ?: sys_get_temp_dir() ?></td>
        <td><?= is_writable(ini_get('upload_tmp_dir') ?: sys_get_temp_dir()) ? '<span class="ok">✅ OK</span>' : '<span class="err">❌ Không ghi được!</span>' ?></td></tr>
    <tr><td>Thư mục uploads/products</td><td><?= __DIR__ . '/uploads/products' ?></td>
        <td><?= is_writable(__DIR__ . '/uploads/products') ? '<span class="ok">✅ OK</span>' : '<span class="err">❌ Không có quyền ghi!</span>' ?></td></tr>
    </table></div>
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['img'])): ?>
    <div class="box"><h2>📊 Kết quả phân tích</h2>
    <?php $f = $_FILES['img']; ?>
    <table>
    <tr><td>Tên file</td><td><?= htmlspecialchars($f['name']) ?></td></tr>
    <tr><td>Kích thước</td><td><?= number_format($f['size']/1024,1) ?> KB</td></tr>
    <tr><td>MIME (browser)</td><td><?= htmlspecialchars($f['type']) ?></td></tr>
    <tr><td>PHP Error Code</td><td><?= $f['error'] ?> <?= $f['error']===0?'<span class="ok">(OK)</span>':'<span class="err">(Lỗi!)</span>' ?></td></tr>
    <tr><td>Temp file tồn tại?</td><td><?= file_exists($f['tmp_name'])?'<span class="ok">Có: '.$f['tmp_name'].'</span>':'<span class="err">Không! File quá lớn hoặc PHP error.</span>' ?></td></tr>
    <?php if($f['error']===0 && file_exists($f['tmp_name'])): ?>
    <?php
    // Magic bytes detection
    $h=fopen($f['tmp_name'],'rb'); $magic=fread($h,12); fclose($h);
    $det=null;
    if(substr($magic,0,3)==="\xFF\xD8\xFF") $det='jpeg';
    elseif(substr($magic,0,8)==="\x89PNG\r\n\x1a\n") $det='png';
    elseif(substr($magic,0,6)==='GIF87a'||substr($magic,0,6)==='GIF89a') $det='gif';
    elseif(substr($magic,0,4)==='RIFF'&&substr($magic,8,4)==='WEBP') $det='webp';
    $img=@getimagesize($f['tmp_name']);
    ?>
    <tr><td>getimagesize()</td><td><?= $img ? '<span class="ok">✅ '.$img['mime'].'</span>' : '<span class="err">❌ Thất bại (thường xảy ra với WebP)</span>' ?></td></tr>
    <tr><td>Magic Bytes phát hiện</td><td><?= $det ? '<span class="ok">✅ Phát hiện được: '.$det.'</span>' : '<span class="err">❌ Không nhận ra magic bytes</span>' ?></td></tr>
    <?php
    $detectedType = $det;
    if(!$detectedType && function_exists('finfo_open')){
        $fi=finfo_open(FILEINFO_MIME_TYPE); $rm=finfo_file($fi,$f['tmp_name']); finfo_close($fi);
        $mm=['image/jpeg'=>'jpeg','image/png'=>'png','image/gif'=>'gif','image/webp'=>'webp'];
        $detectedType=$mm[$rm]??null;
    }
    if(!$detectedType){
        $bm=['image/jpeg'=>'jpeg','image/png'=>'png','image/gif'=>'gif','image/webp'=>'webp'];
        $detectedType=$bm[$f['type']]??null;
    }
    ?>
    <tr><td>Loại cuối cùng xác định</td><td><?= $detectedType ? '<span class="ok">✅ '.$detectedType.'</span>' : '<span class="err">❌ Không xác định được!</span>' ?></td></tr>
    <?php if($detectedType):
    $dest=__DIR__.'/uploads/products/test_'.time().'.'.$detectedType;
    $ok=move_uploaded_file($f['tmp_name'],$dest); ?>
    <tr><td>move_uploaded_file()</td><td><?= $ok?'<span class="ok">✅ THÀNH CÔNG!</span>':'<span class="err">❌ Thất bại ghi file!</span>' ?></td></tr>
    <?php if($ok): ?><tr><td>Ảnh vừa upload</td><td><img src="<?= url('uploads/products/'.basename($dest)) ?>" style="max-height:150px;border-radius:6px"></td></tr>
    <?php unlink($dest); endif; endif; endif; ?>
    </table></div>
    <?php endif; ?>
    <div class="box"><h2>📤 Chọn ảnh để test</h2>
    <form method="POST" enctype="multipart/form-data">
    <input type="file" name="img" accept="image/*" required>
    <button type="submit" style="background:#7c3aed;color:#fff;border:none;padding:8px 20px;border-radius:6px;cursor:pointer;margin-left:10px">🔍 Test Upload</button>
    </form></div>
    </body></html>
    <?php exit; }

// ---- Routing ----
// Admin routes
if ($controller === 'admin') {
    $adminAction = $segments[1] ?? 'dashboard';
    $adminParam  = $segments[2] ?? null;
    $adminParam2 = $segments[3] ?? null;
    routeAdmin($adminAction, $adminParam, $adminParam2);
    exit;
}

// API routes
if ($controller === 'api') {
    routeApi($action, $param);
    exit;
}

// Frontend routes
routeFrontend($controller, $action, $param);

// ================================================================
// FRONTEND ROUTER
// ================================================================
function routeFrontend(string $controller, string $action, ?string $param): void {
    switch ($controller) {
        case '':
        case 'home':
            require_once APP_PATH . '/controllers/HomeController.php';
            $c = new HomeController();
            $c->index();
            break;

        case 'products':
            require_once APP_PATH . '/controllers/ProductController.php';
            $c = new ProductController();
            if ($action === 'index' || $action === '') {
                $c->index();
            } else {
                $c->detail($action); // slug
            }
            break;

        case 'cart':
            require_once APP_PATH . '/controllers/CartController.php';
            $c = new CartController();
            switch ($action) {
                case 'checkout': $c->checkout(); break;
                case 'confirm':  $c->confirm();  break;
                case 'success':  $c->success();  break;
                default:         $c->index();    break;
            }
            break;

        case 'auth':
            require_once APP_PATH . '/controllers/AuthController.php';
            $c = new AuthController();
            switch ($action) {
                case 'login':         $c->login();        break;
                case 'register':      $c->register();     break;
                case 'logout':        $c->logout();       break;
                case 'forgot':        $c->forgot();       break;
                case 'reset':         $c->reset($param);  break;
                default:              $c->login();        break;
            }
            break;

        case 'user':
            if (!isLoggedIn()) {
                setFlash('warning', 'Vui lÃƒÂ²ng Ã„â€˜Ã„Æ’ng nháÂºÂ­p Ã„â€˜áÂ»Æ’ tiáÂºÂ¿p táÂ»Â¥c.');
                redirect(url('auth/login'));
            }
            require_once APP_PATH . '/controllers/UserController.php';
            $c = new UserController();
            switch ($action) {
                case 'profile':   $c->profile();        break;
                case 'orders':    $c->orders();         break;
                case 'order':     $c->orderDetail($param); break;
                case 'wishlist':  $c->wishlist();       break;
                case 'password':  $c->changePassword(); break;
                default:          $c->profile();        break;
            }
            break;

        case 'search':
            require_once APP_PATH . '/controllers/SearchController.php';
            $c = new SearchController();
            $c->index();
            break;

        case 'brand':
            require_once APP_PATH . '/controllers/ProductController.php';
            $c = new ProductController();
            $c->byBrand($action);
            break;

        case 'category':
            require_once APP_PATH . '/controllers/ProductController.php';
            $c = new ProductController();
            $c->byCategory($action);
            break;

        default:
            // 404
            http_response_code(404);
            require_once APP_PATH . '/views/layouts/404.php';
            break;
    }
}

// ================================================================
// ADMIN ROUTER
// ================================================================
function routeAdmin(string $action, ?string $param, ?string $param2): void {
    // Login admin khÃƒÂ´ng cáÂºÂ§n kiáÂ»Æ’m tra session
    if ($action === 'login' || $action === 'do-login') {
        require_once APP_PATH . '/controllers/AdminAuthController.php';
        $c = new AdminAuthController();
        $action === 'login' ? $c->loginForm() : $c->doLogin();
        return;
    }

    // BáÂºÂ£o váÂ»â€¡ táÂºÂ¥t cáÂºÂ£ route admin
    if (!isAdminLoggedIn()) {
        setFlash('warning', 'Vui lÃƒÂ²ng Ã„â€˜Ã„Æ’ng nháÂºÂ­p admin.');
        redirect(url('admin/login'));
    }

    switch ($action) {
        case 'logout':
            require_once APP_PATH . '/controllers/AdminAuthController.php';
            (new AdminAuthController())->logout();
            break;

        case 'dashboard':
        case '':
            require_once APP_PATH . '/controllers/AdminDashboardController.php';
            (new AdminDashboardController())->index();
            break;

        case 'products':
            require_once APP_PATH . '/controllers/AdminProductController.php';
            $c = new AdminProductController();
            switch ($param) {
                case 'create': $c->create(); break;
                case 'edit':   $c->edit($param2); break;
                case 'delete': $c->delete($param2); break;
                default:       $c->index(); break;
            }
            break;

        case 'categories':
            require_once APP_PATH . '/controllers/AdminCategoryController.php';
            $c = new AdminCategoryController();
            switch ($param) {
                case 'create': $c->create(); break;
                case 'edit':   $c->edit($param2); break;
                case 'delete': $c->delete($param2); break;
                default:       $c->index(); break;
            }
            break;

        case 'orders':
            require_once APP_PATH . '/controllers/AdminOrderController.php';
            $c = new AdminOrderController();
            if ($param === 'detail') {
                $c->detail($param2);
            } else {
                $c->index();
            }
            break;

        case 'banners':
            require_once APP_PATH . '/controllers/AdminBannerController.php';
            $c = new AdminBannerController();
            switch ($param) {
                case 'create': $c->create(); break;
                case 'edit':   $c->edit($param2); break;
                case 'delete': $c->delete($param2); break;
                default:       $c->index(); break;
            }
            break;

        case 'coupons':
            require_once APP_PATH . '/controllers/AdminCouponController.php';
            $c = new AdminCouponController();
            switch ($param) {
                case 'create': $c->create(); break;
                case 'edit':   $c->edit($param2); break;
                case 'delete': $c->delete($param2); break;
                default:       $c->index(); break;
            }
            break;

        case 'reviews':
            require_once APP_PATH . '/controllers/AdminReviewController.php';
            $c = new AdminReviewController();
            switch ($param) {
                case 'toggle': $c->toggle($param2); break;
                case 'delete': $c->delete($param2); break;
                default:       $c->index(); break;
            }
            break;

        case 'users':
            require_once APP_PATH . '/controllers/AdminUserController.php';
            $c = new AdminUserController();
            switch ($param) {
                case 'toggle': $c->toggle($param2); break;
                case 'delete': $c->delete($param2); break;
                default:       $c->index(); break;
            }
            break;

        default:
            http_response_code(404);
            require_once APP_PATH . '/views/layouts/404.php';
            break;
    }
}

// ================================================================
// API ROUTER (AJAX)
// ================================================================
function routeApi(string $action, ?string $param): void {
    header('Content-Type: application/json; charset=utf-8');

    switch ($action) {
        case 'cart':
            require_once __DIR__ . '/api/cart.php';
            break;
        case 'wishlist':
            require_once __DIR__ . '/api/wishlist.php';
            break;
        case 'search':
            require_once __DIR__ . '/api/search.php';
            break;
        case 'review':
            require_once __DIR__ . '/api/review.php';
            break;
        case 'coupon':
            require_once __DIR__ . '/api/coupon.php';
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'API not found']);
            break;
    }
}
