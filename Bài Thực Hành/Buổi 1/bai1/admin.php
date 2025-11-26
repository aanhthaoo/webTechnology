<?php require 'data.php'; ?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản trị danh sách hoa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .thumb-img {
            width: 100px;
            height: auto;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Quản Trị Danh Sách Hoa</h2>
            <a href="flowers_web.php" class="btn btn-secondary">Về trang Khách</a>
        </div>

        <button class="btn btn-success mb-3">+ Thêm hoa mới</button>

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tên hoa</th>
                    <th scope="col">Mô tả</th>
                    <th scope="col">Hình ảnh</th>
                    <th scope="col">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($flowers as $key => $flower): ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td style="font-weight: bold;"><?= $flower['name'] ?></td>
                        <td><?= $flower['description'] ?></td>
                        <td>
                            <img src="<?= $flower['image'] ?>" alt="<?= $flower['name'] ?>" class="thumb-img">
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm">Sửa</button>
                            <button class="btn btn-danger btn-sm">Xóa</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>