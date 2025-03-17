<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/link.php') ?>
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo $settings_r['site_title'] ?> CONFIRM BOOKING</title>
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

    ?>


    <div class="container">
        <div class="row">

            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold">CONFIRM BOOKING</h2>
                <div style="font-size:14px">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="rooms.php" class="text-secondary text-decoration-none">ROOMS</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">CONFIRM</a>
                </div>
            </div>

            <div class="col-lg-7 col-md-12 px-4">
                <?php

                $room_thumb = ROOMS_IMG_PATH . "thumbnail.jpg";
                $thumb_q = mysqli_query($con, "SELECT * FROM `room_images` 
                    WHERE `room_id` = '$room_data[id]' 
                    AND `thumb` = '1'");

                if (mysqli_num_rows($thumb_q) > 0) {
                    $thumb_res = mysqli_fetch_assoc($thumb_q);
                    $room_thumb = ROOMS_IMG_PATH . $thumb_res['image'];
                }

                echo <<<data
                    <div class="card p-3 shadow-sm rounded">
                        <img src="$room_thumb" class="img-fluid rounded mb-3">
                        <h5>$room_data[name]</h5>
                        <h6>
                data;

                // Tách phần mã PHP để định dạng giá tiền
                echo number_format($room_data['price'], 0, ',', '.') . ' VND per night';

                echo <<<data
                        </h6>
                    </div>
                data;

                ?>
            </div>

            <div class="col-lg-5 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <form action="" method="POST" id="booking_form">
                            <h6 class="mb-3">BOOKING DETAILS</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label ">Name</label>
                                    <input name="name" type="text" value="<?php echo $user_data['name'] ?>"
                                        class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label ">Phone</label>
                                    <input name="phone" type="number" value="<?php echo $user_data['phonenum'] ?>"
                                        class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label ">Check-in</label>
                                    <input name="checkin" onchange="check_availability()" type="date"
                                        class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label ">Check-out</label>
                                    <input name="checkout" onchange="check_availability()" type="date"
                                        class="form-control shadow-none" required>
                                </div>
                                <div class="col-12">
                                    <div class="spinner-border text-info mb-3 d-none" id="info_loader" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <h6 class="mb-3 text-danger" id="pay_info">Cung cấp ngày nhận phòng và trả phòng!
                                    </h6>
                                    <button name="pay_now" class="btn w-100 text-white custom-bg shadow-none mb-1"
                                        disabled>Pay Now</button>
                                </div>
                            </div>
                        </form>
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

        function check_availability() {
            let checkin_val = booking_form.elements['checkin'].value;
            let checkout_val = booking_form.elements['checkout'].value;

            booking_form.elements['pay_now'].setAttribute('disabled', true);

            if (checkin_val != '' && checkout_val != '') {
                pay_info.classList.add('d-none');
                pay_info.classList.replace('text-dark', 'text-danger');
                info_loader.classList.remove('d-none');

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
                    } else if (data.status == 'check_out_earlier') {
                        pay_info.innerText = "Ngày trả phòng sớm hơn ngày nhận phòng!";
                    } else if (data.status == 'check_in_earlier') {
                        pay_info.innerText = "Ngày nhận phòng sớm hơn ngày hôm nay!";
                    } else if (data.status == 'unavailable') {
                        pay_info.innerText = "Đã được đặt trong thời gian trước đó";
                    } else {
                        pay_info.innerHTML = "Số ngày: " + data.days + "<br>Tổng số tiền phải trả: " + data.payment.toLocaleString('vi-VN') + " VND";
                        pay_info.classList.replace('text-danger', 'text-dark');
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
                    }
                } catch (e) {
                    // Xử lý lỗi khi phân tích dữ liệu JSON
                    console.error('Lỗi phân tích JSON:', e);
                    alert('Đã có lỗi khi xử lý dữ liệu từ server!');
                }
            };

            xhr.send(data);
        });

    </script>
     <?php require('chat.php') ?>
</body>

</html>