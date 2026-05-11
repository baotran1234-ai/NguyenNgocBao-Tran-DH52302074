<?php
// app/controllers/AdminOrderController.php
require_once APP_PATH . '/models/OrderModel.php';

class AdminOrderController {
    private OrderModel $orderModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
    }

    public function index(): void {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $status = $_GET['status'] ?? '';
        $search = $_GET['search'] ?? '';

        $filters = [];
        if ($status) $filters['status'] = $status;
        if ($search) $filters['search'] = $search;

        $perPage = 4;
        $result = $this->orderModel->getAll($filters, $page, $perPage);
        $orders = $result['data'];
        $total = $result['total'];

        $pageTitle = 'Quản Lý Đơn Hàng - Admin LUXE Beauty';
        $activeMenu = 'orders';
        
        require_once APP_PATH . '/views/admin/layout.php';
        require_once APP_PATH . '/views/admin/orders/index.php';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }

    public function detail($id = null): void {
        if (!$id) redirect(url('admin/orders'));
        $order = $this->orderModel->getById((int)$id);
        if (!$order) redirect(url('admin/orders'));

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
            $status = $_POST['status'];
            $reason = $_POST['cancel_reason'] ?? null;
            $this->orderModel->updateStatus((int)$id, $status, $reason);
            setFlash('success', 'Cập nhật trạng thái đơn hàng thành công!');
            redirect(url("admin/orders/detail/$id"));
        }

        $pageTitle = 'Chi Tiết Đơn Hàng ' . $order['order_code'];
        $activeMenu = 'orders';
        require_once APP_PATH . '/views/admin/layout.php';
        require_once APP_PATH . '/views/admin/orders/detail.php';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }
}
