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

// ดึงค่า hotel_services จากฐานข้อมูล
function get_hotel_services($conn)
{
    $stmt = $conn->prepare("SELECT url_image, service_detail FROM homepage_hotel_services");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result ? $result : null;
}
$hotel_services = get_hotel_services($conn);
$count = count($hotel_services);

// สร้างตัวแปร $hotel_services_1 ... $hotel_services_4 แบบ dynamic
for ($i = 0; $i < $count; $i++) {
    ${"hotel_services_" . ($i + 1)} = $hotel_services[$i];
}

// ดึงค่าธีมจากฐานข้อมูล
function get_room_card($conn)
{
    $stmt = $conn->prepare("
                SELECT room_name, price_per_night, room_people, room_img_path 
                FROM rooms
                WHERE room_id BETWEEN 1 AND 4
            ");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result ?: [];
}
$room_cards = get_room_card($conn);
$room_cards_assoc = [];
foreach ($room_cards as $index => $room) {
    $key = 'room-card-' . ($index + 1);
    $room_cards_assoc[$key] = [
        'name' => $room['room_name'],
        'price' => $room['price_per_night'],
        'detail' => $room['room_people'],
        'img' => $room['room_img_path']
    ];
}
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
                    <a href="index.php">
                        <h1 class="text_logo" style="color: <?php echo $theme['primary_color']; ?>;">
                            <?php echo $theme['hotel_name']; ?>
                        </h1>
                    </a>
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
                    <a href="index.php">
                        <h1 class="text_logo" style="color: <?php echo $theme['primary_color']; ?>;">
                            <?php echo $theme['hotel_name']; ?>
                        </h1>
                    </a>
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

    <div class="container">

        <section class="hotel-services" id="services">
            <div class="service-item">
                <img src="<?php echo $hotel_services_1['url_image'] ?>" alt="">
                <p><?php echo $hotel_services_1['service_detail'] ?></p>
            </div>
            <div class="service-item">
                <img src="<?php echo $hotel_services_2['url_image'] ?>" alt="">
                <p><?php echo $hotel_services_2['service_detail'] ?></p>
            </div>
            <div class="service-item">
                <img src="<?php echo $hotel_services_3['url_image'] ?>" alt="">
                <p><?php echo $hotel_services_3['service_detail'] ?></p>
            </div>
            <div class="service-item">
                <img src="<?php echo $hotel_services_4['url_image'] ?>" alt="">
                <p><?php echo $hotel_services_4['service_detail'] ?></p>
            </div>
        </section>


        <main>
            <section id="about"></section>
            <div class="main-header">
                <div class="main-header-text">
                    <h3 style="color: #000000;">ทำไมคุณถึง</h3>
                    <h3 style="color: <?php echo $theme['primary_color']; ?>;">ควรพักกับเรา</h3>
                </div>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis voluptatibus vel aspernatur vitae odit quia.Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis voluptatibus vel aspernatur vitae odit quia.</p>
            </div>

            <div class="main-content">
                <div class="main-content-left">
                    <div class="content-item">
                        <div class="content-item-number" style="background-color: <?php echo $theme['primary_color']; ?>;">1</div>
                        <div class="content-item-text">
                            <h4>Best Price Guarantee</h4>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis voluptatibus vel aspernatur vitae odit quia.</p>
                        </div>
                    </div>
                    <div class="content-item">
                        <div class="content-item-number" style="background-color: <?php echo $theme['primary_color']; ?>;">2</div>
                        <div class="content-item-text">
                            <h4>Best Price Guarantee</h4>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis voluptatibus vel aspernatur vitae odit quia.</p>
                        </div>
                    </div>
                    <div class="content-item">
                        <div class="content-item-number" style="background-color: <?php echo $theme['primary_color']; ?>;">3</div>
                        <div class="content-item-text">
                            <h4>Best Price Guarantee</h4>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis voluptatibus vel aspernatur vitae odit quia.</p>
                        </div>
                    </div>
                </div>
                <div class="main-content-right">
                    <img src="<?php echo $theme['main_content_image_url']; ?>" style="width: 100%; height: 416px; object-fit: cover;" alt="">
                </div>
            </div>
            <section id="rooms"></section>
            <div class="main-book-rooms">
                <div class="main-book-rooms-header">
                    <p class="main-book-rooms-title">ห้องของเรา</p>
                    <div class="main-header-text">
                        <h3 style="color: #000000;">เพราะนี่คือ</h3>
                        <h3 style="color: <?php echo $theme['primary_color']; ?>;">ทางเลือก</h3>
                    </div>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis voluptatibus vel aspernatur vitae odit quia.Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis voluptatibus vel aspernatur vitae odit quia.</p>
                </div>
                <div class="main-book-rooms-content">
                    <?php
                    // จำกัดจำนวนลูปสูงสุด 4 ครั้ง หรือเท่ากับจำนวนข้อมูลที่มี (ถ้าน้อยกว่า 4)
                    $limit = min(4, count($room_cards_assoc));
                    $keys = array_keys($room_cards_assoc);

                    for ($i = 0; $i < $limit; $i++):
                        $key = $keys[$i];
                        $room = $room_cards_assoc[$key];
                    ?>
                        <div class="room-card">
                            <img src="<?php echo htmlspecialchars($room['img']); ?>" style="border-radius: 15px;" alt="">
                            <div class="room-card-text">
                                <div class="room-card-text-left">
                                    <h3><?php echo htmlspecialchars($room['name']); ?></h3>
                                    <p><img src="images/Location.png" alt=""><?php echo htmlspecialchars($room['detail']); ?></p>
                                </div>
                                <div class="room-card-text-right">
                                    <h3><?php echo htmlspecialchars($room['price']); ?>/คืน</h3>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </main>
    </div>
    <section id="contact"></section>
    <footer style="background-color: <?php echo $theme['secondary_color']; ?>;">
        <p>© 2023 CSD3202-WorkShop. All rights reserved.</p>
    </footer>
    <script src="script.js"></script>
</body>

</html>