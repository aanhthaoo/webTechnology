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
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
        }

        .hero-section {
            padding: 100px 0;
            text-align: center;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 2rem;
            color: #2c3e50;
        }

        .hero-title .highlight {
            color: var(--primary-color);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: #6c757d;
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-buttons {
            margin-bottom: 4rem;
        }

        .btn-cta {
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            margin: 0 10px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary-cta {
            background: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-primary-cta:hover {
            background: #3367d6;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(66, 133, 244, 0.3);
        }

        .btn-outline-cta {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-outline-cta:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .features-section {
            padding: 80px 0;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-align: center;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem auto;
            font-size: 2rem;
            color: white;
        }

        .feature-icon.blue {
            background: linear-gradient(135deg, var(--primary-color), #3367d6);
        }

        .feature-icon.green {
            background: linear-gradient(135deg, var(--secondary-color), #2e7d32);
        }

        .feature-icon.purple {
            background: linear-gradient(135deg, var(--purple-color), #7b1fa2);
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .feature-desc {
            color: #6c757d;
            line-height: 1.6;
        }
    </style>


<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">
                Nâng tầm tri thức cùng <span class="highlight">EduPro</span>
            </h1>
            <p class="hero-subtitle">
                Nền tảng học trực tuyến hàng đầu kết nối học viên và giảng viên. Học
                mọi lúc, mọi nơi với sự hỗ trợ của AI.
            </p>

            <div class="cta-buttons">
                <a href="<?php echo baseUrl('/auth/select-register'); ?>" class="btn btn-cta btn-primary-cta">
                    Bắt đầu ngay hôm nay
                </a>
                <a href="<?php echo baseUrl('/auth/select-role'); ?>" class="btn btn-cta btn-outline-cta">
                    Đã có tài khoản?
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon blue">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3 class="feature-title">Học tập linh hoạt</h3>
                        <p class="feature-desc">
                            Truy cập hàng trăm khóa học chất lượng cao từ các chuyên gia hàng đầu.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon purple">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <h3 class="feature-title">Chứng chỉ uy tín</h3>
                        <p class="feature-desc">
                            Nhận chứng chỉ hoàn thành khóa học để nâng cao hồ sơ năng lực của bạn.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
