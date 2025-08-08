<?php
require_once __DIR__ . '/../../../src/models/SplashConfigModel.php';
$model = new SplashConfigModel();
$config = $model->getConfig();
$saved = isset($_GET['saved']);
?>
<style>
.splash-admin-bg {
    min-height: 100vh;
    background: linear-gradient(135deg, #f7e3b0 0%, #fffbe6 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 32px 0;
}

.splash-admin-card {
    box-shadow: 0 12px 40px rgba(191,161,74,0.15), 0 2px 12px #fffbe6;
    border-radius: 24px;
    border: none;
    max-width: 600px;
    width: 100%;
    margin: auto;
    background: #fffbe6;
    overflow: hidden;
}

.splash-admin-card .card-header {
    background: linear-gradient(135deg, #f7c948 0%, #ffe066 50%, #ffd700 100%);
    color: #7c5c00;
    border-bottom: 2px solid #f7e3b0;
    text-align: center;
    padding: 2rem 1.5rem 1.5rem 1.5rem;
    position: relative;
    overflow: hidden;
}

.splash-admin-card .card-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="crown" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M10 2l2 4h6l-2 4 2 4H8l2-4-2-4h6l-2-4z" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23crown)"/></svg>');
    opacity: 0.3;
}

.splash-admin-card .card-header h4 {
    margin: 0;
    font-weight: 800;
    font-size: 1.6rem;
    letter-spacing: 1.5px;
    position: relative;
    z-index: 1;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.splash-admin-card .card-body {
    padding: 2.5rem 2rem 2rem 2rem;
}

.splash-admin-card .form-label {
    font-weight: 700;
    color: #bfa14a;
    font-size: 1.1rem;
    margin-bottom: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.splash-admin-card .form-check-label {
    color: #7c5c00;
    font-weight: 600;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.splash-admin-card .form-check-input:checked {
    background-color: #ffd700;
    border-color: #ffd700;
    box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
}

.splash-admin-card .form-check-input:focus {
    border-color: #ffd700;
    box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
}

.splash-admin-card .form-control {
    border: 2px solid #f7e3b0;
    border-radius: 12px;
    padding: 0.8rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #fff;
}

.splash-admin-card .form-control:focus {
    border-color: #ffd700;
    box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
    background: #fff;
}

.splash-admin-card .form-text {
    color: #bfa14a;
    font-size: 0.9rem;
    font-weight: 500;
    margin-top: 0.5rem;
}

.splash-admin-card .btn-primary {
    background: linear-gradient(135deg, #ffd700 0%, #ffe066 100%);
    color: #7c5c00;
    border: none;
    font-weight: 800;
    font-size: 1.2rem;
    box-shadow: 0 4px 15px rgba(191,161,74,0.2);
    transition: all 0.3s ease;
    width: 100%;
    padding: 16px 0;
    letter-spacing: 1.5px;
    border-radius: 12px;
    text-transform: uppercase;
}

.splash-admin-card .btn-primary:hover {
    background: linear-gradient(135deg, #ffe066 0%, #ffd700 100%);
    color: #222;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(191,161,74,0.3);
}

.splash-admin-card .btn-back {
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    color: #7c5c00;
    border: 2px solid #f7e3b0;
    font-weight: 700;
    font-size: 1.1rem;
    width: 100%;
    padding: 14px 0;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    border-radius: 12px;
    text-align: center;
    display: inline-block;
}

.splash-admin-card .btn-back:hover {
    background: linear-gradient(135deg, #ffe066 0%, #ffd700 100%);
    color: #bfa14a;
    border-color: #ffd700;
    transform: translateY(-1px);
}

.splash-admin-card .alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    border: 2px solid #28a745;
    font-weight: 700;
    text-align: center;
    font-size: 1.1rem;
    margin-bottom: 2rem;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.1);
}

.splash-admin-card .preview-img {
    max-width: 100%;
    max-height: 300px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(191,161,74,0.2);
    border: 3px solid #ffd700;
    margin-top: 1rem;
    transition: transform 0.3s ease;
}

.splash-admin-card .preview-img:hover {
    transform: scale(1.02);
}

.splash-admin-btns {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-top: 2rem;
}

@media (min-width: 500px) {
    .splash-admin-btns {
        flex-direction: row;
        gap: 20px;
    }
}

.splash-admin-card .form-check {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: rgba(255, 215, 0, 0.05);
    border-radius: 12px;
    border: 1px solid rgba(255, 215, 0, 0.2);
}

.splash-admin-card .mb-4 {
    margin-bottom: 2rem !important;
}

.section-divider {
    height: 2px;
    background: linear-gradient(90deg, transparent 0%, #ffd700 50%, transparent 100%);
    margin: 2rem 0;
    border-radius: 1px;
}

.section-title {
    font-size: 1.3rem;
    font-weight: 800;
    color: #7c5c00;
    margin-bottom: 1.5rem;
    text-align: center;
    position: relative;
    padding-bottom: 0.5rem;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #ffd700, #ffe066);
    border-radius: 2px;
}

.royal-duties-section {
    background: linear-gradient(135deg, rgba(255, 215, 0, 0.1) 0%, rgba(255, 224, 102, 0.1) 100%);
    border: 2px solid rgba(255, 215, 0, 0.3);
    border-radius: 16px;
    padding: 1.5rem;
    margin: 1.5rem 0;
    position: relative;
    overflow: hidden;
}

.royal-duties-section::before {
    content: '👑';
    position: absolute;
    top: -10px;
    right: -10px;
    font-size: 3rem;
    opacity: 0.1;
    transform: rotate(15deg);
}

.url-preview {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 0.8rem;
    margin-top: 0.5rem;
    font-family: monospace;
    font-size: 0.9rem;
    color: #6c757d;
    word-break: break-all;
}

.splash-btn {
    font-size: 1.1rem;
    font-weight: bold;
    padding: 12px 24px;
    border: 2px solid #FFD700;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-block;
    min-width: 150px;
    text-align: center;
    font-family: inherit;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    letter-spacing: 0.5px;
}

.splash-btn.secondary {
    background: #fffbe6;
    color: #bfa14a;
}

.splash-btn.secondary:hover {
    background: #FFD700;
    color: #222;
    border-color: #FFD700;
    box-shadow: 0 4px 12px rgba(191,161,74,0.2);
    transform: translateY(-1px);
}

@media (max-width: 600px) {
    .splash-admin-card { 
        max-width: 95vw; 
        margin: 1rem;
    }
    .splash-admin-card .card-body { 
        padding: 1.5rem 1rem 1rem 1rem; 
    }
    .splash-admin-card .card-header {
        padding: 1.5rem 1rem 1rem 1rem;
    }
    .splash-admin-card .card-header h4 {
        font-size: 1.4rem;
    }
}
</style>
<div class="splash-admin-bg">
    <div class="card splash-admin-card">
        <div class="card-header">
            <h4><i class="fas fa-bolt"></i> ตั้งค่า Splash Page</h4>
        </div>
        <div class="card-body">
            <?php if ($saved): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> บันทึกการตั้งค่าสำเร็จ
                </div>
            <?php endif; ?>
            
            <!-- Status Summary -->
            <div style="background: linear-gradient(135deg, rgba(255, 215, 0, 0.1) 0%, rgba(255, 224, 102, 0.1) 100%); border: 1px solid rgba(255, 215, 0, 0.3); border-radius: 12px; padding: 1rem; margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-info-circle" style="color: #bfa14a;"></i>
                        <span style="font-weight: 600; color: #7c5c00;">สถานะปัจจุบัน:</span>
                    </div>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <span style="display: flex; align-items: center; gap: 0.3rem; padding: 0.3rem 0.8rem; background: <?= ($config && $config['enabled']) ? 'rgba(40, 167, 69, 0.2)' : 'rgba(220, 53, 69, 0.2)' ?>; border-radius: 6px; font-size: 0.9rem; font-weight: 600; color: <?= ($config && $config['enabled']) ? '#155724' : '#721c24' ?>;">
                            <i class="fas fa-<?= ($config && $config['enabled']) ? 'check' : 'times' ?>-circle"></i>
                            Splash Page: <?= ($config && $config['enabled']) ? 'เปิดใช้งาน' : 'ปิดใช้งาน' ?>
                        </span>
                        <span style="display: flex; align-items: center; gap: 0.3rem; padding: 0.3rem 0.8rem; background: <?= ($config && isset($config['royal_duties_enabled']) && $config['royal_duties_enabled']) ? 'rgba(40, 167, 69, 0.2)' : 'rgba(220, 53, 69, 0.2)' ?>; border-radius: 6px; font-size: 0.9rem; font-weight: 600; color: <?= ($config && isset($config['royal_duties_enabled']) && $config['royal_duties_enabled']) ? '#155724' : '#721c24' ?>;">
                            <i class="fas fa-<?= ($config && isset($config['royal_duties_enabled']) && $config['royal_duties_enabled']) ? 'check' : 'times' ?>-circle"></i>
                            ปุ่มพระราชกรณียกิจ: <?= ($config && isset($config['royal_duties_enabled']) && $config['royal_duties_enabled']) ? 'เปิดใช้งาน' : 'ปิดใช้งาน' ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <form method="post" enctype="multipart/form-data" action="?action=admin_splash_save" id="splashForm">
                
                <!-- Splash Page Settings Section -->
                <div class="section-title">
                    <i class="fas fa-bolt"></i> การตั้งค่า Splash Page
                </div>
                
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="enabled" id="enabled" value="1" <?= ($config && $config['enabled']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="enabled">
                        <i class="fas fa-eye"></i> เปิดใช้งาน Splash Page
                    </label>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-image"></i> เลือกรูปภาพใหม่ (jpg, png, webp)
                    </label>
                    <input type="file" name="splash_image" accept="image/*" class="form-control" id="splashImage">
                    <div class="form-text">อัปโหลดรูปภาพใหม่เพื่อเปลี่ยนรูป Splash Page</div>
                    
                    <?php if ($config && $config['image_path']): ?>
                        <div class="mt-3">
                            <div class="mb-2 text-muted" style="font-size:1rem; font-weight:600;">
                                <i class="fas fa-eye"></i> รูปปัจจุบัน:
                            </div>
                            <img src="<?= htmlspecialchars($config['image_path']) ?>" alt="splash preview" class="preview-img">
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="section-divider"></div>
                
                <!-- Royal Duties Button Section -->
                <div class="section-title">
                    <i class="fas fa-crown"></i> การตั้งค่าปุ่มพระราชกรณียกิจ
                </div>
                
                <div class="royal-duties-section">
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="royal_duties_enabled" id="royal_duties_enabled" value="1" <?= ($config && isset($config['royal_duties_enabled']) && $config['royal_duties_enabled']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="royal_duties_enabled">
                            <i class="fas fa-crown"></i> เปิดใช้งานปุ่มพระราชกรณียกิจ
                        </label>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-link"></i> URL สำหรับปุ่มพระราชกรณียกิจ
                        </label>
                        <input type="url" name="royal_duties_url" class="form-control" id="royalDutiesUrl" 
                               placeholder="https://example.com" 
                               value="<?= htmlspecialchars($config['royal_duties_url'] ?? '') ?>">
                        <div class="form-text">ใส่ URL ที่จะเปิดเมื่อคลิกปุ่มพระราชกรณียกิจ</div>
                        
                        <!-- URL Preview -->
                        <div id="urlPreview" class="url-preview" style="display: none;">
                            <strong>ตัวอย่างลิงก์:</strong> <span id="previewText"></span>
                        </div>
                    </div>
                    
                    <!-- Button Preview -->
                    <div id="buttonPreview" style="display: none;">
                        <div class="mb-2 text-muted" style="font-size:1rem; font-weight:600;">
                            <i class="fas fa-eye"></i> ตัวอย่างปุ่ม:
                        </div>
                        <div style="background: #f7e3b0; padding: 1rem; border-radius: 12px; text-align: center;">
                            <a href="#" class="splash-btn secondary" style="display: inline-block; margin: 0.5rem;">
                                พระราชกรณียกิจ
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="splash-admin-btns">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> บันทึกการตั้งค่า
                    </button>
                    <button type="button" onclick="window.location.href='/admin?action=dashboard'" class="btn btn-back">
                        <i class="fas fa-arrow-left"></i> กลับสู่แดชบอร์ด
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const royalDutiesEnabled = document.getElementById('royal_duties_enabled');
    const royalDutiesUrl = document.getElementById('royalDutiesUrl');
    const urlPreview = document.getElementById('urlPreview');
    const previewText = document.getElementById('previewText');
    const buttonPreview = document.getElementById('buttonPreview');
    const splashForm = document.getElementById('splashForm');
    
    // Function to update URL preview
    function updateUrlPreview() {
        const url = royalDutiesUrl.value.trim();
        if (url) {
            previewText.textContent = url;
            urlPreview.style.display = 'block';
        } else {
            urlPreview.style.display = 'none';
        }
    }
    
    // Function to update button preview
    function updateButtonPreview() {
        const isEnabled = royalDutiesEnabled.checked;
        const hasUrl = royalDutiesUrl.value.trim();
        
        if (isEnabled && hasUrl) {
            buttonPreview.style.display = 'block';
        } else {
            buttonPreview.style.display = 'none';
        }
    }
    
    // Event listeners
    royalDutiesUrl.addEventListener('input', function() {
        updateUrlPreview();
        updateButtonPreview();
    });
    
    royalDutiesEnabled.addEventListener('change', function() {
        updateButtonPreview();
    });
    
    // Form validation
    splashForm.addEventListener('submit', function(e) {
        const isEnabled = royalDutiesEnabled.checked;
        const url = royalDutiesUrl.value.trim();
        
        if (isEnabled && !url) {
            e.preventDefault();
            alert('กรุณาใส่ URL สำหรับปุ่มพระราชกรณียกิจ');
            royalDutiesUrl.focus();
            return false;
        }
        
        if (url && !isValidUrl(url)) {
            e.preventDefault();
            alert('กรุณาใส่ URL ที่ถูกต้อง');
            royalDutiesUrl.focus();
            return false;
        }
    });
    
    // URL validation function
    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }
    
    // Initialize previews
    updateUrlPreview();
    updateButtonPreview();
    
    // Add smooth transitions
    const formElements = document.querySelectorAll('.form-control, .form-check-input');
    formElements.forEach(element => {
        element.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });
        
        element.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });
    
    // Add loading state to submit button
    splashForm.addEventListener('submit', function() {
        const submitBtn = this.querySelector('.btn-primary');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> กำลังบันทึก...';
        submitBtn.disabled = true;
    });
});
</script>
</div> 