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
    <title>Admin Panel - Refund Bookings</title>
    <?php require('inc/link.php'); ?>
    <link rel="stylesheet" href="css/refund_bookings.css">
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="refund-page-title mb-4">
                    <i class="bi bi-cash-coin me-2"></i>HOÀN TIỀN ĐẶT PHÒNG
                </h3>

                <div class="card refund-card mb-4 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="card-title mb-0">Danh sách hoàn tiền</h5>
                                <p class="text-muted small mb-0">Quản lý và xử lý các đơn hoàn tiền</p>
                            </div>
                            <div class="search-container">
                                <i class="bi bi-search search-icon"></i>   
                                <input type="text" oninput="get_bookings(this.value)" class="form-control refund-search" placeholder="Tìm theo mã đơn, tên, SĐT...">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table refund-table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Chi Tiết Người Dùng</th>
                                        <th scope="col">Chi Tiết Phòng</th>
                                        <th scope="col">Chi Tiết Đặt Phòng</th>
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

    <?php require('inc/scripts.php'); ?>
    <script src="scripts/refund_bookings.js"></script> 
    <script>
        // Định dạng các phần tử sau khi dữ liệu được tải
        document.addEventListener('DOMContentLoaded', function() {
            // Chạy sau khi script tải dữ liệu
            setTimeout(() => {
                formatRefundElements();
            }, 500);
        });

        // Hàm định dạng các phần tử bảng sau khi dữ liệu được tải
        function formatRefundElements() {
            // Định dạng nút hoàn tiền
            document.querySelectorAll('.refund-btn').forEach(elem => {
                elem.classList.add('refund-action-btn');
                elem.classList.add('refund-approve-btn');
            });
            
            // Thêm màu sắc xen kẽ cho hàng
            document.querySelectorAll('#table_data tr').forEach((row, index) => {
                if (index % 2 === 1) {
                    row.style.backgroundColor = 'rgba(0, 0, 0, 0.015)';
                }
            });
        }
    </script>
</body>

</html>