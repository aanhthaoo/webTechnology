<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Course.php';

class InstructorController extends BaseController
{
    private $courseModel;

    public function __construct()
    {
        parent::__construct();
        $this->courseModel = new Course();
    }

    /**
     * Hiển thị dashboard tổng quan cho giảng viên
     */
    public function dashboard()
    {
        // Kiểm tra quyền truy cập - chỉ giảng viên mới được vào
        $this->requireRole('instructor');

        // Lấy thông tin user hiện tại
        $currentUser = $this->getCurrentUser();
        $instructorId = $currentUser['id'];

        try {
            // Lấy dữ liệu thống kê từ model
            $dashboardData = $this->courseModel->getDashboardStats($instructorId);

            // Chuẩn bị dữ liệu để truyền vào view
            $data = [
                'page_title' => 'Tổng quan hệ thống',
                'current_user' => $currentUser,
                'stats' => [
                    'total_courses' => $dashboardData['total_courses'],
                    'total_students' => $dashboardData['total_students']
                ],
                'students_list' => $dashboardData['students_list'],
                'has_students' => count($dashboardData['students_list']) > 0
            ];

            // Render view dashboard
            $this->render('instructor/dashboard', $data, 'dashboard_layout');
        } catch (Exception $e) {
            // Xử lý lỗi
            $this->setFlash('Có lỗi xảy ra khi tải dữ liệu dashboard: ' . $e->getMessage(), 'error');

            // Render view với dữ liệu mặc định
            $data = [
                'page_title' => 'Tổng quan hệ thống',
                'current_user' => $currentUser,
                'stats' => [
                    'total_courses' => 0,
                    'total_students' => 0
                ],
                'students_list' => [],
                'has_students' => false
            ];

            $this->render('instructor/dashboard', $data, 'dashboard_layout');
        }
    }

    /**
     * Hiển thị danh sách khóa học của giảng viên
     */
    public function myCourses()
    {
        $this->requireAuth();
        $this->requireRole('instructor');

        // Lấy thông tin user hiện tại
        $currentUser = $this->getCurrentUser();
        $instructorId = $currentUser['id'];

        try {
            // Lấy danh sách khóa học từ model
            $courses = $this->courseModel->getCoursesByInstructor($instructorId);

            // Chuẩn bị dữ liệu để truyền vào view
            $data = [
                'page_title' => 'Quản lý khóa học',
                'current_user' => $currentUser,
                'courses' => $courses,
                'has_courses' => count($courses) > 0
            ];

            // Render view với dashboard layout
            $this->render('instructor/my_courses', $data, 'dashboard_layout');
        } catch (Exception $e) {
            // Xử lý lỗi
            $this->setFlash('Có lỗi xảy ra khi tải danh sách khóa học: ' . $e->getMessage(), 'error');

            // Render view với dữ liệu rỗng
            $data = [
                'page_title' => 'Quản lý khóa học',
                'current_user' => $currentUser,
                'courses' => [],
                'has_courses' => false
            ];

            $this->render('instructor/my_courses', $data, 'dashboard_layout');
        }
    }

    /**
     * Tạo khóa học mới
     */
    public function createCourse()
    {
        $this->requireAuth();
        $this->requireRole('instructor');

        $currentUser = $this->getCurrentUser();

        // Lấy danh sách categories để hiển thị trong dropdown
        require_once __DIR__ . '/../models/Category.php';
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();

        $data = [
            'page_title' => 'Tạo khóa học mới',
            'current_user' => $currentUser,
            'categories' => $categories
        ];

        $this->render('instructor/course/create', $data, 'dashboard_layout');
    }

    private function handleImageUpload($file)
    {
        // Kiểm tra lỗi upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Kiểm tra đuôi file
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            return null;
        }

        // Tạo tên file ngẫu nhiên để tránh trùng
        $fileName = uniqid('course_') . '.' . $ext;

        // Đường dẫn thư mục lưu: assets/uploads/courses/
        $uploadDir = __DIR__ . '/../assets/uploads/courses/';

        // Tạo thư mục nếu chưa có
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Di chuyển file từ bộ nhớ tạm vào thư mục đích
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
            return $fileName;
        }

        return null;
    }

    /**
     * Lưu khóa học mới
     */
    public function storeCourse()
    {
        $this->requireAuth();
        $this->requireRole('instructor');

        $currentUser = $this->getCurrentUser();
        $instructorId = $currentUser['id'];

        // Validate dữ liệu từ form
        $errors = [];

        if (empty($_POST['title'])) {
            $errors[] = 'Tên khóa học không được để trống';
        }

        if (empty($_POST['description'])) {
            $errors[] = 'Mô tả không được để trống';
        }

        if (empty($_POST['category_id'])) {
            $errors[] = 'Vui lòng chọn danh mục';
        }

        if (!isset($_POST['price']) || $_POST['price'] < 0) {
            $errors[] = 'Giá tiền không hợp lệ';
        }

        if (empty($_POST['duration_weeks']) || $_POST['duration_weeks'] < 1) {
            $errors[] = 'Thời lượng phải lớn hơn 0';
        }

        // Nếu có lỗi, quay lại form


        if (empty($_POST['duration_weeks']) || $_POST['duration_weeks'] < 1) {
            $errors[] = 'Thời lượng phải lớn hơn 0';
        }

        // Validation cho level
        if (empty($_POST['level'])) {
            $errors[] = 'Vui lòng chọn độ khó';
        } elseif (!in_array($_POST['level'], ['Beginner', 'Intermediate', 'Advanced'])) {
            $errors[] = 'Độ khó không hợp lệ';
        }

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            $this->redirect('/instructor/courses/create');
            return;
        }

        $imageName = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageName = $this->handleImageUpload($_FILES['image']);

            if ($imageName === null) {
                $this->setFlash('File ảnh không hợp lệ. Chỉ chấp nhận JPG, PNG, WEBP, GIF', 'error');
                $_SESSION['form_data'] = $_POST;
                $this->redirect('/instructor/courses/create');
                return;
            }
        }


        // Chuẩn bị dữ liệu để lưu
        $courseData = [
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description']),
            'category_id' => (int)$_POST['category_id'],
            'price' => (float)$_POST['price'],
            'duration_weeks' => (int)$_POST['duration_weeks'],
            'instructor_id' => $instructorId,
            'level' => trim($_POST['level']), // Mặc định
            'image' => $imageName
        ];

        try {
            // Gọi model để insert vào database
            $courseId = $this->courseModel->createCourse($courseData);

            if ($courseId) {
                $this->setFlash('Tạo khóa học thành công!', 'success');
                $this->redirect('/instructor/courses');
            } else {
                $this->setFlash('Có lỗi xảy ra khi tạo khóa học', 'error');
                $this->redirect('/instructor/courses/create');
            }
        } catch (Exception $e) {
            error_log("Error in storeCourse: " . $e->getMessage());
            $this->setFlash('Lỗi hệ thống: ' . $e->getMessage(), 'error');
            $this->redirect('/instructor/courses/create');
        }
    }

    /**
     * Quản lý khóa học cụ thể
     */
    public function manageCourse($courseId)
    {
        $this->requireAuth();
        $this->requireRole('instructor');

        $currentUser = $this->getCurrentUser();

        // 1. Kiểm tra khóa học có tồn tại và thuộc về giảng viên này không
        $course = $this->courseModel->getCourseById($courseId, $currentUser['id']);

        if (!$course) {
            $this->setFlash('Khóa học không tồn tại hoặc bạn không có quyền truy cập.', 'error');
            $this->redirect('/instructor/courses');
            return;
        }

        require_once __DIR__ . '/../models/Lesson.php';
        $lessonModel = new Lesson();
        $lessons = $lessonModel->getLessonsByCourseId($courseId);
        $materialCount = $lessonModel->countMaterials($courseId);

        $data = [
            'page_title' => 'Quản lý nội dung: ' . $course['title'],
            'course' => $course,
            'lessons' => $lessons,
            'material_count' => $materialCount
        ];

        // Render view (đường dẫn view mới)
        $this->render('instructor/course/manage', $data, 'dashboard_layout');
    }

    /**
     * Hiển thị form chỉnh sửa khóa học (GET)
     */
    public function editCourse($courseId)
    {
        $this->requireAuth();
        $this->requireRole('instructor');

        // Lấy thông tin user hiện tại
        $currentUser = $this->getCurrentUser();
        $instructorId = $currentUser['id'];

        try {
            // Lấy thông tin khóa học - verify ownership
            $course = $this->courseModel->getCourseById($courseId, $instructorId);

            if (!$course) {
                $this->setFlash('Không tìm thấy khóa học hoặc bạn không có quyền chỉnh sửa', 'error');
                $this->redirect('/instructor/courses');
                return;
            }

            // Lấy danh sách categories để hiển thị trong dropdown
            require_once __DIR__ . '/../models/Category.php';
            $categoryModel = new Category();
            $categories = $categoryModel->getAll();

            $data = [
                'page_title' => 'Chỉnh sửa khóa học',
                'current_user' => $currentUser,
                'course' => $course,
                'categories' => $categories
            ];

            $this->render('instructor/course/edit', $data, 'dashboard_layout');
        } catch (Exception $e) {
            error_log("Error in editCourse: " . $e->getMessage());
            $this->setFlash('Lỗi hệ thống: ' . $e->getMessage(), 'error');
            $this->redirect('/instructor/courses');
        }
    }

    /**
     * Xử lý cập nhật khóa học (POST)
     */
    public function updateCourse($courseId)
    {
        $this->requireAuth();
        $this->requireRole('instructor');

        // Lấy ID giảng viên hiện tại
        $currentUser = $this->getCurrentUser();
        $instructorId = $currentUser['id'];

        // Validate dữ liệu từ form
        $errors = [];

        if (empty($_POST['title'])) {
            $errors[] = 'Tên khóa học không được để trống';
        }

        if (empty($_POST['description'])) {
            $errors[] = 'Mô tả không được để trống';
        }

        if (empty($_POST['category_id'])) {
            $errors[] = 'Vui lòng chọn danh mục';
        }

        if (!isset($_POST['price']) || $_POST['price'] < 0) {
            $errors[] = 'Giá tiền không hợp lệ';
        }

        if (empty($_POST['duration_weeks']) || $_POST['duration_weeks'] < 1) {
            $errors[] = 'Thời lượng phải lớn hơn 0';
        }

        if (empty($_POST['level'])) {
            $errors[] = 'Vui lòng chọn độ khó';
        } elseif (!in_array($_POST['level'], ['Beginner', 'Intermediate', 'Advanced'])) {
            $errors[] = 'Độ khó không hợp lệ';
        }

        // Nếu có lỗi, quay lại form
        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            $this->redirect('/instructor/courses/' . $courseId . '/edit');
            return;
        }

        // Chuẩn bị dữ liệu để update
        $courseData = [
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description']),
            'category_id' => (int)$_POST['category_id'],
            'price' => (float)$_POST['price'],
            'duration_weeks' => (int)$_POST['duration_weeks'],
            'level' => trim($_POST['level'])
        ];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageName = $this->handleImageUpload($_FILES['image']);

            if ($imageName === null) {
                $this->setFlash('File ảnh không hợp lệ. Chỉ chấp nhận JPG, PNG, WEBP, GIF', 'error');
                $_SESSION['form_data'] = $_POST;
                $this->redirect('/instructor/courses/' . $courseId . '/edit');
                return;
            }

            // CHỈ thêm vào courseData nếu upload thành công
            $courseData['image'] = $imageName;

            // // Xóa ảnh cũ (optional)
            $oldCourse = $this->courseModel->getCourseById($courseId, $instructorId);
            if ($oldCourse && !empty($oldCourse['image'])) {
                $oldImagePath = __DIR__ . '/../assets/uploads/courses/' . $oldCourse['image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }

        try {
            // Gọi model để update database
            $result = $this->courseModel->updateCourse($courseId, $courseData, $instructorId);

            if ($result) {
                $this->setFlash('Cập nhật khóa học thành công!', 'success');
                $this->redirect('/instructor/courses');
            } else {
                $this->setFlash('Có lỗi xảy ra khi cập nhật khóa học', 'error');
                $this->redirect('/instructor/courses/' . $courseId . '/edit');
            }
        } catch (Exception $e) {
            error_log("Error in updateCourse: " . $e->getMessage());
            $this->setFlash('Lỗi hệ thống: ' . $e->getMessage(), 'error');
            $this->redirect('/instructor/courses/' . $courseId . '/edit');
        }
    }


    public function deleteCourse($courseId)
    {
        $this->requireAuth();
        $this->requireRole('instructor');

        $currentUser = $this->getCurrentUser();
        $instructorId = $currentUser['id'];

        try {
            $result = $this->courseModel->deleteCourse($courseId, $instructorId);

            if ($result) {
                $this->setFlash('Xóa khóa học thành công!', 'success');
            } else {
                $this->setFlash('Không thể xóa khóa học. Vui lòng thử lại sau.', 'error');
            }
        } catch (Exception $e) {
            error_log("Error in deleteCourse: " . $e->getMessage());
            $this->setFlash('Lỗi hệ thống: ' . $e->getMessage(), 'error');
        }

        $this->redirect('/instructor/courses');
    }


    public function studentProgress()
    {
        $this->requireAuth();
        $this->requireRole('instructor');

        $currentUser = $this->getCurrentUser();
        $instructorId = $currentUser['id'];

        // Lấy các tham số filter từ URL
        $courseId = isset($_GET['course_id']) && $_GET['course_id'] !== '' ? $_GET['course_id'] : null;
        $search = isset($_GET['search']) ? trim($_GET['search']) : null;

        try {
            // 1. Lấy danh sách khóa học để đổ vào Dropdown lọc
            // Tận dụng hàm getCoursesByInstructor đã có
            $courses = $this->courseModel->getCoursesByInstructor($instructorId);

            // 2. Lấy danh sách học viên theo bộ lọc
            $students = $this->courseModel->getStudentsByInstructor($instructorId, $courseId, $search);

            $data = [
                'page_title' => 'Theo dõi tiến độ học viên',
                'current_user' => $currentUser,
                'courses' => $courses,         // Dữ liệu cho dropdown
                'students' => $students,       // Dữ liệu cho bảng
                'filters' => [                 // Để giữ lại giá trị đã chọn trên form
                    'course_id' => $courseId,
                    'search' => $search
                ]
            ];

            $this->render('instructor/students/list', $data, 'dashboard_layout');
        } catch (Exception $e) {
            // In lỗi ra màn hình xem cho rõ
            // echo "<div style='background: #fee2e2; color: #b91c1c; padding: 20px; border: 2px solid red; margin: 20px;'>";
            // echo "<pre style='font-size: 16px;'>" . $e->getMessage() . "</pre>";
            // echo "<p>File: " . $e->getFile() . " (Dòng: " . $e->getLine() . ")</p>";
            // echo "</div>";
            // die(); // Dừng code lại ngay lập tức
            $this->setFlash('Lỗi: ' . $e->getMessage(), 'error');
            $this->redirect('/instructor/dashboard');
        }
    }
}
