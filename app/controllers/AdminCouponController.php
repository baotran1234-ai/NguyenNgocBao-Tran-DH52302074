<?php
// app/controllers/AdminCouponController.php
require_once APP_PATH . '/models/OrderModel.php';

class AdminCouponController {
    private CouponModel $couponModel;

    public function __construct() {
        $this->couponModel = new CouponModel();
    }

    public function index(): void {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 4;
        $result = $this->couponModel->getAll($page, $perPage);
        $coupons = $result['data'];
        $total = $result['total'];

        $pageTitle = 'Quản Lý Mã Giảm Giá - Admin LUXE Beauty';
        $activeMenu = 'coupons';
        
        require_once APP_PATH . '/views/admin/layout.php';
        require_once APP_PATH . '/views/admin/coupons/index.php';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;
            if (empty($data['starts_at'])) $data['starts_at'] = null;
            if (empty($data['expires_at'])) $data['expires_at'] = null;

            $this->couponModel->create($data);
            setFlash('success', 'Thêm mã giảm giá thành công!');
            redirect(url('admin/coupons'));
        }

        $pageTitle = 'Thêm Mã Giảm Giá';
        $activeMenu = 'coupons';
        require_once APP_PATH . '/views/admin/layout.php';
        require_once APP_PATH . '/views/admin/coupons/form.php';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }

    public function edit($id = null): void {
        if (!$id) redirect(url('admin/coupons'));
        $coupon = $this->couponModel->getById((int)$id);
        if (!$coupon) redirect(url('admin/coupons'));

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;
            if (empty($data['starts_at'])) $data['starts_at'] = null;
            if (empty($data['expires_at'])) $data['expires_at'] = null;

            $this->couponModel->update((int)$id, $data);
            setFlash('success', 'Cập nhật mã giảm giá thành công!');
            redirect(url('admin/coupons'));
        }

        $pageTitle = 'Sửa Mã Giảm Giá';
        $activeMenu = 'coupons';
        require_once APP_PATH . '/views/admin/layout.php';
        require_once APP_PATH . '/views/admin/coupons/form.php';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }

    public function delete($id = null): void {
        if ($id) {
            $this->couponModel->delete((int)$id);
            setFlash('success', 'Đã xóa mã giảm giá!');
        }
        redirect(url('admin/coupons'));
    }
}
