<?php

require('../inc/db_config.php');
require('../inc/esentials.php');
adminLogin();

if (isset($_POST['booking_analytics'])) {
    $frm_data = filteration($_POST);

    $condition="";

    if($frm_data['period']==1) {
        $condition="WHERE booking_date BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";
    }
    else if($frm_data['period']==2) {
        $condition="WHERE booking_date BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
    }
    else if($frm_data['period']==3){
        $condition="WHERE booking_date BETWEEN NOW() - INTERVAL 1 YEAR AND NOW()";
    }

    $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT 
        COUNT(booking_id) AS `total_bookings`,
        SUM(total_amount) AS `total_amt`,
        COUNT(CASE WHEN booking_status='booked' AND arrival=1 THEN 1 END) AS `active_bookings`,
        SUM(CASE WHEN booking_status='booked' AND arrival=1 THEN `total_amount` END) AS `active_amt`,
        COUNT(CASE WHEN booking_status='refunded' AND refund=1 THEN 1 END) AS `cancelled_bookings`,
        SUM(CASE WHEN booking_status='refunded' AND refund=1 THEN `total_amount` END) AS `cancelled_amt`
        FROM `booking_order` $condition"));

    $output=json_encode($result);

    echo $output;
}


if (isset($_POST['user_analytics'])) {
    $frm_data = filteration($_POST);

    $condition="";

    if($frm_data['period']==1) {
        $condition="WHERE datentime BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";
    }
    else if($frm_data['period']==2) {
        $condition="WHERE datentime BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
    }
    else if($frm_data['period']==3){
        $condition="WHERE datentime BETWEEN NOW() - INTERVAL 1 YEAR AND NOW()";
    }

    $total_reviews = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(sr_no) AS `count` FROM `rating_review` $condition"));

    $total_queries = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(sr_no) AS `count` FROM `user_queries` $condition"));

    $total_new_reg = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(id) AS `count` FROM `user_cred` $condition"));

    $output = ['total_queries'=>$total_queries['count'],
            'total_reviews' => $total_reviews['count'],
            'total_new_reg' => $total_new_reg['count']
            ];

    $output = json_encode($output);

    echo $output;
}

?>
