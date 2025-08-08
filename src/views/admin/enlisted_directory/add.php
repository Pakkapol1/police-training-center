<?php
$title = 'อัปโหลดทำเนียบกำลังพลประทวน';
include __DIR__ . '/../header.php';
?>
<div class="container mt-4">
    <h2><?= $title ?></h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">ชื่อไฟล์/หัวข้อ</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="word_file" class="form-label">ไฟล์ Word (.doc, .docx)</label>
            <input type="file" name="word_file" id="word_file" class="form-control" accept=".doc,.docx">
        </div>
        <div class="mb-3">
            <label for="pdf_file" class="form-label">ไฟล์ PDF (.pdf)</label>
            <input type="file" name="pdf_file" id="pdf_file" class="form-control" accept=".pdf">
        </div>
        <button type="submit" class="btn btn-primary">อัปโหลด</button>
        <a href="/admin?action=enlisted-directory" class="btn btn-secondary">ยกเลิก</a>
    </form>
</div>
<?php include __DIR__ . '/../footer.php'; ?> 