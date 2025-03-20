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
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">DANH SÁCH ĐẶT PHÒNG</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <input type="text" id="search_input" oninput="get_bookings(this.value)" class="form-control shadow-none w-25 ms-auto" placeholder="Tìm kiếm...">
                        </div>

                        <div class="table-responsive" >
                            <table class="table table-hover border ">
                                <thead>
                                    <tr class="bg-dark text-light" style="min-width: 1200px">
                                        <th scope="col">#</th>
                                        <th scope="col">Chi Tiết Người Dùng</th>
                                        <th scope="col">Chi Tiết Phòng</th>
                                        <th scope="col">Chi Tiết Đặt Phòng</th>
                                        <th scope="col">Trạng Thái</th>
                                        <th scope="col">Download PDF</th>
                                    </tr>
                                </thead>
                                <tbody id="table_data">
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination mt-2" id="table-pagination">
                               <li>
                                1
                               </li>
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