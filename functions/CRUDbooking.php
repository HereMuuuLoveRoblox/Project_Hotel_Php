<?php

    // INSERT INTO `bookings`(`bookingId`, `userId`, `roomId`, `checkIn`, `checkOut`, `totalNights`, `totalPrice`, `bookingDate`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]','[value-8]')

    function CreateBooking(PDO $conn, int $userId, int $roomId, string $checkin, string $checkout, int $totalNights, float $totalPrice): array {
        try {
            // validate เบื้องต้น
            $d1 = DateTime::createFromFormat('Y-m-d', $checkin);
            $d2 = DateTime::createFromFormat('Y-m-d', $checkout);
            if (!$d1 || !$d2 || $d2 <= $d1 || $totalNights <= 0) {
                return ['success' => false, 'error' => 'ช่วงวันไม่ถูกต้อง'];
            }

            $sql = "INSERT INTO bookings (userId, roomId, checkIn, checkOut, totalNights, totalPrice)
                    VALUES (:userId, :roomId, :checkIn, :checkOut, :totalNights, :totalPrice)";
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':roomId', $roomId, PDO::PARAM_INT);
            $stmt->bindValue(':checkIn', $checkin, PDO::PARAM_STR);
            $stmt->bindValue(':checkOut', $checkout, PDO::PARAM_STR);
            $stmt->bindValue(':totalNights', $totalNights, PDO::PARAM_INT);
            // DECIMAL แนะนำส่งเป็น string ที่ฟิกซ์ทศนิยม
            $stmt->bindValue(':totalPrice', number_format($totalPrice, 2, '.', ''), PDO::PARAM_STR);

            $ok = $stmt->execute();
            if (!$ok) {
                return ['success' => false, 'error' => 'Execute failed'];
            }
            return ['success' => true, 'bookingId' => (int)$conn->lastInsertId()];
        } catch (Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }


    
    
?>