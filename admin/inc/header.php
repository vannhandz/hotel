<link rel="stylesheet" href="css/header.css">

<div class="container-fluid admin-header d-flex align-items-center justify-content-between sticky-top">
    <h3 class="admin-logo">HOTEL ADMIN</h3>
    <a href="logout.php" class="logout-btn">ĐĂNG XUẤT <i class="bi bi-box-arrow-right"></i></a>
</div>

<div class="col-lg-2 admin-sidebar" id="dashboard-menu">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid flex-lg-column align-items-stretch">
            <h4 class="admin-title">QUẢN TRỊ HỆ THỐNG</h4>
            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#adminDropdown" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse flex-lg-column align-items-stretch mt-2 admin-menu" id="adminDropdown">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="admin-nav-link" href="dashboard.php">
                            <i class="bi bi-speedometer2"></i> Bảng Điều Khiển
                        </a>
                    </li>
                    <li class="nav-item">
                        <button
                            class="admin-dropdown-btn"
                            type="button" data-bs-toggle="collapse" data-bs-target="#bookingLinks">
                            <span><i class="bi bi-calendar-check"></i> Đặt Phòng</span>
                            <span><i class="bi bi-caret-down-fill"></i></span>
                        </button>
                        <div class="collapse show admin-dropdown-menu" id="bookingLinks">
                            <a class="admin-dropdown-item" href="new_bookings.php">Đặt Phòng Mới</a>
                            <a class="admin-dropdown-item" href="refund_bookings.php">Hoàn Tiền Đặt Phòng</a>
                            <a class="admin-dropdown-item" href="booking_records.php">Danh Sách Đặt Phòng</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="admin-nav-link" href="users.php">
                            <i class="bi bi-people"></i> Người Dùng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="admin-nav-link" href="user_queries.php">
                            <i class="bi bi-question-circle"></i> Yêu Cầu Người Dùng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="admin-nav-link" href="rate_review.php">
                            <i class="bi bi-star"></i> Xếp Hạng & Đánh Giá
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="admin-nav-link" href="rooms.php">
                            <i class="bi bi-house-door"></i> Phòng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="admin-nav-link" href="features_facilities.php">
                            <i class="bi bi-gear"></i> Tính Năng & Tiện Nghi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="admin-nav-link" href="carousel.php">
                            <i class="bi bi-images"></i> Trình Chiếu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="admin-nav-link" href="setting.php">
                            <i class="bi bi-sliders"></i> Cài Đặt
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>

<script>
    // Lấy đường dẫn trang hiện tại
    let currentPath = window.location.pathname;
    let currentFile = currentPath.substring(currentPath.lastIndexOf('/') + 1);
    
    // Đánh dấu mục được chọn
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý các liên kết trong menu
        document.querySelectorAll('.admin-nav-link').forEach(link => {
            if (link.getAttribute('href') === currentFile) {
                link.classList.add('active');
            }
        });
        
        // Xử lý các liên kết trong dropdown
        document.querySelectorAll('.admin-dropdown-item').forEach(link => {
            if (link.getAttribute('href') === currentFile) {
                link.classList.add('active');
                
                // Mở rộng phần dropdown nếu mục con được chọn
                let dropdown = link.closest('.admin-dropdown-menu');
                if (dropdown) {
                    dropdown.classList.add('show');
                }
            }
        });
    });
</script>