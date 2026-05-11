<?php
// api/search.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';
header('Content-Type: application/json');

$q = trim($_GET['q'] ?? '');
if (strlen($q) < 2) {
    echo json_encode(['results' => []]);
    exit;
}

try {
    $stmt = db()->prepare("
        SELECT id, name, slug, price, sale_price, thumbnail 
        FROM products 
        WHERE name LIKE :q OR description LIKE :q 
        LIMIT 5
    ");
    $stmt->execute([':q' => "%$q%"]);
    $products = $stmt->fetchAll();

    $results = array_map(function($p) {
        $price = formatPrice($p['sale_price'] ?? $p['price']);
        return [
            'id'    => $p['id'],
            'name'  => e($p['name']),
            'url'   => url('products/' . $p['slug']),
            'price' => $price,
            'thumbnail' => $p['thumbnail'] ? uploadUrl($p['thumbnail']) : 'https://via.placeholder.com/48'
        ];
    }, $products);

    echo json_encode(['results' => $results]);
} catch (Exception $e) {
    echo json_encode(['results' => []]);
}
