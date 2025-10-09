<?php
    session_start();
    include '../configs/Connect_DB.php';
    include '../configs/hotelTheme.php';

    if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
        header("Location: login.php");
        exit();
    }

    function getUserById($conn, $userId) {
        $stmt = $conn->prepare("SELECT userName, email, role FROM users WHERE userId = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    $user = getUserById($conn, $_SESSION['userId']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include '../components/NavbarAdmin.php';?>
    <!-- End Navbar -->

    <!-- Main Content -->
    

    <!-- Footer -->
    <?php include '../components/Footer.php';?>
    <!-- End Footer -->
     
    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>