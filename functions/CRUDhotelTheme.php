<?php

    function updateTheme(PDO $conn, int $themeId, string $hotelName, string $colorPrimary, string $colorSecondary)
    {
        $stmt = $conn->prepare("UPDATE hotelTheme SET hotelName = :hotelName, colorPrimary = :colorPrimary, colorSecondary = :colorSecondary WHERE themeId = :themeId");
        $stmt->bindParam(':hotelName', $hotelName);
        $stmt->bindParam(':colorPrimary', $colorPrimary);
        $stmt->bindParam(':colorSecondary', $colorSecondary);
        $stmt->bindParam(':themeId', $themeId);
        return $stmt->execute();
    }
?>