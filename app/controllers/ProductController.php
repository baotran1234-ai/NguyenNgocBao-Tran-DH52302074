<?php
// ================================================================
// app/controllers/ProductController.php
// ================================================================
require_once APP_PATH . '/models/ProductModel.php';
require_once APP_PATH . '/models/OtherModels.php';
require_once APP_PATH . '/models/OrderModel.php';

class ProductController {
    private ProductModel $productModel;
    private CategoryModel $categoryModel;
    private BrandModel $brandModel;

    public function __construct() {
        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->brandModel    = new BrandModel();
    }

    public function index(): void {
        $filters = [
            'category'  => $_GET['category']   ?? '',
            'brand'     => $_GET['brand']       ?? '',
            'search'    => $_GET['q']           ?? '',
            'min_price' => $_GET['min_price']   ?? '',
            'max_price' => $_GET['max_price']   ?? '',
            'sort'      => $_GET['sort']        ?? 'newest',
        ];
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $result  = $this->productModel->getAll($filters, $page);
        $products = $result['data'];
        $pagination = paginate($result['total'], ITEMS_PER_PAGE, $page, url('products') . '?' . http_build_query(array_merge($filters, ['page'=>'%d'])));
        $categories = $this->categoryModel->getAll();
        $brands     = $this->brandModel->getAll();
        $pageTitle  = 'Tất Cả sản phẩm - LUXE Beauty';

        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/products/index.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }

    public function detail(string $slug): void {
        require_once APP_PATH . '/models/OrderModel.php'; // ReviewModel
        $product = $this->productModel->getBySlug($slug);
        if (!$product) {
            http_response_code(404);
            require_once APP_PATH . '/views/layouts/404.php';
            return;
        }

        $related  = $this->productModel->getRelated($product['id'], $product['category_id']);
        $reviewModel = new ReviewModel();
        $reviewsData = $reviewModel->getByProduct($product['id']);
        $reviews     = $reviewsData['data'];

        $canReview = false;
        if (isLoggedIn()) {
            $canReview = $reviewModel->userCanReview($_SESSION['user_id'], $product['id']);
        }

        $pageTitle = e($product['name']) . ' - LUXE Beauty';
        $pageDesc  = truncate($product['description'] ?? '', 160);

        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/products/detail.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }

    public function byCategory(string $slug): void {
        $category = $this->categoryModel->getBySlug($slug);
        if (!$category) redirect(url('products'));

        $filters   = ['category' => $slug, 'sort' => $_GET['sort'] ?? 'newest'];
        $page      = max(1, (int)($_GET['page'] ?? 1));
        $result    = $this->productModel->getAll($filters, $page);
        $products  = $result['data'];
        $pagination = paginate($result['total'], ITEMS_PER_PAGE, $page, url('category/' . $slug) . '?page=%d');
        $categories = $this->categoryModel->getAll();
        $brands     = $this->brandModel->getAll();
        $pageTitle  = e($category['name']) . ' - LUXE Beauty';

        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/products/index.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }

    public function byBrand(string $slug): void {
        $brand = $this->brandModel->getBySlug($slug);
        if (!$brand) redirect(url('products'));

        $filters   = ['brand' => $slug, 'sort' => $_GET['sort'] ?? 'newest'];
        $page      = max(1, (int)($_GET['page'] ?? 1));
        $result    = $this->productModel->getAll($filters, $page);
        $products  = $result['data'];
        $pagination = paginate($result['total'], ITEMS_PER_PAGE, $page, url('brand/' . $slug) . '?page=%d');
        $categories = $this->categoryModel->getAll();
        $brands     = $this->brandModel->getAll();
        $pageTitle  = e($brand['name']) . ' - LUXE Beauty';

        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/products/index.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }
}

// ================================================================
// app/controllers/CartController.php
// ================================================================
class CartController {
    public function index(): void {
        $cartItems = $this->getCartWithDetails();
        $total     = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cartItems));
        $pageTitle = 'Giỏ Hàng - LUXE Beauty';

        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/cart/index.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }

    public function checkout(): void {
        $cartItems = $this->getCartWithDetails();
        if (empty($cartItems)) {
            setFlash('warning', 'Giỏ hàng trá»‘ng!');
            redirect(url('cart'));
        }

        $subtotal    = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cartItems));
        $shippingFee = $subtotal >= FREE_SHIPPING_OVER ? 0 : SHIPPING_FEE;
        $discount    = (float)($_SESSION['coupon']['discount'] ?? 0);
        $total       = $subtotal + $shippingFee - $discount;

        $user     = currentUser();
        $pageTitle = 'Thanh Toán - LUXE Beauty';

        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/cart/checkout.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }

    public function confirm(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(url('cart/checkout'));
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Yêu cầu Không hợp lá»‡.');
            redirect(url('cart/checkout'));
        }

        $cartItems = $this->getCartWithDetails();
        if (empty($cartItems)) redirect(url('cart'));

        $subtotal    = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cartItems));
        $shippingFee = $subtotal >= FREE_SHIPPING_OVER ? 0 : SHIPPING_FEE;
        $discount    = (float)($_SESSION['coupon']['discount'] ?? 0);
        $total       = $subtotal + $shippingFee - $discount;

        $orderData = [
            'order_code'     => generateOrderCode(),
            'user_id'        => isLoggedIn() ? $_SESSION['user_id'] : null,
            'coupon_id'      => $_SESSION['coupon']['id'] ?? null,
            'name'           => trim($_POST['name'] ?? ''),
            'email'          => trim($_POST['email'] ?? ''),
            'phone'          => trim($_POST['phone'] ?? ''),
            'address'        => trim($_POST['address'] ?? ''),
            'city'           => trim($_POST['city'] ?? ''),
            'district'       => trim($_POST['district'] ?? ''),
            'ward'           => trim($_POST['ward'] ?? ''),
            'note'           => trim($_POST['note'] ?? ''),
            'payment_method' => $_POST['payment_method'] ?? 'cod',
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'shipping_fee'   => $shippingFee,
            'total'          => $total,
        ];

        // Validate
        $errors = [];
        if (empty($orderData['name']))    $errors[] = 'Vui lòng nhập họ tên.';
        if (!filter_var($orderData['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Email Không hợp lá»‡.';
        if (empty($orderData['phone']))   $errors[] = 'Vui lòng nhập sá»‘ Ä‘iá»‡n thoại.';
        if (empty($orderData['address'])) $errors[] = 'Vui lòng nhập Ä‘á»‹a chá»‰.';

        if (!empty($errors)) {
            setFlash('error', implode('<br>', $errors));
            redirect(url('cart/checkout'));
        }

        try {
            require_once APP_PATH . '/models/OrderModel.php';
            $orderModel = new OrderModel();
            $orderId    = $orderModel->create($orderData, $cartItems);
            $order      = $orderModel->getById($orderId);

            // TÄƒng coupon used count
            if (!empty($_SESSION['coupon']['id'])) {
                require_once APP_PATH . '/models/OrderModel.php';
                (new CouponModel())->incrementUsed($_SESSION['coupon']['id']);
            }

            // Xóa giỏ hàng
            unset($_SESSION['cart'], $_SESSION['coupon']);

            $_SESSION['last_order'] = $order;
            redirect(url('cart/success'));
        } catch (Exception $e) {
            setFlash('error', 'Có lá»—i xảy ra khi Ä‘ặt hàng. Vui lòng thử lại.');
            redirect(url('cart/checkout'));
        }
    }

    public function success(): void {
        $order = $_SESSION['last_order'] ?? null;
        if (!$order) redirect(url(''));

        $pageTitle = 'Đặt Hàng Thành Công - LUXE Beauty';
        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/cart/success.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }

    private function getCartWithDetails(): array {
        $cart    = $_SESSION['cart'] ?? [];
        if (empty($cart)) return [];

        require_once APP_PATH . '/models/ProductModel.php';
        $productModel = new ProductModel();
        $result = [];

        foreach ($cart as $productId => $item) {
            $product = $productModel->getById((int)$productId);
            if ($product) {
                $result[] = [
                    'product_id' => $product['id'],
                    'name'       => $product['name'],
                    'thumbnail'  => $product['thumbnail'],
                    'price'      => $product['sale_price'] ?? $product['price'],
                    'quantity'   => $item['quantity'],
                    'slug'       => $product['slug'],
                    'stock'      => $product['stock'],
                ];
            }
        }
        return $result;
    }
}
