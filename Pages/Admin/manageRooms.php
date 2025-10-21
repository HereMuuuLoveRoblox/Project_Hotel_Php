<?php
    session_start();
    include '../../configs/Connect_DB.php'; // เชื่อมต่อฐานข้อมูล
    include '../../configs/basefile.php'; //-- baseUrl, basePath

    //-- ถ้าไม่ใช่ admin → กลับไปหน้า login
    if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../login.php");
        exit();
    }
    // ---------------------------------------------------------------- //

    include '../../functions/hotelTheme.php'; //-- ข้อมูลโรงแรม
    $hotelTheme = getHotelTheme($conn);

    //-- include Function
    include '../../functions/getRooms.php'; //-- ข้อมูลห้องพัก
    $rooms = getAllRoomsAndImagesShow($conn);

    include '../../functions/getUsers.php'; //-- ข้อมูลผู้ใช้
    $user = getUserById($conn, $_SESSION['userId']);

    include '../../functions/CRUDrooms.php'; //-- แก้ไขห้องพัก

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $action = $_POST['action'] ?? '';
        if ($action === 'delete_room' && isset($_POST['roomId'])) {
            $roomId = (int)$_POST['roomId'];
            deleteRoom($conn, $roomId);
            echo "<script>window.location.href = 'manageRooms.php';</script>";
            exit();
        }
        if ($action === 'create_room') {
            CreateRoom($conn, "New Room", "Room Detail", 0);
            header('Location: manageRooms.php#newroom');
            exit();
        }
    }

    function limitCharacters($text, $limit) {
        if (mb_strlen($text, 'UTF-8') > $limit) {
            return mb_substr($text, 0, $limit, 'UTF-8') . '...'; // ตัดข้อความและใส่ ...
        }
        return $text;
    }
    // ---------------------------------------------------------------- //
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <!-- Navbar -->
    <?php include '../../components/Navbar/Navbar.php'; ?>
    <!-- End Navbar -->
    <!-- Main Content -->
    <div class="container" id="manage-rooms">
        
        <form action="" method="post" class="text-end my-3">
            <input type="hidden" name="action" value="create_room">
            <a href="dashboard.php" class="btn btn-outline-danger">กลับ</a>
            <button type="submit" class="btn btn-success">+ เพิ่มห้องใหม่</button>
        </form>
                    
        <table class="table table-bordered table-hover table-striped rounded shadow-sm table-light" style="table-layout:fixed; width:100%;">
            <colgroup>
                <col style="width:80px;"> <!-- roomId -->
                <col style="width:150px;"> <!-- รูปภาพหลัก -->
                <col style="width:22%;"> <!-- ชื่อห้อง -->
                <col style="width:30%;"> <!-- รายละเอียด -->
                <col style="width:120px;"> <!-- ราคา/คืน -->
                <col style="width:90px;"> <!-- แก้ไข -->
                <col style="width:90px;"> <!-- ลบ -->
            </colgroup>
            <thead>
                <tr>
                    <th scope="col" class="text-center">roomId</th>
                    <th scope="col" class="text-center">รูปภาพหลัก</th>
                    <th scope="col" class="text-center">ชื่อห้อง</th>
                    <th scope="col" class="text-center">รายละเอียด</th>
                    <th scope="col" class="text-center">ราคา / คืน</th>
                    <th scope="col" colspan="2" class="text-center">แก้ไข</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rooms): ?>
                    <?php foreach ($rooms as $room): ?>
                        <tr>
                            <th class="align-middle text-center" scope="row"><?php echo htmlspecialchars($room['roomId']); ?></th>
                            <td class="align-middle"><img src="../../images/rooms/<?php echo htmlspecialchars($room['rimgPath']); ?>" alt="<?php echo htmlspecialchars($room['roomName']); ?>:ไม่มีรูปภาพ" class="img-thumbnail" style="max-width: 150px;"></td>
                            <td class="align-middle h4"><?php echo htmlspecialchars($room['roomName']); ?></td>
                            <td class="align-middle">
                                <?php echo htmlspecialchars(limitCharacters($room['roomDetail'], 100)); ?>
                            </td>
                            <td class="align-middle h4 text-center"><?php echo htmlspecialchars($room['roomPrice']); ?></td>
                            <td class="align-middle text-center"><a href="editRoom.php?roomId=<?php echo $room['roomId']; ?>" class="btn btn-warning">แก้ไข</a></td>

                            <form action="" method="POST" onsubmit="return confirm('ยืนยันลบห้องนี้? : <?php echo addslashes($room['roomName']); ?>')">
                                <input type="hidden" name="action" value="delete_room">
                                <input type="hidden" name="roomId" value="<?php echo $room['roomId']; ?>">
                                <td class="align-middle text-center"><button type="submit" class="btn btn-danger">ลบ</button></td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">ไม่มีห้องพัก</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <section id="newroom"></section>
    
    <!-- Footer -->
    <?php include '../../components/Footer.php'; ?>
    <!-- End Footer -->

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>