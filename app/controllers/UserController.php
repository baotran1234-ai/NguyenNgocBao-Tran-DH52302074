<?php
// app/controllers/UserController.php
require_once APP_PATH . '/models/UserModel.php';
require_once APP_PATH . '/models/OrderModel.php';
require_once APP_PATH . '/models/ProductModel.php';

class UserController {
    private UserModel $userModel;
    private OrderModel $orderModel;
    private ProductModel $productModel;

    public function __construct() {
        if (!isLoggedIn()) redirect(url('auth/login'));
        $this->userModel    = new UserModel();
        $this->orderModel   = new OrderModel();
        $this->productModel = new ProductModel();
    }

    public function profile(): void {
        $user = currentUser();
        if (!$user && isset($_SESSION['user_id'])) {
            $user = $this->userModel->findById($_SESSION['user_id']);
            $_SESSION['user'] = $user;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name'  => trim($_POST['name'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
            ];
            
            if (empty($data['name'])) {
                setFlash('error', 'Họ tên Không Ä‘ược Ä‘á»ƒ trá»‘ng.');
            } else {
                $this->userModel->update($_SESSION['user_id'], $data);
                $_SESSION['user']['name'] = $data['name'];
                $_SESSION['user']['phone'] = $data['phone'];
                setFlash('success', 'Cập nhật thông tin thành công!');
                redirect(url('user/profile'));
            }
        }
        
        $pageTitle = 'Há»“ Sơ Của Tôi - LUXE Beauty';
        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/user/layout.php';
        require_once APP_PATH . '/views/user/profile.php';
        require_once APP_PATH . '/views/user/layout_end.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }

    public function changePassword(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $oldPass = $_POST['old_password'] ?? '';
            $newPass = $_POST['new_password'] ?? '';
            $confPass= $_POST['confirm_password'] ?? '';

            if (!$this->userModel->verifyPassword($oldPass, currentUser()['password'])) {
                setFlash('error', 'Mật khẩu cÅ© Không chính xác.');
            } elseif (strlen($newPass) < 6) {
                setFlash('error', 'Mật khẩu má»›i phải từ 6 ký tự.');
            } elseif ($newPass !== $confPass) {
                setFlash('error', 'Xác nhận mật khẩu Không khá»›p.');
            } else {
                $this->userModel->updatePassword($_SESSION['user_id'], $newPass);
                setFlash('success', 'Đá»•i mật khẩu thành công!');
            }
            redirect(url('user/profile'));
        }
    }

    public function orders(): void {
        $orders = $this->orderModel->getUserOrders($_SESSION['user_id']);
        $pageTitle = 'Lá»‹ch Sử Đơn Hàng - LUXE Beauty';
        
        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/user/layout.php';
        require_once APP_PATH . '/views/user/orders.php';
        require_once APP_PATH . '/views/user/layout_end.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }

    public function orderDetail(int $id): void {
        $order = $this->orderModel->getById($id);
        if (!$order || $order['user_id'] !== $_SESSION['user_id']) {
            redirect(url('user/orders'));
        }
        
        $items = $this->orderModel->getItems($id);
        $pageTitle = 'Chi Tiết Đơn Hàng #' . $order['order_code'];
        
        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/user/layout.php';
        require_once APP_PATH . '/views/user/order_detail.php';
        require_once APP_PATH . '/views/user/layout_end.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }

    public function wishlist(): void {
        $products = $this->productModel->getUserWishlist($_SESSION['user_id']);
        $pageTitle = 'sản phẩm Yêu Thích - LUXE Beauty';
        
        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/user/layout.php';
        require_once APP_PATH . '/views/user/wishlist.php';
        require_once APP_PATH . '/views/user/layout_end.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }
}
