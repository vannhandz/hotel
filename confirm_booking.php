<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/link.php') ?>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/confirm_booking.css">
    <title><?php echo $settings_r['site_title'] ?> - Xác Nhận Đặt Phòng</title>
</head>

<body class="bg-light">

    <?php require('inc/header.php') ?>

    <?php
    if (!isset($_GET['id']) || $settings_r['shutdown'] == true) {
        redirect('rooms.php');

    } else if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        redirect('rooms.php');
    }

    $data = filteration($_GET);
    $room_res = select("SELECT * FROM `rooms` WHERE `id`=? AND `status`=? AND `removed`=?", [$data['id'], 1, 0], 'iii');

    if (mysqli_num_rows($room_res) == 0) {
        redirect('rooms.php');
    }

    $room_data = mysqli_fetch_assoc($room_res);

    $_SESSION['room'] = [
        "id" => $room_data['id'],
        "name" => $room_data['name'],
        "price" => $room_data['price'],
        "payment" => null,
        "available" => false,
    ];

    $user_res = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1", [$_SESSION['uId']], "i");
    $user_data = mysqli_fetch_assoc($user_res);

    // Get room features
    $fac_q = mysqli_query($con, "SELECT f.name FROM `facilities` f
    INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id
    WHERE rfac.room_id = '$room_data[id]' ");
                    
    $features_data = "";
    while ($fea_row = mysqli_fetch_assoc($fac_q)) {
        $features_data .= "<span class='feature-badge'>$fea_row[name]</span>";
    }
    ?>

    <!-- Page Header Banner -->
    <div class="page-header-banner">
        <div class="container">
            <h1 class="text-center text-white"><?php echo $room_data['name']; ?></h1>
            <div class="header-line"></div>
            <div class="breadcrumb-container">
                <div class="breadcrumb-pill">
                    <a href="index.php">Trang Chủ</a>
                    <span class="divider">/</span>
                    <a href="rooms.php">Phòng</a>
                    <span class="divider">/</span>
                    <span class="current"><?php echo $room_data['name']; ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container booking-container">
        <div class="row">
            <div class="col-12 mb-4">
                <h1 class="booking-heading">Xác Nhận Đặt Phòng</h1>
                <div class="breadcrumb-custom">
                    <a href="index.php">Trang Chủ</a>
                    <span class="separator">></span>
                    <a href="rooms.php">Phòng</a>
                    <span class="separator">></span>
                    <a href="#">Xác Nhận</a>
                </div>
            </div>

            <div class="col-lg-7 col-md-12 mb-4">
                <?php
                $room_thumb = ROOMS_IMG_PATH . "thumbnail.jpg";
                $thumb_q = mysqli_query($con, "SELECT * FROM `room_images` 
                    WHERE `room_id` = '$room_data[id]' 
                    AND `thumb` = '1'");

                if (mysqli_num_rows($thumb_q) > 0) {
                    $thumb_res = mysqli_fetch_assoc($thumb_q);
                    $room_thumb = ROOMS_IMG_PATH . $thumb_res['image'];
                }

                // Get additional room images
                $room_imgs = [];
                $imgs_q = mysqli_query($con, "SELECT * FROM `room_images` 
                    WHERE `room_id` = '$room_data[id]' 
                    AND `thumb` = '0' 
                    LIMIT 3");
                    
                while ($img_res = mysqli_fetch_assoc($imgs_q)) {
                    $room_imgs[] = ROOMS_IMG_PATH . $img_res['image'];
                }
                ?>
                
                <div class="card room-card">
                    <div class="position-relative overflow-hidden">
                        <div class="position-absolute top-0 start-0 w-100 p-3" style="background: linear-gradient(to bottom, rgba(0,0,0,0.6), transparent); z-index: 1;">
                            <h4 class="text-white mb-0"><?php echo $room_data['name']; ?></h4>
                        </div>
                        <img src="<?php echo $room_thumb; ?>" class="img-fluid w-100" alt="<?php echo $room_data['name']; ?>">
                        <?php if (!empty($room_data['area'])): ?>
                        <div class="position-absolute top-0 end-0 m-3 py-1 px-3 rounded-pill" style="background: rgba(255,255,255,0.8);">
                            <small class="fw-bold"><i class="bi bi-rulers me-1"></i><?php echo $room_data['area']; ?> m²</small>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="price-container mb-3">
                            <span class="room-price"><?php echo number_format($room_data['price'], 0, ',', '.'); ?> VND</span>
                            <span class="price-unit">mỗi đêm</span>
                        </div>
                        
                        <?php if (!empty($features_data)): ?>
                        <div class="features-container mb-4">
                            <h6 class="section-title"><i class="bi bi-stars me-2"></i>Tiện Nghi</h6>
                            <div class="d-flex flex-wrap">
                                <?php echo $features_data; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                       
                        <div class="gallery-container">
                            <h6 class="section-title mb-3"><i class="bi bi-images me-2"></i>Hình Ảnh Phòng</h6>
                            <div class="row g-3">
                            <?php if (!empty($room_imgs)): ?>
                            <?php 
                            $count = count($room_imgs);
                            $col_class = 'col-4'; // Default for 3 images
                            
                            // Adjust column size based on image count
                            if($count == 1) {
                                $col_class = 'col-12';
                            } else if($count == 2) {
                                $col_class = 'col-6';
                            }
                            ?>
                            <?php foreach($room_imgs as $img): ?>
                            <div class="<?php echo $col_class; ?>">
                                <div class="gallery-thumbnail">
                                    <img src="<?php echo $img; ?>" class="img-fluid rounded" style="height: <?php echo ($count == 1) ? '280px' : '160px'; ?>; object-fit: cover; width: 100%;" alt="Room Image">
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-md-12">
                <div class="card booking-form-card">
                    <div class="card-header">
                        <h5><i class="bi bi-calendar-check me-2"></i>Chi Tiết Đặt Phòng</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" id="booking_form">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tên Khách Hàng</label>
                                    <input name="name" type="text" value="<?php echo $user_data['name'] ?>"
                                        class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số Điện Thoại</label>
                                    <input name="phone" type="number" value="<?php echo $user_data['phonenum'] ?>"
                                        class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Check-in</label>
                                    <input name="checkin" onchange="check_availability()" type="date"
                                        class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Check-out</label>
                                    <input name="checkout" onchange="check_availability()" type="date"
                                        class="form-control" required>
                                </div>
                                
                                <div class="col-12">
                                    <div class="payment-info">
                                        <div class="d-flex">
                                            <div class="spinner-border loading-spinner d-none" id="info_loader" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="payment-info-text danger" id="pay_info">Vui lòng chọn ngày nhận phòng và trả phòng để tiếp tục</p>
                                        </div>
                                    </div>
                                    
                                    <button name="pay_now" class="btn pay-button w-100" disabled>
                                        Thanh Toán Ngay <i class="bi bi-credit-card-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card mt-4 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="useful-info-container">
                            <div class="useful-info-header">
                                <i class="bi bi-info-circle"></i>
                                <h5>Thông tin hữu ích</h5>
                            </div>
                            
                            <div class="useful-info-item">
                                <div class="useful-info-icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="useful-info-content">
                                    <div class="useful-info-label">Check-in</div>
                                    <div class="useful-info-value">8:00</div>
                                </div>
                            </div>
                            
                            <div class="useful-info-item">
                                <div class="useful-info-icon">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div class="useful-info-content">
                                    <div class="useful-info-label">Check-out</div>
                                    <div class="useful-info-value">15:00</div>
                                </div>
                            </div>
                            
                            <div class="useful-info-item">
                                <div class="useful-info-icon">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div class="useful-info-content">
                                    <div class="useful-info-label">Đặt phòng an toàn & bảo mật</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php') ?>

    <script>
        let booking_form = document.getElementById('booking_form');
        let info_loader = document.getElementById('info_loader');
        let pay_info = document.getElementById('pay_info');
        let payment_info = document.querySelector('.payment-info');

        function check_availability() {
            let checkin_val = booking_form.elements['checkin'].value;
            let checkout_val = booking_form.elements['checkout'].value;

            booking_form.elements['pay_now'].setAttribute('disabled', true);

            if (checkin_val != '' && checkout_val != '') {
                pay_info.classList.add('d-none');
                info_loader.classList.remove('d-none');
                payment_info.classList.remove('success', 'warning', 'danger');

                let data = new FormData();
                data.append('check_availability', '');
                data.append('check_in', checkin_val);
                data.append('check_out', checkout_val);

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/confirm_booking.php", true);

                xhr.onload = function () {
                    let data = JSON.parse(this.responseText);

                    if (data.status == 'check_in_out_equal') {
                        pay_info.innerText = "Bạn không thể trả phòng trong cùng một ngày!";
                        payment_info.classList.add('danger');
                        pay_info.classList.add('danger');
                    } else if (data.status == 'check_out_earlier') {
                        pay_info.innerText = "Ngày trả phòng sớm hơn ngày nhận phòng!";
                        payment_info.classList.add('danger');
                        pay_info.classList.add('danger');
                    } else if (data.status == 'check_in_earlier') {
                        pay_info.innerText = "Ngày nhận phòng sớm hơn ngày hôm nay!";
                        payment_info.classList.add('danger');
                        pay_info.classList.add('danger');
                    } else if (data.status == 'unavailable') {
                        pay_info.innerText = "Phòng đã được đặt trong thời gian này";
                        payment_info.classList.add('warning');
                        pay_info.classList.add('warning');
                    } else {
                        pay_info.innerHTML = "<i class='bi bi-calendar-check me-2'></i> Số ngày: <span class='fw-bold'>" + data.days + "</span> đêm<br><i class='bi bi-cash-coin me-2'></i> Tổng thanh toán: <span class='fw-bold'>" + data.payment.toLocaleString('vi-VN') + " VND</span>";
                        payment_info.classList.add('success');
                        pay_info.classList.remove('danger', 'warning');
                        pay_info.classList.add('success');
                        booking_form.elements['pay_now'].removeAttribute('disabled');
                    }
                    pay_info.classList.remove('d-none');
                    info_loader.classList.add('d-none');
                };

                xhr.send(data);
            }
        }
        
        let pay_now_button = document.querySelector('[name="pay_now"]');

        pay_now_button.addEventListener('click', function (e) {
            e.preventDefault();

            let check_in = booking_form.elements['checkin'].value;
            let check_out = booking_form.elements['checkout'].value;
            let room_id = '<?php echo $_SESSION['room']['id']; ?>';
            let user_id = '<?php echo $_SESSION['uId']; ?>';
            let room_price = '<?php echo $_SESSION['room']['price']; ?>'; 

            // Hiện loading
            info_loader.classList.remove('d-none');
            pay_info.classList.add('d-none');
            pay_now_button.setAttribute('disabled', true);
            pay_now_button.innerHTML = 'Đang xử lý <i class="bi bi-hourglass-split"></i>';

            let data = new FormData();
            data.append('check_in', check_in);
            data.append('check_out', check_out);
            data.append('room_id', room_id);
            data.append('user_id', user_id);
            data.append('room_price', room_price); 

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/create_paypal_order.php", true);

            xhr.onload = function () {
                console.log("Dữ liệu trả về từ server: ", this.responseText); // Kiểm tra dữ liệu trả về

                try {
                    let response = JSON.parse(this.responseText); // Thử phân tích JSON

                    // Kiểm tra mã trạng thái trả về
                    if (response.status === 'success') {
                        window.location.href = response.approval_url;  // Điều hướng đến URL thanh toán PayPal
                    } else {
                        alert('Lỗi: ' + response.message);  // Hiển thị thông báo lỗi
                        
                        // Ẩn loading
                        info_loader.classList.add('d-none');
                        pay_info.classList.remove('d-none');
                        pay_now_button.removeAttribute('disabled');
                        pay_now_button.innerHTML = 'Thanh Toán Ngay <i class="bi bi-credit-card-fill"></i>';
                    }
                } catch (e) {
                    // Xử lý lỗi khi phân tích dữ liệu JSON
                    console.error('Lỗi phân tích JSON:', e);
                    alert('Đã có lỗi khi xử lý dữ liệu từ server!');
                    
                    // Ẩn loading
                    info_loader.classList.add('d-none');
                    pay_info.classList.remove('d-none');
                    pay_now_button.removeAttribute('disabled');
                    pay_now_button.innerHTML = 'Thanh Toán Ngay <i class="bi bi-credit-card-fill"></i>';
                }
            };

            xhr.send(data);
        });

        // Set min date for check-in and check-out
        let today = new Date();
        today.setDate(today.getDate());
        let tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        
        let checkin_date_input = document.querySelector('input[name="checkin"]');
        let checkout_date_input = document.querySelector('input[name="checkout"]');
        
        let today_str = today.toISOString().split('T')[0];
        let tomorrow_str = tomorrow.toISOString().split('T')[0];
        
        checkin_date_input.setAttribute('min', today_str);
        checkout_date_input.setAttribute('min', tomorrow_str);
        
        // Add event listener to update min checkout date when checkin changes
        checkin_date_input.addEventListener('change', function(){
            let checkin_date = new Date(this.value);
            let min_checkout_date = new Date(checkin_date);
            min_checkout_date.setDate(min_checkout_date.getDate() + 1);
            let min_checkout_str = min_checkout_date.toISOString().split('T')[0];
            checkout_date_input.setAttribute('min', min_checkout_str);
            
            // If current checkout date is before new min date, update it
            if(checkout_date_input.value && new Date(checkout_date_input.value) <= checkin_date) {
                checkout_date_input.value = min_checkout_str;
                check_availability();
            }
        });
    </script>

</body>

</html>