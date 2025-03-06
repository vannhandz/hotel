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
            echo "<b>Không tìm thấy dữ liệu!</b>";
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
                    <span class='badge bg-primary'>Invoice Id: $data[invoice_id]</span>
                    <br>
                    <b>Name:</b> $data[name]
                    <br>
                    <b>Phone No:</b> $data[phonenum]
                </td>
                <td>
                    <b>Room:</b> $data[room_name]
                    <br>
                    <b>Check-in:</b> $checkin
                    <br>
                    <b>Check-out:</b> $checkout
                    <br>
                    <b>Ngày Đặt:</b> $date
                </td>
                <td>
                <b>Tổng Tiền:</b> " . number_format($data['total_amount']) . " VND
                </td>
                <td>
                    <button type='button' onclick='refund_booking($data[booking_id])' class=' btn btn-success btn-sm fw-bold shadow-none'>
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