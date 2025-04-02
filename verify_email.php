<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Ho_Chi_Minh');

require('admin/inc/db_config.php');
require('admin/inc/esentials.php');

$page_title = "Xác thực tài khoản";
$page_content = '';
ob_start();

$token = $_GET['token'] ?? '';
$email = $_GET['email'] ?? '';

// Kiểm tra token và email
if(empty($token) || empty($email)) {
    show_message("Link không hợp lệ!", 
                "Vui lòng kiểm tra lại link trong email của bạn.", 
                "danger", 
                "index.php");
}
else {
    // Kiểm tra email và token có tồn tại không
    $query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? LIMIT 1", 
                  [$email, $token], 'ss');
    
    if(mysqli_num_rows($query) == 0) {
        show_message("Thông tin không hợp lệ!", 
                    "Token hoặc email không chính xác.", 
                    "danger", 
                    "index.php");
    }
    else {
        $user_data = mysqli_fetch_assoc($query);
        
        // Kiểm tra tài khoản đã được xác thực chưa
        if($user_data['is_verified'] == 1) {
            show_message("Tài khoản đã xác thực!", 
                        "Tài khoản này đã được xác thực trước đó. Bạn có thể đăng nhập ngay bây giờ.", 
                        "success", 
                        "login.php", 
                        "Đăng nhập ngay");
        }
        // Kiểm tra token có hết hạn không
        else if($user_data['t_expire'] < date('Y-m-d H:i:s')) {
            show_message("Link đã hết hạn!", 
                        "Link xác thực tài khoản đã hết hạn.", 
                        "warning", 
                        "resend_verification.php?email=".urlencode($email), 
                        "Gửi lại email xác thực");
        }
        else {
            // Cập nhật trạng thái xác thực
            $update = update("UPDATE `user_cred` SET `is_verified`=?, `token`=?, `t_expire`=? WHERE `email`=?", 
                           [1, '', NULL, $email], 
                           'isss');
            
            if($update) {
                show_message("Xác thực thành công!", 
                           "Tài khoản của bạn đã được xác thực thành công. Bạn có thể đăng nhập ngay bây giờ.", 
                           "success", 
                           "login.php", 
                           "Đăng nhập ngay");
            }
            else {
                show_message("Lỗi xác thực!", 
                           "Đã xảy ra lỗi trong quá trình xác thực tài khoản. Vui lòng thử lại sau.", 
                           "danger", 
                           "index.php");
            }
        }
    }
}

$page_content = ob_get_clean();

// Hiển thị thông báo dưới dạng card
function show_message($title, $message, $type, $redirect_url, $button_text = "Về trang chủ") {
    ?>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow border-0">
                    <div class="card-body text-center p-5">
                        <?php if($type == 'success'): ?>
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem"></i>
                        <?php elseif($type == 'warning'): ?>
                            <i class="bi bi-exclamation-circle-fill text-warning" style="font-size: 4rem"></i>
                        <?php else: ?>
                            <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem"></i>
                        <?php endif; ?>
                        
                        <h2 class="mt-4 mb-3"><?php echo $title; ?></h2>
                        <p class="text-muted mb-4"><?php echo $message; ?></p>
                        
                        <div class="d-grid gap-2">
                            <a href="<?php echo $redirect_url; ?>" class="btn btn-primary py-2"><?php echo $button_text; ?></a>
                            <?php if($redirect_url != 'index.php'): ?>
                                <a href="http://localhost/doan" class="btn btn-outline-secondary">Về trang chủ</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
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
    <?php echo $page_content; ?>

    <!-- Footer -->
    <?php require('inc/footer.php'); ?>
</body>
</html> 