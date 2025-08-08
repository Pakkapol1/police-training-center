<?php
$title = 'เพิ่มป๊อบอัพ';
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
            <textarea name="message" id="message" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">รูปภาพ (ถ้ามี)</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">สถานะ</label>
            <select name="status" id="status" class="form-control">
                <option value="inactive">ไม่แสดง</option>
                <option value="active">แสดง</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">บันทึก</button>
        <a href="/admin?action=popup" class="btn btn-secondary">ยกเลิก</a>
    </form>
</div>
<script>
window.onload = function() {
    var msg = document.getElementById('message');
    if (msg) msg.value = '';
};
</script>
<?php include __DIR__ . '/../footer.php'; ?> 