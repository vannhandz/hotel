<?php
require('inc/esentials.php');
adminLogin();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Setting</title>
    <?php require('inc/link.php'); ?>
    <link rel="stylesheet" href="css/setting.css">
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="setting-page-title">CÀI ĐẶT</h3>
                
                <!-- Setting -->
                <div class="card setting-card">
                    <div class="card-header">
                        <h5>Cài Đặt Chung</h5>
                        <button type="button" class="edit-btn" data-bs-toggle="modal" data-bs-target="#general-s">
                            <i class="bi bi-pencil-square"></i> Sửa
                        </button>
                    </div>
                    <div class="card-body">
                        <h6 class="setting-subtitle">Tên Website</h6>
                        <p class="setting-text" id="site_title"></p>
                        <h6 class="setting-subtitle">Giới Thiệu</h6>
                        <p class="setting-text" id="site_about"></p>
                    </div>
                </div>

                <!-- general setting modal -->
                <div class="modal fade" id="general-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="general_s_form" class="setting-modal">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title setting-modal-title">Cài Đặt Chung</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label setting-form-label">Tên Website</label>
                                        <input type="text" name="site_title" id="site_title_inp"
                                            class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label setting-form-label">Giới Thiệu</label>
                                        <textarea name="site_about" id="site_about_inp" class="form-control"
                                            rows="6" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"
                                        onclick="site_title.value = general_data.site_title,site_about.value=general_data.site_about"
                                        class="setting-btn-cancel" data-bs-dismiss="modal">HỦY</button>
                                    <button type="submit" class="setting-btn-submit">ĐỒNG Ý</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- shutdown section -->
                <div class="card setting-card">
                    <div class="card-header">
                        <h5>Vô hiệu hóa trang web</h5>
                        <div class="form-check form-switch">
                            <form>
                                <input onchange="upd_shutdown(this.value)" class="form-check-input" type="checkbox"
                                    id="shutdown-toggle">
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="setting-text">
                         Hệ thống đặt phòng đang tạm ngưng, khách hàng không thể đặt phòng khách sạn.
                        </p>
                    </div>
                </div>

                <!-- contact setting -->
                <div class="card setting-card">
                    <div class="card-header">
                        <h5>Cài Đặt Liên Hệ</h5>
                        <button type="button" class="edit-btn" data-bs-toggle="modal" data-bs-target="#contacts-s">
                            <i class="bi bi-pencil-square"></i> Sửa
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="setting-contact-grid">
                            <div>
                                <div class="setting-contact-item">
                                    <h6 class="setting-subtitle">Địa Chỉ</h6>
                                    <p class="setting-text" id="address"></p>
                                </div>
                                <div class="setting-contact-item">
                                    <h6 class="setting-subtitle">Bản Đồ Google</h6>
                                    <p class="setting-text" id="gmap"></p>
                                </div>
                                <div class="setting-contact-item">
                                    <h6 class="setting-subtitle">Số Điện Thoại</h6>
                                    <div class="social-link">
                                        <i class="bi bi-telephone-fill"></i>
                                        <span id="pn1"></span>
                                    </div>
                                    <div class="social-link">
                                        <i class="bi bi-telephone-fill"></i>
                                        <span id="pn2"></span>
                                    </div>
                                </div>
                                <div class="setting-contact-item">
                                    <h6 class="setting-subtitle">Email</h6>
                                    <p class="setting-text" id="email"></p>
                                </div>
                            </div>
                            <div>
                                <div class="setting-contact-item">
                                    <h6 class="setting-subtitle">Mạng Xã Hội</h6>
                                    <div class="social-link">
                                        <i class="bi bi-facebook"></i>
                                        <span id="fb"></span>
                                    </div>
                                    <div class="social-link">
                                        <i class="bi bi-instagram"></i>
                                        <span id="insta"></span>
                                    </div>
                                    <div class="social-link">
                                        <i class="bi bi-twitter"></i>
                                        <span id="tw"></span>
                                    </div>
                                </div>
                                <div class="setting-contact-item">
                                    <h6 class="setting-subtitle">iFrame</h6>
                                    <iframe id="iframe" class="contact-iframe" loading="lazy"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- contact detail modal -->
                <div class="modal fade" id="contacts-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form id="contacts_s_form" class="setting-modal">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title setting-modal-title">Cài Đặt Liên Hệ</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="container-fluid p-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label setting-form-label">Địa Chỉ</label>
                                                    <input type="text" name="address" id="address_inp"
                                                        class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label setting-form-label">Bản Đồ Google</label>
                                                    <input type="text" name="gmap" id="gmap_inp"
                                                        class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label setting-form-label">Số Điện Thoại</label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i
                                                                class="bi bi-telephone-fill"></i></span>
                                                        <input type="text" name="pn1" id="pn1_inp"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i
                                                                class="bi bi-telephone-fill"></i></span>
                                                        <input type="text" name="pn2" id="pn2_inp"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label setting-form-label">Email</label>
                                                    <input type="text" name="email" id="email_inp"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label setting-form-label">Mạng Xã Hội</label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="bi bi-facebook"></i></span>
                                                        <input type="text" name="fb" id="fb_inp"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="bi bi-instagram"></i></span>
                                                        <input type="text" name="insta" id="insta_inp"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="bi bi-twitter"></i></span>
                                                        <input type="text" name="tw" id="tw_inp"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label setting-form-label">iFrame Src</label>
                                                    <input type="text" name="iframe" id="iframe_inp"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"
                                        onclick="contacts_inp(contacts_data)"
                                        class="setting-btn-cancel" data-bs-dismiss="modal">HỦY</button>
                                    <button type="submit" class="setting-btn-submit">ĐỒNG Ý</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Management Team -->
                <div class="card setting-card">
                    <div class="card-header">
                        <h5>Đội Ngũ Quản Lý</h5>
                        <button type="button" class="edit-btn" data-bs-toggle="modal" data-bs-target="#team-s">
                            <i class="bi bi-plus-square"></i> Thêm
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="team-grid" id="team-data">
                        </div>
                    </div>
                </div>

                <!-- management team modal -->
                <div class="modal fade" id="team-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="team_s_form" class="setting-modal">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title setting-modal-title">Thêm Người</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label setting-form-label">Tên</label>
                                        <input type="text" name="member_name" id="member_name_inp"
                                            class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label setting-form-label">Hình Ảnh</label>
                                        <input type="file" name="member_picture" id="member_picture_inp"
                                            accept=".jpg, .png, .wedp, .jpeg" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="member_name_inp.value='',member_picture_inp.value=''"
                                        class="setting-btn-cancel" data-bs-dismiss="modal">HỦY</button>
                                    <button type="submit" class="setting-btn-submit">ĐỒNG Ý</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>
    <script src="scripts/settings.js"></script>
   
</body>

</html>