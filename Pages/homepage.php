<?php
    session_start();
    // เช็คว่า user login แล้วหรือยัง
    if (!isset($_SESSION['userId']) || empty($_SESSION['role'])) {
        header("Location: login.php");
        exit();
    }
    // ---------------------------------------------------------------- //
    include '../configs/basefile.php'; //-- baseUrl, basePath
    include '../configs/Connect_DB.php';
    include '../functions/HotelTheme.php';
    $hotelTheme = getHotelTheme($conn);

    include '../functions/getUsers.php';
    $user = getUserById($conn, $_SESSION['userId']);

    include '../functions/getRooms.php';
    $rooms = getLIMITRoomsAndImagesShow($conn, 4);
    
    include '../functions/getbookings.php';

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
    <title>HomePage</title>

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
    <main class="container my-5 d-flex flex-column gap-5" >
        <div>
            <div class="text-center w-75 mx-auto mb-5" id="about">
                <div class="d-flex justify-content-center gap-3">
                    <h1 style="font-size: 3rem; font-weight: bold; color: #000000;">ทำไมคุณถึง</h1>
                    <h1 style="font-size: 3rem; font-weight: bold; color: <?php echo $hotelTheme['colorPrimary']; ?>;">ควรพักกับเรา</h1>
                </div>
                <p>เราเชื่อว่าการเดินทางที่ดีเริ่มต้นจากที่พักที่อบอุ่นและสะดวกสบาย เราใส่ใจทุกรายละเอียดเพื่อให้คุณได้พักผ่อนอย่างเต็มที่ ไม่ว่าจะมาคนเดียว มาคู่ หรือมาเป็นกลุ่ม พร้อมบริการที่เป็นกันเองและราคาที่คุ้มค่า</p>
            </div>
            <?php include '../components/AboutUs.php';?>
        </div>

        <div>
            <div class="text-center w-75 mx-auto mb-5" id="rooms">
                <h4>เพราะเราคือ</h4>
                <div class="d-flex justify-content-center gap-3">
                    <h1 style="font-size: 3rem; font-weight: bold; color: #000000;">ทางเลือก</h1>
                    <h1 style="font-size: 3rem; font-weight: bold; color: <?php echo $hotelTheme['colorPrimary']; ?>;">ที่ดีที่สุด</h1>
                </div>
                <p>เราพร้อมมอบประสบการณ์การพักผ่อนที่เหนือกว่า ด้วยห้องพักสะอาด สะดวกสบาย และราคาที่คุ้มค่า พร้อมบริการที่อบอุ่นจากใจ ไม่ว่าคุณจะเดินทางเพื่อพักผ่อนหรือทำงาน เราคือคำตอบของที่พักที่ดีที่สุดสำหรับคุณ</p>
            </div>
            <?php include '../components/RoomCard.php';?>
        </div>
        <a href="rooms.php#rooms" class="text-decoration-none text-center"><span class="<?php echo $line_animation_CSS; ?>" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;">ดูห้องพักทั้งหมด</span></a>
    </main>
    <!-- End Main Content -->

    <!-- Footer -->
    <?php include '../components/Footer.php';?>
    <!-- End Footer -->
     
    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>