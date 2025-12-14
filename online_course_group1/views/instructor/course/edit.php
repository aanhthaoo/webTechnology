<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Chỉnh sửa khóa học</h2>
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
                    <form action="<?php echo baseUrl('instructor/courses/' . $course['id'] . '/update'); ?>" method="POST" id="editCourseForm" enctype="multipart/form-data">

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
                                value="<?php echo isset($_SESSION['form_data']['title']) ? htmlspecialchars($_SESSION['form_data']['title']) : htmlspecialchars($course['title']); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Ảnh bìa khóa học</label>

                            <?php if (!empty($course['image'])): ?>
                                <div class="mb-2">
                                    <img src="<?php echo baseUrl('assets/uploads/courses/' . htmlspecialchars($course['image'])); ?>"
                                        alt="Ảnh hiện tại"
                                        class="img-thumbnail d-block mb-2"
                                        style="max-width: 200px; height: auto;">

                                    <div class="text-muted small">
                                        <i class="fas fa-file-image me-1"></i>
                                        File hiện tại: <strong><?php echo htmlspecialchars($course['image']); ?></strong>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Chỉ chọn file nếu bạn muốn thay đổi ảnh bìa hiện tại</small>
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
                                required><?php echo isset($_SESSION['form_data']['description']) ? htmlspecialchars($_SESSION['form_data']['description']) : htmlspecialchars($course['description']); ?></textarea>
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
                                    <option value="">Chọn danh mục</option>
                                    <?php if (isset($categories) && !empty($categories)): ?>
                                        <?php
                                        $selectedCategoryId = isset($_SESSION['form_data']['category_id'])
                                            ? $_SESSION['form_data']['category_id']
                                            : $course['category_id'];
                                        ?>
                                        <?php foreach ($categories as $category): ?>
                                            <option
                                                value="<?php echo $category['id']; ?>"
                                                <?php echo ($selectedCategoryId == $category['id']) ? 'selected' : ''; ?>>
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
                                    value="<?php echo isset($_SESSION['form_data']['price']) ? htmlspecialchars($_SESSION['form_data']['price']) : htmlspecialchars($course['price']); ?>"
                                    required>
                                <small class="text-muted">Nhập 0 nếu khóa học miễn phí</small>
                            </div>
                        </div>

                        <!-- Thời lượng (Duration) -->
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
                                value="<?php echo isset($_SESSION['form_data']['duration_weeks']) ? htmlspecialchars($_SESSION['form_data']['duration_weeks']) : htmlspecialchars($course['duration_weeks']); ?>"
                                required>
                            <small class="text-muted">Thời gian dự kiến hoàn thành khóa học (tính theo tuần)</small>
                        </div>

                        <!-- Level (Độ khó) -->
                        <div class="mb-4">
                            <label for="level" class="form-label">
                                Độ khó <span class="text-danger">*</span>
                            </label>
                            <select
                                class="form-select"
                                id="level"
                                name="level"
                                required>
                                <?php
                                $selectedLevel = isset($_SESSION['form_data']['level'])
                                    ? $_SESSION['form_data']['level']
                                    : $course['level'];
                                ?>
                                <option value="">Chọn độ khó</option>
                                <option value="Beginner" <?php echo ($selectedLevel == 'Beginner') ? 'selected' : ''; ?>>
                                    Cơ bản (Beginner)
                                </option>
                                <option value="Intermediate" <?php echo ($selectedLevel == 'Intermediate') ? 'selected' : ''; ?>>
                                    Trung cấp (Intermediate)
                                </option>
                                <option value="Advanced" <?php echo ($selectedLevel == 'Advanced') ? 'selected' : ''; ?>>
                                    Nâng cao (Advanced)
                                </option>
                            </select>
                            <small class="text-muted">Chọn mức độ phù hợp với đối tượng học viên</small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a
                                href="/Website_Quan_ly_khoa_hoc_online/instructor/courses"
                                class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Cập nhật khóa học
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Custom styles for edit course form */
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
    document.getElementById('editCourseForm').addEventListener('submit', function(e) {
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

        if (!level) {
            errors.push('Vui lòng chọn độ khó');
        }

        if (errors.length > 0) {
            e.preventDefault();
            alert('Lỗi:\n' + errors.join('\n'));
            return false;
        }

        return true;
    });

    // Auto-clear form data session after rendering
    <?php if (isset($_SESSION['form_data'])): ?>
        <?php unset($_SESSION['form_data']); ?>
    <?php endif; ?>
</script>