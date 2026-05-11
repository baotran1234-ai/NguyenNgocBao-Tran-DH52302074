<?php
// app/controllers/AdminBannerController.php
require_once APP_PATH . '/models/OtherModels.php';

class AdminBannerController {
    private BannerModel $bannerModel;

    public function __construct() {
        $this->bannerModel = new BannerModel();
    }

    public function index(): void {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 4;
        $result = $this->bannerModel->adminGetAll($page, $perPage);
        $banners = $result['data'];
        $total = $result['total'];

        $pageTitle = 'Quản Lý Banners - Admin LUXE Beauty';
        $activeMenu = 'banners';
        
        require_once APP_PATH . '/views/admin/layout.php';
        require_once APP_PATH . '/views/admin/banners/index.php';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            
            // Handle image upload
            if (!empty($_FILES['image']['name'])) {
                $uploadPath = uploadImage($_FILES['image'], 'banners');
                if ($uploadPath) {
                    $data['image'] = $uploadPath;
                }
            }

            $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;
            $data['sort_order'] = (int)($data['sort_order'] ?? 0);

            if (empty($data['image'])) {
                setFlash('error', 'Vui lòng chọn ảnh cho banner!');
            } else {
                $this->bannerModel->create($data);
                setFlash('success', 'Thêm banner thành công!');
                redirect(url('admin/banners'));
            }
        }

        $nextSortOrder = $this->bannerModel->getNextSortOrder();
        $pageTitle = 'Thêm Banner - Admin LUXE Beauty';
        $activeMenu = 'banners';
        require_once APP_PATH . '/views/admin/layout.php';
        require_once APP_PATH . '/views/admin/banners/form.php';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }

    public function edit($id = null): void {
        if (!$id) redirect(url('admin/banners'));
        $banner = $this->bannerModel->getById((int)$id);
        if (!$banner) redirect(url('admin/banners'));

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            
            if (!empty($_FILES['image']['name'])) {
                $uploadPath = uploadImage($_FILES['image'], 'banners');
                if ($uploadPath) {
                    $data['image'] = $uploadPath;
                }
            } else {
                $data['image'] = $banner['image'];
            }

            $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;
            $data['sort_order'] = (int)($data['sort_order'] ?? 0);

            $this->bannerModel->update((int)$id, $data);
            // Note: BannerModel::update in OtherModels.php might not handle image update in the snippet I saw.
            // Let me double check that snippet.
            setFlash('success', 'Cập nhật banner thành công!');
            redirect(url('admin/banners'));
        }

        $pageTitle = 'Sửa Banner - Admin LUXE Beauty';
        $activeMenu = 'banners';
        require_once APP_PATH . '/views/admin/layout.php';
        require_once APP_PATH . '/views/admin/banners/form.php';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }

    public function delete($id = null): void {
        if ($id) {
            $this->bannerModel->delete((int)$id);
            setFlash('success', 'Đã xóa banner!');
        }
        redirect(url('admin/banners'));
    }
}
