<?php
    require('../inc/db_config.php');
    require('../inc/esentials.php');
    adminLogin();
    

    if(isset($_POST['get_bookings'])){
        $frm_data = filteration($_POST);
       
        $query = "SELECT * FROM `booking_order` bo 
            INNER JOIN `user_cred` u ON bo.user_id = u.id
            WHERE (bo.invoice_id LIKE ? OR u.phonenum LIKE ? OR u.name LIKE ?) 
            AND  ((bo.booking_status=? AND bo.arrival=?) 
            OR (bo.booking_status=? AND bo.arrival=?))
            ORDER BY bo.booking_id ASC";

        $res = select($query, [
            "%$frm_data[search]%", 
            "%$frm_data[search]%", 
            "%$frm_data[search]%", 
            "booked", 0,
            "pending", 0
        ], 'ssssisi');
       
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
            
            $payment_status = "";
            $assign_room_btn = "";
            
            if($data['booking_status'] == 'pending') {
                $payment_status = "<span class='badge bg-warning'>Chờ thanh toán</span>";
                
                // Nút xác nhận thanh toán ngân hàng
                $confirm_payment_btn = "
                <button type='button' onclick='confirm_payment($data[booking_id])' class='booking-action btn-success'>
                    <i class='bi bi-check-circle'></i> Xác nhận đã thanh toán
                </button>";
                
                $assign_room_btn = $confirm_payment_btn;
            } else {
                $payment_status = "<span class='badge bg-success'>Đã thanh toán</span>";
                
                // Nút chỉ định phòng
                $assign_room_btn = "
                <button type='button' onclick='assign_room($data[booking_id])' class='booking-action' data-bs-toggle='modal' data-bs-target='#assign-room'>
                    <i class='bi bi-check2-square'></i> Chỉ định phòng
                </button>";
            }
            
            $table_data.="
            <tr>
                <td>$i</td>
                <td>
                    <span class='badge bg-primary'>
                        Order ID: $data[invoice_id]
                    </span>
                    <br>
                    <b>Tên:</b> $data[name]
                    <br>
                    <b>SĐT:</b> $data[phonenum]
                </td>
                <td>
                    <b>Phòng:</b> $data[room_name]
                    <br>
                    <b>Giá:</b> ".number_format($data['price'], 0, ',', '.')." VND
                </td>
                <td>
                    <b>Check-in:</b> $checkin
                    <br>
                    <b>Check-out:</b> $checkout
                    <br>
                    <b>Ngày đặt:</b> $date
                </td>
                <td>
                    <b>Phương thức:</b> ".($data['payment_method'] == 'paypal' ? 'PayPal' : 'Chuyển khoản')."
                    <br>
                    <b>Trạng thái:</b> $payment_status
                </td>
                <td>
                   $assign_room_btn
                    <button type='button' onclick='cancel_booking($data[booking_id])' class='booking-action btn-danger'>
                    <i class='bi bi-trash'></i> Hủy đặt phòng
                    </button>
                </td>
            </tr>
            ";
            $i++;
        }
        echo $table_data;
    }

    if(isset($_POST['assign_room']))
    {
        $frm_data = filteration($_POST);

        $query = "UPDATE `booking_order` bo SET bo.arrival = ?,bo.rate_review=?, bo.room_no = ?  WHERE bo.booking_id = ?";

        $values = [1,0,$frm_data['room_no'], $frm_data['booking_id']];

        $res = update($query, $values, 'iisi');

        echo ($res==true) ? 1 : 0;
    }

    if(isset($_POST['confirm_payment']))
    {
        $frm_data = filteration($_POST);

        $query = "UPDATE `booking_order` SET `booking_status`=? WHERE `booking_id`=?";
        $values = ['booked', $frm_data['booking_id']];

        $res = update($query, $values, 'si');

        echo ($res==true) ? 1 : 0;
    }

    if(isset($_POST['cancel_booking']))
    {
        $frm_data = filteration($_POST);

        $query = "UPDATE `booking_order` SET `booking_status`=?, `refund`=? WHERE `booking_id`=?";
        $values = ['cancelled', 0, $frm_data['booking_id']];

        $res = update($query, $values, 'sii');

        echo ($res==true) ? 1 : 0;
    }


?>