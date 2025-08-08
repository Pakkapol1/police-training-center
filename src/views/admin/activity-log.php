<?php include SRC_PATH . '/views/admin/header.php'; ?>

<!-- Page Header -->
<div class="page-header mb-4">
    <div class="page-header-content">
        <div class="page-title">
            <h1><i class="fas fa-history text-primary"></i> ประวัติกิจกรรม</h1>
            <p>ติดตามการใช้งานระบบทั้งหมด</p>
        </div>
        <div class="page-actions">
            <a href="/admin?action=dashboard" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> กลับไปหน้า Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Filters Section -->
<div class="filters-section mb-4">
    <div class="filters-card">
        <form method="GET" action="/admin" class="filters-form">
            <input type="hidden" name="action" value="activity-log">
            
            <div class="filters-row">
                <div class="filter-group">
                    <label for="module">โมดูล:</label>
                    <select name="module" id="module" class="form-select">
                        <option value="">ทั้งหมด</option>
                        <option value="news" <?= ($filters['module'] ?? '') === 'news' ? 'selected' : '' ?>>ข่าวสาร</option>
                        <option value="commanders" <?= ($filters['module'] ?? '') === 'commanders' ? 'selected' : '' ?>>ผู้บังคับบัญชา</option>
                        <option value="documents" <?= ($filters['module'] ?? '') === 'documents' ? 'selected' : '' ?>>เอกสาร</option>
                        <option value="auth" <?= ($filters['module'] ?? '') === 'auth' ? 'selected' : '' ?>>การเข้าสู่ระบบ</option>
                        <option value="splash" <?= ($filters['module'] ?? '') === 'splash' ? 'selected' : '' ?>>Splash Page</option>
                        <option value="slides" <?= ($filters['module'] ?? '') === 'slides' ? 'selected' : '' ?>>สไลด์</option>

                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="action_type">ประเภท:</label>
                    <select name="action_type" id="action_type" class="form-select">
                        <option value="">ทั้งหมด</option>
                        <option value="create" <?= ($filters['action_type'] ?? '') === 'create' ? 'selected' : '' ?>>สร้าง</option>
                        <option value="update" <?= ($filters['action_type'] ?? '') === 'update' ? 'selected' : '' ?>>แก้ไข</option>
                        <option value="delete" <?= ($filters['action_type'] ?? '') === 'delete' ? 'selected' : '' ?>>ลบ</option>
                        <option value="login" <?= ($filters['action_type'] ?? '') === 'login' ? 'selected' : '' ?>>เข้าสู่ระบบ</option>
                        <option value="logout" <?= ($filters['action_type'] ?? '') === 'logout' ? 'selected' : '' ?>>ออกจากระบบ</option>
                        <option value="upload" <?= ($filters['action_type'] ?? '') === 'upload' ? 'selected' : '' ?>>อัปโหลด</option>
                        <option value="download" <?= ($filters['action_type'] ?? '') === 'download' ? 'selected' : '' ?>>ดาวน์โหลด</option>
                        <option value="approve" <?= ($filters['action_type'] ?? '') === 'approve' ? 'selected' : '' ?>>อนุมัติ</option>
                        <option value="reject" <?= ($filters['action_type'] ?? '') === 'reject' ? 'selected' : '' ?>>ปฏิเสธ</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="date_from">วันที่เริ่ม:</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="<?= $filters['date_from'] ?? '' ?>">
                </div>
                
                <div class="filter-group">
                    <label for="date_to">วันที่สิ้นสุด:</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="<?= $filters['date_to'] ?? '' ?>">
                </div>
                
                <div class="filter-group">
                    <label for="search">ค้นหา:</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="ค้นหาข้อความ..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> ค้นหา
                    </button>
                    <a href="/admin?action=activity-log" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> ล้าง
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-overview mb-4">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-list"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?= $totalActivities ?></div>
                <div class="stat-label">กิจกรรมทั้งหมด</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?= $uniqueUsers ?></div>
                <div class="stat-label">ผู้ใช้งาน</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?= $todayActivities ?></div>
                <div class="stat-label">กิจกรรมวันนี้</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?= $thisWeekActivities ?></div>
                <div class="stat-label">กิจกรรมสัปดาห์นี้</div>
            </div>
        </div>
    </div>
</div>

<!-- Activities List -->
<div class="activities-container">
    <div class="activities-header">
        <div class="activities-title">
            <h3>รายการกิจกรรม</h3>
            <span class="activities-count"><?= count($activities) ?> รายการ</span>
        </div>
        <div class="activities-export">
            <button class="btn btn-outline-success" onclick="exportActivities()">
                <i class="fas fa-download"></i> ส่งออก
            </button>
        </div>
    </div>
    
    <?php if (!empty($activities)): ?>
        <div class="activities-list">
            <?php foreach ($activities as $activity): ?>
                <div class="activity-item">
                    <div class="activity-icon <?= $activity['module'] ?>-activity">
                        <i class="<?= ActivityLogModel::getActivityIcon($activity['action_type'], $activity['module']) ?>"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-main">
                            <div class="activity-title"><?= htmlspecialchars($activity['description']) ?></div>
                            <div class="activity-meta">
                                <?php if ($activity['user_name']): ?>
                                    <span class="activity-user">
                                        <i class="fas fa-user"></i>
                                        <?= htmlspecialchars($activity['user_name']) ?>
                                    </span>
                                <?php endif; ?>
                                <span class="activity-module">
                                    <i class="fas fa-folder"></i>
                                    <?= $moduleNames[$activity['module']] ?? $activity['module'] ?>
                                </span>
                                <span class="activity-time">
                                    <i class="fas fa-clock"></i>
                                    <?= date('d/m/Y H:i:s', strtotime($activity['created_at'])) ?>
                                </span>
                                <?php if ($activity['ip_address']): ?>
                                    <span class="activity-ip">
                                        <i class="fas fa-network-wired"></i>
                                        <?= htmlspecialchars($activity['ip_address']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="activity-actions">
                            <span class="activity-status">
                                <span class="status-dot <?= ActivityLogModel::getActivityColor($activity['action_type'], $activity['module']) ?>"></span>
                                <?= $actionNames[$activity['action_type']] ?? $activity['action_type'] ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination-container">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?action=activity-log&page=<?= $page - 1 ?>&<?= http_build_query($filters) ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                <a class="page-link" href="?action=activity-log&page=<?= $i ?>&<?= http_build_query($filters) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?action=activity-log&page=<?= $page + 1 ?>&<?= http_build_query($filters) ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="no-activities">
            <div class="no-activities-icon">
                <i class="fas fa-search"></i>
            </div>
            <div class="no-activities-text">
                <h6>ไม่พบกิจกรรม</h6>
                <p>ลองเปลี่ยนเงื่อนไขการค้นหาหรือวันที่</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
/* Page Header */
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 2rem;
    color: white;
}

.page-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-title h1 {
    margin: 0 0 0.5rem 0;
    font-size: 2rem;
    font-weight: 700;
}

.page-title p {
    margin: 0;
    opacity: 0.9;
}

/* Filters Section */
.filters-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.filters-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-group label {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9rem;
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
}

/* Stats Overview */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: linear-gradient(135deg, #3498db, #74b9ff);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: 800;
    color: #2c3e50;
    margin-bottom: 0.2rem;
}

.stat-label {
    color: #7f8c8d;
    font-size: 0.9rem;
}

/* Activities Container */
.activities-container {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.activities-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.activities-title {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.activities-title h3 {
    margin: 0;
    color: #2c3e50;
}

.activities-count {
    background: #e9ecef;
    color: #6c757d;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

/* Activities List */
.activities-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    border-radius: 12px;
    background: #f8f9fa;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.activity-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.activity-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.news-activity { background: linear-gradient(135deg, #e74c3c, #ff6b6b); }
.courses-activity { background: linear-gradient(135deg, #f39c12, #fdcb6e); }
.commanders-activity { background: linear-gradient(135deg, #3498db, #74b9ff); }
.documents-activity { background: linear-gradient(135deg, #27ae60, #00b894); }
.registrations-activity { background: linear-gradient(135deg, #9b59b6, #a29bfe); }
.auth-activity { background: linear-gradient(135deg, #34495e, #636e72); }
.splash-activity { background: linear-gradient(135deg, #e67e22, #f39c12); }

.activity-content {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
}

.activity-main {
    flex: 1;
}

.activity-title {
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.activity-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    font-size: 0.85rem;
    color: #6c757d;
}

.activity-meta span {
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.activity-actions {
    display: flex;
    align-items: center;
}

.activity-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    font-weight: 600;
}

.status-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.status-dot.success { background: #27ae60; }
.status-dot.info { background: #3498db; }
.status-dot.warning { background: #f39c12; }
.status-dot.danger { background: #e74c3c; }
.status-dot.primary { background: #3498db; }
.status-dot.secondary { background: #95a5a6; }

/* Pagination */
.pagination-container {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

.pagination {
    display: flex;
    gap: 0.5rem;
}

.page-link {
    padding: 0.5rem 1rem;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    color: #007bff;
    text-decoration: none;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: #e9ecef;
    border-color: #007bff;
}

.page-item.active .page-link {
    background: #007bff;
    border-color: #007bff;
    color: white;
}

/* No Activities */
.no-activities {
    text-align: center;
    padding: 3rem;
    color: #7f8c8d;
}

.no-activities-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.no-activities-text h6 {
    margin-bottom: 0.5rem;
    color: #2c3e50;
    font-size: 1.2rem;
}

.no-activities-text p {
    margin: 0;
    font-size: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header-content {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .filters-row {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .activities-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .activity-content {
        flex-direction: column;
        gap: 1rem;
    }
    
    .activity-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>

<script>
function exportActivities() {
    // สร้าง URL สำหรับส่งออกข้อมูล
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('export', '1');
    
    // สร้าง link และคลิกเพื่อดาวน์โหลด
    const link = document.createElement('a');
    link.href = currentUrl.toString();
    link.download = 'activity-log-' + new Date().toISOString().split('T')[0] + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Auto-submit form when filters change
document.addEventListener('DOMContentLoaded', function() {
    const filterSelects = document.querySelectorAll('.filters-form select');
    const filterInputs = document.querySelectorAll('.filters-form input[type="date"]');
    
    [...filterSelects, ...filterInputs].forEach(element => {
        element.addEventListener('change', function() {
            document.querySelector('.filters-form').submit();
        });
    });
});
</script>

<?php include SRC_PATH . '/views/admin/footer.php'; ?> 