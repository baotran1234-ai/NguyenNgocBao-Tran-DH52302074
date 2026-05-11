<?php
$labs = array_filter(glob('*'), 'is_dir');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Các Bài Thực Hành Lab</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }
        .container {
            max-width: 800px;
            width: 100%;
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        h1 {
            color: #1a1a1a;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-top: 0;
        }
        .lab-list {
            list-style: none;
            padding: 0;
            margin: 20px 0 0 0;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }
        .lab-item a {
            display: block;
            padding: 15px 20px;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            text-decoration: none;
            color: #0066cc;
            font-weight: 500;
            transition: all 0.2s ease;
            text-align: center;
        }
        .lab-item a:hover {
            background: #f0f7ff;
            border-color: #0066cc;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 102, 204, 0.1);
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .back-btn:hover {
            color: #333;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../" class="back-btn">&larr; Quay lại trang chủ WebsiteBanMyPham</a>
        <h1>Danh Sách Các Bài Lab</h1>
        
        <?php if (empty($labs)): ?>
            <p style="color: #666;">Chưa có bài thực hành lab nào.</p>
        <?php else: ?>
            <ul class="lab-list">
                <?php foreach ($labs as $lab): ?>
                    <li class="lab-item">
                        <a href="/bailabthuchanh/<?= htmlspecialchars($lab) ?>/"><?= htmlspecialchars($lab) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>
