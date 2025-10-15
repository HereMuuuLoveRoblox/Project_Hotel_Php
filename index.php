<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<?php
    session_start();
     // เช็คว่า user login แล้วหรือยัง
    if (!isset($_SESSION['userId']) || empty($_SESSION['role'])) {
        header("Location: Pages/login.php");
        exit();
    }else{
        header("Location: Pages/homepage.php");
    }
?>
</body>
</html>