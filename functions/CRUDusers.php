<?php
    function DeleteUserById($conn, $userId) {
        $stmt = $conn->prepare("DELETE FROM users WHERE userId = :userId");
        $stmt->bindParam(':userId', $userId);
        return $stmt->execute();
    }
    
    function UpdateUser($conn, $userId, $userName, $email, $role) {
        $stmt = $conn->prepare("UPDATE users SET userName = :userName, email = :email, role = :role WHERE userId = :userId");
        $stmt->bindParam(':userName', $userName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':userId', $userId);
        return $stmt->execute();
    }
?>