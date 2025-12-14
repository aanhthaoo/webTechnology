<!-- Instructor my courses -->
<style>
    /* Course Management Styles */
    .course-management {
        padding: 2rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .btn-create-course {
        background: #28a745;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    .btn-create-course:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        color: white;
        text-decoration: none;
    }

    .btn-create-course i {
        margin-right: 0.5rem;
    }

    /* Table Styles */
    .courses-table-container {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        border: 1px solid #e9ecef;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
        padding: 1rem;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-top: 1px solid #dee2e6;
    }

    .course-title {
        font-weight: 600;
        color: #2c3e50;
        font-size: 1.05rem;
    }

    .badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
        font-weight: 500;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .badge-info {
        background: #d1ecf1;
        color: #0c5460;
    }

    .btn-action {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.9rem;
        margin: 0 0.2rem;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-manage {
        background: #e7f3ff;
        color: #0066cc;
        border: 1px solid transparent;
    }

    .btn-manage:hover {
        background: #0066cc;
        color: white;
        text-decoration: none;
    }

    .btn-edit {
        background: transparent;
        color: #007bff;
        border: none;
        font-size: 1.2rem;
    }

    .btn-edit:hover {
        color: #0056b3;
    }

    .btn-delete {
        background: transparent;
        color: #dc3545;
        border: none;
        font-size: 1.2rem;
    }

    .btn-delete:hover {
        color: #bd2130;
    }

    .price-cell {
        font-weight: 600;
        color: #28a745;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: #495057;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .table {
            font-size: 0.9rem;
        }

        .table thead th,
        .table tbody td {
            padding: 0.75rem 0.5rem;
        }
    }

    /* Modal Styles */
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

<div class="course-management">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Quản lý khóa học</h1>
        <a href="<?php echo baseUrl('/instructor/courses/create'); ?>" class="btn-create-course">
            <i class="fas fa-plus"></i>
            Tạo khóa học
        </a>
    </div>

    <!-- Courses Table -->
    <div class="courses-table-container">
        <?php if ($has_courses): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tên khóa học</th>
                            <th>Học viên</th>
                            <th>Trạng thái</th>
                            <th>Giá tiền</th>
                            <th>Nội dung</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <!-- Tên khóa học -->
                                <td>
                                    <div class="course-title">
                                        <?php echo htmlspecialchars($course['title']); ?>
                                    </div>
                                    <small class="text-muted">
                                        <?php echo htmlspecialchars($course['category_name'] ?? 'Chưa phân loại'); ?>
                                    </small>
                                </td>

                                <!-- Số học viên -->
                                <td>
                                    <strong><?php echo $course['student_count']; ?></strong>
                                </td>

                                <!-- Trạng thái -->
                                <td>
                                    <?php
                                    if ($course['status'] == 'pending') {
                                        echo '<span class="badge badge-success">Chờ duyệt</span>';
                                    } else if ($course['status'] == 'approved'){
                                        echo '<span class="badge badge-warning">Đã duyệt</span>';
                                    }
                                    else {
                                        echo '<span class="badge badge-info">Từ chối</span>';
                                    }
                                    ?>
                                </td>

                                <!-- Giá tiền -->
                                <td class="price-cell">
                                    <?php
                                    if ($course['price'] > 0) {
                                        echo number_format($course['price'], 0, ',', '.') . ' đ';
                                    } else {
                                        echo '<span class="badge badge-info">Miễn phí</span>';
                                    }
                                    ?>
                                </td>

                                <!-- Quản lý nội dung -->
                                <td>
                                    <a href="<?php echo baseUrl('/instructor/courses/' .  $course['id'] . '/manage') ;?>"
                                        class="btn-action btn-manage">
                                        <i class="fas fa-tasks"></i> Quản lý nội dung
                                    </a>
                                </td>

                                <!-- Hành động -->
                                <td>
                                    <a href="<?php echo baseUrl('/instructor/courses/' . $course['id'] . '/edit'); ?>"
                                        class="btn-action btn-edit"
                                        title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button type="button"
                                        class="btn-action btn-delete"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-course-id="<?php echo $course['id']; ?>"
                                        data-course-title="<?php echo htmlspecialchars($course['title']); ?>"
                                        title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-book-open"></i>
                <h5>Chưa có khóa học nào</h5>
                <p>Bạn chưa tạo khóa học nào. Hãy tạo khóa học đầu tiên của bạn!</p>
                <a href="/instructor/courses/create" class="btn-create-course">
                    <i class="fas fa-plus"></i>
                    Tạo khóa học đầu tiên
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Xác nhận xóa khóa học
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Bạn có chắc chắn muốn xóa khóa học:</p>
                <p class="fw-bold text-danger mb-3" id="courseNameToDelete"></p>
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>
                        <strong>Lưu ý:</strong> Hành động này sẽ xóa tất cả bài học, tài liệu và đăng ký liên quan. 
                        <strong>Không thể hoàn tác!</strong>
                    </small>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Hủy
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-2"></i>Xóa khóa học
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Delete Confirmation -->
<script>
    // Handle delete modal
    const deleteModal = document.getElementById('deleteModal');
    
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const courseId = button.getAttribute('data-course-id');
        const courseTitle = button.getAttribute('data-course-title');
        
        const courseNameElement = deleteModal.querySelector('#courseNameToDelete');
        courseNameElement.textContent = courseTitle;
        
        const deleteForm = deleteModal.querySelector('#deleteForm');
        deleteForm.action = '/Website_Quan_ly_khoa_hoc_online/instructor/courses/' + courseId + '/delete';
    });
</script>

<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['flash_type'] === 'error' ? 'danger' : $_SESSION['flash_type']; ?> alert-dismissible fade show mt-3" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <?php echo $_SESSION['flash_message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
    // Quan trọng: Xóa session ngay sau khi hiện để không hiện lại khi F5
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
    ?>
<?php endif; ?>