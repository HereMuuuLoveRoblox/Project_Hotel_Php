<?php
echo '<div class="container my-4">';
echo '  <div class="row g-5 justify-content-center">';

if (!empty($rooms)) {
    foreach ($rooms as $room) {
        echo '    <div class="col-12 col-sm-6 col-md-4 col-lg-3">';
        echo '      <div class="card h-100 room-card" style="width:100%;">';

        // รูปภาพ
        echo '        <div class="card-img-top" style="overflow:hidden; height:200px;">';
        echo '          <img src="../' . htmlspecialchars($room['rimgPath']) . '" ';
        echo '               alt="' . htmlspecialchars($room['roomName']) . '" ';
        echo '               style="width:100%; height:100%; object-fit:cover;">';
        echo '        </div>';

        // เนื้อหา
        echo '        <div class="card-body d-flex flex-column">';
        echo '          <h5 class="card-title mb-2">' . htmlspecialchars($room['roomName']) . '</h5>';
        echo '          <p class="card-text flex-grow-1">' . htmlspecialchars($room['roomDetail']) . '</p>';

        // ราคา (คั่นหลักพัน)
        echo '          <p class="card-text text-end fw-semibold mb-3">';
        echo '            THB ' . number_format((float)$room['roomPrice']) . ' / คืน';
        echo '          </p>';

        // ปุ่ม (ชิดล่างด้วย mt-auto)
        echo '          <a href="booking.php?roomId=' . htmlspecialchars($room['roomId']) . '#booking" ';
        echo '             class="btn mt-auto" ';
        echo '             style="background-color:' . $hotel_color_primary . '; color:#fff; width:100%;">';
        echo '             Book Now';
        echo '          </a>';

        echo '        </div>'; // .card-body
        echo '      </div>';   // .card
        echo '    </div>';     // .col
    }
} else {
    echo '    <div class="col-12 text-center text-muted">No rooms available.</div>';
}

echo '  </div>'; // .row
echo '</div>';   // .container
?>
