<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Đặt múi giờ cho Việt Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Sử dụng đường dẫn tuyệt đối
define('ROOT_PATH', dirname(__FILE__));
require(ROOT_PATH . '/admin/inc/db_config.php');
require(ROOT_PATH . '/admin/inc/esentials.php');

// Debug thông tin


$token = $_GET['token'] ?? '';
$email = $_GET['email'] ?? '';



// Định nghĩa hàm show_error ở đây để sử dụng trong cả layout
function show_error($title, $message) {
    ?>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="text-center p-4 rounded shadow bg-white">
                    <i class="bi bi-exclamation-circle text-danger" style="font-size: 4rem"></i>
                    <h2 class="mt-3 mb-3"><?php echo $title; ?></h2>
                    <p class="text-muted mb-4"><?php echo $message; ?></p>
                    <div class="d-grid gap-2">
                        <a href="forgot_password.php" class="btn btn-primary">Yêu cầu link mới</a>
                        <a href="index.php" class="btn btn-outline-secondary">Về trang chủ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

// Content sẽ được hiển thị trong trang
$page_content = '';
ob_start();

// Kiểm tra token và email
if(empty($token) || empty($email)) {
    show_error("Link không hợp lệ!", "Vui lòng kiểm tra lại link trong email của bạn.");
    $page_content = ob_get_clean();
} else {
    // Kiểm tra email và lấy thông tin token
    $query = select("SELECT * FROM `user_cred` WHERE `email`=? LIMIT 1", [$email], 's');
    if(mysqli_num_rows($query) == 0) {
        show_error("Email không tồn tại!", "Email này không tồn tại trong hệ thống.");
        $page_content = ob_get_clean();
    } else {
        $user_data = mysqli_fetch_assoc($query);

        // Kiểm tra token có hợp lệ không
        if($token !== $user_data['token']) {
            show_error("Link không hợp lệ!", "Token không đúng. Vui lòng yêu cầu link mới.");
            $page_content = ob_get_clean();
        }
        // Kiểm tra token có hết hạn không
        else {
            $query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire` > NOW() LIMIT 1", [$email, $token], 'ss');
            if(mysqli_num_rows($query) == 0) {
                show_error("Link đã hết hạn!", "Link khôi phục mật khẩu đã hết hạn vào lúc " . $user_data['t_expire']);
                $page_content = ob_get_clean();
            }
            // Kiểm tra trạng thái tài khoản
            else if($user_data['status'] != 1) {
                show_error("Tài khoản không hoạt động!", "Tài khoản của bạn đã bị khóa hoặc chưa được kích hoạt.");
                $page_content = ob_get_clean();
            }
            // Hiển thị form đổi mật khẩu
            else {
                ?>
                <div class="container my-5">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="bg-white p-4 rounded shadow">
                                <h2 class="text-center mb-4">Đặt lại mật khẩu</h2>
                                <div id="alert"></div>
                                <form id="reset-form">
                                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                                    
                                    <div class="mb-3">
                                        <label for="password" class="form-label fw-bold">Mật khẩu mới</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password" required minlength="8">
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                <i class="bi bi-eye-slash"></i>
                                            </button>
                                        </div>
                                        <div class="form-text">Mật khẩu phải có ít nhất 8 ký tự</div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="confirm_password" class="form-label fw-bold">Xác nhận mật khẩu</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="8">
                                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                                <i class="bi bi-eye-slash"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary py-2">Đặt lại mật khẩu</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $page_content = ob_get_clean();
            }
        }
    }
}

$page_title = "Đặt lại mật khẩu";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - HOTEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <?php require('inc/link.php'); ?>
    <style>
        .custom-bg {
            background-color: #2ec1ac;
        }
        .custom-bg:hover {
            background-color: #279e8c;
        }
        .availability-form {
            margin-top: -50px;
            z-index: 2;
            position: relative;
        }
        @media screen and (max-width: 575px) {
            .availability-form {
                margin-top: 25px;
                padding: 0 35px;
            }
        }
    </style>
</head>
<body class="bg-light">
    <!-- Header -->
    <?php require('inc/header.php'); ?>

    <!-- Main Content -->
    <?php echo $page_content; ?>

    <!-- Footer -->
    <?php require('inc/footer.php'); ?>

    <script>
        function showAlert(type, msg) {
            let alertBox = document.getElementById('alert');
            if (alertBox) {
                alertBox.innerHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${msg}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
            }
        }

        // Toggle password visibility functions
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            
            if (togglePassword) {
                togglePassword.addEventListener('click', function() {
                    const password = document.getElementById('password');
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('bi-eye');
                    this.querySelector('i').classList.toggle('bi-eye-slash');
                });
            }
            
            if (toggleConfirmPassword) {
                toggleConfirmPassword.addEventListener('click', function() {
                    const confirmPassword = document.getElementById('confirm_password');
                    const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirmPassword.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('bi-eye');
                    this.querySelector('i').classList.toggle('bi-eye-slash');
                });
            }
            
            // Form submission
            const resetForm = document.getElementById('reset-form');
            if (resetForm) {
                resetForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    let password = this.elements['password'].value;
                    let confirm_password = this.elements['confirm_password'].value;
                    
                    if(password.length < 8) {
                        showAlert('danger', 'Mật khẩu phải có ít nhất 8 ký tự!');
                        return;
                    }
                    
                    if(password !== confirm_password) {
                        showAlert('danger', 'Mật khẩu xác nhận không khớp!');
                        return;
                    }
                    
                    let data = new FormData();
                    data.append('password', password);
                    data.append('token', this.elements['token'].value);
                    data.append('email', this.elements['email'].value);
                    data.append('reset_pass', '');

                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "ajax/reset_password.php", true);

                    xhr.onload = function() {
                        console.log("Server response:", this.responseText);
                        let response = this.responseText.trim();
                        
                        if(response === 'password_updated') {
                            showAlert('success', 'Mật khẩu đã được cập nhật thành công!');
                            resetForm.reset();
                            setTimeout(() => {
                                window.location.href = 'http://localhost/doan';
                            }, 2000);
                        } else if(response === 'invalid_link') {
                            showAlert('danger', 'Link không hợp lệ hoặc đã hết hạn!');
                        } else {
                            showAlert('danger', 'Lỗi không xác định! Vui lòng thử lại sau.');
                        }
                    }

                    xhr.send(data);
                });
            }
        });
    </script>
</body>
</html> 