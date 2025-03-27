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

      
    //  get dl contacts
    if(isset($_POST['get_contacts']))
    {
        $q ="SELECT * FROM `contact_details` WHERE `id_contact` =?";
        $values = [1];
        $res = select($q,$values,datatypes: "i");
        $data= mysqli_fetch_assoc($res);
        $json_data = json_encode($data);
        echo $json_data;
    }
    
    // update dl contacts
    if(isset($_POST['upd_contacts']))
      {
          $frm_data=filteration($_POST); //  Lọc và làm sạch dữ liệu 
          $q ="UPDATE `contact_details` SET `address`=?,`gmap`=?,`pn1`=?,`pn2`=?,`email`=?,`fb`=?,`insta`=?,`tw`=?,`iframe`=? WHERE `id_contact`=?";
          $values=[$frm_data['address'],$frm_data['gmap'],$frm_data['pn1'],$frm_data['pn2'],$frm_data['email'],$frm_data['fb'],$frm_data['insta'],$frm_data['tw'],$frm_data['iframe'],1];
          $res = update($q,$values,'sssssssssi');
          echo $res;
    }

    
    if(isset($_POST['add_member'])){
        $frm_data =filteration($_POST);

        $img_r=uploadImage($_FILES['picture'],ABOUT_FOLDER);

        if($img_r=='inv_img'){
            echo $img_r;
        }else if($img_r=='inv_size'){
            echo $img_r;
        }else if($img_r=='upd_failed'){
            echo $img_r;
        }else{
            $q="INSERT INTO `team_details`( `name`, `picture`) VALUES (?, ?)";
            $values= [$frm_data['name'],$img_r];
            $res=insert($q,$values,'ss');
            echo $res;
        }

    }


    if(isset($_POST['get_members'])){
        $res = selectAll('team_details');
        $path = ABOUT_IMG_PATH;
        
        while ($row = mysqli_fetch_assoc($res)) {
            echo <<<data
                <div class="team-card">
                    <img src="$path$row[picture]" class="team-card-img">
                    <div class="team-card-body">
                        <h5 class="team-name">$row[name]</h5>
                        <button type="button" onclick="rem_member($row[id_team])" class="team-delete">
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </div>
                </div>
            data;
        }
    }

    if(isset($_POST['rem_member'])){
        $frm_data=filteration($_POST);
        $values=[$frm_data['rem_member']];

        $pre_q = "SELECT * FROM `team_details` WHERE `id_team`=? ";
        $res = select($pre_q,$values,'i');
        $img= mysqli_fetch_assoc($res);

        if(deleteImage($img['picture'],ABOUT_FOLDER)){
            $q = "DELETE FROM `team_details` WHERE `id_team`=?";
            $res= delete($q,$values,'i');
            echo $res;
        }else
        {
            echo 0;
        }
    }
   
?>