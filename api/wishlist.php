<?php
// api/wishlist.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../includes/helpers.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['action']) || $_POST['action'] !== 'toggle') {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu Không hợp lá»‡']);
    exit;
}

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'redirect' => url('auth/login')]);
    exit;
}

$productId = (int)($_POST['product_id'] ?? 0);
$userId    = $_SESSION['user_id'];

if (!$productId) {
    echo json_encode(['success' => false, 'message' => 'Thiếu ID sản phẩm']);
    exit;
}

try {
    $db = db();
    $stmt = $db->prepare("SELECT 1 FROM wishlist WHERE user_id = :uid AND product_id = :pid");
    $stmt->execute([':uid' => $userId, ':pid' => $productId]);
    
    if ($stmt->fetch()) {
        $del = $db->prepare("DELETE FROM wishlist WHERE user_id = :uid AND product_id = :pid");
        $del->execute([':uid' => $userId, ':pid' => $productId]);
        echo json_encode(['success' => true, 'message' => 'Đã bỏ khỏi danh sách yêu thích', 'status' => 'removed']);
    } else {
        $ins = $db->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (:uid, :pid)");
        $ins->execute([':uid' => $userId, ':pid' => $productId]);
        echo json_encode(['success' => true, 'message' => 'Đã thêm vào danh sách yêu thích', 'status' => 'added']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lá»—i há»‡ thá»‘ng']);
}
