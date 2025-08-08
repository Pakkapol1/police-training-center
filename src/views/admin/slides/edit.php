<?php include SRC_PATH . '/views/admin/header.php'; ?>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit text-primary"></i>
                แก้ไขสไลด์
            </h1>
            <p class="text-muted">แก้ไขข้อมูลสไลด์สำหรับหน้าแรกของเว็บไซต์</p>
        </div>
        <a href="/admin?action=slides" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            กลับไปรายการ
        </a>
    </div>

    <!-- Error Message -->
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-edit"></i>
                ข้อมูลสไลด์
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" accept-charset="UTF-8">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Slide Information -->
                        <div class="mb-4">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading"></i>
                                หัวข้อ <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="title" 
                                   name="title" 
                                   value="<?= htmlspecialchars($_POST['title'] ?? $slide['title']) ?>"
                                   placeholder="กรอกหัวข้อสไลด์"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label for="subtitle" class="form-label">
                                <i class="fas fa-subscript"></i>
                                หัวข้อย่อย
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="subtitle" 
                                   name="subtitle" 
                                   value="<?= htmlspecialchars($_POST['subtitle'] ?? $slide['subtitle']) ?>"
                                   placeholder="กรอกหัวข้อย่อย (ถ้ามี)">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left"></i>
                                รายละเอียด
                            </label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="4"
                                      placeholder="กรอกรายละเอียดสไลด์"><?= htmlspecialchars($_POST['description'] ?? $slide['description']) ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="link_url" class="form-label">
                                <i class="fas fa-link"></i>
                                ลิงก์เมื่อคลิก
                            </label>
                            <input type="url" 
                                   class="form-control" 
                                   id="link_url" 
                                   name="link_url" 
                                   value="<?= htmlspecialchars($_POST['link_url'] ?? $slide['link_url']) ?>"
                                   placeholder="https://example.com">
                            <small class="form-text text-muted">เว้นว่างถ้าไม่ต้องการลิงก์</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="sort_order" class="form-label">
                                        <i class="fas fa-sort-numeric-up"></i>
                                        ลำดับการแสดงผล
                                    </label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="sort_order" 
                                           name="sort_order" 
                                           value="<?= htmlspecialchars($_POST['sort_order'] ?? $slide['sort_order']) ?>"
                                           min="0">
                                    <small class="form-text text-muted">ตัวเลขน้อยจะแสดงก่อน</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-toggle-on"></i>
                                        สถานะ
                                    </label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active" <?= ($_POST['status'] ?? $slide['status']) == 'active' ? 'selected' : '' ?>>
                                            เปิดใช้งาน
                                        </option>
                                        <option value="inactive" <?= ($_POST['status'] ?? $slide['status']) == 'inactive' ? 'selected' : '' ?>>
                                            ปิดใช้งาน
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Current Image -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-image"></i>
                                รูปภาพปัจจุบัน
                            </label>
                            <?php if (!empty($slide['image_path'])): ?>
                                <div class="current-image mb-3">
                                    <img src="<?= htmlspecialchars($slide['image_path']) ?>" 
                                         alt="<?= htmlspecialchars($slide['title']) ?>" 
                                         class="img-fluid rounded border">
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4 bg-light rounded border">
                                    <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">ไม่มีรูปภาพ</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-image"></i>
                                เปลี่ยนรูปภาพ (ไม่บังคับ)
                            </label>
                            <div class="upload-area" id="uploadArea">
                                <div class="upload-content text-center">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">คลิกเพื่อเลือกรูปภาพใหม่</p>
                                    <p class="text-muted small">รองรับไฟล์ JPG, PNG, WEBP ขนาดไม่เกิน 5MB</p>
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('image').click()">
                                        <i class="fas fa-folder-open"></i>
                                        เลือกรูปภาพ
                                    </button>
                                </div>
                                <input type="file" 
                                       id="image" 
                                       name="image" 
                                       accept="image/jpeg,image/png,image/webp"
                                       style="display: none;">
                            </div>
                            
                            <!-- Image Preview -->
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <img id="previewImg" src="" alt="Preview" class="img-fluid rounded">
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removeImage()">
                                    <i class="fas fa-trash"></i>
                                    ลบรูปภาพ
                                </button>
                            </div>

                            <!-- Image Requirements -->
                            <div class="mt-3">
                                <h6 class="text-muted">ข้อกำหนดรูปภาพ:</h6>
                                <ul class="text-muted small">
                                    <li>ขนาดที่แนะนำ: 1920x1080 pixels</li>
                                    <li>รูปแบบไฟล์: JPG, PNG, WEBP</li>
                                    <li>ขนาดไฟล์: ไม่เกิน 5MB</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="/admin?action=slides" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        ยกเลิก
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.upload-area {
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
}

.upload-area:hover {
    border-color: #007bff;
    background-color: #f0f8ff;
}

.upload-area.dragover {
    border-color: #007bff;
    background-color: #e3f2fd;
}

.current-image img {
    max-width: 100%;
    height: auto;
    border: 1px solid #ddd;
}

#imagePreview img {
    max-width: 100%;
    height: auto;
    border: 1px solid #ddd;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const uploadArea = document.getElementById('uploadArea');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    // File input change event
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (validateImage(file)) {
                displayImagePreview(file);
            } else {
                alert('กรุณาเลือกรูปภาพที่มีขนาดไม่เกิน 5MB และเป็นไฟล์ JPG, PNG หรือ WEBP เท่านั้น');
                imageInput.value = '';
            }
        }
    });

    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (validateImage(file)) {
                imageInput.files = files;
                displayImagePreview(file);
            } else {
                alert('กรุณาเลือกรูปภาพที่มีขนาดไม่เกิน 5MB และเป็นไฟล์ JPG, PNG หรือ WEBP เท่านั้น');
            }
        }
    });

    // Validate image file
    function validateImage(file) {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        const maxSize = 5 * 1024 * 1024; // 5MB

        if (!allowedTypes.includes(file.type)) {
            return false;
        }

        if (file.size > maxSize) {
            return false;
        }

        return true;
    }

    // Display image preview
    function displayImagePreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            imagePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// Remove image function
function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('previewImg').src = '';
}
</script>

<?php include SRC_PATH . '/views/admin/footer.php'; ?> 