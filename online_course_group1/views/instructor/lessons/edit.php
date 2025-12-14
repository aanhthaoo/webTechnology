<div class="container-fluid px-4">
    <div class="mb-4">
        <a href = "<?php echo baseUrl('instructor/courses/' . $course['id'] . '/manage'); ?>" class="text-decoration-none text-muted mb-2 d-inline-block">
            <i class="fas fa-arrow-left me-1"></i> Quay lại quản lý nội dung
        </a>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark">Chỉnh sửa bài học</h2>
                <p class="text-muted">Khóa học: <strong><?php echo htmlspecialchars($course['title']); ?></strong></p>
            </div>
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                <i class="fas fa-pen me-1"></i> Đang chỉnh sửa
            </span>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="<?php echo baseUrl('lessons/' . $lesson['id'] . '/update'); ?>" method="POST" id="editLessonForm">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Tiêu đề bài học <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-lg" 
                                   placeholder="Ví dụ: Giới thiệu về React Hooks" 
                                   value="<?php echo htmlspecialchars($lesson['title']); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Link Video bài giảng</label>
                            <input type="url" name="video_url" class="form-control" value="<?php echo htmlspecialchars($lesson['video_url']); ?>">
                            <div class="form-text">Hỗ trợ link YouTube, Vimeo hoặc link file .mp4 trực tiếp.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Nội dung chi tiết</label>
                            <textarea name="content" class="form-control" rows="8" 
                                      placeholder="Nhập nội dung tóm tắt..."><?php echo htmlspecialchars($lesson['content']); ?></textarea>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo baseUrl('instructor/courses/' . $course['id'] . '/manage'); ?>" class="btn btn-light">Hủy bỏ</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control-lg { font-size: 1.1rem; }
    .form-control:focus { border-color: #4285f4; box-shadow: 0 0 0 0.2rem rgba(66, 133, 244, 0.15); }
</style>

<script>
// Form validation
document.getElementById('editLessonForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const description = document.getElementById('description').value.trim();
    const categoryId = document.getElementById('category_id').value;
    const price = document.getElementById('price').value;
    const duration = document.getElementById('duration_weeks').value;
    const level = document.getElementById('level').value;
    
    let errors = [];
    
    if (!title) {
        errors.push('Vui lòng nhập tên khóa học');
    }
    
    if (!description) {
        errors.push('Vui lòng nhập mô tả');
    }
    
    if (!categoryId) {
        errors.push('Vui lòng chọn danh mục');
    }
    
    if (price < 0) {
        errors.push('Giá tiền không được âm');
    }
    
    if (!duration || duration < 1) {
        errors.push('Thời lượng phải lớn hơn 0');
    }
    
    if (errors.length > 0) {
        e.preventDefault();
        alert('Lỗi:\n' + errors.join('\n'));
        return false;
    }

    if (!duration || duration < 1) {
        errors.push('Thời lượng phải lớn hơn 0');
    }
    
    if (!level) {
        errors.push('Vui lòng chọn độ khó');
    }
    
    return true;
});

// Auto-clear form data session after rendering
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editLessonForm'); // Đảm bảo ID này khớp với ID trong thẻ <form>
    
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            const title = document.querySelector('input[name="title"]').value.trim();
            let errors = [];
            
            if (!title) {
                errors.push('Vui lòng nhập tiêu đề bài học');
            }
            
            if (errors.length > 0) {
                e.preventDefault(); // Chặn submit nếu có lỗi
                alert('Lỗi:\n' + errors.join('\n'));
            }
        });
    }
});
</script>