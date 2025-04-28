<?php
require('../admin/inc/db_config.php');
require('../admin/inc/esentials.php');

    if (isset($_POST['info_form'])) {
        $frm_data = filteration($_POST);
        session_start();

        // Kiểm tra mật khẩu trước
        if (isset($frm_data['new_pass']) && $frm_data['new_pass'] != '') {
            if ($frm_data['new_pass'] != $frm_data['confirm_pass']) {
                echo 'mismatch';
                exit;
            }

            $enc_pass = password_hash($frm_data['new_pass'], PASSWORD_BCRYPT);
            $query_pass = "UPDATE `user_cred` SET `password`=? WHERE `id`=? LIMIT 1";
            $values_pass = [$enc_pass, $_SESSION['uId']];
            $update_pass_result = update($query_pass, $values_pass, 'ss');
            //sql prepared statement câu lệnh có ?
            if (!$update_pass_result) {
                echo 0; // Cập nhật mật khẩu thất bại
                exit;
            }
        }

        // Kiểm tra số điện thoại đã tồn tại
        $u_exist = select(
            "SELECT * FROM `user_cred` WHERE `phonenum` = ? AND `id` != ? LIMIT 1",
            [$frm_data['phonenum'], $_SESSION['uId']],
            "ss"
        );

        if (mysqli_num_rows($u_exist) != 0) {
            echo 'phone_already';
            exit;
        }

        // Cập nhật thông tin cá nhân
        $query = "UPDATE `user_cred` SET `name` = ?, `phonenum` = ?, `dob` = ? WHERE `id` = ? LIMIT 1";
        $values = [$frm_data['name'], $frm_data['phonenum'], $frm_data['dob'], $_SESSION['uId']];
        $update_result = update($query, $values, 'ssss');

        if ($update_result) {
            $_SESSION['uName'] = $frm_data['name'];
            echo 1; // Cập nhật thành công
        } else {
            echo 0; // Cập nhật thất bại
        }
    }
?>