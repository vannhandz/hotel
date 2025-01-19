<?php
    require('../inc/db_config.php');
    require('../inc/esentials.php');
    adminLogin();
    
    //  get dl setting
    if(isset($_POST['get_general']))
    {
        $q ="SELECT * FROM `settings` WHERE `id_setting` =?";
        $values = [1];
        $res = select($q,$values,"i");
        $data= mysqli_fetch_assoc($res);
        $json_data = json_encode($data);
        echo $json_data;
    }

    // update dl settting
    if(isset($_POST['upd_general']))
    {
        $frm_data=filteration($_POST); //  Lọc và làm sạch dữ liệu 
        $q ="UPDATE `settings` SET `site_title`=? , `site_about`=? WHERE `id_setting`=?";
        $values=[$frm_data['site_title'],$frm_data['site_about'],1];
        $res = update($q,$values,'ssi');
        echo $res;
    }


    if(isset($_POST['upd_shutdown']))
    {
        $frm_data= ($_POST['upd_shutdown']==0) ? 1 : 0;
        $q ="UPDATE `settings` SET `shutdown`=? WHERE `id_setting`=?";
        $values=[$frm_data,1];
        $res = update($q,$values,'ii');
        echo $res;
    }
?>