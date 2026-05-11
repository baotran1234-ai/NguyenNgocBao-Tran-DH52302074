<?php
// ================================================================
// app/models/ProductModel.php
// ================================================================

class ProductModel {
    private PDO $db;

    public function __construct() {
        $this->db = db();
    }

    // Lấy tất cả sản phẩm vá»›i filter
    public function getAll(array $filters = [], int $page = 1, int $perPage = ITEMS_PER_PAGE): array {
        $where  = ['p.is_active = 1'];
        $params = [];

        if (!empty($filters['category'])) {
            $where[]            = 'c.slug = :cat';
            $params[':cat']     = $filters['category'];
        }
        if (!empty($filters['brand'])) {
            $where[]            = 'b.slug = :brand';
            $params[':brand']   = $filters['brand'];
        }
        if (!empty($filters['search'])) {
            $where[]            = '(p.name LIKE :search OR p.description LIKE :search2)';
            $params[':search']  = '%' . $filters['search'] . '%';
            $params[':search2'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['min_price'])) {
            $where[]              = 'COALESCE(p.sale_price,p.price) >= :min';
            $params[':min']       = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where[]              = 'COALESCE(p.sale_price,p.price) <= :max';
            $params[':max']       = $filters['max_price'];
        }
        if (isset($filters['is_featured'])) {
            $where[]              = 'p.is_featured = :featured';
            $params[':featured']  = $filters['is_featured'];
        }

        $sortOptions = [
            'newest'    => 'p.created_at DESC',
            'oldest'    => 'p.created_at ASC',
            'price_asc' => 'COALESCE(p.sale_price,p.price) ASC',
            'price_desc'=> 'COALESCE(p.sale_price,p.price) DESC',
            'popular'   => 'p.sold DESC',
            'rating'    => 'p.rating DESC',
        ];
        $sort = $sortOptions[$filters['sort'] ?? 'newest'] ?? 'p.created_at DESC';

        $whereStr = implode(' AND ', $where);
        $offset   = ($page - 1) * $perPage;

        // Count
        $countSql = "SELECT COUNT(*) FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN brands b ON p.brand_id = b.id
            WHERE $whereStr";
        $stmt = $this->db->prepare($countSql);
        $stmt->execute($params);
        $total = (int)$stmt->fetchColumn();

        // Data
        $sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug,
                       b.name AS brand_name,
                       COALESCE(p.sale_price, p.price) AS effective_price
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE $whereStr
                ORDER BY $sort
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
        $stmt->execute();

        return ['data' => $stmt->fetchAll(), 'total' => $total];
    }

    // Lấy sản phẩm theo slug
    public function getBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug,
                   b.name AS brand_name, b.slug AS brand_slug
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN brands b ON p.brand_id = b.id
            WHERE p.slug = :slug AND p.is_active = 1
        ");
        $stmt->execute([':slug' => $slug]);
        $product = $stmt->fetch();
        if (!$product) return null;

        // TÄƒng view
        $this->db->prepare("UPDATE products SET views = views+1 WHERE id=:id")
                 ->execute([':id' => $product['id']]);

        // Lấy ảnh
        $product['images'] = $this->getImages($product['id']);

        return $product;
    }

    // Lấy ảnh sản phẩm
    public function getImages(int $productId): array {
        $stmt = $this->db->prepare("
            SELECT * FROM product_images WHERE product_id = :id ORDER BY is_main DESC, sort_order ASC
        ");
        $stmt->execute([':id' => $productId]);
        return $stmt->fetchAll();
    }

    // sản phẩm liên quan
    public function getRelated(int $productId, int $categoryId, int $limit = 8): array {
        $stmt = $this->db->prepare("
            SELECT p.*, COALESCE(p.sale_price, p.price) AS effective_price
            FROM products p
            WHERE p.category_id = :cat AND p.id != :id AND p.is_active = 1
            ORDER BY p.is_featured DESC, p.sold DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':cat',   $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':id',    $productId,  PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit,      PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // sản phẩm nổi bật
    public function getFeatured(int $page = 1, int $perPage = 4): array {
        $offset = ($page - 1) * $perPage;
        
        $total = (int)$this->db->query("SELECT COUNT(*) FROM products WHERE is_featured = 1 AND is_active = 1")->fetchColumn();
        
        $stmt = $this->db->prepare("
            SELECT p.*, b.name AS brand_name, COALESCE(p.sale_price, p.price) AS effective_price
            FROM products p
            LEFT JOIN brands b ON p.brand_id = b.id
            WHERE p.is_featured = 1 AND p.is_active = 1
            ORDER BY p.sold DESC LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return ['data' => $stmt->fetchAll(), 'total' => $total];
    }

    public function getNew(int $page = 1, int $perPage = 4): array {
        $offset = ($page - 1) * $perPage;
        
        $total = (int)$this->db->query("SELECT COUNT(*) FROM products WHERE is_new = 1 AND is_active = 1")->fetchColumn();
        
        $stmt = $this->db->prepare("
            SELECT p.*, b.name AS brand_name, COALESCE(p.sale_price, p.price) AS effective_price
            FROM products p
            LEFT JOIN brands b ON p.brand_id = b.id
            WHERE p.is_new = 1 AND p.is_active = 1
            ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return ['data' => $stmt->fetchAll(), 'total' => $total];
    }

    // Lấy theo ID
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name, b.name AS brand_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN brands b ON p.brand_id = b.id
            WHERE p.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    // Tạo sản phẩm (Admin)
    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO products (category_id, brand_id, name, slug, sku, description,
                ingredients, how_to_use, price, sale_price, stock, thumbnail,
                is_featured, is_new, meta_title, meta_desc)
            VALUES (:category_id,:brand_id,:name,:slug,:sku,:description,
                :ingredients,:how_to_use,:price,:sale_price,:stock,:thumbnail,
                :is_featured,:is_new,:meta_title,:meta_desc)
        ");
        $stmt->execute([
            ':category_id' => $data['category_id'],
            ':brand_id'    => $data['brand_id'] ?: null,
            ':name'        => $data['name'],
            ':slug'        => $data['slug'] ?: makeSlug($data['name']),
            ':sku'         => $data['sku'] ?: null,
            ':description' => $data['description'] ?? null,
            ':ingredients' => $data['ingredients'] ?? null,
            ':how_to_use'  => $data['how_to_use'] ?? null,
            ':price'       => $data['price'],
            ':sale_price'  => !empty($data['sale_price']) ? $data['sale_price'] : null,
            ':stock'       => $data['stock'] ?? 0,
            ':thumbnail'   => $data['thumbnail'] ?? null,
            ':is_featured' => $data['is_featured'] ?? 0,
            ':is_new'      => $data['is_new'] ?? 1,
            ':meta_title'  => $data['meta_title'] ?? null,
            ':meta_desc'   => $data['meta_desc'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    // Cập nhật
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE products SET
                category_id=:category_id, brand_id=:brand_id, name=:name,
                slug=:slug, sku=:sku, description=:description,
                ingredients=:ingredients, how_to_use=:how_to_use,
                price=:price, sale_price=:sale_price, stock=:stock,
                thumbnail=:thumbnail,
                is_featured=:is_featured, is_new=:is_new,
                meta_title=:meta_title, meta_desc=:meta_desc,
                updated_at=NOW()
            WHERE id=:id
        ");
        return $stmt->execute([
            ':id'          => $id,
            ':category_id' => $data['category_id'],
            ':brand_id'    => $data['brand_id'] ?: null,
            ':name'        => $data['name'],
            ':slug'        => $data['slug'],
            ':sku'         => $data['sku'] ?: null,
            ':description' => $data['description'] ?? null,
            ':ingredients' => $data['ingredients'] ?? null,
            ':how_to_use'  => $data['how_to_use'] ?? null,
            ':price'       => $data['price'],
            ':sale_price'  => !empty($data['sale_price']) ? $data['sale_price'] : null,
            ':stock'       => $data['stock'] ?? 0,
            ':thumbnail'   => $data['thumbnail'] ?? null,
            ':is_featured' => $data['is_featured'] ?? 0,
            ':is_new'      => $data['is_new'] ?? 0,
            ':meta_title'  => $data['meta_title'] ?? null,
            ':meta_desc'   => $data['meta_desc'] ?? null,
        ]);
    }

    // Xóa
    public function delete(int $id): bool {
        return $this->db->prepare("DELETE FROM products WHERE id=:id")
                        ->execute([':id' => $id]);
    }

    // Thêm ảnh
    public function addImage(int $productId, string $imagePath, bool $isMain = false): void {
        $stmt = $this->db->prepare("
            INSERT INTO product_images (product_id, image, is_main) VALUES (:pid, :img, :main)
        ");
        $stmt->execute([':pid' => $productId, ':img' => $imagePath, ':main' => $isMain ? 1 : 0]);
        if ($isMain) {
            $this->db->prepare("UPDATE products SET thumbnail=:thumb WHERE id=:id")
                     ->execute([':thumb' => $imagePath, ':id' => $productId]);
        }
    }

    // Xóa ảnh
    public function deleteImage(int $imageId): ?string {
        $stmt = $this->db->prepare("SELECT image FROM product_images WHERE id=:id");
        $stmt->execute([':id' => $imageId]);
        $row = $stmt->fetch();
        if ($row) {
            $this->db->prepare("DELETE FROM product_images WHERE id=:id")->execute([':id' => $imageId]);
            return $row['image'];
        }
        return null;
    }

    // Cập nhật rating sau khi có review má»›i
    public function updateRating(int $productId): void {
        $stmt = $this->db->prepare("
            SELECT AVG(rating) AS avg_rating, COUNT(*) AS cnt
            FROM reviews WHERE product_id=:id AND is_active=1
        ");
        $stmt->execute([':id' => $productId]);
        $row = $stmt->fetch();
        $this->db->prepare("UPDATE products SET rating=:r, review_count=:c WHERE id=:id")
                 ->execute([':r' => round($row['avg_rating'], 2), ':c' => $row['cnt'], ':id' => $productId]);
    }

    public function adminGetAll(array $filters = [], int $page = 1, int $perPage = 20): array {
        $where = ['1=1'];
        $params = [];
        if (!empty($filters['search'])) {
            $where[] = '(p.name LIKE :s OR p.sku LIKE :s2)';
            $params[':s'] = '%' . $filters['search'] . '%';
            $params[':s2'] = '%' . $filters['search'] . '%';
        }
        $whereStr = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM products p WHERE $whereStr");
        $stmt->execute($params);
        $total = (int)$stmt->fetchColumn();

        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name, b.name AS brand_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN brands b ON p.brand_id = b.id
            WHERE $whereStr
            ORDER BY p.id ASC
            LIMIT :limit OFFSET :offset
        ");
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return ['data' => $stmt->fetchAll(), 'total' => $total];
    }

    public function getUserWishlist(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT p.*, b.name AS brand_name, COALESCE(p.sale_price, p.price) AS effective_price
            FROM wishlist w
            JOIN products p ON w.product_id = p.id
            LEFT JOIN brands b ON p.brand_id = b.id
            WHERE w.user_id = :uid
            ORDER BY w.created_at DESC
        ");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }
}
