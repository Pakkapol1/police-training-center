<?php include SRC_PATH . '/views/admin/header.php'; ?>

<div class="mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">
            <i class="fas fa-users text-primary"></i> <?= htmlspecialchars($title) ?>
        </h2>
        <div class="btn-group">
            <a href="/admin?action=directory&type=supervisors" 
               class="btn btn-<?= $type === 'supervisors' ? 'primary' : 'outline-primary' ?>">
                <i class="fas fa-star"></i> ผู้กำกับการ
            </a>
            <a href="/admin?action=directory&type=commanders" 
               class="btn btn-<?= $type === 'commanders' ? 'primary' : 'outline-primary' ?>">
                <i class="fas fa-shield-alt"></i> ผู้บังคับการ
            </a>
            <a href="/admin?action=directory&sub_action=add&type=<?= $type ?>" 
               class="btn btn-success">
                <i class="fas fa-plus"></i> เพิ่มข้อมูล
            </a>
        </div>
    </div>
    
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php
            $messages = [
                'added' => 'เพิ่มข้อมูลสำเร็จ',
                'updated' => 'แก้ไขข้อมูลสำเร็จ',
                'deleted' => 'ลบข้อมูลสำเร็จ'
            ];
            echo $messages[$_GET['message']] ?? 'ดำเนินการสำเร็จ';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php
            $errors = [
                'not_found' => 'ไม่พบข้อมูลที่ต้องการ',
                'delete_failed' => 'ไม่สามารถลบข้อมูลได้'
            ];
            echo $errors[$_GET['error']] ?? 'เกิดข้อผิดพลาด';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <?php if (!empty($items)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="80">ลำดับ</th>
                                <th width="100">ยศ</th>
                                <th width="150">ชื่อ</th>
                                <th width="150">นามสกุล</th>
                                <th>ระยะเวลาการดำรงตำแหน่ง</th>
                                <th width="120">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-primary"><?= htmlspecialchars($item['order_number']) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($item['rank']) ?></td>
                                    <td><?= htmlspecialchars($item['first_name']) ?></td>
                                    <td><?= htmlspecialchars($item['last_name']) ?></td>
                                    <td><?= htmlspecialchars($item['service_period']) ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="/admin?action=directory&sub_action=edit&type=<?= $type ?>&id=<?= $item['id'] ?>" 
                                               class="btn btn-outline-primary" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="/admin?action=directory&sub_action=delete&type=<?= $type ?>&id=<?= $item['id'] ?>" 
                                               class="btn btn-outline-danger" title="ลบ"
                                               onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">ยังไม่มีข้อมูล</p>
                    <a href="/admin?action=directory&sub_action=add&type=<?= $type ?>" 
                       class="btn btn-primary">
                        <i class="fas fa-plus"></i> เพิ่มข้อมูลแรก
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include SRC_PATH . '/views/admin/footer.php'; ?> 