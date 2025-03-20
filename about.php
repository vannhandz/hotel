<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/link.php') ?>
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo $settings_r['site_title'] ?> ABOUT</title>
</head>

<body class="bg-light">

    <?php require('inc/header.php') ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">GIỚI THIỆU</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
        Đội ngũ nhân viên khách sạn của chúng tôi là yếu tố then chốt tạo nên trải nghiệm tuyệt vời cho khách hàng. <br>
        Họ được tuyển chọn và đào tạo kỹ lưỡng, thể hiện sự chuyên nghiệp, tận tâm và chu đáo trong mọi hành động.
        </p>
    </div>

    <div class="container ">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1  order-2">
                <h3 class="mb-3">Người Đứng Đầu</h3>
                <p>Với tư cách là người đứng đầu khách sạn, tôi sẽ tập trung vào việc tạo ra trải nghiệm khách hàng vượt trội, 
                    xây dựng đội ngũ nhân viên chuyên nghiệp, quản lý tài chính hiệu quả, củng cố thương hiệu và đảm bảo an toàn, tuân thủ. 
                    Mục tiêu của tôi là đưa khách sạn trở thành điểm đến hàng đầu, nơi khách hàng cảm thấy như ở nhà và nhân viên tự hào cống hiến.
                </p>
            </div>
            <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
                <img src="images/about/leader.jpg" class="w-100">
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/hotel.svg" width="70px">
                    <h4 class="mt-3">100+ PHÒNG</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/customer.svg" width="70px">
                    <h4 class="mt-3">200+ KHÁCH HÀNG</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/review.svg" width="70px">
                    <h4 class="mt-3">150+ ĐÁNH GIÁ</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/staff.svg" width="70px">
                    <h4 class="mt-3">100+ NHÂN VIÊN</h4>
                </div>
            </div>
        </div>
    </div>

    <h3 class="my-5 fw-bold h-font text-center">ĐỘI NGŨ QUẢN LÝ</h3>

    <div class="container px-4">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper mb-5">
                <?php
                    $about_r= selectAll('team_details');
                    $path=ABOUT_IMG_PATH;
                    while($row=mysqli_fetch_assoc($about_r)){
                        echo<<<data
                            <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                                <img src="$path$row[picture]" class="w-100">
                                <h5 class="mt-2">$row[name]</h5>
                            </div>
                        data;
                    }
                ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>


    <?php require('inc/footer.php') ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 40,
            pagination: {
                el: ".swiper-pagination",
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 3,
                }
            },
        });
    </script>
</body>

</html>