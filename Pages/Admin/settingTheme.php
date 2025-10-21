<?php
    session_start();
    include '../../configs/Connect_DB.php'; // เชื่อมต่อฐานข้อมูล
    include '../../configs/basefile.php'; //-- baseUrl, basePath

    //-- ถ้าไม่ใช่ admin → กลับไปหน้า login
    if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../login.php");
        exit();
    }
    // ---------------------------------------------------------------- //

    include '../../functions/hotelTheme.php'; //-- ข้อมูลโรงแรม
    $hotelTheme = getHotelTheme($conn);

    include '../../functions/getUsers.php'; //-- ข้อมูลผู้ใช้
    $user = getUserById($conn, $_SESSION['userId']);

    include '../../functions/CRUDhotelTheme.php'; //-- ฟังก์ชันจัดการธีมโรงแรม
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['hotelNameInput']) && isset($_POST['colorPrimaryInput']) && isset($_POST['colorSecondaryInput'])) {
        
        $action = $_POST['action'] ?? '';
        if ($action == 'update') {
            $hotelName = $_POST['hotelNameInput'];
            $colorPrimary = $_POST['colorPrimaryInput'];
            $colorSecondary = $_POST['colorSecondaryInput'];
            $themeId = $hotelTheme['themeId'] ?? 1;
            updateTheme($conn, $themeId, $hotelName, $colorPrimary, $colorSecondary);
            header("Location: settingTheme.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setting Theme</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <!-- Navbar -->
    <?php include '../../components/Navbar/Navbar.php'; ?>
    <!-- End Navbar -->

    <!-- Main Content -->
    <div class="container" id="">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>ตั้งค่าธีมโรงแรม</h2>
            <a href="dashboard.php" class="btn btn-outline-danger">ยกเลิก</a>
        </div>
        <table class="table table-bordered table-hover table-striped rounded shadow-sm table-light" style="table-layout:fixed; width:100%;">
            <colgroup>
                <col style="width:50%;"> <!-- ชื่อโรงแรม -->
                <col style="width:25%;"> <!-- สีหลัก -->
                <col style="width:25%;"> <!-- สีรอง -->

            </colgroup>
            <thead>
                <tr>
                    <th scope="col" class="text-center">ชื่อโรงแรม</th>
                    <th scope="col" class="text-center">สีหลัก</th>
                    <th scope="col" class="text-center">สีรอง</th>
                </tr>
            </thead>
            <tbody>
                <form method="POST" action="">
                    <?php if ($hotelTheme): ?>
                        <tr>
                            <td class="text-center align-middle">
                                <input type="text" class="form-control" 
                                name="hotelNameInput"
                                id="hotelNameInput" 
                                value="<?php echo htmlspecialchars($hotelTheme['hotelName'] ?? ''); ?>" placeholder="Hotel Name">
                            </td>
                            <td class="text-center align-middle">
                                <input type="color" class="form-control form-control-color w-100"
                                    name="colorPrimaryInput"
                                    id="colorPrimaryInput" 
                                    value="<?php echo htmlspecialchars($hotelTheme['colorPrimary'] ?? '#b3b3b3'); ?>"
                                    title="เลือกสีหลักของธีม">
                            </td>
                            <td class="text-center align-middle">
                                <input type="color" class="form-control form-control-color w-100"
                                    name="colorSecondaryInput"
                                    id="colorSecondaryInput"
                                    value="<?php echo htmlspecialchars($hotelTheme['colorSecondary'] ?? '#b3b3b3'); ?>"
                                    title="เลือกสีรองของธีม">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-start">
                                <button type="submit" class="btn btn-primary" name="action" value="update" onclick="return confirm('ยืนยันที่จะบันทึกธีมโรงแรม?');">บันทึกการเปลี่ยนแปลง</button>
                            </td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No theme data available.</td>
                        </tr>
                    <?php endif; ?>
                </form>
            </tbody>
        </table>
    </div>
    <!-- Footer -->
    <?php include '../../components/Footer.php'; ?>
    <!-- End Footer -->

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>