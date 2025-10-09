<?php
// admin_edit_room.php
session_start();
require_once 'Connect_DB.php'; // ต้องมี $conn = new PDO(...);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// สร้าง CSRF token
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

// ====== HANDLE POST (PRG: Post → Redirect → Get) ======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        http_response_code(400); exit('Bad CSRF token');
    }

    $id      = (int)($_POST['room_id'] ?? 0);
    $name    = trim($_POST['room_name'] ?? '');
    $price   = (float)($_POST['price_per_night'] ?? 0);
    $people  = (int)($_POST['room_people'] ?? 1);
    $type    = trim($_POST['room_type'] ?? 'single');
    $detail1 = trim($_POST['room_detail_1'] ?? '');
    $detail2 = trim($_POST['room_detail_2'] ?? '');
    $detail3 = trim($_POST['room_detail_3'] ?? '');
    $detail4 = trim($_POST['room_detail_4'] ?? '');
    $detail5 = trim($_POST['room_detail_5'] ?? '');
    $path    = trim($_POST['path'] ?? '');

    // อัปโหลดรูปใหม่ (ถ้ามี)
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp  = $_FILES['image']['tmp_name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $tmp);
        finfo_close($finfo);

        $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'];
        if (isset($allowed[$mime])) {
            $ext     = $allowed[$mime];
            $dirDisk = __DIR__ . '/images/rooms';
            if (!is_dir($dirDisk)) { @mkdir($dirDisk, 0775, true); }
            $filename     = 'room_' . $id . '_' . date('Ymd_His') . '.' . $ext;
            $targetDisk   = $dirDisk . '/' . $filename;
            $targetWeb    = 'images/rooms/' . $filename;

            if (move_uploaded_file($tmp, $targetDisk)) {
                $path = $targetWeb; // ใช้ path ใหม่ที่อัปโหลดสำเร็จ
            }
        }
        // ถ้า mime ไม่ผ่าน จะข้ามและคง path เดิม/ที่ผู้ใช้กรอก
    }

    // อัปเดตข้อมูลห้อง
    $stmt = $conn->prepare("
        UPDATE rooms
        SET room_name = :name,
            price_per_night = :price,
            room_people = :people,
            room_type = :type,
            room_detail_1 = :d1,
            room_detail_2 = :d2,
            room_detail_3 = :d3,
            room_detail_4 = :d4,
            room_detail_5 = :d5,
            room_img_path = :path
        WHERE room_id = :id
        LIMIT 1
    ");
    $stmt->execute([
        ':name'  => $name,
        ':price' => $price,
        ':people'=> $people,
        ':type'  => $type,
        ':d1'    => $detail1,
        ':d2'    => $detail2,
        ':d3'    => $detail3,
        ':d4'    => $detail4,
        ':d5'    => $detail5,
        ':path'  => $path,
        ':id'    => $id
    ]);

    $_SESSION['flash'] = '✅ อัปเดตข้อมูลห้องเรียบร้อย';
    header('Location: '.$_SERVER['PHP_SELF']); // PRG กันรีเฟรชแล้วโพสต์ซ้ำ
    exit();
}

// ====== GET: ดึงข้อมูลมาแสดง ======
$rooms = $conn->query("SELECT * FROM rooms ORDER BY room_id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>🛠️ แก้ไขข้อมูลห้อง (ภาพเก่า–ใหม่ + แก้ attribute)</title>
<style>
    form{margin-bottom:28px;border:1px solid #ddd;padding:14px;border-radius:8px}
    .row{display:flex;gap:20px;align-items:flex-start;margin-bottom:10px}
    img{border-radius:8px}
</style>
</head>
<body>

<?php if (!empty($_SESSION['flash'])): ?>
    <p style="color:green;"><?= htmlspecialchars($_SESSION['flash']) ?></p>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<h2>🛠️ แก้ไขข้อมูลห้อง</h2>

<?php foreach ($rooms as $r): ?>
<form method="post" enctype="multipart/form-data">
    <h3><?= htmlspecialchars($r['room_name']) ?> (ID: <?= (int)$r['room_id'] ?>)</h3>

    <div class="row">
        <div>
            <div>ภาพเก่า:</div>
            <img src="<?= htmlspecialchars($r['room_img_path']) ?>" width="220" onerror="this.src='images/rooms/placeholder.png'">
        </div>
        <div>
            <div>ภาพใหม่ (Preview):</div>
            <img id="preview<?= (int)$r['room_id'] ?>" width="220" style="display:none">
        </div>
    </div>

    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
    <input type="hidden" name="room_id" value="<?= (int)$r['room_id'] ?>">

    <label>Path รูป:</label>
    <input type="text" name="path" value="<?= htmlspecialchars($r['room_img_path']) ?>" style="width:320px"><br><br>

    <label>อัปโหลดภาพใหม่:</label>
    <input type="file" name="image" accept="image/*" onchange="previewImage(this,'preview<?= (int)$r['room_id'] ?>')"><br><br>

    <label>ชื่อห้อง:</label>
    <input type="text" name="room_name" value="<?= htmlspecialchars($r['room_name']) ?>" style="width:320px" required><br><br>

    <label>ราคา/คืน:</label>
    <input type="number" name="price_per_night" value="<?= htmlspecialchars($r['price_per_night']) ?>" step="0.01" min="0"><br>

    <label>จำนวนคน:</label>
    <input type="number" name="room_people" value="<?= (int)$r['room_people'] ?>" min="1" step="1"><br>

    <label>ประเภทห้อง:</label>
    <select name="room_type">
        <option value="single" <?= ($r['room_type']==='single'?'selected':'') ?>>ห้องเดี่ยว</option>
        <option value="double" <?= ($r['room_type']==='double'?'selected':'') ?>>ห้องคู่</option>
        <option value="group"  <?= ($r['room_type']==='group' ?'selected':'') ?>>ห้องหมู่</option>
        <option value="suite"  <?= ($r['room_type']==='suite' ?'selected':'') ?>>ห้องสวีท</option>
    </select>
    <br><br>

    <label>รายละเอียดเพิ่มเติม:</label><br>
    <?php for ($i=1; $i<=5; $i++): ?>
        <input type="text" name="room_detail_<?= $i ?>" value="<?= htmlspecialchars($r['room_detail_'.$i]) ?>" style="width:320px"><br>
    <?php endfor; ?>

    <br>
    <button type="submit" onclick="this.disabled=true;this.form.submit();">💾 บันทึกการแก้ไข</button>
</form>
<?php endforeach; ?>

<script>
function previewImage(input, id){
    const file = input.files && input.files[0];
    const img  = document.getElementById(id);
    if(!file){ img.style.display='none'; return; }
    const reader = new FileReader();
    reader.onload = e => { img.src = e.target.result; img.style.display='block'; };
    reader.readAsDataURL(file);
}
</script>
</body>
</html>
