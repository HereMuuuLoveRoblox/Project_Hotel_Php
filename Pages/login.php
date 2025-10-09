<?php
    session_start();

    include '../configs/Connect_DB.php';
    include '../configs/hotelTheme.php';

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
    function getUserIdByUserName($conn, $userName) {
        $stmt = $conn->prepare("SELECT userId, role FROM users WHERE userName = ?");
        $stmt->execute([$userName]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

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
    <div class="container border p-4 my-5" style="border-radius: 10px;" id="login">
        <h2 class="text-center mb-4" 
            style="font-size: 2rem; font-weight: bold; color: <?php echo $hotel_color_primary; ?>;">
            Login
        </h2>
        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userName = $_POST['userName'];
                $password = $_POST['password'];

                $result = userLogin($conn, $userName, $password);
                if ($result === true) {
                    echo '<div class="alert alert-success" role="alert">Login successful! You will be redirected shortly.</div>';
                    $user = getUserIdByUserName($conn, $userName);
                    $_SESSION['userId'] = $user['userId'];
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] === 'admin') {
                        header("Refresh: 1; url=adminpage.php");
                    } else {
                        header("Refresh: 1; url=homepage.php");
                    }
                } elseif (is_array($result)) {
                    foreach ($result as $error) {
                        echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($error) . '</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger" role="alert">An unexpected error occurred. Please try again later.</div>';
                }
            }
        ?>

        <form action="login.php" method="POST">
            
            
            <div class="mb-3">
                <label for="userName" class="form-label">User Name</label>
                <input type="text" class="form-control" id="userName" name="userName" required
                value="<?php echo htmlspecialchars($_POST['userName'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <p>Don't have an account? <a href="register.php" class="text-decoration-none fw-semibold" style="color: <?php echo $hotel_color_primary; ?>;">Register</a></p>
            
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-5 py-2 w-100"
                    style="background-color: <?php echo $hotel_color_primary; ?>; border: none; border-radius: 8px;">
                    Login
                </button>
            </div>
        </form>
    </div>
    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>