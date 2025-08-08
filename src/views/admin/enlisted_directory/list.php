<?php
$title = 'รายการไฟล์ทำเนียบกำลังพลประทวน';
include __DIR__ . '/../header.php';
?>
<div class="container mt-4">
    <h2><?= $title ?></h2>
    <a href="/admin?action=enlisted-directory&sub_action=add" class="btn btn-success mb-3">+ อัปโหลดไฟล์ใหม่</a>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>วันที่อัปโหลด</th>
                    <th>ชื่อไฟล์/หัวข้อ</th>
                    <th>Word</th>
                    <th>PDF</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $file): ?>
                    <tr>
                        <td><?= htmlspecialchars($file['uploaded_at']) ?></td>
                        <td>
                            <div class="fw-bold"><?= htmlspecialchars($file['title']) ?></div>
                            <?php if ($file['original_word_name'] || $file['original_pdf_name']): ?>
                                <div class="text-muted small">
                                    <?php if ($file['original_word_name']): ?>
                                        <i class="fas fa-file-word text-primary"></i> <?= htmlspecialchars($file['original_word_name']) ?>
                                    <?php endif; ?>
                                    <?php if ($file['original_word_name'] && $file['original_pdf_name']): ?>
                                        <br>
                                    <?php endif; ?>
                                    <?php if ($file['original_pdf_name']): ?>
                                        <i class="fas fa-file-pdf text-danger"></i> <?= htmlspecialchars($file['original_pdf_name']) ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($file['word_file']): ?>
                                <a href="<?= $file['word_file'] ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-download"></i> ดาวน์โหลด
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($file['pdf_file']): ?>
                                <a href="/download.php?file=<?= urlencode(ltrim($file['pdf_file'], '/')) ?>&name=<?= urlencode($file['original_pdf_name']) ?>" target="_blank" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-download"></i> ดาวน์โหลด
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/admin?action=enlisted-directory&sub_action=delete&id=<?= htmlspecialchars($file['id']) ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('ยืนยันการลบไฟล์นี้?');">
                               <i class="fas fa-trash"></i> ลบ
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?> 