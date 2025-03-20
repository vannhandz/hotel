<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/link.php') ?>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/shared.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/room_details.css?v=<?php echo time(); ?>">
    <title><?php echo $settings_r['site_title'] ?> - Chi Tiết Phòng</title>
</head>

<body class="bg-light">

    <?php require('inc/header.php') ?>

    <?php
    if (!isset($_GET['id'])) {
        redirect('rooms.php');
    }
    $data = filteration($_GET);
    $room_res = select("SELECT * FROM `rooms` WHERE `id`=? AND `status`=? AND `removed`=?", [$data['id'], 1, 0], 'iii');

    if (mysqli_num_rows($room_res) == 0) {
        redirect('rooms.php');
    }

    $room_data = mysqli_fetch_assoc($room_res);
    ?>

    <!-- Page Banner -->
    <div class="page-banner room-details-banner">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="banner-title"><?php echo $room_data['name'] ?></h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="index.php">Trang Chủ</a></li>
                            <li class="breadcrumb-item"><a href="rooms.php">Phòng</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $room_data['name'] ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="room-details-container">
            <div class="row g-4">
                <!-- Room Gallery -->
                <div class="col-lg-7 col-md-12">
                    <div class="room-gallery-container">
                        <?php
                        $room_images = array();
                        $img_q = mysqli_query($con, "SELECT image FROM `room_images` WHERE `room_id` = '$room_data[id]'");

                        if (mysqli_num_rows($img_q) > 0) {
                            while ($img_res = mysqli_fetch_assoc($img_q)) {
                                $room_images[] = ROOMS_IMG_PATH . $img_res['image'];
                            }
                        } else {
                            $room_images[] = ROOMS_IMG_PATH . "thumb.png";
                        }

                        // Display main image
                        echo '<div class="main-image-container">
                                <div class="hot-tag">Hot</div>
                                <img src="' . $room_images[0] . '" id="main-room-image" alt="' . $room_data['name'] . '">
                            </div>';

                        // Display thumbnails (up to 5)
                        if (count($room_images) > 1) {
                            echo '<div class="thumbnail-gallery">';
                            $count = 0;
                            foreach ($room_images as $index => $img) {
                                $active_class = ($index == 0) ? 'active' : '';
                                echo '<div class="thumbnail-item ' . $active_class . '" data-src="' . $img . '" data-index="' . $index . '">
                                        <img src="' . $img . '" alt="Room thumbnail">
                                    </div>';
                                $count++;
                                if ($count >= 5) break; // Limit to 5 thumbnails
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>

                

                <!-- Room Booking Card -->
                <div class="col-lg-5 col-md-12">
                    <div class="booking-card">
                        <div class="price-container">
                            <h2 class="room-price"><?php echo number_format($room_data['price'], 0, ',', '.') ?></h2>
                            <span class="price-suffix">VND / đêm</span>
                        </div>

                        <div class="section-container">
                            <h3 class="booking-section-header">
                            <i class="bi bi-star me-1"></i>Tính Năng
                            </h3>
                            <div class="feature-pills">
                                <?php
                                $fea_q = mysqli_query($con, "SELECT f.name FROM `features` f
                                INNER JOIN `room_features` rfea ON f.id = rfea.features_id
                                WHERE rfea.room_id = '$room_data[id]' ");

                                if ($fea_q && mysqli_num_rows($fea_q) > 0) {
                                    while ($fac_row = mysqli_fetch_assoc($fea_q)) {
                                        echo "<span class='feature-pill'>$fac_row[name]</span>";
                                    }
                                } else {
                                    echo "<p>Không có tiện nghi được liệt kê</p>";
                                }
                                ?>
                                <?php
                               
                                ?>
                            </div>
                        </div>

                        <!-- Tiện Nghi Section -->
                        <div class="section-container">
                            <h3 class="booking-section-header">
                                <i class="bi bi-house-gear"></i> Tiện Nghi
                            </h3>
                            <div class="feature-pills">
                                <?php
                                $fac_q = mysqli_query($con, "SELECT f.name FROM `facilities` f
                                    INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id
                                    WHERE rfac.room_id = '$room_data[id]' ");

                                if ($fac_q && mysqli_num_rows($fac_q) > 0) {
                                    while ($fac_row = mysqli_fetch_assoc($fac_q)) {
                                        echo "<span class='feature-pill'>$fac_row[name]</span>";
                                    }
                                } else {
                                    echo "<p>Không có tiện nghi được liệt kê</p>";
                                }
                                ?>
                                <?php
                               
                                ?>
                            </div>
                        </div>
                        

                        <!-- Khách Hàng Section -->
                        <div class="section-container">
                            <h3 class="booking-section-header">
                                <i class="bi bi-people"></i> Khách Hàng
                            </h3>
                            <div class="guest-info">
                                <span class="guest-pill">
                                    <i class="bi bi-person"></i> <?php echo $room_data['adult'] ?> Người Lớn
                                </span>
                                <span class="guest-pill">
                                    <i class="bi bi-person-heart"></i> <?php echo $room_data['children'] ?> Trẻ Em
                                </span>
                            </div>
                        </div>

                        <!-- Diện Tích Section -->
                        <div class="section-container">
                            <h3 class="booking-section-header">
                                <i class="bi bi-rulers"></i> Diện Tích
                            </h3>
                            <span class="area-pill">
                                <i class="bi bi-square"></i> <?php echo $room_data['area'] ?> sq. ft
                            </span>
                        </div>

                        <?php
                        if (!$settings_r['shutdown']) {
                            $login = 0;
                            if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
                                $login = 1;
                            }
                            echo '<button onclick="checkLoginToBook(' . $login . ',' . $room_data['id'] . ')" class="btn-book-now">ĐẶT NGAY <i class="bi bi-arrow-right-circle"></i></button>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Room Description & Reviews -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="content-tabs">
                        <ul class="nav nav-tabs" id="roomDetailsTabs" role="tablist">
                            <li class="nav-item <?php echo !isset($_GET['review']) ? 'active' : ''; ?>" role="presentation">
                                <button class="nav-link <?php echo !isset($_GET['review']) ? 'active' : ''; ?>" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
                                    <div class="room-tab">
                                        <i class="bi bi-file-text"></i> Mô Tả
                                    </div>
                                </button>
                            </li>
                            <li class="nav-item <?php echo isset($_GET['review']) ? 'active' : ''; ?>" role="presentation">
                                <button class="nav-link <?php echo isset($_GET['review']) ? 'active' : ''; ?>" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                                    <div class="room-tab">
                                        <i class="bi bi-star"></i> Đánh Giá
                                    </div>
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="roomDetailsTabContent">
                            <div class="tab-pane fade <?php echo !isset($_GET['review']) ? 'show active' : ''; ?>" id="description" role="tabpanel">
                                <div class="description-content">
                                    <?php echo $room_data['description']; ?>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade <?php echo isset($_GET['review']) ? 'show active' : ''; ?>" id="reviews" role="tabpanel">
                                <div class="reviews-content">
                                    <?php
                                    $review_q = "SELECT rr.*,uc.name AS uname, r.name AS rname FROM `rating_review` rr
                                            INNER JOIN `user_cred` uc ON rr.user_id = uc.id
                                            INNER JOIN `rooms` r ON rr.room_id = r.id
                                            WHERE rr.room_id = '$room_data[id]'
                                            ORDER BY `sr_no` DESC LIMIT 15";

                                    $review_res = mysqli_query($con, $review_q);    
                                    $img_path = USERS_IMG_PATH;

                                    if(mysqli_num_rows($review_res) == 0) {
                                        echo '<div class="no-reviews">Chưa có đánh giá nào.</div>';
                                    } else {
                                        while($row = mysqli_fetch_assoc($review_res)) { 
                                            $stars = "";
                                            for($i=0; $i<5; $i++) {
                                                if($i < $row['rating']) {
                                                    $stars .= "<i class='bi bi-star-fill'></i>";
                                                } else {
                                                    $stars .= "<i class='bi bi-star'></i>";
                                                }
                                            }
                                            
                                            echo '<div class="review-item">
                                                <div class="review-user">
                                                    <div class="user-avatar">
                                                        <img src="images/users/user.svg" alt="User">
                                                    </div>
                                                    <div class="user-info">
                                                        <h4>' . $row['uname'] . '</h4>
                                                        <div class="review-date">' . date('d M Y', strtotime($row['datentime'])) . '</div>
                                                    </div>
                                                </div>
                                                <div class="review-content">
                                                    <div class="review-stars">
                                                        ' . $stars . '
                                                    </div>
                                                    <div class="review-text">
                                                        ' . $row['review'] . '
                                                    </div>
                                                </div>
                                            </div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fullscreen Gallery -->
    <div class="fullscreen-gallery" id="fullscreen-gallery">
        <div class="gallery-close" onclick="closeGallery()">
            <i class="bi bi-x-lg"></i>
        </div>
        <div class="gallery-nav gallery-prev" onclick="prevImage()">
            <i class="bi bi-chevron-left"></i>
        </div>
        <img src="" class="fullscreen-image" id="fullscreen-image" alt="Room">
        <div class="gallery-nav gallery-next" onclick="nextImage()">
            <i class="bi bi-chevron-right"></i>
        </div>
    </div>

    <?php require('inc/footer.php') ?>

    <script>
        // Gallery images array
        let galleryImages = [];
        let currentImageIndex = 0;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Collect all gallery images
            <?php
            echo "galleryImages = [";
            foreach ($room_images as $index => $img) {
                if ($index > 0) echo ", ";
                echo "'" . $img . "'";
                if ($index >= 4) break; // Limit to 5 images (0-4)
            }
            echo "];";
            ?>
            
            // Make main image clickable to open fullscreen
            document.getElementById('main-room-image').addEventListener('click', function() {
                openGallery(0);
            });
            
            // Make all thumbnails clickable
            document.querySelectorAll('.thumbnail-item').forEach(item => {
                item.addEventListener('click', function() {
                    const src = this.getAttribute('data-src');
                    const index = parseInt(this.getAttribute('data-index'));
                    
                    // Change main image
                    document.getElementById('main-room-image').src = src;
                    
                    // Update active class on thumbnails
                    document.querySelectorAll('.thumbnail-item').forEach(thumb => {
                        thumb.classList.remove('active');
                    });
                    this.classList.add('active');
                    
                    // Store current index
                    currentImageIndex = index;
                });
                
                // Double click to open gallery
                item.addEventListener('dblclick', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    openGallery(index);
                });
            });
        });
        
        // Open fullscreen gallery
        function openGallery(index) {
            currentImageIndex = index;
            const gallery = document.getElementById('fullscreen-gallery');
            const fullscreenImage = document.getElementById('fullscreen-image');
            
            // Set image source first, then show and animate
            fullscreenImage.src = galleryImages[index];
            gallery.style.display = 'flex';
            
            // Trigger reflow for animation
            void gallery.offsetWidth;
            
            // Add active class for animation
            gallery.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        }
        
        // Close fullscreen gallery
        function closeGallery() {
            const gallery = document.getElementById('fullscreen-gallery');
            
            // Remove active class first for fade out
            gallery.classList.remove('active');
            
            // Hide gallery after animation
            setTimeout(() => {
                gallery.style.display = 'none';
                document.body.style.overflow = 'auto'; // Re-enable scrolling
            }, 300); // Match CSS transition time
        }
        
        // Navigate to previous image
        function prevImage() {
            currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
            document.getElementById('fullscreen-image').src = galleryImages[currentImageIndex];
        }
        
        // Navigate to next image
        function nextImage() {
            currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
            document.getElementById('fullscreen-image').src = galleryImages[currentImageIndex];
        }
        
        // Enable keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (document.getElementById('fullscreen-gallery').style.display === 'flex') {
                if (e.key === 'ArrowLeft') {
                    prevImage();
                } else if (e.key === 'ArrowRight') {
                    nextImage();
                } else if (e.key === 'Escape') {
                    closeGallery();
                }
            }
        });
    </script>

</body>

</html>