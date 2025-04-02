<?php

require('../admin/inc/db_config.php');
require('../admin/inc/esentials.php');

// Bật báo cáo lỗi để debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Đặt múi giờ cho Việt Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');

error_log("Script forgot_password.php đang chạy");

if(isset($_POST['forgot_pass']))
{
    $frm_data = filteration($_POST);
    $email = $frm_data['email'];
    error_log("Email nhận được: $email");
    
    try {
        // Kiểm tra email có tồn tại không
        $query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `status`=? LIMIT 1", [$email, 1], 'si');
        
        if(mysqli_num_rows($query) == 1) {
            error_log("Email $email tồn tại, tiếp tục xử lý");

            // Tạo token ngẫu nhiên
            $token = bin2hex(random_bytes(32));
            
            // Thiết lập thời gian hết hạn là 30 phút từ thời điểm hiện tại
            $current_time = date('Y-m-d H:i:s');
            $expire = date('Y-m-d H:i:s', strtotime('+30 minutes'));
            
            error_log("Tạo token: $token");
            error_log("Thời gian hiện tại: $current_time");
            error_log("Thời gian hết hạn: $expire");
            
            // Cập nhật token trong database sử dụng thời gian của MySQL
            $update_result = update("UPDATE `user_cred` SET `token`=?, `t_expire`=DATE_ADD(NOW(), INTERVAL 30 MINUTE) WHERE `email`=?", 
                [$token, $email], 'ss');
            
            if($update_result !== false) {
                error_log("Kết quả cập nhật token: thành công");

                // Tạo link reset password
                $reset_link = rtrim(SITE_URL, '/') . '/reset_password.php?token=' . $token . '&email=' . urlencode($email);
                error_log("Link reset: $reset_link");

                // Nội dung email
                $to = $email;
                $subject = "Khôi phục mật khẩu - Khách sạn";
                $message = "
                    <html>
                    <head>
                        <title>Khôi phục mật khẩu</title>
                    </head>
                    <body>
                        <h2>Khôi phục mật khẩu</h2>
                        <p>Bạn đã yêu cầu khôi phục mật khẩu cho tài khoản của mình.</p>
                        <p>Vui lòng click vào link bên dưới để đặt lại mật khẩu:</p>
                        <p><a href='{$reset_link}'>{$reset_link}</a></p>
                        <p>Link này sẽ hết hạn sau 30 phút.</p>
                        <p>Thời gian tạo: {$current_time}</p>
                        <p>Thời gian hết hạn: {$expire}</p>
                        <p>Nếu bạn không yêu cầu khôi phục mật khẩu, vui lòng bỏ qua email này.</p>
                        <br>
                        <p>Trân trọng,</p>
                        <p>Đội ngũ Khách sạn</p>
                    </body>
                    </html>
                ";
                
                // Headers cho email HTML
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                $headers .= "From: your-email@gmail.com\r\n";
                
                // Gửi email
                if(mail($to, $subject, $message, $headers)){
                    echo 'email_sent';
                } else {
                    error_log("Lỗi gửi email: " . error_get_last()['message']);
                    echo 'email_failed';
                }
            } else {
                error_log("Lỗi cập nhật token trong database");
                echo 'update_failed';
            }
        } else {
            error_log("Email $email không tồn tại trong hệ thống");
            echo 'invalid_email';
        }
    } catch (Exception $e) {
        error_log("Lỗi: " . $e->getMessage());
        echo 'error';
    }
} else {
    error_log("Không nhận được tham số forgot_pass");
    echo 'invalid_request';
}

?> 