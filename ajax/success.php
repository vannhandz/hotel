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

// Biến kiểm tra trạng thái thanh toán
$isSuccess = false;
$errorMessage = '';

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
        $isSuccess = true;
        
        // Lấy thông tin từ session
        session_start();
        $room_name = $_SESSION['room_name'];
        $number_of_nights = $_SESSION['number_of_nights'];
        $check_in = $_SESSION['check_in'];
        $check_out = $_SESSION['check_out'];
        $room_id = $_SESSION['room_id'];
        $user_id = $_SESSION['user_id'];
        $room_price = $_SESSION['room_price'];
        $total = $room_price * $number_of_nights;

        // Lấy Transaction ID và Invoice ID từ kết quả thanh toán
        $transactionId = $result->getTransactions()[0]->getRelatedResources()[0]->getSale()->getId();
        $invoiceId = $result->getTransactions()[0]->getInvoiceNumber();

        // Định dạng ngày tháng theo dd/mm/yyyy
        $check_in_formatted = date('Y-m-d', strtotime($check_in));
        $check_out_formatted = date('Y-m-d', strtotime($check_out));

        // Thêm dữ liệu vào cơ sở dữ liệu
        $query = "INSERT INTO `booking_order` (`user_id`, `room_id`, `room_name`,`check_in`, `check_out`, `number_of_nights`,`price`, `total_amount`, `payment_id`, `payer_id`, `transaction_id`, `invoice_id`, `payment_method`) VALUES (?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);

        $payment_method = 'paypal';
        mysqli_stmt_bind_param($stmt, "iisssdddsssss", $user_id, $room_id, $room_name, $check_in_formatted,
            $check_out_formatted, $number_of_nights, $room_price, $total, $paymentId, $payerId, $transactionId, $invoiceId, $payment_method);

        // Thực hiện lưu vào DB    
        $booking_saved = mysqli_stmt_execute($stmt);
        
        // Đóng statement
        mysqli_stmt_close($stmt);
        
        // Format số tiền
        $formatted_total = number_format($total, 0, ',', '.');
    } else {
        $errorMessage = 'Thanh toán không được chấp nhận';
    }
} catch (Exception $ex) {
    $errorMessage = $ex->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả thanh toán PayPal</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/success.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <div class="success-header" style="background-color: <?php echo $isSuccess ? '#28a745' : '#dc3545'; ?>;">
                <div class="success-icon">
                    <i class="bi <?php echo $isSuccess ? 'bi-check-circle' : 'bi-x-circle'; ?>"></i>
                </div>
                <h2><?php echo $isSuccess ? 'Thanh Toán Thành Công' : 'Thanh Toán Thất Bại'; ?></h2>
                <div class="subheading">
                    <?php echo $isSuccess ? 'Đơn đặt phòng của bạn đã được xác nhận' : 'Đã xảy ra lỗi trong quá trình thanh toán'; ?>
                </div>
            </div>
            
            <div class="card-body p-4">
                <?php if($isSuccess): ?>
                <div class="alert-notification">
                    <i class="bi bi-check-circle-fill"></i>
                    <p>Cảm ơn bạn đã đặt phòng. Chúng tôi đã nhận được thanh toán của bạn qua PayPal.</p>
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
                        <div class="booking-info-label">Tổng thanh toán:</div>
                        <div class="booking-info-value price"><?php echo $formatted_total; ?> VND</div>
                    </div>
                </div>
                
                <div class="payment-info">
                    <h4><i class="bi bi-credit-card-2-front"></i> Thông tin thanh toán</h4>
                    
                    <div class="payment-info-row">
                        <div class="payment-info-label">Phương thức:</div>
                        <div class="payment-info-value">PayPal</div>
                    </div>
                    
                    <div class="payment-info-row">
                        <div class="payment-info-label">Mã giao dịch:</div>
                        <div class="payment-info-value"><?php echo $transactionId; ?></div>
                    </div>
                    
                    <div class="payment-info-row">
                        <div class="payment-info-label">Mã hóa đơn:</div>
                        <div class="payment-info-value"><?php echo $invoiceId; ?></div>
                    </div>
                </div>
                <?php else: ?>
                <div class="alert-notification" style="background-color: #f8d7da; border-left-color: #dc3545;">
                    <i class="bi bi-exclamation-triangle-fill" style="color: #dc3545;"></i>
                    <p style="color: #721c24;">
                        Thanh toán không thành công: <?php echo $errorMessage; ?>.
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
if ($isSuccess) {
    unset($_SESSION['room_name']);
    unset($_SESSION['number_of_nights']);
    unset($_SESSION['check_in']);
    unset($_SESSION['check_out']);
    unset($_SESSION['room_id']);
    unset($_SESSION['user_id']);
    unset($_SESSION['room_price']);
}
?>