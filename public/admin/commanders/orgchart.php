<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../src/config/database.php';
require_once __DIR__ . '/../../../src/models/CommanderModel.php';

// --- การรับ POST JSON ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') === 0) {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Log ข้อมูลที่รับมาแบบละเอียด
    file_put_contents(__DIR__.'/debug.log', "\n=== NEW SAVE REQUEST ===\n", FILE_APPEND);
    file_put_contents(__DIR__.'/debug.log', "RAW INPUT: ".json_encode($data, JSON_UNESCAPED_UNICODE)."\n", FILE_APPEND);

    if (isset($data['tree']) && is_array($data['tree'])) {
        require_once __DIR__ . '/../../../src/models/CommanderModel.php';
        $commanderModel = new CommanderModel();

        $updateCount = 0;
        $errorCount = 0;

        // ประมวลผลข้อมูลโดยตรงจาก flat array
        foreach ($data['tree'] as $group => $nodes) {
            file_put_contents(__DIR__.'/debug.log', "Processing group: $group with " . count($nodes) . " nodes\n", FILE_APPEND);
            
            // ⭐ เพิ่มการ debug สำหรับแต่ละ node
            foreach ($nodes as $index => $node) {
                file_put_contents(__DIR__.'/debug.log', "Node $index: " . json_encode($node, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
            }
            
            // อัปเดต parent_id ของแต่ละ node ตามข้อมูลที่ส่งมา
            foreach ($nodes as $node) {
                $nodeId = $node['nodeId'] ?? $node['id'] ?? null;
                $parentId = isset($node['parent_id']) && $node['parent_id'] !== '' && $node['parent_id'] !== 'null' 
                    ? (int)$node['parent_id'] 
                    : null;
                
                if ($nodeId) {
                    file_put_contents(__DIR__.'/debug.log', "Updating node $nodeId: parent_id = " . ($parentId ?? 'NULL') . "\n", FILE_APPEND);
                    $result = $commanderModel->updateParentId($nodeId, $parentId);
                    
                    if ($result) {
                        $updateCount++;
                        file_put_contents(__DIR__.'/debug.log', "✓ Node $nodeId updated successfully\n", FILE_APPEND);
                    } else {
                        $errorCount++;
                        file_put_contents(__DIR__.'/debug.log', "✗ Node $nodeId update failed\n", FILE_APPEND);
                    }
                } else {
                    file_put_contents(__DIR__.'/debug.log', "⚠ Skipping node: missing nodeId\n", FILE_APPEND);
                    $errorCount++;
                }
            }
        }

        file_put_contents(__DIR__.'/debug.log', "UPDATE SUMMARY: Success=$updateCount, Errors=$errorCount\n", FILE_APPEND);
        
        // ⭐ เพิ่มข้อมูล debug เพิ่มเติม
        file_put_contents(__DIR__.'/debug.log', "Response: " . json_encode([
            'success' => true, 
            'updated' => $updateCount, 
            'errors' => $errorCount,
            'message' => "อัปเดตสำเร็จ $updateCount รายการ"
        ], JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
        
        echo json_encode([
            'success' => true, 
            'updated' => $updateCount, 
            'errors' => $errorCount,
            'message' => "อัปเดตสำเร็จ $updateCount รายการ"
        ]);
        exit;
    } else {
        file_put_contents(__DIR__.'/debug.log', "ERROR: Invalid tree data structure\n", FILE_APPEND);
        echo json_encode(['success' => false, 'error' => 'ข้อมูล tree ไม่ถูกต้อง']);
        exit;
    }
}

// ดึงข้อมูลผู้บังคับบัญชา
$commanderModel = new CommanderModel();
$commanders = $commanderModel->getAllCommanders(true);

// Group by group
$grouped = [];
foreach ($commanders as $c) {
    $g = $c['group'] ?: 'ไม่ระบุฝ่าย';
    $grouped[$g][] = $c;
}

// ตรวจสอบกลุ่มที่เลือกจาก GET parameter
$selectedGroup = $_GET['group'] ?? null;

// หากไม่ได้เลือกกลุ่ม ใช้กลุ่มแรกที่มีข้อมูล
if (!$selectedGroup || !isset($grouped[$selectedGroup])) {
    $selectedGroup = null;
foreach ($grouped as $gName => $nodes) {
    if (count($nodes) > 0) {
            $selectedGroup = $gName;
        break;
        }
    }
}

// Debug LOG
$orgDataArr = [];
if ($selectedGroup && isset($grouped[$selectedGroup])) {
    $orgDataArr = buildOrgChartData($grouped[$selectedGroup]);
    file_put_contents(__DIR__.'/debug.log', "ORGDATA = ".json_encode($orgDataArr, JSON_UNESCAPED_UNICODE)."\n", FILE_APPEND);
}

// --- ฟังก์ชัน tree ---
function buildOrgChartData($commanders) {
    if (empty($commanders)) {
        return [];
    }
    
    file_put_contents(__DIR__.'/debug.log', "RAW COMMANDERS DATA = ".json_encode($commanders, JSON_UNESCAPED_UNICODE)."\n", FILE_APPEND);
    
    // สร้าง array ของ items พร้อม parent-child relationship
    $items = [];
    foreach ($commanders as $c) {
        $parentId = null;
        if (!empty($c['parent_id']) && $c['parent_id'] != '0' && $c['parent_id'] != 0) {
            $parentId = intval($c['parent_id']);
        }
        
        $items[$c['id']] = [
            'nodeId'    => $c['id'],
            'name'      => trim($c['rank_name'] . ' ' . $c['full_name']),
            'title'     => $c['position_name'],
            'group'     => $c['group'],
            'photo'     => $c['photo'],
            'parent_id' => $parentId,
            'children'  => []
        ];
        
        file_put_contents(__DIR__.'/debug.log', "ITEM {$c['id']}: parent_id = " . ($parentId ?? 'NULL') . " (from DB: {$c['parent_id']})\n", FILE_APPEND);
    }
    
    // สร้าง tree structure โดยใช้ parent_id
    $tree = [];
    foreach ($items as $id => &$item) {
        if ($item['parent_id'] && isset($items[$item['parent_id']])) {
            // มี parent - เพิ่มเป็น child ของ parent
            $items[$item['parent_id']]['children'][] = &$item;
            file_put_contents(__DIR__.'/debug.log', "NODE $id is child of {$item['parent_id']}\n", FILE_APPEND);
        } else {
            // ไม่มี parent หรือ parent ไม่อยู่ในกลุ่ม - เป็น root node
            $tree[] = &$item;
            file_put_contents(__DIR__.'/debug.log', "NODE $id is ROOT node\n", FILE_APPEND);
        }
    }
    
    // ลบ children array ที่ว่าง
    function cleanEmptyChildren(&$node) {
        if (empty($node['children'])) {
            unset($node['children']);
        } else {
            foreach ($node['children'] as &$child) {
                cleanEmptyChildren($child);
            }
        }
    }
    
    foreach ($tree as &$rootNode) {
        cleanEmptyChildren($rootNode);
    }
    
    file_put_contents(__DIR__.'/debug.log', "BUILD TREE RESULT = ".json_encode($tree, JSON_UNESCAPED_UNICODE)."\n", FILE_APPEND);
    
    return $tree;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดแผนผังผู้บังคับบัญชา</title>
    
   <!-- เรียก jQuery ก่อน -->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- ใช้ CDN สำหรับ orgchart -->
    <script src="https://unpkg.com/orgchart@3.1.1/dist/js/jquery.orgchart.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/orgchart@3.1.1/dist/css/jquery.orgchart.min.css">
    
    <!-- Font Awesome สำหรับ icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body{font-family:'Sarabun','Prompt',sans-serif;background:#f8fafc;margin:0;padding:20px;}
        .container{max-width:1400px;margin:0 auto;background:#fff;border-radius:18px;box-shadow:0 2px 16px rgba(30,58,138,0.08);padding:32px 28px;}
      h2{text-align:center;color:#1e3a8a;margin-bottom:32px;}
        .controls{background:#f8fafc;border-radius:12px;padding:20px;margin-bottom:24px;border:2px solid #e5e7eb;}
        .group-selector{display:flex;align-items:center;justify-content:center;gap:15px;margin-bottom:20px;}
        .group-selector label{font-weight:600;color:#374151;}
        .group-selector select{padding:8px 15px;border:2px solid #d1d5db;border-radius:8px;font-size:1rem;background:#fff;min-width:200px;}
        .group-selector select:focus{outline:none;border-color:#2563eb;}
        .chart-box{background:#f8fafc;border-radius:16px;box-shadow:0 2px 8px rgba(30,58,138,0.06);padding:18px;min-width:340px;margin:0 auto;min-height:400px;overflow:auto;}
      .group-title{font-size:1.25rem;font-weight:700;color:#2563eb;text-align:center;margin-bottom:18px;}
        
        /* ปุ่มแบบใหม่ */
        .btn-group{text-align:center;margin:20px 0;}
        .action-btn{display:inline-block;margin:5px;padding:12px 20px;border:none;border-radius:8px;font-size:1rem;font-weight:600;cursor:pointer;transition:all 0.3s ease;text-decoration:none;}
        .action-btn i{margin-right:8px;}
        
        .btn-primary{background:#2563eb;color:#fff;}
        .btn-primary:hover{background:#1d4ed8;transform:translateY(-2px);}
        
        .btn-success{background:#059669;color:#fff;}
        .btn-success:hover{background:#047857;transform:translateY(-2px);}
        
        .btn-warning{background:#f59e0b;color:#fff;}
        .btn-warning:hover{background:#d97706;transform:translateY(-2px);}
        
        .btn-info{background:#0891b2;color:#fff;}
        .btn-info:hover{background:#0e7490;transform:translateY(-2px);}
        
        .btn-secondary{background:#6b7280;color:#fff;}
        .btn-secondary:hover{background:#4b5563;transform:translateY(-2px);}
        
        /* ปุ่มบันทึกใหญ่ */
        .save-btn{display:block;margin:20px auto;background:#059669;color:#fff;border:none;border-radius:10px;padding:16px 40px;font-size:1.2rem;font-weight:700;cursor:pointer;transition:all .3s ease;box-shadow:0 4px 12px rgba(5,150,105,0.3);}
        .save-btn:hover{background:#047857;transform:translateY(-2px);box-shadow:0 8px 20px rgba(5,150,105,0.4);}
        .save-btn:disabled{background:#9ca3af!important;cursor:not-allowed!important;transform:none!important;box-shadow:none!important;}
        
        .back-btn{display:inline-block;margin-bottom:20px;padding:10px 20px;background:#6b7280;color:#fff;text-decoration:none;border-radius:8px;transition:background .2s;}
        .back-btn:hover{background:#4b5563;}
        .no-data{text-align:center;color:#6b7280;font-size:1.1rem;padding:40px;}
        
        /* Custom orgchart styles */
        .orgchart{background:#f8fafc;}
        .orgchart .node{background:#fff;border:2px solid #3b82f6;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);min-width:200px;padding:10px;position:relative;transition:all 0.3s ease;}
        .orgchart .node:hover{box-shadow:0 4px 12px rgba(0,0,0,0.15);transform:translateY(-2px);}
        .orgchart .node.ui-draggable-dragging{transform:rotate(3deg);box-shadow:0 8px 20px rgba(59,130,246,0.4);border-color:#1d4ed8;z-index:1000;}
        .orgchart .node .title{font-weight:600;color:#1e3a8a;text-align:center;word-wrap:break-word;}
        .orgchart .lines .topLine, .orgchart .lines .rightLine, .orgchart .lines .leftLine, .orgchart .lines .downLine{border-color:#3b82f6;}
        
        /* Draggable node styles */
        .draggable-node{cursor:move!important;}
        .drag-handle{position:absolute;top:5px;right:5px;color:#6b7280;font-size:12px;opacity:0;transition:opacity 0.2s ease;z-index:10;background:rgba(255,255,255,0.8);padding:2px 4px;border-radius:3px;}
        .node:hover .drag-handle{opacity:1;}
        .drag-handle:hover{color:#3b82f6;background:rgba(59,130,246,0.1);}
        
        /* Drop zone highlighting */
        .orgchart .node.drop-target{border-color:#10b981!important;background:#f0fdf4!important;transform:scale(1.05);}
        .orgchart .node.drop-invalid{border-color:#ef4444!important;background:#fef2f2!important;}
        
        .commander-node{padding:8px;text-align:center;}
        .commander-photo{width:50px;height:50px;border-radius:50%;border:3px solid #ffd700;margin:0 auto 8px auto;object-fit:cover;}
        .commander-name{font-weight:700;color:#1e3a8a;font-size:0.9rem;margin-bottom:4px;}
        .commander-position{color:#374151;font-size:0.8rem;}
        
        /* Status */
        .status-box{background:#f0f9ff;border:1px solid #0ea5e9;border-radius:8px;padding:15px;margin:15px 0;text-align:center;}
        .status-success{background:#f0fdf4;border-color:#22c55e;color:#15803d;}
        .status-error{background:#fef2f2;border-color:#ef4444;color:#dc2626;}
        .status-warning{background:#fffbeb;border-color:#f59e0b;color:#d97706;}
        .status-info{background:#f0f9ff;border-color:#3b82f6;color:#1d4ed8;}
    </style>
</head> 
<body>
<div class="container">
    <a href="/admin?action=commanders" class="back-btn">
        <i class="fas fa-arrow-left"></i> กลับไปหน้าจัดการผู้บังคับบัญชา
    </a>
    
    <h2>จัดแผนผังผู้บังคับบัญชา</h2>
    
    <?php if (!empty($grouped)): ?>
        <div class="controls">
            <div class="group-selector">
                <label for="groupSelect">เลือกกลุ่ม/ฝ่าย:</label>
                <select id="groupSelect" onchange="changeGroup()">
                    <?php foreach ($grouped as $groupName => $groupCommanders): ?>
                        <?php if (count($groupCommanders) > 0): ?>
                            <option value="<?= htmlspecialchars($groupName) ?>" 
                                    <?= $groupName === $selectedGroup ? 'selected' : '' ?>>
                                <?= htmlspecialchars($groupName) ?> (<?= count($groupCommanders) ?> คน)
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="btn-group">
                <button id="editStructureBtn" class="action-btn btn-primary">
                    <i class="fas fa-sitemap"></i> ย้ายตำแหน่ง
                </button>
                <button id="quickSaveBtn" class="action-btn btn-success">
                    <i class="fas fa-save"></i> บันทึกทันที
                </button>
            </div>
        </div>
        
        <?php if ($selectedGroup && !empty($orgDataArr)): ?>
            <div class="status-box status-success" id="statusBox">
                <i class="fas fa-arrows-alt"></i> 
                <strong>วิธีย้ายตำแหน่ง:</strong> 
                ลากจับ node ที่ต้องการย้าย → วางบน node ที่ต้องการให้เป็นหัวหน้า → กดปุ่ม "บันทึกการเปลี่ยนแปลง" | 
                หรือใช้ปุ่ม "ย้ายตำแหน่ง" เพื่อเลือกแบบ dropdown
            </div>
            
    <div class="chart-box">
                <div class="group-title"><?= htmlspecialchars($selectedGroup) ?></div>
                <div id="chart-main"></div>
    </div>
            
            <button id="saveBtn" class="save-btn">
                <i class="fas fa-save"></i> บันทึกการเปลี่ยนแปลง
            </button>
            
    <div id="saveStatus" style="text-align:center;margin-top:12px;"></div>
  <?php else: ?>
            <div class="no-data">
                <i class="fas fa-info-circle" style="font-size:2rem;margin-bottom:10px;"></i><br>
                ไม่พบข้อมูลผู้บังคับบัญชาในกลุ่มที่เลือก
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="no-data">
            <i class="fas fa-exclamation-triangle" style="font-size:2rem;margin-bottom:10px;"></i><br>
            ไม่พบข้อมูลผู้บังคับบัญชาในระบบ
        </div>
  <?php endif; ?>
</div>

<script>
function changeGroup() {
    var selectedGroup = document.getElementById('groupSelect').value;
    window.location.href = window.location.pathname + '?group=' + encodeURIComponent(selectedGroup);
}

$(document).ready(function(){
    <?php if ($selectedGroup && !empty($orgDataArr)): ?>
        var originalTreeData = <?= json_encode($orgDataArr, JSON_UNESCAPED_UNICODE) ?>;
        var currentChanges = {};
        var dragEnabled = false;
        
        // ⭐ แปลง tree structure เป็น flat array
        function flattenTreeData(treeData) {
            var flatArray = [];
            
            function traverse(node) {
                // เพิ่ม node ปัจจุบันลงใน flat array
                flatArray.push({
                    nodeId: node.nodeId,
                    name: node.name,
                    title: node.title,
                    group: node.group,
                    photo: node.photo,
                    parent_id: node.parent_id
                });
                
                // ถ้ามี children ให้ traverse ต่อ
                if (node.children && node.children.length > 0) {
                    node.children.forEach(function(child) {
                        traverse(child);
                    });
                }
            }
            
            // เริ่ม traverse จาก root nodes
            treeData.forEach(function(rootNode) {
                traverse(rootNode);
            });
            
            return flatArray;
        }
        
        // ⭐ สร้าง originalData เป็น flat array
        var originalData = flattenTreeData(originalTreeData);
        
        console.log('Position management system initialized with', originalData.length, 'people');

        // แปลงข้อมูลให้เข้ากับรูปแบบของ orgchart
        function transformData(nodes) {
            if (!Array.isArray(nodes)) {
                return nodes;
            }
            
            return nodes.map(function(node) {
                var transformed = {
                    id: node.nodeId || node.id,
                    name: node.name || '',
                    title: node.title || '',
                    className: 'commander-node'
                };
                
                if (node.children && node.children.length > 0) {
                    transformed.children = transformData(node.children);
                }
                
                return transformed;
            });
        }

        var transformedData = transformData(originalTreeData);
        console.log('Transformed data:', transformedData);

        // สร้าง orgchart
        var chartData = transformedData.length === 1 ? transformedData[0] : {
            id: 'root',
            name: '<?= htmlspecialchars($selectedGroup) ?>',
            title: 'กลุ่มหลัก',
            children: transformedData,
            className: 'commander-node'
        };

        try {
            var $chart = $('#chart-main').orgchart({
                data: chartData,
        nodeContent: 'title',
                // Enhanced drag and drop with better visual feedback
        draggable: true,
                direction: 't2b',
        pan: true,
        zoom: true,
                zoominLimit: 7,
                zoomoutLimit: 0.5,
        createNode: function($node, data) {
                    // เพิ่ม data attributes สำหรับการติดตาม
                    $node.attr('data-node-id', data.id);
                    $node.attr('draggable', 'true');
                    
                    // เพิ่ม CSS class สำหรับการลาก
                    $node.addClass('draggable-node');
                    
                    // ค้นหาข้อมูลเพิ่มเติมจาก originalData
                    var originalNode = findNodeById(originalData, data.id);
                    
                    var html = '<div class="commander-node" data-node-id="' + data.id + '">';
                    html += '<div class="drag-handle" title="ลากเพื่อย้ายตำแหน่ง"><i class="fas fa-grip-vertical"></i></div>';
                    
                    if (originalNode && originalNode.photo) {
                        html += '<img src="' + originalNode.photo + '" class="commander-photo" alt="' + data.name + '">';
                    }
                    
                    html += '<div class="commander-name">' + data.name + '</div>';
                    html += '<div class="commander-position">' + data.title + '</div>';
                    html += '</div>';
                    
                    $node.html(html);
                    
                    // เพิ่ม event listeners สำหรับการลาก
                    $node.on('mouseenter', function() {
                        $(this).css('cursor', 'move');
                        $(this).find('.drag-handle').fadeIn(200);
                    });
                    
                    $node.on('mouseleave', function() {
                        $(this).find('.drag-handle').fadeOut(200);
                    });
                },
                // Enhanced dragstart event
                'dragstart': function($draggedNode) {
                    console.log('=== DRAG START DETECTED ===');
                    var draggedId = $draggedNode.attr('data-node-id');
                    console.log('Dragged node ID:', draggedId);
                    
                    // เพิ่ม visual feedback
                    $draggedNode.addClass('ui-draggable-dragging');
                    updateStatus('info', 'กำลังลาก: ' + getNodeName(draggedId) + ' - วางบน node ที่ต้องการให้เป็นหัวหน้า');
                    
                    // เพิ่มการ highlight ให้ node อื่นๆ
                    $('.orgchart .node').not($draggedNode).addClass('drop-target');
                    
                    // Debug alert
                    alert('เริ่มลาก: ' + getNodeName(draggedId));
                },
                
                // Enhanced drop event with multiple detection methods
                'drop': function($draggedNode, $dragZone, $dropZone) {
                    console.log('=== DROP EVENT DETECTED ===');
                    console.log('Dragged Node:', $draggedNode);
                    console.log('Drop Zone:', $dropZone);
                    
                    // ลบ visual feedback
                    $('.orgchart .node').removeClass('drop-target drop-invalid ui-draggable-dragging');
                    
                    var draggedId = $draggedNode.attr('data-node-id') || $draggedNode.find('[data-node-id]').first().attr('data-node-id');
                    var newParentId = null;
                    
                    if ($dropZone && $dropZone.length > 0) {
                        newParentId = $dropZone.attr('data-node-id') || $dropZone.find('[data-node-id]').first().attr('data-node-id');
                        if (newParentId === 'root') newParentId = null;
                    }
                    
                    console.log('Final Dragged ID:', draggedId);
                    console.log('New Parent ID:', newParentId);
                    
                    // ตรวจสอบการลากที่ไม่ถูกต้อง
                    if (draggedId === newParentId) {
                        updateStatus('error', 'ไม่สามารถวางบนตัวเองได้');
                        alert('ข้อผิดพลาด: ไม่สามารถวางบนตัวเองได้');
                        return false;
                    }
                    
                    // บันทึกการเปลี่ยนแปลง
                    if (draggedId && draggedId !== 'root') {
                        currentChanges[draggedId] = newParentId;
                        console.log('Changes recorded:', currentChanges);
                        
                        updateStatus('success', 'ย้ายตำแหน่งสำเร็จ: ' + getNodeName(draggedId) + ' → ' + (newParentId ? getNodeName(newParentId) : 'ระดับสูงสุด'));
                        
                        // เปิดปุ่มบันทึก
                        $('#saveBtn').prop('disabled', false);
                        
                        // แสดง alert ยืนยัน
                        alert('ย้ายตำแหน่งสำเร็จ!\n\n' + 
                              'คน: ' + getNodeName(draggedId) + '\n' +
                              'ย้ายไปอยู่ภายใต้: ' + (newParentId ? getNodeName(newParentId) : 'ระดับสูงสุด') + '\n\n' +
                              'กดปุ่ม "บันทึกการเปลี่ยนแปลง" เพื่อยืนยัน');
                    } else {
                        updateStatus('error', 'การลากล้มเหลว: ข้อมูล node ไม่ถูกต้อง');
                        alert('การลากล้มเหลว: ข้อมูล node ไม่ถูกต้อง');
                    }
                },
                
                // Enhanced dragend event  
                'dragend': function($draggedNode) {
                    console.log('=== DRAG END ===');
                    console.log('Drag ended for node:', $draggedNode.attr('data-node-id'));
                }
            });

            // ฟังก์ชันหาข้อมูล node ตาม id
            function findNodeById(nodes, id) {
                for (var i = 0; i < nodes.length; i++) {
                    if (nodes[i].nodeId == id || nodes[i].id == id) {
                        return nodes[i];
                    }
                    if (nodes[i].children) {
                        var found = findNodeById(nodes[i].children, id);
                        if (found) return found;
                    }
                }
                return null;
            }
            
            function getNodeName(id) {
                var node = findNodeById(originalData, id);
                return node ? node.name : 'ID:' + id;
            }

            // เพิ่มระบบ HTML5 drag and drop สำรอง
            function enableHTML5DragDrop() {
                console.log('=== ENABLING HTML5 DRAG DROP ===');
                
                $('.orgchart .node').each(function() {
                    var $node = $(this);
                    var nodeId = $node.attr('data-node-id');
                    
                    if (nodeId && nodeId !== 'root') {
                        // เปิดใช้งาน HTML5 draggable
                        $node.attr('draggable', 'true');
                        
                        // HTML5 drag events
                        $node.on('dragstart', function(e) {
                            console.log('HTML5 dragstart for node:', nodeId);
                            e.originalEvent.dataTransfer.setData('text/plain', nodeId);
                            $(this).addClass('ui-draggable-dragging');
                            updateStatus('info', 'กำลังลาก (HTML5): ' + getNodeName(nodeId));
                        });
                        
                        $node.on('dragover', function(e) {
                            e.preventDefault(); // Allow drop
                        });
                        
                        $node.on('drop', function(e) {
                            e.preventDefault();
                            var draggedId = e.originalEvent.dataTransfer.getData('text/plain');
                            var droppedOnId = $(this).attr('data-node-id');
                            
                            console.log('HTML5 drop - dragged:', draggedId, 'dropped on:', droppedOnId);
                            
                            if (draggedId !== droppedOnId) {
                                currentChanges[draggedId] = droppedOnId === 'root' ? null : droppedOnId;
                                updateStatus('success', 'ย้ายตำแหน่งสำเร็จ (HTML5): ' + getNodeName(draggedId) + ' → ' + getNodeName(droppedOnId));
                                $('#saveBtn').prop('disabled', false);
                                alert('ลากสำเร็จ!\n\nคน: ' + getNodeName(draggedId) + '\nย้ายไปอยู่ภายใต้: ' + getNodeName(droppedOnId));
                            }
                            
                            $('.orgchart .node').removeClass('ui-draggable-dragging');
                        });
                        
                        $node.on('dragend', function() {
                            $(this).removeClass('ui-draggable-dragging');
                        });
                    }
                });
            }
            
            // เปิดใช้งาน HTML5 drag drop หลังจากสร้าง orgchart
            setTimeout(function() {
                enableHTML5DragDrop();
            }, 1000);





        } catch (error) {
            console.error('Error creating orgchart:', error);
            $('#chart-main').html('<div style="text-align:center;color:red;padding:40px;">เกิดข้อผิดพลาดในการสร้างแผนผัง<br>กรุณาลองใหม่อีกครั้ง</div>');
        }
    <?php endif; ?>



    // ฟังก์ชันอัปเดตสถานะ
    function updateStatus(type, message) {
        var statusClass = 'status-' + type;
        var icon = type === 'success' ? 'check-circle' : 
                  type === 'error' ? 'exclamation-circle' : 
                  type === 'warning' ? 'exclamation-triangle' : 'info-circle';
        
        $('#statusBox').removeClass('status-success status-error status-warning status-info')
                      .addClass(statusClass)
                      .html('<i class="fas fa-' + icon + '"></i> ' + message);
    }

    // ปุ่มบันทึก
    $('#saveBtn').click(function(){
        if (Object.keys(currentChanges).length === 0) {
            // หากไม่มีการเปลี่ยนแปลง ให้เลือกว่าจะบันทึกข้อมูลปัจจุบันหรือไม่
            if (confirm('ไม่พบการเปลี่ยนแปลง\n\nต้องการบันทึกข้อมูลปัจจุบันลงฐานข้อมูลหรือไม่?\n(จะช่วยรีเฟรชข้อมูลในระบบ)')) {
                quickSave();
            }
            return;
        }
        
        if (!confirm('ต้องการบันทึกการเปลี่ยนแปลงหรือไม่?')) {
            return;
        }
        
        saveChanges();
    });

    // ฟังก์ชันบันทึกการเปลี่ยนแปลง
    function saveChanges() {
        updateStatus('info', 'กำลังบันทึกการเปลี่ยนแปลง...');
        $('#saveBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> กำลังบันทึก...');
        
        if (Object.keys(currentChanges).length === 0) {
            alert('ไม่มีการเปลี่ยนแปลงให้บันทึก');
            $('#saveBtn').prop('disabled', false).html('<i class="fas fa-save"></i> บันทึกการเปลี่ยนแปลง');
            return;
        }
        
        // สร้างข้อมูลแบบ FLAT ARRAY
        var updatedData = [];
        
        originalData.forEach(function(node) {
            // ใช้ parent_id ใหม่จาก currentChanges หรือใช้ของเดิม
            var newParentId = currentChanges.hasOwnProperty(node.nodeId) ? currentChanges[node.nodeId] : node.parent_id;
            
            // แปลงค่า null, undefined, 'root' ให้เป็น null
            if (newParentId === 'root' || newParentId === undefined || newParentId === 'null' || newParentId === '') {
                newParentId = null;
            } else if (newParentId !== null) {
                newParentId = parseInt(newParentId);
                if (isNaN(newParentId)) {
                    newParentId = null;
                }
            }
            
            // สร้าง node แบบ FLAT
            var updatedNode = {
                nodeId: parseInt(node.nodeId),
                id: parseInt(node.nodeId),
                parent_id: newParentId
            };
            
            updatedData.push(updatedNode);
        });
        
        // ตรวจสอบ circular reference
        function hasCircularReference(data) {
            var visited = new Set();
            
            function checkNode(nodeId, path) {
                if (path.has(nodeId)) {
                    return true;
                }
                
                if (visited.has(nodeId)) {
                    return false;
                }
                
                visited.add(nodeId);
                path.add(nodeId);
                
                var node = data.find(function(n) { return n.nodeId == nodeId; });
                if (node && node.parent_id) {
                    if (checkNode(node.parent_id, path)) {
                        return true;
                    }
                }
                
                path.delete(nodeId);
                return false;
            }
            
            for (var i = 0; i < data.length; i++) {
                if (checkNode(data[i].nodeId, new Set())) {
                    return true;
                }
            }
            return false;
        }
        
        if (hasCircularReference(updatedData)) {
            alert('โครงสร้างที่กำหนดมีปัญหา: พบการวนซ้ำในลำดับชั้น กรุณาจัดใหม่');
            $('#saveBtn').prop('disabled', false).html('<i class="fas fa-save"></i> บันทึกการเปลี่ยนแปลง');
        return;
    }

        // เตรียมข้อมูลสำหรับส่ง - ส่งเป็น FLAT ARRAY
        var payload = {};
        payload['<?= addslashes($selectedGroup) ?>'] = updatedData;
        
        var requestData = {tree: payload};

    $.ajax({
            url: window.location.href,
            type: 'POST',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            data: JSON.stringify(requestData),
        success: function(res) {
                $('#saveBtn').prop('disabled', false).html('<i class="fas fa-save"></i> บันทึกการเปลี่ยนแปลง');
                
                if (res.success) {
                    var updatedCount = res.updated || 0;
                    
                    updateStatus('success', 'บันทึกสำเร็จ! อัปเดต ' + updatedCount + ' รายการ - กำลังรีโหลด...');
                    
                    // รีเซ็ตการเปลี่ยนแปลง
                    currentChanges = {};
                    
                    // แสดงข้อความยืนยันพร้อมจำนวนที่อัปเดต
                    alert('บันทึกสำเร็จ!\n\n' + 
                          'อัปเดต ' + updatedCount + ' รายการ\n' +
                          'จากทั้งหมด ' + originalData.length + ' คน\n\n' +
                          'กำลังรีโหลดหน้าเพื่อแสดงผลล่าสุด...');
                    
                    // รอสักครู่แล้วรีโหลด
                    setTimeout(function() {
                        window.location.reload(true);
                    }, 2000);
                } else {
                    var errorMsg = res.error || res.message || 'ไม่ทราบสาเหตุ';
                    updateStatus('error', 'บันทึกล้มเหลว: ' + errorMsg);
                    alert('บันทึกล้มเหลว!\n\n' + errorMsg + '\n\nกรุณาตรวจสอบข้อมูลและลองใหม่');
                }
            },
            error: function(xhr, status, err) {
                $('#saveBtn').prop('disabled', false).html('<i class="fas fa-save"></i> บันทึกการเปลี่ยนแปลง');
                updateStatus('error', 'เกิดข้อผิดพลาดในการบันทึก: ' + err + ' (Code: ' + xhr.status + ')');
                
                var errorDetail = 'HTTP ' + xhr.status + ': ' + err;
                if (xhr.responseText) {
                    errorDetail += '\n\nServer Response:\n' + xhr.responseText.substring(0, 500);
                }
                
                alert('เกิดข้อผิดพลาดในการบันทึก!\n\n' + errorDetail);
            }
        });
    }
    
    // ฟังก์ชันบันทึกทันที
    function quickSave() {
        updateStatus('info', 'กำลังบันทึกข้อมูลลงฐานข้อมูล...');
        $('#quickSaveBtn').prop('disabled', true).text('กำลังบันทึก...');
        
        // แปลงข้อมูลเป็น FLAT ARRAY เหมือนกับ saveChanges()
        var flatData = [];
        
        originalData.forEach(function(node) {
            var flatNode = {
                nodeId: parseInt(node.nodeId),
                id: parseInt(node.nodeId),
                parent_id: node.parent_id
            };
            flatData.push(flatNode);
        });
        
        var payload = {};
        payload['<?= addslashes($selectedGroup) ?>'] = flatData;
        
        $.ajax({
            url: window.location.href,
            type: 'POST',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            data: JSON.stringify({tree: payload}),
            success: function(res) {
                $('#quickSaveBtn').prop('disabled', false).html('<i class="fas fa-save"></i> บันทึกทันที');
                
                if (res.success) {
                    var updatedCount = res.updated || 0;
                    updateStatus('success', 'บันทึกทันทีสำเร็จ! อัปเดต ' + updatedCount + ' รายการ - ข้อมูลในระบบล่าสุดแล้ว');
                    alert('บันทึกทันทีสำเร็จ!\n\nอัปเดต ' + updatedCount + ' รายการ\nจากทั้งหมด ' + originalData.length + ' คน');
                } else {
                    updateStatus('error', 'บันทึกล้มเหลว: ' + (res.error || 'ไม่ทราบสาเหตุ'));
                }
            },
            error: function(xhr, status, err) {
                $('#quickSaveBtn').prop('disabled', false).html('<i class="fas fa-save"></i> บันทึกทันที');
                updateStatus('error', 'เกิดข้อผิดพลาดในการบันทึก: ' + err);
            }
        });
    }

    // จัดการ modal โครงสร้าง
    $('#editStructureBtn').click(function() {
        showStructureModal();
    });
    
    function showStructureModal() {
        var html = '<div style="max-height:400px;overflow-y:auto;">';
        
        // สร้างรายการผู้บังคับบัญชา
        originalData.forEach(function(node, index) {
            html += '<div style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:15px;margin-bottom:10px;">';
            html += '<div style="font-weight:600;color:#1e3a8a;margin-bottom:8px;">' + node.name + '</div>';
            html += '<div style="color:#6b7280;margin-bottom:10px;">' + node.title + '</div>';
            html += '<div>';
            html += '<label style="display:block;margin-bottom:5px;font-weight:500;">ผู้บังคับบัญชาต้นสังกัด:</label>';
            html += '<select class="parent-select" data-node-id="' + node.nodeId + '" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">';
            html += '<option value="">-- ไม่มีผู้บังคับบัญชาต้นสังกัด (ระดับสูงสุด) --</option>';
            
            // เพิ่มตัวเลือกผู้บังคับบัญชาอื่นๆ
            originalData.forEach(function(otherNode) {
                if (otherNode.nodeId !== node.nodeId) {
                    var selected = (node.parent_id == otherNode.nodeId) ? 'selected' : '';
                    html += '<option value="' + otherNode.nodeId + '" ' + selected + '>' + otherNode.name + '</option>';
                }
            });
            
            html += '</select>';
            html += '</div>';
            html += '</div>';
        });
        
        html += '</div>';
        $('#structureList').html(html);
        $('#structureModal').show();
    }
    
    $('#cancelStructureBtn').click(function() {
        $('#structureModal').hide();
    });
    
    $('#saveStructureBtn').click(function() {
        var changes = {};
        var hasChanges = false;
        
        // เก็บการเปลี่ยนแปลงจาก modal
        $('.parent-select').each(function() {
            var nodeId = parseInt($(this).data('node-id'));
            var parentId = $(this).val() ? parseInt($(this).val()) : null;
            
            // หาข้อมูลเดิม
            var originalNode = originalData.find(function(n) { return n.nodeId == nodeId; });
            if (originalNode && originalNode.parent_id != parentId) {
                changes[nodeId] = parentId;
                hasChanges = true;
            }
        });
        
        if (!hasChanges) {
            alert('ไม่มีการเปลี่ยนแปลงในโครงสร้าง');
            $('#structureModal').hide();
            return;
        }
        
        if (!confirm('ต้องการบันทึกการเปลี่ยนแปลงโครงสร้างหรือไม่?')) {
            return;
        }
        
        // ใช้ changes นี้เป็น currentChanges
        currentChanges = changes;
        
        $('#structureModal').hide();
        
        // เปิดปุ่มบันทึกและแสดงสถานะ
        $('#saveBtn').prop('disabled', false);
        
        var changeText = Object.keys(changes).map(function(nodeId) {
            return getNodeName(nodeId) + ' → ' + (changes[nodeId] ? getNodeName(changes[nodeId]) : 'ระดับสูงสุด');
        }).join(', ');
        
        updateStatus('success', 'กำหนดโครงสร้างใหม่แล้ว: ' + changeText);
        
        // บันทึกทันที
        saveChanges();
  });
});
</script>

<!-- Modal สำหรับจัดการโครงสร้าง -->
<div id="structureModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9999;">
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;border-radius:12px;padding:30px;max-width:600px;width:90%;max-height:80vh;overflow-y:auto;">
        <h3 style="color:#1e3a8a;margin-bottom:20px;text-align:center;">จัดการโครงสร้างผู้บังคับบัญชา</h3>
        <div id="structureList"></div>
        <div style="text-align:center;margin-top:25px;">
            <button id="saveStructureBtn" style="background:#2563eb;color:#fff;border:none;border-radius:8px;padding:12px 24px;margin-right:10px;cursor:pointer;">
                <i class="fas fa-save"></i> บันทึก
            </button>
            <button id="cancelStructureBtn" style="background:#6b7280;color:#fff;border:none;border-radius:8px;padding:12px 24px;cursor:pointer;">
                <i class="fas fa-times"></i> ยกเลิก
            </button>
        </div>
    </div>
</div>

</body>
</html>
