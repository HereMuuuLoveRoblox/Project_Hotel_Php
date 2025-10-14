<?php
    session_start();
    include '../configs/basefile.php'; //-- baseUrl, basePath

    // เช็คว่า user login แล้วหรือยัง
    if (!isset($_SESSION['userId']) || empty($_SESSION['role'])) {
        header("Location: login.php");
        exit();
    }
    // ---------------------------------------------------------------- //

    include '../configs/Connect_DB.php';
    
    include '../functions/HotelTheme.php';
    $hotelTheme = getHotelTheme($conn);

    include '../functions/getUsers.php';
    $user = getUserById($conn, $_SESSION['userId']);

    include '../functions/getRooms.php';
    $rooms = getLIMITRoomsAndImagesShow($conn, 4);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomePage</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        /* Custom styles for the homepage */
        .room-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .room-card:hover {
            transform: scale(1.01);
            z-index: 10; /* ป้องกันบังกันเวลาขยาย */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

    </style>
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
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis voluptatibus vel aspernatur vitae odit quia.Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis voluptatibus vel aspernatur vitae odit quia.</p>
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
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis voluptatibus vel aspernatur vitae odit quia.Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis voluptatibus vel aspernatur vitae odit quia.</p>
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