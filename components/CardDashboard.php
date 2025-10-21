<style>
    .card {
        border-radius: 0.5rem;
        transition: transform 0.5s;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.);
    }
    .text-xs {
        font-size: 0.75rem;
    }
    .font-weight-bold {
        font-weight: 700;
    }
    .h5 {
        font-size: 1.25rem;
    }
    .bi {
        font-size: 2rem;
    }
</style>
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow-sm h-100 py-2" style="border-left: 0.25rem solid <?php echo $hotelTheme['colorPrimary']; ?> !important;">
            <div class="card-body">
                <div class="row mx-0 px-0 align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;">
                            รายได้ทั้งหมด</div>
                        <div class="h5 mb-0 font-weight-bold text-black"><?php echo array_sum(array_column($bookings, 'totalPrice')); ?> บาท</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-bank2 fs-3" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;"></i>
                    </div>
                </div>
            </div>
        </div>  
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow-sm h-100 py-2" style="border-left: 0.25rem solid <?php echo $hotelTheme['colorPrimary']; ?> !important;">
            <div class="card-body">
                <div class="row mx-0 px-0 align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;">
                            รายได้เฉลี่ย</div>
                        <div class="h5 mb-0 font-weight-bold text-black"><?php echo number_format(array_sum(array_column($bookings, 'totalPrice')) / count($bookings), 2); ?> บาท</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-cash fs-3" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow-sm h-100 py-2" style="border-left: 0.25rem solid <?php echo $hotelTheme['colorPrimary']; ?> !important;">
            <div class="card-body">
                <div class="row mx-0 px-0 align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;">จำนวนผู้ใช้งาน</div>
                        <div class="h5 mb-0 font-weight-bold text-black"><?php echo number_format(count(array_unique(array_column($bookings, 'userId'))), 0); ?> คน</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people fs-3" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow-sm h-100 py-2" style="border-left: 0.25rem solid <?php echo $hotelTheme['colorPrimary']; ?> !important;">
            <div class="card-body">
                <div class="row mx-0 px-0 align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;">จำนวนคืนเฉลี่ย</div>
                        <div class="h5 mb-0 font-weight-bold text-black"><?php echo number_format(array_sum(array_column($bookings, 'totalNights')) / count($bookings), 0); ?> คืน</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-moon-stars fs-3" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow-sm h-100 py-2" style="border-left: 0.25rem solid <?php echo $hotelTheme['colorPrimary']; ?> !important;">
            <div class="card-body">
                <div class="row mx-0 px-0 align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;">
                            ข้อมูลการจองทั้งหมด</div>
                        <div class="h5 mb-0 font-weight-bold text-black"><?php echo count($bookings); ?> รายการ</div>

                    </div>
                    <div class="col-auto">
                        <i class="bi bi-journal-check fs-3" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow-sm h-100 py-2" style="border-left: 0.25rem solid <?php echo $hotelTheme['colorPrimary']; ?> !important;">
            <div class="card-body">
                <div class="row mx-0 px-0 align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;">ยังไม่เข้าพัก</div>
                        <div class="h5 mb-0 font-weight-bold text-black"><?php echo $statusCounts['upcoming']; ?> รายการ</div>

                    </div>
                    <div class="col-auto">
                        <i class="bi bi-journal-minus fs-3" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow-sm h-100 py-2" style="border-left: 0.25rem solid <?php echo $hotelTheme['colorPrimary']; ?> !important;">
            <div class="card-body">
                <div class="row mx-0 px-0 align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;">กำลังเข้าพัก</div>
                        <div class="h5 mb-0 font-weight-bold text-black"><?php echo $statusCounts['staying']; ?> รายการ</div>

                    </div>
                    <div class="col-auto">
                        <i class="bi bi-journal-arrow-down fs-3" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow-sm h-100 py-2" style="border-left: 0.25rem solid <?php echo $hotelTheme['colorPrimary']; ?> !important;">
            <div class="card-body">
                <div class="row mx-0 px-0 align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;">เช็คเอาท์แล้ว</div>
                        <div class="h5 mb-0 font-weight-bold text-black"><?php echo $statusCounts['checked_out']; ?> รายการ</div>

                    </div>
                    <div class="col-auto">
                        <i class="bi bi-journal-x fs-3" style="color: <?php echo $hotelTheme['colorPrimary']; ?>;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>