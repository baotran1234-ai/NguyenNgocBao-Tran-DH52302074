<?php
// ================================================================
// config/config.php - Cấu hình ứng dụng
// ================================================================

define('APP_NAME', 'LUXE Beauty');
define('APP_URL',  'http://websitebanmypham.rf.gd');
define('APP_VERSION', '1.0.0');

define('ROOT_PATH',   __DIR__ . '/..');
define('APP_PATH',    ROOT_PATH . '/app');
define('UPLOAD_PATH', ROOT_PATH . '/uploads');
define('ASSET_URL',   APP_URL . '/assets');

define('ITEMS_PER_PAGE', 12);
define('MAX_FILE_SIZE',      5 * 1024 * 1024);
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/jpg', 'image/pjpeg', 'image/x-png']);
define('SHIPPING_FEE',        30000);
define('FREE_SHIPPING_OVER', 500000);
define('APP_ENV', 'production');

if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}