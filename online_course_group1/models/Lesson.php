<?php
require_once __DIR__ . '/../config/Database.php';

class Lesson {
    private $db;

    public function __construct($db = null) {
        if ($db) {
            $this->db = $db;
        } else {
            $database = new Database();
            $this->db = $database->getConnection();
        }
    }

    // Lấy danh sách bài học theo course_id, sắp xếp theo thứ tự (order)
    public function getLessonsByCourseId($courseId) {
        try {
            $query = "SELECT * FROM lessons 
                      WHERE course_id = :course_id 
                      ORDER BY `order` ASC";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("getLessonsByCourseId - CourseId: $courseId, Count: " . count($result));
            return $result;
        } catch (Exception $e) {
            error_log("Error in getLessonsByCourseId: " . $e->getMessage());
            return [];
        }
    }
    
    // Đếm số lượng tài liệu (để hiển thị con số trên Tab)
    public function countMaterials($courseId) {
        try {
            $query = "SELECT COUNT(*) as total FROM materials m 
                      JOIN lessons l ON m.lesson_id = l.id 
                      WHERE l.course_id = :course_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':course_id', $courseId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Lấy số thứ tự tiếp theo cho bài học trong khóa
     */
    public function getNextOrder($courseId) {
        $query = "SELECT MAX(`order`) as max_order FROM lessons WHERE course_id = :course_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result['max_order'] ?? 0) + 1;
    }

    /**
     * Tạo bài học mới
     */
    public function create($data) {
        try {
            $query = "INSERT INTO lessons (course_id, title, content, video_url, `order`) 
                      VALUES (:course_id, :title, :content, :video_url, :order)";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':course_id', $data['course_id']);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':content', $data['content']);
            $stmt->bindParam(':video_url', $data['video_url']);
            $stmt->bindParam(':order', $data['order']);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error creating lesson: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy thông tin chi tiết một bài học
     */
    public function getLessonById($id) {
        try {
            $query = "SELECT * FROM lessons WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Cập nhật bài học
     */
    public function update($id, $data) {
        try {
            $query = "UPDATE lessons 
                      SET title = :title, 
                          content = :content, 
                          video_url = :video_url
                      WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':content', $data['content']);
            $stmt->bindParam(':video_url', $data['video_url']);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error updating lesson: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $query = "DELETE FROM lessons WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error deleting lesson: " . $e->getMessage());
            return false;
        }
    }

    // Get lessons for a course with progress information  
    public function getCourseLessonsWithProgress($courseId, $userId) {
        // Use the simple approach that works
        $lessons = $this->getLessonsByCourseId($courseId);
        
        // Add progress information
        foreach ($lessons as &$lesson) {
            $lesson['is_completed'] = $this->isLessonCompleted($userId, $lesson['id']) ? 1 : 0;
            $lesson['completed_at'] = null;
            $lesson['type'] = 'video';
            $lesson['duration'] = '30 phút';
            $lesson['description'] = $lesson['content'] ?? '';
        }
        
        return $lessons;
    }



    /**
     * Kiểm tra xem bài học đã được hoàn thành chưa
     */
    public function isLessonCompleted($userId, $lessonId) {
        try {
            // Kiểm tra bảng lesson_progress nếu có, hoặc trả về false tạm thời
            $query = "SELECT COUNT(*) as completed FROM lesson_progress 
                      WHERE user_id = :user_id AND lesson_id = :lesson_id AND status = 'completed'";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':lesson_id', $lessonId, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['completed'] > 0;
        } catch (Exception $e) {
            // Nếu bảng chưa tồn tại, trả về false
            return false;
        }
    }

    /**
     * Đánh dấu bài học đã hoàn thành
     */
    public function markAsCompleted($userId, $lessonId) {
        try {
            // Kiểm tra đã hoàn thành chưa
            if ($this->isLessonCompleted($userId, $lessonId)) {
                return true; // Đã hoàn thành rồi
            }
            
            // Thêm vào bảng lesson_progress
            $query = "INSERT INTO lesson_progress (user_id, lesson_id, status, completed_at) 
                      VALUES (:user_id, :lesson_id, 'completed', NOW())
                      ON DUPLICATE KEY UPDATE status = 'completed', completed_at = NOW()";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':lesson_id', $lessonId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (Exception $e) {
            // Nếu bảng chưa tồn tại, chỉ trả về true (tạm thời)
            error_log("Lesson progress tracking not available: " . $e->getMessage());
            return true;
        }
    }

    /**
     * Lấy tiến độ học tập của user cho một khóa học
     */
    public function getCourseProgress($userId, $courseId) {
        try {
            $query = "SELECT l.id as lesson_id, l.title, 
                            CASE WHEN lp.status = 'completed' THEN 1 ELSE 0 END as is_completed,
                            lp.completed_at
                     FROM lessons l 
                     LEFT JOIN lesson_progress lp ON l.id = lp.lesson_id AND lp.user_id = :user_id
                     WHERE l.course_id = :course_id 
                     ORDER BY l.`order` ASC";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting course progress: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Tính phần trăm hoàn thành khóa học
     */
    public function calculateCourseCompletionPercent($userId, $courseId) {
        try {
            $progress = $this->getCourseProgress($userId, $courseId);
            if (empty($progress)) {
                return 0;
            }
            
            $totalLessons = count($progress);
            $completedLessons = array_sum(array_column($progress, 'is_completed'));
            
            return round(($completedLessons / $totalLessons) * 100);
        } catch (Exception $e) {
            return 0;
        }
    }
    /**
     * Lấy thông tin bài học kèm thông tin khóa học và kiểm tra quyền truy cập
     */
    public function getLessonWithCourseInfo($lessonId, $userId = null) {
        try {
            $query = "SELECT l.*, c.title as course_title, c.instructor_id,
                             CASE WHEN e.id IS NOT NULL THEN 1 ELSE 0 END as has_access,
                             CASE WHEN lp.status = 'completed' THEN 1 ELSE 0 END as is_completed,
                             lp.completed_at
                      FROM lessons l 
                      INNER JOIN courses c ON l.course_id = c.id
                      LEFT JOIN enrollments e ON c.id = e.course_id AND e.student_id = :user_id AND e.status = 'active'
                      LEFT JOIN lesson_progress lp ON l.id = lp.lesson_id AND lp.user_id = :user_id
                      WHERE l.id = :lesson_id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':lesson_id', $lessonId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting lesson with course info: " . $e->getMessage());
            return null;
        }
    }



    /**
     * Đánh dấu bài học là đã bắt đầu học
     */
    public function markAsStarted($userId, $lessonId) {
        try {
            $query = "INSERT IGNORE INTO lesson_progress (user_id, lesson_id, status) 
                      VALUES (:user_id, :lesson_id, 'started')";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':lesson_id', $lessonId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error marking lesson as started: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy lesson tiếp theo trong khóa học
     */
    public function getNextLesson($currentLessonId) {
        try {
            $currentLesson = $this->getLessonById($currentLessonId);
            if (!$currentLesson) return null;
            
            $query = "SELECT * FROM lessons 
                      WHERE course_id = :course_id AND `order` > :current_order 
                      ORDER BY `order` ASC LIMIT 1";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':course_id', $currentLesson['course_id'], PDO::PARAM_INT);
            $stmt->bindParam(':current_order', $currentLesson['order'], PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Lấy lesson trước đó trong khóa học
     */
    public function getPreviousLesson($currentLessonId) {
        try {
            $currentLesson = $this->getLessonById($currentLessonId);
            if (!$currentLesson) return null;
            
            $query = "SELECT * FROM lessons 
                      WHERE course_id = :course_id AND `order` < :current_order 
                      ORDER BY `order` DESC LIMIT 1";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':course_id', $currentLesson['course_id'], PDO::PARAM_INT);
            $stmt->bindParam(':current_order', $currentLesson['order'], PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Đánh dấu bài học đã hoàn thành (sử dụng bởi StudentController)
     */
    public function markLessonComplete($userId, $lessonId) {
        try {
            // Check if already completed
            $query = "SELECT id FROM lesson_progress WHERE user_id = :user_id AND lesson_id = :lesson_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':lesson_id', $lessonId, PDO::PARAM_INT);
            $stmt->execute();
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existing) {
                // Update existing record
                $query = "UPDATE lesson_progress SET status = 'completed', completed_at = NOW() WHERE user_id = :user_id AND lesson_id = :lesson_id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':lesson_id', $lessonId, PDO::PARAM_INT);
                return $stmt->execute();
            } else {
                // Insert new record
                $query = "INSERT INTO lesson_progress (user_id, lesson_id, status, completed_at) VALUES (:user_id, :lesson_id, 'completed', NOW())";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':lesson_id', $lessonId, PDO::PARAM_INT);
                return $stmt->execute();
            }
        } catch (Exception $e) {
            error_log("Error marking lesson complete: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách tài liệu của một bài học
     */
    public function getMaterialsByLessonId($lessonId) {
        try {
            $query = "SELECT * FROM materials WHERE lesson_id = :lesson_id ORDER BY created_at ASC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':lesson_id', $lessonId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting materials by lesson id: " . $e->getMessage());
            return [];
        }
    }
}
?>