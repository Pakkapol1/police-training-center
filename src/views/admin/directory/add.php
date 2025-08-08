<?php include SRC_PATH . '/views/admin/header.php'; ?>

<div class="mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin?action=dashboard">แดชบอร์ด</a></li>
            <li class="breadcrumb-item">
                <a href="/admin?action=directory&type=<?= $type ?>">
                    <?= $type === 'supervisors' ? 'ผู้กำกับการ' : 'ผู้บังคับการ' ?>
                </a>
            </li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($title) ?></li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-plus text-primary"></i> <?= htmlspecialchars($title) ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error) && $error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" id="directoryForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="order_number" class="form-label">
                                    ลำดับ <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control" 
                                       id="order_number" 
                                       name="order_number" 
                                       value="<?= isset($_POST['order_number']) ? htmlspecialchars($_POST['order_number']) : $nextOrder ?>" 
                                       min="1" 
                                       required>
                                <div class="form-text">หมายเลขลำดับการแสดงผล</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="rank" class="form-label">
                                    ยศ <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="rank" 
                                       name="rank" 
                                       value="<?= htmlspecialchars($_POST['rank'] ?? '') ?>" 
                                       placeholder="เช่น พ.อ.อ., พล.ต.ต." 
                                       required>
                                <div class="form-text">ยศของบุคลากร</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">
                                    ชื่อ <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="first_name" 
                                       name="first_name" 
                                       value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>" 
                                       required>
                                <div class="form-text">ชื่อจริง</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">
                                    นามสกุล <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="last_name" 
                                       name="last_name" 
                                       value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>" 
                                       required>
                                <div class="form-text">นามสกุล</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">
                                ระยะเวลาการดำรงตำแหน่ง
                            </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">
                                        วันที่เริ่มต้น
                                    </label>
                                    <input type="text" 
                                           class="form-control thai-datepicker" 
                                           id="start_date" 
                                           name="start_date" 
                                           value="<?= htmlspecialchars($_POST['start_date'] ?? '') ?>"
                                           placeholder="วว/ดด/พศศศ">
                                    <div class="form-text">วันที่เริ่มดำรงตำแหน่ง (ปี พ.ศ.)</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label">
                                        วันที่สิ้นสุด
                                    </label>
                                    <input type="text" 
                                           class="form-control thai-datepicker" 
                                           id="end_date" 
                                           name="end_date" 
                                           value="<?= htmlspecialchars($_POST['end_date'] ?? '') ?>"
                                           placeholder="วว/ดด/พศศศ">
                                    <div class="form-text">วันที่สิ้นสุดการดำรงตำแหน่ง (ปี พ.ศ.)</div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_current" 
                                               name="is_current" 
                                               value="1" 
                                               <?= isset($_POST['is_current']) && $_POST['is_current'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_current">
                                            <strong>ปัจจุบัน</strong> - กำลังดำรงตำแหน่งอยู่
                                        </label>
                                        <div class="form-text">เลือกถ้าบุคลากรยังคงดำรงตำแหน่งอยู่ (จะทำให้ช่องวันที่สิ้นสุดเป็นช่องเลือก)</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="/admin?action=directory&type=<?= $type ?>" 
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> ย้อนกลับ
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> บันทึกข้อมูล
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle text-info"></i> คำแนะนำ
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            กรอกข้อมูลให้ครบถ้วน
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            ลำดับจะใช้สำหรับการจัดเรียงข้อมูล
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            ใส่ยศให้ถูกต้อง เช่น พ.อ.อ.
                        </li>
                        <li>
                            <i class="fas fa-check text-success"></i> 
                            เลือกวันที่เริ่มต้นและวันที่สิ้นสุดให้ถูกต้อง (ปี พ.ศ.)
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('directoryForm').addEventListener('submit', function(e) {
    // ตรวจสอบข้อมูลก่อนส่ง
    const requiredFields = ['order_number', 'rank', 'first_name', 'last_name'];
    let isValid = true;
    
    requiredFields.forEach(field => {
        const input = document.getElementById(field);
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    // ตรวจสอบวันที่
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const isCurrent = document.getElementById('is_current').checked;
    
    if (startDate.thaiDatePicker && endDate.thaiDatePicker) {
        const startISO = startDate.thaiDatePicker.getISODate();
        const endISO = endDate.thaiDatePicker.getISODate();
        
        // ถ้าเลือกปัจจุบัน ไม่ต้องตรวจสอบวันที่สิ้นสุด
        if (!isCurrent && startISO && endISO) {
            if (new Date(startISO) > new Date(endISO)) {
                alert('วันที่เริ่มต้นต้องไม่เกินวันที่สิ้นสุด');
                startDate.classList.add('is-invalid');
                endDate.classList.add('is-invalid');
                isValid = false;
            } else {
                startDate.classList.remove('is-invalid');
                endDate.classList.remove('is-invalid');
            }
        }
    }
    
    if (!isValid) {
        e.preventDefault();
        alert('กรุณาตรวจสอบข้อมูลให้ถูกต้อง');
    }
});

// เพิ่ม validation แบบ real-time
document.getElementById('start_date').addEventListener('change', validateDates);
document.getElementById('end_date').addEventListener('change', validateDates);

// จัดการ checkbox ปัจจุบัน
document.getElementById('is_current').addEventListener('change', function() {
    const endDateInput = document.getElementById('end_date');
    const endDateLabel = endDateInput.parentElement.querySelector('label');
    
    if (this.checked) {
        endDateInput.disabled = true;
        endDateInput.value = '';
        endDateInput.classList.remove('is-invalid');
        endDateLabel.classList.add('text-muted');
        endDateInput.parentElement.querySelector('.form-text').classList.add('text-muted');
    } else {
        endDateInput.disabled = false;
        endDateLabel.classList.remove('text-muted');
        endDateInput.parentElement.querySelector('.form-text').classList.remove('text-muted');
    }
});

// เรียกใช้ฟังก์ชันเมื่อโหลดหน้าเพื่อตั้งค่าเริ่มต้น
document.addEventListener('DOMContentLoaded', function() {
    const isCurrentCheckbox = document.getElementById('is_current');
    if (isCurrentCheckbox.checked) {
        const endDateInput = document.getElementById('end_date');
        const endDateLabel = endDateInput.parentElement.querySelector('label');
        endDateInput.disabled = true;
        endDateLabel.classList.add('text-muted');
        endDateInput.parentElement.querySelector('.form-text').classList.add('text-muted');
    }
});

function validateDates() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const isCurrent = document.getElementById('is_current').checked;
    
    // ถ้าเลือกปัจจุบัน ให้ข้ามการตรวจสอบวันที่สิ้นสุด
    if (isCurrent) {
        startDate.classList.remove('is-invalid');
        endDate.classList.remove('is-invalid');
        return;
    }
    
    if (startDate.thaiDatePicker && endDate.thaiDatePicker) {
        const startISO = startDate.thaiDatePicker.getISODate();
        const endISO = endDate.thaiDatePicker.getISODate();
        
        if (startISO && endISO) {
            if (new Date(startISO) > new Date(endISO)) {
                startDate.classList.add('is-invalid');
                endDate.classList.add('is-invalid');
            } else {
                startDate.classList.remove('is-invalid');
                endDate.classList.remove('is-invalid');
            }
        }
    }
}

// เพิ่มฟังก์ชันแปลงวันที่ก่อนส่งฟอร์ม
document.getElementById('directoryForm').addEventListener('submit', function(e) {
    // ก่อนส่งฟอร์ม ให้แปลงวันที่จากรูปแบบไทยเป็น ISO
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    if (startDateInput.thaiDatePicker) {
        const startISO = startDateInput.thaiDatePicker.getISODate();
        if (startISO) {
            // สร้าง hidden input สำหรับส่งค่า ISO date
            const hiddenStart = document.createElement('input');
            hiddenStart.type = 'hidden';
            hiddenStart.name = 'start_date';
            hiddenStart.value = startISO;
            this.appendChild(hiddenStart);
            
            // ลบ name จาก input หลักเพื่อไม่ให้ส่งค่าซ้ำ
            startDateInput.removeAttribute('name');
        }
    }
    
    if (endDateInput.thaiDatePicker) {
        const endISO = endDateInput.thaiDatePicker.getISODate();
        if (endISO) {
            // สร้าง hidden input สำหรับส่งค่า ISO date
            const hiddenEnd = document.createElement('input');
            hiddenEnd.type = 'hidden';
            hiddenEnd.name = 'end_date';
            hiddenEnd.value = endISO;
            this.appendChild(hiddenEnd);
            
            // ลบ name จาก input หลักเพื่อไม่ให้ส่งค่าซ้ำ
            endDateInput.removeAttribute('name');
        }
    }
    
    // จัดการ checkbox ปัจจุบัน
    const isCurrentCheckbox = document.getElementById('is_current');
    if (isCurrentCheckbox.checked) {
        // ถ้าเลือกปัจจุบัน ให้ล้างค่า end_date
        const hiddenEnd = document.createElement('input');
        hiddenEnd.type = 'hidden';
        hiddenEnd.name = 'end_date';
        hiddenEnd.value = '';
        this.appendChild(hiddenEnd);
        
        // ลบ name จาก input หลักเพื่อไม่ให้ส่งค่าซ้ำ
        endDateInput.removeAttribute('name');
    }
});
</script>

<!-- โหลด Thai Date Picker -->
<script src="/assets/js/thai-datepicker.js"></script>

<?php include SRC_PATH . '/views/admin/footer.php'; ?> 