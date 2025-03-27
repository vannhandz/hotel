<?php
session_start();
require_once("./config.php");
require_once("../admin/inc/db_config.php");
require_once("../admin/inc/esentials.php");

// Lấy thông tin thanh toán từ session
if(!isset($_SESSION['vnpay_payment'])) {
    redirect('../index.php');
}

$payment_info = $_SESSION['vnpay_payment'];
$room_name = $payment_info['room_name'];
$number_of_nights = $payment_info['number_of_nights'];
$total_amount = $payment_info['total_amount'];
$check_in = $payment_info['check_in'];
$check_out = $payment_info['check_out'];
$room_id = $payment_info['room_id'];
$user_id = $payment_info['user_id'];
$room_price = $payment_info['room_price'];
$invoice_id = $payment_info['invoice_id'];
$booking_date = $payment_info['booking_date'];

// Định dạng ngày tháng
$check_in_formatted = date('Y-m-d', strtotime($check_in));
$check_out_formatted = date('Y-m-d', strtotime($check_out));

// Kiểm tra thông tin từ VNPAY trả về
$vnp_SecureHash = $_GET['vnp_SecureHash'];
$inputData = array();
foreach ($_GET as $key => $value) {
    if (substr($key, 0, 4) == "vnp_") {
        $inputData[$key] = $value;
    }
}

unset($inputData['vnp_SecureHash']);
ksort($inputData);
$i = 0;
$hashData = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
}

$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
$vnp_Amount = $_GET['vnp_Amount']/100; // Số tiền thanh toán VNPAY trả về

// Kiểm tra chữ ký hợp lệ và số tiền trùng khớp
$isValidSignature = ($secureHash == $vnp_SecureHash);
$isValidAmount = ($vnp_Amount == $total_amount);

// Kiểm tra trạng thái giao dịch
$isSuccess = ($_GET['vnp_ResponseCode'] == '00');
$transactionNo = $_GET['vnp_TransactionNo']; // Mã giao dịch từ VNPAY

// Nếu thanh toán thành công, lưu thông tin đặt phòng vào DB
if($isValidSignature && $isSuccess && $isValidAmount) {
    // Trạng thái đơn hàng
    $status = 'booked';
    $payment_method = 'vnpay';
    $formatted_total = number_format($total_amount, 0, ',', '.');
    
    // Lưu thông tin đặt phòng vào DB
    $query = "INSERT INTO `booking_order` (`user_id`, `room_id`, `room_name`, `check_in`, `check_out`, 
            `number_of_nights`, `price`, `total_amount`, `invoice_id`, `booking_date`, `booking_status`, `payment_method`, `transaction_id`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "iisssiddsssss", 
        $user_id, $room_id, $room_name, $check_in_formatted, $check_out_formatted, 
        $number_of_nights, $room_price, $total_amount, $invoice_id, $booking_date, $status, $payment_method, $transactionNo);
    
    // Thực hiện lưu vào DB
    $booking_saved = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả thanh toán VNPAY</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/bank_success.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <div class="bank-success-container">
        <div class="bank-success-card">
            <div class="bank-success-header" style="background-color: <?php echo $isSuccess ? '#28a745' : '#dc3545'; ?>;">
                <h2><?php echo $isSuccess ? 'Thanh Toán Thành Công' : 'Thanh Toán Thất Bại'; ?></h2>
                <div class="subheading">
                    <?php echo $isSuccess ? 'Đơn đặt phòng của bạn đã được xác nhận' : 'Đã xảy ra lỗi trong quá trình thanh toán'; ?>
                </div>
            </div>
            
            <div class="card-body p-4">
                <?php if($isSuccess): ?>
                <div class="alert-notification" style="background-color: #d4edda; border-left-color: #28a745;">
                    <i class="bi bi-check-circle-fill" style="color: #28a745;"></i>
                    <p style="color: #155724;">Cảm ơn bạn đã đặt phòng. Chúng tôi đã nhận được thanh toán của bạn.</p>
                </div>
                
                <div class="booking-info">
                    <h4><i class="bi bi-calendar2-check"></i> Chi tiết đặt phòng</h4>
                    
                    <div class="booking-info-row">
                        <div class="booking-info-label">Phòng:</div>
                        <div class="booking-info-value highlight"><?php echo $room_name; ?></div>
                    </div>
                    
                    <div class="booking-info-row">
                        <div class="booking-info-label">Số đêm:</div>
                        <div class="booking-info-value"><?php echo $number_of_nights; ?> đêm</div>
                    </div>
                    
                    <div class="booking-info-row">
                        <div class="booking-info-label">Nhận phòng:</div>
                        <div class="booking-info-value"><?php echo date('d/m/Y', strtotime($check_in)); ?></div>
                    </div>
                    
                    <div class="booking-info-row">
                        <div class="booking-info-label">Trả phòng:</div>
                        <div class="booking-info-value"><?php echo date('d/m/Y', strtotime($check_out)); ?></div>
                    </div>
                    
                    <div class="booking-info-row">
                        <div class="booking-info-label">Mã hóa đơn:</div>
                        <div class="booking-info-value"><?php echo $invoice_id; ?></div>
                    </div>
                    
                    <div class="booking-info-row">
                        <div class="booking-info-label">Mã giao dịch:</div>
                        <div class="booking-info-value"><?php echo $transactionNo; ?></div>
                    </div>
                    
                    <div class="booking-info-row">
                        <div class="booking-info-label">Tổng thanh toán:</div>
                        <div class="booking-info-value price"><?php echo number_format($total_amount, 0, ',', '.'); ?> VND</div>
                    </div>
                </div>
                <?php else: ?>
                <div class="alert-notification" style="background-color: #f8d7da; border-left-color: #dc3545;">
                    <i class="bi bi-exclamation-triangle-fill" style="color: #dc3545;"></i>
                    <p style="color: #721c24;">
                        Thanh toán không thành công (Mã lỗi: <?php echo $_GET['vnp_ResponseCode']; ?>).
                        Vui lòng thử lại hoặc chọn phương thức thanh toán khác.
                    </p>
                </div>
                <?php endif; ?>
                
                <div class="action-buttons">
                    <?php if($isSuccess): ?>
                    <a href="../bookings.php" class="btn-view-bookings">
                        <i class="bi bi-list-check"></i> Xem đơn đặt phòng
                    </a>
                    <?php endif; ?>
                    <a href="../index.php" class="btn-home" <?php echo !$isSuccess ? 'style="grid-column: span 2;"' : ''; ?>>
                        <i class="bi bi-house"></i> Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
// Xóa session sau khi sử dụng
unset($_SESSION['vnpay_payment']);
?>
