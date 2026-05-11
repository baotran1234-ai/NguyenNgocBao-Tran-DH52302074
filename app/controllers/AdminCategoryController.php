<?php
// app/controllers/AdminCategoryController.php
require_once APP_PATH . '/models/OtherModels.php';

class AdminCategoryController {
    private CategoryModel $categoryModel;

    public function __construct() {
        $this->categoryModel = new CategoryModel();
    }

    public function index(): void {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 4;
        $result = $this->categoryModel->adminGetAll($page, $perPage);
        $categories = $result['data'];
        $total = $result['total'];

        $pageTitle = 'Quản Lý Danh Mục - Admin LUXE Beauty';
        $activeMenu = 'categories';
        
        require_once APP_PATH . '/views/admin/layout.php';
        require_once APP_PATH . '/views/admin/categories/index.php';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'slug' => !empty($_POST['slug']) ? trim($_POST['slug']) : makeSlug($_POST['name']),
                'description' => $_POST['description'] ?? null,
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];

            if (empty($data['name'])) {
                setFlash('error', 'Tên danh mục Không Ä‘ược Ä‘á»ƒ trá»‘ng!');
            } else {
                $this->categoryModel->create($data);
                setFlash('success', 'Thêm danh mục thành công!');
                redirect(url('admin/categories'));
            }
        }

        $pageTitle = 'Thêm Danh Mục - Admin LUXE Beauty';
        $activeMenu = 'categories';
        require_once APP_PATH . '/views/admin/layout.php';
        require_once APP_PATH . '/views/admin/categories/form.php';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }

    public function edit($id = null): void {
        if (!$id) redirect(url('admin/categories'));
        $category = $this->categoryModel->getById((int)$id);
        if (!$category) redirect(url('admin/categories'));

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'slug' => !empty($_POST['slug']) ? trim($_POST['slug']) : makeSlug($_POST['name']),
                'description' => $_POST['description'] ?? null,
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];

            if (empty($data['name'])) {
                setFlash('error', 'Tên danh mục Không Ä‘ược Ä‘á»ƒ trá»‘ng!');
            } else {
                $this->categoryModel->update((int)$id, $data);
                setFlash('success', 'Cập nhật danh mục thành công!');
                redirect(url('admin/categories'));
            }
        }

        $pageTitle = 'Sửa Danh Mục - Admin LUXE Beauty';
        $activeMenu = 'categories';
        require_once APP_PATH . '/views/admin/layout.php';
        require_once APP_PATH . '/views/admin/categories/form.php';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }

    public function delete($id = null): void {
        if ($id) {
            $this->categoryModel->delete((int)$id);
            setFlash('success', 'Đã xóa danh mục!');
        }
        redirect(url('admin/categories'));
    }
}
