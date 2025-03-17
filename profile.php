<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/link.php') ?>
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo $settings_r['site_title'] ?> PROFILE</title>
</head>

<body class="bg-light">

    <?php 
        require('inc/header.php') ;

        if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
            redirect('index.php');
        }

        $u_exist = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1", [$_SESSION['uId']], 's');

        if(mysqli_num_rows($u_exist)==0){
            redirect('index.php');
        }

        $u_fetch = mysqli_fetch_assoc($u_exist);
    ?>

    <div class="container">
        <div class="row">

            <div class="col-12 my-5 px-4">
                <h2 class="fw-bold">PROFILE</h2>
                <div style="font-size:14px">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">PROFILE</a>
                </div>
            </div>

            <div class="col-12 mb-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                    <form id="info-form">
                        <h5 class="mb-3 fw-bold">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Name</label>
                                <input name="name" type="text" value="<?php echo $u_fetch['name'] ?>" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input name="phonenum" type="number" value="<?php echo $u_fetch['phonenum'] ?>"class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Ngày Sinh</label>
                                <input name="dob" type="date" value="<?php echo $u_fetch['dob'] ?>" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Password</label>
                                <input name="new_pass" type="password"class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Confirm Password</label>
                                <input name="confirm_pass" type="password" class="form-control shadow-none" required>
                            </div>
                        </div>
                        <button type="submit" class="btn text-white custom-bg shadow-none">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
 

    <?php require('inc/footer.php') ?>

    <script>
        let info_form = document.getElementById('info-form');

        info_form.addEventListener('submit', function(e) {
        e.preventDefault();

        let new_pass = info_form.elements['new_pass'].value;
        let confirm_pass = info_form.elements['confirm_pass'].value;

        if (new_pass && confirm_pass && new_pass != confirm_pass) {
            alert('error', 'Mật khẩu không khớp!');
            return false;
        }

        let data = new FormData();
        data.append('info_form', '');
        data.append('name', info_form.elements['name'].value);
        data.append('phonenum', info_form.elements['phonenum'].value);
        data.append('dob', info_form.elements['dob'].value);
        if (new_pass) {
            data.append('new_pass', new_pass);
            data.append('confirm_pass', confirm_pass);
        }

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/profile.php", true);

        xhr.onload = function() {
        if (this.status >= 200 && this.status < 300) { // Kiểm tra mã trạng thái HTTP thành công
            if (this.responseText === 'phone_already') {
              alert('error', "Số điện thoại đã được đăng ký!");
            } else if (this.responseText === 'mismatch') {
              alert('error', "Mật khẩu không khớp!");
            } else if (this.responseText === '0') {
              alert('error', "Cập nhật thất bại!");
            } else if (this.responseText === '1') {
              alert('success', 'Đã lưu thay đổi!');
            } else {
            // Xử lý các phản hồi khác nếu có
            console.log('Phản hồi không xác định:', this.responseText);
            alert('error', 'Có lỗi xảy ra, vui lòng thử lại sau!');
            }
        } else {
            // Xử lý lỗi HTTP (ví dụ: 404, 500)
            console.error('Lỗi HTTP:', this.status, this.statusText);
            alert('error', 'Lỗi kết nối đến máy chủ!');
        }
        };

        xhr.send(data);
        });

    </script>

    <?php require('chat.php') ?>

</body>

</html>