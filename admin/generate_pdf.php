<?php
 require('inc/esentials.php');
 require('inc/db_config.php');
 require __DIR__ . '/../vendor/autoload.php';
 use Mpdf\Mpdf;
 adminLogin();

if(isset($_GET['gen_pdf']) && isset($_GET['id']))
{
    $frm_data = filteration($_GET);
    $query = "SELECT * FROM `booking_order` bo 
        INNER JOIN `user_cred` u ON bo.user_id = u.id
        WHERE ((bo.booking_status='booked' AND bo.arrival=1) 
        OR (bo.booking_status='cancelled' AND bo.refund=1) 
        OR (bo.booking_status='refunded'))
        AND bo.booking_id='$frm_data[id]'";

    $res = mysqli_query($con, $query);
    $total_rows = mysqli_num_rows($res);

    if($total_rows==0){
            header('location: dashboard.php');
            exit;
    }

    $data = mysqli_fetch_assoc($res);
    
    $date = date("h:ia | d-m-Y", strtotime($data['booking_date']));
    $checkin = date("d-m-Y", strtotime($data['check_in']));
    $checkout = date("d-m-Y", strtotime($data['check_out']));


    $table_data = "
    <h2>Hóa Đơn</h2>
    <table border='1'>
        <tr>
            <td>Order ID: $data[invoice_id]</td>
            <td>Booking Date: $date</td>
        </tr>
        <tr>
            <td>Name: $data[name]</td>
            <td colspan='2'>Status: $data[booking_status]</td>
        </tr>
        <tr>
            <td>Email: $data[email]</td>
            <td>Phone Number: $data[phonenum]</td>
        </tr>
        <tr>
            <td>Room Name: $data[room_name]</td>
            <td>Price: " . number_format($data['price']) . " VND Mỗi đêm</td>
        </tr>
        <tr>
            <td>Check-in: $checkin</td>
            <td>check-out: $checkout</td>
        </tr>
    ";
    if ($data['booking_status']=='cancelled')
    {
        $refund = ($data['refund']) ? "Số tiền được hoàn lại" : "Chưa hoàn lại tiền";

        $table_data.="<tr>
            <td>Tổng thanh toán: " . number_format($data['total_amount']) . " VND </td>
            <td>Trạng thái: $refund</td>
        </tr>";
    }
    else if ($data['booking_status']=='refunded')
    {
        $refund = ($data['refund']) ? "Số tiền đã được hoàn lại" : "Chưa hoàn lại tiền";

        $table_data.="<tr>
            <td>Tổng thanh toán: " . number_format($data['total_amount']) . " VND </td>
            <td>Trạng thái: $refund</td>
        </tr>";
    }
    else
    {
        $table_data.="<tr>
            <td>Số phòng: $data[room_no]</td>
            <td>Đã thanh toán: " . number_format($data['total_amount']) . " VND </td>
        </tr>";
    }

    $table_data.= "</table>";


   
    $mpdf = new Mpdf();

    $mpdf->WriteHTML($table_data);

    ob_end_clean(); // Xóa output buffer

    $mpdf->Output($data['invoice_id'] . '.pdf', 'D');

    echo $table_data;
}
else{
    header('location: dashboard.php');
}

?>