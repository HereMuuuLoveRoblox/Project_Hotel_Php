<?php
    $Services = [
        '../images/Services/wifi.png' => 'Wi-Fi Free',
        '../images/Services/welcome_drink.png' => 'Welcome Drink',
        '../images/Services/car_rental.png' => 'Car Rental Service',
        '../images/Services/spa.png' => 'Resort & Spa'
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
    <?php foreach ($Services as $path => $service): ?>
        <div class="d-flex align-items-center gap-3 my-2">
            <div class="color-mask"
                style="
                    background-color: <?= htmlspecialchars($hotelTheme['colorPrimary']); ?>;
                    mask: url('<?= htmlspecialchars($path); ?>') no-repeat center;
                    -webkit-mask: url('<?= htmlspecialchars($path); ?>') no-repeat center;
                ">
            </div>
            <p class="mb-0 fw-semibold"><?= htmlspecialchars($service); ?></p>
        </div>
    <?php endforeach; ?>
</div>
