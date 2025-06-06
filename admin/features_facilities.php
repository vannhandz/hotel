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
    <title>Admin Panel - Features & Facilities</title>
    <?php require('inc/link.php'); ?>
    <link rel="stylesheet" href="css/features_facilities.css">
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="features-page-title">TÍNH NĂNG & TIỆN NGHI</h3>

                <div class="card features-card mb-4">
                    <div class="card-header">
                        <h5 class="m-0">Tính Năng</h5>
                        <button type="button" class="add-btn" data-bs-toggle="modal"
                            data-bs-target="#feature-s">
                            <i class="bi bi-plus-square"></i> Thêm
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="features-table-wrapper">
                            <table class="features-table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Tên</th>
                                        <th scope="col">Hoạt Động</th>
                                    </tr>
                                </thead>
                                <tbody id="features-data">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>



                <div class="card features-card mb-4">
                    <div class="card-header">
                        <h5 class="m-0">Tiện Nghi</h5>
                        <button type="button" class="add-btn" data-bs-toggle="modal"
                            data-bs-target="#facility-s">
                            <i class="bi bi-plus-square"></i> Thêm
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="features-table-wrapper">
                            <table class="features-table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Icon</th>
                                        <th scope="col">Tên</th>
                                        <th scope="col">Mô Tả</th>
                                        <th scope="col">Hoạt Động</th>
                                    </tr>
                                </thead>
                                <tbody id="facilities-data">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>


     <!--feature modal -->
    <div class="modal fade" id="feature-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="feature_s_form" class="feature-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title feature-modal-title">Thêm Tính Năng</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label feature-form-label">Tên</label>
                            <input type="text" name="feature_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="feature-btn-cancel" data-bs-dismiss="modal">HỦY</button>
                        <button type="submit" class="feature-btn-submit">ĐỒNG Ý</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

     <!--facility modal -->
     <div class="modal fade" id="facility-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="facility_s_form" class="feature-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title feature-modal-title">Thêm Tiên Nghi</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label feature-form-label">Tên</label>
                            <input type="text" name="facility_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label feature-form-label">Icon</label>
                            <input type="file" name="facility_icon" accept=".svg" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label feature-form-label">Mô Tả</label>
                            <textarea name="facility_desc" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="feature-btn-cancel" data-bs-dismiss="modal">HỦY</button>
                        <button type="submit" class="feature-btn-submit">ĐỒNG Ý</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>
    <script src="scripts/features_facilities.js"></script>                    
   
</body>

</html>