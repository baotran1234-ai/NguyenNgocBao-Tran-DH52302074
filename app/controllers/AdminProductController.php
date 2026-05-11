<?php
// app/controllers/AdminProductController.php
require_once APP_PATH . '/models/ProductModel.php';
require_once APP_PATH . '/models/OtherModels.php';

class AdminProductController {
    private ProductModel $productModel;
    private CategoryModel $categoryModel;
    private BrandModel $brandModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->brandModel = new BrandModel();
    }

    public function index(): void {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $search = $_GET['search'] ?? '';

        $filters = [];
        if ($search) $filters['search'] = $search;

        $perPage = 4;
        $result = $this->productModel->adminGetAll($filters, $page, $perPage);
        $products = $result['data'];
        $total = $result['total'];

        $pageTitle = 'Quản Lý sản phẩm - Admin LUXE Beauty';
        $activeMenu = 'products';
        
        require_once APP_PATH . '/views/admin/layout.php';
        require_once APP_PATH . '/views/admin/products/index.php';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            
            // Handle thumbnail upload
            if (!empty($_FILES['thumbnail']['name'])) {
                $uploadPath = uploadImage($_FILES['thumbnail'], 'products');
                if ($uploadPath) {
                    $data['thumbnail'] = $uploadPath;
                } else {
                    setFlash('error', 'Lỗi tải ảnh lên! Định dạng không hỗ trợ hoặc dung lượng quá lớn.');
                    redirect(currentUrl());
                }
            }

            // Defaults
            $data['is_featured'] = isset($_POST['is_featured']) ? 1 : 0;
            $data['is_new'] = isset($_POST['is_new']) ? 1 : 0;
            $data['stock'] = (int)($data['stock'] ?? 0);
            $data['price'] = (int)($data['price'] ?? 0);
            $data['sale_price'] = !empty($data['sale_price']) ? (int)$data['sale_price'] : null;
            $data['brand_id'] = !empty($data['brand_id']) ? (int)$data['brand_id'] : null;

            $this->productModel->create($data);
            setFlash('success', 'Thêm sản phẩm thành công!');
            redirect(url('admin/products'));
        }

        $categories = $this->categoryModel->getAll(false);
        $brands = $this->brandModel->getAll(false);
        $product = null;

        $pageTitle = 'Thêm sản phẩm - Admin LUXE Beauty';
        $activeMenu = 'products';
        require_once APP_PATH . '/views/admin/layout.php';
        require_once APP_PATH . '/views/admin/products/form.php';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }

    public function edit($id = null): void {
        if (!$id) redirect(url('admin/products'));
        $product = $this->productModel->getById((int)$id);
        if (!$product) redirect(url('admin/products'));

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            
            // Handle thumbnail upload
            if (!empty($_FILES['thumbnail']['name'])) {
                $f = $_FILES['thumbnail'];
                // Detect real mime from content
                $realMime = function_exists('finfo_open')
                    ? finfo_file(finfo_open(FILEINFO_MIME_TYPE), $f['tmp_name'])
                    : $f['type'];

                $uploadPath = uploadImage($f, 'products');
                if ($uploadPath) {
                    $data['thumbnail'] = $uploadPath;
                } else {
                    $phpErrors = [0=>'OK',1=>'Quá lớn (php.ini)',2=>'Quá lớn (form)',3=>'Upload không hoàn chỉnh',4=>'Không có file',6=>'Không có tmp folder',7=>'Không ghi được disk'];
                    $errMsg = 'Lỗi upload ảnh! ';
                    if ($f['error'] !== UPLOAD_ERR_OK) {
                        $errMsg .= 'PHP Error: ' . ($phpErrors[$f['error']] ?? 'Code '.$f['error']) . '. ';
                    } elseif ($f['size'] > MAX_FILE_SIZE) {
                        $errMsg .= 'File quá nặng: ' . round($f['size']/1024/1024, 2) . 'MB (tối đa 5MB). ';
                    } else {
                        $errMsg .= 'File không phải ảnh hợp lệ hoặc định dạng không được hỗ trợ. Vui lòng dùng JPG, PNG, GIF, WEBP.';
                    }
                    setFlash('error', $errMsg);
                    redirect(currentUrl());
                }
            } else {
                $data['thumbnail'] = $product['thumbnail']; // Keep old
            }

            // Defaults
            $data['is_featured'] = isset($_POST['is_featured']) ? 1 : 0;
            $data['is_new'] = isset($_POST['is_new']) ? 1 : 0;
            $data['stock'] = (int)($data['stock'] ?? 0);
            $data['price'] = (int)($data['price'] ?? 0);
            $data['sale_price'] = !empty($data['sale_price']) ? (int)$data['sale_price'] : null;
            $data['brand_id'] = !empty($data['brand_id']) ? (int)$data['brand_id'] : null;

            $this->productModel->update((int)$id, $data);
            setFlash('success', 'Cập nhật sản phẩm thành công!');
            redirect(url('admin/products'));
        }

        $categories = $this->categoryModel->getAll(false);
        $brands = $this->brandModel->getAll(false);

        $pageTitle = 'Sửa sản phẩm - Admin LUXE Beauty';
        $activeMenu = 'products';
        require_once APP_PATH . '/views/admin/layout.php';
        require_once APP_PATH . '/views/admin/products/form.php';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }

    public function delete($id = null): void {
        if ($id) {
            $this->productModel->delete((int)$id);
            setFlash('success', 'Đã xóa sản phẩm!');
        }
        redirect(url('admin/products'));
    }
}
