<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/link.php') ?>
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo $settings_r['site_title'] ?> BOOKINGS</title>
</head>

<body class="bg-light">

    <?php 
        require('inc/header.php') ;

        if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
            redirect('index.php');
        }
    ?>

    <div class="container">
        <div class="row">
            <div class="col-12 my-5 px-4">
                <h2 class="fw-bold">BOOKINGS</h2>
                <div style="font-size:14px">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">BOOKINGS</a>
                </div>
            </div>

            <?php 
                $query="SELECT * FROM `booking_order` bo 
                    INNER JOIN `user_cred` u ON bo.user_id = u.id
                    WHERE ((bo.booking_status='booked') 
                    OR (bo.booking_status='cancelled') 
                    OR (bo.booking_status='refunded'))
                    AND (bo.user_id=?)
                    ORDER BY bo.booking_id DESC";

                $result=select($query,[$_SESSION['uId']],'i');

                while ($data=mysqli_fetch_assoc($result)){

                    $date = date("d-m-Y", strtotime($data['booking_date']));
                    $checkin = date("d-m-Y", strtotime($data['check_in']));
                    $checkout = date("d-m-Y", strtotime($data['check_out']));


                    $status_bg = "";
                    $btn = "";

                    if ($data['booking_status'] == 'booked')
                    {
                        $status_bg = "bg-success";
                        if ($data['arrival'] == 1)
                        {

                            $btn = "<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'>Download PDF </a>
                                <button type='button'  class='btn btn-dark btn-sm shadow-none'> Xếp hạng và Đánh giá</button>
                            ";
                        }else{
                            
                            $btn = "<button onclick='cancel_booking($data[booking_id])' type='button'  class='btn btn-danger btn-sm shadow-none'> Hủy bỏ</button>
                            ";
                        }
                    }
                    else if ($data['booking_status'] == 'cancelled')
                    {
                        $status_bg = "bg-danger";
                        if ($data['refund'] == 0)
                        {
                            $btn = "<span class='badge bg-primary'>Đang hoàn tiền!</span>";
                        }
                        else
                        {
                            $btn = "<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'>Download PDF </a>";
                        }
                    }
                    else{
                        $status_bg = "bg-warning";
                        $btn = "<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'>Download PDF </a>";
                    }
                    $formatted_price = number_format($data['price'], 0, ',', '.');
                    $formatted_price_total = number_format($data['total_amount'], 0, ',', '.');

                    echo <<<bookings
                        <div class='col-md-4 px-4 mb-4'>
                            <div class='bg-white p-3 rounded shadow-sm'>
                                <h5 class='fw-bold'>$data[room_name]</h5>
                                <b>Price:</b> $formatted_price VND Mỗi đêm
                                <p>
                                    <b>Check in: </b> $checkin <br>
                                    <b>Check out: </b> $checkout
                                </p>
                                <p>
                                    <b>Amount: </b> $formatted_price_total VND<br>
                                    <b>Order ID: </b> $data[invoice_id] <br>
                                    <b>Date: </b> $date
                                </p>
                                <p>
                                    <span class='badge $status_bg'>$data[booking_status]</span>
                                </p>
                                $btn
                            </div>
                        </div>
                    bookings;

                }
                

            ?>

        </div>
    </div>
    <?php
    if(isset($_GET['cancel_status'])){
        alert('success', 'Đã hủy đặt phòng');
    }
    ?>

    <?php require('inc/footer.php') ?>

    <script>
    function cancel_booking(id)
    {
        if(confirm('Bạn có chắc chắn hủy đặt phòng?'))
        {
            
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/cancel_booking.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function(){
                if(this.responseText==1){
                    window.location.href="bookings.php?cancel_status=true";
                }
                else{
                    alert('error', 'Hủy không thành công!');
                }
            }
            console.log(id);
            xhr.send('cancel_booking&id='+id);
        }
    }
    </script>

</body>

</html>