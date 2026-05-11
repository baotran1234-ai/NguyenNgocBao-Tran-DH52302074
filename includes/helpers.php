<?php
// ================================================================
// includes/helpers.php - Hàm tiá»‡n ích toàn cục
// ================================================================

// ---- Format tiền VND ----
function formatPrice(float $price): string {
    return number_format($price, 0, ',', '.') . '₫';
}

// ---- Tính % giảm giá ----
function discountPercent(float $original, float $sale): int {
    if ($original <= 0) return 0;
    return (int) round((($original - $sale) / $original) * 100);
}

// ---- Tạo slug ----
function makeSlug(string $text): string {
    $text = mb_strtolower($text, 'UTF-8');
    $map  = [
        'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a',
        'è'=>'e','é'=>'e','ê'=>'e','ë'=>'e',
        'ì'=>'i','í'=>'i','î'=>'i','ï'=>'i',
        'ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o',
        'ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u',
        'ý'=>'y','ÿ'=>'y','ñ'=>'n','ç'=>'c',
        'Ä‘'=>'d',
        'ả'=>'a','ắ'=>'a','ặ'=>'a','ằ'=>'a','ẳ'=>'a','ẵ'=>'a',
        'ầ'=>'a','ấ'=>'a','ậ'=>'a','ẩ'=>'a','ẫ'=>'a',
        'ẻ'=>'e','ế'=>'e','á»‡'=>'e','ề'=>'e','á»ƒ'=>'e','á»…'=>'e',
        'á»‰'=>'i','á»‹'=>'i',
        'ỏ'=>'o','á»‘'=>'o','á»™'=>'o','á»“'=>'o','á»•'=>'o','á»—'=>'o',
        'á»›'=>'o','ợ'=>'o','ờ'=>'o','á»Ÿ'=>'o','ỡ'=>'o',
        'ủ'=>'u','ứ'=>'u','ụ'=>'u','ừ'=>'u','ử'=>'u','ữ'=>'u',
        'ư'=>'u',
        'ỳ'=>'y','ỵ'=>'y','ỷ'=>'y','ỹ'=>'y',
        'ơ'=>'o','Äƒ'=>'a',
    ];
    $text = strtr($text, $map);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', trim($text));
    return $text;
}

// ---- Escape HTML output ----
function e($str): string {
    return htmlspecialchars((string)($str ?? ''), ENT_QUOTES, 'UTF-8');
}

// ---- Redirect ----
function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

// ---- Lấy URL hiá»‡n tại ----
function currentUrl(): string {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

// ---- Tạo URL từ path ----
function url(string $path = ''): string {
    return rtrim(APP_URL, '/') . '/' . ltrim($path, '/');
}

// ---- Tạo URL asset ----
function asset(string $path): string {
    return rtrim(APP_URL, '/') . '/assets/' . ltrim($path, '/');
}

// ---- Tạo URL upload ----
function uploadUrl(string $path): string {
    if (empty($path)) return '';
    // Đã là URL đầy đủ
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }
    // Path bắt đầu bằng /assets/ → dùng trực tiếp với APP_URL
    if (str_starts_with($path, '/assets/')) {
        return rtrim(APP_URL, '/') . $path;
    }
    // Path bắt đầu bằng / → dùng trực tiếp
    if (str_starts_with($path, '/')) {
        return rtrim(APP_URL, '/') . $path;
    }
    // Path tương đối → ghép với /uploads/
    return rtrim(APP_URL, '/') . '/uploads/' . ltrim($path, '/');
}

// ---- Upload ảnh ----
function uploadImage(array $file, string $folder = 'products') {
    // Step 1: PHP upload error check
    if ($file['error'] !== UPLOAD_ERR_OK) {
        error_log('[uploadImage] PHP upload error code: ' . $file['error']);
        return false;
    }

    // Step 2: File size (5MB max)
    if ($file['size'] > MAX_FILE_SIZE) {
        error_log('[uploadImage] File too large: ' . round($file['size']/1024/1024, 2) . 'MB');
        return false;
    }

    $tmpFile = $file['tmp_name'];
    if (!file_exists($tmpFile)) {
        error_log('[uploadImage] Temp file does not exist: ' . $tmpFile);
        return false;
    }

    // Step 3: Detect image type using multiple fallback methods
    $detectedType = null; // will be one of: 'jpeg','png','gif','webp'

    // Method A: Read first bytes of file to detect magic bytes (most reliable)
    $handle = fopen($tmpFile, 'rb');
    $magic  = fread($handle, 12);
    fclose($handle);

    if (substr($magic, 0, 4) === "\xFF\xD8\xFF\xE0" || substr($magic, 0, 4) === "\xFF\xD8\xFF\xE1" || substr($magic, 0, 3) === "\xFF\xD8\xFF") {
        $detectedType = 'jpeg';
    } elseif (substr($magic, 0, 8) === "\x89PNG\r\n\x1a\n") {
        $detectedType = 'png';
    } elseif (substr($magic, 0, 6) === 'GIF87a' || substr($magic, 0, 6) === 'GIF89a') {
        $detectedType = 'gif';
    } elseif (substr($magic, 0, 4) === 'RIFF' && substr($magic, 8, 4) === 'WEBP') {
        $detectedType = 'webp';
    }

    // Method B: fallback via finfo if magic bytes didn't work
    if (!$detectedType && function_exists('finfo_open')) {
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $realMime = finfo_file($finfo, $tmpFile);
        finfo_close($finfo);
        $mimeMap = [
            'image/jpeg' => 'jpeg', 'image/pjpeg' => 'jpeg',
            'image/png'  => 'png',  'image/x-png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'webp',
        ];
        $detectedType = $mimeMap[$realMime] ?? null;
    }

    // Method C: last resort — trust browser MIME type (only for known image types)
    if (!$detectedType) {
        $browserMimeMap = [
            'image/jpeg' => 'jpeg', 'image/jpg' => 'jpeg', 'image/pjpeg' => 'jpeg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'webp',
        ];
        $detectedType = $browserMimeMap[$file['type']] ?? null;
    }

    if (!$detectedType) {
        error_log('[uploadImage] Could not determine image type for: ' . $file['name'] . ' browser-mime=' . $file['type']);
        return false;
    }

    // Step 4: Build safe filename using detected (not browser-supplied) type
    $filename  = uniqid('img_', true) . '.' . $detectedType;
    $uploadDir = UPLOAD_PATH . '/' . $folder . '/';

    // Step 5: Create directory if needed
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            error_log('[uploadImage] Cannot create dir: ' . $uploadDir);
            return false;
        }
    }

    // Step 6: Move uploaded file
    $dest = $uploadDir . $filename;
    if (!move_uploaded_file($tmpFile, $dest)) {
        error_log('[uploadImage] move_uploaded_file failed → dest=' . $dest);
        return false;
    }

    return $folder . '/' . $filename;
}

// ---- Tạo mã Ä‘ơn hàng ----
function generateOrderCode(): string {
    return 'ORD' . strtoupper(uniqid());
}

// ---- Rating sao HTML ----
function renderStars(float $rating, bool $interactive = false): string {
    $html = '<div class="stars">';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= floor($rating)) {
            $html .= '<i class="star full">â˜…</i>';
        } elseif ($i - $rating < 1 && $i - $rating > 0) {
            $html .= '<i class="star half">â˜…</i>';
        } else {
            $html .= '<i class="star empty">â˜†</i>';
        }
    }
    $html .= '</div>';
    return $html;
}

// ---- Truncate text ----
function truncate(string $text, int $length = 100, string $suffix = '...'): string {
    $text = strip_tags($text);
    if (mb_strlen($text, 'UTF-8') <= $length) return $text;
    return mb_substr($text, 0, $length, 'UTF-8') . $suffix;
}

// ---- Đá»‹nh dạng ngày giờ tiếng Viá»‡t ----
function formatDate(string $date, string $format = 'd/m/Y'): string {
    return date($format, strtotime($date));
}

function formatDateTime(string $date): string {
    return date('H:i d/m/Y', strtotime($date));
}

// ---- Lấy sá»‘ lượng giỏ hàng ----
function getCartCount(): int {
    if (!isset($_SESSION)) return 0;
    $cart = $_SESSION['cart'] ?? [];
    return array_sum(array_column($cart, 'quantity'));
}

// ---- Lấy tá»•ng giỏ hàng ----
function getCartTotal(): float {
    if (!isset($_SESSION)) return 0;
    $cart  = $_SESSION['cart'] ?? [];
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// ---- Trạng thái Ä‘ơn hàng ----
function orderStatusLabel(string $status): string {
    $labels = [
        'pending'    => '<span class="badge badge-warning">Chờ xác nhận</span>',
        'confirmed'  => '<span class="badge badge-info">Đã xác nhận</span>',
        'processing' => '<span class="badge badge-info">Đang xử lý</span>',
        'shipping'   => '<span class="badge badge-primary">Đang giao</span>',
        'delivered'  => '<span class="badge badge-success">Đã giao</span>',
        'cancelled'  => '<span class="badge badge-danger">Đã hủy</span>',
    ];
    return $labels[$status] ?? '<span class="badge">Không rõ</span>';
}

// ---- Pagination ----
function paginate(int $total, int $perPage, int $currentPage, string $urlPattern): array {
    $totalPages = (int) ceil($total / $perPage);
    return [
        'total'       => $total,
        'per_page'    => $perPage,
        'current'     => $currentPage,
        'total_pages' => $totalPages,
        'has_prev'    => $currentPage > 1,
        'has_next'    => $currentPage < $totalPages,
        'prev_page'   => max(1, $currentPage - 1),
        'next_page'   => min($totalPages, $currentPage + 1),
        'url_pattern' => $urlPattern,
    ];
}

// ---- CSRF Token ----
function csrfToken(): string {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField(): string {
    return '<input type="hidden" name="csrf_token" value="' . csrfToken() . '">';
}

function verifyCsrfToken(string $token): bool {
    if (empty($token) || empty($_SESSION['csrf_token'])) return false;
    $valid = hash_equals($_SESSION['csrf_token'], $token);
    // Regenerate token sau khi verify
    if ($valid) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $valid;
}
