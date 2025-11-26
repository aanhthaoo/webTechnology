<?php require 'data.php'; // Gọi dữ liệu vào để dùng 
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Danh sách các loài hoa đẹp</title>
    <style>
        body {
            font-family: sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }

        .flower-item {
            margin-bottom: 40px;
        }

        .flower-name {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .flower-desc {
            text-align: justify;
            margin-bottom: 15px;
        }

        .flower-img {
            width: 100%;
            max-width: 600px;
            display: block;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .menu {
            margin-bottom: 30px;
            padding: 10px;
            background: #eee;
            text-align: right;
        }

        a {
            text-decoration: none;
            color: blue;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="menu">
        <a href="admin.php">Chuyển sang giao diện Quản trị (Admin) >></a>
    </div>

    <h1>14 loại hoa tuyệt đẹp thích hợp trồng để khoe hương sắc dịp xuân hè</h1>

    <?php
    
    $index = 1; 
    foreach ($flowers as $flower):
    ?>
        <div class="flower-item">
            <div class="flower-name">
                <?= $index ?>. <?= $flower['name'] ?>
            </div>

            <p class="flower-desc">
                <?= $flower['description'] ?>
            </p>

            <img src="<?= $flower['image'] ?>" alt="<?= $flower['name'] ?>" class="flower-img">
        </div>
    <?php
        $index++; 
    endforeach;
    ?>

</body>

</html>