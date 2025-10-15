
<?php
    $Carousel_Image_1 = '../images/Carousel/C1.png';
    $Carousel_Image_2 = '../images/Carousel/C2.jpg';
    $Carousel_Image_3 = '../images/Carousel/C3.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>
<body>    

<Carousel id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
    
  <div class="carousel-inner">
    <div class="carousel-item active" style="height: 500px;">
      <img src="<?php echo $Carousel_Image_1; ?>" class="d-block w-100 object-fit-cover h-100" alt="...">
    </div>
    <div class="carousel-item" style="height: 500px;">
      <img src="<?php echo $Carousel_Image_2; ?>" class="d-block w-100 object-fit-cover h-100" alt="...">
    </div>
    <div class="carousel-item" style="height: 500px;">
      <img src="<?php echo $Carousel_Image_3; ?>" class="d-block w-100 object-fit-cover h-100" alt="...">
    </div>
  </div>
</Carousel>