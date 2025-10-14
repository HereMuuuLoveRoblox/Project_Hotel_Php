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
    <style>
        
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include '../../components/Navbar/Navbar.php'; ?>
    <!-- End Navbar -->
    <!-- Main Content -->
    <div class="container">
        <h2 class="mt-4 mb-4">จัดการผู้ใช้</h2>
        <table class="table table-hover border-top-color table-light table-bordered" style="table-layout:fixed; width:100%;">
            <colgroup>
                <col style="width:80px;"> <!-- roomId -->
                
            </colgroup>
            <thead>
                <tr>
                    <th>UserId</th>
                    <th>UserName</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allUsers as $u): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['userId']); ?></td>
                        <td><?php echo htmlspecialchars($u['userName']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars($u['role']); ?></td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="userId" value="<?php echo $u['userId']; ?>">
                                <a href="edituser.php?userId=<?php echo $u['userId']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <button type="submit" class="btn btn-danger btn-sm" name="action" value="delete" onclick="return confirm('ยืนยันที่จะลบผู้ใช้ ID: <?php echo $u['userId']; ?>');">Delete</button>
                            </form>
                        </td>
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