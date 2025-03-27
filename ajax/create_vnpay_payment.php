<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
date_default_timezone_set('Asia/Ho_Chi_Minh');

/**
 * File xử lý tạo thanh toán VNPAY cho hệ thống đặt phòng khách sạn
 */
session_start();
require_once("./config.php");
require_once("../admin/inc/db_config.php");
require_once("../admin/inc/esentials.php");

// Kiểm tra đăng nhập
if (!isset($_SESSION['login']) && $_SESSION['login'] != true) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để tiếp tục!']);
    exit;
}

// Lấy dữ liệu từ form
$check_in = isset($_POST['check_in']) ? $_POST['check_in'] : '';
$check_out = isset($_POST['check_out']) ? $_POST['check_out'] : '';
$room_id = isset($_POST['room_id']) ? $_POST['room_id'] : '';
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$room_price = isset($_POST['room_price']) ? $_POST['room_price'] : '';

// Kiểm tra dữ liệu
if (empty($check_in) || empty($check_out) || empty($room_id) || empty($user_id) || empty($room_price)) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin cần thiết!']);
    exit;
}

// Lấy thông tin phòng
$room_res = mysqli_query($con, "SELECT * FROM `rooms` WHERE `id`='$room_id'");
if (mysqli_num_rows($room_res) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy thông tin phòng!']);
    exit;
}
$room_data = mysqli_fetch_assoc($room_res);
$room_name = $room_data['name'];

// Tính số đêm lưu trú
$check_in_obj = new DateTime($check_in);
$check_out_obj = new DateTime($check_out);
$diff = $check_in_obj->diff($check_out_obj);
$number_of_nights = $diff->days;

// Tính tổng tiền
$total_amount = $room_price * $number_of_nights;

// Tạo mã đơn hàng
$invoice_id = 'ORD_' . bin2hex(random_bytes(3)) . '_' . time();
$booking_date = date('Y-m-d');

// Lưu thông tin thanh toán vào session để sử dụng sau
$_SESSION['vnpay_payment'] = array(
    'room_name' => $room_name,
    'number_of_nights' => $number_of_nights,
    'total_amount' => $total_amount,
    'check_in' => $check_in,
    'check_out' => $check_out,
    'room_id' => $room_id,
    'user_id' => $user_id,
    'room_price' => $room_price,
    'invoice_id' => $invoice_id,
    'booking_date' => $booking_date
);

// Tạo đơn hàng VNPAY
$vnp_TxnRef = $invoice_id; // Mã đơn hàng
$vnp_Amount = $total_amount; // Số tiền
$vnp_Locale = 'vn'; // Ngôn ngữ (vn/en)
$vnp_BankCode = ''; // Mã ngân hàng (có thể để trống để hiển thị trang chọn ngân hàng)
$vnp_IpAddr = $_SERVER['REMOTE_ADDR']; // IP khách hàng

$inputData = array(
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_Amount" => $vnp_Amount * 100, // Nhân 100 vì VNPAY tính tiền theo đơn vị 100 đồng
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => $vnp_IpAddr,
    "vnp_Locale" => $vnp_Locale,
    "vnp_OrderInfo" => "Thanh toan phong " . $room_name,
    "vnp_OrderType" => "billpayment",
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_TxnRef" => $vnp_TxnRef,
    "vnp_ExpireDate" => $expire
);

// Thêm bankcode nếu có
if (!empty($vnp_BankCode)) {
    $inputData['vnp_BankCode'] = $vnp_BankCode;
}

// Tạo URL thanh toán
ksort($inputData);
$query = "";
$i = 0;
$hashdata = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

$vnp_Url = $vnp_Url . "?" . $query;
if (isset($vnp_HashSecret)) {
    $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
}

// Trả về thông tin URL thanh toán
echo json_encode([
    'status' => 'success',
    'message' => 'Đã tạo URL thanh toán VNPAY',
    'payment_url' => $vnp_Url
]); 