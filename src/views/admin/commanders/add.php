<?php include SRC_PATH . '/views/admin/header.php'; ?>

<div class="mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin?action=dashboard">แดชบอร์ด</a></li>
            <li class="breadcrumb-item"><a href="/admin?action=commanders">ผู้บังคับบัญชา</a></li>
            <li class="breadcrumb-item active">เพิ่มผู้บังคับบัญชา</li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-plus text-primary"></i> เพิ่มผู้บังคับบัญชา</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error) && $error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" enctype="multipart/form-data" id="commanderForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="group" class="form-label">กลุ่ม/ฝ่าย <span class="text-danger">*</span> <i class="fas fa-info-circle" title="เลือกกลุ่ม/ฝ่ายที่สังกัด"></i></label>
                                <select class="form-select" id="group" name="group" required>
                                    <option value="">เลือกกลุ่ม/ฝ่าย</option>
                                    <option value="ศฝร.ภ.8">ศฝร.ภ.8</option>
                                    <option value="ฝ่ายอำนวยการ">ฝ่ายอำนวยการ</option>
                                    <option value="ฝ่ายบริการการศึกษา">ฝ่ายบริการการศึกษา</option>
                                    <option value="ฝ่ายปกครองและการฝึก">ฝ่ายปกครองและการฝึก</option>
                                    <option value="กลุ่มงานอาจารย์">กลุ่มงานอาจารย์</option>
                                </select>
                                <div class="form-text">เลือกกลุ่ม/ฝ่ายที่ต้องการ</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="position_name" class="form-label">ตำแหน่ง <span class="text-danger">*</span> <i class="fas fa-info-circle" title="กรอกชื่อตำแหน่ง"></i></label>
                                <input type="text" class="form-control" id="position_name" name="position_name" value="<?= htmlspecialchars($_POST['position_name'] ?? '') ?>" placeholder="ระบุตำแหน่ง เช่น ผู้บังคับบัญชา ฝ่ายอำนวยการ ศูนย์ฝึกอบรมตำรวจภูธรภาค 8" required autocomplete="off">
                                <div class="form-text">กรอกชื่อตำแหน่งที่ต้องการ</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rank_id" class="form-label">ยศ</label>
                                <select class="form-select" id="rank_id" name="rank_id">
                                    <option value="">เลือกยศ</option>
                                    <?php if (!empty($ranks)): ?>
                                        <?php foreach ($ranks as $rank): ?>
                                            <option value="<?= $rank['id'] ?>" 
                                                    <?= (($_POST['rank_id'] ?? '') == $rank['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($rank['rank_name_short']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>ไม่มีข้อมูลยศ</option>
                                    <?php endif; ?>
                                </select>
                                <div class="form-text">
                                    เลือกยศที่เหมาะสม
                                    <?php if (empty($ranks)): ?>
                                        <br><a href="/admin?action=ranks&sub_action=add" class="btn btn-sm btn-primary mt-1">เพิ่มยศใหม่</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>"
                                       placeholder="ระบุชื่อและนามสกุล" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="qualifications" class="form-label">คุณวุฒิ <i class="fas fa-info-circle" title="ระบุคุณวุฒิการศึกษาและหลักสูตรที่ผ่าน"></i></label>
                            <textarea class="form-control" id="qualifications" name="qualifications" rows="2"
                                      placeholder="ระบุคุณวุฒิการศึกษาและหลักสูตรที่ผ่าน"><?= htmlspecialchars($_POST['qualifications'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="previous_positions" class="form-label">เคยดำรงตำแหน่ง <i class="fas fa-info-circle" title="ระบุตำแหน่งที่เคยดำรงมาก่อน"></i></label>
                            <textarea class="form-control" id="previous_positions" name="previous_positions" rows="2"
                                      placeholder="ระบุตำแหน่งที่เคยดำรงมาก่อน"><?= htmlspecialchars($_POST['previous_positions'] ?? '') ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="work_phone" class="form-label">โทรศัพท์ที่ทำงาน</label>
                                <input type="text" class="form-control" id="work_phone" name="work_phone"
                                       value="<?= htmlspecialchars($_POST['work_phone'] ?? '') ?>"
                                       placeholder="เช่น 077-123456 ต่อ 101">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">E-mail Address</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                       placeholder="เช่น commander@school8.police.go.th">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="photo" class="form-label">รูปภาพ</label>
                                <input type="file" class="form-control" id="photo" name="photo" 
                                       accept="image/*" onchange="validateFile(this)">
                                <div class="form-text">ขนาดไฟล์สูงสุด: 5MB | ประเภทไฟล์: JPG, PNG</div>
                                <div id="fileError" class="text-danger mt-2" style="display: none;"></div>
                                <div id="imagePreview" class="mt-2" style="display: none;">
                                    <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 300px; border: 4px solid #ffd700;">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> บันทึกข้อมูล
                            </button>
                            <a href="/admin?action=commanders" class="btn btn-secondary">
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
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> คำแนะนำ</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            ใช้รูปภาพที่มีความชัดเจน
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            ระบุข้อมูลให้ครบถ้วนและถูกต้อง
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            ตรวจสอบการสะกดชื่อและยศ
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info text-info"></i> 
                            ช่องที่มี * คือช่องที่จำเป็นต้องกรอก
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Preview รูปภาพ
function validateFile(input) {
    const file = input.files[0];
    const errorDiv = document.getElementById('fileError');
    const previewDiv = document.getElementById('imagePreview');
    const previewImg = document.getElementById('preview');
    errorDiv.style.display = 'none';
    previewDiv.style.display = 'none';
    if (file) {
        if (!['image/jpeg', 'image/png'].includes(file.type)) {
            errorDiv.textContent = 'อนุญาตเฉพาะไฟล์ JPG, PNG';
            errorDiv.style.display = 'block';
            input.value = '';
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            errorDiv.textContent = 'ขนาดไฟล์ต้องไม่เกิน 5MB';
            errorDiv.style.display = 'block';
            input.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewDiv.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}
</script>

<?php include SRC_PATH . '/views/admin/footer.php'; ?>
