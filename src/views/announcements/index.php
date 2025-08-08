
<!-- External Links Container -->


<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4"><i class="fas fa-bullhorn text-warning"></i> ประกาศ</h1>
        </div>
    </div>
    <?php
    require_once SRC_PATH . '/models/AnnouncementModel.php';
    $announcementModel = new AnnouncementModel();
    ?>
    <?php if (!empty($announcements)): ?>
        <?php foreach ($announcements as $item): ?>
            <?php $images = $announcementModel->getAnnouncementImages($item['id']); ?>
            <div class="card mb-4 announcement-card-clickable" style="cursor:pointer; transition: all 0.3s ease; border-left: 4px solid <?= getPriorityColor($item['priority']) ?>;" onclick="window.location.href='/?page=announcements&action=detail&id=<?= $item['id'] ?>'">
                <?php if (!empty($images)): ?>
                    <!-- แสดงรูปเต็มความกว้างบนมือถือ -->
                    <div class="d-block d-md-none">
                        <img src="<?= htmlspecialchars($images[0]['image_path']) ?>" 
                             class="card-img-top clickable-image" 
                             alt="" 
                             style="height: 200px; object-fit:cover; cursor: pointer;"
                             onclick="event.stopPropagation(); openImageModal('<?= htmlspecialchars($images[0]['image_path']) ?>', '<?= htmlspecialchars($item['title']) ?>')">
                    </div>
                <?php endif; ?>
                
                <div class="row g-0 align-items-center">
                    <?php if (!empty($images)): ?>
                        <!-- แสดงรูปด้านข้างบน desktop -->
                        <div class="col-md-4 col-lg-3 d-none d-md-flex align-items-center justify-content-start">
                            <img src="<?= htmlspecialchars($images[0]['image_path']) ?>" 
                                 class="img-thumbnail clickable-image" 
                                 alt="" 
                                 style="width: 100%; max-width: 260px; height: 180px; object-fit:cover; border-radius:8px; cursor: pointer;"
                                 onclick="event.stopPropagation(); openImageModal('<?= htmlspecialchars($images[0]['image_path']) ?>', '<?= htmlspecialchars($item['title']) ?>')">
                        </div>
                    <?php endif; ?>
                    <div class="<?= !empty($images) ? 'col-md-8 col-lg-9' : 'col-12' ?>">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge me-2" style="background-color: <?= getPriorityColor($item['priority']) ?>; color: white;">
                                    <?= getPriorityText($item['priority']) ?>
                                </span>
                                <h5 class="card-title fw-bold mb-0" style="color:#b30000;">
                                    <?= htmlspecialchars($item['title']) ?>
                                </h5>
                            </div>
                            <div class="mb-2" style="color:#666; font-size:14px;">
                                <i class="fas fa-calendar me-1"></i><?= date('j F Y', strtotime($item['created_at'])) ?> 
                                <span class="d-none d-sm-inline">โดย <?= htmlspecialchars($item['author_name'] ?? 'admin') ?></span>
                                <?php if ($item['start_date'] || $item['end_date']): ?>
                                    <span class="d-none d-sm-inline">|</span>
                                    <i class="fas fa-clock me-1"></i>
                                    <?php if ($item['start_date'] && $item['end_date']): ?>
                                        <?= date('j/m/Y', strtotime($item['start_date'])) ?> - <?= date('j/m/Y', strtotime($item['end_date'])) ?>
                                    <?php elseif ($item['start_date']): ?>
                                        เริ่ม: <?= date('j/m/Y', strtotime($item['start_date'])) ?>
                                    <?php elseif ($item['end_date']): ?>
                                        สิ้นสุด: <?= date('j/m/Y', strtotime($item['end_date'])) ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="mb-2" style="font-size:14px;">
                                <?= nl2br(htmlspecialchars(mb_substr(strip_tags($item['content']), 0, 150))) ?><?= mb_strlen(strip_tags($item['content'])) > 150 ? '...' : '' ?>
                            </div>
                            <div class="mb-2">
                                <a href="/?page=announcements&action=detail&id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary">อ่านเพิ่มเติม</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">ยังไม่มีประกาศ</h4>
        </div>
    <?php endif; ?>
</div>

<!-- Image Modal -->
<div id="imageModal" class="image-modal" style="display: none;">
    <div class="image-modal-content">
        <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
        <img id="modalImage" src="" alt="" class="modal-image">
        <div class="modal-caption" id="modalCaption"></div>
    </div>
</div>

<style>
/* Image Modal Styles */
.image-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(5px);
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.image-modal-content {
    position: relative;
    margin: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    max-width: 95%;
    max-height: 95%;
}

.modal-image {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    animation: zoomIn 0.3s ease-out;
}

@keyframes zoomIn {
    from { 
        transform: scale(0.8);
        opacity: 0;
    }
    to { 
        transform: scale(1);
        opacity: 1;
    }
}

.modal-caption {
    color: white;
    text-align: center;
    margin-top: 15px;
    font-size: 1.1rem;
    font-weight: 500;
    max-width: 80%;
    word-wrap: break-word;
}

.image-modal-close {
    position: absolute;
    top: 20px;
    right: 30px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    z-index: 10000;
    transition: all 0.3s ease;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

.image-modal-close:hover,
.image-modal-close:focus {
    color: #bbb;
    text-decoration: none;
    transform: scale(1.1);
}

/* Clickable Image Styles */
.clickable-image:hover {
    transform: scale(1.02);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Announcement Card Styles */
.announcement-card-clickable:hover {
    box-shadow: 0 6px 24px rgba(179, 0, 0, 0.15) !important;
    transform: translateY(-3px) !important;
}

/* Mobile responsive adjustments */
@media (max-width: 575.98px) {
    .image-modal-close {
        top: 15px;
        right: 20px;
        font-size: 30px;
        width: 40px;
        height: 40px;
    }
    
    .modal-caption {
        font-size: 1rem;
        margin-top: 10px;
    }
}
</style>

<script>
function openImageModal(imageSrc, title) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const modalCaption = document.getElementById('modalCaption');
    
    modalImg.src = imageSrc;
    modalCaption.textContent = title;
    modal.style.display = 'block';
    
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside the image
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Add loading indicator for images
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('.clickable-image');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        img.style.opacity = '0.8';
        img.style.transition = 'opacity 0.3s ease';
    });
});
</script>

<?php
function getPriorityColor($priority) {
    switch ($priority) {
        case 'urgent':
            return '#dc3545'; // สีแดง
        case 'high':
            return '#fd7e14'; // สีส้ม
        case 'normal':
            return '#0d6efd'; // สีน้ำเงิน
        case 'low':
            return '#6c757d'; // สีเทา
        default:
            return '#0d6efd';
    }
}

function getPriorityText($priority) {
    switch ($priority) {
        case 'urgent':
            return 'ด่วนมาก';
        case 'high':
            return 'สำคัญ';
        case 'normal':
            return 'ปกติ';
        case 'low':
            return 'ทั่วไป';
        default:
            return 'ปกติ';
    }
}
?> 