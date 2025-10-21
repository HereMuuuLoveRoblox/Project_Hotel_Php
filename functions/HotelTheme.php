<?php
    
    function getHotelTheme($conn) {
        $stmt = $conn->prepare("SELECT themeId, hotelName, colorPrimary, colorSecondary
                            FROM hotelTheme
                            WHERE themeId = 1");

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }
?>