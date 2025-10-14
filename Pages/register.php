<?php
    session_start();

    include '../configs/Connect_DB.php';
    include '../configs/basefile.php'; //-- baseUrl, basePath

    include '../functions/HotelTheme.php';
    $hotelTheme = getHotelTheme($conn);
    
    include '../functions/getUsers.php';
    include '../functions/validateAccount.php';
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
            <p class="h2 text-decoration-none" style="white-space: nowrap; display: inline-block; color: <?php echo $hotelTheme['colorPrimary']; ?>;"><?php echo $hotelTheme['hotelName']; ?></p>
        </div>
        <?php include '../components/NavServices.php'; ?>
    </nav>
    <!-- End Navbar -->
    <div class="container border p-4 my-5" style="border-radius: 10px;" id="register">
        <h2 class="text-center mb-4" 
            style="font-size: 2rem; font-weight: bold; color: <?php echo $hotelTheme['colorPrimary']; ?>;">
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
            
            <p>Already have an account? <a href="login.php" class="text-decoration-none fw-semibold" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;">Login</a></p>
            
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-5 py-2 w-100"
                    style="background-color: <?php echo $hotelTheme['colorPrimary']; ?>; border: none; border-radius: 8px;">
                    Register
                </button>
            </div>
        </form>
    </div>
    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>