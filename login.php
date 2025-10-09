<?php
    session_start(); // ต้องมีทุกหน้าที่จะใช้ session
    function userLogin($conn, $email, $password) {
        $stmt = $conn->prepare("SELECT password_hash, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            // ตั้งค่า session เมื่อ login สำเร็จ
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $user['role']; // เปลี่ยนเป็นค่าจริงจากฐานข้อมูล
            return [
                "status" => "success",
                "message" => "Login successful."
            ];
        } else {
            return [
                "status" => "error",
                "message" => "Invalid email or password."
            ];
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="login-register.css">
</head>
<body>

    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="text" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <p>Don't have an account? <a href="register.php">Register</a></p>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <?php
            include 'Connect_DB.php';
            
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_POST['email'];
                $password = $_POST['password'];

                $result = userLogin($conn, $email, $password);
                if ($result['status'] === 'success') {

                    echo "<div class='alert alert-success' role='alert'>" . $result['message'] . "</div>";
                    header("refresh:1;url=index.php");
                    exit();
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