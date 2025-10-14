
<div class="container my-4">
    <div class="row g-5 justify-content-center">
        <?php if (!empty($rooms)): ?>
            <?php foreach ($rooms as $room): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 room-card" style="width:100%;">
                        <!-- รูปภาพ -->
                        <div class="card-img-top" style="overflow:hidden; height:200px;">
                            <img src="../images/rooms/<?php echo htmlspecialchars($room['rimgPath']); ?>"
                                alt="<?php echo htmlspecialchars($room['roomName']); ?>"
                                style="width:100%; height:100%; object-fit:cover;">
                        </div>

                        <!-- เนื้อหา -->
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2"><?php echo htmlspecialchars($room['roomName']); ?></h5>
                            <p class="card-text flex-grow-1"><?php echo htmlspecialchars($room['roomDetail']); ?></p>

                            <!-- ราคา -->
                            <p class="card-text text-end fw-semibold mb-3">
                                THB <?php echo number_format((float)$room['roomPrice']); ?> / คืน
                            </p>

                            <!-- ปุ่ม -->
                            <a href="booking.php?roomId=<?php echo htmlspecialchars($room['roomId']); ?>#booking"
                            class="btn mt-auto"
                            style="background-color: <?php echo $hotelTheme['colorPrimary']; ?>; color:#fff; width:100%;">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center text-muted">No rooms available.</div>
        <?php endif; ?>
    </div>
</div>