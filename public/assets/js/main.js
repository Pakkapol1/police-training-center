// Back to Top Button
document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.getElementById('btn-back-to-top');
    
    if (backToTopButton) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.style.display = 'block';
            } else {
                backToTopButton.style.display = 'none';
            }
        });
        
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Form Validation Enhancement
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
    
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        if (alert.classList.contains('alert-success')) {
            setTimeout(function() {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            }, 3000);
        }
    });
    
    // Loading state for buttons
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const form = button.closest('form');
            if (form && form.checkValidity()) {
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> กำลังประมวลผล...';
                button.disabled = true;
            }
        });
    });
});

// Admin Panel Functions
function confirmDelete(message) {
    return confirm(message || 'คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?');
}

// File upload validation
function validateFile(input) {
    const file = input.files[0];
    const errorDiv = document.getElementById('fileError');
    const submitBtn = document.getElementById('submitBtn');
    
    if (file) {
        // Check file size (10MB)
        if (file.size > 10 * 1024 * 1024) {
            showError('ขนาดไฟล์ใหญ่เกินไป (สูงสุด 10MB)');
            input.value = '';
            return;
        }
        
        // Check file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            showError('ประเภทไฟล์ไม่ถูกต้อง (อนุญาตเฉพาะ JPG, PNG, GIF)');
            input.value = '';
            return;
        }
        
        hideError();
        previewImage(input);
    }
}

function showError(message) {
    const errorDiv = document.getElementById('fileError');
    const submitBtn = document.getElementById('submitBtn');
    
    if (errorDiv) {
        errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;
        errorDiv.style.display = 'block';
    }
    
    if (submitBtn) {
        submitBtn.disabled = true;
    }
}

function hideError() {
    const errorDiv = document.getElementById('fileError');
    const submitBtn = document.getElementById('submitBtn');
    
    if (errorDiv) {
        errorDiv.style.display = 'none';
    }
    
    if (submitBtn) {
        submitBtn.disabled = false;
    }
}

function previewImage(input) {
    const file = input.files[0];
    const preview = document.getElementById('preview');
    const previewDiv = document.getElementById('imagePreview');
    
    if (file && preview && previewDiv) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewDiv.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}
