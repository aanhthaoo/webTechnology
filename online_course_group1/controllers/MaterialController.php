<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/Material.php';

class MaterialController extends BaseController
{
    private $courseModel;
    private $lessonModel;
    private $materialModel;

    public function __construct()
    {
        parent::__construct();
        $this->courseModel = new Course();
        $this->lessonModel = new Lesson();
        $this->materialModel = new Material();
    }

    /**
     * Hiển thị danh sách tài liệu (Tab 2)
     */
    public function index($courseId)
    {
        $this->requireAuth();
        $this->requireRole('instructor');
        $currentUser = $this->getCurrentUser();

        // Check quyền sở hữu khóa học
        $course = $this->courseModel->getCourseById($courseId, $currentUser['id']);
        if (!$course) {
            $this->redirect('/instructor/courses');
            return;
        }

        // Lấy dữ liệu
        $materials = $this->materialModel->getMaterialsByCourseId($courseId);
        $lessons = $this->lessonModel->getLessonsByCourseId($courseId); // Để đếm số bài giảng
        $materialCount = count($materials);

        $data = [
            'page_title' => 'Quản lý tài liệu',
            'course' => $course,
            'materials' => $materials,
            'lessons_count' => count($lessons),
            'material_count' => $materialCount
        ];

        $this->render('instructor/materials/upload', $data, 'dashboard_layout');
    }

    /**
     * Xử lý Upload file
     */
    public function store($courseId)
    {
        $this->requireAuth();

        // 1. Kiểm tra có file không
        if (!isset($_FILES['file_upload']) || $_FILES['file_upload']['error'] !== UPLOAD_ERR_OK) {
            $this->setFlash('Vui lòng chọn file hợp lệ', 'error');
            $this->redirect("/courses/$courseId/materials");
            return;
        }

        $lessonId = $_POST['lesson_id'] ?? null;
        if (!$lessonId) {
            $this->setFlash('Vui lòng chọn bài học cho tài liệu', 'error');
            $this->redirect("/courses/$courseId/materials");
            return;
        }

        // 2. Cấu hình upload
        $file = $_FILES['file_upload'];
        $fileName = $file['name'];
        $fileTmp = $file['tmp_name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Cho phép: pdf, doc, docx, ppt, pptx, zip, rar
        $allowed = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'zip', 'rar', 'xls', 'xlsx'];

        if (!in_array($fileExt, $allowed)) {
            $this->setFlash('Định dạng file không được hỗ trợ', 'error');
            $this->redirect("/courses/$courseId/materials");
            return;
        }

        // 3. Tạo tên file unique để tránh trùng
        $newFileName = uniqid() . '_' . $fileName;
        $uploadDir = __DIR__ . '/../../assets/uploads/materials/';

        // Tạo folder nếu chưa có
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        // 4. Di chuyển file
        if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
            // Lưu vào DB
            $data = [
                'lesson_id' => $lessonId,
                'filename' => $fileName, // Tên gốc để hiển thị
                'file_path' => $newFileName, // Tên file vật lý
                'file_type' => $fileExt
            ];

            $this->materialModel->create($data);
            $this->setFlash('Đăng tải tài liệu thành công!', 'success');
        } else {
            $this->setFlash('Lỗi khi lưu file vào server', 'error');
        }

        $this->redirect("/courses/$courseId/materials");
    }

    /**
     * Xóa tài liệu
     */
    public function delete($id)
    {
        $this->requireAuth();
        $this->requireRole('instructor');
        $currentUser = $this->getCurrentUser();

        // 1. Lấy thông tin material kèm course_id
        $material = $this->materialModel->getMaterialById($id);

        if (!$material) {
            $this->setFlash('Tài liệu không tồn tại.', 'error');
            $this->redirect('/instructor/courses');
            return;
        }

        // 2. Verify quyền sở hữu qua course_id
        $course = $this->courseModel->getCourseById($material['course_id'], $currentUser['id']);

        if (!$course) {
            $this->setFlash('Bạn không có quyền xóa tài liệu này.', 'error');
            $this->redirect('/instructor/courses');
            return;
        }

        // 3. Xóa file vật lý
        $filePath = __DIR__ . '/../../assets/uploads/materials/' . $material['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // 4. Xóa trong DB
        if ($this->materialModel->delete($id)) {
            $this->setFlash('Xóa tài liệu thành công!', 'success');
        } else {
            $this->setFlash('Có lỗi xảy ra khi xóa tài liệu.', 'error');
        }

        // 5. Redirect về trang materials của course
        $this->redirect("/courses/" . $material['course_id'] . "/materials");
    }

    /**
     * Tải xuống hoặc Xem tài liệu
     */
    public function download($id)
    {
        $this->requireAuth();
        $material = $this->materialModel->getMaterialById($id);

        if (!$material) {
            die("File không tồn tại trong hệ thống.");
        }

        $filePath = __DIR__ . '/../../assets/uploads/materials/' . $material['file_path'];

        if (file_exists($filePath)) {
            $mimeType = mime_content_type($filePath);

            // Thiết lập Header
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $mimeType);


            $ext = strtolower($material['file_type']);
            if (in_array($ext, ['pdf', 'jpg', 'jpeg', 'png', 'gif'])) {
                header('Content-Disposition: inline; filename="' . $material['filename'] . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $material['filename'] . '"');
            }

            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));

            readfile($filePath);
            exit;
        } else {
            die("Lỗi: File gốc không còn tồn tại trên server.");
        }
    }
}
