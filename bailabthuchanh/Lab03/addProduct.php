<?php
// File nhận và hiển thị kết quả thêm sản phẩm cho Bài 3 (POST method)

$proID = $_POST['nmProID'] ?? '';
$proName = $_POST['nmProName'] ?? '';

// Lấy danh sách tên file upload
$imgName = '';
if (isset($_FILES['nmProImg']) && $_FILES['nmProImg']['error'] == UPLOAD_ERR_OK) {
    $imgName = $_FILES['nmProImg']['name'];
} elseif (isset($_FILES['nmProImg'])) {
    $imgName = '(Chưa chọn file hoặc lỗi upload)';
}

$man = $_POST['nmMan'] ?? '';
$w = $_POST['nmW'] ?? '';
$h = $_POST['nmH'] ?? '';
$count = $_POST['nmCount'] ?? '';
$tacGia = $_POST['nmTacGia'] ?? '';
$price = $_POST['nmPrice'] ?? '';
$color = $_POST['nmColor'] ?? '';
$live = $_POST['nmLive'] ?? '';

$cats = $_POST['nmCat'] ?? [];
$catStr = (is_array($cats) && count($cats) > 0) ? implode(";", $cats) . ";" : '';

$description = $_POST['nmDescription'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kết quả Thêm Sản Phẩm (Bài 3)</title>
    <style>
        .result-box {
            border: 4px solid #000;
            padding: 20px 40px;
            width: 450px;
            margin: 40px auto;
            font-family: Arial, sans-serif;
            font-size: 13px;
        }
        .result-box h3 {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 20px;
            font-size: 15px;
            font-weight: bold;
        }
        .row {
            display: flex;
            margin-bottom: 5px;
        }
        .label {
            width: 130px;
        }
        .value {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="result-box">
        <h3>THÔNG TIN SẢN PHẨM VỪA THÊM LÀ:</h3>
        
        <div class="row">
            <div class="label">Mã sản phẩm</div>
            <div class="value"><?php echo htmlspecialchars($proID); ?></div>
        </div>
        <div class="row">
            <div class="label">Tên sản phẩm</div>
            <div class="value"><?php echo htmlspecialchars($proName); ?></div>
        </div>
        <div class="row">
            <div class="label">Hình ảnh</div>
            <div class="value"><?php echo htmlspecialchars($imgName); ?></div>
        </div>
        <div class="row">
            <div class="label">Hãng sản xuất</div>
            <div class="value"><?php echo htmlspecialchars($man); ?></div>
        </div>
        <div class="row">
            <div class="label">Kích thước</div>
            <div class="value"><?php echo htmlspecialchars($w); ?> x <?php echo htmlspecialchars($h); ?></div>
        </div>
        <div class="row">
            <div class="label">Số trang</div>
            <div class="value"><?php echo htmlspecialchars($count); ?>trang</div>
        </div>
        <div class="row">
            <div class="label">Tác giả</div>
            <div class="value"><?php echo htmlspecialchars($tacGia); ?></div>
        </div>
        <div class="row">
            <div class="label">Giá</div>
            <div class="value"><?php echo htmlspecialchars($price); ?> &nbsp;&nbsp;&nbsp;&nbsp; Màu: <?php echo htmlspecialchars($color); ?></div>
        </div>
        <div class="row">
            <div class="label">Xuất xứ</div>
            <div class="value"><?php echo htmlspecialchars($live); ?></div>
        </div>
        <div class="row">
            <div class="label">Thể loại</div>
            <div class="value"><?php echo htmlspecialchars($catStr); ?></div>
        </div>
        <div class="row">
            <div class="label">Mô tả chi tiết</div>
            <div class="value"><?php echo nl2br(htmlspecialchars($description)); ?></div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="lab03_bai3.html" style="color: blue; text-decoration: underline;">Quay lại Lab 03 - Bài 3</a>
        </div>
    </div>
</body>
</html>
