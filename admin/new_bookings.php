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
    <title>Admin Panel - New Bookings</title>
    <?php require('inc/link.php'); ?>
    <link rel="stylesheet" href="css/new_bookings.css">
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="booking-page-title mb-4">
                    <i class="bi bi-calendar-check me-2"></i>ĐƠN ĐẶT PHÒNG MỚI
                </h3>

                <div class="card booking-card mb-4 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="card-title mb-0">Danh sách đơn đặt phòng mới</h5>
                                <p class="text-muted small mb-0">Quản lý và xử lý các đơn đặt phòng mới</p>
                            </div>
                            <div class="search-container">
                                <i class="bi bi-search search-icon"></i>   
                                <input type="text" oninput="get_bookings(this.value)" class="form-control refund-search" placeholder="Tìm theo mã đơn, tên, SĐT...">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table booking-table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Chi Tiết Người Dùng</th>
                                        <th scope="col">Chi Tiết Phòng</th>
                                        <th scope="col">Chi Tiết Đặt Phòng</th>
                                        <th scope="col">Thanh Toán</th>
                                        <th scope="col">Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody id="table_data">
                                    <!-- JavaScript sẽ thêm dữ liệu vào đây -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--assign room number modal -->
    <div class="modal fade" id="assign-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="assign_room_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chỉ Định Số Phòng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Số phòng</label>
                            <input type="text" name="room_no" class="form-control shadow-none" required>
                        </div>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Lưu ý: Chỉ gán số phòng khi người dùng đã đến!
                        </div>
                        <input type="hidden" name="booking_id">
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn cancel-btn" data-bs-dismiss="modal">HỦY</button>
                        <button type="submit" class="btn submit-btn">ĐỒNG Ý</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>
    <script src="scripts/booking.js"></script>
    <script>
        // Gọi hàm lấy dữ liệu đặt phòng khi tải trang
        document.addEventListener('DOMContentLoaded', function() {
            get_bookings('');

            // Chạy sau khi script tải dữ liệu
            setTimeout(() => {
                formatBookingElements();
            }, 500);
        });

        // Hàm định dạng các phần tử bảng sau khi dữ liệu được tải
        function formatBookingElements() {
            // Định dạng trạng thái đặt phòng
            document.querySelectorAll('[data-booking-status]').forEach(elem => {
                let status = elem.getAttribute('data-booking-status');
                elem.classList.add('booking-status');
                if(status === 'booked') {
                    elem.classList.add('booked');
                } else if(status === 'cancelled') {
                    elem.classList.add('cancelled');
                } else if(status === 'payment-pending') {
                    elem.classList.add('payment-pending');
                }
            });
            
            // Định dạng nút hành động
            document.querySelectorAll('.booking-action').forEach(elem => {
                elem.classList.add('action-btn');
            });
        }
    </script>
</body>

</html>