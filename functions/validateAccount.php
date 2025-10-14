<?php
    // login
    function userLogin($conn, $userName, $password) {
        $stmt = $conn->prepare("SELECT password_hash, role FROM users WHERE userName = ?");
        $stmt->execute([$userName]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $error = [];
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $error[] = "Invalid username or password.";
        }
  
        if (!empty($error)) {
            return $error;
        }
        return true;
    }

    // register 
    function checkUserNameLength($userName) {
        return strlen($userName) >= 6;
    }
    function checkPasswordLength($password) {
        return strlen($password) >= 8;
    }
    function checkPasswordMatch($password, $confirm_password) {
        return $password === $confirm_password;
    }
    function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    // SQL check email exists
    function checkEmailExists($conn, $email) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }
    // SQL check username exists
    function checkUsernameExists($conn, $userName) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE userName = ?");
        $stmt->execute([$userName]);
        return $stmt->fetchColumn() > 0;
    }

    function userRegister($conn, $userName, $email, $password, $confirm_password) {
        $error = [];
        if (!checkUserNameLength($userName)) {
            $error[] = "Username must be at least 6 characters long.";
        }
        if (!checkPasswordLength($password)) {
            $error[] = "Password must be at least 8 characters long.";
        }
        if (!checkPasswordMatch($password, $confirm_password)) {
            $error[] = "Passwords do not match.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error[] = "Invalid email format.";
        }
        if (checkEmailExists($conn, $email)) {
            $error[] = "Email is already in use.";
        }
        if (checkUsernameExists($conn, $userName)) {
            $error[] = "Username is already taken.";
        }
        if (!empty($error)) {
            return $error;
        }
        try {
            $hashed_password = hashPassword($password);
            $stmt = $conn->prepare("INSERT INTO users (userName, email, password_hash) VALUES (?, ?, ?)");
            $ok = $stmt->execute([$userName, $email, $hashed_password]);
            return $ok === true ? true : ["Failed to insert user. Please try again."];
        } catch (Throwable $e) {
            // ถ้ามี unique constraint จะมาชนกรณีนี้
            return ["Database error: " . $e->getMessage()];
        }
    }
?>