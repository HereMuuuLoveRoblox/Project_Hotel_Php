<div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">ชำระเงิน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                if (isset($_POST['checkin']) && isset($_POST['checkout'])) {
                    $checkin = $_POST['checkin'];
                    $checkout = $_POST['checkout'];
                    $date1 = new DateTime($checkin);
                    $date2 = new DateTime($checkout);
                    $interval = $date1->diff($date2);
                    $nights = $interval->days;

                    if ($nights > 0) {
                        $total_price = $nights * $room[0]['roomPrice'];
                        echo "<div class='d-flex justify-content-between align-items-center'>";
                        echo "<img src='../" . htmlspecialchars($room[0]['rimgPath']) . "' style='width: auto; height: 200px; object-fit: cover;' alt='...'>";
                        echo "<div class='justify-content-start w-100 mx-3'>";
                        echo "<p class='h3'>" . $room[0]['roomName'] . "</p>";
                        echo "<p>" . $room[0]['roomDetail'] . "</p>";
                        echo "<p>ราคา: $" . number_format((float)$room[0]['roomPrice']) . " / night</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "<hr>";
                        echo "<h5 class='d-flex justify-content-center align-items-center' style='font-size: 1.3rem; font-weight: bold; color: gray;'>รายละเอียดการจอง</h5>";
                        echo "<p>วันที่เช็คอิน: $checkin</p>";
                        echo "<p>วันที่เช็คเอาท์: $checkout</p>";
                        echo "<p>จำนวนคืน: $nights</p>";
                        echo "<p>ราคารวม: $" . number_format((float)$total_price) . "</p>";
                        echo "<img src='../images/example/qrcode.png' style='display: block; margin: auto; height: 300px; object-fit: cover; object-position: center;' alt=''>";
                        $error = false;
                    } else {
                        echo "<div class='alert alert-danger' role='alert'>
                            เกิดข้อผิดพลาด : วันที่เช็คเอาท์ต้องอยู่หลังวันที่เช็คอิน / ต้องจองห้องพักอย่างน้อย 1 คืน
                        </div>";
                        $error = true;
                    }
                } else {
                    echo "<div class='alert alert-danger' role='alert'>
                        กรุณากรอกข้อมูลให้ครบถ้วน
                    </div>";
                    $error = true;
                }
                ?>
            </div>
            <div class="modal-footer">
                <?php if (!isset($error) || !$error): ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button id="confirmPaymentBtn" type="button" class="btn"
                        style="background-color: <?= $hotel_color_primary ?>; color:white;">
                        ยืนยันการชำระเงิน
                    </button>
                <?php else: ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>