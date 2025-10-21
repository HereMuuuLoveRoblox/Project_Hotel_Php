<style>
    .room-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }
    .room-card:hover {
        transform: translateY(-2px);
        z-index: 10; /* ป้องกันบังกันเวลาขยาย */
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }
</style>
<div class="container my-4">
    <div class="row g-5 justify-content-center">
        <?php if (!empty($rooms)): ?>
            <?php foreach ($rooms as $room): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 ">
                    <div class="card h-100 room-card" style="width:100%;">

                        <?php if (!empty($room['rimgPath'])): ?>
                            <!-- รูปภาพ -->
                            <div class="card-img-top" style="overflow:hidden; height:200px;">
                                <img src="../images/rooms/<?php echo htmlspecialchars($room['rimgPath']); ?>"
                                    alt="<?php echo htmlspecialchars($room['roomName']); ?>"
                                    style="width:100%; height:100%; object-fit:cover;">
                            </div>
                        <?php else: ?>
                            <div class="card-img-top" style="overflow:hidden; height:200px;">
                                <img src="https://placehold.co/280x200"
                                    alt="<?php echo htmlspecialchars($room['roomName']); ?>"
                                    style="width:100%; height:100%; object-fit:cover;">
                            </div>
                        <?php endif; ?>

                        <!-- เนื้อหา -->
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <h5 class="card-title mb-0 me-2 flex-grow-1 text-truncate">
                                    <?= htmlspecialchars($room['roomName']) ?>
                                </h5>
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                    style="min-width:25px;height:25px;padding:0 6px;
                                        background-color: <?= $hotelTheme['colorPrimary'] ?>; color:#fff;
                                        font-size:12px;font-weight:600;line-height:1;">
                                    <?= htmlspecialchars(getRoomAvailableCount($conn, (int)$room['roomId'])) ?>
                                </span>
                            </div>
                            
                            <!-- รายละเอียดห้อง -->
                            <p class="card-text flex-grow-1"><?php echo htmlspecialchars(limitCharacters($room['roomDetail'], 100)); ?></p>

                            <!-- ราคา -->
                            <p class="card-text fw-semibold text-end">
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