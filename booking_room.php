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
        $stmt = $conn->prepare("SELECT hotel_name, primary_color, secondary_color, hero_image_url, main_content_image_url, path_show_hotel_1, path_show_hotel_2, path_show_hotel_3, path_show_hotel_4, path_show_hotel_5, path_show_hotel_6, path_show_hotel_7 FROM hotel_theme WHERE id = 1");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }
    $theme = get_theme_hotel($conn);


    if (!isset($_GET['id'])) {
        echo "No room ID provided.";
        header("Location: booking.php");
        exit();
    }
    $roomId = $_GET['id'];
    
    // ดึงข้อมูลห้องพักจากฐานข้อมูลตาม ID
    function get_room_by_id($conn, $roomId) {
        $stmt = $conn->prepare("SELECT * FROM rooms WHERE room_id = :id");
        $stmt->bindParam(':id', $roomId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    $room = get_room_by_id($conn, $roomId);
    if (!$room) {
        echo "Room not found.";
        header("Location: booking.php");
        exit();
    }
    echo '<pre>' . print_r($room, true) . '</pre>';
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

    <link rel="stylesheet" href="styles/pages/booking.css">
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
    <hero>
        <img class="hero-image" src="<?php echo $theme['hero_image_url']; ?>" style="width: 100%; height: 544px; object-fit: cover;" alt="">
    </hero>

    <main>
        <div class="container">
            <h2>Room Details</h2>
            <div class="room-details">
                <img src="<?php echo $room['room_img_path']; ?>" alt="Room Image" style="width:300px; height:200px; object-fit:cover;">
                <div class="room-info">
                    
                </div>
            </div>
        </div>
    </main>

    <section id="contact"></section>
    <footer style="background-color: <?php echo $theme['secondary_color']; ?>;">
        <p>© 2023 CSD3202-WorkShop. All rights reserved.</p>
    </footer>
    <script src="script.js"></script>
    <script>
    // เมื่อคลิกรูปใน grid
    document.querySelectorAll('.grid-item').forEach(item => {
    item.addEventListener('click', () => {
        const imgSrc = item.getAttribute('data-img');
        document.getElementById('modal-img').src = imgSrc;
        document.getElementById('modal').style.display = 'flex';
    });
    });

    // คลิกที่พื้นหลังเพื่อปิด
    document.getElementById('modal').addEventListener('click', () => {
    document.getElementById('modal').style.display = 'none';
    });
    </script>
</body>

</html>