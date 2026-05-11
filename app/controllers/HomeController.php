<?php
// ================================================================
// app/controllers/HomeController.php
// ================================================================
require_once APP_PATH . '/models/ProductModel.php';
require_once APP_PATH . '/models/OtherModels.php';

class HomeController {
    private ProductModel $productModel;
    private CategoryModel $categoryModel;
    private BannerModel $bannerModel;

    public function __construct() {
        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->bannerModel   = new BannerModel();
    }

    public function index(): void {
        $banners        = $this->bannerModel->getActive('hero');
        $categories     = $this->categoryModel->getAll();
        $pageFeatured = max(1, (int)($_GET['page_featured'] ?? 1));
        $featuredResult = $this->productModel->getFeatured($pageFeatured, 4);
        $featuredProducts = $featuredResult['data'];
        $featuredPagination = paginate($featuredResult['total'], 4, $pageFeatured, url('') . '?page_featured=%d#hot-sale');
        
        $page = max(1, (int)($_GET['page'] ?? 1));
        $newProductsResult = $this->productModel->getNew($page, 4);
        $newProducts = $newProductsResult['data'];
        $pagination = paginate($newProductsResult['total'], 4, $page, url('') . '?page=%d#new-products');

        $promoBanners   = $this->bannerModel->getActive('promo');

        $pageTitle = 'LUXE Beauty - Mỹ Phẩm Cao Cấp';
        $pageDesc  = 'Khám phá bộ sưu tập mỹ phẩm cao cấp tại LUXE Beauty. Hàng chính hãng, giá tốt nhất.';

        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/home/index.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }
}
