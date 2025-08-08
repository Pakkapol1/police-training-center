<?php include SRC_PATH . '/views/admin/header.php'; ?>

<div class="mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-medal text-primary"></i> จัดการยศตำรวจ</h2>
        <a href="/admin?action=ranks&sub_action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i> เพิ่มยศใหม่
        </a>
    </div>
    
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php
            $messages = [
                'added' => 'เพิ่มยศสำเร็จ',
                'updated' => 'แก้ไขยศสำเร็จ',
                'deleted' => 'ลบยศสำเร็จ'
            ];
            echo $messages[$_GET['message']] ?? 'ดำเนินการสำเร็จ';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <?php if (empty($ranks)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h4>ยังไม่มีข้อมูลยศ</h4>
                    <a href="/admin?action=ranks&sub_action=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i> เพิ่มยศแรก
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>ยศเต็ม</th>
                                <th>ยศย่อ</th>
                                <th>ระดับ</th>
                                <th>ลำดับการแสดง</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ranks as $index => $rank): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><strong><?= htmlspecialchars($rank['rank_name_full']) ?></strong></td>
                                    <td><span class="badge bg-primary"><?= htmlspecialchars($rank['rank_name_short']) ?></span></td>
                                    <td><?= $rank['rank_level'] ?></td>
                                    <td><?= $rank['display_order'] ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/admin?action=ranks&sub_action=edit&id=<?= $rank['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="/admin?action=ranks&sub_action=delete&id=<?= $rank['id'] ?>" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirmDelete('คุณแน่ใจหรือไม่ที่จะลบยศนี้?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include SRC_PATH . '/views/admin/footer.php'; ?>
