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

        
        $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);

        $query = "INSERT INTO `user_cred` (`name`, `email`, `phonenum`, `password`) VALUES (?,?,?,?)";

        $values = [$data['name'], $data['email'], $data['phonenum'], $enc_pass, ];

        if (insert($query, $values, 'ssss')) {
            echo 1;
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

?>