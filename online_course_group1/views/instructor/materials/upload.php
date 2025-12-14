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
                    <a class="nav-link text-muted border-0" href="<?php echo baseUrl('instructor/courses/' . $course['id'] . '/manage'); ?>">
                        Bài giảng (<?php echo $lessons_count; ?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-bold border-bottom-0" href="#">
                        Tài liệu (<?php echo $material_count; ?>)
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="card-body p-4 bg-light">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0 text-dark">Tài liệu tham khảo</h5>
                
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-file-upload me-2"></i>Đăng tải tài liệu
                </button>
            </div>

            <div class="row">
                <?php if (empty($materials)): ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Chưa có tài liệu nào.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($materials as $file): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-3 d-flex align-items-center">
                                    
                                    <div class="file-icon me-3">
                                        <?php 
                                            $ext = $file['file_type'];
                                            if (in_array($ext, ['pdf'])) echo '<i class="fas fa-file-pdf text-danger fa-2x"></i>';
                                            elseif (in_array($ext, ['doc', 'docx'])) echo '<i class="fas fa-file-word text-primary fa-2x"></i>';
                                            elseif (in_array($ext, ['ppt', 'pptx'])) echo '<i class="fas fa-file-powerpoint text-warning fa-2x"></i>';
                                            elseif (in_array($ext, ['zip', 'rar'])) echo '<i class="fas fa-file-archive text-secondary fa-2x"></i>';
                                            else echo '<i class="fas fa-file text-muted fa-2x"></i>';
                                        ?>
                                    </div>
                                    
                                    <div class="flex-grow-1 overflow-hidden">
                                        <a href="<?php echo baseUrl('materials/' . $file['id'] . '/download'); ?>" 
                                            class="text-decoration-none text-dark fw-bold mb-0 text-truncate d-block"
                                            target="_blank"
                                            title="<?php echo htmlspecialchars($file['filename']); ?>">
                                                <?php echo htmlspecialchars($file['filename']); ?>
                                        </a>
                                        <small class="text-muted">
                                            Thuộc bài: <?php echo htmlspecialchars($file['lesson_title'] ?? 'Chung'); ?>
                                        </small>
                                    </div>

                                    <button type="button"
                                            class="btn btn-link text-muted p-0 ms-2" 
                                            title="Xóa"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteMaterialModal"
                                            data-material-id="<?php echo $file['id']; ?>"
                                            data-material-name="<?php echo htmlspecialchars($file['filename']); ?>">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Đăng tải tài liệu mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo baseUrl('courses/' . $course['id'] . '/materials'); ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    
                    <div class="mb-3">
                        <label class="form-label">Tài liệu này thuộc bài học nào?</label>
                        <select name="lesson_id" class="form-select" required>
                            <option value="">-- Chọn bài học --</option>
                            <?php 
                                // Cần lấy danh sách bài học để đổ vào đây
                                // Logic này đã có trong Controller (biến $lessons)
                                require_once __DIR__ . '/../../../models/Lesson.php'; 
                                $lessonModel = new Lesson();
                                $lessons = $lessonModel->getLessonsByCourseId($course['id']);
                            ?>
                            <?php foreach ($lessons as $l): ?>
                                <option value="<?php echo $l['id']; ?>"><?php echo htmlspecialchars($l['title']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Chọn file (PDF, Doc, Slide, Zip)</label>
                        <input type="file" name="file_upload" class="form-control" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Đăng tải</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteMaterialModal" tabindex="-1" aria-labelledby="deleteMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="deleteMaterialModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Xác nhận xóa tài liệu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Bạn có chắc chắn muốn xóa tài liệu:</p>
                <p class="fw-bold text-danger mb-3" id="materialNameToDelete"></p>
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>
                        <strong>Lưu ý:</strong> File sẽ bị xóa vĩnh viễn khỏi hệ thống. 
                        <strong>Không thể hoàn tác!</strong>
                    </small>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Hủy
                </button>
                <form id="deleteMaterialForm" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-2"></i>Xóa tài liệu
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* CSS Custom giống ảnh */
.file-icon {
    width: 50px;
    height: 50px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.nav-tabs .nav-link { color: #6c757d; }
.nav-tabs .nav-link.active { color: #0d6efd; border-top: 3px solid #0d6efd; }

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

</style>

<script>
// Handle delete material modal
const deleteMaterialModal = document.getElementById('deleteMaterialModal');

if (deleteMaterialModal) {
    deleteMaterialModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const materialId = button.getAttribute('data-material-id');
        const materialName = button.getAttribute('data-material-name');
        
        const materialNameElement = deleteMaterialModal.querySelector('#materialNameToDelete');
        materialNameElement.textContent = materialName;
        
        const deleteForm = deleteMaterialModal.querySelector('#deleteMaterialForm');
        deleteForm.action = '<?php echo baseUrl('materials/'); ?>' + materialId + '/delete';
    });
}
</script>