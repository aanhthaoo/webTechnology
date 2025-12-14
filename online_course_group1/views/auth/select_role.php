<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
    <style>
        :root {
            --primary-color: #4285f4;
            --secondary-color: #34a853;
            --accent-color: #ea4335;
            --purple-color: #9c27b0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding-top: 20px;
        }

        .page-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 60vh; 
            width: 100%;
            padding: 2rem 0;
        }

        .role-selection-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            margin: 2rem auto;
        }

        .role-header {
            text-align: center;
            padding: 3rem 2rem 2rem 2rem;
        }

        .role-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .role-subtitle {
            color: #6c757d;
            font-size: 1rem;
        }

        .role-options {
            padding: 1rem 2rem 3rem 2rem;
        }

        .role-option {
            background: #f8f9fa;
            border: 2px solid transparent;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            color: inherit;
        }

        .role-option:hover {
            background: #e3f2fd;
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(66, 133, 244, 0.15);
            text-decoration: none;
            color: inherit;
        }

        .role-option:last-child {
            margin-bottom: 0;
        }

        .role-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            margin-bottom: 1rem;
        }

        .role-icon.student {
            background: linear-gradient(135deg, var(--primary-color), #3367d6);
        }

        .role-icon.instructor {
            background: linear-gradient(135deg, var(--secondary-color), #2e7d32);
        }

        .role-icon.admin {
            background: linear-gradient(135deg, var(--purple-color), #7b1fa2);
        }

        .role-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .role-desc {
            color: #6c757d;
            font-size: 0.95rem;
            line-height: 1.4;
        }

        .back-link {
            text-align: center;
            padding: 0 2rem 2rem 2rem;
        }

        .back-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.95rem;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

    </style>

<body>
    <!-- Role Selection Container -->
   <div class="page-container">
    <div class="container">
        <div class="role-selection-container">
            <!-- Header -->
            <div class="role-header">
                <h1 class="role-title">Đăng nhập với vai trò</h1>
                <p class="role-subtitle">Vui lòng chọn vai trò để tiếp tục</p>
            </div>

            <!-- Role Options -->
            <div class="role-options">
                <!-- Học viên -->
                <a href="<?php echo baseUrl('/login?role=student'); ?>" class="role-option">
                    <div class="d-flex align-items-center">
                        <div class="role-icon student">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="ms-3">
                            <div class="role-name">Học viên</div>
                            <div class="role-desc">Tôi muốn tìm và học các khóa học</div>
                        </div>
                    </div>
                </a>

                <!-- Giảng viên -->
                <a href="<?php echo baseUrl('/login?role=instructor'); ?>" class="role-option">
                    <div class="d-flex align-items-center">
                        <div class="role-icon instructor">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="ms-3">
                            <div class="role-name">Giảng viên</div>
                            <div class="role-desc">Tôi muốn chia sẻ kiến thức</div>
                        </div>
                    </div>
                </a>

                <!-- Quản trị viên -->
                <a href="<?php echo baseUrl('/login?role=admin'); ?>" class="role-option">
                    <div class="d-flex align-items-center">
                        <div class="role-icon admin">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="ms-3">
                            <div class="role-name">Quản trị viên</div>
                            <div class="role-desc">Quản lý hệ thống</div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Back Link -->
            <div class="back-link">
                <a href="<?php echo baseUrl('/'); ?>">Quay lại trang chủ</a>
            </div>
        </div>
    </div>
</div>
