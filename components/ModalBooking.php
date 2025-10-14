<div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">ชำระเงิน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <?php
                    if ($nights > 0):
                        $total_price = $nights * $room[0]['roomPrice'];
                        $error = false; ?>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="../images/rooms/<?= htmlspecialchars($room[0]['rimgPath']); ?>"
                                 class="rounded shadow"
                                 alt="<?= htmlspecialchars($room[0]['roomName']); ?>"
                                 style="width: 200px; height: 200px; object-fit: cover;">
                            <div class="justify-content-start w-100 mx-3">
                                <p class="h3 mb-1"><?= htmlspecialchars($room[0]['roomName']); ?></p>
                                <p class="mb-2"><?= htmlspecialchars($room[0]['roomDetail']); ?></p>
                                <p>ราคา: $<?= number_format((float)$room[0]['roomPrice']); ?> / คืน</p>
                            </div>
                        </div>

                        <hr>

                        <h5 class="text-center fw-bold" style="font-size: 1.3rem; color: gray;"> รายละเอียดการจอง</h5>

                        <p>วันที่เช็คอิน: <?= htmlspecialchars($checkin); ?></p>
                        <p>วันที่เช็คเอาท์: <?= htmlspecialchars($checkout); ?></p>
                        <p>จำนวนคืน: <?= htmlspecialchars($nights); ?></p>
                        <p>ราคารวม: $<?= number_format((float)$total_price); ?></p>

                        <img src="../images/example/qrcode.png"
                            alt="QR Code สำหรับการชำระเงิน"
                            style="display: block; margin: auto; height: 300px; object-fit: cover; object-position: center;">
                        <p class="text-center mt-3">สแกน QR Code เพื่อชำระเงิน</p>
                <?php
                    else:
                        $error = true;
                ?>
                        <div class="alert alert-danger" role="alert">เกิดข้อผิดพลาด : วันที่เช็คเอาท์ต้องอยู่หลังวันที่เช็คอิน / ต้องจองห้องพักอย่างน้อย 1 คืน</div>
                <?php endif; ?>
            </div>
            <!-- Footer -->
            <div class="modal-footer justify-content-end">
                <?php if (!isset($error) || !$error): ?>
                    <button id="confirmPaymentBtn" type="button" class="btn"
                            style="background-color: <?= htmlspecialchars($hotelTheme['colorPrimary']); ?>; color: white;">
                        ยืนยันการชำระเงิน
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <?php else: ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>