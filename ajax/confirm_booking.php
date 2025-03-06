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

             // chạy câu truy vấn để kiểm tra phòng còn trống hay không

            $tb_query = "SELECT COUNT(*) AS `total_bookings` FROM `booking_order` 
                    WHERE booking_status=? AND room_id=?
                    AND check_out>? AND check_in<?";

            $values = ['booked', $_SESSION['room']['id'], $frm_data['check_in'], $frm_data['check_out']];
            $tb_fetch = mysqli_fetch_assoc(select($tb_query, $values, 'siss'));

            $rq_result = select("SELECT `quantity` FROM `rooms` WHERE id=?", [$_SESSION['room']['id']], 'i');
            $rq_fetch = mysqli_fetch_assoc($rq_result);

            if (($rq_fetch['quantity'] - $tb_fetch['total_bookings']) == 0) {
                $status = 'unavailable';
            
                $result = json_encode(['status' => $status]);
                echo $result;
                exit;
            }

        
            $count_days = date_diff($checkin_date, $checkout_date)->days;
            $payment = $_SESSION['room']['price'] * $count_days;
        
            $_SESSION['room']['payment'] = $payment;
            $_SESSION['room']['available'] = true;
        
            $result = json_encode(["status" => 'available', "days" => $count_days, "payment" => $payment]);
            echo $result;
        }
    }
?>