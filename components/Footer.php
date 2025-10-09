<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
    </style>
</head>

<body>
    <footer class="" style="background-color: <?php echo $hotel_color_secondary; ?>;" id="contact">
        <div class="container py-5">
            <div class="d-flex justify-content-between align-items-center">
                <p class="mb-0">&copy; <?php echo date("Y"); ?> <?php echo $hotel_name; ?>. All rights reserved.</p>
                <ul class="list-unstyled d-flex mb-0">
                    <li class="ms-3"><a href="" class="<?php echo $link_css; ?>">Privacy Policy</a></li>
                    <li class="ms-3"><a href="" class="<?php echo $link_css; ?>">Terms of Service</a></li>
                </ul>
            </div>
        </div>
    </footer>