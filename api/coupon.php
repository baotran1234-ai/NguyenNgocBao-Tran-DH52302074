<?php
// api/coupon.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once APP_PATH . '/models/OrderModel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu Không hợp lá»‡']);
    exit;
}

$code  = trim($_POST['code'] ?? '');
$total = (float)($_POST['total'] ?? 0);
$userId = $_SESSION['user_id'] ?? 0;

if (empty($code)) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập mã giảm giá']);
    exit;
}

$couponModel = new CouponModel();
$coupon = $couponModel->getByCode($code);

if (!$coupon) {
    echo json_encode(['success' => false, 'message' => 'Mã giảm giá Không tá»“n tại hoặc Ä‘ã hết hạn']);
    exit;
}

$validation = $couponModel->validate($coupon, $total, $userId);

if (!$validation['valid']) {
    echo json_encode(['success' => false, 'message' => $validation['message']]);
    exit;
}

$discount = $validation['discount'];

// Save to session
$_SESSION['coupon'] = [
    'id'       => $coupon['id'],
    'code'     => $coupon['code'],
    'discount' => $discount,
    'type'     => $coupon['type'],
    'value'    => $coupon['value']
];

echo json_encode([
    'success'  => true,
    'message'  => 'Áp dụng mã giảm giá thành công!',
    'discount' => $discount,
    'total'    => max(0, $total - $discount)
]);
