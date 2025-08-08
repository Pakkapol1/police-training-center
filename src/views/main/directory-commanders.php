<?php
// แสดงทำเนียบผู้กำกับการและผู้บังคับการ
?>
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <div class="official-header p-4">
                <img src="/assets/img/stic.webp" alt="โลโก้หน่วยงาน" class="mb-3" style="height: 80px;">
                <h1 class="official-title mb-2">ทำเนียบผู้บังคับบัญชา</h1>
                <h2 class="official-subtitle">ศูนย์ฝึกอบรมตำรวจภูธรภาค 8</h2>
                <div class="official-line"></div>
            </div>
        </div>
    </div>

    <!-- ทำเนียบผู้กำกับการ -->
    <div class="row mb-5">
        
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0 text-center">ทำเนียบผู้กำกับการ</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($supervisors)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="10%">ลำดับ</th>
                                        <th width="15%">ยศ</th>
                                        <th width="35%">ชื่อ-นามสกุล</th>
                                        <th width="40%">ระยะเวลาการดำรงตำแหน่ง</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($supervisors as $supervisor): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($supervisor['order_number']) ?></strong></td>
                                            <td><?= htmlspecialchars($supervisor['rank']) ?></td>
                                            <td><?= htmlspecialchars($supervisor['first_name'] . ' ' . $supervisor['last_name']) ?></td>
                                            <td><?= htmlspecialchars($supervisor['service_period']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> ยังไม่มีข้อมูลทำเนียบผู้กำกับการ
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ทำเนียบผู้บังคับการ -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0 text-center">ทำเนียบผู้บังคับการ</h3>
                </div>
                <div class="card-body">
    <?php if (!empty($commanders)): ?>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                                        <th width="10%">ลำดับ</th>
                                        <th width="15%">ยศ</th>
                                        <th width="35%">ชื่อ-นามสกุล</th>
                                        <th width="40%">ระยะเวลาการดำรงตำแหน่ง</th>
                    </tr>
                </thead>
                <tbody>
                                    <?php foreach ($commanders as $commander): ?>
                        <tr>
                                            <td><strong><?= htmlspecialchars($commander['order_number']) ?></strong></td>
                                            <td><?= htmlspecialchars($commander['rank']) ?></td>
                                            <td><?= htmlspecialchars($commander['first_name'] . ' ' . $commander['last_name']) ?></td>
                                            <td><?= htmlspecialchars($commander['service_period']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> ยังไม่มีข้อมูลทำเนียบผู้บังคับการ
                        </div>
    <?php endif; ?>
</div> 
            </div>
        </div>
    </div>
</div>

<style>
.official-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.official-title {
    font-size: 2.2rem;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.official-subtitle {
    font-size: 1.3rem;
    font-weight: 400;
    opacity: 0.9;
}

.official-line {
    height: 4px;
    background: linear-gradient(90deg, transparent, #fff, transparent);
    margin: 20px auto;
    width: 200px;
    border-radius: 2px;
}

.card {
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border: none;
    padding: 20px;
    font-weight: 600;
}

.table {
    margin-bottom: 0;
}

.table th {
    background-color: #343a40;
    color: white;
    font-weight: 600;
    border: 1px solid #495057;
    padding: 15px 10px;
    vertical-align: middle;
}

.table td {
    padding: 15px 10px;
    vertical-align: middle;
    border: 1px solid #dee2e6;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.alert {
    border-radius: 10px;
    border: none;
    padding: 20px;
    margin: 20px 0;
}

.alert-info {
    background-color: #e3f2fd;
    color: #0277bd;
    border-left: 4px solid #2196f3;
}

@media (max-width: 768px) {
    .official-title {
        font-size: 1.8rem;
    }
    
    .official-subtitle {
        font-size: 1.1rem;
    }
    
    .table {
        font-size: 0.9rem;
    }
    
    .table th,
    .table td {
        padding: 10px 8px;
    }
}
</style> 