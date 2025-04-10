<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/link.php') ?>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/shared.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/about.css?v=<?php echo time(); ?>">
    <title><?php echo $settings_r['site_title'] ?> - Giới Thiệu</title>
</head>

<body class="bg-light">

    <?php require('inc/header.php') ?>

    <!-- Banner Section -->
    <div class="page-banner about-banner">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2>Giới Thiệu</h2>
                    <p>Đội ngũ nhân viên khách sạn của chúng tôi là yếu tố then chốt tạo nên trải nghiệm tuyệt vời cho khách hàng. 
                    Họ được tuyển chọn và đào tạo kỹ lưỡng, thể hiện sự chuyên nghiệp, tận tâm và chu đáo trong mọi hành động.</p>
                </div>
            </div>
        </div>
        <div class="wave-shape"></div>
    </div>

    <!-- Leader Section -->
    <div class="container">
        <div class="leader-section">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2 fade-in">
                    <h3>Người Đứng Đầu</h3>
                    <p>Với tư cách là người đứng đầu khách sạn, tôi sẽ tập trung vào việc tạo ra trải nghiệm khách hàng vượt trội, 
                        xây dựng đội ngũ nhân viên chuyên nghiệp, quản lý tài chính hiệu quả, củng cố thương hiệu và đảm bảo an toàn, tuân thủ. 
                        Mục tiêu của tôi là đưa khách sạn trở thành điểm đến hàng đầu, nơi khách hàng cảm thấy như ở nhà và nhân viên tự hào cống hiến.
                    </p>
                </div>
                <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1 fade-in-delay">
                    <div class="leader-image">
                        <img src="images/about/leader.jpg" class="w-100">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="container">
        <div class="stats-section">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-box fade-in" style="animation-delay: 0.1s;">
                        <img src="images/about/hotel.svg" alt="Rooms">
                        <h4>100+</h4>
                        <p>Phòng</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-box fade-in" style="animation-delay: 0.2s;">
                        <img src="images/about/customer.svg" alt="Customers">
                        <h4>200+</h4>
                        <p>Khách Hàng</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-box fade-in" style="animation-delay: 0.3s;">
                        <img src="images/about/review.svg" alt="Reviews">
                        <h4>150+</h4>
                        <p>Đánh Giá</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-box fade-in" style="animation-delay: 0.4s;">
                        <img src="images/about/staff.svg" alt="Staff">
                        <h4>50+</h4>
                        <p>Nhân Viên</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="container">
        <div class="team-section">
            <h3 class="team-section-title text-center">Ban Quản Lý</h3>
            
            <div class="row mt-5">
                <?php
                    $about_r = selectAll('team_details');
                    $path = ABOUT_IMG_PATH;
                    
                    while($row = mysqli_fetch_assoc($about_r)){
                        echo<<<data
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="team-card fade-in">
                                    <img src="$path$row[picture]" class="w-100">
                                    <div class="team-card-content">
                                        <h5>$row[name]</h5>
                                        <div class="social-icons">
                                            <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
                                            <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
                                            <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        data;
                    }
                ?>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php') ?>

    <script>
        // Add animation class on scroll
        const animateOnScroll = () => {
            const elements = document.querySelectorAll('.fade-in, .fade-in-delay');
            
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementTop < windowHeight * 0.85) {
                    element.classList.add('visible');
                }
            });
        };
        
        // Initial check
        window.addEventListener('load', animateOnScroll);
        // Check on scroll
        window.addEventListener('scroll', animateOnScroll);
        
    </script>
     <?php require('chat.php') ?>
</body>

</html>