<?php
session_start();
include '../configs/Connect_DB.php';
include '../configs/hotelTheme.php';

if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

function getUserById($conn, $userId)
{
    $stmt = $conn->prepare("SELECT userName, email, role FROM users WHERE userId = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
$user = getUserById($conn, $_SESSION['userId']);

function getAllRooms($conn)
{
    $stmt = $conn->prepare("SELECT rooms.roomId, rooms.roomName, rooms.roomDetail, rooms.roomPrice, roomsimages.rimgPath, roomsimages.rimgShow
                                FROM rooms
                                JOIN roomsimages ON rooms.roomId = roomsimages.roomId
                                WHERE rimgShow = 1");

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result ? $result : null;
}
$rooms = getAllRooms($conn);

function getRoomServicesById($conn, $roomId)
{
    $stmt = $conn->prepare("SELECT roomservices.serviceName 
                                FROM roomservices 
                                WHERE roomservices.roomId = :roomId");
    $stmt->bindParam(':roomId', $roomId);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result ? $result : null;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>

    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include '../components/NavbarAdmin.php'; ?>
    <!-- End Navbar -->
    <!-- Main Content -->
    <div class="container" id="manage-rooms">
        <table class="table table-hover border-top-color table-light table-bordered">
            <thead>
                <tr>
                    <th scope="col">roomId</th>
                    <th scope="col">roomImg</th>
                    <th scope="col">roomName</th>
                    <th scope="col">roomDetail</th>
                    <th scope="col">roomPrice</th>
                    <th scope="col">edit</th>
                    <th scope="col">delete</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rooms): ?>
                    <?php foreach ($rooms as $room): ?>
                        <tr>
                            <th class="align-middle" scope="row"><?php echo htmlspecialchars($room['roomId']); ?></th>
                            <td class="align-middle"><img src="../<?php echo htmlspecialchars($room['rimgPath']); ?>" alt="<?php echo htmlspecialchars($room['roomName']); ?>" class="img-thumbnail" style="max-width: 150px;"></td>
                            <td class="align-middle h4"><?php echo htmlspecialchars($room['roomName']); ?></td>
                            <td class="align-middle"><?php echo htmlspecialchars($room['roomDetail']); ?></td>
                            <td class="align-middle h4"><?php echo htmlspecialchars($room['roomPrice']); ?></td>
                            <td class="align-middle"><a href="editRoom.php?roomId=<?php echo $room['roomId']; ?>" class="btn btn-primary">Edit</a></td>
                            <td class="align-middle"><a href="deleteRoom.php?roomId=<?php echo $room['roomId']; ?>" class="btn btn-danger">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No rooms found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <?php include '../components/Footer.php'; ?>
    <!-- End Footer -->

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>