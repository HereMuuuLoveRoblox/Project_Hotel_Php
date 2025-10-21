<?php
    session_start();
    include '../configs/basefile.php'; //-- baseUrl, basePath
    include '../configs/Connect_DB.php';

    // เช็คว่า user login แล้วหรือยัง
    if (!isset($_SESSION['userId']) || empty($_SESSION['role'])) {
        header("Location: login.php");
        exit();
    }
    // ---------------------------------------------------------------- //

    include '../functions/HotelTheme.php';
    $hotelTheme = getHotelTheme($conn);

    include '../functions/getUsers.php';
    $user = getUserById($conn, $_SESSION['userId']);

    include '../functions/getRooms.php';
    $rooms = getAllRoomsAndImagesShow($conn);

    include '../functions/getBookings.php';

    function limitCharacters($text, $limit) {
        if (mb_strlen($text, 'UTF-8') > $limit) {
            return mb_substr($text, 0, $limit, 'UTF-8') . '...'; // ตัดข้อความและใส่ ...
        }
        return $text;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <!-- Nav -->
    <?php include '../components/Navbar/Navbar.php';?>
    <?php include '../components/NavCarousel.php';?>
    <?php include '../components/NavServices.php';?>
    <!-- End Nav -->

    <!-- Main Content -->
    <main class="container my-5" id="rooms">
        <div class="text-center w-75 mx-auto">
            <div class="d-flex justify-content-center gap-3">
                <h1 style="font-size: 3rem; font-weight: bold; color: #000000;">ห้องที่ใช่</h1>
                <h1 style="font-size: 3rem; font-weight: bold; color: <?php echo $hotelTheme['colorPrimary']; ?>;">คือห้องที่ชอบ</h1>
            </div>
            <p>เราตั้งใจคัดทุกรายละเอียดให้พอดีกับไลฟ์สไตล์ของคุณ ทั้งแสงสวย ๆ หมอนนุ่มกำลังดี มุมทำงานสงบ และห้องน้ำสะอาดสะอ้าน ให้การพักครั้งนี้รู้สึก “ใช่” ตั้งแต่เปิดประตู</p>
        </div>
        <?php include '../components/RoomCard.php';?>
    </main>
    <!-- End Main Content -->

    <!-- Footer -->
    <?php include '../components/Footer.php';?>
    <!-- End Footer -->
     
    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>