<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/link.php') ?>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/profile.css">
    <title><?php echo $settings_r['site_title'] ?> - Hồ Sơ Cá Nhân</title>
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

    <!-- Page Header Banner -->
    <div class="page-header-banner">
        <div class="container">
            <h1 class="text-center text-white">Hồ Sơ Cá Nhân</h1>
            <div class="header-line"></div>
            <div class="breadcrumb-container">
                <div class="breadcrumb-pill">
                    <a href="index.php">Trang Chủ</a>
                    <span class="divider">/</span>
                    <span class="current">Hồ Sơ</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container profile-container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="profile-card">
                    <div class="profile-header">
                        <h5>Thông Tin Cá Nhân</h5>
                    </div>
                    <div class="profile-body">
                        <div class="profile-avatar">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <h6 class="profile-info-title">Chỉnh Sửa Thông Tin</h6>
                        
                        <form id="info-form">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="form-label">Tên</label>
                                    <input name="name" type="text" value="<?php echo $u_fetch['name'] ?>" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="form-label">Số Điện Thoại</label>
                                    <input name="phonenum" type="number" value="<?php echo $u_fetch['phonenum'] ?>" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="form-label">Ngày Sinh</label>
                                    <input name="dob" type="date" value="<?php echo $u_fetch['dob'] ?>" class="form-control" required>
                                </div>
                            </div>

                            <div class="password-section">
                                <h6 class="password-title">Đổi Mật Khẩu</h6>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Mật Khẩu Mới</label>
                                        <input name="new_pass" type="password" class="form-control">
                                        <small class="text-muted">Để trống nếu không thay đổi mật khẩu</small>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Nhập Lại Mật Khẩu</label>
                                        <input name="confirm_pass" type="password" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="profile-btn">
                                    <i class="bi bi-check-circle me-2"></i>Lưu Thay Đổi
                                </button>
                            </div>
                        </form>
                    </div>
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

            if (new_pass != confirm_pass) {
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
                if (this.status >= 200 && this.status < 300) {
                    if (this.responseText === 'phone_already') {
                        alert('error', "Số điện thoại đã được đăng ký!");
                    } else if (this.responseText === 'mismatch') {
                        alert('error', "Mật khẩu không khớp!");
                    } else if (this.responseText === '0') {
                        alert('error', "Cập nhật thất bại!");
                    } else if (this.responseText === '1') {
                        alert('success', 'Đã lưu thay đổi!');
                    } else {
                        console.log('Phản hồi không xác định:', this.responseText);
                        alert('error', 'Có lỗi xảy ra, vui lòng thử lại sau!');
                    }
                } else {
                    console.error('Lỗi HTTP:', this.status, this.statusText);
                    alert('error', 'Lỗi kết nối đến máy chủ!');
                }
            };

            xhr.send(data);
        });
    </script>

</body>

</html>