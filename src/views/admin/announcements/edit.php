<?php
require_once SRC_PATH . '/models/AnnouncementModel.php';
$announcementModel = new AnnouncementModel();
$announcement = $announcementModel->getAnnouncementById($_GET['id']);
$announcementImages = $announcementModel->getAnnouncementImages($announcement['id']);
?>

<?php include SRC_PATH . '/views/admin/header.php'; ?>

<div class="mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin?action=dashboard">แดชบอร์ด</a></li>
            <li class="breadcrumb-item"><a href="/admin?action=announcements">ประกาศ</a></li>
            <li class="breadcrumb-item active">แก้ไขประกาศ</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit text-primary"></i> แก้ไขประกาศ</h5>
                    </div>
                    <div class="card-body">
                    <?php if (isset($error) && $error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                    <?php if (isset($success) && $success): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data" id="announcementForm">
                        <div class="mb-3">
                            <label for="title" class="form-label">หัวข้อประกาศ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" 
                                       value="<?= htmlspecialchars($announcement['title']) ?>" 
                                   placeholder="กรอกหัวข้อประกาศ..." required maxlength="255">
                            </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">เนื้อหา <span class="text-danger">*</span></label>
                            <div class="input-group mb-2">
                                <input type="url" class="form-control" id="pasteUrl" 
                                       placeholder="วางลิงก์ที่นี่เพื่อแปะในข้อความ..." 
                                       pattern="https?://.+">
                                <button type="button" class="btn btn-outline-secondary" onclick="pasteLinkToContent()">
                                    <i class="fas fa-paste"></i> แปะลิงก์
                                </button>
                                <button type="button" class="btn btn-outline-info" onclick="showCustomLinkDialog()">
                                    <i class="fas fa-edit"></i> แปะแบบกำหนดข้อความ
                                </button>
                            </div>
                            <textarea class="form-control" id="content" name="content" rows="10" 
                                          placeholder="กรอกเนื้อหาประกาศ..." required><?= htmlspecialchars($announcement['content']) ?></textarea>
                                <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                    <strong>การจัดรูปแบบข้อความ:</strong><br>
                                    • <code>**ข้อความ**</code> = <strong>ตัวหนา</strong><br>
                                    • <code>*ข้อความ*</code> = <em>ตัวเอียง</em><br>
                                    • <code>`ข้อความ`</code> = <code class="bg-light px-1 rounded">โค้ด</code><br>
                                    • <code>https://example.com</code> = ลิงก์อัตโนมัติ<br>
                                • <code>[ข้อความ](https://example.com)</code> = ลิงก์แบบกำหนดข้อความ<br>
                                • ใช้ปุ่ม "แปะลิงก์" ด้านบนเพื่อแปะลิงก์ในข้อความ
                                </div>
                            </div>

                        <!-- Embed Link Section -->
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-link text-primary me-2"></i>
                                ฝังลิงก์ (Embed Links)
                                        </label>
                            <div class="embed-link-container">
                                <div class="input-group mb-2">
                                    <input type="url" class="form-control" id="embedUrl" 
                                           placeholder="วางลิงก์ที่นี่ (เช่น: https://www.youtube.com/watch?v=...)" 
                                           pattern="https?://.+">
                                    <button type="button" class="btn btn-outline-primary" onclick="addEmbedLink()">
                                        <i class="fas fa-plus"></i> เพิ่ม
                                    </button>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    รองรับ: YouTube, Facebook, Twitter, Instagram, และลิงก์ทั่วไป
                                </div>
                                <div id="embedPreview" class="mt-3"></div>
                            </div>
                        </div>

                        <!-- Current Images -->
                        <?php if (!empty($announcementImages)): ?>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-images text-primary me-2"></i>
                                    รูปภาพปัจจุบัน
                                </label>
                                <div class="current-images-grid">
                                    <?php foreach ($announcementImages as $image): ?>
                                        <div class="image-item" id="image-<?= $image['id'] ?>">
                                            <div class="image-preview">
                                                <img src="/uploads/announcements/<?= htmlspecialchars($image['image_path']) ?>" 
                                                     alt="รูปภาพประกาศ" class="img-thumbnail">
                                                <div class="image-overlay">
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="deleteImage(<?= $image['id'] ?>, '<?= htmlspecialchars($image['image_path']) ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label">รูปภาพประกอบ</label>
                            <div id="dropZone" class="border border-2 border-primary rounded p-3 mb-2 text-center bg-light" style="cursor:pointer; min-height: 80px;">
                                <i class="fas fa-cloud-upload-alt fa-2x text-primary"></i><br>
                                <span>ลากไฟล์รูปภาพมาวางที่นี่ หรือคลิกเพื่อเลือกไฟล์</span>
                                <input type="file" class="form-control d-none" id="image" name="image[]" accept="image/*" multiple>
                            </div>
                            <div class="form-text">ขนาดไฟล์สูงสุด: 5MB ต่อไฟล์ | ประเภทไฟล์: JPG, PNG, GIF | เลือก/ลากได้หลายไฟล์</div>
                            <div id="fileError" class="text-danger mt-2" style="display: none;"></div>
                            <div id="imagePreview" class="mt-2 row" style="display: none;"></div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="priority" class="form-label">ความสำคัญ</label>
                                <select class="form-select" id="priority" name="priority">
                                            <option value="low" <?= $announcement['priority'] === 'low' ? 'selected' : '' ?>>
                                        ทั่วไป
                                            </option>
                                            <option value="normal" <?= $announcement['priority'] === 'normal' ? 'selected' : '' ?>>
                                        ปกติ
                                            </option>
                                            <option value="high" <?= $announcement['priority'] === 'high' ? 'selected' : '' ?>>
                                        สำคัญ
                                            </option>
                                            <option value="urgent" <?= $announcement['priority'] === 'urgent' ? 'selected' : '' ?>>
                                        ด่วนมาก
                                            </option>
                                        </select>
                                </div>
                                <div class="col-md-6">
                                <label for="status" class="form-label">สถานะการเผยแพร่</label>
                                <select class="form-select" id="status" name="status">
                                            <option value="draft" <?= $announcement['status'] === 'draft' ? 'selected' : '' ?>>
                                        ร่าง (ยังไม่เผยแพร่)
                                            </option>
                                            <option value="published" <?= $announcement['status'] === 'published' ? 'selected' : '' ?>>
                                        เผยแพร่ทันที
                                            </option>
                                        </select>
                                </div>
                            </div>

                        <div class="row mb-3">
                                <div class="col-md-6">
                                <label for="start_date" class="form-label">วันที่เริ่มต้น</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                               value="<?= htmlspecialchars($announcement['start_date'] ?? '') ?>">
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            เว้นว่างถ้าไม่มีวันที่เริ่มต้น
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <label for="end_date" class="form-label">วันที่สิ้นสุด</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                               value="<?= htmlspecialchars($announcement['end_date'] ?? '') ?>">
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            เว้นว่างถ้าไม่มีวันที่สิ้นสุด
                                    </div>
                                </div>
                            </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> บันทึกการเปลี่ยนแปลง
                                                        </button>
                            <button type="button" class="btn btn-info" onclick="previewContent()">
                                <i class="fas fa-eye"></i> ดูตัวอย่าง
                                    </button>
                            <button type="button" class="btn btn-warning" onclick="clearForm()">
                                <i class="fas fa-eraser"></i> ล้างฟอร์ม
                                    </button>
                            <a href="/admin?action=announcements" class="btn btn-secondary">
                                <i class="fas fa-times"></i> ยกเลิก
                                    </a>
                                </div>
                        
                        <!-- Hidden field for embed links -->
                        <input type="hidden" id="embedLinksData" name="embed_links" value="">
                        </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Announcement Info Card -->
            <div class="card mb-4">
                    <div class="card-header">
                    <h6 class="mb-0">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            ข้อมูลประกาศ
                    </h6>
                    </div>
                    <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">วันที่สร้าง:</small><br>
                        <strong><?= date('d/m/Y H:i', strtotime($announcement['created_at'])) ?></strong>
                        </div>
                        
                    <div class="mb-3">
                        <small class="text-muted">ผู้สร้าง:</small><br>
                        <strong><?= htmlspecialchars($announcement['author_name'] ?? 'admin') ?></strong>
                        </div>
                        
                    <div class="mb-3">
                        <small class="text-muted">จำนวนรูปภาพ:</small><br>
                        <strong><?= count($announcementImages) ?> รูป</strong>
                        </div>
                        
                    <div class="mb-3">
                        <small class="text-muted">ลิงก์ดูตัวอย่าง:</small><br>
                                <a href="/?page=announcements&action=detail&id=<?= $announcement['id'] ?>" 
                                   target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-external-link-alt me-1"></i> ดูในหน้าเว็บ
                                </a>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card mb-4">
                    <div class="card-header">
                    <h6 class="mb-0">
                            <i class="fas fa-question-circle text-info me-2"></i>
                            ตัวอย่างการใช้งาน
                    </h6>
                    </div>
                    <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">
                            <i class="fas fa-link me-2"></i>
                                การใส่ลิงก์:
                            </h6>
                        <ul class="list-unstyled small">
                                <li><code>https://example.com</code></li>
                                <li><code>[คลิกที่นี่](https://example.com)</code></li>
                            <li><strong>แปะลิงก์:</strong> ใช้ช่องด้านบนเพื่อแปะลิงก์ในข้อความ</li>
                            <li><strong>แปะแบบกำหนดข้อความ:</strong> ใช้ปุ่ม "แปะแบบกำหนดข้อความ" เพื่อใส่ลิงก์พร้อมข้อความ</li>
                            </ul>
                        </div>
                        
                    <div class="mb-3">
                        <h6 class="text-success">
                            <i class="fas fa-paint-brush me-2"></i>
                                การจัดรูปแบบข้อความ:
                            </h6>
                        <ul class="list-unstyled small">
                                <li><code>**ข้อความสำคัญ**</code> = <strong>ข้อความสำคัญ</strong></li>
                                <li><code>*ข้อความเอียง*</code> = <em>ข้อความเอียง</em></li>
                                <li><code>`โค้ด`</code> = <code class="bg-light px-1 rounded">โค้ด</code></li>
                            </ul>
                        </div>
                        
                    <div class="mb-3">
                        <h6 class="text-warning">
                            <i class="fas fa-lightbulb me-2"></i>
                                ตัวอย่างประกาศ:
                            </h6>
                            <div class="example-announcement">
                                <div class="example-header">
                                    <span class="badge bg-danger me-2">ด่วนมาก</span>
                                    <strong>ประกาศสำคัญ</strong>
                                </div>
                                <div class="example-content">
                                    กรุณาเข้าไปดูรายละเอียดเพิ่มเติมที่ <a href="#" class="text-primary">เว็บไซต์หลัก</a><br>
                                    <em>ขอบคุณที่ให้ความร่วมมือ</em>
                                </div>
                            </div>
                        </div>
                    
                    <div class="mb-3">
                        <h6 class="text-info">
                            <i class="fas fa-link me-2"></i>
                            ตัวอย่างลิงก์ที่ฝังได้:
                        </h6>
                        <ul class="list-unstyled small">
                            <li><code>https://youtube.com/watch?v=...</code></li>
                            <li><code>https://facebook.com/...</code></li>
                            <li><code>https://twitter.com/...</code></li>
                            <li><code>https://instagram.com/p/...</code></li>
                            <li><code>https://example.com</code></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Priority Guide -->
                <div class="card">
                    <div class="card-header">
                    <h6 class="mb-0">
                            <i class="fas fa-flag text-warning me-2"></i>
                            คู่มือความสำคัญ
                    </h6>
                    </div>
                    <div class="card-body">
                    <div class="mb-3">
                            <div class="priority-badge priority-urgent">
                                <i class="fas fa-exclamation-triangle"></i>
                                ด่วนมาก
                            </div>
                            <small class="text-muted">สำหรับประกาศที่ต้องดำเนินการทันที</small>
                        </div>
                    <div class="mb-3">
                            <div class="priority-badge priority-high">
                                <i class="fas fa-exclamation-circle"></i>
                                สำคัญ
                            </div>
                            <small class="text-muted">สำหรับประกาศที่สำคัญแต่ไม่เร่งด่วน</small>
                        </div>
                    <div class="mb-3">
                            <div class="priority-badge priority-normal">
                                <i class="fas fa-info-circle"></i>
                                ปกติ
                            </div>
                            <small class="text-muted">สำหรับประกาศทั่วไป</small>
                        </div>
                    <div class="mb-3">
                            <div class="priority-badge priority-low">
                                <i class="fas fa-circle"></i>
                                ทั่วไป
                            </div>
                            <small class="text-muted">สำหรับประกาศที่ไม่สำคัญ</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>
                    ตัวอย่างประกาศ
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview content will be inserted here -->
            </div>
        </div>
    </div>
</div>

<style>
/* Current Images Grid */
.current-images-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.image-item {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.image-preview {
    position: relative;
    width: 100%;
    height: 150px;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-item:hover .image-overlay {
    opacity: 1;
}

/* Priority Guide */
.priority-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.priority-urgent {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
}

.priority-high {
    background: linear-gradient(135deg, #fd7e14, #e55a00);
    color: white;
}

.priority-normal {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
}

.priority-low {
    background: linear-gradient(135deg, #6c757d, #545b62);
    color: white;
}

/* Example Announcement */
.example-announcement {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    border-left: 4px solid #dc3545;
}

.example-header {
    margin-bottom: 0.5rem;
}

.example-content {
    font-size: 0.9rem;
    color: #495057;
}

/* Embed Link Styles */
.embed-link-container {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1rem;
    background-color: #f8f9fa;
}

.embed-item { 
    transition: all 0.3s ease; 
}

.embed-item:hover { 
    box-shadow: 0 2px 8px rgba(0,0,0,0.1); 
}

.embed-item iframe { 
    border-radius: 8px; 
    box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
}

.embed-item .btn { 
    border-radius: 20px; 
    padding: 0.5rem 1rem; 
}

/* Responsive iframe */
.ratio iframe { 
    max-width: 100%; 
    height: auto; 
}

/* Embed preview in modal */
.preview-announcement .embed-item { 
    margin-bottom: 1rem; 
}

.preview-announcement .embed-item:last-child { 
    margin-bottom: 0; 
}

/* Loading state for embed */
.embed-loading { 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    padding: 2rem; 
    color: #6c757d; 
}

.embed-loading .spinner-border { 
    margin-right: 0.5rem; 
}

/* Responsive */
@media (max-width: 768px) {
    .current-images-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        margin-bottom: 0.5rem;
    }
}
</style>

<script>
// File upload preview
document.getElementById('image').addEventListener('change', function(e) {
    const files = e.target.files;
    const preview = document.getElementById('imagePreview');
    const dropZone = document.getElementById('dropZone');
    const fileError = document.getElementById('fileError');
    
    if (files.length > 0) {
        preview.style.display = 'block';
        preview.innerHTML = '<h6 class="mb-3">ไฟล์ใหม่ที่เลือก:</h6>';
        
        Array.from(files).forEach((file, index) => {
            const fileDiv = document.createElement('div');
            fileDiv.className = 'col-md-6 mb-2';
            fileDiv.innerHTML = `
                <div class="alert alert-info d-flex align-items-center">
                <i class="fas fa-file-image me-2"></i>
                <div class="flex-grow-1">
                    <strong>${file.name}</strong><br>
                    <small>${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${index})">
                    <i class="fas fa-times"></i>
                </button>
                </div>
            `;
            preview.appendChild(fileDiv);
        });
        
        markFormAsChanged(); // Track form changes
    } else {
        preview.style.display = 'none';
        preview.innerHTML = '';
    }
});

function removeFile(index) {
    const input = document.getElementById('image');
    const dt = new DataTransfer();
    const { files } = input;
    
    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            dt.items.add(files[i]);
        }
    }
    
    input.files = dt.files;
    input.dispatchEvent(new Event('change'));
    markFormAsChanged(); // Track form changes
}

// Drag and drop functionality
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('image');

dropZone.addEventListener('click', () => fileInput.click());

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-primary');
    dropZone.classList.remove('border-secondary');
});

dropZone.addEventListener('dragleave', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-primary');
    dropZone.classList.add('border-secondary');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-primary');
    dropZone.classList.add('border-secondary');
    
    const files = e.dataTransfer.files;
    fileInput.files = files;
    fileInput.dispatchEvent(new Event('change'));
    markFormAsChanged(); // Track form changes
});

// Delete image function
function deleteImage(imageId, imagePath) {
    if (confirm('คุณต้องการลบรูปภาพนี้ใช่หรือไม่?\n\nการดำเนินการนี้ไม่สามารถยกเลิกได้')) {
        window.location.href = `/admin?action=announcements&sub_action=delete_image&id=${imageId}&image_path=${encodeURIComponent(imagePath)}`;
    }
}

// Date validation
document.getElementById('end_date').addEventListener('change', function() {
    const startDate = document.getElementById('start_date').value;
    const endDate = this.value;
    
    if (startDate && endDate && startDate > endDate) {
        alert('วันที่สิ้นสุดต้องไม่น้อยกว่าวันที่เริ่มต้น');
        this.value = '';
    }
});

document.getElementById('start_date').addEventListener('change', function() {
    const startDate = this.value;
    const endDate = document.getElementById('end_date').value;
    
    if (startDate && endDate && startDate > endDate) {
        document.getElementById('end_date').value = '';
    }
});

// Preview functionality
function previewContent() {
    const title = document.getElementById('title').value;
    const content = document.getElementById('content').value;
    const priority = document.getElementById('priority').value;
    const status = document.getElementById('status').value;
    
    if (!title || !content) {
        alert('กรุณากรอกหัวข้อและเนื้อหาก่อนดูตัวอย่าง');
        return;
    }
    
    const priorityText = {
        'urgent': 'ด่วนมาก',
        'high': 'สำคัญ',
        'normal': 'ปกติ',
        'low': 'ทั่วไป'
    };
    
    const priorityColor = {
        'urgent': '#dc3545',
        'high': '#fd7e14',
        'normal': '#007bff',
        'low': '#6c757d'
    };
    
    const processedContent = processContentLinks(content);
    
    // Generate embed links HTML
    let embedLinksHtml = '';
    if (embedLinks.length > 0) {
        embedLinksHtml = '<div class="mt-3"><h6>ลิงก์ที่ฝัง:</h6>';
        embedLinks.forEach(link => {
            embedLinksHtml += `<div class="embed-item mb-2">${generateEmbedContent(link)}</div>`;
        });
        embedLinksHtml += '</div>';
    }
    
    const previewHtml = `
        <div class="preview-announcement">
            <div class="preview-header mb-3">
                <span class="badge me-2" style="background-color: ${priorityColor[priority]}; color: white;">
                    ${priorityText[priority]}
                </span>
                <span class="badge bg-${status === 'published' ? 'success' : 'secondary'}">
                    ${status === 'published' ? 'เผยแพร่' : 'ร่าง'}
                </span>
            </div>
            <h4 class="preview-title mb-3">${title}</h4>
            <div class="preview-content">
                ${processedContent}
                ${embedLinksHtml}
            </div>
        </div>
    `;
    
    // Create modal for preview
    const modalHtml = `
        <div class="modal fade" id="previewModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-eye me-2"></i>
                            ตัวอย่างประกาศ
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ${previewHtml}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('previewModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add new modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
}

// Process content links (same as in AnnouncementModel)
function processContentLinks(content) {
    // Convert URLs to links
    content = content.replace(
        /(https?:\/\/[^\s]+)/gi,
        '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-primary">$1</a>'
    );
    
    // Convert [text](url) to links
    content = content.replace(
        /\[([^\]]+)\]\(([^)]+)\)/gi,
        '<a href="$2" target="_blank" rel="noopener noreferrer" class="text-primary">$1</a>'
    );
    
    // Convert **text** to bold
    content = content.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
    
    // Convert *text* to italic
    content = content.replace(/\*([^*]+)\*/g, '<em>$1</em>');
    
    // Convert `text` to code
    content = content.replace(/`([^`]+)`/g, '<code class="bg-light px-1 rounded">$1</code>');
    
    // Convert line breaks
    content = content.replace(/\n/g, '<br>');
    
    return content;
}

// Form validation
document.getElementById('announcementForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const content = document.getElementById('content').value.trim();
    
    if (!title) {
        e.preventDefault();
        alert('กรุณากรอกหัวข้อประกาศ');
        document.getElementById('title').focus();
        return;
    }
    
    if (!content) {
        e.preventDefault();
        alert('กรุณากรอกเนื้อหาประกาศ');
        document.getElementById('content').focus();
        return;
    }
});

// Clear form function
function clearForm() {
    // Check if form has any changes
    const title = document.getElementById('title').value.trim();
    const content = document.getElementById('content').value.trim();
    const hasFiles = document.getElementById('image').files.length > 0;
    const hasEmbedLinks = embedLinks.length > 0;
    
    // Get original values from PHP
    const originalTitle = '<?= htmlspecialchars($announcement['title']) ?>';
    const originalContent = '<?= htmlspecialchars($announcement['content']) ?>';
    
    // Check if there are actual changes
    const hasChanges = (title !== originalTitle || content !== originalContent || hasFiles || hasEmbedLinks);
    
    if (hasChanges) {
        if (!confirm('คุณต้องการล้างฟอร์มทั้งหมดหรือไม่? ข้อมูลที่แก้ไขไว้จะหายไป')) {
            return;
        }
    }
    
    // Reset form to original values
    document.getElementById('title').value = originalTitle;
    document.getElementById('content').value = originalContent;
    document.getElementById('priority').value = '<?= $announcement['priority'] ?>';
    document.getElementById('status').value = '<?= $announcement['status'] ?>';
    document.getElementById('start_date').value = '<?= htmlspecialchars($announcement['start_date'] ?? '') ?>';
    document.getElementById('end_date').value = '<?= htmlspecialchars($announcement['end_date'] ?? '') ?>';
    
    // Clear file input
    document.getElementById('image').value = '';
    
    // Clear file preview
    document.getElementById('imagePreview').innerHTML = '';
    document.getElementById('imagePreview').style.display = 'none';
    
    // Clear embed links
    embedLinks = [];
    updateEmbedPreview();
    
    // Clear embed URL input
    document.getElementById('embedUrl').value = '';
    
    // Clear paste URL input
    document.getElementById('pasteUrl').value = '';
    
    // Clear hidden embed links data
    document.getElementById('embedLinksData').value = '';
    
    // Reset form change flag
    formHasChanges = false;
    
    // Focus on title field
    document.getElementById('title').focus();
    
    // Show success message
    showNotification('ล้างฟอร์มเรียบร้อยแล้ว', 'success');
}

// Form change detection
let formHasChanges = false;

function markFormAsChanged() {
    formHasChanges = true;
}

// Track form changes
document.getElementById('title').addEventListener('input', markFormAsChanged);
document.getElementById('content').addEventListener('input', markFormAsChanged);
document.getElementById('start_date').addEventListener('change', markFormAsChanged);
document.getElementById('end_date').addEventListener('change', markFormAsChanged);
document.getElementById('priority').addEventListener('change', markFormAsChanged);
document.getElementById('status').addEventListener('change', markFormAsChanged);
document.getElementById('image').addEventListener('change', markFormAsChanged);
document.getElementById('embedUrl').addEventListener('input', markFormAsChanged);
document.getElementById('pasteUrl').addEventListener('input', markFormAsChanged);

// Warn before leaving page if form has changes
window.addEventListener('beforeunload', function(e) {
    if (formHasChanges) {
        e.preventDefault();
        e.returnValue = 'คุณมีข้อมูลที่ยังไม่ได้บันทึก ต้องการออกจากหน้านี้หรือไม่?';
        return e.returnValue;
    }
});

// Reset form change flag when form is submitted
document.getElementById('announcementForm').addEventListener('submit', function() {
    // Set embed links data before submit
    document.getElementById('embedLinksData').value = JSON.stringify(embedLinks);
    formHasChanges = false;
});

// Notification function
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

// Embed Link functionality
let embedLinks = [];

function addEmbedLink() {
    const urlInput = document.getElementById('embedUrl');
    const url = urlInput.value.trim();
    
    if (!url) {
        alert('กรุณาใส่ลิงก์ก่อน');
        return;
    }
    
    if (!isValidUrl(url)) {
        alert('กรุณาใส่ลิงก์ที่ถูกต้อง (ต้องขึ้นต้นด้วย http:// หรือ https://)');
        return;
    }
    
    const embedData = processEmbedUrl(url);
    if (embedData) {
        embedLinks.push(embedData);
        updateEmbedPreview();
        urlInput.value = '';
        markFormAsChanged(); // Track form changes
    } else {
        alert('ไม่สามารถประมวลผลลิงก์นี้ได้ กรุณาลองลิงก์อื่น');
    }
}

function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (_) {
        return false;
    }
}

function processEmbedUrl(url) {
    const urlObj = new URL(url);
    const domain = urlObj.hostname.toLowerCase();
    
    // YouTube
    if (domain.includes('youtube.com') || domain.includes('youtu.be')) {
        const videoId = extractYouTubeId(url);
        if (videoId) {
            return {
                type: 'youtube',
                url: url,
                embedUrl: `https://www.youtube.com/embed/${videoId}`,
                title: `YouTube Video: ${videoId}`
            };
        }
    }
    
    // Facebook
    if (domain.includes('facebook.com')) {
        return {
            type: 'facebook',
            url: url,
            embedUrl: `https://www.facebook.com/plugins/post.php?href=${encodeURIComponent(url)}&width=500&show_text=true&height=600&appId`,
            title: 'Facebook Post'
        };
    }
    
    // Twitter
    if (domain.includes('twitter.com') || domain.includes('x.com')) {
        const tweetId = extractTwitterId(url);
        if (tweetId) {
            return {
                type: 'twitter',
                url: url,
                tweetId: tweetId,
                title: `Twitter Post: ${tweetId}`
            };
        }
    }
    
    // Instagram
    if (domain.includes('instagram.com')) {
        const postId = extractInstagramId(url);
        if (postId) {
            return {
                type: 'instagram',
                url: url,
                embedUrl: `https://www.instagram.com/p/${postId}/embed/`,
                title: `Instagram Post: ${postId}`
            };
        }
    }
    
    // General link
    return {
        type: 'general',
        url: url,
        title: getDomainName(url),
        favicon: `https://www.google.com/s2/favicons?domain=${urlObj.hostname}&sz=32`
    };
}

function extractYouTubeId(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : null;
}

function extractTwitterId(url) {
    const regExp = /twitter\.com\/\w+\/status\/(\d+)/;
    const match = url.match(regExp);
    return match ? match[1] : null;
}

function extractInstagramId(url) {
    const regExp = /instagram\.com\/p\/([^\/]+)/;
    const match = url.match(regExp);
    return match ? match[1] : null;
}

function getDomainName(url) {
    try {
        return new URL(url).hostname.replace('www.', '');
    } catch {
        return 'Unknown';
    }
}

function updateEmbedPreview() {
    const preview = document.getElementById('embedPreview');
    if (embedLinks.length === 0) {
        preview.innerHTML = '';
        return;
    }
    
    preview.innerHTML = '<h6 class="mb-3">ลิงก์ที่ฝัง:</h6>';
    
    embedLinks.forEach((link, index) => {
        const embedDiv = document.createElement('div');
        embedDiv.className = 'embed-item alert alert-light border mb-2';
        embedDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center mb-2">
                        <i class="${getEmbedIcon(link.type)} me-2"></i>
                        <strong>${link.title}</strong>
                    </div>
                    <small class="text-muted d-block mb-2">${link.url}</small>
                    <div class="embed-content">
                        ${generateEmbedContent(link)}
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeEmbedLink(${index})">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        preview.appendChild(embedDiv);
    });
}

function getEmbedIcon(type) {
    const icons = {
        'youtube': 'fab fa-youtube text-danger',
        'facebook': 'fab fa-facebook text-primary',
        'twitter': 'fab fa-twitter text-info',
        'instagram': 'fab fa-instagram text-warning',
        'general': 'fas fa-link text-secondary'
    };
    return icons[type] || 'fas fa-link';
}

function generateEmbedContent(link) {
    switch (link.type) {
        case 'youtube':
        case 'facebook':
        case 'instagram':
            return `<div class="ratio ratio-16x9">
                <iframe src="${link.embedUrl}" frameborder="0" allowfullscreen></iframe>
            </div>`;
        
        case 'twitter':
            return `<a href="${link.url}" target="_blank" class="btn btn-info btn-sm">
                <i class="fab fa-twitter me-1"></i> ดูทวีต
            </a>`;
        
        case 'general':
            return `<a href="${link.url}" target="_blank" class="btn btn-outline-primary btn-sm">
                <img src="${link.favicon}" alt="favicon" class="me-1" style="width: 16px; height: 16px;">
                เปิดลิงก์
            </a>`;
        
        default:
            return `<a href="${link.url}" target="_blank" class="btn btn-outline-secondary btn-sm">เปิดลิงก์</a>`;
    }
}

function removeEmbedLink(index) {
    embedLinks.splice(index, 1);
    updateEmbedPreview();
    markFormAsChanged(); // Track form changes
}

// Auto-add embed link on Enter key
document.getElementById('embedUrl').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addEmbedLink();
    }
});

// Auto-add embed link on paste
document.getElementById('embedUrl').addEventListener('paste', function(e) {
    setTimeout(() => {
        const url = this.value.trim();
        if (url && isValidUrl(url)) {
            addEmbedLink();
        }
    }, 100);
});

// Auto-paste link to content when pasting URL
document.getElementById('pasteUrl').addEventListener('paste', function(e) {
    setTimeout(() => {
        const url = this.value.trim();
        if (url && isValidUrl(url)) {
            pasteLinkToContent();
        }
    }, 100);
});

// Auto-paste link to content on Enter key
document.getElementById('pasteUrl').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        pasteLinkToContent();
    }
});

// Paste link functionality
function pasteLinkToContent() {
    const urlInput = document.getElementById('pasteUrl');
    const url = urlInput.value.trim();

    if (!url) {
        alert('กรุณาใส่ลิงก์ที่ต้องการแปะก่อน');
        return;
    }

    if (!isValidUrl(url)) {
        alert('กรุณาใส่ลิงก์ที่ถูกต้อง (ต้องขึ้นต้นด้วย http:// หรือ https://)');
        return;
    }

    const contentTextarea = document.getElementById('content');
    const start = contentTextarea.selectionStart;
    const end = contentTextarea.selectionEnd;
    
    // Insert the URL at cursor position
    const beforeText = contentTextarea.value.substring(0, start);
    const afterText = contentTextarea.value.substring(end);
    const newText = beforeText + url + afterText;
    
    contentTextarea.value = newText;
    
    // Set cursor position after the inserted URL
    const newCursorPos = start + url.length;
    contentTextarea.focus();
    contentTextarea.setSelectionRange(newCursorPos, newCursorPos);
    
    // Clear the URL input
    urlInput.value = '';
    
    // Show success message
    showNotification('แปะลิงก์เรียบร้อยแล้ว', 'success');
    
    markFormAsChanged(); // Track form changes
}

// Custom Link Dialog
function showCustomLinkDialog() {
    const customLinkModal = document.getElementById('customLinkModal');
    if (!customLinkModal) {
        // Create modal HTML if it doesn't exist
        const modalHtml = `
            <div class="modal fade" id="customLinkModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">แปะลิงก์แบบกำหนดข้อความ</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="customLinkText" class="form-label">ข้อความที่ต้องการแปะ (ถ้าไม่มีจะใช้ลิงก์เป็นข้อความ)</label>
                                <input type="text" class="form-control" id="customLinkText" placeholder="ตัวอย่าง: ดูรายละเอียดที่นี่">
                            </div>
                            <div class="mb-3">
                                <label for="customLinkUrl" class="form-label">ลิงก์ที่ต้องการแปะ</label>
                                <input type="url" class="form-control" id="customLinkUrl" placeholder="https://example.com" required>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                <button type="button" class="btn btn-primary ms-2" onclick="addCustomLink()">แปะลิงก์</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);
    }
    const modal = new bootstrap.Modal(document.getElementById('customLinkModal'));
    modal.show();
}

function addCustomLink() {
    const textInput = document.getElementById('customLinkText');
    const urlInput = document.getElementById('customLinkUrl');
    const url = urlInput.value.trim();
    const text = textInput.value.trim() || url; // Use URL as text if no text is provided

    if (!url) {
        alert('กรุณาใส่ลิงก์ที่ต้องการแปะ');
        return;
    }

    if (!isValidUrl(url)) {
        alert('กรุณาใส่ลิงก์ที่ถูกต้อง (ต้องขึ้นต้นด้วย http:// หรือ https://)');
        return;
    }

    const contentTextarea = document.getElementById('content');
    const start = contentTextarea.selectionStart;
    const end = contentTextarea.selectionEnd;
    
    // Create the markdown link format: [text](url)
    const markdownLink = `[${text}](${url})`;
    
    // Insert the markdown link at cursor position
    const beforeText = contentTextarea.value.substring(0, start);
    const afterText = contentTextarea.value.substring(end);
    const newText = beforeText + markdownLink + afterText;
    
    contentTextarea.value = newText;
    
    // Set cursor position after the inserted link
    const newCursorPos = start + markdownLink.length;
    contentTextarea.focus();
    contentTextarea.setSelectionRange(newCursorPos, newCursorPos);
    
    // Clear inputs
    textInput.value = '';
    urlInput.value = '';
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('customLinkModal'));
    if (modal) {
        modal.hide();
    }
    
    // Show success message
    showNotification('แปะลิงก์เรียบร้อยแล้ว', 'success');
    
    markFormAsChanged(); // Track form changes
}
</script> 