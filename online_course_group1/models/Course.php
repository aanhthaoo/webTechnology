<?php
require_once __DIR__ . '/../config/Database.php';

class Course
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Lấy tổng số khóa học của giảng viên
     * @param int $instructorId ID của giảng viên
     * @return int Tổng số khóa học
     */
    public function getTotalCoursesByInstructor($instructorId)
    {
        try {
            $query = "SELECT COUNT(*) as total FROM courses WHERE instructor_id = :instructor_id AND status = 'approved'";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':instructor_id', $instructorId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Error in getTotalCoursesByInstructor: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Lấy tổng số học viên đã đăng ký khóa học của giảng viên
     * @param int $instructorId ID của giảng viên
     * @return int Tổng số học viên
     */
    public function getTotalStudentsByInstructor($instructorId)
    {
        try {
            $query = "SELECT COUNT(DISTINCT e.student_id) as total 
                     FROM enrollments e 
                     INNER JOIN courses c ON e.course_id = c.id 
                     WHERE c.instructor_id = :instructor_id 
                     AND e.status = 'active'";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':instructor_id', $instructorId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Error in getTotalStudentsByInstructor: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Lấy danh sách học viên của giảng viên
     * @param int $instructorId ID của giảng viên
     * @return array Danh sách học viên
     */
    /**
     * Lấy danh sách học viên (Có hỗ trợ Lọc và Tìm kiếm)
     * @param int $instructorId ID giảng viên
     * @param int|null $courseId ID khóa học (để lọc)
     * @param string|null $search Từ khóa tìm kiếm
     */
    public function getStudentsByInstructor($instructorId, $courseId = null, $search = null)
    {
        try {
            // Base query
            $query = "SELECT 
                        u.fullname as student_name,
                        u.email as student_email,
                        c.title as course_title,
                        e.enrolled_date,
                        e.progress,
                        e.status
                     FROM enrollments e
                     INNER JOIN users u ON e.student_id = u.id
                     INNER JOIN courses c ON e.course_id = c.id
                     WHERE c.instructor_id = :instructor_id
                     AND e.status = 'active'";

            // Thêm điều kiện lọc theo khóa học
            if ($courseId) {
                $query .= " AND c.id = :course_id";
            }

            // Thêm điều kiện tìm kiếm (Tên hoặc Email)
            if ($search) {
                $query .= " AND (u.fullname LIKE :search OR u.email LIKE :search)";
            }

            $query .= " ORDER BY e.enrolled_date DESC";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':instructor_id', $instructorId, PDO::PARAM_INT);

            if ($courseId) {
                $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
            }

            if ($search) {
                $searchTerm = "%$search%";
                $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
            }

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getStudentsByInstructor: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy thống kê dashboard cho giảng viên
     * @param int $instructorId ID của giảng viên
     * @return array Mảng chứa các thống kê
     */
    public function getDashboardStats($instructorId)
    {
        return [
            'total_courses' => $this->getTotalCoursesByInstructor($instructorId),
            'total_students' => $this->getTotalStudentsByInstructor($instructorId),
            'students_list' => $this->getStudentsByInstructor($instructorId)
        ];
    }

    /**
     * Lấy tất cả khóa học của instructor
     * @param int $instructorId ID của giảng viên
     * @return array Danh sách khóa học
     */
    public function getCoursesByInstructor($instructorId)
    {
        try {
            $query = "SELECT 
                        c.id,
                        c.title,
                        c.image,
                        c.description,
                        c.status,
                        c.price,
                        c.level,
                        c.created_at,
                        COUNT(DISTINCT e.student_id) as student_count,
                        cat.name as category_name
                    FROM courses c
                    LEFT JOIN enrollments e ON c.id = e.course_id AND e.status = 'approved'
                    LEFT JOIN categories cat ON c.category_id = cat.id
                    WHERE c.instructor_id = :instructor_id
                    GROUP BY c.id, c.title, c.description, c.price, c.level, c.created_at, cat.name
                    ORDER BY c.created_at DESC";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':instructor_id', $instructorId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getCoursesByInstructor: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy thông tin một khóa học cụ thể
     * @param int $courseId ID khóa học
     * @param int $instructorId ID giảng viên (để verify ownership)
     * @return array|null Thông tin khóa học
     */
    public function getCourseById($courseId, $instructorId = null)
    {
        try {
            $query = "SELECT c.*, cat.name as category_name
                    FROM courses c
                    LEFT JOIN categories cat ON c.category_id = cat.id
                    WHERE c.id = :course_id";

            if ($instructorId !== null) {
                $query .= " AND c.instructor_id = :instructor_id";
            }

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);

            if ($instructorId !== null) {
                $stmt->bindParam(':instructor_id', $instructorId, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getCourseById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy thông tin chi tiết khóa học cho trang detail (bao gồm instructor_name)
     * @param int $courseId ID khóa học
     * @return array|null Thông tin khóa học đầy đủ
     */
    public function getCourseDetails($courseId)
    {
        try {
            $query = "SELECT c.*, 
                            u.fullname as instructor_name,
                            cat.name as category_name
                    FROM courses c
                    LEFT JOIN users u ON c.instructor_id = u.id
                    LEFT JOIN categories cat ON c.category_id = cat.id
                    WHERE c.id = :course_id AND c.status = 'approved'";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getCourseDetails: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Tạo khóa học mới
     * @param array $data Dữ liệu khóa học [title, description, category_id, price, duration_weeks, instructor_id]
     * @return int|false ID của khóa học mới tạo hoặc false nếu thất bại
     */
    public function createCourse($data)
    {
        try {
            $query = "INSERT INTO courses 
                  (title, description, instructor_id, category_id, price, duration_weeks, level, status, image) 
                  VALUES 
                  (:title, :description, :instructor_id, :category_id, :price, :duration_weeks, :level, :status, :image)";

            $stmt = $this->db->prepare($query);

            // Bind parameters
            $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':instructor_id', $data['instructor_id'], PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
            $stmt->bindParam(':price', $data['price'], PDO::PARAM_STR);

            // Duration weeks - mặc định 4 tuần nếu không có
            $duration = $data['duration_weeks'] ?? 4;
            $stmt->bindParam(':duration_weeks', $duration, PDO::PARAM_INT);

            // Level - mặc định Beginner
            $level = $data['level'] ?? 'Beginner';
            $stmt->bindParam(':level', $level, PDO::PARAM_STR);

            // Status - mặc định pending
            $status = $data['status'] ?? 'pending';
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);

            $image = $data['image'] ?? null;
            $stmt->bindParam(':image', $image, PDO::PARAM_STR);

            $stmt->execute();

            // Trả về ID của khóa học vừa tạo
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            error_log("Error creating course: " . $e->getMessage());
            return false;
        }
    }

    /**
 * Cập nhật thông tin khóa học
 * @param int $courseId ID của khóa học cần update
 * @param array $data Dữ liệu mới [title, description, category_id, price, duration_weeks, level]
 * @param int $instructorId ID giảng viên (để verify ownership)
 * @return bool True nếu update thành công, false nếu thất bại
 */
    public function updateCourse($courseId, $data, $instructorId)
    {
        try {
            // Kiểm tra quyền sở hữu khóa học
            $course = $this->getCourseById($courseId, $instructorId);
            if (!$course) {
                error_log("Course not found or unauthorized: courseId=$courseId, instructorId=$instructorId");
                return false;
            }
            
            $sql = "UPDATE courses SET 
                    title = :title,
                    description = :description,
                    category_id = :category_id,
                    price = :price,
                    duration_weeks = :duration_weeks,
                    level = :level,
                    updated_at = CURRENT_TIMESTAMP";
            
            // Nếu có ảnh mới thì mới thêm vào câu SQL update
            if (!empty($data['image'])) {
                $sql .= ", image = :image";
            }
            
            $sql .= " WHERE id = :id AND instructor_id = :instructor_id";
            
            $stmt = $this->db->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
            $stmt->bindParam(':price', $data['price'], PDO::PARAM_STR);
            $stmt->bindParam(':duration_weeks', $data['duration_weeks'], PDO::PARAM_INT);
            $stmt->bindParam(':level', $data['level'], PDO::PARAM_STR);
            if (!empty($data['image'])) {
                $stmt->bindParam(':image', $data['image'], PDO::PARAM_STR);
            }
            $stmt->bindParam(':id', $courseId, PDO::PARAM_INT);
            $stmt->bindParam(':instructor_id', $instructorId, PDO::PARAM_INT);
            
            $result = $stmt->execute();
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Error updating course: " . $e->getMessage());
            return false;
        }
    }

    public function deleteCourse($courseId, $instructorId)
    {
        try {
            $course = $this->getCourseById($courseId, $instructorId);
            if (!$course) {
                error_log("Course not found or unauthorized: courseId=$courseId, instructorId=$instructorId");
                return false;
            }
            
            $query = "DELETE FROM courses WHERE id = :id AND instructor_id = :instructor_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $courseId, PDO::PARAM_INT);
            $stmt->bindParam(':instructor_id', $instructorId, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (Exception $e) {
            error_log("Error deleting course: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách khóa học được đề xuất cho học viên
     * @param int $userId ID của học viên
     * @param int $limit Số lượng khóa học cần lấy (mặc định 4)
     * @return array Danh sách khóa học được đề xuất
     */
    public function getRecommendedCourses($userId, $limit = 4)
    {
        try {
            // Lấy các khóa học mà học viên chưa đăng ký
            // Ưu tiên theo: popularity, rating, và mức độ phù hợp
            $query = "SELECT 
                        c.id,
                        c.title,
                        c.description,
                        c.price,
                        c.level,
                        c.image,
                        c.created_at,
                        u.fullname as instructor_name,
                        cat.name as category_name
                    FROM courses c
                    LEFT JOIN users u ON c.instructor_id = u.id
                    LEFT JOIN categories cat ON c.category_id = cat.id
                    WHERE c.status = 'approved'
                    AND c.id NOT IN (
                        SELECT DISTINCT course_id 
                        FROM enrollments 
                        WHERE student_id = :user_id AND status = 'active'
                    )
                    ORDER BY c.created_at DESC
                    LIMIT :limit";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getRecommendedCourses: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Tìm kiếm khóa học với các bộ lọc
     * @param string|null $search Từ khóa tìm kiếm
     * @param int|null $categoryId ID danh mục
     * @param string $sort Sắp xếp (newest, oldest, price_asc, price_desc, popular)
     * @param int $limit Số lượng kết quả trên 1 trang
     * @param int $offset Vị trí bắt đầu
     * @return array Danh sách khóa học
     */
    public function searchCourses($search = null, $categoryId = null, $sort = 'newest', $limit = 12, $offset = 0)
    {
        try {
            $query = "SELECT 
                        c.id,
                        c.title,
                        c.description,
                        c.price,
                        c.level,
                        c.image,
                        c.created_at,
                        c.duration_weeks,
                        u.fullname as instructor_name,
                        cat.name as category_name
                    FROM courses c
                    LEFT JOIN users u ON c.instructor_id = u.id
                    LEFT JOIN categories cat ON c.category_id = cat.id
                    WHERE c.status = 'approved'";

            // Thêm điều kiện tìm kiếm
            if ($search) {
                $query .= " AND (c.title LIKE :search OR c.description LIKE :search OR u.fullname LIKE :search)";
            }

            // Thêm điều kiện lọc theo danh mục
            if ($categoryId) {
                $query .= " AND c.category_id = :category_id";
            }

            // Thêm sắp xếp
            switch ($sort) {
                case 'oldest':
                    $query .= " ORDER BY c.created_at ASC";
                    break;
                case 'price_asc':
                    $query .= " ORDER BY c.price ASC";
                    break;
                case 'price_desc':
                    $query .= " ORDER BY c.price DESC";
                    break;
                case 'popular':
                    $query .= " ORDER BY student_count DESC, avg_rating DESC";
                    break;
                case 'newest':
                default:
                    $query .= " ORDER BY c.created_at DESC";
                    break;
            }

            $query .= " LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($query);

            // Bind parameters
            if ($search) {
                $searchTerm = "%$search%";
                $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
            }

            if ($categoryId) {
                $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
            }

            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in searchCourses: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Đếm số lượng kết quả tìm kiếm
     * @param string|null $search Từ khóa tìm kiếm
     * @param int|null $categoryId ID danh mục
     * @return int Tổng số khóa học
     */
    public function countSearchResults($search = null, $categoryId = null)
    {
        try {
            $query = "SELECT COUNT(DISTINCT c.id) as total
                    FROM courses c
                    LEFT JOIN users u ON c.instructor_id = u.id
                    WHERE c.status = 'approved'";

            // Thêm điều kiện tìm kiếm
            if ($search) {
                $query .= " AND (c.title LIKE :search OR c.description LIKE :search OR u.fullname LIKE :search)";
            }

            // Thêm điều kiện lọc theo danh mục
            if ($categoryId) {
                $query .= " AND c.category_id = :category_id";
            }

            $stmt = $this->db->prepare($query);

            // Bind parameters
            if ($search) {
                $searchTerm = "%$search%";
                $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
            }

            if ($categoryId) {
                $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
            }

            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Error in countSearchResults: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Lấy danh sách bài học của khóa học
     * @param int $courseId ID khóa học
     * @return array Danh sách bài học
     */
    public function getCourseLessons($courseId)
    {
        try {
            $query = "SELECT 
                        id,
                        title,
                        content,
                        video_url,
                        `order`,
                        created_at
                    FROM lessons 
                    WHERE course_id = :course_id 
                    ORDER BY `order` ASC";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getCourseLessons: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy các khóa học liên quan
     * @param int $categoryId ID danh mục
     * @param int $excludeCourseId ID khóa học cần loại trừ
     * @param int $limit Số lượng khóa học cần lấy
     * @return array Danh sách khóa học liên quan
     */
    public function getRelatedCourses($categoryId, $excludeCourseId, $limit = 4)
    {
        try {
            $query = "SELECT 
                        c.id,
                        c.title,
                        c.description,
                        c.price,
                        c.level,
                        c.image,
                        u.fullname as instructor_name
                    FROM courses c
                    LEFT JOIN users u ON c.instructor_id = u.id
                    WHERE c.category_id = :category_id 
                    AND c.id != :exclude_course_id 
                    AND c.status = 'approved'
                    ORDER BY c.created_at DESC
                    LIMIT :limit";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
            $stmt->bindParam(':exclude_course_id', $excludeCourseId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getRelatedCourses: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy số lượng học viên đã đăng ký khóa học
     * @param int $courseId ID khóa học
     * @return int Số lượng học viên
     */
    public function getCourseEnrollmentCount($courseId)
    {
        try {
            $query = "SELECT COUNT(DISTINCT student_id) as total
                    FROM enrollments 
                    WHERE course_id = :course_id AND status = 'active'";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Error in getCourseEnrollmentCount: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Lấy khóa học theo danh mục
     * @param int $categoryId ID danh mục
     * @return array Danh sách khóa học
     */
    public function getCoursesByCategory($categoryId)
    {
        try {
            $query = "SELECT 
                        c.id,
                        c.title,
                        c.description,
                        c.price,
                        c.level,
                        c.image,
                        c.created_at,
                        c.duration_weeks,
                        u.fullname as instructor_name,
                        cat.name as category_name
                    FROM courses c
                    LEFT JOIN users u ON c.instructor_id = u.id
                    LEFT JOIN categories cat ON c.category_id = cat.id
                    WHERE c.category_id = :category_id 
                    AND c.status = 'approved'
                    ORDER BY c.created_at DESC";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getCoursesByCategory: " . $e->getMessage());
            return [];
        }
    }
}
