<?php
    $link_css = 'text-decoration-none text-dark hover-underline-animation text-decoration-none fw-semibold text-dark';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        .hover-underline-animation {
            position: relative;
            display: inline-block;
            color: inherit;
        }

        .hover-underline-animation::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -3px;
            width: 0;
            height: 2px;
            background-color: <?php echo $hotel_color_primary; ?>;
            transition: width 0.3s ease-in-out;
        }

        .hover-underline-animation:hover::after {
            width: 100%;
        }

        /* Navbar ที่ fixed */
        .fixedNav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            opacity: 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transform: translateY(-100%);
            transition: all 0.8s ease;
        }

        /* แสดงเมื่อ scroll */
        .fixedNav.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body>
    <div id="mainNav">
        <nav  class="d-flex justify-content-between container py-5 align-items-center">
            <div class="nav-left">
                <a href="homepage.php" class="h2 text-decoration-none" style="white-space: nowrap; display: inline-block; color: <?php echo $hotel_color_primary; ?>;"><?php echo $hotel_name; ?></a>
            </div>
            <div class="nav-middle d-flex justify-content-around w-50">
                <a href="manageUsers.php" class="<?php echo $link_css; ?>">Manage Users</a>
                <a href="manageRooms.php" class="<?php echo $link_css; ?>">Manage Rooms</a>
            </div>
            <div class="nav-right d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $user['userName']; ?>
                    </button>
                    <ul class="dropdown-menu mt-2">
                        <li><p class="dropdown-item"><?php echo $user['email']; ?></p></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="../configs/logout.php">Logout</a></li>
                    </ul>
                </div>
                <a href="../Pages/rooms.php#rooms" class="btn" style="background-color: <?php echo $hotel_color_primary; ?>; color: white;">Book Now</a>
            </div>
        </nav>
    </div>
    <div id="fixedNav" class="fixedNav bg-white">
        <nav id="mainNav" class="d-flex justify-content-between container py-5 align-items-center" style="height: 50px;">
            <div class="nav-left">
                <a href="homepage.php" class="h2 text-decoration-none" style="white-space: nowrap; display: inline-block; color: <?php echo $hotel_color_primary; ?>;"><?php echo $hotel_name; ?></a>
            </div>
            <div class="nav-middle d-flex justify-content-around w-50">
                <a href="#manage-user" class="<?php echo $link_css; ?>">Manage Users</a>
                <a href="#manage-rooms" class="<?php echo $link_css; ?>">Manage Rooms</a>
            </div>
            <div class="nav-right d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $user['userName']; ?>
                    </button>
                    <ul class="dropdown-menu mt-2">
                        <li><p class="dropdown-item"><?php echo $user['email']; ?></p></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="../configs/logout.php">Logout</a></li>
                    </ul>
                </div>
                <a href="../Pages/rooms.php#rooms" class="btn" style="background-color: <?php echo $hotel_color_primary; ?>; color: white;">Book Now</a>
            </div>
        </nav>
    </div>
    <!-- bootstrap -->
    <script src="../js/Navbar.js"></script>