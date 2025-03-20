<!-- Navbar -->
<nav id="nav-bar" class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold h-font" href="index.php">
            <?php echo $settings_r['site_title'] ?>
        </a>
        <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link px-3" href="index.php">
                        <i class="bi bi-house-door me-1"></i>Trang Chủ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="rooms.php">
                        <i class="bi bi-building me-1"></i>Phòng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="facilities.php">
                        <i class="bi bi-stars me-1"></i>Tiện Nghi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="contact.php">
                        <i class="bi bi-telephone me-1"></i>Liên Hệ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="about.php">
                        <i class="bi bi-info-circle me-1"></i>Giới Thiệu
                    </a>
                </li>
            </ul>
            <div class="ms-lg-3 mt-lg-0 mt-3 d-flex">
                <?php
                if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
                    echo <<<data
                        <div class="dropdown">
                            <button class="btn btn-outline-dark rounded-pill d-flex align-items-center shadow-none dropdown-toggle" 
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="images/users/user.svg" class="rounded-circle me-2" style="width: 24px; height: 24px; object-fit: cover;">
                                <span>$_SESSION[uName]</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person-circle me-2"></i>Hồ Sơ</a></li>
                                <li><a class="dropdown-item" href="bookings.php"><i class="bi bi-calendar-check me-2"></i>Đặt Phòng</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Đăng Xuất</a></li>
                            </ul>
                        </div>
                    data;
                } else {
                    echo <<<data
                        <button type="button" class="btn btn-outline-dark rounded-pill me-2 shadow-none" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Đăng Nhập
                        </button>
                        <button type="button" class="btn custom-bg text-white rounded-pill shadow-none" data-bs-toggle="modal" data-bs-target="#registerModal">
                            <i class="bi bi-person-plus me-1"></i>Đăng Ký
                        </button>
                    data;
                }
                ?>
            </div>
        </div>
    </div>
</nav>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden">
            <form id="login-form">
                <div class="modal-header bg-gradient" style="background-color: var(--teal);">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-person-circle fs-4 me-2"></i>Đăng Nhập
                    </h5>
                    <button type="reset" class="btn-close shadow-none bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email / Số điện thoại</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="email_mob" required class="form-control shadow-none"
                                placeholder="Email hoặc số điện thoại">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Mật khẩu</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="pass" required class="form-control shadow-none"
                                placeholder="Mật khẩu của bạn">
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <button type="submit" class="btn custom-bg text-white shadow-none rounded-pill px-4 py-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Đăng Nhập
                        </button>
                        <a href="#" class="text-secondary text-decoration-none">Quên mật khẩu?</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 overflow-hidden">
            <form id="register-form">
                <div class="modal-header bg-gradient" style="background-color: var(--teal);">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-person-plus-fill fs-4 me-2"></i>Đăng Ký Tài Khoản
                    </h5>
                    <button type="reset" class="btn-close shadow-none bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="container-fluid p-0">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Họ Tên</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input name="name" type="text" class="form-control shadow-none"
                                        placeholder="Họ và tên của bạn" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input name="email" type="email" class="form-control shadow-none"
                                        placeholder="Địa chỉ email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Số Điện Thoại</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                    <input name="phonenum" type="number" class="form-control shadow-none"
                                        placeholder="Số điện thoại" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Mật Khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input name="pass" type="password" class="form-control shadow-none"
                                        placeholder="Tạo mật khẩu" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Xác Nhận Mật Khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input name="cpass" type="password" class="form-control shadow-none"
                                        placeholder="Nhập lại mật khẩu" required>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn custom-bg text-white shadow-none rounded-pill px-4 py-2">
                                <i class="bi bi-person-check me-1"></i>Tạo Tài Khoản
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>