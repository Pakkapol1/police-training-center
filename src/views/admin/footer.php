</div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Toggle Sidebar for Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            sidebar.classList.toggle('show');
        }

        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    if (alert.parentNode) {
                        alert.style.opacity = '0';
                        setTimeout(function() {
                            if (alert.parentNode) {
                                alert.parentNode.removeChild(alert);
                            }
                        }, 300);
                    }
                }, 5000);
            });

            // Add loading state to forms
            const forms = document.querySelectorAll('form');
            forms.forEach(function(form) {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<span class="loading"></span> กำลังดำเนินการ...';
                        submitBtn.disabled = true;
                    }
                });
            });

            // Confirm delete actions
            const deleteLinks = document.querySelectorAll('a[href*="delete"], button[onclick*="delete"]');
            deleteLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    if (!confirm('คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?')) {
                        e.preventDefault();
                        return false;
                    }
                });
            });

            // Auto-save for textareas
            const textareas = document.querySelectorAll('textarea');
            textareas.forEach(function(textarea) {
                let timeout;
                textarea.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(function() {
                        localStorage.setItem('draft_' + textarea.id, textarea.value);
                    }, 1000);
                });

                // Load saved data
                const savedData = localStorage.getItem('draft_' + textarea.id);
                if (savedData && !textarea.value) {
                    textarea.value = savedData;
                }
            });

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        // Show success message
        function showSuccess(message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                <i class="fas fa-check-circle"></i> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);

            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }

        // Show error message
        function showError(message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed';
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                <i class="fas fa-exclamation-triangle"></i> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);

            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }

        // Check for URL parameters and show messages
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('message')) {
            const messages = {
                'added': 'เพิ่มข้อมูลสำเร็จ',
                'updated': 'แก้ไขข้อมูลสำเร็จ',
                'deleted': 'ลบข้อมูลสำเร็จ',
                'uploaded': 'อัปโหลดไฟล์สำเร็จ'
            };
            const message = messages[urlParams.get('message')] || 'ดำเนินการสำเร็จ';
            showSuccess(message);
        }

        if (urlParams.get('error')) {
            const errors = {
                'not_found': 'ไม่พบข้อมูลที่ต้องการ',
                'permission_denied': 'ไม่มีสิทธิ์ในการดำเนินการ',
                'invalid_data': 'ข้อมูลไม่ถูกต้อง'
            };
            const error = errors[urlParams.get('error')] || 'เกิดข้อผิดพลาด';
            showError(error);
        }
    </script>
</body>
</html>
