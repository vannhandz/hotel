<?php
    require('inc/esentials.php');
    require('inc/db_config.php');
    adminLogin();
    if(isset($_GET['seen']))
    {
        $frm_data=filteration($_GET);

        if($frm_data['seen']=='all'){

            $q="UPDATE `rating_review` SET `seen`=?";
            $values=[1];
            if(update($q,$values,'i')){
                alert('success','Read All');
            }else{
                alert('error','Failed');
            }
        }else{
            $q="UPDATE `rating_review` SET `seen`=? WHERE `sr_no`=?";
            $values=[1,$frm_data['seen']];
            if(update($q,$values,'ii')){
                alert('success','Read');
            }else{
                alert('error','Failed');
            }
        }
    }


    if(isset($_GET['del']))
    {
        $frm_data=filteration($_GET);

        if($frm_data['del']=='all'){
            $q="DELETE FROM `rating_review` ";
            if(mysqli_query($con,$q)){
                alert('success','Delete All');
            }else{
                alert('error','Failed');
            }
        }else{
            $q="DELETE FROM `rating_review` WHERE `sr_no`=?";
            $values=[$frm_data['del']];
            if(delete($q,$values,'i')){
                alert('success','Delete');
            }else{
                alert('error','Failed');
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Ratings & Review</title>
    <?php require('inc/link.php'); ?>
    <link rel="stylesheet" href="css/rate_review.css">
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="review-page-title">XẾP HẠNG & ĐÁNH GIÁ</h3>

                <div class="card review-card mb-4">
                    <div class="card-body">

                        <div class="review-action-bar">
                            <a href='?seen=all' class='review-btn review-btn-primary'>
                            <i class="bi bi-check-all"></i> Đọc Tất Cả</a>
                            <a href='?del=all' class='review-btn review-btn-danger'>
                            <i class="bi bi-trash3-fill"></i> Xóa Tất Cả</a>
                        </div>

                        <div class="table-responsive-md">
                            <table class="table review-table">
                                <thead class="sticky-top">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Tên Phòng</th>
                                        <th scope="col">Tên Người Dùng</th>
                                        <th scope="col">Xếp Hạng</th>
                                        <th scope="col" width="30%">Đánh Giá</th>
                                        <th scope="col">Ngày</th>
                                        <th scope="col">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $q = "SELECT rr.*,uc.name AS uname, r.name AS rname FROM `rating_review` rr
                                            INNER JOIN `user_cred` uc ON rr.user_id = uc.id
                                            INNER JOIN `rooms` r ON rr.room_id = r.id
                                            ORDER BY `sr_no` DESC";

                                        $data=mysqli_query($con,$q);
                                        $i=1;

                                        while($row = mysqli_fetch_assoc($data)){
                                            
                                            $date = date('d-m-Y', strtotime($row['datentime']));
                                            
                                            // Generate stars based on rating
                                            $stars = '';
                                            for($j=1; $j<=$row['rating']; $j++) {
                                                $stars .= '<i class="bi bi-star-fill"></i>';
                                            }
                                            for($j=$row['rating']+1; $j<=5; $j++) {
                                                $stars .= '<i class="bi bi-star"></i>';
                                            }

                                            $seen='<div class="review-action">';
                                            if($row['seen']!=1){
                                                $seen.="<a href='?seen=$row[sr_no]' class='btn review-action-btn review-read'>Đọc</a>";
                                            }
                                            $seen.="<a href='?del=$row[sr_no]' class='btn review-action-btn review-delete'>Xóa</a>";
                                            $seen.='</div>';
                                            echo<<<query
                                                <tr>
                                                    <td>$i</td>
                                                    <td>$row[rname]</td>
                                                    <td>$row[uname]</td>
                                                    <td><div class="rating-stars">$stars</div></td>
                                                    <td><div class="review-text">$row[review]</div></td>
                                                    <td><div class="review-date">$date</div></td>
                                                    <td>$seen</td>
                                                </tr>
                                            query;
                                            $i++;
                                        }

                                    
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>

</body>

</html>