<?php include SRC_PATH . '/views/admin/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users text-primary"></i> จัดการผู้ใช้</h2>
        <a href="/admin?action=users&sub_action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i> เพิ่มผู้ใช้ใหม่
        </a>
    </div>
    
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php
            $messages = [
                'added' => 'เพิ่มผู้ใช้สำเร็จ',
                'updated' => 'แก้ไขผู้ใช้สำเร็จ',
                'deleted' => 'ลบผู้ใช้สำเร็จ'
            ];
            echo $messages[$_GET['message']] ?? 'ดำเนินการสำเร็จ';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ชื่อผู้ใช้</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>อีเมล</th>
                            <th>บทบาท</th>
                            <th>วันที่สร้าง</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['full_name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'info' ?>">
                                        <?= $user['role'] === 'admin' ? 'ผู้ดูแลระบบ' : 'บรรณาธิการ' ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/admin?action=users&sub_action=edit&id=<?= $user['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/admin?action=users&sub_action=change_password&id=<?= $user['id'] ?>" 
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-key"></i>
                                        </a>
                                        <?php if ($user['id'] != $_SESSION['admin_id']): ?>
                                            <a href="/admin?action=users&sub_action=delete&id=<?= $user['id'] ?>" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirmDelete('คุณแน่ใจหรือไม่ที่จะลบผู้ใช้นี้?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include SRC_PATH . '/views/admin/footer.php'; ?>
