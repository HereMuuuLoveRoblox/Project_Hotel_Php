<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'update_services') {
        $services = $_POST['serviceName'] ?? [];
        UpdateService($conn, $roomId, $services);
        echo "<script>window.location.href = 'editRoom.php?roomId={$roomId}';</script>";
        exit();
    }
    if ($action === 'delete_service') {
        $serviceId = isset($_POST['serviceId']) ? (int)$_POST['serviceId'] : 0;
        if ($serviceId > 0) {
            DeleteService($conn, $serviceId, $roomId);
        } else {
            echo "<script>alert('ไม่พบ serviceId สำหรับลบ');</script>";
        }
        echo "<script>window.location.href = 'editRoom.php?roomId={$roomId}';</script>";
        exit();
    }
}
?>
<form id="servicesForm" action="editRoom.php?roomId=<?php echo (int)$room[0]['roomId']; ?>" method="POST">
    <input type="hidden" name="action" value="update_services">
    <table class="table table-hover border-top-color table-light table-bordered">
        <thead>
            <colgroup>
                <col style="width:90%;"> <!-- ชื่อห้อง -->
                <col style="width:10%;"> <!-- รายละเอียด -->

            </colgroup>
            <tr>
                <th>ชื่อบริการ</th>
                <th class="text-center">จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($roomServices)): ?>
                <?php foreach ($roomServices as $service): ?>
                    <tr>
                        <td class="align-middle h5">
                            <input type="text" name="serviceName[]" class="form-control"
                                value="<?php echo htmlspecialchars($service['serviceName']); ?>">
                        </td>
                        <td class="align-middle text-center">
                            <!-- ปุ่มลบเรียก JS ใส่ hidden แล้ว submit ฟอร์มหลัก -->
                            <button type="button" class="btn btn-danger btn"
                                onclick="submitDelete(<?php echo (int)$service['serviceId']; ?>, '<?php echo addslashes($service['serviceName']); ?>')">
                                ลบ
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" class="text-center">
                        <em>ยังไม่มีบริการ</em>
                        <div class="mt-2">
                            <input type="text" name="serviceName[]" class="form-control" placeholder="ตัวอย่าง: ฟรี Wi-Fi">
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
            <tr>
                <td colspan="2" class="d-flex gap-3 justify-content-start">
                    <button type="button" class="btn btn-success" onclick="addServiceRow(this)">+ เพิ่มบริการ</button>
                    <button class="btn btn-primary" type="submit" onclick="return confirm('แน่ใจหรือไม่ว่าจะบันทึกบริการนี้?');">บันทึกบริการ</button>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- ปุ่มบันทึกต้องส่ง action=update_services -->
</form>

<script>
    function addServiceRow(btn) {
        const tbody = btn.closest('tbody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
    <td class="align-middle h5">
      <div class="input-group">
        <input type="text" name="serviceName[]" class="form-control" placeholder="ตัวอย่าง: อาหารเช้า">
        <button type="button" class="btn btn-outline-secondary" onclick="this.closest('tr').remove()">ลบ</button>
      </div>
    </td>
    <td></td>`;
        tbody.insertBefore(tr, tbody.lastElementChild);
    }

    function submitDelete(serviceId, serviceName = '') {
        if (!confirm('ยืนยันลบบริการนี้? : ' + serviceName)) return;

        const form = document.getElementById('servicesForm');

        // เปลี่ยน action เป็น delete_service
        // (ลบ hidden เดิมก่อน กันซ้ำ)
        [...form.querySelectorAll('input[name="action"]')].forEach(el => el.remove());
        let act = document.createElement('input');
        act.type = 'hidden';
        act.name = 'action';
        act.value = 'delete_service';
        form.appendChild(act);

        // ใส่ serviceId ของแถวที่ต้องการลบ
        // (ลบของเดิมก่อน กันซ้ำ)
        [...form.querySelectorAll('input[name="serviceId"]')].forEach(el => el.remove());
        let sid = document.createElement('input');
        sid.type = 'hidden';
        sid.name = 'serviceId';
        sid.value = String(serviceId);
        form.appendChild(sid);
        form.submit();
    }
</script>