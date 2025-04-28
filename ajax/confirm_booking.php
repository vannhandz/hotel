<?php

    require('../admin/inc/db_config.php');
    require('../admin/inc/esentials.php');

    if (isset($_POST['check_availability'])) {
        $frm_data = filteration($_POST);
        $status = "";
        $result = "";
    
      // kiểm tra xác nhận vào và ra    
    
        $today_date = new DateTime(date("Y-m-d"));
        $checkin_date = new DateTime($frm_data['check_in']);
        $checkout_date = new DateTime($frm_data['check_out']);


        if ($checkin_date == $checkout_date) {
            $status = 'check_in_out_equal';
            $result = json_encode(["status" => $status]);
        } else if ($checkout_date < $checkin_date) {
            $status = 'check_out_earlier';
            $result = json_encode(["status" => $status]);
        } else if ($checkin_date < $today_date) {
            $status = 'check_in_earlier';
            $result = json_encode(["status" => $status]);
        }
        
       // kiểm tra tình trạng phòng trống nếu trạng thái trống, ngược lại trả về lỗi


        if ($status != '') {
            echo $result;
        } else {
            session_start();

            // Bước 1: Kiểm tra xem người dùng đã có đơn đặt phòng nào trong khoảng thời gian này chưa
            $user_booking_query = "SELECT COUNT(*) AS `user_booking_count` FROM `booking_order` 
                    WHERE user_id=? AND room_id=?
                    AND booking_status IN ('booked', 'pending')
                    AND check_out>? AND check_in<?";

            $user_values = [$_SESSION['uId'], $_SESSION['room']['id'], $frm_data['check_in'], $frm_data['check_out']];
            $user_booking = mysqli_fetch_assoc(select($user_booking_query, $user_values, 'iiss'));

            // Nếu người dùng đã có đơn đặt phòng cho khoảng thời gian này, trả về lỗi
            if ($user_booking['user_booking_count'] > 0) {
                $status = 'already_booked';
                $result = json_encode(['status' => $status]);
                echo $result;
                exit;
            }

            // // Bước 2: Kiểm tra số lượng phòng còn trống với tất cả đơn đặt
            // $tb_query = "SELECT COUNT(*) AS `total_bookings` FROM `booking_order` 
            //         WHERE booking_status IN ('booked', 'pending') AND room_id=?
            //         AND check_out>? AND check_in<?";

            // $values = [$_SESSION['room']['id'], $frm_data['check_in'], $frm_data['check_out']];
            // $tb_fetch = mysqli_fetch_assoc(select($tb_query, $values, 'iss'));

            // $rq_result = select("SELECT `quantity` FROM `rooms` WHERE id=?", [$_SESSION['room']['id']], 'i');
            // $rq_fetch = mysqli_fetch_assoc($rq_result);

            // if (($rq_fetch['quantity'] - $tb_fetch['total_bookings']) == 0) {
            //     $status = 'unavailable';
            //     $result = json_encode(['status' => $status]);
            //     echo $result;
            //     exit;
            // }

        
            $count_days = date_diff($checkin_date, $checkout_date)->days;
            $payment = $_SESSION['room']['price'] * $count_days;
        
            $_SESSION['room']['payment'] = $payment;
            $_SESSION['room']['available'] = true;
        
            $result = json_encode(["status" => 'available', "days" => $count_days, "payment" => $payment]);
            echo $result;
        }
    }
?>