<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();
if (!isset($_SESSION['user_id']))
    return;

$userName = $_SESSION['user_name'] ?? 'Người dùng';
$role = $_SESSION['user_role'] ?? 0;
$currentUri = $_SERVER['REQUEST_URI'];

// 1. Cấu hình Menu cho từng Role vào mảng
$menus = [];

// Menu GIẢNG VIÊN (Role 1)
if ($role == 1) {
    $userRoleName = 'GIẢNG VIÊN';
    $userIcon = 'fas fa-chalkboard-teacher fa-2x text-success';
    $menus = [
        ['url' => '/instructor/dashboard', 'icon' => 'fa-chart-bar', 'label' => 'Tổng quan'],
        ['url' => '/instructor/courses', 'icon' => 'fa-book', 'label' => 'Quản lý khóa học'],
        ['url' => '/instructor/student-progress', 'icon' => 'fa-chart-line', 'label' => 'Tiến độ học viên'],

    ];
}
// Menu ADMIN (Role 2)
elseif ($role == 2) {
    $userRoleName = 'QUẢN TRỊ VIÊN';
    $userIcon = 'fas fa-user-shield fa-2x text-warning';
    $menus = [
        ['url' => '/admin/dashboard', 'icon' => 'fa-tachometer-alt', 'label' => 'Dashboard'],
        ['url' => '/admin/users', 'icon' => 'fa-users', 'label' => 'Quản lý người dùng'],
        ['url' => '/admin/categories', 'icon' => 'fas fa-list', 'label' => 'Danh mục'],
        ['url' => '/admin/courses', 'icon' => 'fa-book', 'label' => 'Quản lý khóa học'],
    ];
}
// Menu HỌC VIÊN (Role 0)
else {
    $userRoleName = 'HỌC VIÊN';
    $userIcon = 'fas fa-user-graduate fa-2x text-primary';
    $menus = [
        ['url' => '/student/dashboard', 'icon' => 'fa-home', 'label' => 'Dashboard'],
        ['url' => '/student/courses', 'icon' => 'fa-book-reader', 'label' => 'Khóa học của tôi'],
        ['url' => '/courses', 'icon' => 'fa-search', 'label' => 'Tìm kiếm khóa học'],
    ];
}

// Hàm check active (logic: URL hiện tại chứa URL menu thì active)
function getActiveClass($currentUri, $menuUrl)
{
    // Xử lý đặc biệt cho trang tìm kiếm để tránh nhận nhầm active
    if ($menuUrl === '/courses' && strpos($currentUri, '/student/') !== false)
        return '';

    return strpos($currentUri, $menuUrl) !== false ? 'active' : '';
}
?>

<div class="sidebar bg-dark text-white" id="sidebar">
    <div class="sidebar-content">
        <div class="user-info p-3 border-bottom border-secondary">
            <div class="d-flex align-items-center">
                <div class="user-avatar me-3">
                    <i class="<?php echo $userIcon; ?>"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-white"><?php echo htmlspecialchars($userName); ?></h6>
                    <small class="text-white"><?php echo $userRoleName; ?></small>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <ul class="nav flex-column">

                <?php foreach ($menus as $item): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo getActiveClass($currentUri, $item['url']); ?> d-flex align-items-center py-3"
                            href="<?php echo baseUrl($item['url']); ?>">
                            <i class="fas <?php echo $item['icon']; ?> me-3"></i>
                            <span><?php echo $item['label']; ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>

                <li class="nav-item mt-auto">
                    <a class="nav-link d-flex align-items-center py-3 text-danger"
                        href="<?php echo baseUrl('logout'); ?>">
                        <i class="fas fa-sign-out-alt me-3"></i>
                        <span>Đăng xuất</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<style>
    /* Sidebar Styles */
    .sidebar {
        width: 280px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    .sidebar-content {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .user-info {
        background: rgba(0, 0, 0, 0.2);
    }

    .user-avatar i {
        color: #3498db !important;
    }

    .user-avatar .text-success {
        color: #28a745 !important;
    }

    .user-avatar .text-warning {
        color: #ffc107 !important;
    }

    .user-avatar .text-primary {
        color: #007bff !important;
    }

    .sidebar-nav {
        flex: 1;
        padding: 1rem 0;
    }

    .sidebar-nav .nav-link {
        color: #bdc3c7;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
        border-radius: 0;
        font-weight: 500;
    }

    .sidebar-nav .nav-link:hover {
        background: rgba(52, 152, 219, 0.1);
        color: #3498db;
        padding-left: 2rem;
    }

    .sidebar-nav .nav-link.active {
        background: linear-gradient(90deg, #3498db, #2980b9);
        color: white;
        border-left: 4px solid #fff;
    }

    .sidebar-nav .nav-link.active:hover {
        padding-left: 1.5rem;
    }

    .sidebar-nav .nav-link i {
        width: 20px;
        text-align: center;
    }

    .sidebar-nav .nav-link.text-danger {
        color: #dc3545 !important;
    }

    .sidebar-nav .nav-link.text-danger:hover {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545 !important;
    }

    /* Main content adjustment */
    .main-content {
        margin-left: 280px;
        min-height: 100vh;
        background: #f8f9fa;
    }
</style>