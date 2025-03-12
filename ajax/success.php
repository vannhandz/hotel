<?php
// Include thư viện PayPal
require __DIR__ . '/../vendor/autoload.php';
require('../admin/inc/db_config.php');
require('../admin/inc/esentials.php');

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

// Lấy các tham số từ URL
$paymentId = $_GET['paymentId'];
$payerId = $_GET['PayerID'];
$token = $_GET['token'];

// Tạo API Context
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        'AWfsJJQtISG19sPZjXVBx5jqSExND-7OxXwGMUdyCagyIVwv3gr0NIShB38JHTVVUBos8srI6_IxFX8x',  // Thay bằng Client ID của bạn
        'EDV-NDzaGMZPt4KrB-h7CHgtMVGu6CYGxl-7i1VvDTpjRmlE2PSUzikN6qqe6ByaBu-QVVJ1Ytlrmg4F'    // Thay bằng Secret của bạn
    )
);

$apiContext->setConfig([
    'mode' => 'sandbox', // Chế độ sandbox
]);

try {
    // Lấy thông tin thanh toán từ PayPal
    $payment = Payment::get($paymentId, $apiContext);

    // Thực hiện xác nhận thanh toán
    $execution = new PaymentExecution();
    $execution->setPayerId($payerId);

    // Execute payment
    $result = $payment->execute($execution, $apiContext);

    // Kiểm tra trạng thái thanh toán
    if ($result->getState() === 'approved') {
        // Lấy thông tin từ session
        session_start();
        $room_name = $_SESSION['room_name'];
        $number_of_nights = $_SESSION['number_of_nights'];
        // $total_amount = floatval($_SESSION['total_amount']); // Chuyển đổi thành số
        // $total_amount_vnd = $total_amount * 25000;
        $check_in = $_SESSION['check_in'];
        $check_out = $_SESSION['check_out'];
        $room_id = $_SESSION['room_id'];
        $user_id = $_SESSION['user_id'];
        $room_price=$_SESSION['room_price'];
        $total= $room_price*$number_of_nights;

        // Lấy Transaction ID và Invoice ID từ kết quả thanh toán
        $transactionId = $result->getTransactions()[0]->getRelatedResources()[0]->getSale()->getId();
        $invoiceId = $result->getTransactions()[0]->getInvoiceNumber();

        // Định dạng số tiền sang VND

        // Định dạng ngày tháng theo dd/mm/yyyy
        $check_in_formatted = date('Y-m-d', strtotime($check_in));
        $check_out_formatted = date('Y-m-d', strtotime($check_out));

        // Thêm dữ liệu vào cơ sở dữ liệu
        $query = "INSERT INTO `booking_order` (`user_id`, `room_id`, `room_name`,`check_in`, `check_out`, `number_of_nights`,`price`, `total_amount`, `payment_id`, `payer_id`, `transaction_id`, `invoice_id`) VALUES (?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);

        mysqli_stmt_bind_param($stmt, "iisssdddssss", $user_id, $room_id, $room_name , $check_in_formatted,
            $check_out_formatted, $number_of_nights,  $room_price,$total, $paymentId, $payerId, $transactionId, $invoiceId); // Sửa chuỗi kiểu dữ liệu

        $formatted_total = number_format($total, 0, ',', '.');    
        if (mysqli_stmt_execute($stmt)) {
            // Hiển thị thông báo thành công bằng SweetAlert2
            echo '
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Thanh toán thành công</title>
                    <link rel="stylesheet" href="css/style.css">
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
                </head>
                <body>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        Swal.fire({
                            title: "Thanh toán thành công!",
                            html: "Phòng: \'' . $room_name . '\'<br>Số ngày: ' . $number_of_nights . '<br>Tổng tiền: ' . $formatted_total . ' VND<br>Ngày nhận phòng: ' . date('d/m/Y', strtotime($check_in_formatted)) . '<br>Ngày trả phòng: ' . date('d/m/Y', strtotime($check_out_formatted)) . '",
                            icon: "success",
                            confirmButtonText: "OK",
                            customClass: {
                                popup: "custom-popup-class",
                                confirmButton: "custom-confirm-button-class"
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                               window.location.href = "http://localhost:3000/index.php";
                            }
                        });
                    </script>
                </body>
                </html>
            ';
        } else {
            echo "Lỗi khi thêm dữ liệu vào cơ sở dữ liệu: " . mysqli_error($con);
        }

        // Đóng statement
        mysqli_stmt_close($stmt);

        // // Xóa session sau khi sử dụng
        // session_unset();
        // session_destroy();
    } else {
        echo "<h1>Thanh toán không thành công.</h1>";
        echo "<p>Vui lòng thử lại.</p>";
    }
} catch (Exception $ex) {
    echo "<h1>Lỗi:</h1>";
    echo "<p>" . $ex->getMessage() . "</p>";
}
?>