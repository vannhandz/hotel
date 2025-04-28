<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/link.php') ?>
    <title><?php echo $settings_r['site_title'] ?> - Trang Chủ</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>

<body class="bg-light">

    <?php require('inc/header.php') ?>

    <!-- Hero Carousel -->
    <div class="container-fluid px-lg-4 mt-0 hero-carousel">
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
        </div>

    <!-- Check availability form -->
    <div class="container-fluid availability-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="availability-form">
                        <div class="availability-form-header">
                            <h5><i class="bi bi-calendar-check"></i> Kiểm Tra Phòng Trống</h5>
                        </div>
                        <div class="availability-form-body">
                            <form action="rooms.php">
                                <div class="row align-items-end">
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Check-in</label>
                                            <i class="bi bi-calendar3 form-control-icon"></i>
                                            <input type="date" class="form-control shadow-none" name="checkin" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Check-out</label>
                                            <i class="bi bi-calendar3 form-control-icon"></i>
                                            <input type="date" class="form-control shadow-none" name="checkout" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Người Lớn</label>
                                            <i class="bi bi-person form-control-icon"></i>
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
                                    </div>
                                    <div class="col-lg-2 col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Trẻ Em</label>
                                            <i class="bi bi-person-heart form-control-icon"></i>
                                            <select class="form-select shadow-none" name="children">
                                            <?php
                                                for($i=0; $i<=$guests_res['max_children']; $i++){
                                                  echo "<option value='$i'>$i</option>";
                                                }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="check_availability">
                                    <div class="col-lg-2 col-md-12 mb-lg-0 mb-3">
                                        <button type="submit" class="btn btn-check-availability text-white shadow-none w-100">
                                            <span>Tìm Kiếm</span>
                                            <i class="bi bi-search ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Room  -->
    <div class="container py-5">
        <h2 class="section-title text-center h-font mb-4">Các Loại Phòng</h2>
        <div class="row">
            <?php
            $room_res = select("SELECT * FROM `rooms` WHERE `status`=? AND `removed`=? ORDER BY `id` DESC LIMIT 3 ", [1, 0], 'ii');

            while ($room_data = mysqli_fetch_assoc($room_res)) {
                // tính năng của phòng
                $fea_q = mysqli_query($con, "SELECT f.name FROM `features` f
                        INNER JOIN `room_features` rfea ON f.id = rfea.features_id
                        WHERE rfea.room_id = '$room_data[id]' ");

                $features_data = "";
                while ($fea_row = mysqli_fetch_assoc($fea_q)) {
                    $features_data .= "<span class='badge rounded-pill me-1 mb-1'>$fea_row[name]</span>";
                }

                // tiện ích của phòng
                $fac_q = mysqli_query($con, "SELECT f.name FROM `facilities` f
                    INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id
                    WHERE rfac.room_id = '$room_data[id]' ");

                $facilities_data = "";
                while ($fac_row = mysqli_fetch_assoc($fac_q)) {
                    $facilities_data .= "<span class='badge rounded-pill me-1 mb-1'>$fac_row[name]</span>";
                }
                

                // lấy hình ảnh khi chưa có ảnh phòng
                $room_thumb = ROOMS_IMG_PATH . "thumb.png";
                $thumb_q = mysqli_query($con, "SELECT image FROM `room_images`
                                                WHERE `room_id` = '$room_data[id]'
                                                AND `thumb` = '1' ");

                if (mysqli_num_rows($thumb_q) > 0) {
                    $thumb_res = mysqli_fetch_assoc($thumb_q);
                    $room_thumb = ROOMS_IMG_PATH . $thumb_res['image'];
                }

                // shutdown website
                $book_btn = "";

                if (!$settings_r['shutdown']) {
                    $login = 0;
                    if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
                        $login = 1;
                    }
                    $book_btn = "<button onclick='checkLoginToBook($login,$room_data[id])' class='btn btn-book'><i class='bi bi-calendar-check me-1'></i>Đặt Ngay</button>";
                }

                $rating_q = "SELECT AVG(rating) AS `avg_rating` FROM `rating_review`
                    WHERE `room_id` = '$room_data[id]' ORDER BY `sr_no` DESC LIMIT 20";

                $rating_res = mysqli_query($con, $rating_q);
                $rating_fetch = mysqli_fetch_assoc($rating_res);

                // print room
                echo <<<data
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card room-card shadow-sm">
                            <div class="room-image-container position-relative overflow-hidden">
                                <img src="$room_thumb" class="card-img-top" alt="$room_data[name]">
                                <div class="rating-badge position-absolute">
                data;

                if($rating_fetch['avg_rating'] != NULL) {
                    echo '<div class="d-flex align-items-center">';
                    
                    for($i=0; $i < floor($rating_fetch['avg_rating']); $i++){
                        echo '<i class="bi bi-star-fill text-warning"></i>';
                    }
                    
                    // Hiển thị nửa sao nếu có phần thập phân lớn hơn 0.2 và nhỏ hơn 0.8
                    $decimal = $rating_fetch['avg_rating'] - floor($rating_fetch['avg_rating']);
                    if ($decimal > 0.2 && $decimal < 0.8) {
                        echo '<i class="bi bi-star-half text-warning"></i>';
                        $i++;
                    } else if ($decimal >= 0.8) {
                        echo '<i class="bi bi-star-fill text-warning"></i>';
                        $i++;
                    }
                    
                    // Hiển thị sao trống cho số còn lại đến 5
                    for(; $i < 5; $i++){
                        echo '<i class="bi bi-star text-warning"></i>';
                    }
                    
                    echo '</div>';
                } else {
                    echo '<span class="no-rating">Chưa đánh giá</span>';
                }
                
                echo <<<data
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold">$room_data[name]</h5>
                                <h6 class="room-price mb-3">
                data;

                // Tách mã PHP ra khỏi chuỗi heredoc
                echo '<span class="price-tag">' . number_format($room_data['price'], 0, ',', '.') . ' VND</span> <small class="text-muted">mỗi đêm</small>';

                echo <<<data
                                </h6>
                                
                                <div class="room-capacity d-flex mb-3">
                                    <div class="capacity-item me-3">
                                        <i class="bi bi-person-fill"></i> $room_data[adult] người lớn
                                    </div>
                                    <div class="capacity-item">
                                        <i class="bi bi-people-fill"></i> $room_data[children] trẻ em
                                    </div>
                                </div>
                                
                                <div class="room-features mb-3">
                                    <h6 class="fw-bold mb-2"><i class="bi bi-star me-1"></i>Tính Năng</h6>
                                    <div class="d-flex flex-wrap">
                                        $features_data
                                    </div>
                                </div>
                                
                                <div class="room-facilities mb-3">
                                    <h6 class="fw-bold mb-2"><i class="bi bi-house-gear me-1"></i>Tiện Nghi</h6>
                                    <div class="d-flex flex-wrap">
                                        $facilities_data
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-3">
                                    $book_btn
                                    <a href="room_details.php?id=$room_data[id]" class="btn btn-details"><i class="bi bi-info-circle me-1"></i>Chi Tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                data;
            }
            ?>
            <div class="col-lg-12 text-center mt-3">
                <a href="rooms.php" class="btn btn-view-more">Xem Thêm <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Tiện Nghi -->
    <div class="container py-5">
        <h2 class="section-title text-center h-font">Tiện Nghi</h2>
        <div class="row justify-content-evenly g-4">
                <?php
                $res = mysqli_query($con, "SELECT * FROM `facilities` ORDER BY `id` DESC LIMIT 5");
                $path = FACILITIES_IMG_PATH;

                while ($row = mysqli_fetch_assoc($res)) {
                    echo <<<data
                    <div class="col-lg-2 col-md-3 col-sm-6 col-6">
                        <div class="facility-box text-center">
                            <img src="$path$row[icon]" class="mb-3">
                            <h5>$row[name]</h5>
                        </div>
                    </div>
                    data;
                }
                ?>
            <div class="col-lg-12 text-center">
                <a href="facilities.php" class="btn btn-view-more">Xem Thêm <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
        </div>

    <!-- Đánh Giá Từ Khách Hàng -->
    <div class="testimonials-section">
        <div class="container">
            <h2 class="section-title text-center h-font">Đánh Giá Từ Khách Hàng</h2>
            <div class="swiper swiper-testimonials-new">
                <div class="swiper-wrapper">
                    <?php
                    $review_q = "SELECT rr.*,uc.name AS uname, r.name AS rname FROM `rating_review` rr
                                INNER JOIN `user_cred` uc ON rr.user_id = uc.id
                                INNER JOIN `rooms` r ON rr.room_id = r.id
                                ORDER BY `sr_no` DESC LIMIT 10";

                    $review_res = mysqli_query($con, $review_q);
                    $img_path = USERS_IMG_PATH;

                    if(mysqli_num_rows($review_res) == 0){
                        echo '<div class="text-center my-5"><h5 class="fw-bold text-muted">Chưa có đánh giá nào.</h5></div>';
                    } else {
                        while($row = mysqli_fetch_assoc($review_res))
                        {
                            $stars = '';
                            for($i=0; $i < $row['rating']; $i++){
                                $stars .= '<i class="bi bi-star-fill"></i>';
                            }
                            
                            for($i=$row['rating']; $i < 5; $i++){
                                $stars .= '<i class="bi bi-star"></i>';
                            }
                            
                            echo <<<slides
                                <div class="swiper-slide">
                                    <div class="testimonial-box">
                                        <div class="testimonial-content">
                                            <div class="testimonial-text">
                                                $row[review]
                                            </div>
                                            <div class="testimonial-rating">
                                                <div class="testimonial-stars">$stars</div>
                                            </div>
                                            <div class="testimonial-user">
                                                <div class="testimonial-user-img">
                                                    <img src="images/users/user.svg" loading="lazy">
                                                </div>
                                                <div class="testimonial-user-info">
                                                    <div class="testimonial-name">$row[uname]</div>
                                                    <div class="testimonial-room">$row[rname]</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            slides;
                        }
                    }
                    ?>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next" style="color: var(--teal);"></div>
                <div class="swiper-button-prev" style="color: var(--teal);"></div>
            </div>
            <div class="text-center">
                <a href="about.php" class="view-more-reviews">Xem Thêm Đánh Giá <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Contact us section -->
    <div class="contact-section">
        <div class="container">
            <h2 class="section-title text-center h-font">Liên Hệ Với Chúng Tôi</h2>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="map-container">
                        <iframe class="w-100" src="<?php echo $contact_r['iframe'] ?>" loading="lazy"></iframe>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-box">
                        <h5>Thông Tin Liên Hệ</h5>
                        <div class="contact-info">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="bi bi-telephone-fill"></i>
                                </div>
                                <div class="contact-text">
                                    <div class="contact-label">Số điện thoại chính</div>
                                    <div class="contact-value">+<?php echo $contact_r['pn1'] ?></div>
                                </div>
                            </div>
                            
                            <?php
                            if ($contact_r['pn2'] != '') {
                                echo <<<data
                                    <div class="contact-item">
                                        <div class="contact-icon">
                                            <i class="bi bi-phone-fill"></i>
                                        </div>
                                        <div class="contact-text">
                                            <div class="contact-label">Số điện thoại phụ</div>
                                            <div class="contact-value">+$contact_r[pn2]</div>
                                        </div>
                                    </div>
                                data;
                            }
                            ?>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <div class="contact-text">
                                    <div class="contact-label">Địa chỉ</div>
                                    <div class="contact-value"><?php echo $contact_r['address'] ?></div>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="bi bi-envelope-fill"></i>
                                </div>
                                <div class="contact-text">
                                    <div class="contact-label">Email</div>
                                    <div class="contact-value"><?php echo $contact_r['email'] ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <h5 class="social-title">Kết Nối Với Chúng Tôi</h5>
                        <div class="social-icons">
                            <?php
                            if ($contact_r['tw'] != '') {
                                echo "<a href='$contact_r[tw]' class='social-icon twitter'><i class='bi bi-twitter'></i></a>";
                            }
                            ?>
                            <a href="<?php echo $contact_r['fb'] ?>" class="social-icon facebook"><i class="bi bi-facebook"></i></a>
                            <a href="<?php echo $contact_r['insta'] ?>" class="social-icon instagram"><i class="bi bi-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php') ?>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- Initialize Swiper -->
        <script>
        var mainSwiper = new Swiper(".swiper-container", {
            spaceBetween: 0,
            effect: "fade",
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            speed: 1500
        });

        // Testimonials swiper
        var testimonialSwiper = new Swiper(".swiper-testimonials-new", {
            slidesPerView: 3,
            spaceBetween: 20,
            loop: true,
            loopFillGroupWithBlank: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            speed: 1000,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                    spaceBetween: 10,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 15,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                }
            }
        });
        </script>

    <!-- Main JS -->
        <script src="js/main.js"></script>

        <?php require('chat.php') ?>

</body>

</html>