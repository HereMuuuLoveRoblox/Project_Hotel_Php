<nav class="d-flex justify-content-between container py-5 align-items-center" style="height: 50px;">
     <div class="nav-left">
         <a href="<?php echo url('Pages/homepage.php') ?>" class="h2 text-decoration-none" style="white-space: nowrap; display: inline-block; color: <?php echo $hotelTheme['colorPrimary']; ?>;"><?php echo $hotelTheme['hotelName']; ?></a>
     </div>
     <div class="nav-middle d-flex justify-content-around w-50">
            <a href="<?php echo url('Pages/homepage.php#services') ?>" class="<?php echo $line_animation_CSS; ?>">Services</a>
            <a href="<?php echo url('Pages/homepage.php#about') ?>" class="<?php echo $line_animation_CSS; ?>">About Us</a>
            <a href="<?php echo url('Pages/homepage.php#rooms') ?>" class="<?php echo $line_animation_CSS; ?>">Rooms</a>
     </div>
     <div class="nav-right d-flex align-items-center gap-3">
         <div class="dropdown">
             <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                 <?php echo $user['userName']; ?>
             </button>
             <ul class="dropdown-menu mt-2">
                <li>
                    <p class="dropdown-item"><?php echo $user['email']; ?></p>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <?php
                    if ($user['role'] === 'admin') {
                        echo "<li><a class='dropdown-item' href='" . url('Pages/Admin/Dashboard.php') . "'>Dashboard</a></li>";
                        echo "<li><a class='dropdown-item' href='" . url('Pages/Admin/manageUsers.php') . "'>Manage Users</a></li>";
                        echo "<li><a class='dropdown-item' href='" . url('Pages/Admin/manageRooms.php') . "'>Manage Rooms</a></li>";
                        echo '<li><hr class="dropdown-divider"></li>';
                    }
                ?>
                <li><a class="dropdown-item" href="<?php echo url('configs/logout.php'); ?>">Logout</a></li>
             </ul>
         </div>
         <a href="<?php echo url('Pages/rooms.php#rooms'); ?>" class="btn" style="background-color: <?php echo $hotelTheme['colorPrimary']; ?>; color: white;">Book Now</a>
     </div>
 </nav>