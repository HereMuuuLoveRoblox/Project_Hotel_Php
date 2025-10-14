<?php

    // ดึงข้อมูลห้องพักทั้งหมด พร้อมรูปภาพที่ตั้งค่าให้แสดง (rimgShow = 1) , ถ้าไม่พบ (NULL) → จะดึงรูปแรกแทน
    function getAllRoomsAndImagesShow($conn)
    {   
        // ถ้าไม่พบ (NULL) → จะดึงรูปแรก (ORDER BY rimgId ASC LIMIT 1) แทน
        $stmt = $conn->prepare("SELECT rooms.roomId, rooms.roomName, rooms.roomDetail, rooms.roomPrice,
                                IFNULL(
                                        ( SELECT roomsimages.rimgPath FROM roomsimages WHERE roomsimages.roomId = rooms.roomId AND roomsimages.rimgShow = 1 LIMIT 1 ),
                                        ( SELECT roomsimages.rimgPath FROM roomsimages WHERE roomsimages.roomId = rooms.roomId ORDER BY roomsimages.rimgId ASC LIMIT 1 ) 
                                    ) AS rimgPath
                                FROM rooms");

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    // ดึงข้อมูลห้องพักทั้งหมด พร้อมรูปภาพที่ตั้งค่าให้แสดง (rimgShow = 1) , ถ้าไม่พบ (NULL) → จะดึงรูปแรกแทน
    function getLIMITRoomsAndImagesShow($conn, $limit)
    {   
        // ถ้าไม่พบ (NULL) → จะดึงรูปแรก (ORDER BY rimgId ASC LIMIT 1) แทน
        $stmt = $conn->prepare("SELECT rooms.roomId, rooms.roomName, rooms.roomDetail, rooms.roomPrice,
                                IFNULL(
                                        ( SELECT roomsimages.rimgPath FROM roomsimages WHERE roomsimages.roomId = rooms.roomId AND roomsimages.rimgShow = 1 LIMIT 1 ),
                                        ( SELECT roomsimages.rimgPath FROM roomsimages WHERE roomsimages.roomId = rooms.roomId ORDER BY roomsimages.rimgId ASC LIMIT 1 ) 
                                    ) AS rimgPath
                                FROM rooms
                                LIMIT :limit");

        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    function getRoomById($conn, $roomId) {
        $stmt = $conn->prepare(" SELECT rooms.roomId, rooms.roomName, rooms.roomDetail, rooms.roomPrice,
                                IFNULL(
                                    ( SELECT roomsimages.rimgPath FROM roomsimages WHERE roomsimages.roomId = rooms.roomId AND roomsimages.rimgShow = 1 LIMIT 1),
                                    ( SELECT roomsimages.rimgPath FROM roomsimages WHERE roomsimages.roomId = rooms.roomId ORDER BY roomsimages.rimgId ASC LIMIT 1)
                                ) AS rimgPath
                                FROM rooms
                                WHERE rooms.roomId = :roomId");
        $stmt->bindParam(':roomId', $roomId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    function getRoomImageById($conn, $roomId) {
        $stmt = $conn->prepare("SELECT roomsimages.rimgId, roomsimages.rimgPath, roomsimages.rimgShow
                                FROM roomsimages
                                WHERE roomsimages.roomId = :roomId
                                ORDER BY roomsimages.rimgShow DESC, roomsimages.rimgId ASC");
        $stmt->bindParam(':roomId', $roomId);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    function getRoomServicesById($conn, $roomId)
    {
        $stmt = $conn->prepare("SELECT roomservices.serviceId, roomservices.serviceName 
                                    FROM roomservices 
                                    WHERE roomservices.roomId = :roomId");
        $stmt->bindParam(':roomId', $roomId);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    function CreateRoom($conn, $roomName, $roomDetail, $roomPrice) {
        $stmt = $conn->prepare("INSERT INTO rooms (roomName, roomDetail, roomPrice) VALUES (:roomName, :roomDetail, :roomPrice)");
        $stmt->bindParam(':roomName', $roomName);
        $stmt->bindParam(':roomDetail', $roomDetail);
        $stmt->bindParam(':roomPrice', $roomPrice);
        $stmt->execute();
        return $conn->lastInsertId(); // คืนค่า roomId ที่เพิ่งสร้าง
    }
?>