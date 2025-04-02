<?php

    require('../admin/inc/db_config.php');
    require('../admin/inc/esentials.php');
   
    if (isset($_POST['register'])) {

        $data = filteration($_POST);

        // khớp mật khẩu và xác nhận trường mật khẩu

        if ($data['pass'] != $data['cpass']) {
            echo 'pass_mismatch';
            exit;
        }

         // Kiểm tra email đã tồn tại hay chưa
         $email_exist = select("SELECT * FROM `user_cred` WHERE `email` = ? LIMIT 1",
         [$data['email']], "s");

        if (mysqli_num_rows($email_exist) != 0) {
            echo 'email_already';
            exit;
        }

        // Kiểm tra số điện thoại đã tồn tại hay chưa
        $phone_exist = select("SELECT * FROM `user_cred` WHERE `phonenum` = ? LIMIT 1",
            [$data['phonenum']], "s");

        if (mysqli_num_rows($phone_exist) != 0) {
            echo 'phone_already';
            exit;
        }

        // Tạo mã xác thực và thời gian hết hạn (24 giờ)
        $token = bin2hex(random_bytes(16));
        $expire = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);

        $query = "INSERT INTO `user_cred` (`name`, `email`, `phonenum`, `password`, `token`, `t_expire`, `is_verified`) VALUES (?,?,?,?,?,?,?)";

        $values = [$data['name'], $data['email'], $data['phonenum'], $enc_pass, $token, $expire, 0];

        if (insert($query, $values, 'ssssssi')) {
            // Gửi email xác thực
            $mail_status = sendVerificationEmail($data['email'], $token, $data['name']);
            
            if($mail_status) {
                echo 'email_sent';
            } else {
                echo 'mail_failed';
            }
        } else {
            echo 'ins_failed';
        }

    }

    if(isset($_POST['login']))
    {
        $data = filteration($_POST);

        $u_exist = select("SELECT * FROM `user_cred` WHERE `email`=? OR `phonenum`=? LIMIT 1",
            [$data['email_mob'], $data['email_mob']], "ss");

        if(mysqli_num_rows($u_exist)==0) {
            echo 'inv_email_mob';
        }
        else{
            $u_fetch = mysqli_fetch_assoc($u_exist);
            if($u_fetch['is_verified']==0){
                echo 'not_verified';
            }
            else if($u_fetch['status']==0){
                echo 'inactive';
            }
            else{
                if(!password_verify($data['pass'], $u_fetch['password'])) {
                    echo 'invalid_pass';
                }
                else {
                    session_start();
                    $_SESSION['login'] = true;
                    $_SESSION['uId'] = $u_fetch['id'];
                    $_SESSION['uName'] = $u_fetch['name'];
                    $_SESSION['uPhone'] = $u_fetch['phonenum'];
                    echo 1;
                }
            }
        }
    }

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
            <p>Cảm ơn bạn đã đăng ký tài khoản tại HOTEL. Để kích hoạt tài khoản, vui lòng nhấn vào liên kết bên dưới:</p>
            <p style='text-align: center;'>
                <a href='$url' style='display: inline-block; background-color: #2ec1ac; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>XÁC THỰC NGAY</a>
            </p>
            <p>Liên kết xác thực sẽ hết hạn sau 24 giờ. Nếu bạn không thực hiện đăng ký tài khoản, vui lòng bỏ qua email này.</p>
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