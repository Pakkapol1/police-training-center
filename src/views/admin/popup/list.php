<?php
$title = 'จัดการป๊อบอัพ';
include __DIR__ . '/../header.php';
?>
<div class="container mt-4">
    <h2><?= $title ?></h2>
    <a href="/admin?action=popup&sub_action=add" class="btn btn-success mb-3">เพิ่มป๊อบอัพใหม่</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>ข้อความ</th>
                <th>รูปภาพ</th>
                <th>สถานะ</th>
                <th>อัปเดตล่าสุด</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($popups as $popup): ?>
                <tr>
                    <td><?= $popup['id'] ?></td>
                    <td><?= htmlspecialchars(mb_strimwidth($popup['message'], 0, 50, '...')) ?></td>
                    <td>
                        <?php if (!empty($popup['image'])): ?>
                            <img src="/<?= $popup['image'] ?>" alt="popup image" style="max-width:80px;">
                        <?php endif; ?>
                    </td>
                    <td><?= $popup['status'] == 'active' ? '<span class="badge bg-success">แสดง</span>' : '<span class="badge bg-secondary">ไม่แสดง</span>' ?></td>
                    <td><?= $popup['updated_at'] ?></td>
                    <td>
                        <a href="/admin?action=popup&sub_action=edit&id=<?= $popup['id'] ?>" class="btn btn-sm btn-primary">แก้ไข</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../footer.php'; ?> 