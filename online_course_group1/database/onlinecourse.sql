-- Tạo database
CREATE DATABASE IF NOT EXISTS onlinecourse;
USE onlinecourse;

-- Bảng users
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(255) NOT NULL,
    role INT NOT NULL DEFAULT 0 COMMENT '0: học viên, 1: giảng viên, 2: quản trị viên',
    
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bảng categories
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bảng courses
CREATE TABLE courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    instructor_id INT NOT NULL,
    category_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    duration_weeks INT NOT NULL DEFAULT 1,
    level VARCHAR(50) NOT NULL DEFAULT 'Beginner' COMMENT 'Beginner, Intermediate, Advanced',
    image VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Bảng enrollments
CREATE TABLE enrollments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    student_id INT NOT NULL,
    enrolled_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) NOT NULL DEFAULT 'active' COMMENT 'active, completed, dropped',
    progress INT NOT NULL DEFAULT 0 COMMENT 'phần trăm hoàn thành (0-100)',
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (course_id, student_id)
);

-- Bảng lessons
CREATE TABLE lessons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT,
    video_url VARCHAR(255),
    `order` INT NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- Bảng materials
CREATE TABLE materials (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lesson_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL COMMENT 'pdf, doc, ppt, etc.',
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
);

-- Thêm dữ liệu mẫu
-- Thêm categories mẫu
INSERT INTO categories (name, description) VALUES
('Lập trình', 'Các khóa học về lập trình và phát triển phần mềm'),
('Thiết kế', 'Các khóa học về thiết kế đồ họa và UI/UX'),
('Marketing', 'Các khóa học về marketing và kinh doanh'),
('Ngoại ngữ', 'Các khóa học ngoại ngữ'),
('Kỹ năng mềm', 'Các khóa học phát triển kỹ năng cá nhân');

-- Thêm users mẫu (password: 123456 - đã hash)
INSERT INTO users (username, email, password, fullname, role) VALUES
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Quản trị viên', 2),
('instructor1', 'instructor1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Giảng viên Nguyễn Văn A', 1),
('student1', 'student1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Học viên Trần Thị B', 0);

-- Thêm courses mẫu
INSERT INTO courses (title, description, instructor_id, category_id, price, duration_weeks, level, image) VALUES
('PHP và MySQL cơ bản', 'Khóa học lập trình web với PHP và MySQL dành cho người mới bắt đầu', 2, 1, 500000, 8, 'Beginner', 'php-mysql.jpg'),
('Thiết kế UI/UX chuyên nghiệp', 'Học thiết kế giao diện người dùng và trải nghiệm người dùng', 2, 2, 750000, 12, 'Intermediate', 'ui-ux.jpg');

-- Thêm lessons mẫu
INSERT INTO lessons (course_id, title, content, `order`) VALUES
(1, 'Giới thiệu về PHP', 'Nội dung bài học về PHP cơ bản', 1),
(1, 'Biến và kiểu dữ liệu trong PHP', 'Nội dung về biến và kiểu dữ liệu', 2),
(2, 'Nguyên tắc thiết kế UI', 'Các nguyên tắc cơ bản trong thiết kế giao diện', 1);


ALTER TABLE courses
ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT 'pending' COMMENT 'pending: chờ duyệt, approved: đã duyệt, rejected: từ chối' AFTER level;
UPDATE courses SET status = 'approved' WHERE id = 1;
UPDATE courses SET status = 'pending' WHERE id = 2;

ALTER TABLE users ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, disabled' AFTER role;

-- Tạo bảng lesson_progress để theo dõi tiến độ học tập
CREATE TABLE IF NOT EXISTS lesson_progress (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    lesson_id INT NOT NULL,
    status ENUM('started', 'completed') DEFAULT 'started',
    completed_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_lesson (user_id, lesson_id)
);

-- Chỉ mục để tối ưu hóa truy vấn
CREATE INDEX idx_lesson_progress_user ON lesson_progress(user_id);
CREATE INDEX idx_lesson_progress_lesson ON lesson_progress(lesson_id);
CREATE INDEX idx_lesson_progress_status ON lesson_progress(status);