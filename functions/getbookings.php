<?php

    function getAllBookings($conn)
    {   
        // ถ้าไม่พบ (NULL) → จะดึงรูปแรก (ORDER BY rimgId ASC LIMIT 1) แทน
        $stmt = $conn->prepare("SELECT bookings.bookingId, bookings.userId, bookings.roomId, bookings.checkIn, bookings.checkOut, bookings.totalNights, bookings.totalPrice, bookings.bookingDate,  bookings.status,
                                users.userName, rooms.roomName
                                FROM bookings 
                                JOIN users ON bookings.userId = users.userId 
                                JOIN rooms ON bookings.roomId = rooms.roomId 
                                ORDER BY bookings.bookingId ASC");

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    function getAllBookingsDashboard($conn)
    {   
        // ถ้าไม่พบ (NULL) → จะดึงรูปแรก (ORDER BY rimgId ASC LIMIT 1) แทน
        $stmt = $conn->prepare("SELECT bookings.bookingId, bookings.userId, bookings.roomId, bookings.checkIn, bookings.checkOut, bookings.totalNights, bookings.totalPrice, bookings.bookingDate,  bookings.status,
                                users.userName, rooms.roomName
                                FROM bookings 
                                JOIN users ON bookings.userId = users.userId 
                                JOIN rooms ON bookings.roomId = rooms.roomId 
                                ORDER BY bookings.status ASC, bookings.bookingDate DESC");

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    function getBookingById($conn, $bookingId)
    {
        $stmt = $conn->prepare("SELECT bookings.bookingId, bookings.userId, bookings.roomId, bookings.checkIn, bookings.checkOut, bookings.totalNights, bookings.totalPrice, bookings.bookingDate,  bookings.status,
                                users.userName, rooms.roomName
                                FROM bookings 
                                JOIN users ON bookings.userId = users.userId 
                                JOIN rooms ON bookings.roomId = rooms.roomId 
                                WHERE bookings.bookingId = :bookingId");
        $stmt->bindValue(':bookingId', $bookingId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    function getBookingsByUserId($conn, $userId){
        $stmt = $conn->prepare("SELECT bookings.bookingId, bookings.userId, bookings.roomId, bookings.checkIn, bookings.checkOut, bookings.totalNights, bookings.totalPrice, bookings.bookingDate,  bookings.status,
                                users.userName, rooms.roomName
                                FROM bookings 
                                JOIN users ON bookings.userId = users.userId 
                                JOIN rooms ON bookings.roomId = rooms.roomId 
                                WHERE bookings.userId = :userId
                                ORDER BY bookings.bookingDate DESC");
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }
    

    function countActiveBookingsForRoomById(PDO $conn, int $roomId): int {
        $sql = "SELECT COUNT(*) 
                FROM bookings 
                WHERE roomId = :roomId 
                AND status IN ('staying','upcoming')";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':roomId', $roomId, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn(); // ถ้าไม่มีผลลัพธ์จะได้ 0
    }

    function getStatusCountAllRooms(PDO $conn): array {
        $sql = "SELECT COUNT(bookings.status = 'upcoming' OR NULL) AS upcoming,
                       COUNT(bookings.status = 'staying' OR NULL) AS staying,
                       COUNT(bookings.status = 'checked_out' OR NULL) AS checked_out
                FROM bookings";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

?>