<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Ho_Chi_Minh');

require('admin/inc/db_config.php');
require('admin/inc/esentials.php');

$page_title = "Gửi lại email xác thực";
$success_msg = $error_msg = '';

// Kiểm tra nếu email được gửi từ form
if(isset($_POST['resend_email'])) {
    $email = $_POST['email'];
    
    // Kiểm tra email có tồn tại không
    $query = select("SELECT * FROM `user_cred` WHERE `email`=? LIMIT 1", [$email], 's');
    
    if(mysqli_num_rows($query) == 0) {
        $error_msg = "Email không tồn tại trong hệ thống!";
    } else {
        $user_data = mysqli_fetch_assoc($query);
        
        // Kiểm tra tài khoản đã được xác thực chưa
        if($user_data['is_verified'] == 1) {
            $error_msg = "Tài khoản đã được xác thực trước đó. Bạn có thể đăng nhập ngay.";
        } else {
            // Tạo token mới và thời gian hết hạn
            $token = bin2hex(random_bytes(16));
            $expire = date('Y-m-d H:i:s', strtotime('+24 hours'));
            
            // Cập nhật token mới
            $update = update("UPDATE `user_cred` SET `token`=?, `t_expire`=? WHERE `email`=?", 
                          [$token, $expire, $email], 
                          'sss');
            
            if($update) {
                // Gửi email xác thực
                $mail_status = sendVerificationEmail($email, $token, $user_data['name']);
                
                if($mail_status) {
                    $success_msg = "Email xác thực đã được gửi lại. Vui lòng kiểm tra hộp thư của bạn.";
                } else {
                    $error_msg = "Không thể gửi email xác thực. Vui lòng thử lại sau.";
                }
            } else {
                $error_msg = "Đã xảy ra lỗi khi cập nhật thông tin. Vui lòng thử lại sau.";
            }
        }
    }
}

// Lấy email từ query string (nếu có)
$email_value = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';

// Hàm gửi email xác thực
function sendVerificationEmail($email, $token, $name) {
    // Lấy URL từ cấu hình
    $url = SITE_URL . '/verify_email.php?token=' . $token . '&email=' . urlencode($email);

    // Thiết lập nội dung email
    $subject = "Xác thực tài khoản - HOTEL";
    
    $message = "
    <html>
    <head>
        <title>Xác thực tài khoản</title>
    </head>
    <body style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>
        <div style='text-align: center; margin-bottom: 20px;'>
            <h2 style='color: #2ec1ac;'>XÁC THỰC TÀI KHOẢN</h2>
        </div>
        <p>Xin chào <b>$name</b>,</p>
        <p>Bạn đã yêu cầu gửi lại email xác thực tài khoản tại HOTEL. Để kích hoạt tài khoản, vui lòng nhấn vào liên kết bên dưới:</p>
        <p style='text-align: center;'>
            <a href='$url' style='display: inline-block; background-color: #2ec1ac; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>XÁC THỰC NGAY</a>
        </p>
        <p>Liên kết xác thực sẽ hết hạn sau 24 giờ. Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.</p>
        <p>Trân trọng,<br>Đội ngũ HOTEL</p>
    </body>
    </html>
    ";
    
    // Headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: HOTEL <noreply@'.SITE_URL.'>' . "\r\n";
    
    // Gửi email
    return mail($email, $subject, $message, $headers);
}
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
    </style>
</head>
<body class="bg-light">
    <!-- Header -->
    <?php require('inc/header.php'); ?>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-center mb-4">Gửi lại email xác thực</h2>
                    
                    <?php if(!empty($success_msg)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $success_msg; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($error_msg)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error_msg; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email đăng ký</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email_value; ?>" required>
                            <div class="form-text">Nhập email bạn đã sử dụng để đăng ký tài khoản</div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" name="resend_email" class="btn btn-primary py-2">Gửi lại email xác thực</button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="login.php" class="text-decoration-none">Quay lại đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php require('inc/footer.php'); ?>
    <?php require('chat.php') ?>
</body>
</html> 