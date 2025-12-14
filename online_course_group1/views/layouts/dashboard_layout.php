<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'EduPro LMS'; ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Website_Quan_ly_khoa_hoc_online/assets/css/style.css">
    <link rel="stylesheet" href="/Website_Quan_ly_khoa_hoc_online/assets/css/admin-dashboard.css">
    
    <style>
        /* CSS QUAN TRỌNG ĐỂ ĐẨY HEADER/FOOTER SANG PHẢI */
        
        /* 1. Header: Dẹp sang phải 280px */
        .navbar {
            left: 280px !important; 
            width: calc(100% - 280px) !important;
            transition: all 0.3s ease;
        }

        /* 2. Footer: Dẹp sang phải 280px */
        footer.footer {
            margin-left: 280px !important;
            width: auto !important;
            transition: all 0.3s ease;
        }

        /* 3. Nội dung chính: Cũng dẹp sang phải */
        /* Class .main-content này đã có trong style của sidebar.php, 
           nhưng ta khai báo lại ở đây cho thẻ <main> để chắc chắn */
        main {
            margin-left: 280px; 
            min-height: 80vh;
            padding-top: 80px;
        }

        /* Responsive: Mobile thì trả lại full màn hình */
        @media (max-width: 768px) {
            .navbar { left: 0 !important; width: 100% !important; }
            footer.footer { margin-left: 0 !important; }
            main { margin-left: 0 !important; }
            
            /* Ẩn sidebar trên mobile (logic này cần JS toggle sau này) */
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.show { transform: translateX(0); }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/sidebar.php'; ?>

    <?php include __DIR__ . '/header.php'; ?>
    
    <main>
        <?php echo $content; ?>
    </main>
    
    <?php include __DIR__ . '/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Website_Quan_ly_khoa_hoc_online/assets/js/script.js"></script>
</body>
</html>