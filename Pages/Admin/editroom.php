<?php
    session_start();

    include '../../configs/Connect_DB.php'; // เชื่อมต่อฐานข้อมูล
    include '../../configs/basefile.php'; //-- baseUrl, basePath

    //-- ถ้าไม่ใช่ admin → กลับไปหน้า login
    if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../login.php");
        exit();
    }
    $roomId = $_GET['roomId'] ?? null;
    if (!$roomId) {
        header("Location: manageRooms.php");
        exit();
    }

    // ---------------------------------------------------------------- //
    
    include '../../functions/hotelTheme.php'; //-- ข้อมูลโรงแรม
    $hotelTheme = getHotelTheme($conn);

    //-- include Function
    include '../../functions/getRooms.php'; //-- ข้อมูลห้องพัก
    $room = getRoomById($conn, $roomId);
    $roomImages = getRoomImageById($conn, $roomId);
    $roomServices = getRoomServicesById($conn, $roomId);

    include '../../functions/getUsers.php'; //-- ข้อมูลผู้ใช้
    $user = getUserById($conn, $_SESSION['userId']);


    // functions
    include '../../functions/CRUDrooms.php'; //-- แก้ไขห้องพัก


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms</title>

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
    <div class="container" id="manage-rooms">
        <?php include '../../components/FormUpdateRoom.php'; ?>
        <hr>
        <?php include '../../components/FormUpdateService.php'; ?>
        <hr>
        <?php include '../../components/FormUpdateImageRoom.php'; ?>
    </div>

    </div>

    <!-- Footer -->
    <?php include '../../components/Footer.php'; ?>
    <!-- End Footer -->

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>