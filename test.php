<?php
// 1) ต้องอยู่บรรทัดแรกสุด ก่อนมี HTML/output
session_start();

// 2) ตรวจสอบว่ามี session ที่ต้องการหรือไม่
if (!isset($_SESSION['email']) || empty($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}
include 'Connect_DB.php';

// ดึงค่าธีมจากฐานข้อมูล
function get_theme_hotel($conn)
{
    $stmt = $conn->prepare("SELECT hotel_name, primary_color, secondary_color, hero_image_url, main_content_image_url FROM hotel_theme WHERE id = 1");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result : null;
}
$theme = get_theme_hotel($conn);

$rooms = $conn->query("SELECT * FROM rooms ORDER BY room_id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/Global/Global.css">
    <link rel="stylesheet" href="styles/Global/Navbar.css">
    <link rel="stylesheet" href="styles/Global/Footer.css">

    <link rel="stylesheet" href="styles/pages/index.css">
</head>
<body>
    <section id="home"></section>
    <div class="container">
        <header>
            <div class="Navbar" id="mainNav">
                <div class="nav-left">
                    <h1 class="text_logo" style="color: <?php echo $theme['primary_color']; ?>;">
                        <?php echo $theme['hotel_name']; ?>
                    </h1>
                </div>
                <div class="nav-middle">
                    <a href="index.php">Home</a>
                    <a href="#services">Services</a>
                    <a href="#about">About Us</a>
                    <a href="#rooms">Rooms</a>
                    <a href="#contact">Contact</a>
                </div>
                <div class="nav-right">
                    <a class="book-now" style="background-color: <?php echo $theme['primary_color']; ?>;" href="booking.php">Book Now</a>
                </div>
            </div>

            <!-- Navbar clone ที่จะปรากฏเมื่อ scroll -->
            <div class="Navbar fixedNav" id="fixedNav">
                <div class="nav-left">
                    <h1 class="text_logo" style="color: <?php echo $theme['primary_color']; ?>;">
                        <?php echo $theme['hotel_name']; ?>
                    </h1>
                </div>
                <div class="nav-middle">
                    <a href="#home">Home</a>
                    <a href="#services">Services</a>
                    <a href="#about">About Us</a>
                    <a href="#rooms">Rooms</a>
                    <a href="#contact">Contact</a>
                </div>
                <div class="nav-right">
                    <a class="book-now" style="background-color: <?php echo $theme['primary_color']; ?>;" href="booking.php">Book Now</a>
                </div>
            </div>
        </header>
    </div>

    
    <section id="contact"></section>
    <footer style="background-color: <?php echo $theme['secondary_color']; ?>;">
        <p>© 2023 CSD3202-WorkShop. All rights reserved.</p>
    </footer>
    <script src="script.js"></script>
</body>

</html>