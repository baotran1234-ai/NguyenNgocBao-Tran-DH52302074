<?php
// app/controllers/AdminUserController.php
require_once APP_PATH . '/models/UserModel.php';

class AdminUserController {
    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function index(): void {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 4;
        $result = $this->userModel->getAll($page, $perPage);
        $users = $result['data'];
        $total = $result['total'];

        $pageTitle = 'Quản Lý Khách Hàng - Admin LUXE Beauty';
        $activeMenu = 'users';
        
        require_once APP_PATH . '/views/admin/layout.php';
        require_once APP_PATH . '/views/admin/users/index.php';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }

    public function toggle($id = null): void {
        if ($id) {
            $this->userModel->toggleStatus((int)$id);
            setFlash('success', 'Đã cập nhật trạng thái tài khoản!');
        }
        redirect(url('admin/users'));
    }

    public function delete($id = null): void {
        if ($id) {
            $this->userModel->delete((int)$id);
            setFlash('success', 'Đã xóa người dùng!');
        }
        redirect(url('admin/users'));
    }
}
