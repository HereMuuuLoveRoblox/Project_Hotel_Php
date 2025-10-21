<?php
    session_start();
    include '../configs/Connect_DB.php';
    include '../configs/basefile.php'; //-- baseUrl, basePath

    include '../functions/HotelTheme.php';
    $hotelTheme = getHotelTheme($conn);

    include '../functions/getUsers.php';
    include '../functions/validateAccount.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav  class="d-flex flex-column justify-content-center container py-3 align-items-center border my-3" style="border-radius: 10px;">
        <div class="nav-left">
            <p class="h2 text-decoration-none" style="white-space: nowrap; display: inline-block; color: <?php echo $hotelTheme['colorPrimary']; ?>;"><?php echo $hotelTheme['hotelName']; ?></p>
        </div>
        <?php include '../components/NavServices.php'; ?>
    </nav>
    <!-- End Navbar -->
    <div class="container border p-4 my-5" style="border-radius: 10px;" id="login">
        <h2 class="text-center mb-4" 
            style="font-size: 2rem; font-weight: bold; color: <?php echo $hotelTheme['colorPrimary']; ?>;">
            เข้าสู่ระบบ
        </h2>
        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userName = $_POST['userName'];
                $password = $_POST['password'];

                $result = userLogin($conn, $userName, $password);
                if ($result === true) {
                    echo '<div class="alert alert-success text-center" role="alert">เข้าสู่ระบบสำเร็จ! กำลังเปลี่ยนเส้นทาง...</div>';
                    $user = getUserIdByUserName($conn, $userName);
                    $_SESSION['userId'] = $user['userId'];
                    $_SESSION['role'] = $user['role'];
                    if ($user['role'] === 'admin') {
                        header("Refresh: 1; url=Admin/manageUsers.php");
                    } else {
                        header("Refresh: 1; url=homepage.php");
                    }
                } elseif (is_array($result)) {
                    foreach ($result as $error) {
                        echo '<div class="alert alert-danger text-center" role="alert">' . htmlspecialchars($error) . '</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger text-center" role="alert">เกิดข้อผิดพลาดที่ไม่คาดคิด กรุณาลองอีกครั้งในภายหลัง</div>';
                }
            }
        ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="userName" class="form-label">Username</label>
                <input type="text" class="form-control" id="userName" name="userName" required
                value="<?php echo htmlspecialchars($_POST['userName'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <p>ยังไม่มีบัญชีผู้ใช้? <a href="register.php" class="text-decoration-none fw-semibold" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;">สมัครสมาชิก</a></p>
            
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-5 py-2 w-100"
                    style="background-color: <?php echo $hotelTheme['colorPrimary']; ?>; border: none; border-radius: 8px;">
                    เข้าสู่ระบบ
                </button>
            </div>
        </form>
    </div>

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>