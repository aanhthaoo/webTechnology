// Lesson functionality
let isLessonCompleted = false;
let currentLessonId = null;

// Initialize lesson functionality
function initializeLesson(completed, lessonId) {
    isLessonCompleted = completed;
    currentLessonId = lessonId;
    
    // Show/hide next lesson button based on completion status
    const nextBtn = document.getElementById('nextLessonBtn');
    if (nextBtn) {
        if (completed) {
            nextBtn.style.display = 'inline-block';
        } else {
            nextBtn.style.display = 'none';
        }
    }
}

// Mark lesson as complete
function markComplete() {
    if (isLessonCompleted) {
        return;
    }
    
    const formData = new FormData();
    
    fetch(`/Website_Quan_ly_khoa_hoc_online/lessons/${currentLessonId}/complete`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            isLessonCompleted = true;
            
            // Update UI
            const completeBtn = document.querySelector('[onclick="markComplete()"]');
            if (completeBtn) {
                completeBtn.outerHTML = `
                    <span class="badge bg-success fs-6 py-2 px-3">
                        <i class="fas fa-check-circle"></i> Đã hoàn thành
                    </span>
                `;
            }
            
            // Show next lesson button
            const nextBtn = document.getElementById('nextLessonBtn');
            if (nextBtn) {
                nextBtn.style.display = 'inline-block';
            }
            
            // Show success message
            showNotification('Đã đánh dấu hoàn thành bài học!', 'success');
        } else {
            showNotification('Có lỗi xảy ra: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra khi đánh dấu hoàn thành', 'error');
    });
}

// Toggle notes modal
function toggleNotes() {
    const modal = new bootstrap.Modal(document.getElementById('notesModal'));
    modal.show();
    
    // Load existing notes (if any)
    loadNotes();
}

// Save notes
function saveNotes() {
    const notes = document.getElementById('lessonNotes').value;
    
    // Save to localStorage for now (can be enhanced to save to server)
    const notesKey = `lesson_notes_${currentLessonId}`;
    localStorage.setItem(notesKey, notes);
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('notesModal'));
    modal.hide();
    
    showNotification('Đã lưu ghi chú!', 'success');
}

// Load notes
function loadNotes() {
    const notesKey = `lesson_notes_${currentLessonId}`;
    const savedNotes = localStorage.getItem(notesKey);
    
    if (savedNotes) {
        document.getElementById('lessonNotes').value = savedNotes;
    }
}

// Show notification
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// Video progress tracking (optional enhancement)
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('lessonVideo');
    if (video) {
        video.addEventListener('ended', function() {
            // Auto mark as complete when video ends (optional)
            if (!isLessonCompleted) {
                setTimeout(() => {
                    const completeBtn = document.querySelector('[onclick="markComplete()"]');
                    if (completeBtn) {
                        completeBtn.click();
                    }
                }, 1000);
            }
        });
    }
});