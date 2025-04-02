<?php

require('../admin/inc/db_config.php');
require('../admin/inc/esentials.php');

// Bật báo cáo lỗi để debug
ini_set('display_errors', 1);
error_reporting(E_ALL);
error_log("Script reset_password.php đang chạy");

if(isset($_POST['reset_pass']))
{
    $frm_data = filteration($_POST);
    $token = $frm_data['token'];
    $email = $frm_data['email'];
    $password = $frm_data['password'];

    // Kiểm tra token và email có hợp lệ không
    $query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire` > NOW() AND `status`=? LIMIT 1", [$email, $token, 1], 'ssi');
    
    if(mysqli_num_rows($query) == 1) {
        // Mã hóa mật khẩu mới
        $enc_pass = password_hash($password, PASSWORD_BCRYPT);
        
        // Cập nhật mật khẩu và xóa token
        $query = update("UPDATE `user_cred` SET `password`=?, `token`=NULL, `t_expire`=NULL WHERE `email`=?", [$enc_pass, $email], 'ss');
        
        if($query) {
            echo 'password_updated';
        } else {
            echo 'update_failed';
        }
    } else {
        echo 'invalid_link';
    }
} else {
    echo 'invalid_request';
}

?> 