<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/link.php') ?>
    <title><?php echo $settings_r['site_title'] ?> HOME</title>
</head>

<body class="bg-light">

    <?php require('inc/header.php') ?>

    <!-- carousel -->
    <div class="container-fluid px-lg-4 mt-4">
        <div class="swiper swiper-container">
            <div class="swiper-wrapper">
                <?php
                $res = selectAll('carousel');
                while ($row = mysqli_fetch_assoc($res)) {
                    $path = CAROUSEL_IMG_PATH;
                    echo <<<data
                            <div class="swiper-slide">
                                <img src="$path$row[image]" class="w-100 d-block" />
                            </div>
                         data;
                }
                ?>
            </div>
        </div>

        <!-- check availability form -->
        <div class="container availability-form">
            <div class="row">
                <div class="col-lg-12 bg-white shadow p-4 rounded">
                    <h5 class="mb-4">Kiểm Tra Phòng Trống</h5>
                    <form action="rooms.php">
                        <div class="row align-items-end">
                            <div class="col-lg-3 mb-3">
                                <label class="form-label" style="font-weight:500;">Check-in</label>
                                <input type="date" class="form-control shadow-none" name="checkin" required>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label class="form-label" style="font-weight:500;">Check-out</label>
                                <input type="date" class="form-control shadow-none" name="checkout" required>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label class="form-label" style="font-weight:500;" >Người Lớn</label>
                                <select class="form-select shadow-none" name="adult"> 
                                <?php
                                    $guests_q = mysqli_query($con,"SELECT MAX(adult) AS `max_adult`, MAX(children) AS `max_children`
                                        FROM `rooms` WHERE `status`='1' AND `removed`='0'");
                                       
                                       $guests_res = mysqli_fetch_assoc($guests_q);
                                       
                                       for($i=1; $i<=$guests_res['max_adult']; $i++){
                                         echo "<option value='$i'>$i</option>";
                                       }
                                ?>
                                </select>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label class="form-label" style="font-weight:500;">Trẻ Em</label>
                                <select class="form-select shadow-none" name="children">
                                <?php
                                    for($i=1; $i<=$guests_res['max_children']; $i++){
                                      echo "<option value='$i'>$i</option>";
                                    }
                                ?>
                                </select>
                            </div>
                            <input type="hidden" name="check_availability">
                            <div class="col-lg-1 mb-lg-3 mt-2">
                                <button type="submit" class="btn text-white shadow-none custom-bg">Gửi</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- our rooms -->

        <h2 class="mt-5 pt-4 mb-4 text-center fm-bold h-font">Các Loại Phòng</h2>
        <div class="container">
            <div class="row justify-content-center">
                <?php
                $room_res = select("SELECT * FROM `rooms` WHERE `status`=? AND `removed`=? ORDER BY `id` DESC LIMIT 3 ", [1, 0], 'ii');

                while ($room_data = mysqli_fetch_assoc($room_res)) {
                    // get features of room
                    $fea_q = mysqli_query($con, "SELECT f.name FROM `features` f
                            INNER JOIN `room_features` rfea ON f.id = rfea.features_id
                            WHERE rfea.room_id = '$room_data[id]' ");

                    $features_data = "";
                    while ($fea_row = mysqli_fetch_assoc($fea_q)) {
                        $features_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>$fea_row[name]</span>";
                    }

                    // get facilities of room
                    $fac_q = mysqli_query($con, "SELECT f.name FROM `facilities` f
                        INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id
                        WHERE rfac.room_id = '$room_data[id]' ");

                    $facilities_data = "";
                    if ($fac_q) { // Kiểm tra xem truy vấn có thành công không
                        while ($fac_row = mysqli_fetch_assoc($fac_q)) {
                            $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>$fac_row[name]</span>";
                        }
                    }

                    // get thumbnail of image
                    $room_thumb = ROOMS_IMG_PATH . "thumb.png";
                    $thumb_q = mysqli_query($con, "SELECT image FROM `room_images`
                                                    WHERE `room_id` = '$room_data[id]'
                                                    AND `thumb` = '1' ");

                    if (mysqli_num_rows($thumb_q) > 0) {
                        $thumb_res = mysqli_fetch_assoc($thumb_q);
                        $room_thumb = ROOMS_IMG_PATH . $thumb_res['image'];
                    }


                    $book_btn = "";

                    if (!$settings_r['shutdown']) {
                        $login = 0;
                        if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
                            $login = 1;
                        }
                        $book_btn = "<button onclick='checkLoginToBook($login,$room_data[id])' class='btn btn-sm text-white custom-bg shadow-none'>Đặt Ngay</button>";
                    }

                    $rating_q = "SELECT AVG(rating) AS `avg_rating` FROM `rating_review`
                        WHERE `room_id` = '$room_data[id]' ORDER BY `sr_no` DESC LIMIT 20";

                    $rating_res = mysqli_query($con, $rating_q);
                    $rating_fetch = mysqli_fetch_assoc($rating_res);

                    $rating_data = "";

                    if($rating_fetch['avg_rating'] != NULL)
                    {
                        $rating_data = "<div class='rating mb-4'>
                                            <h6 class='mb-1'>Đánh Giá</h6>
                                            <span class='badge rounded-pill bg-light'>";

                        for($i=0; $i < $rating_fetch['avg_rating']; $i++){
                            $rating_data .= "<i class='bi bi-star-fill text-warning'></i> ";
                        }

                        $rating_data .= "</span>
                                        </div>";
                    }


                    // print romm
                
                    echo <<<data
                        <div class="col-lg-4 col-md-6 my-3 d-flex align-items-stretch">
                            <div class="card border-0 shadow h-100" style="width: 100%;">
                                <img src="$room_thumb" class="card-img-top" style="height: 200px; object-fit: cover;">
                                <div class="card-body d-flex flex-column">
                                    <h5>$room_data[name]</h5>
                                    <h6 class="mb-4">
                     data;

                    // Tách mã PHP ra khỏi chuỗi heredoc
                    echo number_format($room_data['price'], 0, ',', '.') . ' VND mỗi đêm';

                    echo <<<data
                                    </h6>
                                    <div class="features mb-4">
                                        <h6 class="mb-1">Tính Năng</h6>
                                        $features_data
                                    </div>
                                    <div class="facilities mb-4">
                                        <h6 class="mb-1">Tiện Nghi</h6>
                                        $facilities_data
                                    </div>
                                    <div class="guests mb-4">
                                        <h6 class="mb-1">Khách Hàng</h6>
                                        <span class="badge rounded-pill bg-light text-dark text-wrap">
                                            $room_data[adult] Người Lớn
                                        </span>
                                        <span class="badge rounded-pill bg-light text-dark text-wrap">
                                            $room_data[children] Trẻ Em
                                        </span>
                                    </div>
                                     $rating_data
                                    <div class="d-flex justify-content-evenly mt-auto mb-2">
                                        $book_btn
                                        <a href="room_details.php?id=$room_data[id]" class="btn btn-sm btn-outline-dark shadow-none">Chi Tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    data;
                }
                ?>
                <div class="col-lg-12 text-center mt-5">
                    <a href="rooms.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Xem Thêm
                        >>></a>
                </div>
            </div>
        </div>

        <!-- our facilities -->
        <h2 class="mt-5 pt-4 mb-4 text-center fm-bold h-font">Tiện Nghi </h2>
        <div class="container">
            <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
                <?php
                $res = mysqli_query($con, "SELECT * FROM `facilities` ORDER BY `id` DESC LIMIT 5");
                $path = FACILITIES_IMG_PATH;

                while ($row = mysqli_fetch_assoc($res)) {
                    echo <<<data
                        <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                            <img src="$path$row[icon]" width="60px">
                            <h5 class="mt-3">$row[name]</h5>
                        </div>
                        
                    data;
                }
                ?>
                <div class="col-lg-12 text-center mt-5">
                    <a href="facilities.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Xem Thêm>>></a>
                </div>
            </div>

        </div>


        <!-- testimonials -->

        <h2 class="mt-5 pt-4 mb-4 text-center fm-bold h-font">Đánh Giá</h2>
        <div class="container mt-5">
            <!-- Điều chỉnh container để nhỏ hơn và tập trung hơn -->
            <div class="col-lg-10 col-md-12 mx-auto">
                <div class="swiper swiper-testimonials testimonials-container" style="width: 100%; overflow: hidden; padding: 20px 0;">
                    <div class="swiper-wrapper mb-5">

                        <?php

                            $review_q = "SELECT rr.*,uc.name AS uname, r.name AS rname FROM `rating_review` rr
                                        INNER JOIN `user_cred` uc ON rr.user_id = uc.id
                                        INNER JOIN `rooms` r ON rr.room_id = r.id
                                        ORDER BY `sr_no` DESC LIMIT 6";

                            $review_res = mysqli_query($con, $review_q);
                            $img_path = USERS_IMG_PATH;

                            if(mysqli_num_rows($review_res) == 0){
                                echo 'Chưa có đánh giá nào.';
                            }else
                            {
                                while($row = mysqli_fetch_assoc($review_res))
                                {
                                    $stars = "<i class='bi bi-star-fill text-warning'></i>";
                                    for($i=1; $i<$row['rating']; $i++){
                                        $stars .= "<i class='bi bi-star-fill text-warning'></i>";
                                    }
                                    echo <<<slides
                                        <div class="swiper-slide">
                                            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 12px; height: 250px;">
                                                <div class="card-body p-4">
                                                    <div class="profile d-flex align-items-center mb-3">
                                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; overflow: hidden;">
                                                            <img src="images/users/user.svg" loading="lazy" width="30px">
                                                        </div>
                                                        <h6 class="m-0 ms-2 fw-bold">$row[uname]</h6>
                                                    </div>
                                                    <p class="card-text" style="font-size: 0.9rem; height: 80px; overflow-y: auto;">
                                                        $row[review]
                                                    </p>
                                                    <div class="rating d-flex align-items-center mt-3">
                                                        <span class="text-muted me-2" style="font-size: 0.85rem;">Đánh Giá:</span>
                                                        <span class="text-warning">$stars</span>
                                                    </div>
                                                    <small class="text-muted d-block mt-2">Loại: $row[rname]</small>
                                                </div>
                                            </div>
                                        </div>
                                    slides;
                                }
                            }
                        ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
            <div class="col-lg-12 text-center mt-4">
                <a href="about.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Xem Thêm
                    >>></a>
            </div>
        </div>

        <!-- Thay thế script cấu hình Swiper -->
        <script>
            // Đảm bảo đợi cho Swiper script tải xong
            window.addEventListener('load', function() {
                // Đặt timeout ngắn để đảm bảo Swiper đã được khởi tạo
                setTimeout(function() {
                    var testimonialSwiper = new Swiper('.swiper-testimonials', {
                        slidesPerView: 3,
                        spaceBetween: 30,
                        centeredSlides: false,
                        loop: true,
                        speed: 800,
                        autoplay: {
                            delay: 3500,
                            disableOnInteraction: false,
                        },
                        effect: 'slide',
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                            dynamicBullets: true,
                        },
                        breakpoints: {
                            0: {
                                slidesPerView: 1,
                                spaceBetween: 20,
                            },
                            768: {
                                slidesPerView: 2,
                                spaceBetween: 20,
                            },
                            1024: {
                                slidesPerView: 3,
                                spaceBetween: 30,
                            }
                        }
                    });
                }, 100);
            });
        </script>

        <!-- reach us -->


        <h2 class="mt-5 pt-4 mb-4 text-center fm-bold h-font">Liên Hệ Với Chung Tôi</h2>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
                    <iframe class="w-100 rounded" height="320px" src="<?php echo $contact_r['iframe'] ?>"></iframe>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="bg-white p-4 rounded mb-4">
                        <h5>Liên Hệ</h5>
                        <a href="tel: <?php echo $contact_r['pn1'] ?>"
                            class="d-inline-block mb-2 text-decoration-none text-dark"><i
                                class="bi bi-telephone-fill "></i>+<?php echo $contact_r['pn1'] ?></a>
                        <br>
                        <?php
                        if ($contact_r['pn2'] != '') {
                            echo <<<data
                                 <a href="tel: +$contact_r[pn2]" class="d-inline-block  text-decoration-none text-dark"><i
                                    class="bi bi-telephone-fill  "></i>+$contact_r[pn2]</a>
                                data;
                        }

                        ?>
                    </div>
                    <div class="bg-white p-4 rounded mb-4">
                        <h5>Mạng Xã Hội</h5>
                        <?php
                        if ($contact_r['tw'] != '') {
                            echo <<<data
                                    <a href="$contact_r[tw]" class="d-inline-block mb-3">
                                        <span class="badge bg-light text-dark fs-6 p-2">
                                            <i class="bi bi-twitter me-1"></i>Twitter
                                        </span>
                                    </a>
                                data;
                        }


                        ?>

                        <br>
                        <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block mb-3">
                            <span class="badge bg-light text-dark fs-6 p-2">
                                <i class="bi bi-facebook me-1"></i>FaceBook
                            </span>
                        </a>
                        <br>
                        <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block mb-3">
                            <span class="badge bg-light text-dark fs-6 p-2">
                                <i class="bi bi-instagram me-1"></i>Instagram
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php require('inc/footer.php') ?>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        
        <script src="js/main.js"></script>

        <?php require('chat.php') ?>
    

</body>

</html>