<?php
// ================================================================
// app/controllers/AdminDashboardController.php
// ================================================================
require_once APP_PATH . '/models/OrderModel.php';

class AdminDashboardController {
    private OrderModel $orderModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
    }

    public function index(): void {
        // Lấy thá»‘ng kê từ OrderModel
        $stats = $this->orderModel->getStats();
        
        // Bá»• sung thêm thá»‘ng kê sản phẩm và Khách hàng
        $stats['total_products'] = (int)db()->query("SELECT COUNT(*) FROM products")->fetchColumn();
        $stats['total_users']    = (int)db()->query("SELECT COUNT(*) FROM users")->fetchColumn();

        // Lấy biá»ƒu Ä‘á»“ doanh thu 30 ngày
        $revenueChart = $this->orderModel->getRevenueChart(30);

        // Lấy 10 Ä‘ơn hàng gần nhất
        $recentOrdersReq = $this->orderModel->getAll([], 1, 10);
        $recentOrders = $recentOrdersReq['data'] ?? [];

        $pageTitle = 'Admin Dashboard - LUXE Beauty';
        $activeMenu = 'dashboard';

        require_once APP_PATH . '/views/admin/layout.php';
        require_once APP_PATH . '/views/admin/dashboard.php';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }
}
