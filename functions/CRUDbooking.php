<?php
    function CreateBooking(PDO $conn, int $userId, int $roomId, string $checkin, string $checkout, int $totalNights, float $totalPrice): array {
        try {
            // validate เบื้องต้น
            $d1 = DateTime::createFromFormat('Y-m-d', $checkin);
            $d2 = DateTime::createFromFormat('Y-m-d', $checkout);
            if (!$d1 || !$d2 || $d2 <= $d1 || $totalNights <= 0) {
                return ['success' => false, 'error' => 'ช่วงวันไม่ถูกต้อง'];
            }

            
            $sql = "INSERT INTO bookings (userId, roomId, checkIn, checkOut, totalNights, totalPrice, status)
                    VALUES ( :userId, :roomId, :checkIn, :checkOut, :totalNights, :totalPrice,
                            CASE
                                WHEN CURDATE() < :checkIn THEN 'upcoming'
                                WHEN CURDATE() >= :checkOut THEN 'checked_out'
                                ELSE 'staying'
                            END )";

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

    function UpdateBookingById(
        PDO $conn,
        int $bookingId,
        int $roomId,
        string $checkinStr,
        string $checkoutStr,
        float $totalPrice,
        ?string $bookingDateStr = null // optional: 'Y-m-d\TH:i' หรือ 'Y-m-d H:i:s'
    ): array {
        try {
            // แปลงเป็น DateTime จาก string
            // checkin/checkout ส่งมาแบบ 'Y-m-d'
            $checkin = DateTime::createFromFormat('Y-m-d', $checkinStr);
            $checkout = DateTime::createFromFormat('Y-m-d', $checkoutStr);

            if (!$checkin || !$checkout || $checkout <= $checkin) {
                return ['success' => false, 'error' => 'ช่วงวันไม่ถูกต้อง'];
            }

            // คำนวณจำนวนคืนเสมอ (อย่าใช้ค่าจากฟอร์ม)
            $totalNights = (int)$checkin->diff($checkout)->format('%a');
            if ($totalNights <= 0) {
                return ['success' => false, 'error' => 'จำนวนคืนไม่ถูกต้อง'];
            }

            // bookingDate: รับจาก datetime-local -> แปลงเป็น 'Y-m-d H:i:s'
            $bookingDateSql = null;
            if (!empty($bookingDateStr)) {
                // รับได้ทั้ง 'Y-m-d\TH:i' (จาก input) หรือ 'Y-m-d H:i:s' (จาก DB)
                $bd = DateTime::createFromFormat('Y-m-d\TH:i', $bookingDateStr)
                    ?: DateTime::createFromFormat('Y-m-d H:i:s', $bookingDateStr)
                    ?: DateTime::createFromFormat('Y-m-d', $bookingDateStr);
                if ($bd) $bookingDateSql = $bd->format('Y-m-d H:i:s');
            }

            $sql = "UPDATE bookings 
                    SET roomId = :roomId,
                        checkIn = :checkIn,
                        checkOut = :checkOut,
                        totalNights = :totalNights,
                        totalPrice = :totalPrice,
                        ".(!is_null($bookingDateSql) ? "bookingDate = :bookingDate," : "")."
                        status = CASE
                                    WHEN CURDATE() < :checkIn THEN 'upcoming'
                                    WHEN CURDATE() >= :checkOut THEN 'checked_out'
                                    ELSE 'staying'
                                END
                    WHERE bookingId = :bookingId";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':bookingId', $bookingId, PDO::PARAM_INT);
            $stmt->bindValue(':roomId', $roomId, PDO::PARAM_INT);
            $stmt->bindValue(':checkIn', $checkin->format('Y-m-d'), PDO::PARAM_STR);
            $stmt->bindValue(':checkOut', $checkout->format('Y-m-d'), PDO::PARAM_STR);
            $stmt->bindValue(':totalNights', $totalNights, PDO::PARAM_INT);
            $stmt->bindValue(':totalPrice', number_format($totalPrice, 2, '.', ''), PDO::PARAM_STR);
            if (!is_null($bookingDateSql)) {
                $stmt->bindValue(':bookingDate', $bookingDateSql, PDO::PARAM_STR);
            }

            return ['success' => $stmt->execute()];
        } catch (Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    function DeleteBookingById($conn, $bookingId) {
        $sql = "DELETE FROM bookings WHERE bookingId = :bookingId";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':bookingId', $bookingId, PDO::PARAM_INT);
        return $stmt->execute();
    }
?>