<?php
$title = 'แก้ไขป๊อบอัพ';
include __DIR__ . '/../header.php';
?>
<div class="container mt-4">
    <h2><?= $title ?></h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="message" class="form-label">ข้อความป๊อบอัพ</label>
            <textarea name="message" id="message" class="form-control" rows="3"><?= htmlspecialchars($popup['message'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">รูปภาพ (ถ้ามี)</label>
            <?php if (!empty($popup['image'])): ?>
                <div class="mb-2">
                    <img src="/<?= $popup['image'] ?>" alt="popup image" style="max-width:200px;">
                </div>
            <?php endif; ?>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">สถานะ</label>
            <select name="status" id="status" class="form-control">
                <option value="inactive" <?= ($popup['status'] == 'inactive') ? 'selected' : '' ?>>ไม่แสดง</option>
                <option value="active" <?= ($popup['status'] == 'active') ? 'selected' : '' ?>>แสดง</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">บันทึก</button>
        <a href="/admin?action=popup" class="btn btn-secondary">ยกเลิก</a>
    </form>
</div>
<?php include __DIR__ . '/../footer.php'; ?> 