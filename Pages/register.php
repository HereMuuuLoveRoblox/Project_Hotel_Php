<?php
    session_start();

    include '../configs/Connect_DB.php';
    include '../configs/hotelTheme.php';

    // check userName length
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

    function getUserIdByEmail($conn, $email) {
        $stmt = $conn->prepare("SELECT userId, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav  class="d-flex flex-column justify-content-center container py-3 align-items-center border my-3" style="border-radius: 10px;">
        <div class="nav-left">
            <p class="h2 text-decoration-none" style="white-space: nowrap; display: inline-block; color: <?php echo $hotel_color_primary; ?>;"><?php echo $hotel_name; ?></p>
        </div>
        <?php include '../components/NavServices.php'; ?>
    </nav>
    <!-- End Navbar -->
    <div class="container border p-4 my-5" style="border-radius: 10px;" id="register">
        <h2 class="text-center mb-4" 
            style="font-size: 2rem; font-weight: bold; color: <?php echo $hotel_color_primary; ?>;">
            Register
        </h2>
        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userName = $_POST['userName'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];

                $result = userRegister($conn, $userName, $email, $password, $confirm_password);
                if ($result === true) {
                    echo '<div class="alert alert-success" role="alert">Registration successful! You can now <a href="login.php" class="alert-link">login</a>.</div>';
                    $user = getUserIdByEmail($conn, $email);
                    $_SESSION['userId'] = $user['userId'];
                    $_SESSION['role'] = $user['role'];
                    header("Refresh: 1; url=homepage.php");
                } elseif (is_array($result)) {
                    foreach ($result as $error) {
                        echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($error) . '</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger" role="alert">An unexpected error occurred. Please try again later.</div>';
                }
            }
        ?>

        <form action="register.php" method="POST">
            
            
            <div class="mb-3">
                <label for="userName" class="form-label">User Name</label>
                <input type="text" class="form-control" id="userName" name="userName" required
                value="<?php echo htmlspecialchars($_POST['userName'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required
                value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-4">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            
            <p>Already have an account? <a href="login.php" class="text-decoration-none fw-semibold" style="color: <?php echo $hotel_color_primary; ?>;">Login</a></p>
            
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-5 py-2 w-100"
                    style="background-color: <?php echo $hotel_color_primary; ?>; border: none; border-radius: 8px;">
                    Register
                </button>
            </div>
        </form>
    </div>
    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>