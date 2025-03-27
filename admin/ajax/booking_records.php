<?php

require('../inc/db_config.php');
require('../inc/esentials.php');
adminLogin();

if (isset($_POST['get_bookings'])) {
    $frm_data = filteration($_POST);

    $limit = 7;
    $page = $frm_data['page'];
    $start = ($page - 1) * $limit;

    $query = "SELECT * FROM `booking_order` bo 
        INNER JOIN `user_cred` u ON bo.user_id = u.id
        WHERE ((bo.booking_status='booked' AND bo.arrival=1) 
        OR (bo.booking_status='cancelled' AND bo.refund=1) 
        OR (bo.booking_status='refunded'))
        AND (bo.invoice_id LIKE ? OR u.phonenum LIKE ? OR u.name LIKE ?) 
        ORDER BY bo.booking_id DESC";

    $res = select($query, ["%$frm_data[search]%", "%$frm_data[search]%", "%$frm_data[search]%"], 'sss');

    $limit_query = $query . " LIMIT $start, $limit";
    $limit_res = select($limit_query, ["%$frm_data[search]%", "%$frm_data[search]%", "%$frm_data[search]%"], 'sss');

    $i = $start+1;
    $table_data = "";
    $total_rows = mysqli_num_rows($res);

    if ($total_rows == 0) {
        // Nếu không tìm thấy dữ liệu, trả về thông báo trống và không có phân trang
        $pagination = "";  // Không hiển thị phân trang
        $table_data = "<tr><td colspan='6' class='text-center py-5'><h6 class='fw-bold'>Không tìm thấy dữ liệu!</h6></td></tr>";
        
        echo json_encode([
            "table_data" => $table_data,
            "pagination" => $pagination
        ]);
        exit;
    }
    while ($data = mysqli_fetch_assoc($limit_res)) {
        $date = date("d-m-Y", strtotime($data['booking_date']));
        $checkin = date("d-m-Y", strtotime($data['check_in']));
        $checkout = date("d-m-Y", strtotime($data['check_out']));

        $status = $data['booking_status'];

        // Add proper status display class
        $status_class = '';
        if($status == 'booked') {
            $status_class = 'booked';
            $status_text = 'Đã đặt phòng';
        } else if($status == 'cancelled') {
            $status_class = 'cancelled';
            $status_text = 'Đã hủy';
        } else if($status == 'refunded') {
            $status_class = 'refunded';
            $status_text = 'Đã hoàn tiền';
        }

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
                    <div class='detail-title'>Tổng tiền:</div>
                    <div class='detail-content'>" . number_format($data['total_amount']) . " VND</div>
                    <div class='detail-title mt-2'>Ngày đặt:</div>
                    <div class='detail-content'>$date</div>
                    <div class='detail-title mt-2'>Check-in:</div>
                    <div class='detail-content'>$checkin</div>
                    <div class='detail-title mt-2'>Check-out:</div>
                    <div class='detail-content'>$checkout</div>
                </div>
            </td>
            <td>
                <span data-booking-status='$status' class='booking-status $status_class'>$status_text</span>
            </td>
            <td>
                <button type='button' onclick='download($data[booking_id])' class='download-pdf'>
                    <i class='bi bi-file-earmark-arrow-down-fill'></i> PDF
                </button>
            </td>
        </tr>
        ";

        $i++;
    }

    $pagination = "";

    if($total_rows>$limit)
    {
        $total_pages = ceil($total_rows/$limit);

        $disabled = ($page==1)? "disabled": "";
        $prev= $page-1;

        if($page!=1) {
            $pagination .="<li class='page-item'>
              <button onclick='change_page(1)' class='page-link shadow-none'>Đầu</button>
            </li>";
        }

        $pagination .="<li class='page-item $disabled'>
                        <button onclick='change_page($prev)' class='page-link shadow-none'>Trước</button>
                    </li>";

        $disabled = ($page==$total_pages) ? "disabled": "";
        $next = $page+1;

        $pagination .="<li class='page-item $disabled'>
                        <button onclick='change_page($next)' class='page-link shadow-none'>Kế Tiếp</button>
                    </li>";

        if($page!=$total_pages) {
            $pagination .="<li class='page-item'>
            <button onclick='change_page($total_pages)' class='page-link shadow-none'>Cuối</button>
            </li>";
            }            
    }
    // Loại bỏ các ký tự thừa như \r\n
    $table_data = trim($table_data);
    $pagination = trim($pagination);

    $output = [
        "table_data" => $table_data,
        "pagination" => $pagination
    ];

    // Trả về kết quả dưới dạng JSON
    echo json_encode($output);
}

if (isset($_POST['assign_room'])) {
    $frm_data = filteration($_POST);

    $query = "UPDATE `booking_order` bo SET bo.arrival = ?, bo.room_no = ?  WHERE bo.booking_id = ?";
    $values = [1, $frm_data['room_no'], $frm_data['booking_id']];
    $res = update($query, $values, 'isi');
    echo ($res == true) ? 1 : 0;
}

if (isset($_POST['cancel_booking'])) {
    $frm_data = filteration($_POST);

    $query = "UPDATE `booking_order` SET `booking_status`=?, `refund`=? WHERE `booking_id`=?";
    $values = ['cancelled', 0, $frm_data['booking_id']];
    $res = update($query, $values, 'sii');
    echo $res;
}

?>
