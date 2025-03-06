<?php
    require('../inc/db_config.php');
    require('../inc/esentials.php');
    adminLogin();
    

    if(isset($_POST['get_bookings'])){
        $frm_data = filteration($_POST);
       
        $query = "SELECT * FROM `booking_order` bo 
            INNER JOIN `user_cred` u ON bo.user_id = u.id
            WHERE (bo.invoice_id LIKE ? OR u.phonenum LIKE ? OR u.name LIKE ?) 
            AND  (bo.booking_status=? AND bo.arrival=?) ORDER BY bo.booking_id ASC";

        $res = select($query, ["%$frm_data[search]%", "%$frm_data[search]%", "%$frm_data[search]%", "booked", 0],'sssss');
       
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
                    <b>Price:</b> " . number_format($data['price']) . " VND
                </td>
                <td>
                    <b>Check-in:</b> $checkin
                    <br>
                    <b>Check-out:</b> $checkout
                    <br>
                    <b>Tổng Tiền:</b> " . number_format($data['total_amount']) . " VND
                    <br>
                    <b>Ngày Đặt:</b> $date
                </td>
                <td>
                   <button type='button' onclick='assign_room($data[booking_id])' class='btn text-white btn-sm fw-bold custom-bg shadow-none' data-bs-toggle='modal' data-bs-target='#assign-room'>
                    <i class='bi bi-check2-square' ></i> Chỉ định phòng
                    </button>
                    <br>
                    <button type='button' onclick='cancel_booking($data[booking_id])' class='mt-2 btn btn-outline-danger btn-sm fw-bold  shadow-none'>
                    <i class='bi bi-trash' ></i> Hủy đặt phòng
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

        $query = "UPDATE `booking_order` bo SET bo.arrival = ?, bo.room_no = ?  WHERE bo.booking_id = ?";

        $values = [1,$frm_data['room_no'], $frm_data['booking_id']];

        $res = update($query, $values, 'isi');

        echo ($res==true) ? 1 : 0;
    }

    if(isset($_POST['cancel_booking']))
    {
        $frm_data = filteration($_POST);

        $query = "UPDATE `booking_order` SET `booking_status`=?, `refund`=? WHERE `booking_id`=?";
        $values = ['cancelled', 0, $frm_data['booking_id']];

        $res = update($query, $values, 'sii');

        echo $res;
    }


?>