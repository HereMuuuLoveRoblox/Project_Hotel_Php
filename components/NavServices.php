
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    
    <style>
        .color-mask {
            width: 40px;
            height: 40px;
            mask-size: contain;
            -webkit-mask-size: contain;
        }
    </style>
</head>
<body>
    <div class="container my-5 d-flex justify-content-around" id="services">
       

        <div class="d-flex align-items-center gap-3">
            <div class="color-mask"
                style="
                    background-color: <?= $hotel_color_primary ?>;
                    mask: url('../images/example/33x41.png') no-repeat center;
                    -webkit-mask: url('../images/example/33x41.png') no-repeat center;
                ">
            </div>
            <p class="mb-0">Wi-Fi Free</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="color-mask"
                style="
                    background-color: <?= $hotel_color_primary ?>;
                    mask: url('../images/example/33x41.png') no-repeat center;
                    -webkit-mask: url('../images/example/33x41.png') no-repeat center;
                ">
            </div>
            <p class="mb-0">Welcome Drink</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="color-mask"
                style="
                    background-color: <?= $hotel_color_primary ?>;
                    mask: url('../images/example/33x41.png') no-repeat center;
                    -webkit-mask: url('../images/example/33x41.png') no-repeat center;
                ">
            </div>
            <p class="mb-0">Car Rental Service</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="color-mask"
                style="
                    background-color: <?= $hotel_color_primary ?>;
                    mask: url('../images/example/33x41.png') no-repeat center;
                    -webkit-mask: url('../images/example/33x41.png') no-repeat center;
                ">
            </div>
            <p class="mb-0">Resort & Spa</p>
        </div>
    </div>
    