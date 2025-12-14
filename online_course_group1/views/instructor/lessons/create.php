<div class="container-fluid px-4">
    <div class="mb-4">
        <a href="/Website_Quan_ly_khoa_hoc_online/instructor/courses/<?php echo $course['id']; ?>/manage" class="text-decoration-none text-muted mb-2 d-inline-block">
            <i class="fas fa-arrow-left me-1"></i> Quay lại quản lý nội dung
        </a>
        <h2 class="fw-bold text-dark">Thêm bài học mới</h2>
        <p class="text-muted">Khóa học: <strong><?php echo htmlspecialchars($course['title']); ?></strong></p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="/Website_Quan_ly_khoa_hoc_online/courses/<?php echo $course['id']; ?>/lessons" method="POST">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Tiêu đề bài học <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-lg" 
                                   placeholder="Ví dụ: Giới thiệu về React Hooks" required autofocus>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Link Video bài giảng</label>
                                <input type="url" name="video_url" class="form-control"  id="videoUrlInput">
                            <div class="form-text">Hỗ trợ link YouTube, Vimeo hoặc link file .mp4 trực tiếp.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Nội dung chi tiết</label>
                            <textarea name="content" class="form-control" rows="6" 
                                      placeholder="Nhập nội dung tóm tắt, ghi chú hoặc bài tập cho bài học này..."></textarea>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="/Website_Quan_ly_khoa_hoc_online/instructor/courses/<?php echo $course['id']; ?>/manage" class="btn btn-light">Hủy bỏ</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-plus-circle me-2"></i>Thêm bài học
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