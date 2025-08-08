<?php
// ไม่ต้อง filter กลุ่มใน view แล้ว ใช้ตัวแปรที่ controller ส่งมา

function render_commander_tree($commander, $level = 1) {
    $levelClass = 'org-node-level' . min($level, 3);
    $childLevel = min($level + 1, 3);
    $branchClass = 'org-branch-level' . $childLevel;
    ?>
    <div class="tree-container">
        <div class="org-node <?= $levelClass ?>" <?= commander_data_attrs($commander) ?>>
            <div class="commander-photo">
                <?php if (!empty($commander['photo'])): ?>
                    <img src="<?= htmlspecialchars($commander['photo']) ?>" alt="<?= htmlspecialchars($commander['full_name']) ?>" data-photo="<?= htmlspecialchars($commander['photo']) ?>">
                <?php else: ?>
                    <div class="photo-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="commander-info">
                <h<?= $level + 3 ?>><?= htmlspecialchars($commander['rank_name'] . ' ' . $commander['full_name']) ?></h<?= $level + 3 ?>>
                <p><?= htmlspecialchars($commander['position_name']) ?></p>
                <?php if (!empty($commander['work_phone'])): ?>
                    <div class="contact-info">
                        <i class="fas fa-phone"></i> <?= htmlspecialchars($commander['work_phone']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (!empty($commander['children'])): ?>
            <!-- Connector Line - เส้นแนวตั้งลงไป -->
            <div class="org-connector-vertical"></div>
            
            <!-- Children Container -->
            <div class="org-branch <?= $branchClass ?> has-multiple-children" data-children-count="<?= count($commander['children']) ?>">
                <?php foreach ($commander['children'] as $child): ?>
                    <?php render_commander_tree($child, $level + 1); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
?>
<hr>
<div class="container-fluid my-3">
    <!-- Header Section -->
    <div class="row mb-3">
        <div class="col-12 text-center">
            <div class="official-header p-3">
                <img src="/assets/img/stic.webp" alt="ตราสัญลักษณ์ตำรวจ" class="mb-2" style="height: 80px;">
                <h1 class="official-title mb-2">ผู้บังคับบัญชา</h1>
                <h2 class="official-subtitle">ศูนย์ฝึกอบรมตำรวจภูธรภาค 8</h2>
                <div class="official-line"></div>
            </div>
        </div>
    </div>
<hr>
    <div class="org-chart">
        <!-- 1. ผู้บังคับบัญชา ศฝร.ภ.8 -->
        <?php if (!empty($commander_level1)): ?>
            <div class="org-section">
                <div class="org-section-title">ผู้บังคับบัญชา ศฝร.ภ.8</div>
                <div class="org-branch org-branch-level1">
                    <?php foreach ($commander_level1 as $commander): ?>
                        <?php render_commander_tree($commander, 1); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
<hr>
        <!-- 2. ผู้บังคับบัญชา ฝ่ายอำนวยการ -->
        <?php if (!empty($commander_admin)): ?>
            <div class="org-section">
                <div class="org-section-title">ผู้บังคับบัญชา ฝ่ายอำนวยการ ศูนย์ฝึกอบรมตำรวจภูธรภาค 8</div>
                <div class="org-branch org-branch-level2">
                    <?php foreach ($commander_admin as $commander): ?>
                        <?php render_commander_tree($commander, 1); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- 3. ผู้บังคับบัญชา ฝ่ายบริการการศึกษา -->
        <?php if (!empty($commander_edu)): ?>
            <div class="org-section">
                <div class="org-section-title">ผู้บังคับบัญชา ฝ่ายบริการการศึกษา ศูนย์ฝึกอบรมตำรวจภูธรภาค 8</div>
                <div class="org-branch org-branch-level2">
                    <?php foreach ($commander_edu as $commander): ?>
                        <?php render_commander_tree($commander, 1); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- 4. ผู้บังคับบัญชา ฝ่ายปกครองและการฝึก -->
        <?php if (!empty($commander_training)): ?>
            <div class="org-section">
                <div class="org-section-title">ผู้บังคับบัญชา ฝ่ายปกครองและการฝึก ศูนย์ฝึกอบรมตำรวจภูธรภาค 8</div>
                <div class="org-branch org-branch-level2">
                    <?php foreach ($commander_training as $commander): ?>
                        <?php render_commander_tree($commander, 1); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- 5. กลุ่มงานอาจารย์ -->
        <?php if (!empty($commander_teacher)): ?>
            <div class="org-section">
                <div class="org-section-title">กลุ่มงานอาจารย์ ศูนย์ฝึกอบรมตำรวจภูธรภาค 8</div>
                <div class="org-branch org-branch-level2">
                    <?php foreach ($commander_teacher as $commander): ?>
                        <?php render_commander_tree($commander, 1); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal for Commander Details -->
<div id="commanderModal" class="commander-modal" style="display:none;">
  <div class="commander-modal-content">
    <span class="commander-modal-close" id="commanderModalClose">&times;</span>
    
    <!-- Header Section -->
    <div class="commander-modal-header">
      <img id="modalPhoto" src="" alt="" class="commander-modal-photo">
      <h4 id="modalName"></h4>
      <p id="modalPosition"></p>
    </div>
    
    <!-- Body Section -->
    <div class="commander-modal-body">
      <!-- Contact Information -->
      <div class="modal-contact-section" id="contactSection" style="display:none;">
        <div id="modalPhone" class="contact-item"></div>
        <div id="modalEmail" class="contact-item"></div>
      </div>
      
      <!-- Qualifications -->
      <div class="modal-content-section" id="qualificationsSection" style="display:none;">
        <div class="modal-section-title">
          <i class="fas fa-graduation-cap"></i>
          คุณวุฒิ
        </div>
        <div id="modalQualifications" class="modal-section-content"></div>
      </div>
      
      <!-- Previous Positions -->
      <div class="modal-content-section" id="previousPositionsSection" style="display:none;">
        <div class="modal-section-title">
          <i class="fas fa-briefcase"></i>
          เคยดำรงตำแหน่ง
        </div>
        <div id="modalPreviousPositions" class="modal-section-content"></div>
      </div>
    </div>
  </div>
</div>

<style>
/* --- Organizational Chart --- */
.org-section {
    width: 100%;
    margin-bottom: 15px;
    padding: 15px 15px;
    border-radius: 12px;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1), 0 4px 10px rgba(0,0,0,0.05);
    position: relative;
    border: 3px solid #1e40af;
    border-top: 6px solid #1e40af;
}

/* เพิ่ม hover effect สำหรับ org-node ที่คลิกได้ */
.org-node {
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
}

.org-node:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
    background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
    border-color: #3b82f6;
}

.org-node:active {
    transform: translateY(-1px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}

/* เพิ่ม indicator ว่าสามารถคลิกได้ */
.org-node::after {
    content: "🔍";
    position: absolute;
    top: 8px;
    right: 8px;
    font-size: 0.8rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.org-node:hover::after {
    opacity: 0.6;
}

/* รองรับ touch devices */
@media (hover: none) and (pointer: coarse) {
    .org-node:hover {
        transform: none;
    }
    
    .org-node:active {
        transform: scale(0.98);
        background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
    }
    
    .org-node::after {
        opacity: 0.4;
        content: "👆";
    }
}
.org-section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e40af;
    margin-bottom: 12px;
    letter-spacing: 0.3px;
    text-align: center;
    text-shadow: 0 1px 3px rgba(30,64,175,0.1);
    font-family: 'Sarabun', 'THSarabunNew', 'Leelawadee UI', sans-serif;
    position: relative;
}

.org-section-title:after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: #1e40af;
    border-radius: 2px;
}
.org-branch {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    width: 100%;
    margin: 8px 0;
    position: relative;
    z-index: 2;
}

/* สำหรับ level แรก - แสดงเป็น horizontal */
.org-branch-level1 {
    display: flex;
    flex-direction: row;
    justify-content: center;
    flex-wrap: wrap;
    gap: 12px;
}

/* สำหรับ level ย่อย - แสดงเป็น vertical tree */
.org-branch-level2,
.org-branch-level3 {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: flex-start;
    gap: 8px;
    width: 100%;
    position: relative;
}

/* Tree positioning */
.org-section .tree-container {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* Contact info styling */
.contact-info {
    color: #64748b;
    font-size: 0.85rem;
    font-weight: 500;
    margin-top: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.contact-info i {
    color: #2563eb;
    font-size: 0.8rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .org-branch-level1,
    .org-branch-level2,
    .org-branch-level3 {
        flex-direction: column;
        align-items: center;
        gap: 5px;
    }
    
    .tree-container {
        margin: 3px 0;
    }
    
    .org-connector-vertical {
        height: 15px;
        margin: 3px auto;
    }
    
    .org-section {
        margin-bottom: 10px;
        padding: 10px 10px;
    }
    
    .org-section-title {
        margin-bottom: 8px;
    }
    
    .container-fluid {
        margin-top: 10px !important;
        margin-bottom: 10px !important;
    }
    
    .row {
        margin-bottom: 15px !important;
    }
    
    .official-header {
        padding: 15px !important;
    }
    
    .official-header img {
        margin-bottom: 10px !important;
    }
    
    .org-node {
        width: 260px !important;
        height: 180px !important;
        padding: 16px 12px !important;
    }
    
    .org-node-level1 {
        width: 300px !important;
        height: 220px !important;
        padding: 20px 16px !important;
    }
    
    .org-node-level2 {
        width: 260px !important;
        height: 180px !important;
    }
    
    .org-node-level3 {
        width: 260px !important;
        height: 180px !important;
    }
    
    .org-section-title {
        font-size: 1.3rem;
    }
    
    .commander-photo img,
    .photo-placeholder {
        width: 90px !important;
        height: 90px !important;
    }
    
    .org-node-level1 .commander-photo img,
    .org-node-level1 .photo-placeholder {
        width: 110px !important;
        height: 110px !important;
    }
}
/* ลบ CSS นี้ออกเพราะใช้ has-children แทน */
.org-node {
    background: #ffffff;
    border-radius: 8px;
    border: 2px solid #2563eb;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15), 0 2px 6px rgba(0,0,0,0.08);
    padding: 20px 16px;
    width: 280px;
    height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    position: relative;
    transition: all 0.3s ease;
    font-family: 'Sarabun', 'THSarabunNew', 'Leelawadee UI', sans-serif;
}
.org-node:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.2), 0 4px 10px rgba(0,0,0,0.12);
    transform: translateY(-2px);
    z-index: 10;
    border-color: #1d4ed8;
}
.org-node-level1 {
    border: 3px solid #1e40af;
    width: 340px;
    height: 240px;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    z-index: 3;
    box-shadow: 0 8px 25px rgba(30,64,175,0.15), 0 4px 12px rgba(30,64,175,0.08);
}
.org-node-level2 {
    border: 2px solid #3b82f6;
    width: 280px;
    height: 200px;
    background: #f8fafc;
}
.org-node-level3 {
    border: 2px solid #60a5fa;
    width: 280px;
    height: 200px;
    background: #f1f5f9;
}
/* Tree Container */
.tree-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 5px 0;
}

/* Connector แสดงเฉพาะเมื่อมี children (สไตล์ราชการ) */
.org-connector-vertical {
    width: 3px;
    height: 20px;
    background: #1e40af;
    margin: 5px auto;
    position: relative;
    z-index: 1;
    display: block;
    box-shadow: 0 2px 4px rgba(30,64,175,0.3);
}

/* เส้นแนวนอนสำหรับเชื่อมต่อ multiple children */
.has-multiple-children:before {
    content: '';
    position: absolute;
    top: -10px;
    left: 15%;
    right: 15%;
    height: 3px;
    background: #1e40af;
    z-index: 0;
    box-shadow: 0 1px 3px rgba(30,64,175,0.3);
}

/* ซ่อนเส้นถ้ามี child เดียว */
.has-multiple-children[data-children-count="1"]:before {
    display: none;
}

/* Individual Child Connectors - เฉพาะเมื่อมี parent ที่มี children */
.org-branch-level2 .tree-container:before,
.org-branch-level3 .tree-container:before {
    content: '';
    position: absolute;
    top: -20px;
    left: 50%;
    width: 3px;
    height: 20px;
    background: linear-gradient(180deg, #3b82f6 0%, #1e3a8a 100%);
    transform: translateX(-50%);
    z-index: 1;
    display: none; /* ซ่อนไว้ก่อน */
}

/* เส้นแนวตั้งเล็กๆ เชื่อมจากเส้นแนวนอนลงไปยัง children */
.has-multiple-children .tree-container:before {
    content: '';
    position: absolute;
    top: -25px;
    left: 50%;
    width: 3px;
    height: 25px;
    background: #1e40af;
    transform: translateX(-50%);
    z-index: 1;
    box-shadow: 0 1px 3px rgba(30,64,175,0.3);
}

/* ซ่อนเส้นสำหรับ child เดียว */
.has-multiple-children[data-children-count="1"] .tree-container:before {
    display: none;
}
.commander-photo img,
.photo-placeholder {
    width: 100px;
    height: 100px;
    border-radius: 8px;
    object-fit: cover;
    border: 3px solid #2563eb;
    margin-bottom: 12px;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 8px rgba(37,99,235,0.15);
    transition: all 0.3s ease;
}
.org-node:hover .commander-photo img,
.org-node:hover .photo-placeholder {
    border-color: #1d4ed8;
    box-shadow: 0 6px 12px rgba(37,99,235,0.25);
}
.org-node-level1 .commander-photo img,
.org-node-level1 .photo-placeholder {
    width: 130px;
    height: 130px;
    border-radius: 10px;
    border-width: 4px;
    margin-bottom: 16px;
}

.photo-placeholder i {
    color: #64748b;
    font-size: 2rem;
}
/* Typography - สไตล์ราชการ */
.org-node h4, .org-node h5, .org-node h6 {
    color: #1e40af;
    font-weight: 600;
    margin-bottom: 8px;
    text-align: center;
    line-height: 1.4;
    font-family: 'Sarabun', 'THSarabunNew', 'Leelawadee UI', sans-serif;
}

.org-node-level1 h4 {
    color: #1e40af;
    font-weight: 700;
    font-size: 1.4rem;
    margin-bottom: 12px;
    text-shadow: 0 1px 2px rgba(30,64,175,0.1);
}

.org-node p {
    color: #475569;
    font-size: 0.95rem;
    font-weight: 500;
    text-align: center;
    margin-bottom: 6px;
    line-height: 1.3;
    font-family: 'Sarabun', 'THSarabunNew', 'Leelawadee UI', sans-serif;
}

.org-node-level1 p {
    color: #334155;
    font-size: 1.05rem;
    font-weight: 600;
}
.org-node-level2 h5 {
    color: #374151;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 8px;
}
.org-node-level3 h6 {
    color: #374151;
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 6px;
}
.photo-placeholder {
    color: #9ca3af;
    font-size: 2rem;
}
.contact-info {
    color: #6b7280;
    font-size: 0.85rem;
    margin-top: 8px;
}
.contact-info i {
    margin-right: 5px;
    color: #3b82f6;
}
/* Responsive */
@media (max-width: 1200px) {
    .org-branch { gap: 24px; }
    .org-node { min-width: 180px; max-width: 220px; }
    .org-node-level1 { min-width: 220px; max-width: 260px; }
}
@media (max-width: 900px) {
    .org-branch { flex-wrap: wrap; gap: 16px; }
    .org-node { min-width: 150px; max-width: 180px; padding: 14px 8px 10px 8px; }
    .org-node-level1 { min-width: 180px; max-width: 200px; }
}
@media (max-width: 600px) {
    .org-branch { 
        flex-direction: column; 
        align-items: center; 
        gap: 15px; 
    }
    .org-node, .org-node-level1 { 
        width: 90vw !important; 
        max-width: 320px !important;
        height: auto !important;
        min-height: 160px !important;
    }
    .org-connector-vertical { 
        height: 30px; 
    }
}
/* ปรับปรุง Modal Styles ให้ดีขึ้น */
.commander-modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0; top: 0; width: 100vw; height: 100vh;
  background: rgba(0,0,0,0.4);
  justify-content: center;
  align-items: center;
  animation: fadeIn 0.25s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; } 
  to { opacity: 1; }
}

.commander-modal-content {
  background: #fff;
  border-radius: 20px;
  max-width: 520px;
  width: 90%;
  max-height: 85vh;
  margin: 0 auto;
  position: relative;
  font-family: 'Sarabun', 'Prompt', sans-serif;
  animation: modalSlide 0.3s ease-out;
  box-shadow: 0 20px 60px rgba(0,0,0,0.15);
  overflow: hidden;
}

@keyframes modalSlide {
  from { 
    transform: scale(0.9) translateY(-30px); 
    opacity: 0; 
  } 
  to { 
    transform: scale(1) translateY(0); 
    opacity: 1; 
  }
}

.commander-modal-close {
  position: absolute;
  top: 16px; right: 20px;
  font-size: 1.8rem;
  color: #9ca3af;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.2s ease;
  z-index: 10;
  background: rgba(255,255,255,0.9);
  border-radius: 50%;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.commander-modal-close:hover { 
  color: #dc2626; 
  background: rgba(255,255,255,1);
  transform: scale(1.1);
}

.commander-modal-header {
  background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
  padding: 24px 28px 24px 28px;
  text-align: center;
  color: white;
  position: relative;
}

.commander-modal-photo {
  width: 110px; 
  height: 110px;
  border-radius: 18px;
  object-fit: cover;
  border: 4px solid rgba(255,255,255,0.9);
  margin-bottom: 16px;
  background: #f3f4f6;
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

#modalName {
  font-size: 1.4rem;
  font-weight: 700;
  margin-bottom: 6px;
  text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

#modalPosition {
  font-size: 1.1rem;
  opacity: 0.95;
  font-weight: 500;
  margin-bottom: 0;
}

.commander-modal-body {
  padding: 0;
  max-height: calc(85vh - 180px);
  overflow-y: auto;
  overflow-x: hidden;
}

.modal-contact-section {
  padding: 20px 28px;
  background: #f8fafc;
  border-bottom: 1px solid #e5e7eb;
}

.contact-item {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 8px;
  font-size: 0.95rem;
  color: #374151;
}

.contact-item:last-child {
  margin-bottom: 0;
}

.contact-item i {
  color: #3b82f6;
  font-size: 1rem;
  width: 18px;
  text-align: center;
}

.modal-content-section {
  padding: 24px 28px 32px 28px;
}

.modal-content-section:last-child {
  padding-bottom: 24px;
}

.modal-section-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: #1e3a8a;
  margin-bottom: 12px;
  padding-bottom: 8px;
  border-bottom: 2px solid #e5e7eb;
  display: flex;
  align-items: center;
  gap: 8px;
}

.modal-section-title i {
  color: #3b82f6;
  font-size: 1rem;
}

.modal-section-content {
  font-size: 0.94rem;
  color: #374151;
  line-height: 1.6;
  text-align: left;
}

.modal-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.modal-list li {
  padding: 6px 0;
  padding-left: 16px;
  position: relative;
  border-bottom: 1px solid #f1f5f9;
}

.modal-list li:last-child {
  border-bottom: none;
}

.modal-list li:before {
  content: "•";
  color: #3b82f6;
  font-weight: bold;
  position: absolute;
  left: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
  .commander-modal-content {
    max-width: 95vw;
    max-height: 90vh;
    margin: 5vh auto;
  }
  
  .commander-modal-header {
    padding: 20px 20px;
  }
  
  .modal-contact-section,
  .modal-content-section {
    padding: 16px 20px;
  }
  
  .commander-modal-photo {
    width: 90px;
    height: 90px;
  }
  
  #modalName {
    font-size: 1.2rem;
  }
  
  #modalPosition {
    font-size: 1rem;
  }
  
  .modal-section-title {
    font-size: 1rem;
  }
  
  .modal-section-content {
    font-size: 0.9rem;
}
}

@media (max-width: 480px) {
  .commander-modal-content {
    max-width: 98vw;
  }
  
  .commander-modal-header {
    padding: 16px 16px;
  }
  
  .modal-contact-section,
  .modal-content-section {
    padding: 14px 16px;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // ฟังก์ชันสำหรับจัดรูปแบบข้อมูลที่เป็นรายการ
  function formatListData(data) {
    if (!data) return '';
    
    // แยกบรรทัดและกรองข้อมูลที่ไม่เป็นค่าว่าง
    const lines = data.split(/\n|<br\s*\/?>/i).filter(line => line.trim());
    
    if (lines.length === 0) return '';
    
    let html = '<ul class="modal-list">';
    lines.forEach(line => {
      const cleanLine = line.trim().replace(/^\d+\.\s*/, ''); // ลบตัวเลขข้างหน้า
      if (cleanLine) {
        html += '<li>' + cleanLine + '</li>';
      }
    });
    html += '</ul>';
    
    return html;
  }
  
  // ฟังก์ชันแสดง/ซ่อน section
  function toggleSection(sectionId, show) {
    const section = document.getElementById(sectionId);
    if (section) {
      section.style.display = show ? 'block' : 'none';
    }
  }
  
  // Event listener สำหรับคลิกที่กรอบผู้บังคับบัญชา
  document.querySelectorAll('.org-node, .commander-card, .department-card, .staff-card').forEach(function(node) {
    node.addEventListener('click', function(e) {
      e.preventDefault();
      var parent = node;
      
             // เก็บข้อมูลจาก attributes
       var photoEl = parent.querySelector('.commander-photo img, .commander-photo .photo-placeholder');
       var photo = '';
       if (photoEl) {
         photo = photoEl.getAttribute('data-photo') || photoEl.getAttribute('src') || '';
       }
      var name = parent.querySelector('h4,h5,h6') ? parent.querySelector('h4,h5,h6').innerText : '';
      var position = parent.querySelector('p') ? parent.querySelector('p').innerText : '';
      var phone = parent.getAttribute('data-work_phone') || '';
      var qualifications = parent.getAttribute('data-qualifications') || '';
      var previousPositions = parent.getAttribute('data-previous_positions') || '';
      var email = parent.getAttribute('data-email') || '';
      
      // อัปเดตข้อมูลหลัก
      document.getElementById('modalPhoto').src = photo && photo !== '#' ? photo : '/assets/img/default-user.png';
      document.getElementById('modalName').innerText = name;
      document.getElementById('modalPosition').innerText = position;
      
      // จัดการข้อมูลติดต่อ
      const hasContactInfo = phone || email;
      toggleSection('contactSection', hasContactInfo);
      
      if (phone) {
        document.getElementById('modalPhone').innerHTML = '<i class="fas fa-phone"></i> ' + phone;
      } else {
        document.getElementById('modalPhone').innerHTML = '';
      }
      
      if (email) {
        document.getElementById('modalEmail').innerHTML = '<i class="fas fa-envelope"></i> ' + email;
      } else {
        document.getElementById('modalEmail').innerHTML = '';
      }
      
      // จัดการคุณวุฒิ
      const hasQualifications = qualifications && qualifications.trim();
      toggleSection('qualificationsSection', hasQualifications);
      if (hasQualifications) {
        document.getElementById('modalQualifications').innerHTML = formatListData(qualifications);
      }
      
      // จัดการประวัติการทำงาน
      const hasPreviousPositions = previousPositions && previousPositions.trim();
      toggleSection('previousPositionsSection', hasPreviousPositions);
      if (hasPreviousPositions) {
        document.getElementById('modalPreviousPositions').innerHTML = formatListData(previousPositions);
      }
      
      // แสดง modal
      document.getElementById('commanderModal').style.display = 'flex';
      
      // เพิ่ม smooth scroll สำหรับ modal body
      setTimeout(() => {
        const modalBody = document.querySelector('.commander-modal-body');
        if (modalBody) {
          modalBody.scrollTop = 0;
        }
      }, 100);
    });
  });
  
  // ปิด modal
  document.getElementById('commanderModalClose').onclick = function() {
    document.getElementById('commanderModal').style.display = 'none';
  };
  
  // ปิด modal เมื่อคลิกนอก modal
  window.onclick = function(event) {
    var modal = document.getElementById('commanderModal');
    if (event.target === modal) {
      modal.style.display = 'none';
    }
  };
  
  // ปิด modal เมื่อกด ESC
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
      var modal = document.getElementById('commanderModal');
      if (modal.style.display === 'flex') {
        modal.style.display = 'none';
      }
    }
  });
});
</script>

<?php
function commander_data_attrs($commander) {
  $attrs = '';
  if (!empty($commander['qualifications'])) $attrs .= ' data-qualifications="' . htmlspecialchars($commander['qualifications']) . '"';
  if (!empty($commander['previous_positions'])) $attrs .= ' data-previous_positions="' . htmlspecialchars($commander['previous_positions']) . '"';
  if (!empty($commander['email'])) $attrs .= ' data-email="' . htmlspecialchars($commander['email']) . '"';
  if (!empty($commander['work_phone'])) $attrs .= ' data-work_phone="' . htmlspecialchars($commander['work_phone']) . '"';
  return $attrs;
}
?>
