<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Lesson.php';

class LessonController extends BaseController {
    private $lessonModel;
    private $courseModel;

    public function __construct() {
        parent::__construct();
        $this->lessonModel = new Lesson($this->db);
        $this->courseModel = new Course();
    }


    /**
     * Hiển thị form tạo bài học
     * Route: GET /courses/{courseId}/lessons/create
     */
    public function create($courseId)
    {
        $this->requireAuth();
        $this->requireRole('instructor');
        $currentUser = $this->getCurrentUser();

        // 1. Verify: Khóa học phải tồn tại và thuộc về giảng viên này
        $course = $this->courseModel->getCourseById($courseId, $currentUser['id']);
        
        if (!$course) {
            $this->setFlash('Khóa học không tồn tại hoặc bạn không có quyền truy cập.', 'error');
            $this->redirect('/instructor/courses');
            return;
        }

        $data = [
            'page_title' => 'Thêm bài học mới',
            'course' => $course
        ];

        // Render view tạo bài học
        $this->render('instructor/lessons/create', $data, 'dashboard_layout');
    }

    /**
     * Xử lý lưu bài học
     * Route: POST /courses/{courseId}/lessons
     */
    public function store($courseId)
    {
        $this->requireAuth();
        $this->requireRole('instructor');
        $currentUser = $this->getCurrentUser();

        // 1. Verify quyền sở hữu khóa học lần nữa
        $course = $this->courseModel->getCourseById($courseId, $currentUser['id']);
        if (!$course) {
            $this->redirect('/instructor/courses');
            return;
        }

        // 2. Validate dữ liệu
        $title = trim($_POST['title'] ?? '');
        $videoUrl = trim($_POST['video_url'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if (empty($title)) {
            $this->setFlash('Tiêu đề bài học không được để trống', 'error');
            $this->redirect("/courses/$courseId/lessons/create");
            return;
        }

        // 3. Tự động tính số thứ tự (Order)
        $order = $this->lessonModel->getNextOrder($courseId);

        // 4. Chuẩn bị data
        $lessonData = [
            'course_id' => $courseId,
            'title' => $title,
            'video_url' => $videoUrl,
            'content' => $content,
            'order' => $order
        ];

        // 5. Lưu vào DB
        if ($this->lessonModel->create($lessonData)) {
            $this->setFlash('Thêm bài học thành công!', 'success');
            // Quay về trang quản lý nội dung khóa học
            $this->redirect("/instructor/courses/$courseId/manage");
        } else {
            $this->setFlash('Có lỗi xảy ra, vui lòng thử lại.', 'error');
            $this->redirect("/courses/$courseId/lessons/create");
        }
    }

    /**
     * Hiển thị form chỉnh sửa bài học
     * Route: GET /lessons/{id}/edit
     */
    public function edit($id)
    {
        $this->requireAuth();
        $this->requireRole('instructor');
        $currentUser = $this->getCurrentUser();

        // 1. Lấy thông tin bài học
        $lesson = $this->lessonModel->getLessonById($id);

        if (!$lesson) {
            $this->setFlash('Bài học không tồn tại.', 'error');
            $this->redirect('/instructor/courses');
            return;
        }

        // 2. CHECK BẢO MẬT: Lấy thông tin khóa học để xem giảng viên có quyền không
        // (Tránh trường hợp ông A mò URL sửa bài của ông B)
        $course = $this->courseModel->getCourseById($lesson['course_id'], $currentUser['id']);

        if (!$course) {
            $this->setFlash('Bạn không có quyền chỉnh sửa bài học này.', 'error');
            $this->redirect('/instructor/courses');
            return;
        }

        $data = [
            'page_title' => 'Chỉnh sửa bài học',
            'course' => $course,
            'lesson' => $lesson
        ];

        $this->render('instructor/lessons/edit', $data, 'dashboard_layout');
    }

    /**
     * Xử lý cập nhật bài học
     * Route: POST /lessons/{id}/update
     */
    public function update($id)
    {
        $this->requireAuth();
        $this->requireRole('instructor');
        $currentUser = $this->getCurrentUser();

        // 1. Lấy bài học cũ để check quyền sở hữu
        $lesson = $this->lessonModel->getLessonById($id);
        if (!$lesson) {
            $this->redirect('/instructor/courses');
            return;
        }

        // 2. Verify quyền sở hữu qua Course ID
        $course = $this->courseModel->getCourseById($lesson['course_id'], $currentUser['id']);
        if (!$course) {
            $this->setFlash('Truy cập bị từ chối.', 'error');
            $this->redirect('/instructor/courses');
            return;
        }

        // 3. Validate dữ liệu
        $title = trim($_POST['title'] ?? '');
        $videoUrl = trim($_POST['video_url'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if (empty($title)) {
            $this->setFlash('Tiêu đề không được để trống', 'error');
            $this->redirect("/lessons/$id/edit");
            return;
        }

        // 4. Chuẩn bị data update
        $updateData = [
            'title' => $title,
            'video_url' => $videoUrl,
            'content' => $content
        ];

        // 5. Gọi Model update
        if ($this->lessonModel->update($id, $updateData)) {
            $this->setFlash('Cập nhật bài học thành công!', 'success');
            // Redirect về trang quản lý khóa học
            $this->redirect("/instructor/courses/" . $lesson['course_id'] . "/manage");
        } else {
            $this->setFlash('Có lỗi xảy ra, vui lòng thử lại.', 'error');
            $this->redirect("/lessons/$id/edit");
        }
    }



    /**
     * Hiển thị bài học cho học viên
     * Route: GET /lessons/{id}
     */
    public function show($id)
    {
        $this->requireAuth();
        $currentUser = $this->getCurrentUser();

        // 1. Lấy thông tin bài học
        $lesson = $this->lessonModel->getLessonById($id);
        
        if (!$lesson) {
            $this->setFlash('Bài học không tồn tại.', 'error');
            $this->redirect('/student/courses');
            return;
        }

        // 2. Lấy thông tin khóa học với instructor_name
        $course = $this->courseModel->getCourseDetails($lesson['course_id']);
        
        if (!$course) {
            $this->setFlash('Khóa học không tồn tại.', 'error');
            $this->redirect('/student/courses');
            return;
        }

        // 3. Kiểm tra xem user đã đăng ký khóa học chưa
        require_once __DIR__ . '/../models/Enrollment.php';
        $enrollmentModel = new Enrollment($this->db);
        $isEnrolled = $enrollmentModel->isUserEnrolled($currentUser['id'], $lesson['course_id']);
        
        if (!$isEnrolled) {
            $this->setFlash('Bạn cần đăng ký khóa học để xem bài học này.', 'error');
            $this->redirect('/courses/' . $lesson['course_id']);
            return;
        }

        // 4. Lấy danh sách tất cả bài học của khóa học
        $allLessons = $this->lessonModel->getLessonsByCourseId($lesson['course_id']);
        $totalLessons = count($allLessons);
        
        // 5. Tìm vị trí bài học hiện tại
        $lessonPosition = 1;
        $previousLesson = null;
        $nextLesson = null;
        
        for ($i = 0; $i < count($allLessons); $i++) {
            if ($allLessons[$i]['id'] == $id) {
                $lessonPosition = $i + 1;
                $previousLesson = $i > 0 ? $allLessons[$i - 1] : null;
                $nextLesson = $i < count($allLessons) - 1 ? $allLessons[$i + 1] : null;
                break;
            }
        }

        // 6. Kiểm tra trạng thái hoàn thành bài học
        $isCompleted = $this->lessonModel->isLessonCompleted($currentUser['id'], $id);
        
        // 7. Tính tiến độ khóa học
        $completedCount = 0;
        foreach ($allLessons as $l) {
            if ($this->lessonModel->isLessonCompleted($currentUser['id'], $l['id'])) {
                $completedCount++;
            }
        }
        $progressPercent = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;
        
        // 8. Lấy tài liệu đính kèm (nếu có)
        require_once __DIR__ . '/../models/Material.php';
        $materialModel = new Material();
        $materials = $materialModel->getMaterialsByLessonId($id);

        $data = [
            'title' => $lesson['title'] . ' - ' . $course['title'],
            'lesson' => $lesson,
            'course' => $course,
            'allLessons' => $allLessons,
            'lessonPosition' => $lessonPosition,
            'totalLessons' => $totalLessons,
            'previousLesson' => $previousLesson,
            'nextLesson' => $nextLesson,
            'isCompleted' => $isCompleted,
            'materials' => $materials,
            'completedCount' => $completedCount,
            'progressPercent' => $progressPercent
        ];

        $this->render('lesson/main', $data, 'dashboard_layout');
    }

    /**
     * Đánh dấu bài học đã hoàn thành
     * Route: POST /lessons/{id}/complete
     */
    public function markComplete($id)
    {
        $this->requireAuth();
        $currentUser = $this->getCurrentUser();
        
        // Kiểm tra bài học tồn tại
        $lesson = $this->lessonModel->getLessonById($id);
        if (!$lesson) {
            $this->json(['success' => false, 'message' => 'Bài học không tồn tại']);
            return;
        }
        
        // Kiểm tra đã đăng ký khóa học
        require_once __DIR__ . '/../models/Enrollment.php';
        $enrollmentModel = new Enrollment($this->db);
        $isEnrolled = $enrollmentModel->isUserEnrolled($currentUser['id'], $lesson['course_id']);
        
        if (!$isEnrolled) {
            $this->json(['success' => false, 'message' => 'Bạn chưa đăng ký khóa học này']);
            return;
        }
        
        // Đánh dấu hoàn thành
        if ($this->lessonModel->markAsCompleted($currentUser['id'], $id)) {
            $this->json(['success' => true, 'message' => 'Đã đánh dấu hoàn thành bài học']);
        } else {
            $this->json(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
    }

    /**
     * Đánh dấu bài học đã bắt đầu
     * Route: POST /lessons/{id}/start
     */
    public function markStarted($id)
    {
        $this->requireAuth();
        $currentUser = $this->getCurrentUser();
        
        // Kiểm tra bài học tồn tại
        $lesson = $this->lessonModel->getLessonById($id);
        if (!$lesson) {
            $this->json(['success' => false, 'message' => 'Bài học không tồn tại']);
            return;
        }
        
        // Kiểm tra đã đăng ký khóa học
        require_once __DIR__ . '/../models/Enrollment.php';
        $enrollmentModel = new Enrollment($this->db);
        $isEnrolled = $enrollmentModel->isUserEnrolled($currentUser['id'], $lesson['course_id']);
        
        if (!$isEnrolled) {
            $this->json(['success' => false, 'message' => 'Bạn chưa đăng ký khóa học này']);
            return;
        }
        
        // Đánh dấu bắt đầu
        if ($this->lessonModel->markAsStarted($currentUser['id'], $id)) {
            $this->json(['success' => true, 'message' => 'Đã đánh dấu bắt đầu bài học']);
        } else {
            $this->json(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
    }

    public function delete($id)
    {
        $this->requireAuth();
        $this->requireRole('instructor');
        $currentUser = $this->getCurrentUser();

        // 1. Lấy thông tin bài học
        $lesson = $this->lessonModel->getLessonById($id);
        
        if (!$lesson) {
            $this->setFlash('Bài học không tồn tại.', 'error');
            $this->redirect('/instructor/courses');
            return;
        }

        // 2. Verify quyền sở hữu qua course_id
        $course = $this->courseModel->getCourseById($lesson['course_id'], $currentUser['id']);
        
        if (!$course) {
            $this->setFlash('Bạn không có quyền xóa bài học này.', 'error');
            $this->redirect('/instructor/courses');
            return;
        }

        // 3. Thực hiện xóa
        if ($this->lessonModel->delete($id)) {
            $this->setFlash('Xóa bài học thành công!', 'success');
        } else {
            $this->setFlash('Có lỗi xảy ra khi xóa bài học.', 'error');
        }

        // 4. Redirect về trang quản lý khóa học
        $this->redirect("/instructor/courses/" . $lesson['course_id'] . "/manage");
    }

}

?>