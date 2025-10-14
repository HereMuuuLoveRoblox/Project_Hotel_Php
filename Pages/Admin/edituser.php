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

    $userId = (int)($_GET['userId'] ?? 0);
    if ($userId <= 0) {
        echo "<script>window.location.href = 'manageUsers.php';</script>";
        exit();
    }
    $edituser = getUserById($conn, $userId);
    if (!$edituser) {
        echo "<script>window.location.href = 'manageUsers.php';</script>";
        exit();
    }

    include '../../functions/CRUDusers.php'; //-- แก้ไขผู้ใช้

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $action = $_POST['action'] ?? '';

        $edituserId = (int)$_POST['edituserId'];
        $edituserName = $_POST['edituserName'] ?? '';
        $editemail = $_POST['editemail'] ?? '';
        $editrole = $_POST['editrole'] ?? '';


        if ($action === 'save' && isset($_POST['edituserId']) && isset($_POST['edituserName']) && isset($_POST['editemail']) && isset($_POST['editrole'])) {
            UpdateUser($conn, $edituserId, $edituserName, $editemail, $editrole);
            echo "<script>window.location.href = 'manageUsers.php';</script>";
        }
    }

// ---------------------------------------------------------------- //
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
        <h2 class="mt-4 mb-4">จัดการผู้ใช้ ID : <?php echo htmlspecialchars($edituser['userId']); ?> <?php echo htmlspecialchars($edituser['userName']); ?></h2>
        <table class="table table-hover border-top-color table-light table-bordered" style="table-layout:fixed; width:100%;">
            <thead>
                <tr>
                    <th>UserName</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($edituser): ?>
                    <tr>
                        <form action="" method="post" class="">
                            <td>
                                <input type="text" name="edituserName" value="<?php echo htmlspecialchars($edituser['userName']); ?>" class="form-control">
                            </td>
                            <td>
                                <input type="email" name="editemail" value="<?php echo htmlspecialchars($edituser['email']); ?>" class="form-control">
                            </td>
                            <td>
                                <select name="editrole" class="form-select">
                                    <option value="admin" <?php if ($edituser['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                                    <option value="customer" <?php if ($edituser['role'] === 'customer') echo 'selected'; ?>>Customer</option>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="edituserId" value="<?php echo htmlspecialchars($edituser['userId']); ?>">
                                <button type="submit" class="btn btn-primary btm-sm" name="action" value="save"
                                    onclick="return confirm('ยืนยันที่จะบันทึกการเปลี่ยนแปลงผู้ใช้ ID: <?php echo $edituser['userId']; ?>?');">
                                    Save
                                </button>
                                
                            </td>
                        </form>
                    </tr>
                    <tr></tr>
                <?php endif; ?>
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