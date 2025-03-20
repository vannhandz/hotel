<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/link.php') ?>
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo $settings_r['site_title'] ?> CONTACT</title>
</head>

<body class="bg-light">

    <?php require('inc/header.php') ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">Liên Hệ</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
        Chúng tôi cam kết mang đến cho quý khách dịch vụ hỗ trợ khách hàng tận tình và chuyên nghiệp.  <br>
        Nếu quý khách gặp bất kỳ vấn đề nào, xin vui lòng liên hệ với bộ phận hỗ trợ khách hàng của chúng tôi để được giải đáp và hỗ trợ kịp thời.
        </p>
    </div>

 
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4 ">
                    <iframe class="w-100 rounded mb-4" height="320px"
                        src="<?php echo $contact_r['iframe'] ?>"></iframe>
                    <h5>Địa Chỉ</h5>
                    <a href="<?php echo $contact_r['gmap'] ?>" target="_blank"
                        class="d-inline-block text-decoration-none text-dark mb-2">
                        <i class="bi bi-geo-alt-fill"></i><?php echo $contact_r['address'] ?>
                    </a>
                    <h5 class="mt-4">Liên Hệ</h5>
                    <a href="tel: <?php echo $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark"><i
                            class="bi bi-telephone-fill "></i>+<?php echo $contact_r['pn1'] ?>
                    </a>
                    <br>
                    <?php 
                        if($contact_r['pn2'] !=''){
                            echo<<<data
                                    <a href="tel: +$contact_r[pn2]" class="d-inline-block  text-decoration-none text-dark"><i
                                        class="bi bi-telephone-fill  "></i>+<?php echo $contact_r[pn2] ?>
                                </a>
                            data;

                        }

                    ?>

                    <h5 class="mt-4">Email</h5>
                    <a href="mailto: <?php echo $contact_r['email'] ?>" class="d-inline-block  text-decoration-none text-dark">
                        <i class="bi bi-envelope-fill"></i> <?php echo $contact_r['email'] ?>
                    </a>
                    <h5 class="mt-4">Mạng Xã Hội</h5>
                    
                    <?php 
                        if($contact_r['tw']!=''){
                            echo<<<data
                                <a href="$contact_r[tw]" class="d-inline-block  text-dark fs-5 me-2">
                                    <i class="bi bi-twitter me-1"></i>
                                </a>
                            data;
                        }

                    ?>

                    <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block  text-dark fs-5 me-2">
                        <i class="bi bi-facebook me-1"></i>
                    </a>
                    <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block  text-dark fs-5 ">
                        <i class="bi bi-instagram me-1"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 px-4">
                <div class="bg-white rounded shadow p-4 ">
                    <form method="POST">
                        <h5>Gửi Tin Nhắn</h5>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:500;">Tên</label>
                            <input name="name" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:500;">Email</label>
                            <input name="email" required type="email" class="form-control shadow-none">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:500;">Tiêu Đề</label>
                            <input name="subject" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:500;">Tin Nhắn</label>
                            <textarea name="message" type="text" required class="form-control shadow-none" rows="6" style="resize:none;"></textarea>
                        </div>
                        <button type="submit" name="send" class="btn text-white custom-bg mt-3">Gửi</button>
                    </form>
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
      

</body>

</html>