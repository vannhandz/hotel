<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/link.php') ?>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bookings.css">
    <title><?php echo $settings_r['site_title'] ?> - Đặt Phòng</title>
</head>

<body class="bg-light">

    <?php 
        require('inc/header.php') ;
        if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
            redirect('index.php');
        }
    ?>

    <!-- Page Header Banner -->
    <div class="page-header-banner">
        <div class="container">
            <h1 class="text-center text-white">Đặt Phòng Của Tôi</h1>
            <div class="header-line"></div>
            <div class="breadcrumb-container">
                <div class="breadcrumb-pill">
                    <a href="index.php">Trang Chủ</a>
                    <span class="divider">/</span>
                    <span class="current">Đặt Phòng</span>
                </div>
            </div>
        </div>
    </div>

     <!-- chi tiet dat phong -->
    <div class="container booking-container">
        <div class="row">
            <?php 
                $query="SELECT * FROM `booking_order` bo 
                    INNER JOIN `user_cred` u ON bo.user_id = u.id
                    INNER JOIN `rooms` r ON bo.room_id = r.id
                    WHERE ((bo.booking_status='booked') 
                    OR (bo.booking_status='cancelled') 
                    OR (bo.booking_status='refunded'))
                    AND (bo.user_id=?)
                    ORDER BY bo.booking_id DESC";

                $result=select($query,[$_SESSION['uId']],'i');

                if(mysqli_num_rows($result) == 0) {
                    echo '<div class="col-12 text-center py-5">
                        <i class="bi bi-calendar-x" style="font-size: 5rem; color: #ccc;"></i>
                        <h3 class="mt-3">Bạn chưa có đơn đặt phòng nào</h3>
                        <a href="rooms.php" class="btn btn-sm btn-outline-dark mt-3">Xem Phòng</a>
                    </div>';
                }

                while ($data=mysqli_fetch_assoc(result: $result)){

                    $date = date("d-m-Y", strtotime($data['booking_date']));
                    $checkin = date("d-m-Y", strtotime($data['check_in']));
                    $checkout = date("d-m-Y", strtotime($data['check_out']));

                    $status_bg = "";
                    $btn = "";

                    if ($data['booking_status'] == 'booked')
                    {
                        $status_bg = "bg-success";
                        if ($data['arrival'] == 1)
                        {
                            $btn = "<div class='booking-actions'>
                                <a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm'><i class='bi bi-file-earmark-pdf me-1'></i>Tải PDF</a>";
                            
                            if ($data['rate_review'] == 0) {
                                $btn .= "<button type='button' onclick='review_room($data[booking_id], $data[room_id])' data-bs-toggle='modal' data-bs-target='#reviewModal' class='btn btn-dark btn-sm'><i class='bi bi-star me-1'></i>Đánh Giá</button>";
                            }
                            
                            $btn .= "</div>";
                        }else{
                            $btn = "<div class='booking-actions'>
                                <button onclick='cancel_booking($data[booking_id])' type='button' class='btn btn-danger btn-sm'><i class='bi bi-x-circle me-1'></i>Hủy Đơn</button>
                            </div>";
                        }
                    }
                    else if ($data['booking_status'] == 'cancelled')
                    {
                        $status_bg = "bg-danger";
                        if ($data['refund'] == 0)
                        {
                            $btn = "<div class='booking-status'><span class='badge bg-primary'>Đang hoàn tiền!</span></div>";
                        }
                        else
                        {
                            $btn = "<div class='booking-actions'>
                                <a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm'><i class='bi bi-file-earmark-pdf me-1'></i>Tải PDF</a>
                            </div>";
                        }
                    }
                    else{
                        $status_bg = "bg-warning";
                        $btn = "<div class='booking-actions'>
                            <a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm'><i class='bi bi-file-earmark-pdf me-1'></i>Tải PDF</a>
                        </div>";
                    }
                    
                    $formatted_price = number_format($data['price'], 0, ',', '.');
                    $formatted_price_total = number_format($data['total_amount'], 0, ',', '.');

                    $status_text = $data['booking_status'];
                    if($status_text == 'booked') $status_text = 'Đã Đặt';
                    else if($status_text == 'cancelled') $status_text = 'Đã Hủy';
                    else if($status_text == 'refunded') $status_text = 'Đã Hoàn Tiền';

                    // Get room thumbnail
                    $room_thumb = "images/rooms/thumbnail.jpg";
                    $thumb_q = mysqli_query($con, "SELECT * FROM `room_images` 
                        WHERE `room_id` = '$data[room_id]' 
                        AND `thumb` = '1'");

                    if (mysqli_num_rows($thumb_q) > 0) {
                        $thumb_res = mysqli_fetch_assoc($thumb_q);
                        $room_thumb = ROOMS_IMG_PATH . $thumb_res['image'];
                    }

                    // Hiển thị thông tin phương thức thanh toán
                    $payment_method = '';
                    if(isset($data['payment_method'])) {
                        if($data['payment_method'] == 'paypal') {
                            $payment_method = '<div class="booking-details-item">
                                <div class="booking-details-label">Thanh toán</div>
                                <div class="booking-details-value">
                                    <span class="badge bg-info">
                                        <i class="bi bi-paypal me-1"></i> PayPal
                                    </span>
                                </div>
                            </div>';
                        } 
                        else if($data['payment_method'] == 'vnpay') {
                            $payment_method = '<div class="booking-details-item">
                                <div class="booking-details-label">Thanh toán</div>
                                <div class="booking-details-value">
                                    <span class="badge bg-primary">
                                        <i class="bi bi-bank me-1"></i> Chuyển khoản
                                    </span>
                                </div>
                            </div>';
                        }
                    }

                    echo <<<bookings
                        <div class='col-lg-4 col-md-6 mb-4'>
                            <div class='booking-card'>
                                <div class='booking-header'>
                                    <h5 class='booking-room-name'>$data[name]</h5>
                                    <div class='booking-price'>$formatted_price VND <small class="text-muted">mỗi đêm</small></div>
                                </div>
                                <div class='booking-body'>
                                    <div class='booking-dates'>
                                        <div class='booking-date-item'>
                                            <div class='booking-date-label'>Check in</div>
                                            <div class='booking-date-value'>$checkin</div>
                                        </div>
                                        <div class='booking-date-item'>
                                            <div class='booking-date-label'>Check out</div>
                                            <div class='booking-date-value'>$checkout</div>
                                        </div>
                                    </div>
                                    <div class='booking-details'>
                                        <div class='booking-details-item booking-total'>
                                            <div class='booking-details-label'>Tổng Thanh Toán</div>
                                            <div class='booking-details-value'>$formatted_price_total VND</div>
                                        </div>
                                        <div class='booking-details-item'>
                                            <div class='booking-details-label'>Mã Hóa Đơn</div>
                                            <div class='booking-details-value'>$data[invoice_id]</div>
                                        </div>
                                        <div class='booking-details-item'>
                                            <div class='booking-details-label'>Ngày Đặt</div>
                                            <div class='booking-details-value'>$date</div>
                                        </div>
                                        $payment_method
                                    </div>
                                    <div class='booking-status'>
                                        <span class='badge $status_bg'>$status_text</span>
                                    </div>
                                    $btn
                                </div>
                            </div>
                        </div>
                    bookings;
                }
            ?>
        </div>
    </div>

    <!-- Rating & Review Modal -->
    <div class="modal fade" id="reviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="review-form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center"><i class="bi bi-star-fill fs-3 me-2 text-warning"></i>Xếp hạng & Đánh giá</h5>
                        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Xếp Hạng</label>
                            <div class="rating-container mb-2">
                                <div class="rating-stars">
                                    <i class="bi bi-star-fill rating-star" data-rating="1"></i>
                                    <i class="bi bi-star rating-star" data-rating="2"></i>
                                    <i class="bi bi-star rating-star" data-rating="3"></i>
                                    <i class="bi bi-star rating-star" data-rating="4"></i>
                                    <i class="bi bi-star rating-star" data-rating="5"></i>
                                </div>
                                <div class="rating-text">Tuyệt vời</div>
                            </div>
                            <select class="form-select shadow-none d-none" name="rating">
                                <option value="5">Tuyệt vời</option>
                                <option value="4">Hài lòng</option>
                                <option value="3">Tạm ổn</option>
                                <option value="2">Không hài lòng</option>
                                <option value="1">Rất tệ</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Đánh Giá</label>
                            <textarea type="password" name="review" rows="3" required class="form-control shadow-none" placeholder="Hãy chia sẻ trải nghiệm của bạn..."></textarea>
                        </div>

                        <input type="hidden" name="booking_id">
                        <input type="hidden" name="room_id">

                        <div class="text-end">
                            <button type="submit" class="btn custom-bg text-white shadow-none">Gửi Đánh Giá</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    if(isset($_GET['cancel_status'])){
        alert('success', 'Đã hủy đặt phòng');
    }else if (isset($_GET['review_status'])) {
        alert('success', 'Cảm ơn bạn đã đánh giá và bình luận!');
    }
    ?>

    <?php require('inc/footer.php') ?>

    <script>
    function cancel_booking(id) {
        if(confirm('Bạn có chắc chắn hủy đặt phòng?')) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/cancel_booking.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function(){
                if(this.responseText==1){
                    window.location.href="bookings.php?cancel_status=true";
                }
                else{
                    alert('error', 'Hủy không thành công!');
                }
            }
            xhr.send('cancel_booking&id='+id);
        }
    }

    let review_form = document.getElementById('review-form');
    
    // Star rating functionality
    const ratingStars = document.querySelectorAll('.rating-star');
    const ratingText = document.querySelector('.rating-text');
    const ratingSelect = document.querySelector('select[name="rating"]');
    const ratingTexts = ['Rất tệ', 'Không hài lòng', 'Tạm ổn', 'Hài lòng', 'Tuyệt vời'];
    
    // Set initial rating
    let currentRating = 5;
    updateStars(currentRating);
    
    ratingStars.forEach(star => {
        star.addEventListener('click', function() {
            currentRating = parseInt(this.dataset.rating);
            ratingSelect.value = currentRating;
            updateStars(currentRating);
        });
        
        star.addEventListener('mouseover', function() {
            const hoverRating = parseInt(this.dataset.rating);
            highlightStars(hoverRating);
        });
        
        star.addEventListener('mouseout', function() {
            highlightStars(currentRating);
        });
    });
    
    function highlightStars(rating) {
        ratingStars.forEach(star => {
            const starRating = parseInt(star.dataset.rating);
            if (starRating <= rating) {
                star.classList.remove('bi-star');
                star.classList.add('bi-star-fill');
                star.style.color = '#ffc107';
            } else {
                star.classList.remove('bi-star-fill');
                star.classList.add('bi-star');
                star.style.color = '#6c757d';
            }
        });
        ratingText.textContent = ratingTexts[rating-1];
    }
    
    function updateStars(rating) {
        ratingSelect.value = rating;
        highlightStars(rating);
    }

    function review_room(bid, rid) {
        review_form.elements['booking_id'].value = bid;
        review_form.elements['room_id'].value = rid;
        
        // Reset stars to 5 by default
        currentRating = 5;
        updateStars(currentRating);
    }
    
    review_form.addEventListener('submit', function(e) {
        e.preventDefault();

        let data = new FormData();

        data.append('review_form', '');
        data.append('rating', review_form.elements['rating'].value);
        data.append('review', review_form.elements['review'].value);
        data.append('booking_id', review_form.elements['booking_id'].value);
        data.append('room_id', review_form.elements['room_id'].value);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/review_room.php", true);
        xhr.onload = function() {
            if (this.responseText == 1) {
                window.location.href = 'bookings.php?review_status=true';
            } else {
                var myModal = document.getElementById('reviewModal');
                var modal = bootstrap.Modal.getInstance(myModal);
                modal.hide();
                alert('error', "Đánh giá và xếp hạng không thành công!");
            }
        }

        xhr.send(data);
    });
    </script>
 <?php require('chat.php') ?>
</body>

</html>