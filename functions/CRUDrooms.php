<?php

    function editRoom($conn, $roomId, $roomName, $roomDetail, $roomPrice, $roomCount)
    {
        $stmt = $conn->prepare("UPDATE rooms SET roomName = :roomName, roomDetail = :roomDetail, roomPrice = :roomPrice, roomCount = :roomCount WHERE roomId = :roomId");
        $stmt->bindParam(':roomName', $roomName);
        $stmt->bindParam(':roomDetail', $roomDetail);
        $stmt->bindParam(':roomPrice', $roomPrice);
        $stmt->bindParam(':roomCount', $roomCount);
        $stmt->bindParam(':roomId', $roomId);
        return $stmt->execute();
    }

    function setImageShow($conn, $rimgId, $roomId)
    {
        // เริ่มต้นด้วยการตั้งค่า rimgShow เป็น 0 สำหรับรูปภาพทั้งหมดของห้องนี้
        $stmt = $conn->prepare("UPDATE roomsimages SET rimgShow = 0 WHERE roomId = :roomId");
        $stmt->bindParam(':roomId', $roomId);
        $stmt->execute();

        // จากนั้นตั้งค่า rimgShow เป็น 1 สำหรับรูปภาพที่เลือก
        $stmt = $conn->prepare("UPDATE roomsimages SET rimgShow = 1 WHERE rimgId = :rimgId AND roomId = :roomId");
        $stmt->bindParam(':rimgId', $rimgId);
        $stmt->bindParam(':roomId', $roomId);
        return $stmt->execute();
    }

    function UpdateService($conn, $roomId, $services)
    {
        // ลบทั้งหมดของห้องนี้ก่อน
        $stmt = $conn->prepare("DELETE FROM roomservices WHERE roomId = :roomId");
        $stmt->execute([':roomId' => $roomId]);

        // แทรกใหม่ตามที่ส่งมา (ข้ามช่องว่าง)
        if (is_array($services)) {
            $ins = $conn->prepare("INSERT INTO roomservices (roomId, serviceName) VALUES (:roomId, :serviceName)");
            foreach ($services as $sv) {
                $sv = trim($sv ?? '');
                if ($sv === '') continue;
                $ins->execute([':roomId' => $roomId, ':serviceName' => $sv]);
            }
        }
    }

    function DeleteService($conn, $serviceId, $roomId)
    {
        $stmt = $conn->prepare("DELETE FROM roomservices WHERE serviceId = :serviceId AND roomId = :roomId");
        $stmt->bindParam(':serviceId', $serviceId);
        $stmt->bindParam(':roomId', $roomId);
        return $stmt->execute();
    }

    function UpdateImageRoom($conn, $new_name, $image_id)
    {
        $stmt = $conn->prepare("UPDATE roomsimages SET rimgPath = :rimgPath WHERE rimgId = :rimgId");
        $stmt->bindParam(':rimgPath', $new_name);
        $stmt->bindParam(':rimgId', $image_id);
        $stmt->execute();
    }

    function deleteImageFile($relativeName) {
        if (!$relativeName) return;
        $abs = "../../images/rooms/" . $relativeName;  // เก็บใน DB เป็นชื่อไฟล์อย่างเดียว
        if (is_file($abs)) { @unlink($abs); }
    }

    function AddNewImageRoom($conn, $roomId, $new_name)
    {
        $stmt = $conn->prepare("INSERT INTO roomsimages (roomId, rimgPath, rimgShow) VALUES (:roomId, :rimgPath, 0)");
        $stmt->bindParam(':roomId', $roomId);
        $stmt->bindParam(':rimgPath', $new_name);
        $stmt->execute();
    }

    function createImagetoFolder($folder)
    {
        if (!is_dir($folder)) {
            mkdir($folder, 0775, true);
        }
    }

    function getPathByImgIdAndRoomId($conn, $image_id, $roomId)
    {
        // ดึงไฟล์เก่ามาเก็บชื่อไว้ก่อน
        $old = $conn->prepare("SELECT rimgPath FROM roomsimages WHERE rimgId = :id AND roomId = :rid");
        $old->execute([':id' => $image_id, ':rid' => $roomId]);
        $oldRow = $old->fetch(PDO::FETCH_ASSOC);
        $oldName = $oldRow['rimgPath'] ?? null;
        return $oldName;
    }

    function DeleteRoom($conn, $roomId)
    {
        // ลบรูปภาพทั้งหมดของห้องนี้ (ไฟล์จริง)
        $stmt = $conn->prepare("SELECT rimgPath FROM roomsimages WHERE roomId = :roomId");
        $stmt->bindParam(':roomId', $roomId);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            deleteImageFile($row['rimgPath']);
        }

        // ลบข้อมูลในตาราง roomsimages
        $stmt = $conn->prepare("DELETE FROM roomsimages WHERE roomId = :roomId");
        $stmt->bindParam(':roomId', $roomId);
        $stmt->execute();

        // ลบข้อมูลในตาราง roomservices
        $stmt = $conn->prepare("DELETE FROM roomservices WHERE roomId = :roomId");
        $stmt->bindParam(':roomId', $roomId);
        $stmt->execute();

        // ลบข้อมูลในตาราง rooms
        $stmt = $conn->prepare("DELETE FROM rooms WHERE roomId = :roomId");
        $stmt->bindParam(':roomId', $roomId);
        return $stmt->execute();
    }
?>