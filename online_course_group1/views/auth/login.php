<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/Config.php';

// Lấy thông tin vai trò từ URL parameter
$role = $_GET['role'] ?? 'student';
$roleInfo = [
    'student' => [
        'title' => 'Đăng nhập',
        'subtitle' => 'Vai trò: Học viên',
        'icon' => 'fas fa-user-graduate',
        'color' => '#4285f4'
    ],
    'instructor' => [
        'title' => 'Đăng nhập',
        'subtitle' => 'Vai trò: Giảng viên',
        'icon' => 'fas fa-chalkboard-teacher',
        'color' => '#34a853'
    ],
    'admin' => [
        'title' => 'Đăng nhập',
        'subtitle' => 'Vai trò: Quản trị viên',
        'icon' => 'fas fa-user-shield',
        'color' => '#9c27b0'
    ]
];

$currentRole = $roleInfo[$role] ?? $roleInfo['student'];
?>
    <style>
        :root {
            --role-color: <?php echo $currentRole['color']; ?>;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding-top: 120px;
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #4285f4 !important;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            margin: 0 auto;
        }

        .login-header {
            text-align: center;
            padding:2.5rem 2rem 1.5rem 2rem;
        }

        .role-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: var(--role-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.2rem auto;
            font-size: 1.8rem;
            color: white;
        }

        .login-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.4rem;
        }

        .login-subtitle {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 0;
        }

        .login-form {
            padding: 2rem 2.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.8rem;
            display: block;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 15px 18px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
            margin-top: 0.5rem;
        }

        .form-control:focus {
            border-color: var(--role-color);
            box-shadow: 0 0 0 0.2rem rgba(66, 133, 244, 0.15);
            background: white;
        }

        .input-group {
            margin-bottom: 1.5rem;
        }

        .form-text {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }

        .btn-login {
            background: var(--role-color);
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-weight: 600;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(66, 133, 244, 0.3);
            filter: brightness(1.05);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background: #fee2e2;
            color: #dc2626;
        }

        .alert-success {
            background: #d1fae5;
            color: #059669;
        }

        .links-section {
            text-align: center;
            padding: 1rem 2rem 2rem 2rem;
            border-top: 1px solid #e9ecef;
        }

        .links-section a {
            color: var(--role-color);
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .links-section a:hover {
            text-decoration: underline;
            filter: brightness(1.1);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e9ecef;
        }

        .divider span {
            padding: 0 1rem;
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* Animation cho form load */
        .login-container {
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

    <div class="page-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="login-container">
                    <!-- Header với icon role -->
                    <div class="login-header">
                        <div class="role-icon">
                            <i class="<?php echo $currentRole['icon']; ?>"></i>
                        </div>
                        <h1 class="login-title"><?php echo $currentRole['title']; ?></h1>
                        <p class="login-subtitle"><?php echo $currentRole['subtitle']; ?></p>
                    </div>

                    <!-- Login Form -->
                    <div class="login-form">
                        <!-- Flash Messages -->
                        <?php if (isset($_SESSION['flash_message'])): ?>
                            <div class="alert alert-<?php echo $_SESSION['flash_type'] === 'error' ? 'danger' : $_SESSION['flash_type']; ?> alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?php echo $_SESSION['flash_message']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php
                            unset($_SESSION['flash_message']);
                            unset($_SESSION['flash_type']);
                            ?>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo baseUrl('/login'); ?>" id="loginForm">
                            <!-- Hidden field để lưu role -->
                            <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">

                            <!-- Email Field -->
                            <div class="form-group">
                                <label for="username" class="form-label">Email</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="example@edu.vn" required>
                            </div>

                            <!-- Password Field -->
                            <div class="form-group">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="••••••••" required>
                            </div>

                            <!-- Login Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Links Section -->
                    <div class="links-section">
                        <a href="<?php echo baseUrl('/auth/select-role'); ?>">Chọn lại vai trò</a>

                        <div class="divider">
                            <span>hoặc</span>
                        </div>

                        <p class="mb-0">
                            Chưa có tài khoản?
                            <a href="<?php echo baseUrl('/auth/select-register'); ?>">Đăng ký ngay</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Custom JavaScript for Form Validation -->
    <script>
        // Form validation và UX improvements
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const loginBtn = document.querySelector('.btn-login');

            // Real-time validation
            function validateEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            function validateForm() {
                const username = usernameInput.value.trim();
                const password = passwordInput.value;

                let isValid = true;

                // Reset previous validation states
                usernameInput.classList.remove('is-valid', 'is-invalid');
                passwordInput.classList.remove('is-valid', 'is-invalid');

                // Username/Email validation
                if (username === '') {
                    usernameInput.classList.add('is-invalid');
                    isValid = false;
                } else if (!validateEmail(username) && username.length < 3) {
                    usernameInput.classList.add('is-invalid');
                    isValid = false;
                } else {
                    usernameInput.classList.add('is-valid');
                }

                // Password validation
                if (password === '') {
                    passwordInput.classList.add('is-invalid');
                    isValid = false;
                } else if (password.length < 3) {
                    passwordInput.classList.add('is-invalid');
                    isValid = false;
                } else {
                    passwordInput.classList.add('is-valid');
                }

                // Enable/disable login button
                loginBtn.disabled = !isValid;

                return isValid;
            }

            // Real-time validation on input
            usernameInput.addEventListener('input', validateForm);
            passwordInput.addEventListener('input', validateForm);

            // Form submission
            loginForm.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();

                    // Show error message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                    alertDiv.innerHTML = `
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Vui lòng kiểm tra lại thông tin đăng nhập!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;

                    // Insert alert before form
                    const existingAlert = document.querySelector('.alert');
                    if (existingAlert) {
                        existingAlert.remove();
                    }
                    loginForm.insertAdjacentElement('beforebegin', alertDiv);

                    // Auto remove alert after 5 seconds
                    setTimeout(() => {
                        if (alertDiv.parentNode) {
                            alertDiv.remove();
                        }
                    }, 5000);

                    return false;
                }

                // Show loading state
                loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang đăng nhập...';
                loginBtn.disabled = true;
            });

            // Initial validation
            validateForm();

            // Auto focus on first empty field
            if (usernameInput.value === '') {
                usernameInput.focus();
            } else if (passwordInput.value === '') {
                passwordInput.focus();
            }

            // Add floating label effect
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentNode.classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    if (this.value === '') {
                        this.parentNode.classList.remove('focused');
                    }
                });

                // Check if input has value on load
                if (input.value !== '') {
                    input.parentNode.classList.add('focused');
                }
            });
        });
    </script>
