<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['roomId'])) {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_room') {
        // --- อัปเดตข้อมูลห้อง ---
        $roomName   = trim($_POST['roomName'] ?? '');
        $roomDetail = trim($_POST['roomDetail'] ?? '');
        $roomPrice  = trim($_POST['roomPrice'] ?? '');
        $roomCount  = trim($_POST['roomCount'] ?? '');
        $rimgId     = trim($_POST['rimgId'] ?? '');
        if ($roomName === '' || $roomDetail === '' || $roomPrice === '' || $roomCount === '' || $rimgId === '') {
            echo "<script>alert('กรุณากรอกข้อมูลห้องให้ครบ');</script>";
            echo "<script>window.location.href = 'editRoom.php?roomId={$roomId}';</script>";
            exit();
        }
        if (!editRoom($conn, $roomId, $roomName, $roomDetail, $roomPrice, $roomCount)) {
            echo "<script>alert('Error updating room.');</script>";
        }
        if (!setImageShow($conn, $rimgId, $roomId) && $rimgId != -1) {
            echo "<script>alert('Error setting image show.');</script>";
            echo "<script>alert('Error updating image show.');</script>";
        }
        echo "<script>window.location.href = 'editRoom.php?roomId={$roomId}';</script>";
        exit();
    }
}
?>
<form action="editRoom.php?roomId=<?php echo $room[0]['roomId']; ?>" method="POST">
    <input type="hidden" name="action" value="update_room">
    <table class="table table-hover border-top-color table-light table-bordered">
        <thead>
            <colgroup>
                <col style="width:20%;"> <!-- ชื่อห้อง -->
                <col style="width:50%;"> <!-- รายละเอียด -->
                <col style="width:10%;"> <!-- ราคา -->
                <col style="width:10%;"> <!-- จำนวนห้อง -->
                <col style="width:10%;"> <!-- รูปภาพหลัก -->
            </colgroup>
            <tr>
                <th scope="col">ชื่อห้อง</th>
                <th scope="col">รายละเอียด</th>
                <th scope="col">ราคา / คืน</th>
                <th scope="col">จำนวนห้อง</th>
                <th scope="col">ID รูปภาพหลัก</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($room): ?>
                <tr>
                    <td class="align-middle h4">
                        <input type="text" name="roomName" class="form-control" required
                            value="<?php echo htmlspecialchars($room[0]['roomName']); ?>">
                    </td>
                    <td class="align-middle">
                        <textarea name="roomDetail" class="form-control autosize" required
                            rows="1" style="overflow:hidden;resize:none;"><?php echo htmlspecialchars($room[0]['roomDetail']); ?></textarea>
                    </td>
                    <td class="align-middle h4">
                        <input type="number" step="0.01" name="roomPrice" class="form-control" required
                            value="<?php echo htmlspecialchars($room[0]['roomPrice']); ?>">
                    </td>
                    <td class="align-middle h4">
                        <input type="number" step="1" name="roomCount" class="form-control" required
                            value="<?php echo htmlspecialchars($room[0]['roomCount']); ?>">
                    </td>
                    <td class="align-middle">
                        <select name="rimgId" class="form-select" aria-label="Default select example">
                            <?php
                            $roomImages = getRoomImageById($conn, $room[0]['roomId']);
                            if ($roomImages) {
                                foreach ($roomImages as $image) {
                                    $selected = ($image['rimgShow'] == 1) ? 'selected' : '';
                                    echo "<option value=\"" . htmlspecialchars($image['rimgId']) . "\" $selected>ID : " . htmlspecialchars($image['rimgId']) . "</option>";
                                }
                            } else {
                                echo "<option value=-1>No images found</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="text-start">
                        <button class="btn btn-primary" type="submit" onclick="return confirm('แน่ใจหรือไม่ว่าจะบันทึกการเปลี่ยนแปลง?');">บันทึกการเปลี่ยนแปลง</button>
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No rooms found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</form>
<script>
    (function() {
        function autoResize(el) {
            el.style.height = 'auto';
            el.style.height = el.scrollHeight + 'px';
        }
        // init + bind
        document.querySelectorAll('textarea.autosize').forEach(t => {
            autoResize(t);
            t.addEventListener('input', () => autoResize(t));
        });
    })();
</script>