<?php
    session_start();
    include '../configs/Connect_DB.php';
    include '../configs/hotelTheme.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $checkin = $_POST['checkin'];
        $checkout = $_POST['checkout'];

        $date1 = new DateTime($checkin);
        $date2 = new DateTime($checkout);
        $nights = $date1->diff($date2)->days;
    }

    function getUserById($conn, $userId) {
        $stmt = $conn->prepare("SELECT userName, email, role FROM users WHERE userId = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    $user = getUserById($conn, $_SESSION['userId']);

    function getRoomById($conn, $roomId) {
        $stmt = $conn->prepare("SELECT rooms.roomId, rooms.roomName, rooms.roomDetail, rooms.roomPrice, roomsimages.rimgPath, roomsimages.rimgShow
                                FROM rooms
                                JOIN roomsimages ON rooms.roomId = roomsimages.roomId
                                WHERE rooms.roomId = :roomId AND roomsimages.rimgShow = 1");

        $stmt->bindParam(':roomId', $roomId);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    function getRoomImageById($conn, $roomId) {
        $stmt = $conn->prepare("SELECT roomsimages.rimgPath
                                FROM roomsimages
                                WHERE roomsimages.roomId = :roomId");
        $stmt->bindParam(':roomId', $roomId);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    function getRoomServicesById($conn, $roomId) {
        $stmt = $conn->prepare("SELECT roomservices.serviceName 
                                FROM roomservices 
                                WHERE roomservices.roomId = :roomId");
        $stmt->bindParam(':roomId', $roomId);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    if (isset($_GET['roomId'])) {
        $roomId = $_GET['roomId'];
        $room = getRoomById($conn, $roomId);
        $roomImages = getRoomImageById($conn, $roomId);
        $roomServices = getRoomServicesById($conn, $roomId);
    }
    else {
        // หากไม่มี roomId ใน URL ให้รีไดเรกต์กลับไปที่หน้าห้องพัก
        header("Location: rooms.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - <?php echo $room[0]['roomName'] ?? ''; ?></title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include '../components/Navbar.php'; ?>
    <?php include '../components/NavCarousel.php'; ?>
    <section id="booking"></section>
    <?php include '../components/NavServices.php'; ?>
    <!-- End Navbar -->
    <!-- Main Content -->
    <div class="container my-5 d-flex justify-content-between">
        <?php include '../components/RoomCarousel.php'; ?>
        <div class="w-100 px-5">
            <h1 class="d-flex justify-content-center align-items-center" style="font-size: 3rem; font-weight: bold; color: <?php echo $hotel_color_primary; ?>;"><?php echo $room[0]['roomName'] ?? ''; ?></h1>
            <p class="text-center"><?php echo $room[0]['roomDetail'] ?? ''; ?></p>
            <div class="container border rounded p-3 my-5">
                <h3 class="d-flex justify-content-center align-items-center mt-4" style="font-size: 1.5rem; font-weight: bold; color: gray;">สิ่งอำนวยความสะดวก</h3>
                <ul class="row list-unstyled text-center">
                    <?php if ($roomServices): ?>
                        <?php foreach ($roomServices as $service): ?>
                            <li class="col-12 col-md-4 d-flex justify-content-center align-items-center gap-2 mb-3">
                                <span><?php echo $service['serviceName']; ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
                <h3 class="d-flex justify-content-center align-items-center mt-4" style="font-size: 1.5rem; font-weight: bold; color: green;">$<?php echo number_format((float)$room[0]['roomPrice']); ?> / คืน</h3>
                <hr>
                <h3>ข้อมูลผู้เข้าพัก</h3>
                <p><strong>ชื่อผู้ใช้:</strong> <?php echo $user['userName'] ?? ''; ?></p>
                <p><strong>อีเมล:</strong> <?php echo $user['email'] ?? ''; ?></p>
                <hr>
                <form method="post" action="">
                    <div class="mb-3">
                        <label>วันที่เริ่มเข้าพัก (Check-in)</label>
                        <input type="date" name="checkin" class="form-control"
                            value="<?php echo $_POST['checkin'] ?? ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>วันสุดท้าย (Check-out)</label>
                        <input type="date" name="checkout" class="form-control"
                            value="<?php echo $_POST['checkout'] ?? ''; ?>" required>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="width: 100%; background-color: <?php echo $hotel_color_primary; ?>; color: white;">จองห้องพัก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Main Content -->

    <!-- Modal Booking / Payment -->
    <?php include '../components/ModalBooking.php'; ?>
    <?php include '../components/ModalPayment.php'; ?>
    <!-- End Modal -->
     
    <!-- Footer -->
    <?php include '../components/Footer.php'; ?>
    <!-- End Footer -->
     
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const confirmBtn = document.getElementById('confirmPaymentBtn');
        confirmBtn.addEventListener('click', function() {
            // ปิดโมดัลหลัก
            const bookingModal = bootstrap.Modal.getInstance(document.getElementById('staticBackdrop'));
            bookingModal.hide();

            // หลังปิด รอแป๊บแล้วเปิด success modal
            setTimeout(() => {
            const successModal = new bootstrap.Modal(document.getElementById('paymentSuccessModal'));
            successModal.show();
            }, 500);
        });
        });
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                var modal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
                modal.show();
            <?php endif; ?>
        });
    </script>



    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>