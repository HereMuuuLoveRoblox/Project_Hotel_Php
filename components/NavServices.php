<?php
$icons = [
    '../images/Services/33x41.png',
    '../images/Services/33x41.png',
    '../images/Services/33x41.png',
    '../images/Services/33x41.png',
];

$Services = [
    'Wi-Fi Free',
    'Welcome Drink',
    'Car Rental Service',
    'Resort & Spa'
];
?>

<style>
    .color-mask {
        width: 40px;
        height: 40px;
        mask-size: contain;
        -webkit-mask-size: contain;
    }
</style>

<div class="container my-5 d-flex justify-content-around flex-wrap" id="services">
    <?php foreach ($Services as $index => $service): ?>
        <div class="d-flex align-items-center gap-3 my-2">
            <div class="color-mask"
                style="
                    background-color: <?= htmlspecialchars($hotelTheme['colorPrimary']); ?>;
                    mask: url('<?= htmlspecialchars($icons[$index]); ?>') no-repeat center;
                    -webkit-mask: url('<?= htmlspecialchars($icons[$index]); ?>') no-repeat center;
                ">
            </div>
            <p class="mb-0 fw-semibold"><?= htmlspecialchars($service); ?></p>
        </div>
    <?php endforeach; ?>
</div>
