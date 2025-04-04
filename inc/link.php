<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Merienda:wght@700&family=Poppins:wght@400;500;600&display=swap"
    rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link rel="stylesheet" href="css/style.css">

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- CSS tùy chỉnh -->
<link rel="stylesheet" href="css/common.css">
<style>
    :root{
        --teal:#2ec1ac;
        --teal_hover: #279e8c;
        --dark-text: #020202;
    }
</style>

<?php
    session_start();
    require('admin/inc/db_config.php');
    require('admin/inc/esentials.php');
    
    $contact_q="SELECT * FROM `contact_details` WHERE `id_contact`=?";
    $settings_q="SELECT * FROM `settings` WHERE `id_setting`=?";
    $values=[1];
    $contact_r=mysqli_fetch_assoc(select($contact_q,$values,'i'));
    $settings_r=mysqli_fetch_assoc(select($settings_q,$values,'i'));

    if($settings_r['shutdown']) {
        echo<<<alertbar
          <div class='bg-danger text-center p-2 fw-bold'>
            <i class="bi bi-exclamation-triangle-fill"></i>
            Việc đặt chỗ tạm thời bị đóng!
          </div>
        alertbar;
      }
?>
