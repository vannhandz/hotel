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
        // Nếu không tìm thấy dữ liệu, trả về thông báo không có dữ liệu và không có phân trang
        $pagination = "";  // Không hiển thị phân trang
        $table_data = "Không tìm thấy dữ liệu!";
        
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

        if ($data['booking_status'] == 'booked') {
            $status_bg = 'bg-success';
        } else if ($data['booking_status'] == 'cancelled') {
            $status_bg = 'bg-danger';
        } else {
            $status_bg = 'bg-warning text-dark';
        }

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
                <b>Tổng Tiền:</b> " . number_format($data['total_amount']) . " VND
                <br>
                <b>Ngày Đặt:</b> $date
            </td>
            <td>
                <span class='badge $status_bg '> $data[booking_status]</span>
            </td>
            <td>
                <button type='button' onclick='download($data[booking_id])' class='btn btn-outline-success btn-sm fw-bold shadow-none'>
                    <i class='bi bi-file-earmark-arrow-down-fill'></i>
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
              <button onclick='change_page(1)' class='page-link shadow-none'>First</button>
            </li>";
        }

        $pagination .="<li class='page-item $disabled'>
                        <button onclick='change_page($prev)' class='page-link shadow-none'>Prev</button>
                    </li>";

        $disabled = ($page==$total_pages) ? "disabled": "";
        $next = $page+1;

        $pagination .="<li class='page-item $disabled'>
                        <button onclick='change_page($next)' class='page-link shadow-none'>Next</button>
                    </li>";

        if($page!=$total_pages) {
            $pagination .="<li class='page-item'>
            <button onclick='change_page($total_pages)' class='page-link shadow-none'>Last</button>
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
