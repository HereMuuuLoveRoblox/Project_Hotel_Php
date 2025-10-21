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

    include '../../functions/getRooms.php';
    $rooms = getAllRoomsAndImagesShow($conn);

    include '../../functions/getBookings.php'; //-- CRUD Booking
    include '../../functions/CRUDbooking.php'; //-- CRUD Booking

    // ---------------------------------------------------------------- //
    if (!isset($_GET['bookingId']) || empty($_GET['bookingId']) || !is_numeric($_GET['bookingId'])) {
        header("Location: dashboard.php");
        exit();
    }
    $booking = getBookingById($conn, $_GET['bookingId']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['bookingId'])) {
        $action = $_POST['action'];
        $bookingId   = (int)$_POST['bookingId'];
        $roomId      = (int)($_POST['roomIdInput'] ?? $_POST['roomId']);
        $checkIn     = $_POST['checkInInput'] ?? '';
        $checkOut    = $_POST['checkOutInput'] ?? '';
        $bookingDate = $_POST['bookingDateInput'] ?? null;
        $totalPrice  = (float)($_POST['totalPriceInput'] ?? 0);

        // ✅ ถ้ากดปุ่ม "คำนวณราคาใหม่"
        if ($action === 'recalc') {
            // ดึงราคาห้องจากฐานข้อมูล
            $stmt = $conn->prepare("SELECT roomPrice FROM rooms WHERE roomId = :roomId");
            $stmt->bindValue(':roomId', $roomId, PDO::PARAM_INT);
            $stmt->execute();
            $room = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($room) {
                $pricePerNight = (float)$room['roomPrice'];
                $checkinDate = new DateTime($checkIn);
                $checkoutDate = new DateTime($checkOut);
                $nights = max(1, (int)$checkinDate->diff($checkoutDate)->format('%a'));
                $totalPrice = $pricePerNight * $nights;
            }
        }

        // ✅ update booking
        $result = UpdateBookingById($conn, $bookingId, $roomId, $checkIn, $checkOut, $totalPrice, $bookingDate);
        if ($result['success']) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error = $result['error'] ?? 'Unknown error';
            echo "<script>alert('Error: " . htmlspecialchars($error) . "');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking : <?= htmlspecialchars($booking['bookingId']); ?></title>

    <!-- bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <!-- Navbar -->
    <?php include '../../components/Navbar/Navbar.php'; ?>
    <!-- End Navbar -->
    <!-- Main Content -->
    <div class="container">
        <?php
        // เตรียมค่า datetime-local ให้ถูกต้อง
        $bookingDateValue = '';
        if (!empty($booking['bookingDate'])) {
            $bookingDateValue = date('Y-m-d\TH:i', strtotime($booking['bookingDate']));
        }
        ?>
        <form action="" method="post" class="container my-4">
            <div class="card shadow-sm border-0 rounded-4 w-100 mx-auto">
                <div class="card-header text-white rounded-top-4 py-3" style="background-color: <?= htmlspecialchars($hotelTheme['colorPrimary']) ?>;">
                    <h5 class="mb-0">แก้ไขข้อมูลการจองห้องพัก ID: <?= htmlspecialchars($booking['bookingId']); ?></h5>
                </div>

                <div class="card-body p-4">
                    <input type="hidden" name="bookingId" value="<?= htmlspecialchars($booking['bookingId']); ?>">
                    <input type="hidden" name="userId" value="<?= htmlspecialchars($booking['userId']); ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">ชื่อห้อง</label>
                            <select class="form-select mb-3" name="roomIdInput">
                                <?php foreach ($rooms as $room): ?>
                                    <option
                                        value="<?= htmlspecialchars($room['roomId']); ?>"
                                        data-price="<?= htmlspecialchars($room['roomPrice']); ?>"
                                        <?= ($room['roomId'] == $booking['roomId']) ? 'selected' : ''; ?>>
                                        <?= "ID: " . htmlspecialchars($room['roomId']) . " — " . htmlspecialchars($room['roomName']) . " (฿" . number_format($room['roomPrice']) . "/คืน)" ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">ชื่อผู้จอง</label>
                            <input type="text" value="<?= "ID: " . htmlspecialchars($booking['userId']) . " " . htmlspecialchars($booking['userName']); ?>" class="form-control" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">วันที่จอง</label>
                            <input type="datetime-local" name="bookingDateInput" value="<?= htmlspecialchars($bookingDateValue); ?>" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">เช็คอิน</label>
                            <input type="date" name="checkInInput" value="<?= htmlspecialchars($booking['checkIn']); ?>" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">เช็คเอาท์</label>
                            <input type="date" name="checkOutInput" value="<?= htmlspecialchars($booking['checkOut']); ?>" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">จำนวนคืน</label>
                            <input type="number" step="1" value="<?= htmlspecialchars($booking['totalNights']); ?>" class="form-control text-end" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">ราคารวม (บาท)</label>
                            <input type="number" step="0.01" name="totalPriceInput" value="<?= htmlspecialchars($booking['totalPrice']); ?>" class="form-control text-end">
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light text-center py-3 rounded-bottom-4">
                    <!-- ปุ่ม 1: คำนวณราคาใหม่ -->
                    <button type="submit" name="action" value="recalc" class="btn btn-success px-4 me-2">คำนวณราคาใหม่และบันทึก</button>

                    <!-- ปุ่ม 2: บันทึกโดยไม่คำนวณราคา -->
                    <button type="submit" name="action" value="update" class="btn btn-primary px-4 me-2">บันทึกโดยไม่คำนวณราคา</button>
                    <a href="dashboard.php" class="btn btn-outline-danger px-4">ยกเลิก</a>
                </div>
            </div>
        </form>


    </div>
    <!-- End Main Content-->

    <!-- Footer -->
    <?php include '../../components/Footer.php'; ?>
    <!-- End Footer -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkInInput = document.querySelector('input[name="checkInInput"]');
            const checkOutInput = document.querySelector('input[name="checkOutInput"]');
            const totalNightsInput = document.querySelector('input[name="totalNightsInput"]');
            const totalPriceInput = document.querySelector('input[name="totalPriceInput"]');
            const roomSelect = document.querySelector('select[name="roomIdInput"]');

            // สมมติว่าแต่ละ <option> มีราคาใน data attribute
            // เช่น <option value="1" data-price="2500">Deluxe Room</option>
            // ถ้ายังไม่มี → เพิ่มใน PHP loop ได้เลย

            function calculatePrice() {
                const checkIn = new Date(checkInInput.value);
                const checkOut = new Date(checkOutInput.value);
                const roomOption = roomSelect.options[roomSelect.selectedIndex];
                const pricePerNight = parseFloat(roomOption.getAttribute("data-price")) || 0;

                // ตรวจสอบวันที่
                if (!isNaN(checkIn) && !isNaN(checkOut) && checkOut > checkIn) {
                    const nights = Math.round((checkOut - checkIn) / (1000 * 60 * 60 * 24));
                    totalNightsInput.value = nights;

                    const totalPrice = nights * pricePerNight;
                    totalPriceInput.value = totalPrice.toFixed(2);

                    // ✅ แสดงผลแบบสวย ๆ ใน console หรือ alert
                    console.log(`คำนวณราคา: ${nights} คืน × ${pricePerNight} บาท = ${totalPrice} บาท`);
                } else {
                    totalNightsInput.value = '';
                    totalPriceInput.value = '';
                }
            }

            // เมื่อเปลี่ยนห้องหรือวันที่ → คำนวณใหม่ทันที
            [checkInInput, checkOutInput, roomSelect].forEach(el => {
                el.addEventListener("change", calculatePrice);
            });
        });
    </script>

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>