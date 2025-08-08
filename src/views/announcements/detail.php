<?php
require_once SRC_PATH . '/models/AnnouncementModel.php';
$announcementModel = new AnnouncementModel();
$images = isset($announcementItem['id']) ? $announcementModel->getAnnouncementImages($announcementItem['id']) : [];
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge me-3" style="background-color: <?= getPriorityColor($announcementItem['priority']) ?>; color: white; font-size: 0.9rem;">
                            <?= getPriorityText($announcementItem['priority']) ?>
                        </span>
                        <h2 class="fw-bold mb-0" style="color:#b30000; font-size: clamp(1.3rem, 4vw, 2rem);"> 
                            <?= htmlspecialchars($announcementItem['title']) ?> 
                        </h2>
                    </div>
                    
                    <div class="mb-3 text-muted" style="font-size: clamp(0.8rem, 2.5vw, 1rem); border-bottom:1px solid #eee; padding-bottom:6px;">
                        <i class="fas fa-calendar"></i> <?= date('j F Y', strtotime($announcementItem['created_at'])) ?>
                        <span class="d-none d-sm-inline">&nbsp;|&nbsp; <i class="fas fa-user"></i> <?= htmlspecialchars($announcementItem['author_name'] ?? 'admin') ?></span>
                        <?php if ($announcementItem['start_date'] || $announcementItem['end_date']): ?>
                            <span class="d-none d-sm-inline">&nbsp;|&nbsp; <i class="fas fa-clock"></i>
                                <?php if ($announcementItem['start_date'] && $announcementItem['end_date']): ?>
                                    <?= date('j/m/Y', strtotime($announcementItem['start_date'])) ?> - <?= date('j/m/Y', strtotime($announcementItem['end_date'])) ?>
                                <?php elseif ($announcementItem['start_date']): ?>
                                    เริ่ม: <?= date('j/m/Y', strtotime($announcementItem['start_date'])) ?>
                                <?php elseif ($announcementItem['end_date']): ?>
                                    สิ้นสุด: <?= date('j/m/Y', strtotime($announcementItem['end_date'])) ?>
                                <?php endif; ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($images)): ?>
                        <div class="mb-3 text-center">
                            <img src="<?= htmlspecialchars($images[0]['image_path']) ?>" 
                                 class="img-fluid mb-2 clickable-image" 
                                 alt="" 
                                 style="width: 100%; max-width: 640px; height: auto; max-height: 420px; object-fit:cover; border-radius:10px; cursor: pointer; transition: all 0.3s ease;"
                                 onclick="openImageModal('<?= htmlspecialchars($images[0]['image_path']) ?>', '<?= htmlspecialchars($announcementItem['title']) ?>')">
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-4" style="font-size: clamp(0.9rem, 3vw, 1.1rem); line-height:1.7;">
                        <?= nl2br(htmlspecialchars($announcementItem['content'])) ?>
                    </div>
                    
                    <?php if (!empty($images) && count($images) > 1): ?>
                        <div class="mb-4 row g-2 g-md-3 justify-content-center">
                            <?php foreach (array_slice($images, 1) as $img): ?>
                                <div class="col-6 col-sm-6 col-md-4 d-flex justify-content-center">
                                    <img src="<?= htmlspecialchars($img['image_path']) ?>" 
                                         class="img-thumbnail clickable-image" 
                                         alt="" 
                                         style="width: 100%; max-width: 320px; height: auto; aspect-ratio: 16/11; object-fit:cover; border-radius:10px; cursor: pointer; transition: all 0.3s ease;"
                                         onclick="openImageModal('<?= htmlspecialchars($img['image_path']) ?>', '<?= htmlspecialchars($announcementItem['title']) ?>')">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <a href="/?page=announcements" class="btn btn-outline-primary"><i class="fas fa-arrow-left"></i> กลับหน้าประกาศ</a>
                </div>
            </div>
        </div>
    </div>
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

/* Mobile responsive adjustments for announcement detail */
@media (max-width: 575.98px) {
    .container {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .card {
        margin: 0.5rem 0;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .img-fluid {
        border-radius: 8px !important;
        margin-bottom: 1rem !important;
    }
    
    .img-thumbnail {
        border-radius: 8px !important;
        margin-bottom: 0.5rem !important;
    }
    
    .btn {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
    
    /* ปรับขนาดรูปภาพให้เหมาะสมกับมือถือ */
    .row.g-2 .col-6 {
        margin-bottom: 0.5rem;
    }
    
    /* ป้องกันการล้นของข้อความ */
    .card-body * {
        word-wrap: break-word;
        word-break: break-word;
    }
    
    /* Mobile Modal Adjustments */
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

@media (max-width: 767.98px) {
    .col-lg-10.col-xl-8 {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    
    .my-5 {
        margin-top: 1.5rem !important;
        margin-bottom: 1.5rem !important;
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