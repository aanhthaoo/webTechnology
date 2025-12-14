<style>
    /* Custom Style theo ảnh Reference */
    .filter-container {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    .search-box .input-group-text {
        background: #f8f9fa;
        border-right: none;
    }
    
    .search-box .form-control {
        border-left: none;
        background: #f8f9fa;
    }
    
    .search-box .form-control:focus {
        background: white;
        box-shadow: none;
        border-color: #dee2e6;
    }

    .table-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .table thead th {
        background: white;
        border-bottom: 2px solid #f1f1f1;
        font-weight: 600;
        color: #333;
        padding: 1rem;
    }
    
    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f1f1;
    }

    /* Avatar circle */
    .student-avatar-sm {
        width: 35px;
        height: 35px;
        background-color: #e3f2fd;
        color: #4285f4;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
        margin-right: 10px;
    }

    /* Progress bar custom */
    .progress-thin {
        height: 6px;
        border-radius: 3px;
        background-color: #f1f1f1;
        margin-top: 5px;
    }
</style>

<div class="container-fluid px-4">
    <h3 class="fw-bold text-dark mb-4">Theo dõi tiến độ học viên</h3>

    <div class="filter-container">
        <form method="GET" action="" class="row g-3">
            <div class="col-md-4">
                <select name="course_id" class="form-select border-light bg-light" onchange="this.form.submit()">
                    <option value="">-- Tất cả khóa học --</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo $course['id']; ?>" 
                            <?php echo ($filters['course_id'] == $course['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($course['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-8">
                <div class="input-group search-box">
                    <span class="input-group-text"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Tìm kiếm tên học viên hoặc email..." 
                           value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                    <button class="btn btn-primary px-4" type="submit">Tìm</button>
                </div>
            </div>
        </form>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Học viên</th>
                        <th>Khóa học</th>
                        <th style="width: 30%;">Tiến độ</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <img src="/Website_Quan_ly_khoa_hoc_online/assets/images/empty-state.png" 
                                     alt="No data" style="width: 100px; opacity: 0.5;" class="mb-3">
                                <p class="text-muted mb-0">Không tìm thấy dữ liệu phù hợp.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $std): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="student-avatar-sm">
                                            <?php echo strtoupper(substr($std['student_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark"><?php echo htmlspecialchars($std['student_name']); ?></div>
                                            <small class="text-muted" style="font-size: 0.8rem;"><?php echo htmlspecialchars($std['student_email']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-dark fw-medium"><?php echo htmlspecialchars($std['course_title']); ?></span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold small"><?php echo $std['progress']; ?>%</span>
                                    </div>
                                    <div class="progress progress-thin">
                                        <?php 
                                            $bgClass = 'bg-primary';
                                            if ($std['progress'] == 100) $bgClass = 'bg-success';
                                            else if ($std['progress'] < 30) $bgClass = 'bg-warning';
                                        ?>
                                        <div class="progress-bar <?php echo $bgClass; ?>" 
                                             role="progressbar" 
                                             style="width: <?php echo $std['progress']; ?>%">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($std['progress'] == 100): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                                            Hoàn thành
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3">
                                            Đang học
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>