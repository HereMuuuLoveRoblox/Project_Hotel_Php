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
    $bookings = getAllBookingsDashboard($conn);

    $statusCounts = getStatusCountAllRooms($conn);

    include '../../functions/CRUDbooking.php'; //-- CRUD Booking

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['bookingId'])) {

        $action = $_POST['action'] ?? '';
        if ($action == 'delete') {
            $bookingId = $_POST['bookingId'];
            echo $bookingId;
            if ($bookingId) {
                DeleteBookingById($conn, $bookingId);
                header("Location: dashboard.php");
                exit();
            }
        }
        if ($action == 'edit') {
            $bookingId = $_POST['bookingId'];
            if ($bookingId) {
                header("Location: editBooking.php?bookingId=" . urlencode($bookingId));
                exit();
            }
        }
    }
    // ---------------------------------------------------------------- //
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

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
        <?php if ($bookings): ?>
            <?php include '../../components/CardDashboard.php'; ?>
            <h1 class="mt-4 mb-4">ข้อมูลการจอง</h1>
            <table class="table table-bordered table-hover table-striped rounded shadow-sm table-light">
                <thead class="">
                    <colgroup>
                        <col style="width:5%;"> <!-- bookingId -->
                        <col style="width:13%;"> <!-- ชื่อห้อง -->
                        <col style="width:10%;"> <!-- ผู้จอง -->
                        <col style="width:15%;"> <!-- วันที่จอง -->
                        <col style="width:10%;"> <!-- เช็คอิน -->
                        <col style="width:10%;"> <!-- เช็คเอาท์ -->
                        <col style="width:7%;"> <!-- จำนวนคืน -->
                        <col style="width:10%;"> <!-- ราคารวม -->
                        <col style="width:10%;"> <!-- สถานะ -->
                        <col style="width:10%;"> <!-- จัดการ -->
                    </colgroup>
                    <tr>
                        <th scope="col" class="text-center">ไอดี</th>
                        <th scope="col">ชื่อห้อง</th>
                        <th scope="col">ผู้จอง</th>
                        <th scope="col" class="text-center">วันที่จอง</th>
                        <th scope="col" class="text-center">วันที่เช็คอิน</th>
                        <th scope="col" class="text-center">วันที่เช็คเอาท์</th>
                        <th scope="col" class="text-center">จำนวนคืน</th>
                        <th scope="col" class="text-center">ราคารวม</th>
                        <th scope="col" class="text-center">สถานะ</th>
                        <th scope="col" class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td class="text-center"><?= htmlspecialchars($booking['bookingId']) ?></td>
                            <td><?= htmlspecialchars($booking['roomName']) ?></td>
                            <td><?= htmlspecialchars($booking['userName']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($booking['bookingDate']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($booking['checkIn']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($booking['checkOut']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($booking['totalNights']) ?></td>
                            <td class="text-end"><?= number_format((float)$booking['totalPrice'], 2) ?></td>
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
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="bookingId" value="<?= htmlspecialchars($booking['bookingId']) ?>">
                                    <button type="submit" class="btn btn-warning btn-sm" name="action" value="edit">แก้ไข</button>
                                    <button type="submit" class="btn btn-danger btn-sm" name="action" value="delete" onclick="return confirm('ยืนยันที่จะลบการจองนี้?');">ลบ</button>

                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning mt-4" role="alert">
                ไม่มีข้อมูลการจองในระบบ
            </div>
        <?php endif; ?>
    </div>
    <!-- End Main Content-->

    <!-- Footer -->
    <?php include '../../components/Footer.php'; ?>
    <!-- End Footer -->

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>