<?php
    function getHotelTheme($conn) {
        $stmt = $conn->prepare("SELECT hotelName, colorPrimary, colorSecondary
                                FROM hotelTheme
                                WHERE themeId = 1");

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }
    $theme = getHotelTheme($conn);
    if ($theme) {
        $hotel_name = $theme[0]['hotelName'];
        $hotel_color_primary = $theme[0]['colorPrimary'];
        $hotel_color_secondary = $theme[0]['colorSecondary'];
    }
?>