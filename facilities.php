<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/link.php') ?>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <title><?php echo $settings_r['site_title'] ?> - Tiện Nghi</title>
</head>

<body class="bg-light">
    
    <?php require('inc/header.php') ?>

    <!-- Banner Section -->
    <div class="facilities-banner">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="text-white fw-bold">Tiện Nghi Đẳng Cấp</h1>
                    <p class="text-white">Khám phá những dịch vụ và tiện nghi cao cấp mang đến trải nghiệm nghỉ dưỡng tuyệt vời nhất</p>
                    
                    <div class="banner-features">
                        <span><i class="bi bi-water"></i> Hồ bơi vô cực</span>
                        <span><i class="bi bi-cup-hot"></i> Nhà hàng 5 sao</span>
                        <span><i class="bi bi-bicycle"></i> Khu thể thao</span>
                        <span><i class="bi bi-wifi"></i> Wi-Fi tốc độ cao</span>
                        <span><i class="bi bi-headset"></i> Dịch vụ 24/7</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="wave-shape"></div>
    </div>

    <div class="container">
        <!-- Categories Section -->
        <div class="facility-categories">
            <div class="facility-category active" data-category="all"><i class="bi bi-grid"></i> Tất cả</div>
            <div class="facility-category" data-category="pool"><i class="bi bi-water"></i> Hồ bơi & Spa</div>
            <div class="facility-category" data-category="dining"><i class="bi bi-cup-hot"></i> Ẩm thực</div>
            <div class="facility-category" data-category="sport"><i class="bi bi-bicycle"></i> Thể thao</div>
            <div class="facility-category" data-category="entertainment"><i class="bi bi-headset"></i> Giải trí</div>
            <div class="facility-category" data-category="service"><i class="bi bi-person-check"></i> Dịch vụ</div>
        </div>

        <div class="row facility-items">
            <?php 
                $res = selectAll('facilities');
                $path = FACILITIES_IMG_PATH;
                $categories = ['pool', 'dining', 'sport', 'entertainment', 'service'];

                while($row = mysqli_fetch_assoc($res))
                {
                    // Get a random image for display purposes (in real implementation, you'd have proper facility images)
                    $img_num = ($row['id'] % 5) + 1;
                    $img_path = "images/carousel/" . $img_num . ".png";
                    
                    // Assign a random category for demo purposes (in real implementation, you'd have proper categories)
                    $category = $categories[($row['id'] % 5)];
                    
                    echo<<<data
                        <div class="col-lg-4 col-md-6 mb-4 facility-item" data-category="$category">
                            <div class="facility-card">
                                <div class="facility-card-img">
                                   
                                </div>
                                <div class="facility-card-body">
                                    <div class="facility-card-icon">
                                        <img src="$path$row[icon]" alt="$row[name]">
                                    </div>
                                    <h3 class="facility-card-title">$row[name]</h3>
                                    <p class="facility-card-text">$row[description]</p>
                                    <a href="#" class="facility-card-link">Xem chi tiết <i class="bi bi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    data;
                }
            ?>
        </div>

        <div class="row justify-content-center mt-5 experience-section">
            <div class="col-lg-8 col-md-10 text-center">
                <h2 class="section-title">Trải Nghiệm Tuyệt Vời</h2>
                <p class="experience-text">
                    Chúng tôi tự hào mang đến cho quý khách những tiện nghi đẳng cấp, được thiết kế tỉ mỉ để đáp ứng mọi nhu cầu.
                    Từ hồ bơi vô cực với tầm nhìn ngoạn mục, phòng tập gym hiện đại với trang thiết bị tối tân, 
                    đến spa thư giãn với các liệu pháp trị liệu chuyên nghiệp, chúng tôi cam kết mang đến những trải nghiệm khó quên.
                </p>
                <a href="contact.php" class="btn btn-lg btn-contact">
                    <i class="bi bi-send"></i> Liên Hệ Ngay
                </a>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php') ?>

    <script>
        // Add functionality for facility categories
        document.addEventListener('DOMContentLoaded', function() {
            const categories = document.querySelectorAll('.facility-category');
            const facilityItems = document.querySelectorAll('.facility-item');
            
            // Hiển thị tất cả các mục khi trang được tải
            facilityItems.forEach(item => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
                item.style.display = 'block';
            });
            
            categories.forEach(category => {
                category.addEventListener('click', function() {
                    // Remove active class from all categories
                    categories.forEach(c => c.classList.remove('active'));
                    
                    // Add active class to clicked category
                    this.classList.add('active');
                    
                    // Get the selected category
                    const selectedCategory = this.getAttribute('data-category');
                    
                    // Show all items if "all" is selected, otherwise filter by category
                    if(selectedCategory === 'all') {
                        facilityItems.forEach(item => {
                            item.style.display = 'block';
                            setTimeout(() => {
                                item.style.opacity = '1';
                                item.style.transform = 'translateY(0)';
                            }, 50);
                        });
                    } else {
                        facilityItems.forEach(item => {
                            if(item.getAttribute('data-category') === selectedCategory) {
                                item.style.display = 'block';
                                setTimeout(() => {
                                    item.style.opacity = '1';
                                    item.style.transform = 'translateY(0)';
                                }, 50);
                            } else {
                                item.style.opacity = '0';
                                item.style.transform = 'translateY(20px)';
                                setTimeout(() => {
                                    item.style.display = 'none';
                                }, 300);
                            }
                        });
                    }
                });
            });
            
            // Add CSS for animation
            const style = document.createElement('style');
            style.textContent = `
                .facility-item {
                    transition: opacity 0.3s ease, transform 0.3s ease;
                }
            `;
            document.head.appendChild(style);
        });
    </script>
     <?php require('chat.php') ?>
</body>

</html>