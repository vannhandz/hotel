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
    <title>Admin Panel - Users</title>
    <?php require('inc/link.php'); ?>
    <link rel="stylesheet" href="css/users.css">
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="user-page-title">QUẢN LÝ NGƯỜI DÙNG</h3>

                <div class="card user-card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="card-title mb-0">Danh sách người dùng</h5>
                                <p class="text-muted small mb-0">Quản lý tất cả người dùng trong hệ thống</p>
                            </div>
                            <input type="text" oninput="search_user(this.value)" class="form-control user-search w-25 ms-auto" placeholder="Tìm kiếm...">
                        </div>

                        <div class="table-responsive">
                            <table class="table user-table" style="min-width: 1300px;">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Tên</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Số Điện Thoại</th>
                                        <th scope="col">Đã Xác Minh</th>
                                        <th scope="col">Trạng Thái</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Hoạt động</th>
                                    </tr>
                                </thead>
                                <tbody id="users-data">
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
    <script src="scripts/users.js"></script>
    <script>
        // Format user status when data is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // This will be executed after users.js loads the data
            setTimeout(() => {
                formatUserElements();
            }, 500);
        });

        // Function to format table elements after data is loaded
        function formatUserElements() {
            // Format status badges
            document.querySelectorAll('[data-status]').forEach(elem => {
                let status = elem.getAttribute('data-status');
                elem.classList.add('user-status');
                if(status === 'active') {
                    elem.classList.add('active');
                } else {
                    elem.classList.add('inactive');
                }
            });
            
            // Format verification indicators
            document.querySelectorAll('[data-verified]').forEach(elem => {
                let verified = elem.getAttribute('data-verified');
                let span = document.createElement('span');
                span.classList.add('user-verified');
                span.classList.add(verified === 'yes' ? 'yes' : 'no');
                elem.prepend(span);
            });
        }
    </script>
</body>

</html>