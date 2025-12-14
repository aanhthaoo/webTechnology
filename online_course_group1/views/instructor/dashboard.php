<?php
// File: views/instructor/dashboard.php
?>

<style>
/* Dashboard Layout Styles */
.dashboard-container {
    display: flex;
    min-height: 100vh;
    background: #f8f9fa;
}

.main-content {
    flex: 1;
    margin-left: 280px;
    padding: 0;
}

.content-header {
    background: white;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #dee2e6;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.page-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.content-body {
    padding: 2rem;
}

/* Stats Cards */
.stats-container {
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.stat-number {
    font-size: 3rem;
    font-weight: 800;
    color: #2c3e50;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1.1rem;
    color: #6c757d;
    font-weight: 500;
    margin: 0;
}

.stat-card.courses .stat-number {
    color: #3498db;
}

.stat-card.students .stat-number {
    color: #e74c3c;
}

/* Table Section */
.table-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    border: 1px solid #e9ecef;
}

.section-title {
    font-size: 1.4rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
}

.section-title i {
    margin-right: 0.5rem;
    color: #3498db;
}

/* Table Styles */
.table-responsive {
    border-radius: 8px;
    overflow: hidden;
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
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-top: 1px solid #dee2e6;
}

.progress {
    height: 8px;
    border-radius: 4px;
}

.badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.8rem;
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
    margin: 0;
    font-size: 0.95rem;
}

/* Responsive */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
    }
    
    .content-body {
        padding: 1rem;
    }
    
    .stat-card {
        margin-bottom: 1rem;
    }
}
</style>

    <div class="container-fluid px-4">
        
        <!-- Content Body -->
        <div class="content-body">
            <!-- Stats Cards -->
            <div class="stats-container">
                <div class="row">
                    <!-- Tổng khóa học -->
                    <div class="col-lg-6 col-md-6 mb-4">
                        <div class="stat-card courses">
                            <div class="stat-number"><?php echo $stats['total_courses'] ?? 0; ?></div>
                            <p class="stat-label">Tổng khóa học</p>
                        </div>
                    </div>
                    
                    <!-- Tổng học viên -->
                    <div class="col-lg-6 col-md-6 mb-4">
                        <div class="stat-card students">
                            <div class="stat-number"><?php echo $stats['total_students'] ?? 0; ?></div>
                            <p class="stat-label">Tổng học viên</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Students Table Section -->
            <div class="table-section">
                <h3 class="section-title">
                    <i class="fas fa-users"></i>
                    Danh sách học viên của tôi
                </h3>
                
                <?php if ($has_students): ?>
                    <!-- Table with data -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Học viên</th>
                                    <th>Khóa học</th>
                                    <th>Ngày đăng ký</th>
                            </thead>
                            <tbody>
                                <?php foreach ($students_list as $student): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-3">
                                                    <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold"><?php echo htmlspecialchars($student['student_name']); ?></div>
                                                    <small class="text-muted"><?php echo htmlspecialchars($student['student_email']); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-medium"><?php echo htmlspecialchars($student['course_title']); ?></span>
                                        </td>
                                        <td>
                                            <?php 
                                            $date = new DateTime($student['enrolled_date']);
                                            echo $date->format('d/m/Y');
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <!-- Empty State -->
                    <div class="empty-state">
                        <i class="fas fa-user-friends"></i>
                        <h5>Chưa có học viên nào</h5>
                        <p>Khi có học viên đăng ký khóa học của bạn, họ sẽ xuất hiện ở đây.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
