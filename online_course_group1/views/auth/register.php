<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/Config.php';

// Lấy thông tin vai trò từ URL parameter
$role = $_GET['role'] ?? 'student';
$roleInfo = [
    'student' => [
        'title' => 'Đăng ký tài khoản',
        'subtitle' => 'Vai trò: Học viên',
        'icon' => 'fas fa-user-graduate',
        'color' => '#4285f4'
    ],
    'instructor' => [
        'title' => 'Đăng ký tài khoản',
        'subtitle' => 'Vai trò: Giảng viên',
        'icon' => 'fas fa-chalkboard-teacher',
        'color' => '#34a853'
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

    .page-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 200px);
        width: 100%;
        padding: 2rem 0;
    }

    .register-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-width: 480px;
        width: 90%;
        margin: 0 auto;
    }

    .register-header {
        text-align: center;
        padding: 2.5rem 2rem 1.5rem 2rem;
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

    .register-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.4rem;
    }

    .register-subtitle {
        color: #6c757d;
        font-size: 0.95rem;
        margin-bottom: 0;
    }

    .register-form {
        padding: 1.5rem 2rem 2rem 2rem;
    }

    /* Form Styling */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.6rem;
        display: block;
        font-size: 0.9rem;
    }

    .form-control {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 14px 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
        width: 100%;
        margin-top: 0.4rem;
    }

    .form-control:focus {
        border-color: var(--role-color);
        box-shadow: 0 0 0 0.2rem rgba(66, 133, 244, 0.15);
        background: white;
        outline: none;
    }

    .form-control::placeholder {
        color: #9ca3af;
        font-size: 0.9rem;
    }

    /* Validation States */
    .form-control.is-valid {
        border-color: #28a745;
        background-color: #f8fff9;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        background-color: #fff8f8;
    }

    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.8rem;
        color: #dc3545;
    }

    .form-control.is-invalid+.invalid-feedback {
        display: block;
    }

    .btn-register {
        background: var(--role-color);
        border: none;
        border-radius: 10px;
        padding: 14px;
        font-weight: 600;
        font-size: 1rem;
        letter-spacing: 0.5px;
        width: 100%;
        transition: all 0.3s ease;
        color: white;
        margin-top: 1rem;
    }

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(66, 133, 244, 0.3);
        filter: brightness(1.05);
    }

    .btn-register:active {
        transform: translateY(0);
    }

    .btn-register:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .alert {
        border-radius: 10px;
        border: none;
        padding: 0.8rem 1.2rem;
        margin-bottom: 1.2rem;
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
        padding: 1.2rem 2rem 1.5rem 2rem;
        border-top: 1px solid #e9ecef;
    }

    .links-section a {
        color: var(--role-color);
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .links-section a:hover {
        text-decoration: underline;
        filter: brightness(1.1);
    }

    .divider {
        display: flex;
        align-items: center;
        margin: 1.2rem 0;
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
        font-size: 0.85rem;
    }

    /* Animation */
    .register-container {
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

    /* Responsive */
    @media (max-width: 768px) {
        body {
            padding-top: 100px;
        }

        .register-container {
            max-width: 400px;
            margin: 1rem;
            width: 95%;
        }

        .register-form {
            padding: 1.2rem 1.5rem;
        }

        .links-section {
            padding: 1rem 1.5rem;
        }
    }
</style>

<!-- Page Container -->
<div class="page-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="register-container">
                    <!-- Header với icon role -->
                    <div class="register-header">
                        <div class="role-icon">
                            <i class="<?php echo $currentRole['icon']; ?>"></i>
                        </div>
                        <h1 class="register-title"><?php echo $currentRole['title']; ?></h1>
                        <p class="register-subtitle"><?php echo $currentRole['subtitle']; ?></p>
                    </div>

                    <!-- Register Form -->
                    <div class="register-form">
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

                        <form method="POST" action="<?php echo baseUrl('/register'); ?>" id="registerForm">
                            <!-- Hidden field để lưu role -->
                            <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">

                            <!-- Họ và tên -->
                            <div class="form-group">
                                <label for="username" class="form-label">Tên đăng nhập</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Nhập tên đăng nhập" required>
                                <div class="invalid-feedback">
                                    Tên đăng nhập phải có ít nhất 3 ký tự
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fullname" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="fullname" name="fullname"
                                    placeholder="Nhập họ tên của bạn" required>
                                <div class="invalid-feedback">
                                    Vui lòng nhập họ và tên hợp lệ
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="example@edu.vn" required>
                                <div class="invalid-feedback">
                                    Vui lòng nhập địa chỉ email hợp lệ
                                </div>
                            </div>

                            <!-- Mật khẩu -->
                            <div class="form-group">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="••••••••" required>
                                <div class="invalid-feedback">
                                    Mật khẩu phải có ít nhất 6 ký tự
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                    placeholder="••••••••" required>
                                <div class="invalid-feedback">
                                    Mật khẩu xác nhận không khớp
                                </div>
                            </div>

                            <!-- Register Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-register" id="registerBtn">
                                    <i class="fas fa-user-plus me-2"></i>Tạo tài khoản
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Links Section -->
                    <div class="links-section">
                        <a href="<?php echo baseUrl('/auth/select-register'); ?>">Chọn lại vai trò</a>

                        <div class="divider">
                            <span>hoặc</span>
                        </div>

                        <p class="mb-0">
                            Đã có tài khoản?
                            <a href="<?php echo baseUrl('/auth/select-role'); ?>">Đăng nhập ngay</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Validation -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const registerForm = document.getElementById('registerForm');
        const fullnameInput = document.getElementById('fullname');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const registerBtn = document.getElementById('registerBtn');

        // Validation functions
        function validateFullname(name) {
            return name.trim().length >= 2 && /^[a-zA-ZÀ-ỹ\s]+$/.test(name.trim());
        }

        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function validatePassword(password) {
            return password.length >= 6;
        }

        // Real-time validation
        function validateField(input, validationFn) {
            const isValid = validationFn(input.value);

            if (input.value === '') {
                input.classList.remove('is-valid', 'is-invalid');
            } else if (isValid) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
            } else {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
            }

            updateSubmitButton();
            return isValid;
        }

        // Update submit button state
        function updateSubmitButton() {
            const isFormValid =
                validateFullname(fullnameInput.value) &&
                validateEmail(emailInput.value) &&
                validatePassword(passwordInput.value);

            registerBtn.disabled = !isFormValid;
        }

        // Event listeners for real-time validation
        fullnameInput.addEventListener('blur', () => validateField(fullnameInput, validateFullname));
        fullnameInput.addEventListener('input', () => validateField(fullnameInput, validateFullname));

        emailInput.addEventListener('blur', () => validateField(emailInput, validateEmail));
        emailInput.addEventListener('input', () => validateField(emailInput, validateEmail));

        passwordInput.addEventListener('blur', () => validateField(passwordInput, validatePassword));
        passwordInput.addEventListener('input', () => validateField(passwordInput, validatePassword));

        // Form submission validation
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Reset validation states
            [fullnameInput, emailInput, passwordInput].forEach(input => {
                input.classList.remove('is-valid', 'is-invalid');
            });

            // Validate all fields
            const isFullnameValid = validateField(fullnameInput, validateFullname);
            const isEmailValid = validateField(emailInput, validateEmail);
            const isPasswordValid = validateField(passwordInput, validatePassword);

            // If all valid, show loading state and submit
            if (isFullnameValid && isEmailValid && isPasswordValid) {
                registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang tạo tài khoản...';
                registerBtn.disabled = true;

                // Simulate form submission delay
                setTimeout(() => {
                    registerForm.submit();
                }, 1000);
            } else {
                // Focus on first invalid field
                if (!isFullnameValid) fullnameInput.focus();
                else if (!isEmailValid) emailInput.focus();
                else if (!isPasswordValid) passwordInput.focus();
            }
        });

        // Initialize button state
        updateSubmitButton();
    });
</script>