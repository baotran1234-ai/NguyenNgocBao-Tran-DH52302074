<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once APP_PATH . '/models/OrderModel.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { echo json_encode(['success'=>false,'message'=>'Vui lòng Ä‘Đăng nhập']); exit; }
$reviewModel = new ReviewModel();
$data = ['product_id'=>(int)($_POST['product_id']??0),'user_id'=>$_SESSION['user_id'],'rating'=>(int)($_POST['rating']??5),'title'=>trim($_POST['title']??''),'content'=>trim($_POST['content']??'')];
if (empty($data['content'])) { echo json_encode(['success'=>false,'message'=>'Vui lòng nhập ná»™i dung']); exit; }
if (!$reviewModel->userCanReview($data['user_id'],$data['product_id'])) { echo json_encode(['success'=>false,'message'=>'Bạn Ä‘ã Ä‘ánh giá sản phẩm này']); exit; }
$reviewModel->create($data);
(new ProductModel())->updateRating($data['product_id']);
echo json_encode(['success'=>true,'message'=>'Cảm ơn Ä‘ánh giá của bạn!']);
