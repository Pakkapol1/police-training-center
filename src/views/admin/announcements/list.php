<?php
require_once SRC_PATH . '/models/AnnouncementModel.php';
$announcementModel = new AnnouncementModel();
?>

<!-- Enhanced CSS for Announcements Page -->
<link rel="stylesheet" href="/assets/css/announcements-enhanced.css">
<link rel="stylesheet" href="/assets/css/action-buttons-with-icons.css">

<div class="container-fluid">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/admin?action=dashboard" class="breadcrumb-link">
                    <i class="fas fa-home me-1"></i> แดชบอร์ด
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fas fa-bullhorn me-1"></i> จัดการประกาศ
            </li>
        </ol>
    </nav>

    <!-- Enhanced Header Section -->
    <div class="announcement-header mb-4">
        <div class="header-background">
            <div class="header-pattern"></div>
            <div class="header-glow"></div>
        </div>
        <div class="header-content">
            <div class="row align-items-center">
                <div class="col-lg-8">
                <div class="header-text">
                        <div class="title-wrapper">
                    <h2 class="header-title">
                        <i class="fas fa-bullhorn me-3"></i> 
                        จัดการประกาศ
                    </h2>
                            <div class="title-decoration"></div>
                        </div>
                    <p class="header-subtitle">
                        จัดการประกาศและข่าวสารสำคัญขององค์กรอย่างมีประสิทธิภาพ
                    </p>
                    <div class="header-stats">
                        <div class="stat-item">
                                <div class="stat-icon-wrapper">
                            <i class="fas fa-bullhorn"></i>
                                </div>
                                <span class="stat-text"><?= $totalAnnouncements ?> ประกาศ</span>
                        </div>
                        <div class="stat-item">
                                <div class="stat-icon-wrapper">
                            <i class="fas fa-check-circle"></i>
                                </div>
                                <span class="stat-text"><?= $announcementModel->getTotalAnnouncements('', 'published') ?> เผยแพร่</span>
                        </div>
                        <div class="stat-item">
                                <div class="stat-icon-wrapper">
                            <i class="fas fa-clock"></i>
                                </div>
                                <span class="stat-text"><?= $announcementModel->getTotalAnnouncements('', 'draft') ?> ร่าง</span>
                        </div>
                        <div class="stat-item">
                                <div class="stat-icon-wrapper">
                            <i class="fas fa-fire"></i>
                        </div>
                                <span class="stat-text"><?= $announcementModel->getTotalAnnouncements('', 'all', 'urgent') ?> ด่วน</span>
                    </div>
                </div>
                    </div>
                </div>
                <div class="col-lg-4">
                <div class="header-actions">
                    <div class="quick-actions">
                        <a href="/admin?action=announcements&sub_action=add" class="btn btn-light btn-lg add-btn">
                            <i class="fas fa-plus me-2"></i> เพิ่มประกาศใหม่
                        </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Horizontal Stats Cards -->
    <div class="stats-cards mb-4">
        <div class="stats-row">
            <div class="stat-card total-card">
                <div class="stat-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number"><?= $totalAnnouncements ?></h3>
                    <p class="stat-label">ประกาศทั้งหมด</p>
                </div>
            </div>
            <div class="stat-card published-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number"><?= $announcementModel->getTotalAnnouncements('', 'published') ?></h3>
                    <p class="stat-label">เผยแพร่แล้ว</p>
                </div>
            </div>
            <div class="stat-card draft-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number"><?= $announcementModel->getTotalAnnouncements('', 'draft') ?></h3>
                    <p class="stat-label">ร่าง</p>
                </div>
            </div>
            <div class="stat-card urgent-card">
                <div class="stat-icon">
                    <i class="fas fa-fire"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number"><?= $announcementModel->getTotalAnnouncements('', 'all', 'urgent') ?></h3>
                    <p class="stat-label">ด่วนมาก</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Simplified Search and Filter Section -->
    <div class="search-filter-section mb-4">
        <div class="simple-filter-card">
            <div class="filter-row">
                <!-- Search Box -->
                <div class="search-container">
                    <div class="search-input-group">
                        <div class="search-icon-wrapper">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" class="form-control simple-search-input" id="searchInput" 
                               placeholder="ค้นหาประกาศ..." 
                               value="<?= htmlspecialchars($search ?? '') ?>">
                    </div>
                </div>
                
                <!-- Status Filter -->
                <div class="filter-container">
                    <select class="form-select simple-filter-select" id="statusFilter">
                        <option value="all">สถานะทั้งหมด</option>
                        <option value="published" <?= ($status ?? '') === 'published' ? 'selected' : '' ?>>เผยแพร่</option>
                        <option value="draft" <?= ($status ?? '') === 'draft' ? 'selected' : '' ?>>ร่าง</option>
                    </select>
                </div>
                
                <!-- Total Items Badge -->
                <div class="total-items-container">
                    <div class="total-items-badge">
                        <i class="fas fa-list"></i>
                        <span>ทั้งหมด <?= $totalAnnouncements ?> รายการ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions-section mb-3">
        <div class="d-flex justify-content-end">
            <div class="bulk-actions">
                <button type="button" class="btn btn-primary btn-sm bulk-btn" id="selectAllBtn">
                    <i class="fas fa-check me-1"></i> เลือกทั้งหมด
                </button>
                <div class="bulk-menu" id="bulkMenu" style="display: none;">
                    <button type="button" class="btn btn-success btn-sm" onclick="bulkPublish()">
                        <i class="fas fa-check-circle me-1"></i> เผยแพร่
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" onclick="bulkDraft()">
                        <i class="fas fa-clock me-1"></i> เปลี่ยนเป็นร่าง
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="bulkDelete()">
                        <i class="fas fa-trash me-1"></i> ลบ
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table View -->
    <div class="announcements-list-section">
        <div class="card list-card">
            <div class="card-header list-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="header-info">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            รายการประกาศ
                        </h5>
                        <small class="text-muted">
                            หน้า <?= $page ?> จาก <?= $totalPages ?> • <?= $totalAnnouncements ?> รายการ
                        </small>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($announcementList)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 announcement-table">
                            <thead class="table-header">
                                <tr>
                                    <th style="width: 50px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </div>
                                    </th>
                                    <th style="width: 80px;">ID</th>
                                    <th style="width: 100px;">รูป</th>
                                    <th>หัวข้อประกาศ</th>
                                    <th style="width: 150px;">ความสำคัญ</th>
                                    <th style="width: 120px;">สถานะ</th>
                                    <th style="width: 150px;">วันที่สร้าง</th>
                                    <th style="width: 120px;">ผู้เขียน</th>
                                    <th style="width: 200px;">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($announcementList as $item): ?>
                                    <tr class="announcement-row" data-id="<?= $item['id'] ?>">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input announcement-checkbox" type="checkbox" value="<?= $item['id'] ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <span class="id-badge">#<?= $item['id'] ?></span>
                                        </td>
                                        <td>
                                            <?php if ($item['image']): ?>
                                                <div class="image-container" onclick="showImageModal('<?= htmlspecialchars($item['image']) ?>', '<?= htmlspecialchars($item['title']) ?>')">
                                                    <img src="/uploads/announcements/<?= htmlspecialchars($item['image']) ?>" 
                                                         alt="รูปภาพประกาศ" class="announcement-thumbnail">
                                                    <div class="image-overlay">
                                                        <i class="fas fa-eye"></i>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="placeholder-image">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="announcement-title">
                                                <div class="title-text"><?= htmlspecialchars($item['title']) ?></div>
                                                <div class="content-preview">
                                                    <?= mb_substr(strip_tags($item['content']), 0, 80) ?>...
                                                </div>
                                                <div class="meta-info">
                                                    <span class="meta-item">
                                                        <i class="fas fa-calendar-alt"></i>
                                                        <?= date('d/m/Y', strtotime($item['created_at'])) ?>
                                                    </span>
                                                    <?php if ($item['start_date'] || $item['end_date']): ?>
                                                        <span class="meta-item">
                                                            <i class="fas fa-clock"></i>
                                                            <?php if ($item['start_date'] && $item['end_date']): ?>
                                                                <?= date('d/m/Y', strtotime($item['start_date'])) ?> - <?= date('d/m/Y', strtotime($item['end_date'])) ?>
                                                            <?php elseif ($item['start_date']): ?>
                                                                เริ่ม: <?= date('d/m/Y', strtotime($item['start_date'])) ?>
                                                            <?php elseif ($item['end_date']): ?>
                                                                สิ้นสุด: <?= date('d/m/Y', strtotime($item['end_date'])) ?>
                                                            <?php endif; ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="priority-badge" data-priority="<?= $item['priority'] ?>">
                                                <i class="fas fa-flag me-1"></i>
                                                <?= getPriorityText($item['priority']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge" data-status="<?= $item['status'] ?>">
                                                <i class="fas fa-<?= $item['status'] === 'published' ? 'check-circle' : 'clock' ?> me-1"></i>
                                                <?= $item['status'] === 'published' ? 'เผยแพร่' : 'ร่าง' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="date-info">
                                                <div class="date-main"><?= date('d/m/Y', strtotime($item['created_at'])) ?></div>
                                                <div class="date-time"><?= date('H:i', strtotime($item['created_at'])) ?> น.</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div class="user-details">
                                                    <div class="user-name"><?= htmlspecialchars($item['author_name'] ?? 'admin') ?></div>
                                                    <div class="user-role">ผู้ดูแลระบบ</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="/?page=announcements&action=detail&id=<?= $item['id'] ?>" 
                                                   target="_blank" class="btn btn-action btn-action-view" title="ดูประกาศ">
                                                    <i class="fas fa-eye"></i>
                                                    <span class="btn-label">ดู</span>
                                                </a>
                                                <a href="/admin?action=announcements&sub_action=edit&id=<?= $item['id'] ?>" 
                                                   class="btn btn-action btn-action-edit" title="แก้ไขประกาศ">
                                                    <i class="fas fa-edit"></i>
                                                    <span class="btn-label">แก้ไข</span>
                                                </a>
                                                <button type="button" class="btn btn-action btn-action-preview" 
                                                        onclick="previewAnnouncement(<?= $item['id'] ?>)" title="ดูตัวอย่าง">
                                                    <i class="fas fa-search"></i>
                                                    <span class="btn-label">ตัวอย่าง</span>
                                                </button>
                                                <button type="button" class="btn btn-action btn-action-delete" 
                                                        onclick="confirmDelete(<?= $item['id'] ?>, '<?= htmlspecialchars($item['title']) ?>')" title="ลบประกาศ">
                                                    <i class="fas fa-trash"></i>
                                                    <span class="btn-label">ลบ</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="card-footer pagination-footer">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center mb-0">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?action=announcements&p=<?= $page - 1 ?>&search=<?= urlencode($search ?? '') ?>&status=<?= $status ?? 'all' ?>&priority=<?= $priority ?? 'all' ?>">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                            <a class="page-link" href="?action=announcements&p=<?= $i ?>&search=<?= urlencode($search ?? '') ?>&status=<?= $status ?? 'all' ?>&priority=<?= $priority ?? 'all' ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($page < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?action=announcements&p=<?= $page + 1 ?>&search=<?= urlencode($search ?? '') ?>&status=<?= $status ?? 'all' ?>&priority=<?= $priority ?? 'all' ?>">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-content">
                            <div class="empty-icon">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <h4 class="empty-title">ไม่พบประกาศ</h4>
                            <p class="empty-description">
                                ยังไม่มีประกาศในระบบ หรือไม่พบประกาศที่ตรงกับเงื่อนไขการค้นหา
                            </p>
                            <div class="empty-actions">
                                <a href="/admin?action=announcements&sub_action=add" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i> เพิ่มประกาศใหม่
                                </a>
                                <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                                    <i class="fas fa-times me-2"></i> ล้างตัวกรอง
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>


</div>



<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<style>
/* Global Styles */
* {
    transition: all 0.3s ease;
}

/* Enhanced Breadcrumb */
.breadcrumb {
    background: transparent;
    padding: 1rem 0;
    margin: 0;
    border-radius: 0;
}

.breadcrumb-item a {
    color: #6c757d;
    text-decoration: none;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    position: relative;
}

.breadcrumb-item a:hover {
    color: #667eea;
    background: rgba(102, 126, 234, 0.1);
    transform: translateY(-1px);
}

.breadcrumb-item.active {
    color: #495057;
    font-weight: 600;
    background: rgba(73, 80, 87, 0.1);
    padding: 0.5rem 1rem;
    border-radius: 8px;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #adb5bd;
    font-weight: bold;
    font-size: 1.2rem;
    margin: 0 0.5rem;
}

/* Enhanced Header Section */
.announcement-header {
    position: relative;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 3rem 2rem;
    border-radius: 25px;
    box-shadow: 0 25px 50px rgba(102, 126, 234, 0.4);
    overflow: hidden;
    margin-bottom: 2rem;
}

.header-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.header-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px),
                      radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 2px, transparent 2px);
    background-size: 50px 50px;
    opacity: 0.3;
}

.header-glow {
    position: absolute;
    top: -50%;
    right: -20%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: glow 6s ease-in-out infinite alternate;
}

@keyframes glow {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.header-content {
    position: relative;
    z-index: 2;
}

.title-wrapper {
    position: relative;
    display: inline-block;
}

.title-decoration {
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, rgba(255,255,255,0.8), transparent);
    border-radius: 2px;
}

.header-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.header-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 2rem;
    line-height: 1.6;
}

.header-stats {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: rgba(255,255,255,0.15);
    padding: 0.75rem 1.25rem;
    border-radius: 15px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s ease;
}

.stat-item:hover {
    background: rgba(255,255,255,0.25);
    transform: translateY(-2px);
}

.stat-icon-wrapper {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.stat-text {
    font-weight: 600;
    font-size: 0.95rem;
}

.quick-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
    justify-content: flex-end;
}

.add-btn {
    background: rgba(255,255,255,0.95);
    border: none;
    color: #667eea;
    font-weight: 600;
    padding: 1rem 2rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.add-btn:hover {
    background: white;
    color: #667eea;
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}

/* Responsive Design */
@media (max-width: 768px) {
    .announcement-header {
        padding: 2rem 1rem;
        border-radius: 20px;
    }
    
    .header-title {
        font-size: 2rem;
    }
    
    .header-subtitle {
        font-size: 1rem;
    }
    
    .header-stats {
        gap: 1rem;
    }
    
    .stat-item {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }
    
    .stat-icon-wrapper {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
    
    .stats-row {
        gap: 1rem;
    }
    
    .stat-card {
        padding: 1.5rem;
        min-width: 100%;
    }
    
    .stat-number {
        font-size: 2.2rem;
    }
    
    .stat-label {
        font-size: 0.9rem;
    }
    
    .add-btn {
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .announcement-header {
        padding: 1.5rem 1rem;
        border-radius: 15px;
    }
    
    .header-title {
        font-size: 1.8rem;
        text-align: center;
    }
    
    .header-subtitle {
        text-align: center;
    }
    
    .header-stats {
        flex-direction: column;
        gap: 0.75rem;
        align-items: center;
    }
    
    .stat-item {
        justify-content: center;
        min-width: 200px;
    }
    
    .quick-actions {
        justify-content: center;
        margin-top: 1.5rem;
    }
    
    .add-btn {
        width: 100%;
        max-width: 300px;
    }
    
    .stats-row {
        flex-direction: column;
    }
    
    .stat-card {
        min-width: 100%;
        margin-bottom: 1rem;
    }
    
    .breadcrumb {
        font-size: 0.9rem;
    }
    
    .breadcrumb-item a {
        padding: 0.4rem 0.8rem;
    }
}

/* Loading Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.announcement-header {
    animation: fadeInUp 0.8s ease-out;
}

.stat-card {
    animation: fadeInUp 0.8s ease-out;
    animation-delay: calc(var(--card-index, 0) * 0.1s);
}

.breadcrumb {
    animation: slideInLeft 0.6s ease-out;
}

.add-btn {
    animation: pulse 2s ease-in-out infinite;
}

.add-btn:hover {
    animation: none;
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Enhanced hover effects */
.stat-card:hover .stat-icon {
    transform: scale(1.1) rotate(5deg);
}

.stat-card:hover .stat-number {
    transform: scale(1.05);
}

/* Glass morphism effect */
.stat-item {
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}



/* Enhanced Horizontal Stats Cards */
.stats-cards {
    margin-bottom: 2rem;
}

.stats-row {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.stat-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 15px 35px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 1.5rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    flex: 1;
    min-width: 220px;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--card-color), var(--card-color-light));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 25px 50px rgba(0,0,0,0.12);
}

.stat-card:hover::before {
    opacity: 1;
}

.stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: white;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
}

.stat-icon::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.2) 50%, transparent 70%);
    transform: translateX(-100%);
    transition: transform 0.6s ease;
}

.stat-card:hover .stat-icon::before {
    transform: translateX(100%);
}

.total-card {
    --card-color: #667eea;
    --card-color-light: #764ba2;
}

.total-card .stat-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.published-card {
    --card-color: #28a745;
    --card-color-light: #20c997;
}

.published-card .stat-icon {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.draft-card {
    --card-color: #ffc107;
    --card-color-light: #fd7e14;
}

.draft-card .stat-icon {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.urgent-card {
    --card-color: #dc3545;
    --card-color-light: #e83e8c;
}

.urgent-card .stat-icon {
    background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
}

.stat-content {
    flex: 1;
    min-width: 0;
}

.stat-number {
    font-size: 2.8rem;
    font-weight: 900;
    margin: 0;
    color: #2c3e50;
    line-height: 1;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.stat-label {
    font-size: 1rem;
    color: #6c757d;
    margin: 0.5rem 0 0 0;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.header-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.header-content {
    position: relative;
    z-index: 1;
}

.header-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.header-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin: 1rem 0 2rem 0;
    font-weight: 300;
}

.header-stats {
    display: flex;
    gap: 2rem;
    margin-top: 1.5rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    opacity: 0.8;
}

.stat-item i {
    font-size: 1.1rem;
}

.add-btn {
    background: rgba(255,255,255,0.2);
    border: 2px solid rgba(255,255,255,0.3);
    color: white;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.add-btn:hover {
    background: rgba(255,255,255,0.3);
    border-color: rgba(255,255,255,0.5);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

/* Simplified Search and Filter */
.search-filter-section {
    margin-bottom: 2rem;
}

.simple-filter-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border: none;
    padding: 1.5rem;
    position: relative;
}

.filter-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Search Container */
.search-container {
    flex: 1;
    min-width: 300px;
}

.search-input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon-wrapper {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px 0 0 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.simple-search-input {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem 1rem 0.75rem 3.5rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
    width: 100%;
}

.simple-search-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    outline: none;
}

/* Filter Container */
.filter-container {
    min-width: 200px;
}

.simple-filter-select {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}

.simple-filter-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    outline: none;
}

/* Total Items Container */
.total-items-container {
    margin-left: auto;
}

.total-items-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
    white-space: nowrap;
}

.total-items-badge i {
    font-size: 1rem;
}

/* List Card */
.list-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
}

.list-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    padding: 1.5rem;
}

.header-info h5 {
    color: #495057;
    font-weight: 700;
}

.bulk-btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 0.5rem 1rem;
}

.bulk-menu {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.bulk-menu .btn {
    border-radius: 6px;
    font-size: 0.8rem;
    padding: 0.4rem 0.8rem;
}

/* Table Styles */
.announcement-table {
    margin: 0;
}

.table-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.table-header th {
    border: none;
    padding: 1.2rem 0.75rem;
    font-weight: 700;
    color: #495057;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.announcement-row {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f4;
}

.announcement-row:hover {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.announcement-row td {
    padding: 1.2rem 0.75rem;
    vertical-align: middle;
    border: none;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

/* ID Badge */
.id-badge {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.8rem;
    display: inline-block;
}

/* Image Container */
.image-container {
    position: relative;
    width: 60px;
    height: 60px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.announcement-thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.3s ease;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
    color: white;
}

.image-container:hover .image-overlay {
    opacity: 1;
}

.image-container:hover .announcement-thumbnail {
    transform: scale(1.1);
}

.placeholder-image {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 1.5rem;
}

/* Announcement Title */
.announcement-title {
    max-width: 400px;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.title-text {
    font-weight: 700;
    color: #2c3e50;
    font-size: 1rem;
    margin-bottom: 0.5rem;
    line-height: 1.4;
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    max-height: 2.8em;
}

.content-preview {
    color: #6c757d;
    font-size: 0.9rem;
    line-height: 1.4;
    margin-bottom: 0.5rem;
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    max-height: 2.5em;
}

.meta-info {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.5rem;
    max-width: 100%;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.8rem;
    color: #6c757d;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px;
}

.meta-item i {
    font-size: 0.7rem;
}

/* Priority Badge */
.priority-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.priority-badge[data-priority="urgent"] {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.priority-badge[data-priority="high"] {
    background: linear-gradient(135deg, #fd7e14 0%, #e55a00 100%);
    color: white;
}

.priority-badge[data-priority="normal"] {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    color: white;
}

.priority-badge[data-priority="low"] {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: white;
}

/* Status Badge */
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.status-badge[data-status="published"] {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
    color: white;
}

.status-badge[data-status="draft"] {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: white;
}

/* Date Info */
.date-info {
    text-align: center;
}

.date-main {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}

.date-time {
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

/* User Info */
.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.user-details {
    flex: 1;
    min-width: 0;
    overflow: hidden;
}

.user-name {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
}

.user-role {
    font-size: 0.8rem;
    color: #6c757d;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    flex-wrap: wrap;
    max-width: 100%;
}

.btn-action {
    min-width: 45px;
    height: 45px;
    padding: 0.5rem 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    border-radius: 12px;
    font-size: 0.9rem;
    border: none;
    transition: all 0.3s ease;
    color: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    position: relative;
    overflow: hidden;
    font-weight: 600;
    text-decoration: none;
}

.btn-action i {
    font-size: 1rem;
    flex-shrink: 0;
}

.btn-label {
    display: none;
    white-space: nowrap;
    font-size: 0.8rem;
}

/* Show labels on hover */
.btn-action:hover .btn-label {
    display: inline;
}

/* Responsive: Show labels on larger screens */
@media (min-width: 1200px) {
    .btn-action {
        min-width: 60px;
        padding: 0.5rem 1rem;
    }
    
    .btn-label {
        display: inline;
    }
}

.btn-action:hover {
    transform: translateY(-3px) scale(1.1);
    box-shadow: 0 8px 20px rgba(0,0,0,0.25);
    color: white;
}

.btn-action-view {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.btn-action-view:hover {
    background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
}

.btn-action-edit {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
}

.btn-action-edit:hover {
    background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
}

.btn-action-preview {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.btn-action-preview:hover {
    background: linear-gradient(135deg, #e0a800 0%, #e55a00 100%);
}

.btn-action-delete {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.btn-action-delete:hover {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
}

/* Dropdown Action Button */
.btn-action-dropdown {
    min-width: 40px;
    height: 40px;
    padding: 0.5rem 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    border-radius: 10px;
    font-size: 0.9rem;
    border: 2px solid #e9ecef;
    background: white;
    color: #6c757d;
    transition: all 0.3s ease;
    font-weight: 600;
}

.dropdown-label {
    display: none;
    white-space: nowrap;
    font-size: 0.8rem;
}

/* Show labels on hover */
.btn-action-dropdown:hover .dropdown-label {
    display: inline;
}

/* Responsive: Show labels on larger screens */
@media (min-width: 1200px) {
    .btn-action-dropdown {
        min-width: 50px;
        padding: 0.5rem 1rem;
    }
    
    .dropdown-label {
        display: inline;
    }
}

.btn-action-dropdown:hover {
    background: #f8f9fa;
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

.btn-action-dropdown:focus {
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Modern Dropdown Menu */
.dropdown-menu-modern {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    padding: 0.5rem 0;
    min-width: 180px;
    animation: fadeInUp 0.3s ease;
}

.dropdown-item-modern {
    padding: 0.75rem 1.5rem;
    font-size: 0.9rem;
    font-weight: 500;
    color: #495057;
    transition: all 0.3s ease;
    border: none;
    background: transparent;
}

.dropdown-item-modern:hover {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    color: #667eea;
    transform: translateX(5px);
}

.dropdown-item-modern.text-danger:hover {
    background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
    color: #dc3545;
}

.dropdown-divider {
    margin: 0.5rem 0;
    border-color: #e9ecef;
}

/* Empty State */
.empty-state {
    padding: 4rem 2rem;
    text-align: center;
}

.empty-content {
    max-width: 500px;
    margin: 0 auto;
}

.empty-icon {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    font-size: 3rem;
    color: #6c757d;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.empty-title {
    color: #495057;
    font-weight: 700;
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.empty-description {
    color: #6c757d;
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 2rem;
}

.empty-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.empty-actions .btn {
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
}

/* Pagination */
.pagination-footer {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-top: 1px solid #dee2e6;
}

.pagination .page-link {
    border: none;
    color: #667eea;
    padding: 0.75rem 1rem;
    margin: 0 2px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* View Toggle */
.view-toggle-section {
    margin-bottom: 1.5rem;
}



/* Responsive */
@media (max-width: 768px) {
    .announcement-header {
        padding: 2rem 1.5rem;
}

    .header-title {
        font-size: 2rem;
}

    .header-stats {
        flex-direction: column;
        gap: 1rem;
}

    .quick-actions {
    flex-direction: column;
        gap: 0.5rem;
        width: 100%;
}

    .add-btn {
        width: 100%;
        text-align: center;
}

    .search-filter-section .row {
        gap: 1rem;
}

    .filter-row {
        flex-direction: column;
        gap: 1rem;
}

    .search-container {
        width: 100%;
        min-width: auto;
}

    .filter-container {
        width: 100%;
        min-width: auto;
}

    .total-items-container {
        margin-left: 0;
        width: 100%;
}

    .total-items-badge {
        justify-content: center;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.25rem;
}

    .btn-action {
        width: 100%;
        height: auto;
        padding: 0.5rem;
        font-size: 0.9rem;
        min-width: auto;
    }
    
    .btn-action .btn-label {
        display: inline;
}

    .btn-action-dropdown {
    width: 100%;
        height: auto;
        padding: 0.5rem;
        min-width: auto;
}

    .btn-action-dropdown .dropdown-label {
        display: inline;
}

    .meta-info {
        flex-direction: column;
        gap: 0.5rem;
}

    .announcement-title {
        max-width: 100%;
    }
    
    .title-text {
    font-size: 0.9rem;
        -webkit-line-clamp: 3;
        max-height: 4.2em;
}

    .content-preview {
    font-size: 0.8rem;
        -webkit-line-clamp: 3;
        max-height: 3.6em;
}

    .user-info {
        flex-direction: column;
    align-items: center;
        text-align: center;
    gap: 0.5rem;
}

    .user-details {
        text-align: center;
}

    .user-name, .user-role {
        max-width: 80px;
    }
    
    .priority-badge, .status-badge {
        font-size: 0.7rem;
        padding: 0.4rem 0.8rem;
    }
    
    .date-info {
        font-size: 0.8rem;
    }
    
    .date-main {
        font-size: 0.8rem;
    }
    
    .date-time {
        font-size: 0.7rem;
    }
}

/* Extra Small Screens */
@media (max-width: 576px) {
    .announcement-table {
        font-size: 0.8rem;
    }
    
    .table-header th {
        padding: 1rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .announcement-row td {
        padding: 1rem 0.5rem;
    }
    
    .title-text {
        font-size: 0.85rem;
        -webkit-line-clamp: 2;
        max-height: 2.8em;
    }
    
    .content-preview {
        font-size: 0.75rem;
        -webkit-line-clamp: 2;
        max-height: 2.5em;
    }
    
    .meta-info {
        gap: 0.25rem;
    }
    
    .meta-item {
        font-size: 0.7rem;
        max-width: 120px;
    }
    
    .priority-badge, .status-badge {
        font-size: 0.65rem;
        padding: 0.3rem 0.6rem;
    }
    
    .action-buttons {
        gap: 0.25rem;
    }
    
    .btn-action {
        min-width: 35px;
        height: 35px;
        padding: 0.4rem;
        font-size: 0.8rem;
    }
    
    .btn-action i {
        font-size: 0.9rem;
    }
    
    .image-container, .placeholder-image {
        width: 50px;
        height: 50px;
    }
    
    .user-avatar {
        width: 35px;
        height: 35px;
        font-size: 0.9rem;
    }
    
    .user-name, .user-role {
        max-width: 60px;
        font-size: 0.8rem;
    }
    
    .date-main {
        font-size: 0.75rem;
    }
    
    .date-time {
        font-size: 0.65rem;
    }
    
    .id-badge {
        font-size: 0.7rem;
        padding: 0.3rem 0.6rem;
    }
    
    .empty-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .stats-row {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .stat-card {
        padding: 1rem;
        min-width: auto;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px 15px 0 0;
    border-bottom: none;
}

.modal-title {
    font-weight: 700;
}

.modal-body {
    padding: 2rem;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    padding: 1.5rem 2rem;
}

/* Image Modal */
#imageModal .modal-body {
    padding: 1rem;
}

#modalImage {
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.announcement-row {
    animation: fadeInUp 0.6s ease forwards;
}

.stat-card {
    animation: slideInRight 0.6s ease forwards;
}

.announcement-row:nth-child(1) { animation-delay: 0.1s; }
.announcement-row:nth-child(2) { animation-delay: 0.2s; }
.announcement-row:nth-child(3) { animation-delay: 0.3s; }
.announcement-row:nth-child(4) { animation-delay: 0.4s; }
.announcement-row:nth-child(5) { animation-delay: 0.5s; }

/* Action buttons animation */
.btn-action {
    animation: fadeInUp 0.6s ease forwards;
}

.btn-action:nth-child(1) { animation-delay: 0.1s; }
.btn-action:nth-child(2) { animation-delay: 0.2s; }
.btn-action:nth-child(3) { animation-delay: 0.3s; }
.btn-action:nth-child(4) { animation-delay: 0.4s; }

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }
</style>

<script>
// Select All functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.announcement-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectAllButton();
    updateBulkMenu();
});

document.getElementById('selectAllBtn').addEventListener('click', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    selectAllCheckbox.checked = !selectAllCheckbox.checked;
    selectAllCheckbox.dispatchEvent(new Event('change'));
});

// Individual checkbox change
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('announcement-checkbox')) {
        updateSelectAllButton();
        updateBulkMenu();
    }
});

function updateSelectAllButton() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const checkboxes = document.querySelectorAll('.announcement-checkbox');
    const checkedCount = document.querySelectorAll('.announcement-checkbox:checked').length;
    
    if (selectAllCheckbox && selectAllBtn) {
    if (selectAllCheckbox.checked) {
        selectAllBtn.innerHTML = '<i class="fas fa-times me-1"></i> ยกเลิกการเลือก';
        selectAllBtn.className = 'btn btn-secondary btn-sm bulk-btn';
    } else {
        selectAllBtn.innerHTML = '<i class="fas fa-check me-1"></i> เลือกทั้งหมด';
        selectAllBtn.className = 'btn btn-primary btn-sm bulk-btn';
    }
    
    // Update select all checkbox state
    if (checkedCount === 0) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
    } else if (checkedCount === checkboxes.length) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = true;
    } else {
        selectAllCheckbox.indeterminate = true;
        selectAllCheckbox.checked = false;
        }
    }
}

function updateBulkMenu() {
    const checkedCount = document.querySelectorAll('.announcement-checkbox:checked').length;
    const bulkMenu = document.getElementById('bulkMenu');
    
    if (bulkMenu) {
    if (checkedCount > 0) {
        bulkMenu.style.display = 'flex';
        bulkMenu.style.animation = 'fadeInUp 0.3s ease';
    } else {
        bulkMenu.style.display = 'none';
        }
    }
}

// Search functionality with debounce
let searchTimeout;
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const searchTerm = this.value;
    
    // Show loading indicator
        const searchIcon = document.querySelector('.search-icon-wrapper i');
        if (searchIcon) {
    const originalIcon = searchIcon.className;
    searchIcon.className = 'fas fa-spinner fa-spin';
        }
    
    searchTimeout = setTimeout(() => {
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('search', searchTerm);
        currentUrl.searchParams.set('p', '1');
        
        // Show loading state
        document.body.style.cursor = 'wait';
        
        window.location.href = currentUrl.toString();
    }, 800); // Increased debounce time for better UX
});
}

// Clear search with confirmation if there's content
const clearSearchBtn = document.getElementById('clearSearch');
if (clearSearchBtn) {
    clearSearchBtn.addEventListener('click', function() {
    const searchInput = document.getElementById('searchInput');
        if (searchInput && searchInput.value.trim()) {
        if (confirm('คุณต้องการล้างการค้นหาใช่หรือไม่?')) {
            searchInput.value = '';
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.delete('search');
            currentUrl.searchParams.set('p', '1');
            window.location.href = currentUrl.toString();
        }
    }
});
}

// Filter functionality with loading states
const statusFilter = document.getElementById('statusFilter');
if (statusFilter) {
    statusFilter.addEventListener('change', function() {
    showFilterLoading();
    const status = this.value;
    const currentUrl = new URL(window.location);
    if (status === 'all') {
        currentUrl.searchParams.delete('status');
    } else {
        currentUrl.searchParams.set('status', status);
    }
    currentUrl.searchParams.set('p', '1');
    window.location.href = currentUrl.toString();
});
}

const priorityFilter = document.getElementById('priorityFilter');
if (priorityFilter) {
    priorityFilter.addEventListener('change', function() {
    showFilterLoading();
    const priority = this.value;
    const currentUrl = new URL(window.location);
    if (priority === 'all') {
        currentUrl.searchParams.delete('priority');
    } else {
        currentUrl.searchParams.set('priority', priority);
    }
    currentUrl.searchParams.set('p', '1');
    window.location.href = currentUrl.toString();
});
}

const sortFilter = document.getElementById('sortFilter');
if (sortFilter) {
    sortFilter.addEventListener('change', function() {
    showFilterLoading();
    const sort = this.value;
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('sort', sort);
    currentUrl.searchParams.set('p', '1');
    window.location.href = currentUrl.toString();
});
}

// Show loading state for filters
function showFilterLoading() {
    document.body.style.cursor = 'wait';
    showNotification('กำลังกรองข้อมูล...', 'info');
}

// Clear all filters with confirmation
function clearFilters() {
    const currentUrl = new URL(window.location);
    const hasFilters = currentUrl.searchParams.has('search') || 
                      currentUrl.searchParams.has('status') || 
                      currentUrl.searchParams.has('priority') || 
                      currentUrl.searchParams.has('sort');
    
    if (hasFilters) {
        if (confirm('คุณต้องการล้างตัวกรองทั้งหมดใช่หรือไม่?')) {
            currentUrl.searchParams.delete('search');
            currentUrl.searchParams.delete('status');
            currentUrl.searchParams.delete('priority');
            currentUrl.searchParams.delete('sort');
            currentUrl.searchParams.set('p', '1');
            window.location.href = currentUrl.toString();
        }
    } else {
        showNotification('ไม่มีตัวกรองที่ต้องล้าง', 'info');
    }
}

// Preview functionality
function previewAnnouncement(id) {
    window.open(`/?page=announcements&action=detail&id=${id}`, '_blank');
}

// Delete confirmation
function confirmDelete(id, title) {
    if (confirm(`คุณต้องการลบประกาศ "${title}" ใช่หรือไม่?\n\nการดำเนินการนี้ไม่สามารถยกเลิกได้`)) {
        window.location.href = `/admin?action=announcements&sub_action=delete&id=${id}`;
    }
}

// Bulk actions
function bulkPublish() {
    const selectedIds = getSelectedIds();
    if (selectedIds.length === 0) {
        alert('กรุณาเลือกประกาศที่ต้องการเผยแพร่');
        return;
    }
    
    if (confirm(`คุณต้องการเผยแพร่ประกาศ ${selectedIds.length} รายการใช่หรือไม่?`)) {
        // Implement bulk publish functionality
        console.log('Bulk publish:', selectedIds);
    }
}

function bulkDraft() {
    const selectedIds = getSelectedIds();
    if (selectedIds.length === 0) {
        alert('กรุณาเลือกประกาศที่ต้องการเปลี่ยนเป็นร่าง');
        return;
    }
    
    if (confirm(`คุณต้องการเปลี่ยนประกาศ ${selectedIds.length} รายการเป็นร่างใช่หรือไม่?`)) {
        // Implement bulk draft functionality
        console.log('Bulk draft:', selectedIds);
    }
}

function bulkDelete() {
    const selectedIds = getSelectedIds();
    if (selectedIds.length === 0) {
        alert('กรุณาเลือกประกาศที่ต้องการลบ');
        return;
    }
    
    if (confirm(`คุณต้องการลบประกาศ ${selectedIds.length} รายการใช่หรือไม่?\n\nการดำเนินการนี้ไม่สามารถยกเลิกได้`)) {
        // Implement bulk delete functionality
        console.log('Bulk delete:', selectedIds);
    }
}

function getSelectedIds() {
    const checkboxes = document.querySelectorAll('.announcement-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateSelectAllButton();
    updateBulkMenu();
    
    // Add loading animation
    document.querySelectorAll('.announcement-row').forEach((row, index) => {
        if (row) row.style.animationDelay = `${index * 0.1}s`;
    });
    

    
    document.querySelectorAll('.stat-card').forEach((card, index) => {
        if (card) card.style.animationDelay = `${index * 0.1}s`;
    });
    
    // Add hover effects to stat cards
    document.querySelectorAll('.stat-card').forEach(card => {
        if (card) {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
            }
        });
    

});



// Notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Image Modal functionality
function showImageModal(imageUrl, title) {
    const modalTitle = document.getElementById('imageModalTitle');
    const modalImage = document.getElementById('modalImage');
    const modalElement = document.getElementById('imageModal');
    
    if (modalTitle) modalTitle.textContent = title;
    if (modalImage) {
    // Show loading state
    modalImage.style.opacity = '0.5';
    modalImage.src = `/uploads/announcements/${imageUrl}`;
    
    // Restore image opacity when loaded
    modalImage.onload = function() {
        this.style.opacity = '1';
    };
    
    // Handle image load error
    modalImage.onerror = function() {
        this.src = '/assets/img/placeholder-image.jpg';
        this.style.opacity = '1';
    };
}
    
    // Show modal if Bootstrap is available
    if (modalElement && typeof bootstrap !== 'undefined') {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    }
}

// Enhanced animations and interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add animation delays to stat cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        card.style.setProperty('--card-index', index);
    });
    
    // Add hover effects to stat items
    const statItems = document.querySelectorAll('.stat-item');
    statItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.02)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add ripple effect to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255,255,255,0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});

// Add ripple animation
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>

<!-- Enhanced JavaScript for Announcements Page -->
<script src="/assets/js/announcements-enhanced.js"></script>
<script src="/assets/js/action-buttons-enhanced.js"></script>

<?php
function getPriorityColor($priority) {
    switch ($priority) {
        case 'urgent':
            return '#dc3545';
        case 'high':
            return '#fd7e14';
        case 'normal':
            return '#0d6efd';
        case 'low':
            return '#6c757d';
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