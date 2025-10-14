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

    include '../../functions/getBookings.php'; //-- ข้อมูลการจอง
    $bookings = getAllBookings($conn);
    
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
        <div class="d-flex justify-content-start align-items-center mt-4 mb-4 gap-3">
            <div class="card text-bg-light mb-3" style="max-width: 18rem;">
                <div class="card-header">จำนวนการจอง</div>
                <div class="card-body">
                    
                    <p class="card-text"><?php echo count($bookings); ?> รายการ</p>
                </div>
            </div>
            <div class="card text-bg-light mb-3" style="max-width: 18rem;">
                <div class="card-header">ยอดรวมรายได้ทั้งหมด</div>
                <div class="card-body">
                    <p class="card-text"><?php echo array_sum(array_column($bookings, 'totalPrice')); ?> บาท</p>
                </div>
            </div>
            <div class="card text-bg-light mb-3" style="max-width: 18rem;">
                <div class="card-header">จำนวนผู้ใช้</div>
                <div class="card-body">
                    <p class="card-text"><?php echo count(array_unique(array_column($bookings, 'userId'))); ?> คน</p>
                </div>
            </div>
        </div>
        
        <h1 class="mt-4 mb-4">ข้อมูลการจอง</h1>
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th scope="col">bookingId</th>
                    <th scope="col">ชื่อห้อง</th>
                    <th scope="col">ผู้จอง</th>
                    <th scope="col">วันที่จอง</th>
                    <th scope="col">วันที่เช็คอิน</th>
                    <th scope="col">วันที่เช็คเอาท์</th>
                    <th scope="col">จำนวนคืน</th>
                    <th scope="col">ราคารวม</th>
                    <th scope="col">สถานะ</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['bookingId']) ?></td>
                        <td><?= htmlspecialchars($booking['roomName']) ?></td>
                        <td><?= htmlspecialchars($booking['userName']) ?></td>
                        <td><?= htmlspecialchars($booking['bookingDate']) ?></td>
                        <td><?= htmlspecialchars($booking['checkIn']) ?></td>
                        <td><?= htmlspecialchars($booking['checkOut']) ?></td>
                        <td><?= htmlspecialchars($booking['totalNights']) ?></td>
                        <td><?= number_format((float)$booking['totalPrice'], 2) ?></td>
                        <td>
                            <?php 
                                if ($booking['status'] === 'upcoming') {
                                    echo '<span class="badge bg-info text-dark">ยังไม่ถึงวันเข้าพัก</span>';
                                } elseif ($booking['status'] === 'in_stay') {
                                    echo '<span class="badge bg-success">กำลังเข้าพัก</span>';
                                } else {
                                    echo '<span class="badge bg-danger">เช็คเอาท์แล้ว</span>';
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
    <?php include '../../components/Footer.php'; ?>
    <!-- End Footer -->

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>