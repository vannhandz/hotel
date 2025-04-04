<?php
    require('../inc/db_config.php');
    require('../inc/esentials.php');
    adminLogin();
    

    if(isset($_POST['get_users'])){
        $res = selectAll('user_cred');
        $i=1;
    
        $data="";
        
        while($row = mysqli_fetch_assoc($res)) {

            $del_btn=" <button type='button' onclick='remove_user($row[id])' class='btn btn-danger btn-action shadow-none btn-sm'>
                            <i class='bi bi-trash'></i>
                        </button> ";

            $verified = "<span data-verified='no'>Không</span>";

            if($row['is_verified']) {
                $verified = "<span data-verified='yes'>Có</span>";
                // $del_btn="";
            }

            $status="<span data-status='active' onclick='toggle_status($row[id],0)' class='btn btn-sm btn-action btn-primary'>Hoạt Động</span>";

            if(!$row['status']) {
                $status="<span data-status='inactive' onclick='toggle_status($row[id],1)' class='btn btn-sm btn-action btn-danger'>Vô Hiệu Hóa</span>";
            }
            $date = date("d-m-Y", strtotime($row['datentime']));

            $data .= "<tr>
                <td>$i</td>
                <td>
                $row[name]
                </td>
                <td>$row[email]</td>
                <td>$row[phonenum]</td>
                <td>$verified</td>
                <td>$status</td>
                <td>$date</td>
                <td>$del_btn</td>
                </tr>";
            $i++;
          }
        echo $data;
    }

    if(isset($_POST['toggle_status'])){
       $frm_data = filteration($_POST);

       $q="UPDATE `user_cred`   SET `status` =? WHERE `id`=?";
       $v=[$frm_data['value'],$frm_data['toggle_status']];

       if(update($q,$v,'ii')){
            echo 1;
       }else{
            echo 0;
       }
    }

    if (isset($_POST['remove_user'])) {
        $frm_data = filteration($_POST);
    
        $res =delete("DELETE FROM `user_cred` WHERE `id`=?  ",[$frm_data['user_id']],'i');
    
        if ($res) {
            echo 1;
        }else{
            echo 0;
        }
    }

    if(isset($_POST['search_user'])){
        $frm_data = filteration($_POST);

        $query = "SELECT * FROM `user_cred` WHERE `name` LIKE ?";

        $res = select($query, ["%$frm_data[name]%"], 's');
        $i=1;
        $data="";
        
        while($row = mysqli_fetch_assoc($res)) {

            $del_btn=" <button type='button' onclick='remove_user($row[id])' class='btn btn-danger btn-action shadow-none btn-sm'>
                            <i class='bi bi-trash'></i>
                        </button> ";

            $verified = "<span data-verified='no'>Không</span>";

            if($row['is_verified']) {
                $verified = "<span data-verified='yes'>Có</span>";
                // $del_btn="";
            }

            $status="<span data-status='active' onclick='toggle_status($row[id],0)' class='btn btn-sm btn-action btn-primary'>Hoạt Động</span>";

            if(!$row['status']) {
                $status="<span data-status='inactive' onclick='toggle_status($row[id],1)' class='btn btn-sm btn-action btn-danger'>Vô Hiệu Hóa</span>";
            }
            $date = date("d-m-Y", strtotime($row['datentime']));

            $data .= "<tr>
                <td>$i</td>
                <td>
                $row[name]
                </td>
                <td>$row[email]</td>
                <td>$row[phonenum]</td>
                <td>$verified</td>
                <td>$status</td>
                <td>$date</td>
                <td>$del_btn</td>
                </tr>";
            $i++;
          }
        echo $data;
    }
?>