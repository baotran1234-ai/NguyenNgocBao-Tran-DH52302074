<?php
// app/controllers/CartController.php
require_once APP_PATH . '/models/ProductModel.php';
require_once APP_PATH . '/models/OrderModel.php'; // includes CouponModel & ReviewModel

class CartController {
    private ProductModel $productModel;
    private OrderModel   $orderModel;
    private CouponModel  $couponModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->orderModel   = new OrderModel();
        $this->couponModel  = new CouponModel();
    }

    // ================================================================
    // Helper: build cart items từ session + DB
    // ================================================================
    private function buildCartItems(): array {
        $sessionCart = $_SESSION['cart'] ?? [];
        $items = [];
        foreach ($sessionCart as $productId => $item) {
            $product = $this->productModel->getById((int)$productId);
            if (!$product) continue;
            $price = (float)($item['price'] ?? $product['sale_price'] ?? $product['price']);
            $qty   = (int)($item['quantity'] ?? 1);
            $items[] = [
                'product_id' => (int)$productId,
                'name'       => $product['name'],
                'slug'       => $product['slug'],
                'thumbnail'  => $product['thumbnail'],
                'price'      => $price,
                'quantity'   => $qty,
                'stock'      => (int)$product['stock'],
                'subtotal'   => $price * $qty,
            ];
        }
        return $items;
    }

    // ================================================================
    // Giỏ hàng
    // ================================================================
    public function index(): void {
        $cartItems = $this->buildCartItems();
        $total     = array_sum(array_column($cartItems, 'subtotal'));
        $pageTitle = 'Giỏ Hàng - LUXE Beauty';
        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/cart/index.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }

    // ================================================================
    // Trang thanh toán
    // ================================================================
    public function checkout(): void {
        if (!isLoggedIn()) {
            setFlash('warning', 'Vui lòng đăng nhập để tiến hành thanh toán.');
            redirect(url('auth/login'));
        }

        $cartItems = $this->buildCartItems();
        if (empty($cartItems)) {
            setFlash('warning', 'Giỏ hàng trống!');
            redirect(url('cart'));
        }

        $subtotal    = array_sum(array_column($cartItems, 'subtotal'));
        $discount    = 0;
        $couponCode  = $_SESSION['coupon_code'] ?? null;
        $couponId    = null;

        // Áp dụng coupon nếu có trong session
        if ($couponCode) {
            $coupon = $this->couponModel->getByCode($couponCode);
            if ($coupon) {
                $result = $this->couponModel->validate($coupon, $subtotal, $_SESSION['user_id']);
                if ($result['valid']) {
                    $discount  = $result['discount'];
                    $couponId  = $coupon['id'];
                }
            }
        }

        $shippingFee = $subtotal >= FREE_SHIPPING_OVER ? 0 : SHIPPING_FEE;
        $total       = $subtotal - $discount + $shippingFee;

        $user      = currentUser();
        $pageTitle = 'Thanh Toán - LUXE Beauty';
        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/cart/checkout.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }

    // ================================================================
    // Xác nhận đặt hàng (POST)
    // ================================================================
    public function confirm(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(url('cart'));
        }
        if (!isLoggedIn()) {
            setFlash('warning', 'Vui lòng đăng nhập để đặt hàng.');
            redirect(url('auth/login'));
        }

        $cartItems = $this->buildCartItems();
        if (empty($cartItems)) {
            setFlash('warning', 'Giỏ hàng trống!');
            redirect(url('cart'));
        }

        // Validate input
        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $phone   = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $city    = trim($_POST['city'] ?? '');
        $district = trim($_POST['district'] ?? '');
        $note    = trim($_POST['note'] ?? '');
        $paymentMethod = $_POST['payment_method'] ?? 'cod';

        if (!$name || !$phone || !$address) {
            setFlash('error', 'Vui lòng điền đầy đủ thông tin giao hàng.');
            redirect(url('cart/checkout'));
        }

        // Tính tiền
        $subtotal    = array_sum(array_column($cartItems, 'subtotal'));
        $discount    = 0;
        $couponId    = null;
        $couponCode  = $_SESSION['coupon_code'] ?? null;

        if ($couponCode) {
            $coupon = $this->couponModel->getByCode($couponCode);
            if ($coupon) {
                $result = $this->couponModel->validate($coupon, $subtotal, $_SESSION['user_id']);
                if ($result['valid']) {
                    $discount = $result['discount'];
                    $couponId = $coupon['id'];
                }
            }
        }

        $shippingFee = $subtotal >= FREE_SHIPPING_OVER ? 0 : SHIPPING_FEE;
        $total       = $subtotal - $discount + $shippingFee;
        $orderCode   = 'ORD-' . strtoupper(substr(uniqid(), -6)) . '-' . date('ymd');

        try {
            $orderId = $this->orderModel->create([
                'order_code'     => $orderCode,
                'user_id'        => $_SESSION['user_id'],
                'coupon_id'      => $couponId,
                'name'           => $name,
                'email'          => $email,
                'phone'          => $phone,
                'address'        => $address,
                'city'           => $city,
                'district'       => $district,
                'note'           => $note,
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'shipping_fee'   => $shippingFee,
                'total'          => $total,
                'payment_method' => $paymentMethod,
            ], $cartItems);

            // Tăng used_count coupon
            if ($couponId) {
                $this->couponModel->incrementUsed($couponId);
            }

            // Xóa giỏ + coupon trong session
            unset($_SESSION['cart'], $_SESSION['coupon_code']);

            // Lưu order code để show trang success
            $_SESSION['last_order_code'] = $orderCode;

            redirect(url('cart/success'));

        } catch (\Throwable $e) {
            // Log lỗi thực tế vào file
            error_log('[CartController::confirm] ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
            setFlash('error', 'Đặt hàng thất bại: ' . $e->getMessage());
            redirect(url('cart/checkout'));
        }
    }

    // ================================================================
    // Đặt hàng thành công
    // ================================================================
    public function success(): void {
        $orderCode = $_SESSION['last_order_code'] ?? null;
        $order     = null;

        if ($orderCode) {
            $order = $this->orderModel->getByCode($orderCode);
            unset($_SESSION['last_order_code']);
        }

        $pageTitle = 'Đặt Hàng Thành Công - LUXE Beauty';
        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/cart/success.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }
}
