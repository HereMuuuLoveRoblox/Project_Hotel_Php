<?php
    $line_animation_CSS = 'text-decoration-none text-dark hover-underline-animation text-decoration-none fw-semibold text-dark';
?>
<style>
    .hover-underline-animation {
        position: relative;
        display: inline-block;
        color: inherit;
    }
    .hover-underline-animation::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -3px;
        width: 0;
        height: 2px;
        background-color: <?php echo $hotelTheme['colorPrimary']; ?>;
        transition: width 0.3s ease-in-out;
    }
    .hover-underline-animation:hover::after {
        width: 100%;
    }
    /* Navbar ที่ fixed */
    .fixedNav {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
        opacity: 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transform: translateY(-100%);
        transition: all 0.8s ease;
    }
    /* แสดงเมื่อ scroll */
    .fixedNav.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div id="mainNav">
    <?php include 'Navbar-body.php'; ?>
</div>
<div id="fixedNav" class="fixedNav bg-white">
    <?php include 'Navbar-body.php'; ?>
</div>
<script src="<?php echo url('js/Navbar.js'); ?>"></script>