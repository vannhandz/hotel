<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('admin/inc/db_config.php');
require('admin/inc/esentials.php');

$token = $_GET['token'] ?? '';
$email = $_GET['email'] ?? '';

echo "<h2>Kiểm tra token</h2>";
echo "Token: " . $token . "<br>";
echo "Email: " . $email . "<br>";

// Kiểm tra email có tồn tại không
$query = select("SELECT * FROM `user_cred` WHERE `email`=? LIMIT 1", [$email], 's');
echo "<h3>Kiểm tra email:</h3>";
echo "Số kết quả: " . mysqli_num_rows($query) . "<br>";
if(mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
    echo "Token trong DB: " . $row['token'] . "<br>";
    echo "Thời gian hết hạn: " . $row['t_expire'] . "<br>";
    echo "Trạng thái: " . $row['status'] . "<br>";
}

// Kiểm tra token có hợp lệ không
$query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? LIMIT 1", [$email, $token], 'ss');
echo "<h3>Kiểm tra token:</h3>";
echo "Số kết quả: " . mysqli_num_rows($query) . "<br>";

// Kiểm tra token có hết hạn không
$query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire` > NOW() LIMIT 1", [$email, $token], 'ss');
echo "<h3>Kiểm tra thời gian hết hạn:</h3>";
echo "Số kết quả: " . mysqli_num_rows($query) . "<br>";

// Kiểm tra trạng thái tài khoản
$query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire` > NOW() AND `status`=? LIMIT 1", [$email, $token, 1], 'ssi');
echo "<h3>Kiểm tra trạng thái tài khoản:</h3>";
echo "Số kết quả: " . mysqli_num_rows($query) . "<br>";
?> 