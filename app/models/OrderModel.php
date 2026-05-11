<?php
// ================================================================
// app/models/OrderModel.php
// ================================================================
class OrderModel {
    private PDO $db;
    public function __construct() { $this->db = db(); }

    public function create(array $order, array $items): int {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("
                INSERT INTO orders (order_code,user_id,coupon_id,name,email,phone,address,city,district,ward,note,subtotal,discount,shipping_fee,total,payment_method,status)
                VALUES (:code,:uid,:cid,:name,:email,:phone,:addr,:city,:district,:ward,:note,:sub,:disc,:ship,:total,:pay,'pending')
            ");
            $stmt->execute([
                ':code'     => $order['order_code'],
                ':uid'      => $order['user_id'] ?? null,
                ':cid'      => $order['coupon_id'] ?? null,
                ':name'     => $order['name'],
                ':email'    => $order['email'],
                ':phone'    => $order['phone'],
                ':addr'     => $order['address'],
                ':city'     => $order['city'] ?? null,
                ':district' => $order['district'] ?? null,
                ':ward'     => $order['ward'] ?? null,
                ':note'     => $order['note'] ?? null,
                ':sub'      => $order['subtotal'],
                ':disc'     => $order['discount'] ?? 0,
                ':ship'     => $order['shipping_fee'] ?? 0,
                ':total'    => $order['total'],
                ':pay'      => $order['payment_method'] ?? 'cod',
            ]);
            $orderId = (int)$this->db->lastInsertId();

            foreach ($items as $item) {
                $this->db->prepare("
                    INSERT INTO order_items (order_id,product_id,name,thumbnail,price,quantity,subtotal)
                    VALUES (:oid,:pid,:name,:thumb,:price,:qty,:sub)
                ")->execute([
                    ':oid'   => $orderId,
                    ':pid'   => $item['product_id'],
                    ':name'  => $item['name'],
                    ':thumb' => $item['thumbnail'] ?? null,
                    ':price' => $item['price'],
                    ':qty'   => $item['quantity'],
                    ':sub'   => $item['price'] * $item['quantity'],
                ]);
                // Giảm tá»“n kho
                $this->db->prepare("UPDATE products SET stock=stock-:qty1, sold=sold+:qty2 WHERE id=:id")
                    ->execute([':qty1' => $item['quantity'], ':qty2' => $item['quantity'], ':id' => $item['product_id']]);
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getByUser(int $userId, int $page=1, int $perPage=10): array {
        $offset = ($page-1)*$perPage;
        $stmt2  = $this->db->prepare("SELECT COUNT(*) FROM orders WHERE user_id=:uid");
        $stmt2->execute([':uid'=>$userId]);
        $total = (int)$stmt2->fetchColumn();

        $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id=:uid ORDER BY created_at DESC LIMIT :l OFFSET :o");
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':l', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':o', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return ['data' => $stmt->fetchAll(), 'total' => $total];
    }

    public function getUserOrders(int $userId): array {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id=:uid ORDER BY created_at DESC");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function getByCode(string $code): ?array {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE order_code=:code");
        $stmt->execute([':code'=>$code]);
        $order = $stmt->fetch();
        if (!$order) return null;
        $order['items'] = $this->getItems($order['id']);
        return $order;
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT o.*, u.name AS user_name FROM orders o LEFT JOIN users u ON o.user_id=u.id WHERE o.id=:id");
        $stmt->execute([':id'=>$id]);
        $order = $stmt->fetch();
        if (!$order) return null;
        $order['items'] = $this->getItems($id);
        return $order;
    }

    public function getItems(int $orderId): array {
        $stmt = $this->db->prepare("SELECT * FROM order_items WHERE order_id=:id");
        $stmt->execute([':id'=>$orderId]);
        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $status, ?string $reason=null): bool {
        return $this->db->prepare("UPDATE orders SET status=:status, cancel_reason=:reason, updated_at=NOW() WHERE id=:id")
            ->execute([':status'=>$status,':reason'=>$reason,':id'=>$id]);
    }

    public function getAll(array $filters=[], int $page=1, int $perPage=20): array {
        $where = ['1=1'];
        $params = [];
        if (!empty($filters['status'])) { $where[] = 'o.status=:status'; $params[':status']=$filters['status']; }
        if (!empty($filters['search'])) { $where[] = '(o.order_code LIKE :s OR o.name LIKE :s2 OR o.phone LIKE :s3)'; $params[':s']="%{$filters['search']}%"; $params[':s2']="%{$filters['search']}%"; $params[':s3']="%{$filters['search']}%"; }
        $whereStr = implode(' AND ', $where);
        $offset = ($page-1)*$perPage;
        $stmt2 = $this->db->prepare("SELECT COUNT(*) FROM orders o WHERE $whereStr");
        $stmt2->execute($params);
        $total = (int)$stmt2->fetchColumn();
        $stmt = $this->db->prepare("SELECT o.*, u.name AS user_name FROM orders o LEFT JOIN users u ON o.user_id=u.id WHERE $whereStr ORDER BY o.created_at DESC LIMIT :l OFFSET :off");
        foreach ($params as $k=>$v) $stmt->bindValue($k,$v);
        $stmt->bindValue(':l',$perPage,PDO::PARAM_INT);
        $stmt->bindValue(':off',$offset,PDO::PARAM_INT);
        $stmt->execute();
        return ['data'=>$stmt->fetchAll(),'total'=>$total];
    }

    public function getStats(): array {
        $today = date('Y-m-d');
        return [
            'total_orders'   => (int)$this->db->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
            'today_orders'   => (int)$this->db->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at)='$today'")->fetchColumn(),
            'total_revenue'  => (float)$this->db->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status='delivered'")->fetchColumn(),
            'today_revenue'  => (float)$this->db->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status='delivered' AND DATE(created_at)='$today'")->fetchColumn(),
            'pending_orders' => (int)$this->db->query("SELECT COUNT(*) FROM orders WHERE status='pending'")->fetchColumn(),
        ];
    }

    public function getRevenueChart(int $days=30): array {
        // Dùng cách khác Ä‘á»ƒ tránh bind interval
        $stmt = $this->db->query("
            SELECT DATE(created_at) AS date, SUM(total) AS revenue, COUNT(*) AS orders
            FROM orders WHERE status='delivered' AND created_at >= DATE_SUB(NOW(), INTERVAL $days DAY)
            GROUP BY DATE(created_at) ORDER BY date ASC
        ");
        return $stmt->fetchAll();
    }
}

// ================================================================
// app/models/ReviewModel.php
// ================================================================
class ReviewModel {
    private PDO $db;
    public function __construct() { $this->db = db(); }

    public function getByProduct(int $productId, int $page=1, int $perPage=10): array {
        $offset = ($page-1)*$perPage;
        $stmt2  = $this->db->prepare("SELECT COUNT(*) FROM reviews WHERE product_id=:id AND is_active=1");
        $stmt2->execute([':id'=>$productId]);
        $total = (int)$stmt2->fetchColumn();
        $stmt = $this->db->prepare("
            SELECT r.*, u.name AS user_name, u.avatar AS user_avatar
            FROM reviews r JOIN users u ON r.user_id=u.id
            WHERE r.product_id=:id AND r.is_active=1
            ORDER BY r.created_at DESC LIMIT :l OFFSET :o
        ");
        $stmt->bindValue(':id',$productId,PDO::PARAM_INT);
        $stmt->bindValue(':l',$perPage,PDO::PARAM_INT);
        $stmt->bindValue(':o',$offset,PDO::PARAM_INT);
        $stmt->execute();
        return ['data'=>$stmt->fetchAll(),'total'=>$total];
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("INSERT INTO reviews (product_id,user_id,order_id,rating,title,content) VALUES (:pid,:uid,:oid,:rating,:title,:content)");
        $stmt->execute([':pid'=>$data['product_id'],':uid'=>$data['user_id'],':oid'=>$data['order_id']??null,':rating'=>$data['rating'],':title'=>$data['title']??null,':content'=>$data['content']]);
        return (int)$this->db->lastInsertId();
    }

    public function userCanReview(int $userId, int $productId): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM reviews WHERE user_id=:uid AND product_id=:pid");
        $stmt->execute([':uid'=>$userId,':pid'=>$productId]);
        return (int)$stmt->fetchColumn() === 0;
    }

    public function getAll(int $page=1, int $perPage=20): array {
        $offset = ($page-1)*$perPage;
        $total  = (int)$this->db->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
        $stmt = $this->db->prepare("SELECT r.*,u.name AS user_name,p.name AS product_name FROM reviews r JOIN users u ON r.user_id=u.id JOIN products p ON r.product_id=p.id ORDER BY r.created_at DESC LIMIT :l OFFSET :o");
        $stmt->bindValue(':l',$perPage,PDO::PARAM_INT);
        $stmt->bindValue(':o',$offset,PDO::PARAM_INT);
        $stmt->execute();
        return ['data'=>$stmt->fetchAll(),'total'=>$total];
    }

    public function toggleStatus(int $id): void {
        $this->db->prepare("UPDATE reviews SET is_active=1-is_active WHERE id=:id")->execute([':id'=>$id]);
    }

    public function delete(int $id): ?int {
        $stmt = $this->db->prepare("SELECT product_id FROM reviews WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        $row = $stmt->fetch();
        $this->db->prepare("DELETE FROM reviews WHERE id=:id")->execute([':id'=>$id]);
        return $row ? (int)$row['product_id'] : null;
    }
}

// ================================================================
// app/models/CouponModel.php
// ================================================================
class CouponModel {
    private PDO $db;
    public function __construct() { $this->db = db(); }

    public function getByCode(string $code): ?array {
        $stmt = $this->db->prepare("SELECT * FROM coupons WHERE code=:code AND is_active=1 AND (starts_at IS NULL OR starts_at<=NOW()) AND (expires_at IS NULL OR expires_at>=NOW())");
        $stmt->execute([':code'=>strtoupper(trim($code))]);
        return $stmt->fetch() ?: null;
    }

    public function validate(array $coupon, float $orderTotal, int $userId): array {
        if ($coupon['min_order'] > 0 && $orderTotal < $coupon['min_order']) {
            return ['valid'=>false,'message'=>'Đơn hàng chưa Ä‘ạt giá trá»‹ tá»‘i thiá»ƒu ' . formatPrice($coupon['min_order'])];
        }
        if ($coupon['max_use'] !== null && $coupon['used_count'] >= $coupon['max_use']) {
            return ['valid'=>false,'message'=>'Mã giảm giá Ä‘ã hết lượt sử dụng'];
        }
        if ($coupon['user_id'] !== null && $coupon['user_id'] != $userId) {
            return ['valid'=>false,'message'=>'Mã giảm giá Không dành cho bạn'];
        }
        $discount = $coupon['type']==='percent'
            ? $orderTotal * $coupon['value'] / 100
            : (float)$coupon['value'];
        if ($coupon['max_discount'] !== null) {
            $discount = min($discount, (float)$coupon['max_discount']);
        }
        return ['valid'=>true,'discount'=>$discount,'message'=>'Áp dụng mã thành công!'];
    }

    public function incrementUsed(int $id): void {
        $this->db->prepare("UPDATE coupons SET used_count=used_count+1 WHERE id=:id")->execute([':id'=>$id]);
    }

    public function getAll(int $page=1, int $perPage=20): array {
        $offset = ($page-1)*$perPage;
        $total  = (int)$this->db->query("SELECT COUNT(*) FROM coupons")->fetchColumn();
        $stmt   = $this->db->prepare("SELECT * FROM coupons ORDER BY created_at DESC LIMIT :l OFFSET :o");
        $stmt->bindValue(':l',$perPage,PDO::PARAM_INT);
        $stmt->bindValue(':o',$offset,PDO::PARAM_INT);
        $stmt->execute();
        return ['data'=>$stmt->fetchAll(),'total'=>$total];
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("INSERT INTO coupons (code,name,type,value,min_order,max_discount,max_use,starts_at,expires_at,is_active) VALUES (:code,:name,:type,:val,:min,:maxd,:maxu,:start,:exp,:active)");
        $stmt->execute([':code'=>strtoupper($data['code']),':name'=>$data['name']??null,':type'=>$data['type'],':val'=>$data['value'],':min'=>$data['min_order']??0,':maxd'=>$data['max_discount']??null,':maxu'=>$data['max_use']??null,':start'=>$data['starts_at']??null,':exp'=>$data['expires_at']??null,':active'=>$data['is_active']??1]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("UPDATE coupons SET code=:code,name=:name,type=:type,value=:val,min_order=:min,max_discount=:maxd,max_use=:maxu,starts_at=:start,expires_at=:exp,is_active=:active WHERE id=:id");
        return $stmt->execute([':code'=>strtoupper($data['code']),':name'=>$data['name']??null,':type'=>$data['type'],':val'=>$data['value'],':min'=>$data['min_order']??0,':maxd'=>$data['max_discount']??null,':maxu'=>$data['max_use']??null,':start'=>$data['starts_at']??null,':exp'=>$data['expires_at']??null,':active'=>$data['is_active']??1,':id'=>$id]);
    }

    public function delete(int $id): bool {
        return $this->db->prepare("DELETE FROM coupons WHERE id=:id")->execute([':id'=>$id]);
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM coupons WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch() ?: null;
    }
}
