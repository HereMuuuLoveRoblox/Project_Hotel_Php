<?php
// admin_homepage_images.php
include 'Connect_DB.php';

// ===== ตั้งค่าทั่วไป =====
$allowedMime = ['image/png', 'image/jpeg', 'image/webp', 'image/gif'];
$maxBytes    = 5 * 1024 * 1024; // 5 MB
$publicRoot  = realpath(__DIR__); // โฟลเดอร์รากของเว็บ (ที่มีโฟลเดอร์ images/)

// ===== อัปเดตรูป (เขียนทับ) =====
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['image_id'])) {
    $id = (int)$_POST['image_id'];

    // ดึง URL เดิมจาก DB (เพื่อคงชื่อไฟล์/URL เดิม)
    $stmt = $conn->prepare("SELECT homepage_image_name, homepage_image_url FROM homepage_images WHERE homepage_image_id = :id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();

    if (!$row) {
        $msg = "ไม่พบรายการรูปภาพ ID: {$id}";
    } elseif (!isset($_FILES['new_image']) || $_FILES['new_image']['error'] !== UPLOAD_ERR_OK) {
        $msg = "กรุณาเลือกรูปภาพ (หรือไฟล์ใหญ่เกิน/อัปโหลดล้มเหลว)";
    } else {
        $fileTmp  = $_FILES['new_image']['tmp_name'];
        $fileSize = $_FILES['new_image']['size'];
        $mime     = mime_content_type($fileTmp);

        // ตรวจสอบชนิดไฟล์/ขนาด
        if (!in_array($mime, $allowedMime, true)) {
            $msg = "ชนิดไฟล์ไม่รองรับ (อนุญาต: png, jpg/jpeg, webp, gif)";
        } elseif ($fileSize > $maxBytes) {
            $msg = "ไฟล์ใหญ่เกินกำหนด (สูงสุด 5 MB)";
        } else {
            // path ปลายทางตาม URL เดิม (เช่น images/hero.png)
            // ป้องกัน path traversal
            $relativeUrl = ltrim($row['homepage_image_url'], '/\\');
            $targetPath  = $publicRoot . DIRECTORY_SEPARATOR . $relativeUrl;

            // สร้างโฟลเดอร์หากยังไม่มี
            $dir = dirname($targetPath);
            if (!is_dir($dir)) {
                @mkdir($dir, 0775, true);
            }

            // ทำสำเนาชั่วคราว แล้วค่อย rename ทับ (atomic-ish)
            $tmpTarget = $targetPath . '.uploading_' . uniqid();
            if (!move_uploaded_file($fileTmp, $tmpTarget)) {
                $msg = "ย้ายไฟล์อัปโหลดไม่สำเร็จ";
            } else {
                // สำรองไฟล์เก่า (ออปชัน)
                // $backup = $targetPath . '.bak_' . date('Ymd_His');
                // if (file_exists($targetPath)) { @copy($targetPath, $backup); }

                // เขียนทับไฟล์เดิม
                if (file_exists($targetPath)) {
                    @unlink($targetPath);
                }
                if (!@rename($tmpTarget, $targetPath)) {
                    @unlink($tmpTarget);
                    $msg = "เขียนทับไฟล์เดิมไม่สำเร็จ";
                } else {
                    $msg = "อัปเดตรูป <b>" . htmlspecialchars($row['homepage_image_name']) . "</b> สำเร็จ (URL เดิม: " . htmlspecialchars($row['homepage_image_url']) . ")";
                }
            }
        }
    }
}

// ===== ดึงรายการรูปทั้งหมดเพื่อแสดงในตาราง =====
$rows = $conn->query("SELECT homepage_image_id, homepage_image_name, homepage_image_url FROM homepage_images ORDER BY homepage_image_id ASC")->fetchAll();
?>
<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin — Homepage Images</title>
    <style>
        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, sans-serif;
            background: #f7f7f8;
            margin: 0;
            padding: 24px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, .06);
        }

        h1 {
            margin: 0 0 16px;
        }

        .msg {
            margin: 0 0 16px;
            padding: 12px 14px;
            border-radius: 10px;
            background: #f2f7ff;
            color: #0b63ce;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 10px 8px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .table th {
            text-align: left;
            color: #666;
            font-weight: 600;
        }

        .thumb {
            width: 180px;
            height: 90px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #eee;
            background: #fafafa;
        }

        .url {
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace;
            font-size: 13px;
            color: #333;
        }

        .form-upload {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        input[type="file"] {
            font-size: 13px;
        }

        .btn {
            appearance: none;
            background: #111827;
            color: #fff;
            border: 0;
            padding: 8px 14px;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn:hover {
            opacity: .9
        }

        .note {
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>จัดการรูปหน้า Homepage (เขียนทับไฟล์เดิม)</h1>
        <?php if ($msg): ?><div class="msg"><?= $msg ?></div><?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th style="width:64px;">ID</th>
                    <th>ชื่อรูป</th>
                    <th>พรีวิว</th>
                    <th>URL (ไม่เปลี่ยน)</th>
                    <th style="width:320px;">อัปโหลดรูปใหม่</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?= (int)$r['homepage_image_id'] ?></td>
                        <td><?= htmlspecialchars($r['homepage_image_name']) ?></td>
                        <td>
                            <img class="thumb" src="<?= htmlspecialchars($r['homepage_image_url']) ?>?t=<?= time() ?>" alt="">
                        </td>
                        <td class="url"><?= htmlspecialchars($r['homepage_image_url']) ?></td>
                        <td>
                            <form class="form-upload" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="image_id" value="<?= (int)$r['homepage_image_id'] ?>">
                                <input type="file" name="new_image" accept=".png,.jpg,.jpeg,.webp,.gif" required>
                                <button class="btn" type="submit">อัปเดต</button>
                            </form>
                            <div class="note">* ระบบจะเขียนทับไฟล์เดิมที่ URL นี้ โดยไม่เปลี่ยนชื่อและไม่แก้ไขฐานข้อมูล</div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>