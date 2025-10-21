<?php
    session_start();
    include '../../configs/Connect_DB.php'; // เชื่อมต่อฐานข้อมูล
    include '../../configs/basefile.php'; //-- baseUrl, basePath

    //-- ถ้าไม่ใช่ admin → กลับไปหน้า login
    if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../login.php");
        exit();
    }

    include '../../functions/hotelTheme.php'; //-- ข้อมูลโรงแรม
    $hotelTheme = getHotelTheme($conn);

    include '../../functions/getUsers.php'; //-- ข้อมูลผู้ใช้
    $user = getUserById($conn, $_SESSION['userId']);
    $allUsers = getAllUsers($conn);

    include '../../functions/CRUDusers.php'; //-- แก้ไขผู้ใช้

    // ---------------------------------------------------------------- //

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $action = $_POST['action'] ?? '';

        if ($action === 'delete' && isset($_POST['userId'])) {
            echo "Delete ID : ".$_POST['userId'];
            $userId = (int)$_POST['userId'];
            DeleteUserById($conn, $userId);
            echo "<script>window.location.href = 'manageUsers.php';</script>";
        }
    }


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <!-- Navbar -->
    <?php include '../../components/Navbar/Navbar.php'; ?>
    <!-- End Navbar -->
    <!-- Main Content -->
    <div class="container">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>จัดการผู้ใช้</h2>
            <a href="dashboard.php" class="btn btn-outline-danger">กลับ</a>
        </div>

        
        <table class="table table-bordered table-hover table-striped rounded shadow-sm table-light table-custom" style="table-layout:fixed; width:100%;">
            <colgroup>
                <col style="width:10%"> <!-- ไอดีผู้ใช้ -->
                <col style="width:25%"> <!-- ชื่อผู้ใช้ -->
                <col style="width:25%"> <!-- อีเมล -->
                <col style="width:10%"> <!-- บทบาท -->
                <col style="width:10%"> <!-- จัดการ -->
                <col style="width:10%"> <!-- จัดการ -->
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center">ไอดีผู้ใช้</th>
                    <th>ชื่อผู้ใช้</th>
                    <th>อีเมล / Email</th>
                    <th class="text-center">บทบาท / Role</th>
                    <th scope="col" colspan="2" class="text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allUsers as $u): ?>
                    <tr <?php if ($u['role'] === 'admin') echo 'class="table-warning"'; ?>>
                        <td class="text-center"><?php echo htmlspecialchars($u['userId']); ?></td>
                        <td><?php echo htmlspecialchars($u['userName']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($u['role']); ?></td>
                        <form action="" method="post">
                            <td class="text-center">
                                <input type="hidden" name="userId" value="<?php echo $u['userId']; ?>">
                                <a href="edituser.php?userId=<?php echo $u['userId']; ?>" class="btn btn-warning">แก้ไข</a>
                            </td>
                            <td class="text-center">
                                <button type="submit" class="btn btn-danger" name="action" value="delete" onclick="return confirm('ยืนยันที่จะลบผู้ใช้ ID: <?php echo $u['userId']; ?>');">ลบ</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
                
            </tbody>
        </table>

    </div>
    <!-- End Main Content-->

    <!-- Footer -->
    <?php include '../../components/Footer.php'; ?>
    <!-- End Footer -->

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>