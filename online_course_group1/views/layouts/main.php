<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Quản lý khóa học online</title>
    <link rel="stylesheet" href="/Website_Quan_ly_khoa_hoc_online/assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding-top: 80px;
        }

        footer {
            background: white;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/header.php'; ?>

    <main>
        <?php echo $content; ?>
    </main>

    <?php include __DIR__ . '/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Website_Quan_ly_khoa_hoc_online/assets/js/script.js"></script>
</body>

</html>