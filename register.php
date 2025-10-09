<?php
    session_start(); // ต้องมีทุกหน้าที่จะใช้ session

    function checkusernamelength($username) {
        return strlen($username) >= 5;
    }
    function checkpasswordlength($password) {
        return strlen($password) >= 8;
    }

    function checkpasswordmatch($password, $confirm_password) {
        return $password === $confirm_password;
    }
    
    function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    function checkEmailExists($conn, $email) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    function checkUsernameExists($conn, $username) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }

    function userRegister($conn, $username, $password, $confirm_password, $email) {

        if (!checkusernamelength($username)) {
            return [
                "status" => "error",
                "message" => "Username must be at least 5 characters long."
            ];
        }
        
        if (!checkpasswordlength($password)) {
            return [
                "status" => "error",
                "message" => "Password must be at least 8 characters long."
            ];
        }
        if (!checkpasswordmatch($password, $confirm_password)) {
            return [
                "status" => "error",
                "message" => "Passwords do not match."
            ];
        }
        if (checkusernameExists($conn, $username)) {
            return [
                "status" => "error",
                "message" => "Username already taken."
            ];
        }
        if (checkemailExists($conn, $email)) {
            return [
                "status" => "error",
                "message" => "Email already registered."
            ];
        }
        $hashed_password = hashPassword($password);
        $stmt = $conn->prepare("INSERT INTO users (username, password_hash, email) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hashed_password, $email]);
        return [
            "status" => "success",
            "message" => "User registered successfully."
        ];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="login-register.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form action="register.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <p>Already have an account? <a href="login.php">Login</a></p>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <?php
            include 'Connect_DB.php';
            
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
                $email = $_POST['email'];

                
                $result = userRegister($conn, $username, $password, $confirm_password, $email);
                if ($result['status'] === 'success') {
                    echo "<div class='alert alert-success' role='alert'>" . $result['message'] . "</div>";
                    header("refresh:1;url=login.php");
                } else {
                    echo "<div class='alert alert-danger' role='alert'>" . $result['message'] . "</div>";
                }
            }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>