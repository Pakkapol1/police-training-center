<?php
// Helper for data attributes used by modal/details
if (!function_exists('commander_data_attrs')) {
    function commander_data_attrs($commander) {
        $attrs = '';
        if (!empty($commander['qualifications'])) $attrs .= ' data-qualifications="' . htmlspecialchars($commander['qualifications']) . '"';
        if (!empty($commander['previous_positions'])) $attrs .= ' data-previous_positions="' . htmlspecialchars($commander['previous_positions']) . '"';
        if (!empty($commander['email'])) $attrs .= ' data-email="' . htmlspecialchars($commander['email']) . '"';
        if (!empty($commander['work_phone'])) $attrs .= ' data-work_phone="' . htmlspecialchars($commander['work_phone']) . '"';
        return $attrs;
    }
}

// Recursive renderer for the org chart tree
if (!function_exists('render_commander_tree')) {
    function render_commander_tree($commander, $level = 1) {
        $levelClass = 'org-node-level' . min($level, 3);
        $childLevel = min($level + 1, 3);
        $branchClass = 'org-branch-level' . $childLevel;
        ?>
        <div class="tree-container">
            <div class="org-node <?= $levelClass ?>" <?= commander_data_attrs($commander) ?>>
                <div class="commander-photo">
                    <?php if (!empty($commander['photo'])): ?>
                        <img src="<?= htmlspecialchars($commander['photo']) ?>" alt="<?= htmlspecialchars($commander['full_name'] ?? '') ?>" data-photo="<?= htmlspecialchars($commander['photo']) ?>">
                    <?php else: ?>
                        <div class="photo-placeholder"><i class="fas fa-user"></i></div>
                    <?php endif; ?>
                </div>
                <div class="commander-info">
                    <h<?= $level + 3 ?>><?= htmlspecialchars(($commander['rank_name'] ?? '') . ' ' . ($commander['full_name'] ?? '')) ?></h<?= $level + 3 ?>>
                    <p><?= htmlspecialchars($commander['position_name'] ?? '') ?></p>
                    <?php if (!empty($commander['work_phone'])): ?>
                        <div class="contact-info"><i class="fas fa-phone"></i> <?= htmlspecialchars($commander['work_phone']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($commander['children'])): ?>
                <div class="org-connector-vertical"></div>
                <div class="org-branch <?= $branchClass ?> has-multiple-children" data-children-count="<?= count($commander['children']) ?>">
                    <?php foreach ($commander['children'] as $child): ?>
                        <?php render_commander_tree($child, $level + 1); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
?>

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
        <?php else: ?>
            <p style="text-align:center;color:#555;">ไม่มีข้อมูลผู้บังคับบัญชาที่จะแสดง</p>
        <?php endif; ?>
</div>

<style>
.org-section { width: 100%; margin: 10px 0; padding: 12px; border-radius: 10px; background: #f8fafc; border: 2px solid #1e40af; }
.org-section-title { text-align: center; color: #1e40af; font-weight: 700; margin-bottom: 8px; }
.org-branch { display: flex; flex-direction: column; align-items: center; gap: 8px; width: 100%; }
.org-branch-level1 { display: flex; flex-direction: row; flex-wrap: wrap; justify-content: center; gap: 12px; }
.tree-container { display: flex; flex-direction: column; align-items: center; }
.org-connector-vertical { width: 3px; height: 18px; background: #1e40af; margin: 6px 0; }
.org-node { background: #fff; border: 2px solid #2563eb; border-radius: 8px; width: 280px; min-height: 180px; padding: 14px; display: flex; flex-direction: column; align-items: center; }
.org-node-level1 { border-width: 3px; }
.commander-photo img, .photo-placeholder { width: 100px; height: 100px; border-radius: 8px; object-fit: cover; border: 3px solid #2563eb; margin-bottom: 10px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; }
.photo-placeholder i { color: #64748b; font-size: 2rem; }
.commander-info h4, .commander-info h5, .commander-info h6 { color: #1e40af; font-weight: 600; margin: 6px 0; text-align: center; }
.commander-info p { color: #475569; margin: 0 0 6px 0; text-align: center; }
.contact-info { color: #64748b; font-size: 0.9rem; }
@media (max-width: 768px) { .org-branch-level1 { flex-direction: column; } .org-node { width: 90vw; max-width: 320px; } }
</style>