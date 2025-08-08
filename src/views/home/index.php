<!-- Hero Slider Section -->
<?php if (!empty($slides)): ?>
<section class="hero-slider-section">
    <div id="heroSlider" class="carousel slide" data-bs-ride="carousel">
        <!-- Slides -->
        <div class="carousel-inner">
            <?php foreach ($slides as $index => $slide): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <?php if (!empty($slide['link_url'])): ?>
                        <a href="<?= htmlspecialchars($slide['link_url']) ?>" target="_blank" class="slide-link">
                            <img src="<?= htmlspecialchars($slide['image_path']) ?>" 
                                 alt="<?= htmlspecialchars($slide['title'] ?? 'Slide') ?>" 
                                 class="slide-image">
                        </a>
                    <?php else: ?>
                        <img src="<?= htmlspecialchars($slide['image_path']) ?>" 
                             alt="<?= htmlspecialchars($slide['title'] ?? 'Slide') ?>" 
                             class="slide-image">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>
<?php endif; ?>

<!-- Commander Section -->
<section class="pt-1 pb-3 commander-section" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="row justify-content-start">
                        <!-- Commander Profile -->
            <div class="col-lg-3 col-md-5 col-sm-6 mb-4">
                <div class="commander-profile-card">
                    <div class="commander-header">
                        <h3 class="commander-title">ผู้บังคับการ ศฝร.ภ.9</h3>
                    </div>
                    <div class="commander-content">
                        <?php if (!empty($commander)): ?>
                            <div class="commander-photo">
                                <?php if (!empty($commander['photo'])): ?>
                                    <img src="<?= htmlspecialchars($commander['photo']) ?>" 
                                         alt="<?= htmlspecialchars($commander['full_name']) ?>" 
                                         class="commander-image">
                                <?php else: ?>
                                    <div class="commander-placeholder">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="commander-info">
                                <div class="info-box">
                                    <span class="info-text">
                                        <?php if (!empty($commander['rank_name'])): ?>
                                            <?= htmlspecialchars($commander['rank_name']) ?> 
                                        <?php endif; ?>
                                        <?= htmlspecialchars($commander['full_name']) ?>
                                    </span>
                                </div>
                                <div class="info-box">
                                    <span class="info-text"><?= htmlspecialchars($commander['position_name']) ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="commander-photo">
                                <div class="commander-placeholder">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                            <div class="commander-info">
                                <div class="info-box">
                                    <span class="info-text">ยังไม่มีข้อมูล</span>
                                </div>
                                <div class="info-box">
                                    <span class="info-text">ผู้บังคับบัญชา ศฝร.ภ.8</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Mission Statement -->
            <div class="col-lg-9 col-md-7 col-sm-12">
                <div class="mission-content">
                    <div class="mission-section">
                        <h3 class="mission-title">ภารกิจของตำรวจ</h3>
                        <p class="mission-text">
                            ภารกิจของตำรวจ คือการบำบัดทุกข์ บำรุงสุข รักษาความสงบเรียบร้อยของประชาชน 
                            และรักษาระเบียบวินัยของสังคม พร้อมที่จะเสียสละเพื่อความสงบสุขของประชาชน
                        </p>
                    </div>
                    
                    <div class="mission-section">
                        <h3 class="mission-title">เจตนารมณ์ในการป้องกันการทุจริต</h3>
                        <p class="mission-text">
                            ศูนย์ฝึกอบรมตำรวจภูธรภาค 8 มีเจตจำนงสุจริตในการบริหารงาน 
                            และเจตนารมณ์จะไม่รับของขวัญและของกำนัลทุกชนิดจากผู้เกี่ยวข้อง 
                            เพื่อรักษาความโปร่งใสและความน่าเชื่อถือขององค์กร
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- News and Announcements Section -->
<section class="py-5" style="background: white;">
    <div class="container">
        <div class="row justify-content-center">
            <!-- Left Column: ข่าวสาร -->
            <div class="col-lg-5 col-md-6 mb-4">
                <div class="news-column">
                    <div class="news-header">
                        <h3 class="news-title">ข่าวสาร</h3>
                    </div>
                    <div class="news-content">
                        <?php 
                        // ดึงข่าวสารที่เกี่ยวข้องกับการบรรจุ/สรรหา/แต่งตั้ง
                        $appointmentNews = array_slice($latestNews, 0, 3);
                        ?>
                        <?php if (!empty($appointmentNews)): ?>
                            <?php foreach ($appointmentNews as $item): ?>
                                <div class="news-item" onclick="window.location.href='/?page=news&action=detail&id=<?= $item['id'] ?>'">
                                    <?php if (!empty($item['image'])): ?>
                                        <div class="news-image">
                                            <img src="<?= htmlspecialchars($item['image']) ?>" 
                                                 alt="<?= htmlspecialchars($item['title']) ?>" 
                                                 class="news-thumbnail"
                                                 onclick="event.stopPropagation(); openImageModal('<?= htmlspecialchars($item['image']) ?>', '<?= htmlspecialchars($item['title']) ?>')">
                                        </div>
                                    <?php endif; ?>
                                    <div class="news-info">
                                        <div class="news-date">
                                            <?= formatThaiDate($item['created_at']) ?>
                                        </div>
                                        <div class="news-description">
                                            <?= htmlspecialchars(mb_substr($item['title'], 0, 120)) ?><?= mb_strlen($item['title']) > 120 ? '...' : '' ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="news-item">
                                <div class="news-image">
                                    <img src="/assets/img/placeholder-image.jpg" alt="ข่าวสาร" class="news-thumbnail">
                                </div>
                                <div class="news-info">
                                    <div class="news-date">31 ก.ค. 68</div>
                                    <div class="news-description">ประกาศรายชื่อผู้ผ่านการประเมินบุคคลเพื่อย้ายข้าราชการครูและ บุคลากรทางการศึกษา ตำแหน่งบุคลากรทางการศึกษาอื่นตาม มาตรา 38...</div>
                                </div>
                            </div>
                            <div class="news-item">
                                <div class="news-image">
                                    <img src="/assets/img/placeholder-image.jpg" alt="ข่าวสาร" class="news-thumbnail">
                                </div>
                                <div class="news-info">
                                    <div class="news-date">29 ก.ค. 68</div>
                                    <div class="news-description">ประกาศรายชื่อผู้ผ่านการคัดเลือกภาค ก และมีสิทธิเข้ารับการ ประเมินภาค ข และภาค ค ในการคัดเลือกบุคคลเพื่อบรรจุและแต่ง ตั้งให...</div>
                                </div>
                            </div>
                            <div class="news-item">
                                <div class="news-image">
                                    <img src="/assets/img/placeholder-image.jpg" alt="ข่าวสาร" class="news-thumbnail">
                                </div>
                                <div class="news-info">
                                    <div class="news-date">25 ก.ค. 68</div>
                                    <div class="news-description">ประกาศรายชื่อผู้มีสิทธิเข้ารับการคัดเลือกบุคคลเพื่อย้าย ข้าราชการครูและบุคลากรทางการศึกษาตำแหน่งบุคลากรทางการ ศึกษาอื่นตาม...</div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="news-footer">
                        <a href="/?page=news" class="view-all-btn">
                            ดูทั้งหมด <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: ประกาศ -->
            <div class="col-lg-5 col-md-6 mb-4">
                <div class="news-column">
                    <div class="news-header">
                        <h3 class="news-title">ประกาศ</h3>
                    </div>
                    <div class="news-content">
                        <?php 
                        // ดึงประกาศที่เกี่ยวข้องกับการจัดซื้อจัดจ้าง
                        require_once SRC_PATH . '/models/AnnouncementModel.php';
                        $announcementModel = new AnnouncementModel();
                        $procurementAnnouncements = $announcementModel->getLatestAnnouncements(3);
                        ?>
                        <?php if (!empty($procurementAnnouncements)): ?>
                            <?php foreach ($procurementAnnouncements as $item): ?>
                                <div class="news-item" onclick="window.location.href='/?page=announcements&action=detail&id=<?= $item['id'] ?>'">
                                    <?php if (!empty($item['image'])): ?>
                                        <div class="news-image">
                                            <img src="<?= htmlspecialchars($item['image']) ?>" 
                                                 alt="<?= htmlspecialchars($item['title']) ?>" 
                                                 class="news-thumbnail"
                                                 onclick="event.stopPropagation(); openImageModal('<?= htmlspecialchars($item['image']) ?>', '<?= htmlspecialchars($item['title']) ?>')">
                                        </div>
                                    <?php endif; ?>
                                    <div class="news-info">
                                        <div class="news-date">
                                            <?= formatThaiDate($item['created_at']) ?>
                                        </div>
                                        <div class="news-description">
                                            <?= htmlspecialchars(mb_substr($item['title'], 0, 120)) ?><?= mb_strlen($item['title']) > 120 ? '...' : '' ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="news-item">
                                <div class="news-image">
                                    <img src="/assets/img/placeholder-image.jpg" alt="ประกาศ" class="news-thumbnail">
                                </div>
                                <div class="news-info">
                                    <div class="news-date">22 ก.ค. 68</div>
                                    <div class="news-description">ประกาศผู้ชนะการเสนอราคาจ้างเหมาบริการดำเนินการบริหาร จัดการเกี่ยวกับการออกข้อสอบและการทดสอบ ในการคัดเลือก บุคคลเพื่อบรรจุแ...</div>
                                </div>
                            </div>
                            <div class="news-item">
                                <div class="news-image">
                                    <img src="/assets/img/placeholder-image.jpg" alt="ประกาศ" class="news-thumbnail">
                                </div>
                                <div class="news-info">
                                    <div class="news-date">27 มิ.ย. 68</div>
                                    <div class="news-description">ประกาศผู้ชนะการเสนอราคาซื้อวัสดุด้านการปรับปรุง พัฒนา อาคารสถานที่และสิ่งแวดล้อม จํานวน 2 รายการ โดยวิธีเฉพาะ เจาะจง</div>
                                </div>
                            </div>
                            <div class="news-item">
                                <div class="news-image">
                                    <img src="/assets/img/placeholder-image.jpg" alt="ประกาศ" class="news-thumbnail">
                                </div>
                                <div class="news-info">
                                    <div class="news-date">24 มิ.ย. 68</div>
                                    <div class="news-description">ประกาศผู้ชนะการเสนอราคา ซื้อวัสดุคอมพิวเตอร์ จํานวน 13 รายการ โดยวิธีเฉพาะเจาะจง</div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="news-footer">
                        <a href="/?page=announcements" class="view-all-btn">
                            ดูทั้งหมด <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<style>
/* Commander Profile Styles */
.commander-profile-card {
    background: white;
    border-radius: 20px;
    padding: 0;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    border: none;
}

.commander-profile-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 60px rgba(0,0,0,0.12);
}

.commander-header {
    background: linear-gradient(135deg, #2c3e50, #34495e);
    color: white;
    padding: 1.2rem 2rem;
    text-align: center;
    position: relative;
    border-bottom: 3px solid #3498db;
}

.commander-title {
    color: white;
    font-size: 1rem;
    font-weight: 700;
    margin: 0;
    text-align: center;
    letter-spacing: 0.8px;
    text-transform: uppercase;
}

.commander-content {
    padding: 2rem;
    text-align: center;
}

.commander-photo {
    margin-bottom: 1.5rem;
    position: relative;
}

.commander-image {
    width: 160px;
    height: 200px;
    border-radius: 8px;
    object-fit: cover;
    border: 3px solid #e9ecef;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.commander-image:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.commander-placeholder {
    width: 160px;
    height: 200px;
    border-radius: 8px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    border: 3px solid #e9ecef;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.commander-placeholder:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.commander-placeholder i {
    font-size: 3.5rem;
    color: #95a5a6;
}

.commander-info {
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
    align-items: center;
}

.info-box {
    background: linear-gradient(135deg, #34495e, #2c3e50);
    color: white;
    padding: 0.7rem 1.4rem;
    border-radius: 6px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    min-width: 180px;
    text-align: center;
    border: 1px solid #2c3e50;
}

.info-box:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    background: linear-gradient(135deg, #2c3e50, #34495e);
}

.info-text {
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    color: white;
    text-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

/* Mission Content Styles */
.mission-content {
    padding: 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    height: 100%;
    border-left: 4px solid #3498db;
}

.mission-section {
    margin-bottom: 2rem;
}

.mission-section:last-child {
    margin-bottom: 0;
}

.mission-title {
    color: #2c3e50;
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

.mission-text {
    color: #555;
    font-size: 1.1rem;
    line-height: 1.7;
    text-align: justify;
    margin-bottom: 0;
}



/* Responsive Design */
@media (max-width: 991px) {
    .commander-profile-card {
        margin-bottom: 2rem;
    }
    
    .commander-image,
    .commander-placeholder {
        width: 140px;
        height: 175px;
    }
    
    .commander-placeholder i {
        font-size: 3rem;
    }
    
    .info-box {
        min-width: 150px;
        padding: 0.6rem 1rem;
    }
    
    .info-text {
        font-size: 0.95rem;
    }
    
    .mission-content {
        padding: 1.5rem;
    }
    
    .mission-title {
        font-size: 1.2rem;
    }
    
    .mission-text {
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .commander-header {
        padding: 1rem 1.5rem;
    }
    
    .commander-content {
        padding: 1.5rem;
    }
    
    .commander-image,
    .commander-placeholder {
        width: 120px;
        height: 150px;
    }
    
    .commander-placeholder i {
        font-size: 2.5rem;
    }
    
    .info-box {
        min-width: 120px;
        padding: 0.5rem 0.8rem;
    }
    
    .info-text {
        font-size: 0.9rem;
    }
    
    .mission-content {
        padding: 1rem;
    }
    
    .mission-title {
        font-size: 1.1rem;
    }
    
    .mission-text {
        font-size: 0.95rem;
    }
}

<?php if (!empty($popup) && ($popup['message'] || $popup['image'])): ?>
#popup {
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100vw; height: 100vh;
  background: rgba(0,0,0,0.7);
  z-index: 9999;
  justify-content: center;
  align-items: center;
}
#popup .popup-content {
  background: #fff;
  border-radius: 10px;
  text-align: center;
  position: relative;
  box-shadow: 0 0 30px rgba(0,0,0,0.3);
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 0;
  overflow: hidden;
  /* ไม่กำหนด width/height ตายตัว */
}
#popup .popup-image {
  display: block;
  max-width: 90vw;
  max-height: 90vh;
  width: auto;
  height: auto;
  object-fit: contain;
  border-radius: 10px 10px 0 0;
  background: #fff;
  margin: 0 auto;
}
#popup .popup-message {
  font-size: 1.2em;
  margin: 10px 0 20px 0;
  padding: 0 16px;
  word-break: break-word;
}
#closePopup {
  position: absolute;
  top: 10px;
  right: 20px;
  cursor: pointer;
  font-size: 2rem;
  color: #fff;
  z-index: 10001;
  text-shadow: 0 0 8px #000;
}
@media (max-width: 600px) {
  #popup .popup-content {
    border-radius: 0;
  }
  #popup .popup-image {
    max-width: 98vw;
    max-height: 60vh;
    border-radius: 0;
  }
  #closePopup {
    top: 8px;
    right: 12px;
    font-size: 1.5rem;
  }
}
</style>
<div id="popup">
  <div class="popup-content" id="popupContent">
    <span id="closePopup">&times;</span>
    <?php if (!empty($popup['image'])): ?>
      <?php
        $imgPath = $popup['image'];
        if (strpos($imgPath, '/uploads/') !== 0) {
            $imgPath = '/uploads/popup/' . ltrim($imgPath, '/');
        }
      ?>
      <img src="<?= htmlspecialchars($imgPath) ?>" alt="popup image" class="popup-image" id="popupImg"><br>
    <?php endif; ?>
    <?php if (!empty($popup['message'])): ?>
      <div class="popup-message"> <?= nl2br(htmlspecialchars($popup['message'])) ?> </div>
    <?php endif; ?>
  </div>
</div>
<script>
window.onload = function() {
  var popup = document.getElementById('popup');
  if (popup) popup.style.display = 'flex';
  var closeBtn = document.getElementById('closePopup');
  if (closeBtn) closeBtn.onclick = function() {
    popup.style.display = 'none';
  }
  // ปรับขนาดกรอบ popup-content ตามอัตราส่วนรูป
  var img = document.getElementById('popupImg');
  var content = document.getElementById('popupContent');
  if (img && content) {
    img.onload = function() {
      var w = img.naturalWidth;
      var h = img.naturalHeight;
      var maxW = window.innerWidth * 0.9;
      var maxH = window.innerHeight * 0.9;
      var ratio = Math.min(maxW / w, maxH / h, 1);
      content.style.width = (w * ratio) + 'px';
      content.style.height = (h * ratio) + 'px';
    }
  }
}
</script>
<?php endif; ?>

<style>
.latest-news-card {
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    border: 1px solid #eee;
    transition: box-shadow 0.2s, transform 0.2s;
    height: 100%;
    background: #fff;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.latest-news-card:hover {
    box-shadow: 0 6px 24px rgba(0,0,0,0.13);
    transform: translateY(-4px) scale(1.02);
}
.latest-news-card-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-bottom: 1px solid #eee;
}
.latest-news-card-body {
    padding: 1.1rem 1.2rem 0.7rem 1.2rem;
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
}
.latest-news-card-title {
    font-size: 1.08rem;
    font-weight: bold;
    color: #0d47a1;
    margin-bottom: 0.5rem;
    min-height: 2.5em;
}
.latest-news-card-date {
    color: #666;
    font-size: 0.97em;
    margin-bottom: 0.5em;
}
.latest-news-card-content {
    color: #333;
    font-size: 0.98em;
    margin-bottom: 0.7em;
    min-height: 2.2em;
}
.latest-news-card-footer {
    margin-top: auto;
    padding-top: 0.5em;
}

/* เพิ่ม CSS สำหรับการ์ดข่าวที่คลิกได้ */
.news-card-clickable:hover {
    box-shadow: 0 6px 24px rgba(179, 0, 0, 0.15) !important;
    transform: translateY(-3px) !important;
}

/* Mobile responsive adjustments for homepage */
@media (max-width: 575.98px) {
    .container {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .py-5 {
        padding-top: 2rem !important;
        padding-bottom: 2rem !important;
    }
    
    .news-card-clickable .card-body {
        padding: 0.8rem;
    }
    
    .news-card-clickable .card-img-top {
        height: 160px !important;
    }
    
    .col-12.col-md-6 {
        margin-bottom: 1rem;
    }
    
    /* ปรับขนาดหัวข้อ */
    .news-card-clickable .fw-bold {
        min-height: auto !important;
        font-size: 0.95rem !important;
    }
    
    /* ปรับข้อความให้สั้นลงบนมือถือ */
    .news-card-clickable .mb-2 {
        min-height: auto !important;
        margin-bottom: 0.5rem !important;
    }
}

@media (max-width: 767.98px) {
    .float-end {
        float: none !important;
        display: block;
        text-align: center;
        margin-top: 0.5rem !important;
    }
}

/* Hero Slider Styling */
.hero-slider-section {
    position: relative;
    overflow: hidden;
}

.carousel {
    border-radius: 0;
}

.carousel-item {
    height: 35vh;
    min-height: 250px;
}

.slide-background {
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    position: relative;
}

.slide-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.3) 100%);
    display: flex;
    align-items: center;
}

.slide-content {
    color: white;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.slide-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.slide-subtitle {
    font-size: 1.3rem;
    font-weight: 500;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.slide-description {
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    opacity: 0.8;
}

.slide-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.slide-btn {
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
    text-decoration: none;
}

.slide-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.carousel-indicators {
    bottom: 30px;
}

.carousel-indicators button {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin: 0 5px;
    background-color: rgba(255,255,255,0.5);
    border: 2px solid rgba(255,255,255,0.8);
    transition: all 0.3s ease;
}

.carousel-indicators button.active {
    background-color: white;
    transform: scale(1.2);
}

.carousel-control-prev,
.carousel-control-next {
    width: 60px;
    height: 60px;
    background: rgba(0,0,0,0.3);
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
    margin: 0 20px;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    width: 30px;
    height: 30px;
}

/* Responsive adjustments for slider */
@media (max-width: 768px) {
    .carousel-item {
        height: 30vh;
        min-height: 200px;
    }
    
    .slide-title {
        font-size: 1.6rem;
    }
    
    .slide-subtitle {
        font-size: 1rem;
    }
    
    .slide-description {
        font-size: 0.85rem;
    }
    
    .slide-actions {
        flex-direction: column;
    }
    
    .slide-btn {
        width: 100%;
        text-align: center;
    }
}

@media (max-width: 576px) {
    .carousel-item {
        height: 25vh;
        min-height: 180px;
    }
    
    .slide-title {
        font-size: 1.2rem;
    }
    
    .slide-subtitle {
        font-size: 0.9rem;
    }
    
    .slide-description {
        font-size: 0.8rem;
    }
    
    .carousel-control-prev,
    .carousel-control-next {
        width: 40px;
        height: 40px;
        margin: 0 10px;
    }
    
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        width: 20px;
        height: 20px;
    }
}



@media (max-width: 991px) {
    .latest-news-card-img { height: 150px; }
}
@media (max-width: 767px) {
    .latest-news-card-img { height: 120px; }
}

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

/* News and Announcements Section Styles */
.news-column {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid #e0e0e0;
}

.news-column:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.news-header {
    background: linear-gradient(135deg, #2c3e50, #34495e);
    padding: 1.2rem 1.5rem;
    text-align: center;
    position: relative;
    border-bottom: 3px solid #e74c3c;
}

.news-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: #e74c3c;
    border-radius: 0;
}

.news-title {
    color: white;
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0;
    text-align: center;
    letter-spacing: 0.3px;
    font-family: 'Sarabun', sans-serif;
}

.news-content {
    padding: 1.2rem 1.5rem;
    flex: 1;
    background: #fafafa;
}

.news-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1rem;
    padding: 0.8rem;
    border-radius: 4px;
    background: white;
    transition: all 0.2s ease;
    cursor: pointer;
    border-left: 3px solid #3498db;
    border-bottom: 1px solid #e0e0e0;
    gap: 0.8rem;
}

.news-item:hover {
    background: #f8f9fa;
    border-left-color: #e74c3c;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.news-item:last-child {
    margin-bottom: 0;
}

.news-image {
    flex-shrink: 0;
    width: 80px;
    height: 60px;
    border-radius: 4px;
    overflow: hidden;
    border: 1px solid #e0e0e0;
}

.news-thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.2s ease;
}

.news-thumbnail:hover {
    transform: scale(1.05);
}

.news-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.news-date {
    background: #2c3e50;
    color: white;
    padding: 0.3rem 0.5rem;
    border-radius: 3px;
    font-size: 0.75rem;
    font-weight: 600;
    min-width: 60px;
    text-align: center;
    align-self: flex-start;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    font-family: 'Sarabun', sans-serif;
}

.news-description {
    color: #2c3e50;
    font-size: 0.85rem;
    line-height: 1.4;
    font-weight: 400;
    font-family: 'Sarabun', sans-serif;
}

.news-footer {
    padding: 1.2rem 1.5rem;
    background: #f8f9fa;
    border-top: 1px solid #e0e0e0;
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 80px;
}

.view-all-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    background: white;
    color: #2c3e50;
    padding: 0.8rem 1.5rem;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    border: 2px solid #2c3e50;
    font-family: 'Sarabun', sans-serif;
    min-width: 140px;
    position: relative;
    overflow: hidden;
}

.view-all-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.view-all-btn:hover::before {
    left: 100%;
}

.view-all-btn:hover {
    background: #2c3e50;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(44, 62, 80, 0.3);
    text-decoration: none;
    border-color: #2c3e50;
}

.view-all-btn i {
    transition: transform 0.3s ease;
    font-size: 0.9rem;
    font-weight: bold;
}

.view-all-btn:hover i {
    transform: translateX(4px);
}

/* Responsive adjustments for news section */
@media (max-width: 991px) {
    .news-column {
        margin-bottom: 1.5rem;
    }
    
    .news-header {
        padding: 1rem 1.2rem;
    }
    
    .news-title {
        font-size: 1.1rem;
    }
    
    .news-content {
        padding: 1rem 1.2rem;
    }
    
    .news-item {
        padding: 0.7rem;
        margin-bottom: 0.8rem;
        gap: 0.6rem;
    }
    
    .news-image {
        width: 70px;
        height: 50px;
    }
    
    .news-date {
        padding: 0.3rem 0.5rem;
        font-size: 0.7rem;
        min-width: 55px;
    }
    
    .news-description {
        font-size: 0.8rem;
    }
    
    .news-footer {
        padding: 1rem 1.2rem;
        min-height: 70px;
    }
    
    .view-all-btn {
        padding: 0.7rem 1.3rem;
        font-size: 0.9rem;
        min-width: 130px;
    }
}

@media (max-width: 576px) {
    .news-header {
        padding: 0.8rem 1rem;
    }
    
    .news-title {
        font-size: 1rem;
    }
    
    .news-content {
        padding: 0.8rem 1rem;
    }
    
    .news-item {
        flex-direction: column;
        align-items: flex-start;
        padding: 0.6rem;
        margin-bottom: 0.6rem;
        gap: 0.5rem;
    }
    
    .news-image {
        width: 100%;
        height: 120px;
        align-self: center;
    }
    
    .news-info {
        width: 100%;
    }
    
    .news-date {
        margin-bottom: 0.4rem;
        font-size: 0.65rem;
        padding: 0.25rem 0.4rem;
        min-width: 50px;
    }
    
    .news-description {
        font-size: 0.75rem;
        line-height: 1.3;
    }
    
    .news-footer {
        padding: 1rem;
        min-height: 80px;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .view-all-btn {
        width: 100%;
        max-width: 200px;
        justify-content: center;
        padding: 0.8rem 1rem;
        font-size: 0.9rem;
        min-width: auto;
    }
    
    .view-all-btn:hover {
        transform: translateY(-1px);
    }
}
</style>

<!-- Image Modal -->
<div id="imageModal" class="image-modal" style="display: none;">
    <div class="image-modal-content">
        <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
        <img id="modalImage" src="" alt="" class="modal-image">
        <div class="modal-caption" id="modalCaption"></div>
    </div>
</div>

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

// ฟังก์ชันแปลงวันที่เป็นภาษาไทย
function formatThaiDate($date) {
    $thaiMonths = [
        'Jan' => 'ม.ค.',
        'Feb' => 'ก.พ.',
        'Mar' => 'มี.ค.',
        'Apr' => 'เม.ย.',
        'May' => 'พ.ค.',
        'Jun' => 'มิ.ย.',
        'Jul' => 'ก.ค.',
        'Aug' => 'ส.ค.',
        'Sep' => 'ก.ย.',
        'Oct' => 'ต.ค.',
        'Nov' => 'พ.ย.',
        'Dec' => 'ธ.ค.'
    ];
    
    $dateObj = new DateTime($date);
    $day = $dateObj->format('j');
    $month = $dateObj->format('M');
    $year = $dateObj->format('y');
    
    // แปลงปี ค.ศ. เป็น พ.ศ.
    $buddhistYear = intval($year) + 43;
    
    return $day . ' ' . $thaiMonths[$month] . ' ' . $buddhistYear;
}
?>
