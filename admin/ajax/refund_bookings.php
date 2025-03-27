<?php
    require('../inc/db_config.php');
    require('../inc/esentials.php');
    adminLogin();
    

    if(isset($_POST['get_bookings'])){
        $frm_data = filteration($_POST);
       
        $query = "SELECT * FROM `booking_order` bo 
            INNER JOIN `user_cred` u ON bo.user_id = u.id
            WHERE (bo.invoice_id LIKE ? OR u.phonenum LIKE ? OR u.name LIKE ?) 
            AND  (bo.booking_status=? AND bo.refund=?) ORDER BY bo.booking_id ASC";

        $res = select($query, ["%$frm_data[search]%", "%$frm_data[search]%", "%$frm_data[search]%", "cancelled", 0],'sssss');
       
        $i=1;

        $table_data = "";

        if(mysqli_num_rows($res)==0){
            echo "<tr><td colspan='5' class='text-center py-5'><h6 class='fw-bold'>Không tìm thấy dữ liệu!</h6></td></tr>";
            exit;
        }

        while($data = mysqli_fetch_assoc($res))
        {
            $date = date("d-m-Y", strtotime($data['booking_date']));
            $checkin = date("d-m-Y", strtotime($data['check_in']));
            $checkout = date("d-m-Y", strtotime($data['check_out']));
        
            $table_data .= "
            <tr>
                <td>$i</td>
                <td>
                    <div class='user-detail-box'>
                        <div class='detail-title'>Invoice ID:</div>
                        <div class='detail-content'>$data[invoice_id]</div>
                        <div class='detail-title mt-2'>Tên khách hàng:</div>
                        <div class='detail-content'>$data[name]</div>
                        <div class='detail-title mt-2'>Số điện thoại:</div>
                        <div class='detail-content'>$data[phonenum]</div>
                    </div>
                </td>
                <td>
                    <div class='room-detail-box'>
                        <div class='detail-title'>Tên phòng:</div>
                        <div class='detail-content'>$data[room_name]</div>
                        <div class='detail-title mt-2'>Giá phòng:</div>
                        <div class='detail-content'>" . number_format($data['price']) . " VND</div>
                    </div>
                </td>
                <td>
                    <div class='booking-detail-box'>
                        <div class='detail-title'>Tổng Tiền:</div>
                        <div class='detail-content'>" . number_format($data['total_amount']) . " VND</div>
                        <div class='detail-title mt-2'>Check-in:</div>
                        <div class='detail-content'>$checkin</div>
                        <div class='detail-title mt-2'>Check-out:</div>
                        <div class='detail-content'>$checkout</div>
                        <div class='detail-title mt-2'>Ngày đặt:</div>
                        <div class='detail-content'>$date</div>
                    </div>
                </td>
                <td>
                    <button type='button' onclick='refund_booking($data[booking_id])' class='refund-btn'>
                    <i class='bi bi-cash-stack' ></i> Hoàn tiền
                    </button>
                </td>
            </tr>
            ";
            $i++;
        }
        echo $table_data;
    }

 
    if(isset($_POST['refund_booking']))
    {
        $frm_data = filteration($_POST);

        $query = "UPDATE `booking_order` SET `booking_status`=?, `refund`=? WHERE `booking_id`=?";
        $values = ['refunded', 1, $frm_data['booking_id']];

        $res = update($query, $values, 'sii');

        echo $res;
    }


?>