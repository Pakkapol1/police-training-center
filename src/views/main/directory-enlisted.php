<?php
// ดึงไฟล์ล่าสุดจาก controller ส่งมาในตัวแปร $file
?>
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <div class="official-header p-4">
             <img src="/assets/img/stic.webp" alt="โลโก้หน่วยงาน" class="mb-3" style="height: 80px;">
                <h1 class="official-title mb-2">ทำเนียบกำลังพลประทวน</h1>
                <h2 class="official-subtitle">ศูนย์ฝึกอบรมตำรวจภูธรภาค 8</h2>
                <div class="official-line"></div>
            </div>
        </div>
    </div>
    <?php if (!empty($file)): ?>
        <div class="mb-3 text-end">
            <?php if (!empty($file['word_file'])): ?>
                <a href="<?= $file['word_file'] ?>" class="btn btn-outline-primary me-2" target="_blank">
                    <i class="bi bi-file-earmark-word"></i> <?= $file['original_word_name'] ? htmlspecialchars($file['original_word_name']) : 'ดาวน์โหลด Word' ?>
                </a>
            <?php endif; ?>
            <?php if (!empty($file['pdf_file'])): ?>
                <a href="<?= $file['pdf_file'] ?>" class="btn btn-outline-danger" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> <?= $file['original_pdf_name'] ? htmlspecialchars($file['original_pdf_name']) : 'ดาวน์โหลด PDF' ?>
                </a>
            <?php endif; ?>
        </div>
        <?php if (!empty($file['title'])): ?>
            <div class="mb-2 text-start">
                <div class="fw-bold" style="font-size:1.3rem; color:#222;">
                    <?= htmlspecialchars($file['title']) ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($file['pdf_file'])): ?>
            <div class="mb-4">
                <embed src="/download.php?file=<?= urlencode(ltrim($file['pdf_file'], '/')) ?>&name=<?= urlencode($file['original_pdf_name']) ?>" type="application/pdf" width="100%" height="600px" style="border:1px solid #ccc;" />
            </div>
        <?php endif; ?>
        <?php if (!empty($file['html_content'])): ?>
            <div class="card mb-4"><div class="card-body">
                <?= $file['html_content'] ?>
            </div></div>
        <?php elseif (empty($file['pdf_file'])): ?>
            <div class="alert alert-info">ไม่สามารถแสดงเนื้อหา Word ได้ กรุณาดาวน์โหลดไฟล์</div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-warning">ยังไม่มีการอัปโหลดไฟล์ทำเนียบกำลังพลประทวน</div>
    <?php endif; ?>
</div>
<style>
.card-body * {
  font-family: 'TH SarabunPSK', 'Tahoma', sans-serif;
  font-size: 20px;
  line-height: 1.7;
}
.card-body img {
  display: block;
  margin: 0 auto;
  max-width: 100%;
  height: auto;
}
.card-body table {
  width: 100%;
  border-collapse: collapse;
}
.card-body th, .card-body td {
  border: 1px solid #ccc;
  padding: 4px 8px;
}
</style>
