<?php include SRC_PATH . '/views/admin/header.php'; ?>

<div class="mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin?action=dashboard">แดชบอร์ด</a></li>
            <li class="breadcrumb-item"><a href="/admin?action=ranks">จัดการยศ</a></li>
            <li class="breadcrumb-item active">เพิ่มยศใหม่</li>
        </ol>
    </nav>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-plus text-primary"></i> เพิ่มยศใหม่</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error) && $error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rank_name_full" class="form-label">ชื่อยศเต็ม <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="rank_name_full" name="rank_name_full" 
                                       placeholder="เช่น พันตำรวจเอก" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="rank_name_short" class="form-label">ยศย่อ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="rank_name_short" name="rank_name_short" 
                                       placeholder="เช่น พ.ต.อ." required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rank_level" class="form-label">ระดับยศ</label>
                                <select class="form-select" id="rank_level" name="rank_level">
                                    <option value="1">ระดับ 1 (ชั้นประทวน)</option>
                                    <option value="2">ระดับ 2 (ชั้นสัญญาบัตร)</option>
                                    <option value="3">ระดับ 3 (ทั่วไป)</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="display_order" class="form-label">ลำดับการแสดง</label>
                                <input type="number" class="form-control" id="display_order" name="display_order" 
                                       value="0" min="0">
                                <div class="form-text">ใช้สำหรับเรียงลำดับการแสดงผล</div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> บันทึกยศ
                            </button>
                            <a href="/admin?action=ranks" class="btn btn-secondary">
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
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> ตัวอย่างยศตำรวจ</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ยศเต็ม</th>
                                    <th>ยศย่อ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>พลตำรวจเอก</td><td>พล.ต.อ.</td></tr>
                                <tr><td>พลตำรวจโท</td><td>พล.ต.ท.</td></tr>
                                <tr><td>พันตำรวจเอก</td><td>พ.ต.อ.</td></tr>
                                <tr><td>ร้อยตำรวจเอก</td><td>ร.ต.อ.</td></tr>
                                <tr><td>สิบตำรวจเอก</td><td>ส.ต.อ.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include SRC_PATH . '/views/admin/footer.php'; ?>
