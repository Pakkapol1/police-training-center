<?php include SRC_PATH . '/views/admin/header.php'; ?>

<div class="mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin?action=dashboard">แดชบอร์ด</a></li>
            <li class="breadcrumb-item"><a href="/admin?action=news">ข่าวสาร</a></li>
            <li class="breadcrumb-item active">แก้ไขข่าว</li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit text-warning"></i> แก้ไขข่าว</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error) && $error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" enctype="multipart/form-data" id="newsForm">
                        <div class="mb-3">
                            <label for="title" class="form-label">หัวข้อข่าว <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required maxlength="255" value="<?= htmlspecialchars($news['title'] ?? '') ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">เนื้อหา <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="10" required><?= htmlspecialchars($news['content'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">รูปภาพประกอบ</label>
                            <div id="dropZone" class="border border-2 border-primary rounded p-3 mb-2 text-center bg-light" style="cursor:pointer; min-height: 80px;">
                                <i class="fas fa-cloud-upload-alt fa-2x text-primary"></i><br>
                                <span>ลากไฟล์รูปภาพมาวางที่นี่ หรือคลิกเพื่อเลือกไฟล์</span>
                                <input type="file" class="form-control d-none" id="image" name="image[]" accept="image/*" multiple>
                            </div>
                            <div class="form-text">ขนาดไฟล์สูงสุด: 10MB ต่อไฟล์ | ประเภทไฟล์: JPG, PNG, GIF | เลือก/ลากได้หลายไฟล์</div>
                            <div id="fileError" class="text-danger mt-2" style="display: none;"></div>
                            <div id="imagePreview" class="mt-2 row" style="display: none;"></div>
                            <?php if (!empty($news['id'])): ?>
                                <?php $images = (new \NewsModel())->getNewsImages($news['id']); ?>
                                <?php if (!empty($images)): ?>
                                    <div class="mt-2">
                                        <div class="mb-1">รูปภาพปัจจุบัน:</div>
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php foreach ($images as $img): ?>
                                                <div class="position-relative">
                                                    <img src="<?= htmlspecialchars($img['image_path']) ?>" class="img-thumbnail" style="max-width: 240px; max-height: 180px;">
                                                    <a href="/admin?action=delete_news_image&id=<?= $img['id'] ?>&news_id=<?= $news['id'] ?>" onclick="return confirm('ลบรูปนี้?')" class="btn btn-sm btn-danger position-absolute top-0 end-0" style="z-index:2;"><i class='fas fa-times'></i></a>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">หมวดหมู่ <span class="text-danger">*</span></label>
                            <?php
                            // สมมติว่าเก็บ category เป็น string เดียว (เช่น แยกด้วย ,)
                            $selectedCategories = [];
                            if (!empty($news['category'])) {
                                if (is_array($news['category'])) {
                                    $selectedCategories = $news['category'];
                                } else {
                                    $selectedCategories = array_map('trim', explode(',', $news['category']));
                                }
                            }
                            $allCategories = [
                                'ข่าวประชาสัมพันธ์',
                                'ข่าวกิจกรรม',
                                'ข่าวการฝึกปฏิบัติงาน',
                                'ผลการดำเนินงาน',
                                'ข่าวภารกิจผู้บังคับบัญชา',
                                'งานอำนวยการ',
                                'อื่นๆ'
                            ];
                            ?>
                            <?php foreach ($allCategories as $i => $cat): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="category[]" id="cat<?= $i+1 ?>" value="<?= $cat ?>" <?= in_array($cat, $selectedCategories) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="cat<?= $i+1 ?>"><?= $cat ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mb-2">
                            <label class="form-label">หมวดหมู่ที่เลือก:</label>
                            <?php foreach ($selectedCategories as $cat): ?>
                                <span class="badge bg-info text-dark me-1 mb-1"> <?= htmlspecialchars($cat) ?> </span>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">สถานะการเผยแพร่</label>
                            <select class="form-select" id="status" name="status">
                                <option value="draft" <?= (isset($news['status']) && $news['status'] == 'draft') ? 'selected' : '' ?>>ร่าง (ยังไม่เผยแพร่)</option>
                                <option value="published" <?= (isset($news['status']) && $news['status'] == 'published') ? 'selected' : '' ?>>เผยแพร่ทันที</option>
                            </select>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning" id="submitBtn">
                                <i class="fas fa-save"></i> บันทึกการแก้ไข
                            </button>
                            <a href="/admin?action=news" class="btn btn-secondary">
                                <i class="fas fa-times"></i> ยกเลิก
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-lightbulb"></i> คำแนะนำ</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> ใช้หัวข้อที่ชัดเจนและน่าสนใจ
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> เขียนเนื้อหาที่มีประโยชน์และถูกต้อง
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> ใช้รูปภาพที่เกี่ยวข้องกับเนื้อหา
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> ตรวจสอบการสะกดและไวยากรณ์
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Drag & Drop + Preview (no remove for reliability)
const dropZone = document.getElementById('dropZone');
let fileInput = document.getElementById('image');
const previewDiv = document.getElementById('imagePreview');
const errorDiv = document.getElementById('fileError');
const submitBtn = document.getElementById('submitBtn');

dropZone.addEventListener('click', () => fileInput.click());
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('bg-primary', 'text-white'); });
dropZone.addEventListener('dragleave', e => { e.preventDefault(); dropZone.classList.remove('bg-primary', 'text-white'); });
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('bg-primary', 'text-white');
    fileInput.files = e.dataTransfer.files;
    renderPreview();
});
fileInput.addEventListener('change', renderPreview);

function renderPreview() {
    previewDiv.innerHTML = '';
    const files = fileInput.files;
    if (!files || files.length === 0) { previewDiv.style.display = 'none'; return; }
    Array.from(files).forEach((file) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const col = document.createElement('div');
            col.className = 'col-auto mb-2 position-relative';
            col.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-width: 120px; max-height: 120px;">`;
            previewDiv.appendChild(col);
            previewDiv.style.display = 'flex';
        };
        reader.readAsDataURL(file);
    });
}

document.getElementById('newsForm').addEventListener('submit', function() {
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> กำลังบันทึก...';
    submitBtn.disabled = true;
});
</script>

<?php include SRC_PATH . '/views/admin/footer.php'; ?> 