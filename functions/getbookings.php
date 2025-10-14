<?php

    function getAllBookings($conn)
    {   
        // ถ้าไม่พบ (NULL) → จะดึงรูปแรก (ORDER BY rimgId ASC LIMIT 1) แทน
        $stmt = $conn->prepare("SELECT bookings.bookingId, bookings.userId, bookings.roomId, bookings.checkIn, bookings.checkOut, bookings.totalNights, bookings.totalPrice, bookings.bookingDate, 
                                users.userName, rooms.roomName,
                                CASE
                                    WHEN CURDATE() <  bookings.checkIn  THEN 'upcoming'  -- ยังไม่ถึงวันเข้าพัก
                                    WHEN CURDATE() >= bookings.checkIn AND CURDATE() < bookings.checkOut    THEN 'in_stay'   -- กำลังเข้าพัก
                                    WHEN CURDATE() >= bookings.checkOut THEN 'closed'    -- เช็คเอาท์แล้ว
                                END AS status
                                FROM bookings 
                                JOIN users ON bookings.userId = users.userId 
                                JOIN rooms ON bookings.roomId = rooms.roomId 
                                ORDER BY bookings.bookingId DESC");

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }
?>