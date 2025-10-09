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

    /* -------------------------------
    รับค่า POST จากฟอร์ม room-type
        -------------------------------- */
    $roomType = isset($_POST['room-type']) ? $_POST['room-type'] : 'all'; // ค่าเริ่มต้น
    // ถ้าคุณมีคอลัมน์ room_type ในตาราง rooms (ENUM: single,double,group,suite)
    // สามารถดึงห้องตามประเภทได้แบบนี้:
    function get_rooms_by_type(PDO $conn, string $roomType)
    {
        if ($roomType === 'all') {
            $stmt = $conn->prepare("SELECT room_id, room_name, price_per_night, room_people, room_img_path, room_type, 
                                        room_detail_1, room_detail_2, room_detail_3, room_detail_4, room_detail_5 
                                        FROM rooms 
                                        ORDER BY room_id ASC");
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("SELECT room_id, room_name, price_per_night, room_people, room_img_path, room_type, 
                                        room_detail_1, room_detail_2, room_detail_3, room_detail_4, room_detail_5
                                        FROM rooms
                                        WHERE room_type = :rt
                                        ORDER BY room_id ASC");
            $stmt->execute([':rt' => $roomType]);
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    $rooms = get_rooms_by_type($conn, $roomType);
    // แปลงข้อมูลห้องเป็นรูปแบบที่ต้องการ
    $room_cards_assoc = [];
    foreach ($rooms as $index => $room) {
        $key = 'room-card-' . ($index + 1);
        $room_cards_assoc[$key] = [
            'id' => $room['room_id'],
            'name' => $room['room_name'],
            'price' => $room['price_per_night'],
            'people' => $room['room_people'],
            'img' => $room['room_img_path'],
            'type' => $room['room_type'],
            'room_detail_1' => $room['room_detail_1'],
            'room_detail_2' => $room['room_detail_2'],
            'room_detail_3' => $room['room_detail_3'],
            'room_detail_4' => $room['room_detail_4'],
            'room_detail_5' => $room['room_detail_5']
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

    <link rel="stylesheet" href="styles/pages/booking.css">
</head>
<style>
    /* ปุ่มที่ถูกเลือก */
    .room-type-filter button.active {
        background-color: <?php echo $theme['primary_color']; ?>;
        color: #fff;
        box-shadow: 0 3px 10px rgba(37,99,235,0.4);
    }
</style>
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
        <div class="grid-container">
            <div class="grid-item item-1" data-img="<?php echo $theme['path_show_hotel_1']; ?>">
                <img src="<?php echo $theme['path_show_hotel_1']; ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="">
            </div>
            <div class="grid-item item-2" data-img="<?php echo $theme['path_show_hotel_2']; ?>">
                <img src="<?php echo $theme['path_show_hotel_2']; ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="">
            </div>
            <div class="grid-item item-3" data-img="<?php echo $theme['path_show_hotel_3']; ?>">
                <img src="<?php echo $theme['path_show_hotel_3']; ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="">
            </div>
            <div class="grid-item item-4" data-img="<?php echo $theme['path_show_hotel_4']; ?>">
                <img src="<?php echo $theme['path_show_hotel_4']; ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="">
            </div>
            <div class="grid-item item-5" data-img="<?php echo $theme['path_show_hotel_5']; ?>">
                <img src="<?php echo $theme['path_show_hotel_5']; ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="">
            </div>
            <div class="grid-item item-6" data-img="<?php echo $theme['path_show_hotel_6']; ?>">
                <img src="<?php echo $theme['path_show_hotel_6']; ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="">
            </div>
            <div class="grid-item item-7" data-img="<?php echo $theme['path_show_hotel_7']; ?>">
                <img src="<?php echo $theme['path_show_hotel_7']; ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="">
            </div>
        </div>
        <!-- 🔹 กล่อง popup แสดงรูป -->
        <div class="modal" id="modal">
            <img id="modal-img" src="" alt="Preview">
        </div>

        <hr>
        <main>
            <div class="room-type-filter">
                <form method="post" action="">
                    <button type="submit" name="room-type" value="all"
                    class="<?= ($roomType === 'all') ? 'active' : '' ?>">ทั้งหมด</button>

                    <button type="submit" name="room-type" value="single" 
                    class="<?= ($roomType === 'single') ? 'active' : '' ?>">ห้องเดี่ยว</button>

                    <button type="submit" name="room-type" value="double" 
                    class="<?= ($roomType === 'double') ? 'active' : '' ?>">ห้องคู่</button>

                    <button type="submit" name="room-type" value="group" 
                    class="<?= ($roomType === 'group') ? 'active' : '' ?>">ห้องหมู่</button>

                    <button type="submit" name="room-type" value="suite" 
                    class="<?= ($roomType === 'suite') ? 'active' : '' ?>">ห้องสวีท</button>
                </form>
            </div>
            <?php
            if (empty($rooms)) {
                echo "<p>ไม่พบห้องพักในประเภทนี้</p>";
            } else {
                echo '<section id="rooms"></section>';
                echo '<div class="rooms">';
                foreach ($room_cards_assoc as $key => $room) {
                    echo '<div class="card-booking-room">';
                    echo '<img src="' . htmlspecialchars($room['img']) . '" style="width: 246px; height: 184px; object-fit: cover;" alt="">';
                    echo '<div class="card-booking-room-detail">';
                    echo '<div class="card-text">';
                    echo '<h3>' . htmlspecialchars($room['name']) . '</h3>';
                    // สมมติว่า detail เป็นข้อความที่คั่นด้วยจุลภาค
                    $details = explode(',', $room['room_detail_1'] . ',' . $room['room_detail_2'] . ',' . $room['room_detail_3'] . ',' . $room['room_detail_4'] . ',' . $room['room_detail_5']);
                    echo '<div class="room-details-container">';
                    foreach ($details as $detail) {
                        echo '<p class="room-detail">' . htmlspecialchars(trim($detail)) . '</p>';
                    }
                    echo '</div>'; // ปิด room-details-container
                    echo '<p>Price: ' . number_format($room['price']) . ' THB/Night</p>';
                    echo '</div>';
                    echo '<a style="background-color: ' . htmlspecialchars($theme['primary_color']) . ';" href="booking_room.php?id=' . $room['id'] . '">Book Now</a>';
                    echo '</div>'; // ปิด card-booking-room-detail
                    echo '</div>'; // ปิด card-booking-room
                }
                echo '</div>'; // ปิด rooms
            }
            ?>
        </main>
    </div>
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