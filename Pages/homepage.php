<?php
    session_start();
    if (!isset($_SESSION['userId']) || empty($_SESSION['role'])) {
        header("Location: login.php");
        exit();
    }
    include '../configs/Connect_DB.php';
    include '../configs/hotelTheme.php';
    
    function getUserById($conn, $userId) {
        $stmt = $conn->prepare("SELECT userName, email, role FROM users WHERE userId = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    $user = getUserById($conn, $_SESSION['userId']);

    function getRooms($conn) {
        $stmt = $conn->prepare("SELECT rooms.roomId, rooms.roomName, rooms.roomDetail, rooms.roomPrice, roomsimages.rimgPath, roomsimages.rimgShow
                                FROM rooms
                                JOIN roomsimages ON rooms.roomId = roomsimages.roomId
                                WHERE rimgShow = 1 AND rooms.roomId IN (1, 2, 3, 4)");

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }
    $rooms = getRooms($conn);
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
    <!-- Navbar -->
    <?php include '../components/Navbar.php';?>
    <?php include '../components/NavCarousel.php';?>
    <?php include '../components/NavServices.php';?>
    <!-- End Navbar -->

    <!-- Main Content -->
    <section class="container my-5" id="about">
        <div class="text-center w-75 mx-auto">
            <div class="d-flex justify-content-center gap-3">
                <h1 style="font-size: 3rem; font-weight: bold; color: #000000;">ทำไมคุณถึง</h1>
                <h1 style="font-size: 3rem; font-weight: bold; color: <?php echo $hotel_color_primary; ?>;">ควรพักกับเรา</h1>
            </div>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis voluptatibus vel aspernatur vitae odit quia.Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis voluptatibus vel aspernatur vitae odit quia.</p>
        </div>
        <div class="d-flex justify-content-between">
            <div class="d-flex flex-column gap-4 align-items-center">
                <div class="d-flex flex-column gap-2">
                    <p class="d-flex align-items-center justify-content-center h4" style="width: 40px; height: 40px; background-color: <?php echo $hotel_color_primary; ?>; border-radius: 50%; color: #FFFFFF;">1</p>
                    <div class="d-flex flex-column align-items-start text-start" style="max-width: 500px;">
                        <h4>Lorem ipsum dolor sit amet.</h4>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis voluptatibus vel aspernatur vitae odit quia.</p>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2">
                    <p class="d-flex align-items-center justify-content-center h4" style="width: 40px; height: 40px; background-color: <?php echo $hotel_color_primary; ?>; border-radius: 50%; color: #FFFFFF;">1</p>
                    <div class="d-flex flex-column align-items-start text-start" style="max-width: 500px;">
                        <h4>Lorem ipsum dolor sit amet.</h4>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis voluptatibus vel aspernatur vitae odit quia.</p>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2">
                    <p class="d-flex align-items-center justify-content-center h4" style="width: 40px; height: 40px; background-color: <?php echo $hotel_color_primary; ?>; border-radius: 50%; color: #FFFFFF;">1</p>
                    <div class="d-flex flex-column align-items-start text-start" style="max-width: 500px;">
                        <h4>Lorem ipsum dolor sit amet.</h4>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis voluptatibus vel aspernatur vitae odit quia.</p>
                    </div>
                </div>
            </div>
            <img class="object-fit-cover border rounded" style="width: 45%; height: auto;" src="../images/example/570x416.png" alt="">
        </div>
    </section>

    <main class="container my-5" id="rooms">
        <div class="text-center w-75 mx-auto">
            <h4>เพราะเราคือ</h4>
            <div class="d-flex justify-content-center gap-3">
                <h1 style="font-size: 3rem; font-weight: bold; color: #000000;">ทางเลือก</h1>
                <h1 style="font-size: 3rem; font-weight: bold; color: <?php echo $hotel_color_primary; ?>;">ที่ดีที่สุด</h1>
            </div>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis voluptatibus vel aspernatur vitae odit quia.Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis voluptatibus vel aspernatur vitae odit quia.</p>
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