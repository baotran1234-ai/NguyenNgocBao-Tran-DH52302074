<?php
// app/controllers/AdminReviewController.php
require_once APP_PATH . '/models/OrderModel.php';

class AdminReviewController {
    private ReviewModel $reviewModel;

    public function __construct() {
        $this->reviewModel = new ReviewModel();
    }

    public function index(): void {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 4;
        $result = $this->reviewModel->getAll($page, $perPage);
        $reviews = $result['data'];
        $total = $result['total'];

        $pageTitle = 'Quản Lý Đánh Giá - Admin LUXE Beauty';
        $activeMenu = 'reviews';
        
        require_once APP_PATH . '/views/admin/layout.php';
        require_once APP_PATH . '/views/admin/reviews/index.php';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }

    public function toggle($id = null): void {
        if ($id) {
            $this->reviewModel->toggleStatus((int)$id);
            setFlash('success', 'Đã cập nhật trạng thái đánh giá!');
        }
        redirect(url('admin/reviews'));
    }

    public function delete($id = null): void {
        if ($id) {
            $this->reviewModel->delete((int)$id);
            setFlash('success', 'Đã xóa đánh giá!');
        }
        redirect(url('admin/reviews'));
    }
}
