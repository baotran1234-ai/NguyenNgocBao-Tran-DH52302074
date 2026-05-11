<?php
// ================================================================
// app/models/CategoryModel.php
// ================================================================
class CategoryModel {
    private PDO $db;
    public function __construct() { $this->db = db(); }

    public function getAll(bool $onlyActive = true): array {
        $sql  = "SELECT * FROM categories" . ($onlyActive ? " WHERE is_active=1" : "") . " ORDER BY id ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE slug=:slug AND is_active=1");
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id=:id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("INSERT INTO categories (name,slug,description,image,sort_order,is_active) VALUES (:name,:slug,:desc,:img,:sort,:active)");
        $stmt->execute([':name'=>$data['name'],':slug'=>$data['slug']??makeSlug($data['name']),':desc'=>$data['description']??null,':img'=>$data['image']??null,':sort'=>$data['sort_order']??0,':active'=>$data['is_active']??1]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("UPDATE categories SET name=:name,slug=:slug,description=:desc,sort_order=:sort,is_active=:active WHERE id=:id");
        return $stmt->execute([':name'=>$data['name'],':slug'=>$data['slug'],':desc'=>$data['description']??null,':sort'=>$data['sort_order']??0,':active'=>$data['is_active']??1,':id'=>$id]);
    }

    public function delete(int $id): bool {
        return $this->db->prepare("DELETE FROM categories WHERE id=:id")->execute([':id'=>$id]);
    }

    public function adminGetAll(int $page = 1, int $perPage = 4): array {
        $offset = ($page - 1) * $perPage;
        $total = (int)$this->db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return ['data' => $stmt->fetchAll(), 'total' => $total];
    }
}

// ================================================================
// app/models/BrandModel.php
// ================================================================
class BrandModel {
    private PDO $db;
    public function __construct() { $this->db = db(); }

    public function getAll(bool $onlyActive = true): array {
        $sql = "SELECT * FROM brands" . ($onlyActive ? " WHERE is_active=1" : "") . " ORDER BY name";
        return $this->db->query($sql)->fetchAll();
    }

    public function getBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("SELECT * FROM brands WHERE slug=:slug");
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM brands WHERE id=:id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("INSERT INTO brands (name,slug,description,logo,website,is_active) VALUES (:name,:slug,:desc,:logo,:web,:active)");
        $stmt->execute([':name'=>$data['name'],':slug'=>$data['slug']??makeSlug($data['name']),':desc'=>$data['description']??null,':logo'=>$data['logo']??null,':web'=>$data['website']??null,':active'=>$data['is_active']??1]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("UPDATE brands SET name=:name,slug=:slug,description=:desc,website=:web,is_active=:active WHERE id=:id");
        return $stmt->execute([':name'=>$data['name'],':slug'=>$data['slug'],':desc'=>$data['description']??null,':web'=>$data['website']??null,':active'=>$data['is_active']??1,':id'=>$id]);
    }

    public function delete(int $id): bool {
        return $this->db->prepare("DELETE FROM brands WHERE id=:id")->execute([':id'=>$id]);
    }
}

// ================================================================
// app/models/BannerModel.php
// ================================================================
class BannerModel {
    private PDO $db;
    public function __construct() { $this->db = db(); }

    public function getActive(string $position = 'hero'): array {
        $stmt = $this->db->prepare("
            SELECT * FROM banners
            WHERE is_active=1 AND position=:pos
              AND (starts_at IS NULL OR starts_at <= NOW())
              AND (ends_at IS NULL OR ends_at >= NOW())
            ORDER BY sort_order ASC
        ");
        $stmt->execute([':pos' => $position]);
        return $stmt->fetchAll();
    }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM banners ORDER BY id ASC")->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM banners WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("INSERT INTO banners (title,subtitle,image,link,position,sort_order,is_active) VALUES (:title,:sub,:img,:link,:pos,:sort,:active)");
        $stmt->execute([':title'=>$data['title']??null,':sub'=>$data['subtitle']??null,':img'=>$data['image'],':link'=>$data['link']??null,':pos'=>$data['position']??'hero',':sort'=>$data['sort_order']??0,':active'=>$data['is_active']??1]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("UPDATE banners SET title=:title,subtitle=:sub,image=:img,link=:link,position=:pos,sort_order=:sort,is_active=:active WHERE id=:id");
        return $stmt->execute([':title'=>$data['title']??null,':sub'=>$data['subtitle']??null,':img'=>$data['image']??null,':link'=>$data['link']??null,':pos'=>$data['position']??'hero',':sort'=>$data['sort_order']??0,':active'=>$data['is_active']??1,':id'=>$id]);
    }

    public function delete(int $id): bool {
        return $this->db->prepare("DELETE FROM banners WHERE id=:id")->execute([':id'=>$id]);
    }

    public function getNextSortOrder(): int {
        return (int)$this->db->query("SELECT MAX(sort_order) FROM banners")->fetchColumn() + 1;
    }

    public function adminGetAll(int $page = 1, int $perPage = 4): array {
        $offset = ($page - 1) * $perPage;
        $total = (int)$this->db->query("SELECT COUNT(*) FROM banners")->fetchColumn();
        $stmt = $this->db->prepare("SELECT * FROM banners ORDER BY sort_order ASC, id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return ['data' => $stmt->fetchAll(), 'total' => $total];
    }
}
