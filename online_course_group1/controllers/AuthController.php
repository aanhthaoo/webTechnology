<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User($this->db);
    }

    // Hiển thị trang chọn vai trò
    public function selectRole()
    {
        $this->render('auth/select_role');
    }

    // Hiển thị form đăng nhập
    public function showLogin()
    {
        $this->render('auth/login');
    }

    // Xử lý đăng nhập
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $expectedRole = $_POST['role'] ?? 'student'; // Role từ form

            // Validate input
            if (empty($username) || empty($password)) {
                $this->setFlash('Vui lòng nhập đầy đủ thông tin!', 'error');
                $this->redirect('/login?role=' . $expectedRole);
                return;
            }

            // Kiểm tra thông tin đăng nhập
            $user = $this->userModel->login($username, $password);

            if ($user === 'disabled') {
                $this->setFlash('Tài khoản của bạn đã bị vô hiệu hóa. Vui lòng liên hệ quản trị viên.', 'error');
                $this->redirect('/login?role=' . $expectedRole);
                return;
            } else if (is_array($user)) {
                // Kiểm tra role có khớp không (chuyển đổi role string sang number)
                $roleMap = [
                    'student' => 0,
                    'instructor' => 1,
                    'admin' => 2
                ];

                $expectedRoleNum = $roleMap[$expectedRole] ?? 0;

                // Nếu role không khớp, hiển thị thông báo lỗi
                if ($user['role'] != $expectedRoleNum) {
                    $roleNames = [
                        0 => 'Học viên',
                        1 => 'Giảng viên',
                        2 => 'Quản trị viên'
                    ];

                    $this->setFlash('Tài khoản của bạn là ' . $roleNames[$user['role']] . '. Vui lòng chọn đúng vai trò!', 'error');
                    $this->redirect('/login?role=' . $expectedRole);
                    return;
                }

                // Lưu thông tin user vào session
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['fullname'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['username'] = $user['username'];

                // Redirect theo role
                switch ($user['role']) {
                    case 2: // Admin
                        $this->redirect('/admin/dashboard');
                        break;
                    case 1: // Instructor
                        $this->redirect('/instructor/dashboard');
                        break;
                    default: // Student
                        $this->redirect('/student/dashboard');
                        break;
                }
            } else {
                $this->setFlash('Tên đăng nhập hoặc mật khẩu không đúng!', 'error');
                $this->redirect('/login?role=' . $expectedRole);
            }
        }
    }

    public function showSelectRegister()
    {
        $this->render('auth/select_register');
    }

    // Hiển thị form đăng ký
    public function showRegister()
    {
        $this->render('auth/register');
    }

    // Xử lý đăng ký
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');
            $fullname = trim($_POST['fullname'] ?? '');

            $roleString = $_POST['role'] ?? $_GET['role'] ?? 'student';

            // Convert role string thành number
            $roleMap = [
                'student' => 0,
                'instructor' => 1
            ];

            $role = $roleMap[$roleString] ?? 0; // Mặc định là học viên (0)

            // Validate input
            $errors = [];

            if (empty($username)) {
                $errors[] = 'Tên đăng nhập không được để trống';
            } elseif (strlen($username) < 3) {
                $errors[] = 'Tên đăng nhập phải có ít nhất 3 ký tự';
            }

            if (empty($email)) {
                $errors[] = 'Email không được để trống';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email không hợp lệ';
            }

            if (empty($password)) {
                $errors[] = 'Mật khẩu không được để trống';
            } elseif (strlen($password) < 6) {
                $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }

            if ($password !== $confirmPassword) {
                $errors[] = 'Mật khẩu xác nhận không khớp';
            }

            if (empty($fullname)) {
                $errors[] = 'Họ tên không được để trống';
            }

            if (!in_array($role, [0, 1])) {
                $role = 0; // Mặc định là học viên
            }

            // Kiểm tra username và email đã tồn tại chưa
            if (empty($errors)) {
                if ($this->userModel->checkUsernameExists($username)) {
                    $errors[] = 'Tên đăng nhập đã tồn tại';
                }

                if ($this->userModel->checkEmailExists($email)) {
                    $errors[] = 'Email đã được sử dụng';
                }
            }

            if (!empty($errors)) {
                $this->setFlash(implode('<br>', $errors), 'error');
                $this->redirect('/register');
                return;
            }

            // Tạo tài khoản mới
            $userData = [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'fullname' => $fullname,
                'role' => $role
            ];

            if ($this->userModel->create($userData)) {
                $this->setFlash('Đăng ký thành công! Vui lòng đăng nhập.', 'success');
                $this->redirect('/auth/select-role');
            } else {
                $this->setFlash('Có lỗi xảy ra. Vui lòng thử lại!', 'error');
                $this->redirect('/auth/select-register');
            }
        }
    }

    // Đăng xuất
    public function logout()
    {
        // Khởi động session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Lưu lại thông tin để log (optional)
        $userName = $_SESSION['user_name'] ?? 'Unknown';
        $userRole = $_SESSION['user_role'] ?? null;

        // Xóa tất cả session variables
        $_SESSION = array();

        // Xóa session cookie nếu có
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Hủy session hoàn toàn
        session_destroy();

        // Log logout action (optional)
        error_log("User logout: $userName (role: $userRole)");

        // Set flash message (cần start session mới để lưu flash)
        session_start();
        $this->setFlash('Đăng xuất thành công!', 'success');

        // Redirect về trang chủ
        $this->redirect('/');
    }

    // Kiểm tra quyền admin
    protected function requireAdmin()
    {
        $this->requireAuth();
        if ($_SESSION['user_role'] != 2) {
            $this->setFlash('Bạn không có quyền truy cập!', 'error');
            $this->redirect('/');
        }
    }

    // Kiểm tra quyền instructor
    protected function requireInstructor()
    {
        $this->requireAuth();
        if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 2) {
            $this->setFlash('Bạn không có quyền truy cập!', 'error');
            $this->redirect('/');
        }
    }
}
