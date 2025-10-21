<?php
  $Carousel_Image_1 = '../images/HomepageImg/C1.png';
  $Carousel_Image_2 = '../images/HomepageImg/C2.png';
  $Carousel_Image_3 = '../images/HomepageImg/C3.png';
?>

<Carousel id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">

  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="<?php echo $Carousel_Image_1; ?>" class="d-block w-100 object-fit-cover h-auto" alt="...">
    </div>
    <div class="carousel-item">
      <img src="<?php echo $Carousel_Image_2; ?>" class="d-block w-100 object-fit-cover h-auto" alt="...">
    </div>
    <div class="carousel-item">
      <img src="<?php echo $Carousel_Image_3; ?>" class="d-block w-100 object-fit-cover h-auto" alt="...">
    </div>
  </div>
</Carousel>