<?php include SRC_PATH . '/views/admin/header.php'; ?>

<div class="mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0"><i class="fas fa-star text-primary"></i> จัดการผู้บังคับบัญชา</h2>
        <div>
            <a href="commanders/order.php" class="btn btn-warning" style="margin-right:8px;"><i class="fas fa-sort-amount-up-alt"></i> จัดลำดับผู้บังคับบัญชา</a>
            <a href="commanders/orgchart.php" class="btn btn-success" style="margin-right:8px;"><i class="fas fa-sitemap"></i> จัดแผนผังองค์กร</a>
            <a href="/admin?action=commanders&sub_action=add" class="btn btn-primary">
                <i class="fas fa-plus"></i> เพิ่มผู้บังคับบัญชา
            </a>
        </div>
    </div>
    
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php
            $messages = [
                'added' => 'เพิ่มผู้บังคับบัญชาสำเร็จ',
                'updated' => 'แก้ไขข้อมูลสำเร็จ',
                'deleted' => 'ลบข้อมูลสำเร็จ'
            ];
            echo $messages[$_GET['message']] ?? 'ดำเนินการสำเร็จ';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <?php if (empty($commanders)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h4>ยังไม่มีข้อมูลผู้บังคับบัญชา</h4>
                    <p>เริ่มต้นเพิ่มข้อมูลผู้บังคับบัญชาคนแรก</p>
                    <a href="/admin?action=commanders&sub_action=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i> เพิ่มผู้บังคับบัญชา
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>รูปภาพ</th>
                                <th>ยศ ชื่อ-นามสกุล</th>
                                <th>ตำแหน่ง</th>
                                <th>โทรศัพท์</th>
                                <th>อีเมล</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($commanders as $commander): ?>
                                <tr>
                                    <td>
                                        <?php if ($commander['photo']): ?>
                                            <img src="<?= htmlspecialchars($commander['photo']) ?>" 
                                                 alt="<?= htmlspecialchars($commander['full_name']) ?>" 
                                                 class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($commander['rank_name'] . ' ' . $commander['full_name']) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($commander['position_name'] ?? 'ไม่ระบุ') ?></td>
                                    <td><?= htmlspecialchars($commander['work_phone']) ?></td>
                                    <td><?= htmlspecialchars($commander['email']) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/admin?action=commanders&sub_action=edit&id=<?= $commander['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="/admin?action=commanders&sub_action=delete&id=<?= $commander['id'] ?>" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirmDelete('คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?')">
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
