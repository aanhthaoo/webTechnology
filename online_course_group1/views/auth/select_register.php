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
    padding-top: 120px;
}

.page-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: calc(100vh - 200px);
    width: 100%;
    padding: 2rem 0;
}

.register-selection-container {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    max-width: 500px;
    width: 90%;
    margin: 0 auto;
}

.register-header {
    text-align: center;
    padding: 3rem 2rem 2rem 2rem;
    color: white;
}

.register-title {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 0.8rem;
    color: black;
}

.register-subtitle {
    font-size: 1rem;
    opacity: 0.9;
    margin-bottom: 0;
    color: black;
}

.role-options {
    padding: 2rem;
}

.role-option {
    background: #f8f9fa;
    border: 2px solid transparent;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: block;
    color: inherit;
    position: relative;
    overflow: hidden;
}

.role-option:hover {
    background: #e3f2fd;
    border-color: var(--primary-color);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(66, 133, 244, 0.15);
    text-decoration: none;
    color: inherit;
}

.role-option:last-child {
    margin-bottom: 0;
}

.role-option-content {
    display: flex;
    align-items: center;
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
    margin-right: 1.5rem;
}

.role-icon.student {
    background: linear-gradient(135deg, var(--primary-color), #3367d6);
}

.role-icon.instructor {
    background: linear-gradient(135deg, var(--secondary-color), #2e7d32);
}

.role-name {
    font-size: 1.4rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.role-desc {
    color: #6c757d;
    font-size: 1rem;
    line-height: 1.4;
}

.back-link {
    text-align: center;
    padding: 0 2rem 2rem 2rem;
    border-top: 1px solid #e9ecef;
}

.back-link a {
    color: var(--primary-color);
    text-decoration: none;
    font-size: 0.95rem;
    display: inline-flex;
    align-items: center;
    padding: 1rem 0;
}

.back-link a:hover {
    text-decoration: underline;
}

/* Animation */
.register-selection-container {
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

</style>

<!-- Page Container -->
<div class="page-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="register-selection-container">
                    <!-- Header -->
                    <div class="register-header">
                        <h1 class="register-title">Bạn muốn đăng ký làm?</h1>
                        <p class="register-subtitle">Vui lòng chọn vai trò để tiếp tục</p>
                    </div>

                    <!-- Role Options -->
                    <div class="role-options">
                        <!-- Học viên -->
                        <a href="<?php echo baseUrl('/register?role=student'); ?>" class="role-option">
                            <div class="role-option-content">
                                <div class="role-icon student">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div>
                                    <div class="role-name">Học viên</div>
                                    <div class="role-desc">Tôi muốn tìm và học các khóa học</div>
                                </div>
                            </div>
                        </a>

                        <!-- Giảng viên -->
                        <a href="<?php echo baseUrl('/register?role=instructor'); ?>" class="role-option">
                            <div class="role-option-content">
                                <div class="role-icon instructor">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <div>
                                    <div class="role-name">Giảng viên</div>
                                    <div class="role-desc">Tôi muốn chia sẻ kiến thức</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Back Link -->
                    <div class="back-link">
                        <a href="<?php echo baseUrl('/'); ?>">
                            <i class="fas fa-arrow-left me-2"></i>
                            Quay lại trang chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>