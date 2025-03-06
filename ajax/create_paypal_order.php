<?php
// Đảm bảo đường dẫn đúng
require __DIR__ . '/../vendor/autoload.php';
require('../admin/inc/db_config.php');
require('../admin/inc/esentials.php');

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

// Kết nối tới cơ sở dữ liệu
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        'AWfsJJQtISG19sPZjXVBx5jqSExND-7OxXwGMUdyCagyIVwv3gr0NIShB38JHTVVUBos8srI6_IxFX8x',  // Thay bằng Client ID của bạn
        'EDV-NDzaGMZPt4KrB-h7CHgtMVGu6CYGxl-7i1VvDTpjRmlE2PSUzikN6qqe6ByaBu-QVVJ1Ytlrmg4F'  // Thay bằng Secret của bạn
    )
);

$apiContext->setConfig([
    'mode' => 'sandbox', // Chế độ sandbox
]);

// Lấy thông tin từ POST
$check_in = $_POST['check_in'];
$check_out = $_POST['check_out'];
$room_id = $_POST['room_id']; // Lấy room_id từ POST
$user_id = $_POST['user_id']; // Lấy user_id từ POST
$room_price=$_POST['room_price']; //


// Truy vấn thông tin phòng từ cơ sở dữ liệu
$room_res = mysqli_query($con, "SELECT * FROM `rooms` WHERE `id` = '$room_id' LIMIT 1");
$room_data = mysqli_fetch_assoc($room_res);

// Kiểm tra xem phòng có tồn tại không
if (!$room_data) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Phòng không tồn tại!'
    ]);
    exit;
}

// Lấy giá phòng từ cơ sở dữ liệu
// $room_price = $room_data['price']; // Đây là giá phòng mỗi đêm (ví dụ: giá mỗi đêm)

// Tính số ngày thuê phòng
$check_in_date = new DateTime($check_in);
$check_out_date = new DateTime($check_out);
$interval = $check_in_date->diff($check_out_date);
$number_of_nights = $interval->days; // Số ngày thuê

// Kiểm tra nếu ngày trả phòng sớm hơn ngày nhận phòng
if ($number_of_nights <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Ngày trả phòng phải sau ngày nhận phòng!'
    ]);
    exit;
}

// Tính tổng số tiền cần thanh toán
$total_amount = ($room_price * $number_of_nights) / 25000; // Tổng số tiền phải trả (chuyển đổi từ VND sang USD)

// Tiến hành tạo thanh toán PayPal
$payer = new Payer();
$payer->setPaymentMethod("paypal");

$item = new Item();
$item->setName($room_data['name']) // Tên phòng
    ->setCurrency('USD')
    ->setQuantity(1)
    ->setPrice($total_amount);

$itemList = new ItemList();
$itemList->setItems([$item]);

$amount = new Amount();
$amount->setCurrency("USD")
    ->setTotal($total_amount);

$transaction = new Transaction();
$transaction->setAmount($amount)
    ->setItemList($itemList)
    ->setDescription("Room Booking Payment")
    ->setInvoiceNumber(uniqid());

$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl("http://localhost/doan/ajax/success.php")
    ->setCancelUrl("http://localhost/doan/index.php");

$payment = new Payment();
$payment->setIntent("sale")
    ->setPayer($payer)
    ->setRedirectUrls($redirectUrls)
    ->setTransactions([$transaction]);

try {
    // Tạo đơn thanh toán PayPal
    $payment->create($apiContext);
    $approvalUrl = $payment->getApprovalLink();

    session_start();
    $_SESSION['room_name'] = $room_data['name'];
    $_SESSION['number_of_nights'] = $number_of_nights;
    // $_SESSION['total_amount'] = number_format($total_amount, 2);
    $_SESSION['check_in'] = $check_in;
    $_SESSION['check_out'] = $check_out;
    $_SESSION['room_id'] = $room_id; // Lưu room_id vào session
    $_SESSION['user_id'] = $user_id; // Lưu user_id vào session
    $_SESSION['room_price']=$room_price; //
    // Trả về URL để chuyển hướng người dùng đến PayPal
    echo json_encode([
        'status' => 'success',
        'approval_url' => $approvalUrl,
        'room_name' => $room_data['name'], // Tên phòng
        'number_of_nights' => $number_of_nights, // Số ngày
        'total_amount' => number_format($total_amount) // Số tiền
    ]);
} catch (Exception $ex) {
    // Lỗi khi tạo đơn thanh toán
    echo json_encode([
        'status' => 'error',
        'message' => $ex->getMessage()
    ]);
}
?>
