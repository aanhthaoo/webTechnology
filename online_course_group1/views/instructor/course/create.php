<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Tạo khóa học mới</h2>
                <a href="<?php echo baseUrl('instructor/courses'); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>

            <!-- Flash messages -->
            <?php if (isset($_SESSION['form_errors'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Lỗi!</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach ($_SESSION['form_errors'] as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['form_errors']); ?>
            <?php endif; ?>

            <!-- Form Card -->
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="<?php echo baseUrl('instructor/courses'); ?>" method="POST" id="createCourseForm" enctype="multipart/form-data">

                        <!-- Tên khóa học -->
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                Tên khóa học <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="title"
                                name="title"
                                placeholder="Nhập tên khóa học"
                                value="<?php echo isset($_SESSION['form_data']['title']) ? htmlspecialchars($_SESSION['form_data']['title']) : ''; ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Ảnh bìa khóa học</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Hỗ trợ: JPG, PNG, JPEG, WEBP. (Để trống sẽ dùng ảnh mặc định)</small>
                        </div>

                        <!-- Mô tả -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                Mô tả <span class="text-danger">*</span>
                            </label>
                            <textarea
                                class="form-control"
                                id="description"
                                name="description"
                                rows="4"
                                placeholder="Nhập mô tả chi tiết về khóa học"
                                required><?php echo isset($_SESSION['form_data']['description']) ? htmlspecialchars($_SESSION['form_data']['description']) : ''; ?></textarea>
                            <small class="text-muted">Mô tả nội dung, mục tiêu và đối tượng học viên của khóa học</small>
                        </div>

                        <!-- Row: Danh mục và Giá -->
                        <div class="row">
                            <!-- Danh mục -->
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">
                                    Danh mục <span class="text-danger">*</span>
                                </label>
                                <select
                                    class="form-select"
                                    id="category_id"
                                    name="category_id"
                                    required>
                                    <option value="" selected>Chọn danh mục</option>
                                    <?php if (isset($categories) && !empty($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <option
                                                value="<?php echo $category['id']; ?>"
                                                <?php echo (isset($_SESSION['form_data']['category_id']) && $_SESSION['form_data']['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- Giá (VND) -->
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">
                                    Giá (VND) <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="number"
                                    class="form-control"
                                    id="price"
                                    name="price"
                                    min="0"
                                    step="1000"
                                    placeholder="0"
                                    value="<?php echo isset($_SESSION['form_data']['price']) ? htmlspecialchars($_SESSION['form_data']['price']) : '0'; ?>"
                                    required>
                                <small class="text-muted">Nhập 0 nếu khóa học miễn phí</small>
                            </div>
                        </div>

                        <!-- Thời lượng (Duration) - Trường mới thêm -->
                        <div class="mb-3">
                            <label for="duration_weeks" class="form-label">
                                Thời lượng (số tuần) <span class="text-danger">*</span>
                            </label>
                            <input
                                type="number"
                                class="form-control"
                                id="duration_weeks"
                                name="duration_weeks"
                                min="1"
                                max="52"
                                placeholder="Ví dụ: 8"
                                value="<?php echo isset($_SESSION['form_data']['duration_weeks']) ? htmlspecialchars($_SESSION['form_data']['duration_weeks']) : ''; ?>"
                                required>
                            <small class="text-muted">Thời gian dự kiến hoàn thành khóa học (tính theo tuần)</small>
                        </div>

                        <div class="mb-4">
                            <label for="level" class="form-label">
                                Độ khó <span class="text-danger">*</span>
                            </label>
                            <select
                                class="form-select"
                                id="level"
                                name="level"
                                required>
                                <option value="">Chọn độ khó</option>
                                <option value="Beginner" <?php echo (isset($_SESSION['form_data']['level']) && $_SESSION['form_data']['level'] == 'Beginner') ? 'selected' : ''; ?>>
                                    Cơ bản (Beginner)
                                </option>
                                <option value="Intermediate" <?php echo (isset($_SESSION['form_data']['level']) && $_SESSION['form_data']['level'] == 'Intermediate') ? 'selected' : ''; ?>>
                                    Trung cấp (Intermediate)
                                </option>
                                <option value="Advanced" <?php echo (isset($_SESSION['form_data']['level']) && $_SESSION['form_data']['level'] == 'Advanced') ? 'selected' : ''; ?>>
                                    Nâng cao (Advanced)
                                </option>
                            </select>
                            <small class="text-muted">Chọn mức độ phù hợp với đối tượng học viên</small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a
                                href="<?php echo baseUrl('/instructor/courses'); ?>"
                                class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Lưu khóa học
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Custom styles for create course form */
    .form-label {
        font-weight: 500;
        color: #333;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }

    .card {
        border: none;
        border-radius: 12px;
    }

    .btn {
        border-radius: 8px;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
    }
</style>

<script>
    // Form validation
    document.getElementById('createCourseForm').addEventListener('submit', function(e) {
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
    <?php if (isset($_SESSION['form_data'])): ?>
        <?php unset($_SESSION['form_data']); ?>
    <?php endif; ?>
</script>