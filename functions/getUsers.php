<?php

    // ดึงข้อมูลผู้ใช้ตาม userId
    function getUserById($conn, $userId)
    {
        $stmt = $conn->prepare("SELECT userId, userName, email, role FROM users WHERE userId = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getUserIdByUserName($conn, $userName) {
        $stmt = $conn->prepare("SELECT userId, role FROM users WHERE userName = ?");
        $stmt->execute([$userName]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getUserIdByEmail($conn, $email) {
        $stmt = $conn->prepare("SELECT userId, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getAllUsers($conn) {
        $stmt = $conn->prepare("SELECT userId, userName, email, role FROM users ORDER BY userId ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
?>