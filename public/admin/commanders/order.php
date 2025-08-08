<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('ROOT_PATH', dirname(dirname(dirname(__DIR__))));
define('SRC_PATH', ROOT_PATH . '/src');

if (file_exists(SRC_PATH . '/config/database.php')) {
    require_once SRC_PATH . '/config/database.php';
}
require_once SRC_PATH . '/models/CommanderModel.php';

$commanderModel = new CommanderModel();
$commanders = $commanderModel->getAllCommanders();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดลำดับผู้บังคับบัญชา</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { font-family: 'Sarabun', 'Prompt', sans-serif; background: #f8fafc; }
        .container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 18px; box-shadow: 0 2px 16px rgba(30,58,138,0.08); padding: 32px 28px; }
        h2 { color: #1e3a8a; text-align: center; margin-bottom: 32px; }
        .sortable-list { list-style: none; padding: 0; margin: 0; }
        .sortable-item { display: flex; align-items: center; background: #f0f7ff; border: 2px solid #3b82f6; border-radius: 12px; margin-bottom: 12px; padding: 12px 18px; cursor: grab; transition: box-shadow 0.2s; }
        .sortable-item:hover { box-shadow: 0 4px 18px rgba(30,58,138,0.10); }
        .sortable-item .photo { width: 48px; height: 48px; border-radius: 10px; background: #e5e7eb; margin-right: 18px; overflow: hidden; display: flex; align-items: center; justify-content: center; }
        .sortable-item .photo img { width: 100%; height: 100%; object-fit: cover; }
        .sortable-item .info { flex: 1; }
        .sortable-item .info .name { font-weight: 700; color: #1e3a8a; }
        .sortable-item .info .position { color: #374151; font-size: 0.98rem; }
        .sortable-item .group { font-size: 0.92rem; color: #2563eb; margin-left: 12px; }
        .save-btn { display: block; margin: 32px auto 0 auto; background: #2563eb; color: #fff; border: none; border-radius: 8px; padding: 12px 36px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .save-btn:hover { background: #1e3a8a; }
        @media (max-width: 600px) { .container { padding: 10px 2vw; } .sortable-item { flex-direction: column; align-items: flex-start; } }
    </style>
</head>
<body>
<div class="container">
    <h2>จัดลำดับการแสดงผู้บังคับบัญชา (ลากเพื่อเรียงลำดับใหม่)</h2>
    <form id="orderForm" method="post" action="">
        <ul id="commanderList" class="sortable-list">
            <?php foreach ($commanders as $commander): ?>
                <li class="sortable-item" data-id="<?= $commander['id'] ?>">
                    <div class="photo">
                        <?php if (!empty($commander['photo'])): ?>
                            <img src="<?= htmlspecialchars($commander['photo']) ?>" alt="<?= htmlspecialchars($commander['full_name']) ?>">
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                        <?php endif; ?>
                    </div>
                    <div class="info">
                        <div class="name"><?= htmlspecialchars($commander['rank_name'] . ' ' . $commander['full_name']) ?></div>
                        <div class="position"><?= htmlspecialchars($commander['position_name']) ?></div>
                    </div>
                    <div class="group">[<?= htmlspecialchars($commander['group']) ?>]</div>
                </li>
            <?php endforeach; ?>
        </ul>
        <input type="hidden" name="order" id="orderInput">
        <button type="submit" class="save-btn"><i class="fas fa-save"></i> บันทึกลำดับ</button>
    </form>
    <?php
    // Handle POST (save order)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order'])) {
        $order = explode(',', $_POST['order']);
        foreach ($order as $idx => $id) {
            $commanderModel->updateSortOrder($id, $idx+1);
        }
        echo '<div style="color:green;text-align:center;margin-top:18px;font-weight:600;">บันทึกลำดับใหม่เรียบร้อยแล้ว</div>';
        echo '<script>setTimeout(()=>location.reload(), 1200);</script>';
    }
    ?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script>
var el = document.getElementById('commanderList');
var sortable = Sortable.create(el, {
    animation: 180,
    onEnd: function () {
        var ids = Array.from(el.children).map(li => li.getAttribute('data-id'));
        document.getElementById('orderInput').value = ids.join(',');
    }
});
document.getElementById('orderForm').onsubmit = function(e) {
    var ids = Array.from(el.children).map(li => li.getAttribute('data-id'));
    document.getElementById('orderInput').value = ids.join(',');
};
</script>
</body>
</html> 