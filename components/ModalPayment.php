<?php
    // Modal Payment Success

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $action = $_POST['action'] ?? '';

        if ($action === 'confirm_payment' && isset($_POST['userId'] ) && isset($_POST['roomId']) && isset($_POST['checkin']) && isset($_POST['checkout']) && isset($_POST['totalNight']) && isset($_POST['totalPrice'])) {
            $userId = (int)$_POST['userId'];
            $roomId = (int)$_POST['roomId'];
            $checkin = $_POST['checkin'];
            $checkout = $_POST['checkout'];
            $totalNight = (int)$_POST['totalNight'];
            $totalPrice = (float)$_POST['totalPrice'];

            CreateBooking($conn, $userId, $roomId, $checkin, $checkout, $totalNight, $totalPrice);
            
            echo "<script>window.location.href = 'homepage.php';</script>";
            exit();
        }
    }

?>
<div class="modal fade" id="paymentSuccessModal" tabindex="-1" aria-labelledby="paymentSuccessLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-3">
        <div class="modal-body">
            <img src="../images/example/check.gif" alt="success" style="width:80px; margin:20px auto;">
            <h4 class="text-success fw-bold mt-3">ชำระเงินสำเร็จ!</h4>
            <p>ขอบคุณที่ใช้บริการของเรา</p>
        </div>
        <div class="modal-footer justify-content-center">
            <form action="" method="post">
                <input type="hidden" name="action" value="confirm_payment">
                <input type="hidden" name="userId" value="<?php echo htmlspecialchars($user['userId']); ?>">
                <input type="hidden" name="roomId" value="<?php echo htmlspecialchars($room[0]['roomId']); ?>">
                <input type="hidden" name="checkin" value="<?php echo htmlspecialchars($checkin); ?>">
                <input type="hidden" name="checkout" value="<?php echo htmlspecialchars($checkout); ?>">
                <input type="hidden" name="totalNight" value="<?php echo htmlspecialchars($nights); ?>">
                <input type="hidden" name="totalPrice" value="<?php echo htmlspecialchars($total_price); ?>">
                <button type="submit" class="btn btn-success" data-bs-dismiss="modal">ตกลง</button>
            </form>
        </div>
        </div>
    </div>
</div>