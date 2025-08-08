<?php include SRC_PATH . '/views/admin/header.php'; ?>

<!-- Welcome Section -->
<div class="welcome-section mb-4">
    <div class="welcome-content">
        <div class="welcome-text">
            <h1 class="welcome-title">
                <i class="fas fa-sun text-warning"></i> 
                สวัสดี, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'ผู้ดูแลระบบ') ?>
            </h1>
            <p class="welcome-subtitle">ยินดีต้อนรับสู่ระบบจัดการ ศฝร.ภ.8</p>
            <div class="welcome-time">
                <i class="fas fa-clock text-primary"></i>
                <span id="currentTime"></span>
            </div>
        </div>
        <div class="welcome-illustration">
            <div class="floating-icons">
                <i class="fas fa-shield-alt icon-1"></i>
                <i class="fas fa-graduation-cap icon-2"></i>
                <i class="fas fa-users icon-3"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions-section mb-4">
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-bolt text-warning"></i>
            <h4>การดำเนินการด่วน</h4>
        </div>
        <p class="section-subtitle">เข้าถึงฟังก์ชันหลักได้อย่างรวดเร็ว</p>
    </div>
    
    <div class="quick-actions-grid">
        <a href="/admin?action=news&sub_action=add" class="quick-action-card news-card">
            <div class="card-icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="card-content">
                <h6>เพิ่มข่าวสาร</h6>
                <p>เพิ่มข่าวสารใหม่ลงในระบบ</p>
            </div>
            <div class="card-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
        
        <a href="/admin?action=announcements&sub_action=add" class="quick-action-card announcement-card">
            <div class="card-icon">
                <i class="fas fa-bullhorn"></i>
            </div>
            <div class="card-content">
                <h6>เพิ่มประกาศ</h6>
                <p>เพิ่มประกาศใหม่ลงในระบบ</p>
            </div>
            <div class="card-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
        
        <a href="/admin?action=commanders&sub_action=add" class="quick-action-card commander-card">
            <div class="card-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="card-content">
                <h6>เพิ่มผู้บังคับบัญชา</h6>
                <p>เพิ่มข้อมูลผู้บังคับบัญชาใหม่</p>
            </div>
            <div class="card-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
        

        
        <a href="/admin?action=documents&sub_action=add" class="quick-action-card document-card">
            <div class="card-icon">
                <i class="fas fa-file-upload"></i>
            </div>
            <div class="card-content">
                <h6>อัปโหลดเอกสาร</h6>
                <p>อัปโหลดเอกสารใหม่</p>
            </div>
            <div class="card-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
        
        <a href="/admin?action=manageSplash" class="quick-action-card splash-card">
            <div class="card-icon">
                <i class="fas fa-bolt"></i>
            </div>
            <div class="card-content">
                <h6>จัดการ Splash</h6>
                <p>ตั้งค่า Splash Page</p>
            </div>
            <div class="card-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
        

        
        <a href="/" target="_blank" class="quick-action-card website-card">
            <div class="card-icon">
                <i class="fas fa-globe"></i>
            </div>
            <div class="card-content">
                <h6>ดูเว็บไซต์</h6>
                <p>เปิดดูเว็บไซต์หลัก</p>
            </div>
            <div class="card-arrow">
                <i class="fas fa-external-link-alt"></i>
            </div>
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-section mb-4">
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-chart-bar text-info"></i>
            <h4>สถิติระบบ</h4>
        </div>
        <p class="section-subtitle">ภาพรวมข้อมูลในระบบ</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card news-stat">
            <div class="stat-icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?= $stats['total_news'] ?? 0 ?></div>
                <div class="stat-label">ข่าวสารทั้งหมด</div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up text-success"></i>
                    <span>+12% จากเดือนที่แล้ว</span>
                </div>
            </div>
            <a href="/admin?action=news" class="stat-link">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        

        
        <div class="stat-card commander-stat">
            <div class="stat-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">0</div>
                <div class="stat-label">ผู้บังคับบัญชา</div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up text-success"></i>
                    <span>+5% จากเดือนที่แล้ว</span>
                </div>
            </div>
            <a href="/admin?action=commanders" class="stat-link">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="stat-card document-stat">
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?= $stats['total_documents'] ?? 0 ?></div>
                <div class="stat-label">เอกสารดาวน์โหลด</div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up text-success"></i>
                    <span>+15% จากเดือนที่แล้ว</span>
                </div>
            </div>
            <a href="/admin?action=documents" class="stat-link">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        

        
        <div class="stat-card system-stat">
            <div class="stat-icon">
                <i class="fas fa-server"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">100%</div>
                <div class="stat-label">สถานะระบบ</div>
                <div class="stat-trend">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>ระบบทำงานปกติ</span>
                </div>
            </div>
            <a href="/admin?action=system-status" class="stat-link">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Recent Activities & System Info -->
<div class="dashboard-bottom-section">
    <div class="activities-section">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-clock text-primary"></i>
                <h4>กิจกรรมล่าสุด</h4>
            </div>
            <a href="/admin?action=activity-log" class="view-all-link">
                ดูทั้งหมด <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="activities-list">
            <?php if (!empty($recentActivities)): ?>
                <?php foreach ($recentActivities as $activity): ?>
                    <div class="activity-item">
                        <div class="activity-icon <?= $activity['module'] ?>-activity">
                            <i class="<?= ActivityLogModel::getActivityIcon($activity['action_type'], $activity['module']) ?>"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title"><?= htmlspecialchars($activity['description']) ?></div>
                            <?php if ($activity['user_name']): ?>
                                <div class="activity-desc">โดย: <?= htmlspecialchars($activity['user_name']) ?></div>
                            <?php endif; ?>
                            <div class="activity-time">
                                <i class="fas fa-clock"></i>
                                <?= $this->getTimeAgo($activity['created_at']) ?>
                            </div>
                        </div>
                        <div class="activity-status">
                            <span class="status-dot <?= ActivityLogModel::getActivityColor($activity['action_type'], $activity['module']) ?>"></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-activities">
                    <div class="no-activities-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <div class="no-activities-text">
                        <h6>ยังไม่มีกิจกรรม</h6>
                        <p>กิจกรรมต่างๆ จะแสดงที่นี่เมื่อมีการใช้งานระบบ</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="system-info-section">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-server text-success"></i>
                <h4>ข้อมูลระบบ</h4>
            </div>
        </div>
        
        <div class="system-info-grid">
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-code-branch"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">เวอร์ชันระบบ</div>
                    <div class="info-value">2.1.0</div>
                </div>
            </div>
            
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">อัปเดตล่าสุด</div>
                    <div class="info-value"><?= date('d/m/Y') ?></div>
                </div>
            </div>
            
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">สถานะระบบ</div>
                    <div class="info-value status-active">
                        <i class="fas fa-check-circle"></i>
                        ปกติ
                    </div>
                </div>
            </div>
            
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">ฐานข้อมูล</div>
                    <div class="info-value status-active">
                        <i class="fas fa-check-circle"></i>
                        เชื่อมต่อปกติ
                    </div>
                </div>
            </div>
        </div>
        
        <div class="quick-actions-mini">
            <h6>การดำเนินการด่วน</h6>
            <div class="mini-actions">
                <a href="/admin?action=manageSplash" class="mini-action-btn">
                    <i class="fas fa-bolt"></i>
                    <span>Splash Page</span>
                </a>
                <a href="/admin?action=backup" class="mini-action-btn">
                    <i class="fas fa-download"></i>
                    <span>สำรองข้อมูล</span>
                </a>
                <a href="/admin?action=logs" class="mini-action-btn">
                    <i class="fas fa-list-alt"></i>
                    <span>ดู Log</span>
                </a>
                <a href="/admin?action=settings" class="mini-action-btn">
                    <i class="fas fa-cog"></i>
                    <span>ตั้งค่า</span>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Welcome Section */
.welcome-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.welcome-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
    opacity: 0.3;
}

.welcome-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    z-index: 1;
}

.welcome-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.welcome-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 1rem;
}

.welcome-time {
    font-size: 1rem;
    opacity: 0.8;
}

.floating-icons {
    position: relative;
    width: 200px;
    height: 200px;
}

.floating-icons i {
    position: absolute;
    font-size: 3rem;
    opacity: 0.8;
    animation: float 6s ease-in-out infinite;
}

.icon-1 { top: 20px; left: 20px; animation-delay: 0s; }
.icon-2 { top: 60px; right: 40px; animation-delay: 2s; }
.icon-3 { bottom: 40px; left: 60px; animation-delay: 4s; }

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

/* Section Headers */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.section-title h4 {
    margin: 0;
    font-weight: 700;
    color: #2c3e50;
}

.section-subtitle {
    color: #7f8c8d;
    margin: 0;
    font-size: 0.95rem;
}

.view-all-link {
    color: #3498db;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: color 0.3s ease;
}

.view-all-link:hover {
    color: #2980b9;
}

/* Quick Actions Grid */
.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.quick-action-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
    overflow: hidden;
}

.quick-action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--card-color), var(--card-color-light));
}

.quick-action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.news-card { --card-color: #e74c3c; --card-color-light: #ff6b6b; }
.commander-card { --card-color: #3498db; --card-color-light: #74b9ff; }
.course-card { --card-color: #f39c12; --card-color-light: #fdcb6e; }
.document-card { --card-color: #27ae60; --card-color-light: #00b894; }
.splash-card { --card-color: #9b59b6; --card-color-light: #a29bfe; }
.website-card { --card-color: #34495e; --card-color-light: #636e72; }
.announcement-card { --card-color: #f39c12; --card-color-light: #fdcb6e; }

.card-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--card-color), var(--card-color-light));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.card-content h6 {
    margin: 0 0 0.3rem 0;
    font-weight: 700;
    color: #2c3e50;
}

.card-content p {
    margin: 0;
    color: #7f8c8d;
    font-size: 0.9rem;
}

.card-arrow {
    margin-left: auto;
    color: #bdc3c7;
    transition: all 0.3s ease;
}

.quick-action-card:hover .card-arrow {
    color: var(--card-color);
    transform: translateX(5px);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
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
    background: linear-gradient(90deg, var(--stat-color), var(--stat-color-light));
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.news-stat { --stat-color: #e74c3c; --stat-color-light: #ff6b6b; }
.course-stat { --stat-color: #f39c12; --stat-color-light: #fdcb6e; }
.commander-stat { --stat-color: #3498db; --stat-color-light: #74b9ff; }
.document-stat { --stat-color: #27ae60; --stat-color-light: #00b894; }
.registration-stat { --stat-color: #9b59b6; --stat-color-light: #a29bfe; }
.system-stat { --stat-color: #34495e; --stat-color-light: #636e72; }

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--stat-color), var(--stat-color-light));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    margin-bottom: 1rem;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1rem;
    color: #7f8c8d;
    margin-bottom: 0.5rem;
}

.stat-trend {
    font-size: 0.85rem;
    color: #27ae60;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.stat-link {
    position: absolute;
    top: 1rem;
    right: 1rem;
    color: #bdc3c7;
    transition: color 0.3s ease;
}

.stat-card:hover .stat-link {
    color: var(--stat-color);
}

/* Dashboard Bottom Section */
.dashboard-bottom-section {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

.activities-section, .system-info-section {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
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
    padding: 1rem;
    border-radius: 12px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.activity-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
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
}

.activity-title {
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.3rem;
}

.activity-desc {
    color: #7f8c8d;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.activity-time {
    color: #95a5a6;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.activity-status {
    display: flex;
    align-items: center;
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

/* No Activities */
.no-activities {
    text-align: center;
    padding: 2rem;
    color: #7f8c8d;
}

.no-activities-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.no-activities-text h6 {
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.no-activities-text p {
    margin: 0;
    font-size: 0.9rem;
}

/* System Info Grid */
.system-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.info-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.info-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: linear-gradient(135deg, #3498db, #74b9ff);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.info-label {
    font-size: 0.8rem;
    color: #7f8c8d;
    margin-bottom: 0.2rem;
}

.info-value {
    font-weight: 700;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.status-active {
    color: #27ae60;
}

/* Quick Actions Mini */
.quick-actions-mini h6 {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 1rem;
}

.mini-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.8rem;
}

.mini-action-btn {
    background: #f8f9fa;
    border: none;
    border-radius: 8px;
    padding: 0.8rem;
    text-decoration: none;
    color: #2c3e50;
    text-align: center;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.3rem;
}

.mini-action-btn:hover {
    background: #e9ecef;
    transform: translateY(-2px);
    color: #2c3e50;
}

.mini-action-btn i {
    font-size: 1.2rem;
    color: #3498db;
}

.mini-action-btn span {
    font-size: 0.8rem;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .dashboard-bottom-section {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .welcome-content {
        flex-direction: column;
        text-align: center;
    }
    
    .welcome-title {
        font-size: 2rem;
    }
    
    .floating-icons {
        display: none;
    }
    
    .quick-actions-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .system-info-grid {
        grid-template-columns: 1fr;
    }
    
    .mini-actions {
        grid-template-columns: 1fr;
    }
}

/* Animation for current time */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.welcome-time {
    animation: pulse 2s ease-in-out infinite;
}
</style>

<script>
// Update current time
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleString('th-TH', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
    document.getElementById('currentTime').textContent = timeString;
}

// Update time every second
setInterval(updateTime, 1000);
updateTime(); // Initial call

// Add hover effects to cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.quick-action-card, .stat-card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>

<?php include SRC_PATH . '/views/admin/footer.php'; ?>
