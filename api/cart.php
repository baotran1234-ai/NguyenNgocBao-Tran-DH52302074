<?php
// ================================================================
// api/cart.php - Giỏ hàng AJAX API (Bản siêu sạch)
// ================================================================

// NgÄƒn chặn mọi output rác trưá»›c Ä‘ó
while (ob_get_level()) ob_end_clean();
ob_start();

header('Content-Type: application/json; charset=utf-8');

try {
    require_once APP_PATH . '/models/ProductModel.php';
    
    $action = $_POST['action'] ?? $_GET['action'] ?? '';
    $productModel = new ProductModel();
    $res = ['success' => false, 'message' => 'Yêu cầu Không hợp lá»‡.'];

    switch ($action) {
        case 'add':
            if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
            
            $productId = (int)($_POST['product_id'] ?? 0);
            $quantity  = max(1, (int)($_POST['quantity'] ?? 1));

            if (!$productId) throw new Exception('sản phẩm Không hợp lá»‡.');

            $product = $productModel->getById($productId);
            if (!$product) throw new Exception('sản phẩm Không tá»“n tại.');
            if ($product['stock'] < 1) throw new Exception('sản phẩm Ä‘ã hết hàng.');

            $currentQty = $_SESSION['cart'][$productId]['quantity'] ?? 0;
            $newQty     = $currentQty + $quantity;

            if ($newQty > $product['stock']) {
                throw new Exception('Chá»‰ còn ' . $product['stock'] . ' sản phẩm trong kho.');
            }

            $_SESSION['cart'][$productId] = [
                'quantity' => $newQty,
                'price'    => $product['sale_price'] ?? $product['price'],
            ];

            $res = [
                'success'    => true,
                'message'    => 'Đã thêm vào giỏ hàng!',
                'cart_count' => getCartCount()
            ];
            break;

        case 'remove':
            $productId = (int)($_POST['product_id'] ?? 0);
            unset($_SESSION['cart'][$productId]);
            $res = ['success' => true, 'cart_count' => getCartCount()];
            break;
            
        case 'get':
            $res = ['success' => true, 'cart_count' => getCartCount()];
            break;
    }
} catch (Throwable $e) {
    $res = ['success' => false, 'message' => $e->getMessage()];
}

// Xóa sạch buffer nếu có lá»—i hiá»ƒn thá»‹ (warning/notice)
if (ob_get_length()) ob_clean();
echo json_encode($res, JSON_UNESCAPED_UNICODE);
exit;