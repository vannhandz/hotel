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
    <link rel="stylesheet" href="css/dashboard.css">
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <!-- Dashboard Header -->
                <div class="dashboard-header">
                    <h3><i class="bi bi-speedometer2 me-2"></i>BẢNG ĐIỀU KHIỂN</h3>
                    <?php
                        if($is_shutdown['shutdown']){
                            echo <<<data
                            <div class="shutdown-message">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                Hệ thống đang trong trạng thái ngừng hoạt động!
                            </div>
                            data;
                        }
                    ?>
                </div>

                <!-- Summary Row -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="dashboard-summary-card">
                            <div class="row g-0">
                                <div class="col-md-3 col-sm-6 border-end">
                                    <div class="summary-item">
                                        <i class="bi bi-bookmark-plus summary-icon text-success"></i>
                                        <div>
                                            <h3><?php echo $current_bookings['new_bookings'] ?></h3>
                                            <p>Đặt Phòng Mới</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 border-end">
                                    <div class="summary-item">
                                        <i class="bi bi-cash-coin summary-icon text-warning"></i>
                                        <div>
                                            <h3><?php echo $current_bookings['refund_bookings'] ?></h3>
                                            <p>Hoàn Tiền</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 border-end">
                                    <div class="summary-item">
                                        <i class="bi bi-question-circle summary-icon text-info"></i>
                                        <div>
                                            <h3><?php echo $unread_queries['count'] ?></h3>
                                            <p>Yêu Cầu Mới</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="summary-item">
                                        <i class="bi bi-star summary-icon text-primary"></i>
                                        <div>
                                            <h3><?php echo $unread_reviews['count'] ?></h3>
                                            <p>Đánh Giá Mới</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts & Analytics Row -->
                <div class="row dashboard-section">
                    <!-- Booking Analytics Chart -->
                    <div class="col-md-8 mb-4">
                        <div class="dashboard-card chart-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Biểu Đồ Đặt Phòng</h5>
                                <select class="form-select shadow-none bg-light w-auto" id="bookingChartPeriod" onchange="updateBookingChart(this.value)">
                                    <option value="1">30 ngày qua</option>
                                    <option value="2">90 ngày qua</option>
                                    <option value="3">1 năm qua</option>
                                </select>
                            </div>
                            <div class="card-body">
                                <canvas id="bookingChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- User Distribution Chart -->
                    <div class="col-md-4 mb-4">
                        <div class="dashboard-card chart-card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Người Dùng</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="userChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Analytics Cards -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h5><i class="bi bi-graph-up me-2"></i>Phân Tích Đặt Phòng</h5>
                        <select class="form-select shadow-none bg-light w-auto" onchange="booking_analytics(this.value)">
                            <option value="1">30 ngày qua</option>
                            <option value="2">90 ngày qua</option>
                            <option value="3">1 năm qua</option>
                            <option value="4">Tất Cả</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="dashboard-card primary">
                                <i class="bi bi-cash-stack card-icon"></i>
                                <div class="card-body">
                                    <h5 class="card-title">Tổng Doanh Thu</h5>
                                    <div class="card-value" id="total_bookings">0</div>
                                    <div class="card-subtitle" id="total_amt">0 VND</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="dashboard-card success">
                                <i class="bi bi-check-circle card-icon"></i>
                                <div class="card-body">
                                    <h5 class="card-title">Hoàn Tất Nhận Phòng</h5>
                                    <div class="card-value" id="active_bookings">0</div>
                                    <div class="card-subtitle" id="active_amt">0 VND</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="dashboard-card warning">
                                <i class="bi bi-x-circle card-icon"></i>
                                <div class="card-body">
                                    <h5 class="card-title">Hoàn Tiền Đặt Phòng</h5>
                                    <div class="card-value" id="cancelled_bookings">0</div>
                                    <div class="card-subtitle" id="cancelled_amt">0 VND</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Analytics -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h5><i class="bi bi-people me-2"></i>Hoạt Động Người Dùng</h5>
                        <select class="form-select shadow-none bg-light w-auto" onchange="user_analytics(this.value)">
                            <option value="1">30 ngày qua</option>
                            <option value="2">90 ngày qua</option>
                            <option value="3">1 năm qua</option>
                            <option value="4">Tất Cả</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="dashboard-card success">
                                <i class="bi bi-person-plus card-icon"></i>
                                <div class="card-body">
                                    <h5 class="card-title">Đăng Ký Mới</h5>
                                    <div class="card-value" id="total_new_reg">0</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="dashboard-card primary">
                                <i class="bi bi-chat-left-text card-icon"></i>
                                <div class="card-body">
                                    <h5 class="card-title">Yêu Cầu</h5>
                                    <div class="card-value" id="total_queries">0</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="dashboard-card primary">
                                <i class="bi bi-star-half card-icon"></i>
                                <div class="card-body">
                                    <h5 class="card-title">Đánh Giá</h5>
                                    <div class="card-value" id="total_reviews">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Statistics -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h5><i class="bi bi-person-badge me-2"></i>Thống Kê Người Dùng</h5>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <div class="dashboard-card info">
                                <i class="bi bi-people card-icon"></i>
                                <div class="card-body">
                                    <h5 class="card-title">Tổng</h5>
                                    <div class="card-value"><?php echo $current_users['total'] ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="dashboard-card success">
                                <i class="bi bi-person-check card-icon"></i>
                                <div class="card-body">
                                    <h5 class="card-title">Hoạt Động</h5>
                                    <div class="card-value"><?php echo $current_users['active'] ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="dashboard-card warning">
                                <i class="bi bi-person-dash card-icon"></i>
                                <div class="card-body">
                                    <h5 class="card-title">Đình Chỉ</h5>
                                    <div class="card-value"><?php echo $current_users['inactive'] ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="dashboard-card danger">
                                <i class="bi bi-person-x card-icon"></i>
                                <div class="card-body">
                                    <h5 class="card-title">Chưa Xác Minh</h5>
                                    <div class="card-value"><?php echo $current_users['unverified'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>
    <script src="scripts/dashboards.js"></script>
    <script>
        // Chart initialization
        let bookingChart;
        let userChart;
        
        // Initialize user chart
        function initUserChart() {
            const userCtx = document.getElementById('userChart').getContext('2d');
            
            userChart = new Chart(userCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Hoạt Động', 'Đình Chỉ', 'Chưa Xác Minh'],
                    datasets: [{
                        data: [
                            <?php echo $current_users['active'] ?>, 
                            <?php echo $current_users['inactive'] ?>, 
                            <?php echo $current_users['unverified'] ?>
                        ],
                        backgroundColor: [
                            '#20c997',
                            '#ffc107',
                            '#dc3545'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
        
        // Initialize booking chart
        function initBookingChart() {
            const bookingCtx = document.getElementById('bookingChart').getContext('2d');
            
            bookingChart = new Chart(bookingCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Đặt Phòng',
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                        borderColor: '#2ac1ac',
                        backgroundColor: 'rgba(42, 193, 172, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2
                    }, {
                        label: 'Hủy Đặt Phòng',
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        }
        
        // Update booking chart with data from server
        function updateBookingChart(period = 1) {
            fetch(`ajax/dashboard_chart.php?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    // Update chart data
                    bookingChart.data.labels = data.labels;
                    bookingChart.data.datasets[0].data = data.bookings;
                    bookingChart.data.datasets[1].data = data.cancellations;
                    bookingChart.update();
                })
                .catch(error => console.error('Error fetching chart data:', error));
        }
        
        // Initialize charts on page load
        window.onload = function() {
            booking_analytics();
            user_analytics();
            initUserChart();
            initBookingChart();
            updateBookingChart();
        };
    </script>
</body>

</html>

</html>