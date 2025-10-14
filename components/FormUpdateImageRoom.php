<?php

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $roomId > 0) {
    $action = $_POST['action'] ?? '';

    if ($action === 'upload_image') {
        if (!isset($_FILES['new_image']) || $_FILES['new_image']['error'] !== UPLOAD_ERR_OK) {
            echo "<script>alert('กรุณาเลือกรูปภาพ (หรือไฟล์ใหญ่เกิน/อัปโหลดล้มเหลว)');</script>";
            echo "<script>window.location.href = 'editRoom.php?roomId={$roomId}';</script>";
            exit();
        }

        $image_id       = (int)$_POST['image_id'];
        $new_image_tmp  = $_FILES['new_image']['tmp_name'];
        $new_image_name = $_FILES['new_image']['name'];
        $ext            = strtolower(pathinfo($new_image_name, PATHINFO_EXTENSION));
        $new_name       = date('Ymd_His') . '_' . uniqid() . '.' . $ext;

        $upload_dir = "../../images/rooms/";
        
        // สร้างโฟลเดอร์ถ้ายังไม่มี
        if (!is_dir($upload_dir)) { 
            mkdir($upload_dir, 0775, true); 
        }

        $oldName = getPathByImgIdAndRoomId($conn, $image_id, $roomId);
        echo $oldName;

        // อัปโหลดไฟล์ใหม่
        if (!move_uploaded_file($new_image_tmp, $upload_dir . $new_name)) {
            echo "<script>alert('ย้ายไฟล์ไม่สำเร็จ');</script>";
            echo "<script>window.location.href = 'editRoom.php?roomId={$roomId}';</script>";
            exit();
        }
        UpdateImageRoom($conn, $new_name, $image_id); // อัปเดต DB ให้ชี้ไฟล์ใหม่
        deleteImageFile($oldName);// ลบไฟล์เก่า (หลังอัปเดตสำเร็จ)
        echo "<script>window.location.href = 'editRoom.php?roomId={$roomId}';</script>";
        exit;
    }


    // ✅ ใหม่: เพิ่มรูปใหม่เข้า DB (หลายไฟล์ได้)
    if ($action === 'add_images') {
        AddNewImageRoom($conn, $roomId, "Example.png");
        echo "<script>window.location.href = 'editRoom.php?roomId={$roomId}&scroll=addimg';</script>";
        exit;
    }

    if ($action === 'delete_image') {
        $image_id = (int)$_POST['image_id'];

        // ดึงชื่อไฟล์ก่อน
        $q = $conn->prepare("SELECT rimgPath FROM roomsimages WHERE rimgId = :id AND roomId = :rid");
        $q->execute([':id' => $image_id, ':rid' => $roomId]);
        if ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            deleteImageFile($row['rimgPath']);  // ลบไฟล์จริง
        }

        // ลบข้อมูลใน DB
        $stmt = $conn->prepare("DELETE FROM roomsimages WHERE rimgId = :rimgId AND roomId = :roomId");
        $stmt->execute([':rimgId' => $image_id, ':roomId' => $roomId]);

        echo "<script>alert('ลบรูปภาพเรียบร้อย');</script>";
        echo "<script>window.location.href = 'editRoom.php?roomId={$roomId}';</script>";
        exit;
    }
}
?>


<table class="table table-hover table-light table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width:10%">rimgId</th>
            <th class="text-center" style="width:40%">img</th>
            <th class="text-center" style="width:50%">upload</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($roomImages): ?>
            <?php foreach ($roomImages as $img): $rimgId = (int)$img['rimgId']; ?>

                <tr class="<?php echo $img['rimgShow'] ? 'table-warning' : ''; ?>">

                    <td class="align-middle text-center"><?php echo $rimgId; ?></td>
                    <td class="align-middle text-center">
                        <img
                            src="<?php echo getBaseFile(); ?>/images/rooms/<?php echo htmlspecialchars($img['rimgPath']); ?>"
                            alt="Room Image"
                            class="img-thumbnail"
                            style="max-width:200px; max-height:200px; object-fit:cover;">
                    </td>
                    <td class="align-middle">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="image_id" value="<?php echo $rimgId; ?>">

                            <div class="input-group d-flex gap-2">
                                <input type="file" name="new_image" accept="image/*" class="form-control js-file" required>
                                <button type="submit" name="action" value="upload_image" class="btn btn-primary js-upload"
                                onclick="return confirm('แน่ใจหรือไม่ว่าจะอัปโหลดรูปนี้?');">Upload</button>
                                <button type="submit" name="action" value="delete_image" class="btn btn-danger" 
                                    onclick="return confirm('แน่ใจหรือไม่ว่าจะลบรูปนี้?');" formnovalidate >Delete</button>
                            </div>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" class="text-center">No images found.</td>
            </tr>
        <?php endif; ?>

        <!-- ✅ แถวสุดท้าย: เพิ่มรูปใหม่เข้า DB (หลายไฟล์ได้) -->
        <tr>
            <td colspan="3">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add_images">
                    <div class="input-group" id="add-image-group">
                        <button type="submit" class="btn btn-success">Add new image(s)</button>
                    </div>
                    <div class="form-text">เลือกได้หลายไฟล์ จะถูกเพิ่มเป็นรูปใหม่ของห้องนี้</div>
                </form>
            </td>
        </tr>
    </tbody>

</table>

<script>
    document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    if (params.get("scroll") === "addimg") {
        const target = document.getElementById("add-image-group");
        if (target) {
        target.scrollIntoView({ behavior: "smooth", block: "center" });
        // ไฮไลท์เบา ๆ เพื่อให้รู้ว่ามาถึงแล้ว
        target.style.transition = "background 1s";
        target.style.background = "#fff3cd";
        setTimeout(() => target.style.background = "", 1500);
        }
    }
    document.querySelectorAll('form').forEach(form => {
        const file = form.querySelector('input.js-file[name="new_image"]');
        const btn  = form.querySelector('button.js-upload[name="action"][value="upload_image"]');
        if (!file || !btn) return;

        const update = () => {
            if (file.files.length === 0) {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-secondary');
                btn.disabled = true;
            } else {
                btn.classList.remove('btn-secondary');
                btn.classList.add('btn-primary');
                btn.disabled = false;
            }
        };

        file.addEventListener('change', update);
        update(); // ตั้งค่าสีให้ถูกตั้งแต่โหลดหน้า
    });
});
</script>
