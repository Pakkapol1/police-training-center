<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการข่าวสาร - ระบบจัดการ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
            --shadow-light: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            --shadow-medium: 0 15px 35px rgba(0, 0, 0, 0.1);
            --shadow-heavy: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding-top: 80px;
        }

        /* Glass morphism navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow-light);
            min-height: 70px;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            color: white !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 0.5rem;
            padding: 0.5rem 1.2rem !important;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        /* Modern breadcrumb */
        .breadcrumb {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-light);
        }

        .breadcrumb-item a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb-item.active {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
        }

        /* Modern cards */
        .modern-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: var(--shadow-medium);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-heavy);
        }

        .modern-card-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.8) 0%, rgba(255, 255, 255, 0.6) 100%);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.5rem 2rem;
            border-radius: 24px 24px 0 0;
        }

        .modern-card-body {
            padding: 2rem;
        }

        /* Header section */
        .page-header {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-light);
        }

        .page-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .page-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            font-weight: 400;
            margin-bottom: 0;
        }

        /* Modern buttons */
        .btn-modern {
            border-radius: 16px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-modern:hover::before {
            left: 100%;
        }

        .btn-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .btn-primary-modern {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-success-modern {
            background: var(--success-gradient);
            color: white;
        }

        .btn-warning-modern {
            background: var(--warning-gradient);
            color: white;
        }

        .btn-danger-modern {
            background: var(--danger-gradient);
            color: white;
        }

        /* Modern form controls */
        .form-control-modern {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .form-control-modern:focus {
            background: white;
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
            transform: translateY(-2px);
        }

        .input-group-modern {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .input-group-modern .input-group-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            font-weight: 600;
        }

        /* Modern table */
        .table-modern {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .table-modern thead th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #495057;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            padding: 1.25rem 1rem;
        }

        .table-modern tbody tr {
            transition: all 0.3s ease;
            border: none;
        }

        .table-modern tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
            transform: scale(1.01);
        }

        .table-modern td {
            padding: 1.25rem 1rem;
            border: none;
            vertical-align: middle;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        /* Modern badges */
        .badge-modern {
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success-modern {
            background: var(--success-gradient);
            color: white;
        }

        .badge-warning-modern {
            background: var(--warning-gradient);
            color: white;
        }

        .badge-info-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .badge-secondary-modern {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }

        /* Modern action buttons */
        .btn-action {
            width: 35px;
            height: 35px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 2px;
            transition: all 0.3s ease;
            border: none;
            font-size: 0.9rem;
        }

        .btn-action:hover {
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .btn-action-view {
            background: var(--success-gradient);
            color: white;
        }

        .btn-action-edit {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-action-delete {
            background: var(--danger-gradient);
            color: white;
        }

        .btn-action-preview {
            background: var(--warning-gradient);
            color: white;
        }

        /* Modern image styling */
        .news-thumbnail {
            width: 50px;
            height: 40px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .news-thumbnail:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        /* Pagination modern */
        .pagination-modern .page-link {
            border: none;
            border-radius: 12px;
            margin: 0 3px;
            padding: 0.75rem 1rem;
            color: #667eea;
            font-weight: 600;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .pagination-modern .page-item.active .page-link {
            background: var(--primary-gradient);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .pagination-modern .page-link:hover {
            background: var(--primary-gradient);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }
            
            .modern-card-body {
                padding: 1.5rem;
            }
            
            .page-header {
                padding: 1.5rem;
            }
            
            .table-responsive {
                border-radius: 16px;
            }
        }

        /* Loading animations */
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .loading {
            animation: pulse 2s infinite;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/admin?action=dashboard">
                <i class="fas fa-cog me-2"></i> ระบบจัดการ
            </a>
            <div class="navbar-nav ms-auto align-items-center">
                <a class="nav-link" href="/admin?action=dashboard">
                    <i class="fas fa-tachometer-alt me-1"></i> แดชบอร์ด
                </a>
                <a class="nav-link" href="/" target="_blank">
                    <i class="fas fa-external-link-alt me-1"></i> ดูเว็บไซต์
                </a>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="container-fluid mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin?action=dashboard">แดชบอร์ด</a></li>
                <li class="breadcrumb-item active">จัดการข่าวสาร</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid">
        <!-- Header Section -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-newspaper me-3"></i> จัดการข่าวสาร
                    </h1>
                    <p class="page-subtitle">จัดการข่าวสารและประกาศของเว็บไซต์อย่างมีประสิทธิภาพ</p>
                </div>
                <a href="/admin?action=news&sub_action=add" class="btn btn-modern btn-primary-modern btn-lg">
                    <i class="fas fa-plus me-2"></i> เพิ่มข่าวใหม่
                </a>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="modern-card mb-4">
            <div class="modern-card-body">
                <div class="row align-items-center g-3">
                    <div class="col-lg-5">
                        <div class="input-group input-group-modern">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control form-control-modern" id="searchInput" placeholder="ค้นหาข่าวสาร...">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <select class="form-select form-control-modern" id="statusFilter">
                            <option value="">สถานะทั้งหมด</option>
                            <option value="published">เผยแพร่</option>
                            <option value="draft">ร่าง</option>
                        </select>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <div class="d-flex align-items-center justify-content-lg-end justify-content-start">
                            <span class="badge badge-modern badge-info-modern me-3">
                                <i class="fas fa-list me-1"></i> 
                                ทั้งหมด <span id="totalCount"><?= count($newsList ?? []) ?></span> รายการ
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold">รายการข่าวสาร</h4>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-modern btn-success-modern btn-sm" onclick="selectAll()">
                            <i class="fas fa-check-square me-1"></i> เลือกทั้งหมด
                        </button>
                        <button type="button" class="btn btn-modern btn-danger-modern btn-sm" onclick="deleteSelected()" id="deleteSelectedBtn" style="display: none;">
                            <i class="fas fa-trash me-1"></i> ลบที่เลือก
                        </button>
                    </div>
                </div>
            </div>
            <div class="modern-card-body p-0">
                <?php if (empty($newsList ?? [])): ?>
                    <div class="empty-state">
                        <i class="fas fa-newspaper"></i>
                        <h4 class="fw-bold">ยังไม่มีข่าวสาร</h4>
                        <p class="mb-4">เริ่มต้นสร้างข่าวสารแรกของคุณเพื่อแบ่งปันข้อมูลกับผู้เข้าชม</p>
                        <a href="/admin?action=news&sub_action=add" class="btn btn-modern btn-primary-modern btn-lg">
                            <i class="fas fa-plus me-2"></i> เพิ่มข่าวแรก
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-modern mb-0" id="newsTable">
                            <thead>
                                <tr>
                                    <th width="50"><input type="checkbox" class="form-check-input" id="selectAllCheckbox"></th>
                                    <th width="60">ID</th>
                                    <th width="70">รูป</th>
                                    <th>หัวข้อข่าว</th>
                                    <th width="130">หมวดหมู่</th>
                                    <th width="100">สถานะ</th>
                                    <th width="150">วันที่สร้าง</th>
                                    <th width="120">ผู้เขียน</th>
                                    <th width="180">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($newsList as $news): ?>
                                    <?php
                                    // Use the NewsModel instance from the controller
                                    $images = $this->newsModel->getNewsImages($news['id']);
                                    ?>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-checkbox" value="<?= $news['id'] ?>"></td>
                                        <td><span class="badge badge-modern badge-secondary-modern">#<?= $news['id'] ?></span></td>
                                        <td>
                                            <?php if (!empty($images)): ?>
                                                <img src="<?= htmlspecialchars($images[0]['image_path']) ?>" alt="thumb" class="news-thumbnail">
                                            <?php else: ?>
                                                <div class="d-flex align-items-center justify-content-center" style="width:50px; height:40px; background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%); border-radius: 12px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark mb-1"><?= htmlspecialchars($news['title']) ?></div>
                                            <?php if (!empty($news['excerpt'])): ?>
                                                <small class="text-muted"><?= htmlspecialchars(substr($news['excerpt'], 0, 60)) ?>...</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $categories = [];
                                            if (!empty($news['category'])) {
                                                $categories = array_map('trim', explode(',', $news['category']));
                                            }
                                            ?>
                                            <?php if (!empty($categories)): ?>
                                                <?php foreach ($categories as $cat): ?>
                                                    <span class="badge badge-modern badge-info-modern me-1 mb-1"><?= htmlspecialchars($cat) ?></span>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="badge badge-modern badge-secondary-modern">ทั่วไป</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-modern <?= $news['status'] === 'published' ? 'badge-success-modern' : 'badge-warning-modern' ?>">
                                                <?= $news['status'] === 'published' ? 'เผยแพร่' : 'ร่าง' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">
                                                <?php
                                                $dt = strtotime($news['created_at']);
                                                echo date('d/m/Y', $dt);
                                                ?>
                                            </div>
                                            <small class="text-muted"><?= date('H:i', $dt) ?> น.</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($news['author_name'] ?? $news['author'] ?? 'admin') ?></div>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="/?page=news&action=detail&id=<?= $news['id'] ?>" class="btn btn-action btn-action-view" target="_blank" title="ดูข่าว">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-action btn-action-preview" title="ดูตัวอย่าง" onclick="showPreviewModal(<?= $news['id'] ?>)">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                                <a href="/admin?action=news&sub_action=edit&id=<?= $news['id'] ?>" class="btn btn-action btn-action-edit" title="แก้ไข">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-action btn-action-delete" 
                                                        onclick="confirmDeleteNews(<?= $news['id'] ?>, '<?= htmlspecialchars($news['title']) ?>')" title="ลบ">
                                                    <i class="fas fa-trash"></i>
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
                    <div class="modern-card-header border-top-0 bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted fw-semibold">
                                แสดง <?= ($page - 1) * 10 + 1 ?>-<?= ($page - 1) * 10 + count($newsList) ?> จาก <?= $totalNews ?> รายการ
                            </span>
                            <nav>
                                <ul class="pagination pagination-modern mb-0">
                                    <li class="page-item<?= $page <= 1 ? ' disabled' : '' ?>">
                                        <a class="page-link" href="?action=news&p=<?= $page-1 ?>">
                                            <i class="fas fa-chevron-left me-1"></i> ก่อนหน้า
                                        </a>
                                    </li>
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item<?= $i == $page ? ' active' : '' ?>">
                                            <a class="page-link" href="?action=news&p=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    <li class="page-item<?= $page >= $totalPages ? ' disabled' : '' ?>">
                                        <a class="page-link" href="?action=news&p=<?= $page+1 ?>">
                                            ถัดไป <i class="fas fa-chevron-right ms-1"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Preview ข่าว -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modern-card">
          <div class="modal-header modern-card-header">
            <h5 class="modal-title fw-bold" id="previewModalLabel">
                <i class="fas fa-eye me-2"></i> ตัวอย่างข่าว
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body modern-card-body" id="previewModalBody">
            <!-- ข้อมูลข่าวจะแสดงที่นี่ -->
          </div>
          <div class="modal-footer modern-card-header border-top">
            <button type="button" class="btn btn-modern btn-secondary-modern" data-bs-dismiss="modal">ปิด</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ฟังก์ชันแสดง preview modal
        function showPreviewModal(newsId) {
            const news = newsData[newsId];
            if (!news) return;
            
            const modalBody = document.getElementById('previewModalBody');
            modalBody.innerHTML = `
                <div class="row">
                    <div class="col-12 mb-3">
                        <h4 class="fw-bold">${news.title}</h4>
                        <div class="d-flex align-items-center text-muted mb-3">
                            <small><i class="fas fa-calendar me-1"></i> ${news.date}</small>
                            <span class="mx-2">•</span>
                            <small><i class="fas fa-user me-1"></i> ${news.author}</small>
                        </div>
                    </div>
                    ${news.image ? `
                    <div class="col-md-4 mb-3">
                        <img src="${news.image}" alt="รูปข่าว" class="img-fluid rounded shadow-sm">
                    </div>
                    ` : ''}
                    <div class="col-md-${news.image ? '8' : '12'}">
                        <div class="text-muted">${news.content ? news.content.substring(0, 300) + '...' : 'ไม่มีเนื้อหา'}</div>
                    </div>
                </div>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            modal.show();
        }

        // ฟังก์ชันค้นหา
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('newsTable');
            const rows = table.getElementsByTagName('tr');
            let visibleCount = 0;
            
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const title = row.cells[3].textContent.toLowerCase();
                
                if (title.includes(searchTerm)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            }
            
            document.getElementById('totalCount').textContent = visibleCount;
        });

        // ฟังก์ชันกรองตามสถานะ
        document.getElementById('statusFilter').addEventListener('change', function() {
            const statusFilter = this.value;
            const table = document.getElementById('newsTable');
            const rows = table.getElementsByTagName('tr');
            let visibleCount = 0;
            
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const statusBadge = row.cells[5].querySelector('.badge');
                const status = statusBadge.textContent.trim() === 'เผยแพร่' ? 'published' : 'draft';
                
                if (!statusFilter || status === statusFilter) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            }
            
            document.getElementById('totalCount').textContent = visibleCount;
        });

        // ฟังก์ชันเลือกทั้งหมด
        function selectAll() {
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            const checkboxes = document.querySelectorAll('.row-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            
            toggleDeleteButton();
        }

        // ฟังก์ชันแสดง/ซ่อนปุ่มลบ
        function toggleDeleteButton() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const deleteBtn = document.getElementById('deleteSelectedBtn');
            
            if (checkedBoxes.length > 0) {
                deleteBtn.style.display = 'inline-block';
            } else {
                deleteBtn.style.display = 'none';
            }
        }

        // ฟังก์ชันลบข่าวเดี่ยว
        function deleteNews(id, title) {
            if (confirm(`คุณแน่ใจหรือไม่ที่จะลบข่าว "${title}"?`)) {
                window.location.href = `/admin?action=news&sub_action=delete&id=${id}`;
            }
        }

        // ฟังก์ชันลบข่าวที่เลือก
        function deleteSelected() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);
            
            if (ids.length === 0) {
                alert('กรุณาเลือกข่าวที่ต้องการลบ');
                return;
            }
            
            if (confirm(`คุณแน่ใจหรือไม่ที่จะลบข่าว ${ids.length} รายการ?`)) {
                window.location.href = `/admin?action=news&sub_action=delete_multiple&ids=${ids.join(',')}`;
            }
        }

        function confirmDeleteNews(id, title) {
            if (confirm('คุณต้องการลบข่าว "' + title + '" หรือไม่?')) {
                deleteNews(id, title);
            }
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // เพิ่ม event listener สำหรับ checkbox
            document.querySelectorAll('.row-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', toggleDeleteButton);
            });
            
            document.getElementById('selectAllCheckbox').addEventListener('change', selectAll);
        });

        // ส่งข้อมูลข่าวทั้งหมดไป JS
        const newsData = {};
        <?php foreach ($newsList as $news): ?>
        newsData[<?= $news['id'] ?>] = {
            id: <?= $news['id'] ?>,
            title: <?= json_encode($news['title']) ?>,
            image: <?= json_encode($news['image'] ?? '') ?>,
            excerpt: <?= json_encode($news['excerpt'] ?? '') ?>,
            content: <?= json_encode($news['content'] ?? '') ?>,
            date: <?= json_encode(date('d/m/Y H:i', strtotime($news['created_at']))) ?>,
            author: <?= json_encode($news['author'] ?? 'ผู้ดูแลระบบ') ?>
        };
        <?php endforeach; ?>
    </script>
</body>
</html>
