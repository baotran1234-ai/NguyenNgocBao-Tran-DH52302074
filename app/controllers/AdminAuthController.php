<?php
// app/controllers/AdminAuthController.php
require_once APP_PATH . '/models/UserModel.php';

class AdminAuthController {
    private AdminModel $adminModel;
    public function __construct() { $this->adminModel = new AdminModel(); }

    public function loginForm(): void {
        if (isAdminLoggedIn()) redirect(url('admin/dashboard'));
        // Chỉ hiện lỗi nếu có flash message, KHÔNG set mặc định
        $error = $_SESSION['admin_login_error'] ?? '';
        unset($_SESSION['admin_login_error']);
        $pageTitle = 'Admin Login - LUXE Beauty';
        require_once APP_PATH . '/views/admin/login.php';
    }

    public function doLogin(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(url('admin/login'));
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $admin    = $this->adminModel->findByEmail($email);

        if ($admin && $this->adminModel->verifyPassword($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin']    = ['id'=>$admin['id'],'name'=>$admin['name'],'email'=>$admin['email'],'role'=>$admin['role']];
            $this->adminModel->updateLastLogin($admin['id']);
            regenerateSession();
            redirect(url('admin/dashboard'));
        }
        // Lưu lỗi vào session rồi redirect để tránh re-submit form
        $_SESSION['admin_login_error'] = 'Email hoặc mật khẩu không đúng!';
        redirect(url('admin/login'));
    }

    public function logout(): void {
        // Xóa toàn bộ thông tin admin khỏi session
        unset($_SESSION['admin_id'], $_SESSION['admin']);
        // Thêm flash message thông báo đã đăng xuất
        $_SESSION['admin_login_success'] = 'Bạn đã đăng xuất thành công!';
        redirect(url('admin/login'));
    }
}

// ================================================================
// app/controllers/AdminDashboardController.php
// ================================================================
class AdminDashboardController {
    public function index(): void {
        require_once APP_PATH . '/models/OrderModel.php';
        require_once APP_PATH . '/models/UserModel.php';
        require_once APP_PATH . '/models/ProductModel.php';

        $orderModel = new OrderModel();
        $stats = $orderModel->getStats();
        $stats['total_products'] = (int)db()->query("SELECT COUNT(*) FROM products")->fetchColumn();
        $stats['total_users']    = (int)db()->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $recentOrders = $orderModel->getAll([], 1, 10)['data'];
        $revenueChart = $orderModel->getRevenueChart(30);

        $pageTitle = 'Dashboard - Admin LUXE Beauty';
        require_once APP_PATH . '/views/admin/layout.php';
        require_once APP_PATH . '/views/admin/dashboard.php';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }
}
