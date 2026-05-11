<?php
// File nhận và hiển thị thông tin form cho Bài 2 (GET method)

$username = $_GET['nmUsername'] ?? '';
$password = $_GET['nmPassword'] ?? '';
$fullname = $_GET['nmFullName'] ?? '';
$birthdate = $_GET['nmBirthDate'] ?? '';
$gender = $_GET['nmGender'] ?? '';
$email = $_GET['nmEmailAdd'] ?? '';
// Lưu ý: với phương thức GET thì trường input file sẽ chỉ truyền tên tệp tin
$avatar = $_GET['nmURLAvatar'] ?? '(Vì form dùng method="GET" nên không thể upload ảnh lên server)';
$address = $_GET['nmAddress'] ?? '';
$cellphone = $_GET['nmCellphone'] ?? '';
$question = $_GET['nmPriateQuestion'] ?? '';
$answer = $_GET['nmPrivateAnswer'] ?? '';
$fields = $_GET['nmFields'] ?? [];
$fields_str = is_array($fields) ? implode(", ", $fields) : '';

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kết quả Đăng ký thành viên</title>
    <style>
        .result-box {
            border: 1px solid #ccc;
            padding: 20px;
            width: 600px;
            margin: 40px auto;
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; color: #0056b3; }
        ul { list-style-type: none; padding: 0; }
        li { margin-bottom: 10px; border-bottom: 1px dashed #ddd; padding-bottom: 5px; }
        strong { display: inline-block; width: 200px; color: #333; }
        a.btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #0056b3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="result-box">
        <h2>Kết quả Đăng ký (Từ Bài 2)</h2>
        <ul>
            <li><strong>Tên đăng nhập:</strong> <?php echo htmlspecialchars($username); ?></li>
            <li><strong>Mật khẩu:</strong> <?php echo htmlspecialchars($password); ?></li>
            <li><strong>Họ tên:</strong> <?php echo htmlspecialchars($fullname); ?></li>
            <li><strong>Ngày sinh:</strong> <?php echo htmlspecialchars($birthdate); ?></li>
            <li><strong>Giới tính:</strong> <?php echo htmlspecialchars($gender); ?></li>
            <li><strong>Địa chỉ email:</strong> <?php echo htmlspecialchars($email); ?></li>
            <li><strong>Hình đại diện (URL):</strong> <span style="font-style: italic; color: #d9534f;"><?php echo htmlspecialchars($avatar); ?></span></li>
            <li><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($address); ?></li>
            <li><strong>Điện thoại:</strong> <?php echo htmlspecialchars($cellphone); ?></li>
            <li><strong>Câu hỏi bí mật:</strong> <?php echo htmlspecialchars($question); ?></li>
            <li><strong>Câu trả lời:</strong> <?php echo htmlspecialchars($answer); ?></li>
            <li><strong>Nhóm sản phẩm quan tâm:</strong> <span style="color: green; font-weight: bold;"><?php echo htmlspecialchars($fields_str); ?></span></li>
        </ul>
        <div style="text-align: center;">
            <a class="btn" href="lab03_bai2.html">Quay lại Lab 03 - Bài 2</a>
        </div>
    </div>
</body>
</html>
