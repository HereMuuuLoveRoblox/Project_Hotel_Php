<?php
session_start(); // ต้องมีทุกหน้าที่ใช้ session
echo "<pre>";
print_r($_SESSION);  // หรือ var_dump($_SESSION);
echo "</pre>";
?>