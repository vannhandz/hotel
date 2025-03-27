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
    <title>Admin Panel - Bookings Record</title>
    <?php require('inc/link.php'); ?>
    <link rel="stylesheet" href="css/booking_records.css">
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="booking-page-title mb-4">
                    <i class="bi bi-journal-bookmark me-2"></i>LỊCH SỬ ĐẶT PHÒNG
                </h3>

                <div class="card booking-card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                            <div class="mb-2 mb-md-0">
                                <h5 class="card-title fw-bold mb-1">Tất cả lịch sử đặt phòng</h5>
                                <p class="text-muted small mb-0">Quản lý và xem chi tiết tất cả đơn đặt phòng</p>
                            </div>
                            <div class="search-container">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" id="search_input" oninput="get_bookings(this.value)" 
                                    class="form-control booking-search" 
                                    placeholder="Tìm theo mã đơn, tên, SĐT...">
                            </div>
                        </div>

                        <div class="booking-filters mb-4">
                            <div class="row g-2">
                                <div class="col-12 col-md-auto">
                                    <button class="filter-btn active" onclick="filterBookings('all')">
                                        <i class="bi bi-card-list me-1"></i> Tất cả
                                    </button>
                                </div>
                                <div class="col-12 col-md-auto">
                                    <button class="filter-btn" onclick="filterBookings('booked')">
                                        <i class="bi bi-check-circle me-1"></i> Đã đặt phòng
                                    </button>
                                </div>
                                <div class="col-12 col-md-auto">
                                    <button class="filter-btn" onclick="filterBookings('refunded')">
                                        <i class="bi bi-cash-coin me-1"></i> Đã hoàn tiền
                                    </button>
                                </div>
                                <div class="col-12 col-md-auto">
                                    <button class="filter-btn" onclick="filterBookings('cancelled')">
                                        <i class="bi bi-x-circle me-1"></i> Đã hủy
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive booking-table-container">
                            <table class="table booking-table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Chi Tiết Người Dùng</th>
                                        <th scope="col">Chi Tiết Phòng</th>
                                        <th scope="col">Chi Tiết Đặt Phòng</th>
                                        <th scope="col">Trạng Thái</th>
                                        <th scope="col">Tải Hóa Đơn</th>
                                    </tr>
                                </thead>
                                <tbody id="table_data">
                                    <!-- JavaScript sẽ thêm dữ liệu vào đây -->
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="booking-empty-state text-center py-5 d-none">
                            <i class="bi bi-search fs-1 text-muted"></i>
                            <h5 class="mt-3">Không tìm thấy dữ liệu</h5>
                            <p class="text-muted">Thử tìm kiếm với từ khóa khác</p>
                        </div>
                        
                        <nav>
                            <ul class="pagination booking-pagination mt-4" id="table-pagination">
                               <!-- JavaScript sẽ tạo phân trang ở đây -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>
    <script src="scripts/booking_records.js"></script>
</body>

</html>