<?php
require_once __DIR__ . '/../config/Database.php';

class Material {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Lấy tất cả tài liệu của một khóa học (thông qua bảng lessons)
     */
    public function getMaterialsByCourseId($courseId) {
        try {
            $query = "SELECT m.*, l.title as lesson_title 
                      FROM materials m
                      JOIN lessons l ON m.lesson_id = l.id
                      WHERE l.course_id = :course_id
                      ORDER BY m.uploaded_at DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':course_id', $courseId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Lấy tất cả tài liệu của một bài học
     */
    public function getMaterialsByLessonId($lessonId) {
        try {
            $query = "SELECT * FROM materials WHERE lesson_id = :lesson_id ORDER BY uploaded_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':lesson_id', $lessonId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting materials by lesson id: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy thông tin 1 tài liệu
     */
    public function getMaterialById($id) {
        $query = "SELECT m.*, l.course_id 
                  FROM materials m
                  JOIN lessons l ON m.lesson_id = l.id
                  WHERE m.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lưu tài liệu mới vào DB
     */
    public function create($data) {
        try {
            $query = "INSERT INTO materials (lesson_id, filename, file_path, file_type) 
                      VALUES (:lesson_id, :filename, :file_path, :file_type)";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':lesson_id', $data['lesson_id']);
            $stmt->bindParam(':filename', $data['filename']);
            $stmt->bindParam(':file_path', $data['file_path']);
            $stmt->bindParam(':file_type', $data['file_type']);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error creating material: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa tài liệu khỏi DB
     */
    public function delete($id) {
        $query = "DELETE FROM materials WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>