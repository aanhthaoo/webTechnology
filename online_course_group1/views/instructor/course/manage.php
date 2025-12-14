<div class="container-fluid px-4">
    <div class="mb-3 mt-3">
        <a href="<?php echo baseUrl('instructor/courses'); ?>" class="text-decoration-none text-muted">
            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-4">
                    <?php if (!empty($course['image'])): ?>
                        <img src="<?php echo baseUrl('assets/uploads/courses/' . htmlspecialchars($course['image'])); ?>" 
                             alt="Course Thumbnail" 
                             class="rounded"
                             style="width: 160px; height: 100px; object-fit: cover; border: 1px solid #eee;">
                    <?php else: ?>
                        <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted"
                             style="width: 160px; height: 100px; border: 1px solid #eee;">
                            <i class="fas fa-image fa-3x"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <div>
                    <h2 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($course['title']); ?></h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-layer-group me-1"></i>
                        Quản lý nội dung học tập và tài liệu
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom-0 pt-3 px-4">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active fw-bold border-bottom-0" href="#">
                        Bài giảng (<?php echo count($lessons); ?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-muted border-0" href="<?php echo baseUrl('courses/' . $course['id'] . '/materials'); ?>">
                        Tài liệu (<?php echo $material_count; ?>)
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Danh sách bài học</h5>
                <a href="<?php echo baseUrl('courses/' . $course['id'] . '/lessons/create'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Thêm bài học
                </a>
            </div>

            <div class="lesson-list">
                <?php if (empty($lessons)): ?>
                    <div class="text-center py-5 border rounded bg-light">
                        <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa có bài học nào được tạo.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">Tạo bài học đầu tiên</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($lessons as $index => $lesson): ?>
                        <div class="lesson-item card mb-3 border hover-shadow transition">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <div class="lesson-number me-3">
                                            <?php echo $index + 1; ?>
                                        </div>
                                        
                                        <div>
                                            <h6 class="fw-bold mb-1 text-dark">
                                                <?php echo htmlspecialchars($lesson['title']); ?>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i>
                                                <?php echo date('H:i d/m/Y', strtotime($lesson['created_at'])); ?> 
                                                </small>
                                        </div>
                                    </div>

                                    <div class="lesson-actions">
                                        <a href="<?php echo baseUrl('lessons/' . $lesson['id'] . '/edit'); ?>" 
                                           class="btn btn-light btn-sm text-primary me-1" title="Chỉnh sửa">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-light btn-sm text-danger" 
                                            title="Xóa"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteLessonModal"
                                            data-lesson-id="<?php echo $lesson['id']; ?>"
                                            data-lesson-title="<?php echo htmlspecialchars($lesson['title']); ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteLessonModal" tabindex="-1" aria-labelledby="deleteLessonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="deleteLessonModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Xác nhận xóa bài học
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Bạn có chắc chắn muốn xóa bài học:</p>
                <p class="fw-bold text-danger mb-3" id="lessonNameToDelete"></p>
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>
                        <strong>Lưu ý:</strong> Hành động này sẽ xóa tất cả tài liệu liên quan đến bài học. 
                        <strong>Không thể hoàn tác!</strong>
                    </small>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Hủy
                </button>
                <form id="deleteLessonForm" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-2"></i>Xóa bài học
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<style>
/* Custom CSS cho trang Manage Course */

/* Style cho số thứ tự bài học */
.lesson-number {
    width: 40px;
    height: 40px;
    background-color: #e3f2fd;
    color: #0d6efd;
    font-weight: 700;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

/* Hiệu ứng hover cho card bài học */
.lesson-item {
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
}

.lesson-item:hover {
    border-color: #b6d4fe;
    background-color: #f8faff;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

/* Ẩn hiện nút hành động */
.lesson-actions {
    opacity: 0; /* Mặc định ẩn */
    visibility: hidden;
    transition: all 0.2s ease;
}

/* Khi hover vào dòng lesson-item thì hiện nút */
.lesson-item:hover .lesson-actions {
    opacity: 1;
    visibility: visible;
}

/* Tab style */
.nav-tabs .nav-link {
    color: #6c757d;
    padding: 1rem 1.5rem;
}
.nav-tabs .nav-link.active {
    color: #0d6efd;
    border-top: 3px solid #0d6efd; /* Highlight top border */
}

/* Mobile responsive */
@media (max-width: 768px) {
    .lesson-actions {
        opacity: 1;
        visibility: visible; /* Trên mobile hiện luôn cho dễ bấm */
    }
}

.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
}

.modal-header {
    padding: 1.5rem 1.5rem 0.5rem;
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
}

.modal-body {
    padding: 1rem 1.5rem;
}

.modal-footer {
    padding: 0.75rem 1.5rem 1.5rem;
}

.modal-footer .btn {
    border-radius: 8px;
    padding: 0.5rem 1.5rem;
    font-weight: 500;
}
</style><!-- Manage course -->

<script>
// Handle delete lesson modal
const deleteLessonModal = document.getElementById('deleteLessonModal');

if (deleteLessonModal) {
    deleteLessonModal.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        const button = event.relatedTarget;
        
        // Extract info from data-* attributes
        const lessonId = button.getAttribute('data-lesson-id');
        const lessonTitle = button.getAttribute('data-lesson-title');
        
        // Update modal content
        const lessonNameElement = deleteLessonModal.querySelector('#lessonNameToDelete');
        lessonNameElement.textContent = lessonTitle;
        
        // Update form action
        const deleteForm = deleteLessonModal.querySelector('#deleteLessonForm');
        deleteForm.action = '<?php echo baseUrl('lessons/'); ?>' + lessonId + '/delete';
    });
}
</script>