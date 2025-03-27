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
    <title>Admin Panel - Rooms</title>
    <?php require('inc/link.php'); ?>
    <link rel="stylesheet" href="css/rooms.css">
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="rooms-page-title">Quản Lý Phòng</h3>

                <div class="card rooms-card mb-4">
                    <div class="card-body">

                        <div class="rooms-action-bar">
                            <button type="button" class="add-room-btn" data-bs-toggle="modal"
                                data-bs-target="#add-room">
                                <i class="bi bi-plus-square"></i> Thêm Phòng
                            </button>
                        </div>

                        <div class="rooms-table-container">
                            <table class="table rooms-table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Tên</th>
                                        <th scope="col">Diện Tích</th>
                                        <th scope="col">Khách hàng</th>
                                        <th scope="col">Giá</th>
                                        <th scope="col">Số Lượng</th>
                                        <th scope="col">Trạng Thái</th>
                                        <th scope="col">Hoạt Động</th>
                                    </tr>
                                </thead>
                                <tbody id="room-data">
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>


    <!--room modal -->
    <div class="modal fade" id="add-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="add_room_form" autocomplete="off" class="room-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title room-modal-title">Thêm Phòng</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label room-form-label">Tên</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label room-form-label">Diện Tích</label>
                                <input type="text" min="1" name="area" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label room-form-label">Giá</label>
                                <input type="number" min="1" name="price" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label room-form-label">Só Lượng</label>
                                <input type="number" min="1" name="quantity" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label room-form-label">Người Lớn</label>
                                <input type="number" min="1" name="adult" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label room-form-label">Trẻ Em</label>
                                <input type="number" min="1" name="children" class="form-control shadow-none" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label room-form-label">Tính Năng</label>
                                <div class="feature-checkbox-group">
                                    <?php
                                    $res = selectAll('features');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo "
                                                <div class='feature-checkbox'>
                                                    <input type='checkbox' name='features' value='$opt[id]' class='form-check-input shadow-none'>
                                                    <span>$opt[name]</span>
                                                </div>
                                            ";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label room-form-label">Tiện Nghi</label>
                                <div class="facility-checkbox-group">
                                    <?php
                                    $res = selectAll('facilities');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo "
                                                <div class='facility-checkbox'>
                                                    <input type='checkbox' name='facilities' value='$opt[id]' class='form-check-input shadow-none'>
                                                    <span>$opt[name]</span>
                                                </div>
                                            ";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label room-form-label">Mô Tả</label>
                                <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer room-modal-footer">
                        <button type="reset" class="room-btn-cancel" data-bs-dismiss="modal">HỦY</button>
                        <button type="submit" class="room-btn-submit">ĐỒNG Ý</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <!--edit room modal -->
    <div class="modal fade" id="edit-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="edit_room_form" autocomplete="off" class="room-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title room-modal-title">Sửa Phòng</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label room-form-label">Tên</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label room-form-label">Diện Tích</label>
                                <input type="text" min="1" name="area" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label room-form-label">Giá</label>
                                <input type="number" min="1" name="price" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label room-form-label">Số Lượng</label>
                                <input type="number" min="1" name="quantity" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label room-form-label">Người Lớn</label>
                                <input type="number" min="1" name="adult" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label room-form-label">Trẻ Em</label>
                                <input type="number" min="1" name="children" class="form-control shadow-none" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label room-form-label">Tính Năng</label>
                                <div class="feature-checkbox-group">
                                    <?php
                                    $res = selectAll('features');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo "
                                                <div class='feature-checkbox'>
                                                    <input type='checkbox' name='features' value='$opt[id]' class='form-check-input shadow-none'>
                                                    <span>$opt[name]</span>
                                                </div>
                                            ";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label room-form-label">Tiện Nghi</label>
                                <div class="facility-checkbox-group">
                                    <?php
                                    $res = selectAll('facilities');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo "
                                                <div class='facility-checkbox'>
                                                    <input type='checkbox' name='facilities' value='$opt[id]' class='form-check-input shadow-none'>
                                                    <span>$opt[name]</span>
                                                </div>
                                            ";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label room-form-label">Mô Tả</label>
                                <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>
                            </div>
                            <input type="hidden" name="room_id">
                        </div>
                    </div>
                    <div class="modal-footer room-modal-footer">
                        <button type="reset" class="room-btn-cancel" data-bs-dismiss="modal">HỦY</button>
                        <button type="submit" class="room-btn-submit">ĐỒNG Ý</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!--management room images modal -->
    <div class="modal fade" id="room-images" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title room-modal-title">Quản lý ảnh: <span id="room-name"></span></h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="image-alert"></div>
                    <div class="border-bottom border-3 pb-3 mb-3">
                        <form id="add_image_form">
                            <div class="mb-3">
                                <label class="form-label room-form-label">Thêm ảnh</label>
                                <input type="file" name="image" accept=".jpg, .png, .webp, .jpeg"
                                    class="form-control shadow-none" required>
                            </div>
                            <button type="submit" class="room-btn-submit">THÊM</button>
                            <input type="hidden" name="room_id" id="room-id">
                        </form>
                    </div>
                    <div class="images-grid" id="room-image-data">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require('inc/scripts.php'); ?>
    <script src="scripts/rooms.js"></script>
</body>

</html>