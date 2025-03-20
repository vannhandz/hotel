<?php
require('inc/esentials.php');
require('inc/db_config.php');
adminLogin();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Dashboard</title>
    <?php require('inc/link.php'); ?>
</head>

<body class="bg-light">
    <?php
        require('inc/header.php'); 

        $is_shutdown = mysqli_fetch_assoc(mysqli_query($con, "SELECT `shutdown` FROM `settings`"));

        $current_bookings = mysqli_fetch_assoc(mysqli_query($con, "SELECT 
                            COUNT(CASE WHEN booking_status='booked' AND arrival=0 THEN 1 END) AS `new_bookings`,
                            COUNT(CASE WHEN booking_status='cancelled' AND refund=0 THEN 1 END) AS `refund_bookings`
                            FROM `booking_order`"));

        $unread_queries = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(sr_no) AS `count` FROM `user_queries` WHERE `seen`=0"));

        $unread_reviews = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(sr_no) AS `count` FROM `rating_review` WHERE `seen`=0"));

        $current_users = mysqli_fetch_assoc(mysqli_query($con, "SELECT 
                            COUNT(id) AS 'total',
                            COUNT(CASE WHEN `status` = 1 THEN 1 END) AS 'active',
                            COUNT(CASE WHEN `status` = 0 THEN 1 END) AS 'inactive',
                            COUNT(CASE WHEN `is_verified` = 0 THEN 1 END) AS 'unverified'
                            FROM `user_cred`"));
     
    ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3>BẢNG ĐIỀU KHIỂN</h3>
                    <?php
                        if($is_shutdown['shutdown']){
                            echo <<<data
                            <h6 class="badge bg-danger py-2 px-3 rounded">Hệ thống đang trong trạng thái ngừng hoạt động!</h6>
                            data;
                        }
                    ?>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3 mb-4">
                        <a href="new_bookings.php" class="text-decoration-none">
                        <div class="card text-center text-success p-3">
                            <h6>Đặt Phòng Mới</h6>
                            <h1 class="mt-2 mb-0"><?php echo $current_bookings['new_bookings'] ?></h1>
                        </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-4">
                        <a href="refund_bookings.php" class="text-decoration-none">
                        <div class="card text-center text-warning p-3">
                            <h6>Hoàn Tiền Đặt Phòng</h6>
                            <h1 class="mt-2 mb-0"><?php echo $current_bookings['refund_bookings'] ?></h1>
                        </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-4">
                        <a href="user_queries.php" class="text-decoration-none">
                        <div class="card text-center text-info p-3">
                            <h6>Yêu Cầu Của Người Dùng</h6>
                            <h1 class="mt-2 mb-0"><?php echo $unread_queries['count'] ?></h1>
                        </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-4">
                        <a href="rate_review.php" class="text-decoration-none">
                        <div class="card text-center text-info p-3">
                            <h6>Xếp Hạng & Đánh Giá</h6>
                            <h1 class="mt-2 mb-0"><?php echo $unread_reviews['count'] ?></h1>
                        </div>
                        </a>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5>Phân tích đặt phòng</h5>
                    <select class="form-select shadow-none bg-light w-auto" onchange="booking_analytics(this.value)">
                        <option value="1">30 ngày qua</option>
                        <option value="2">90 ngày qua</option>
                        <option value="3">1 năm qua</option>
                        <option value="4">Tất Cả</option>
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6>Tổng Doanh Thu</h6>
                            <h1 class="mt-2 mb-0" id="total_bookings">0</h1>
                            <h4 class="mt-2 mb-0" id="total_amt">5 VND</h4>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-success p-3">
                            <h6>Hoàn Tất Nhận Phòng</h6>
                            <h1 class="mt-2 mb-0" id="active_bookings">0</h1>
                            <h4 class="mt-2 mb-0" id="active_amt">0 VND</h4>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-warning p-3">
                            <h6>Hoàn Tiền Đặt Phòng</h6>
                            <h1 class="mt-2 mb-0" id="cancelled_bookings">5</h1>
                            <h4 class="mt-2 mb-0" id="cancelled_amt">5 VND</h4>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5>Xem Xét</h5>
                    <select class="form-select shadow-none bg-light w-auto" onchange="user_analytics(this.value)">
                        <option value="1">30 ngày qua</option>
                        <option value="2">90 ngày qua</option>
                        <option value="3">1 năm qua</option>
                        <option value="4">Tất Cả</option>
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-success p-3">
                            <h6>Đăng Ký Mới</h6>
                            <h1 class="mt-2 mb-0" id="total_new_reg">5</h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6>Yêu Cầu</h6>
                            <h1 class="mt-2 mb-0" id="total_queries">5</h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6>Đánh Giá</h6>
                            <h1 class="mt-2 mb-0" id="total_reviews">5</h1>
                        </div>
                    </div>
                </div>

                <h5>Người Dùng</h5>
                <div class="row mb-3">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-info p-3">
                            <h6>Tổng</h6>
                            <h1 class="mt-2 mb-0"><?php echo $current_users['total'] ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-success p-3">
                            <h6>Hoạt Động </h6>
                            <h1 class="mt-2 mb-0"><?php echo $current_users['active'] ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-warning p-3">
                            <h6>Đình Chỉ</h6>
                            <h1 class="mt-2 mb-0"><?php echo $current_users['inactive'] ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-danger p-3">
                            <h6>Chưa Xác Minh</h6>
                            <h1 class="mt-2 mb-0"><?php echo $current_users['unverified'] ?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>
    <script src="scripts/dashboards.js"></script>                  
   
</body>

</html>