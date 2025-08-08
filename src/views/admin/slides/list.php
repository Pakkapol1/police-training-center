<?php include SRC_PATH . '/views/admin/header.php'; ?>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-images text-primary"></i>
                จัดการสไลด์
            </h1>
            <p class="text-muted">จัดการสไลด์สำหรับหน้าแรกของเว็บไซต์</p>
        </div>
        <a href="/admin?action=slides&subaction=add" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            เพิ่มสไลด์ใหม่
        </a>
    </div>

    <!-- Success/Error Messages -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php if ($_GET['success'] == '1'): ?>
                <i class="fas fa-check-circle"></i> บันทึกข้อมูลเรียบร้อยแล้ว
            <?php elseif ($_GET['success'] == 'deleted'): ?>
                <i class="fas fa-trash"></i> ลบสไลด์เรียบร้อยแล้ว
            <?php elseif ($_GET['success'] == 'status_updated'): ?>
                <i class="fas fa-toggle-on"></i> อัปเดตสถานะเรียบร้อยแล้ว
            <?php elseif ($_GET['success'] == 'sort_updated'): ?>
                <i class="fas fa-sort"></i> อัปเดตลำดับเรียบร้อยแล้ว
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <?php if ($_GET['error'] == 'slide_not_found'): ?>
                ไม่พบสไลด์ที่ต้องการ
            <?php elseif ($_GET['error'] == 'delete_failed'): ?>
                เกิดข้อผิดพลาดในการลบสไลด์
            <?php elseif ($_GET['error'] == 'status_update_failed'): ?>
                เกิดข้อผิดพลาดในการอัปเดตสถานะ
            <?php elseif ($_GET['error'] == 'sort_update_failed'): ?>
                เกิดข้อผิดพลาดในการอัปเดตลำดับ
            <?php else: ?>
                เกิดข้อผิดพลาดในระบบ
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Slides List -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i>
                รายการสไลด์ (<?= $totalSlides ?> รายการ)
            </h6>
        </div>
        <div class="card-body">
            <?php if (empty($slides)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">ยังไม่มีสไลด์</h5>
                    <p class="text-muted">เริ่มต้นโดยการเพิ่มสไลด์ใหม่</p>
                    <a href="/admin?action=slides&subaction=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        เพิ่มสไลด์ใหม่
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="80">ลำดับ</th>
                                <th width="120">รูปภาพ</th>
                                <th>หัวข้อ</th>
                                <th width="100">สถานะ</th>
                                <th width="150">วันที่สร้าง</th>
                                <th width="120">การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($slides as $slide): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary"><?= $slide['sort_order'] ?></span>
                                    </td>
                                    <td>
                                        <img src="<?= htmlspecialchars($slide['image_path']) ?>" 
                                             alt="<?= htmlspecialchars($slide['title']) ?>" 
                                             class="img-thumbnail" 
                                             style="width: 80px; height: 60px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?= htmlspecialchars($slide['title']) ?></strong>
                                            <?php if (!empty($slide['subtitle'])): ?>
                                                <br><small class="text-muted"><?= htmlspecialchars($slide['subtitle']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($slide['status'] == 'active'): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> เปิดใช้งาน
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-times"></i> ปิดใช้งาน
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($slide['created_at'])) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/admin?action=slides&subaction=edit&id=<?= $slide['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <?php if ($slide['status'] == 'active'): ?>
                                                <a href="/admin?action=slides&subaction=status&id=<?= $slide['id'] ?>&status=inactive" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="ปิดใช้งาน"
                                                   onclick="return confirm('ต้องการปิดใช้งานสไลด์นี้หรือไม่?')">
                                                    <i class="fas fa-eye-slash"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="/admin?action=slides&subaction=status&id=<?= $slide['id'] ?>&status=active" 
                                                   class="btn btn-sm btn-outline-success" 
                                                   title="เปิดใช้งาน"
                                                   onclick="return confirm('ต้องการเปิดใช้งานสไลด์นี้หรือไม่?')">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <a href="/admin?action=slides&subaction=delete&id=<?= $slide['id'] ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               title="ลบ"
                                               onclick="return confirm('ต้องการลบสไลด์นี้หรือไม่? การดำเนินการนี้ไม่สามารถยกเลิกได้')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="/admin?action=slides&page=<?= $page - 1 ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="/admin?action=slides&page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="/admin?action=slides&page=<?= $page + 1 ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include SRC_PATH . '/views/admin/footer.php'; ?> 