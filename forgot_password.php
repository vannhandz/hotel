<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - Khách sạn</title>
    <?php require('inc/link.php'); ?>
    <style>
        .custom-bg {
            background-color: #2ec1ac;
        }
        .custom-bg:hover {
            background-color: #279e8c;
        }
        .h-line {
            width: 150px;
            margin: 0 auto;
            height: 1.7px;
        }
        .custom-alert {
            position: fixed;
            top: 80px;
            right: 25px;
            z-index: 1111;
        }
        .reset-link {
            word-break: break-all;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
            border: 1px solid #ddd;
            display: none;
        }
        .reset-link a {
            color: #0d6efd;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container">
        <div class="row justify-content-center" style="margin-top: 100px;">
            <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4">
                    <form id="forgot-form">
                        <h5 class="mb-4 fw-bold">QUÊN MẬT KHẨU</h5>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input name="email" required type="email" class="form-control shadow-none" placeholder="Nhập email đã đăng ký">
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <button type="submit" class="btn btn-primary shadow-none me-2">GỬI LINK KHÔI PHỤC</button>
                            <a href="index.php" class="text-secondary text-decoration-none">Quay lại trang chủ</a>
                        </div>
                    </form>
                    <div id="reset-link-box" class="reset-link">
                        <p class="mb-2">Link khôi phục mật khẩu:</p>
                        <a href="#" id="reset-link-url" target="_blank"></a>
                        <p class="mt-2 text-secondary">Lưu ý: Link này sẽ hết hạn sau 15 phút.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>

    <script>
        let forgot_form = document.getElementById('forgot-form');
        let resetLinkDiv = document.getElementById('reset-link');

        forgot_form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            let data = new FormData();
            data.append('email', forgot_form.elements['email'].value);
            data.append('forgot_pass', '');

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/forgot_password.php", true);

            xhr.onload = function() {
                console.log("Server response:", this.responseText);
                
                // Loại bỏ khoảng trắng từ phản hồi
                let response = this.responseText.trim();
                
                if (response === 'email_sent') {
                    alert('success', 'Link khôi phục mật khẩu đã được gửi đến email của bạn!');
                    forgot_form.reset();
                    resetLinkDiv.style.display = 'none';
                } else if (response === 'email_failed') {
                    alert('error', 'Không thể gửi email. Vui lòng thử lại sau.');
                } else if (response === 'invalid_email') {
                    alert('error', 'Email không tồn tại trong hệ thống!');
                } else {
                    alert('error', 'Lỗi không xác định! Vui lòng thử lại sau. (' + response + ')');
                }
            }

            xhr.send(data);
        });
    </script>
 <?php require('chat.php') ?>
</body>
</html> 