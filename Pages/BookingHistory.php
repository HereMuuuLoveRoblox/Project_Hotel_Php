<?php
    session_start();
    include '../configs/Connect_DB.php'; // เชื่อมต่อฐานข้อมูล
    include '../configs/basefile.php'; //-- baseUrl, basePath

    // เช็คว่า user login แล้วหรือยัง
    if (!isset($_SESSION['userId']) || empty($_SESSION['role'])) {
        header("Location: login.php");
        exit();
    }

    include '../functions/hotelTheme.php'; //-- ข้อมูลโรงแรม
    $hotelTheme = getHotelTheme($conn);

    include '../functions/getUsers.php'; //-- ข้อมูลผู้ใช้
    $user = getUserById($conn, $_SESSION['userId']);

    include '../functions/getBookings.php'; //-- ข้อมูลการจอง
    $bookings = getBookingsByUserId($conn, $_SESSION['userId']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>

    <!-- bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
    </style>
</head>

<body>
    
    <!-- Nav -->
    <?php include '../components/Navbar/Navbar.php';?>
    <?php include '../components/NavCarousel.php';?>
    <?php include '../components/NavServices.php';?>
    <!-- End Nav -->

    <!-- Main Content -->
    <div class="container">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>ประวัติการจองของ : <?= htmlspecialchars($user['userName']) ?></h2>
            <a href="homepage.php" class="btn btn-outline-danger">กลับ</a>
        </div>
        <table class="table table-bordered table-hover table-striped rounded shadow-sm table-light">
                <thead class="">
                    <tr>
                        <th scope="col" class="text-center">รหัสการจอง</th>
                        <th scope="col">ชื่อห้อง</th>
                        <th scope="col" class="text-center">วันที่จอง</th>
                        <th scope="col" class="text-center">เช็คอิน</th>
                        <th scope="col" class="text-center">เช็คเอาท์</th>
                        <th scope="col" class="text-center">จำนวนคืน</th>
                        <th scope="col" class="text-center">ราคารวม</th>
                        <th scope="col" class="text-center">สถานะ</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td class="text-center"><?= htmlspecialchars($booking['bookingId']) ?></td>
                            <td><?= htmlspecialchars($booking['roomName']) ?></td>
                            <td class="text-center"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($booking['bookingDate']))) ?></td>
                            <td class="text-center"><?= htmlspecialchars(date('d/m/Y', strtotime($booking['checkIn']))) ?></td>
                            <td class="text-center"><?= htmlspecialchars(date('d/m/Y', strtotime($booking['checkOut']))) ?></td>
                            <td class="text-center"><?= htmlspecialchars($booking['totalNights']) ?></td>
                            <td class="text-center"><?= htmlspecialchars(number_format($booking['totalPrice'], 2)) ?> ฿</td>
                            <td class="text-center">
                                <?php
                                    if ($booking['status'] === 'upcoming') {
                                        echo '<span class="badge bg-info text-dark"> ยังไม่เข้าพัก</span>';
                                    } elseif ($booking['status'] === 'staying') {
                                        echo '<span class="badge bg-success"> กำลังเข้าพัก</span>';
                                    } elseif ($booking['status'] === 'checked_out') {
                                        echo '<span class="badge bg-secondary"> เช็คเอาท์แล้ว</span>';
                                    } else {
                                        echo '<span class="badge bg-warning text-dark">ไม่ทราบสถานะ</span>';
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
    </div>
    <!-- End Main Content-->

    <!-- Footer -->
    <?php include '../components/Footer.php'; ?>
    <!-- End Footer -->

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>