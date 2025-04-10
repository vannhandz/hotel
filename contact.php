<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/link.php') ?>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/shared.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/contact.css?v=<?php echo time(); ?>">
    <title><?php echo $settings_r['site_title'] ?> - Liên Hệ</title>
</head>

<body class="bg-light">

    <?php require('inc/header.php') ?>

    <!-- Banner Section -->
    <div class="page-banner contact-banner">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2>Liên Hệ</h2>
                    <p>Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ quý khách 24/7.</p>
                </div>
            </div>
        </div>
        <div class="wave-shape"></div>
    </div>

    <div class="container">
        <div class="contact-container">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="contact-info-card fade-in">
                        <div class="map-small">
                            <iframe src="<?php echo $contact_r['iframe'] ?>"></iframe>
                        </div>
                        <div class="info-wrapper">
                            <div class="contact-item">
                                <i class="bi bi-geo-alt-fill"></i>
                                <div>
                                    <h6>Địa Chỉ</h6>
                                    <p><?php echo $contact_r['address'] ?></p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="bi bi-telephone-fill"></i>
                                <div>
                                    <h6>Số Điện Thoại</h6>
                                    <p>+<?php echo $contact_r['pn1'] ?></p>
                                    <?php if($contact_r['pn2'] !=''){ echo '<p>+'.$contact_r['pn2'].'</p>'; } ?>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="bi bi-envelope-fill"></i>
                                <div>
                                    <h6>Email</h6>
                                    <p><?php echo $contact_r['email'] ?></p>
                                </div>
                            </div>
                            <div class="social-strip">
                                <?php if($contact_r['tw']!=''){ ?>
                                    <a href="<?php echo $contact_r['tw'] ?>"><i class="bi bi-twitter"></i></a>
                                <?php } ?>
                                <a href="<?php echo $contact_r['fb'] ?>"><i class="bi bi-facebook"></i></a>
                                <a href="<?php echo $contact_r['insta'] ?>"><i class="bi bi-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="contact-form-wrapper fade-in-delay">
                        <h5>Gửi Tin Nhắn</h5>
                        <form method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input name="name" required type="text" class="form-control slide-up" placeholder="Tên">
                                </div>
                                <div class="col-md-6">
                                    <input name="email" required type="email" class="form-control slide-up" placeholder="Email">
                                </div>
                                <div class="col-12">
                                    <input name="subject" required type="text" class="form-control slide-up" placeholder="Tiêu Đề">
                                </div>
                                <div class="col-12">
                                    <textarea name="message" required class="form-control slide-up" rows="3" placeholder="Tin Nhắn"></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" name="send" class="btn-submit">
                                        <i class="bi bi-send"></i> Gửi
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php') ?>
    <?php
        if(isset($_POST['send']))
        {
            $frm_data =filteration($_POST);
            $q ="INSERT INTO `user_queries`( `name`, `email`, `subject`, `message` ) VALUES (?,?,?,?)";
            $values =[$frm_data['name'],$frm_data['email'],$frm_data['subject'],$frm_data['message']];

            $res=insert($q,$values,'ssss');
            if($res==1){
                alert('success','Gửi thành công');
            }else{
                alert('error','Lỗi Server! Thử lại sau.');
            }
        } 
    ?>
 <?php require('chat.php') ?>
</body>

</html>