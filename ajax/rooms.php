<?php

    require('../admin/inc/db_config.php');
    require('../admin/inc/esentials.php');

    session_start();

    if(isset($_GET['fetch_rooms']))
    {
        $chk_avail = json_decode($_GET['chk_avail'], true);

        if($chk_avail['checkin'] != '' && $chk_avail['checkout'] != ''){

            $today_date = new DateTime(date("Y-m-d"));
            $checkin_date = new DateTime($chk_avail['checkin']);
            $checkout_date = new DateTime($chk_avail['checkout']);


            if ($checkin_date == $checkout_date) {
                echo "<h3 class='text-center text-danger'>Ngày nhập không hợp lệ!</h3>";
                exit;
            } else if ($checkout_date < $checkin_date) {
                echo "<h3 class='text-center text-danger'>Ngày nhập không hợp lệ!</h3>";
                exit;
            } else if ($checkin_date < $today_date) {
                echo "<h3 class='text-center text-danger'>Ngày nhập không hợp lệ!</h3>";
                exit;
            }
        }

        
        $guests = json_decode($_GET['guests'],true);
        $adults = ($guests['adults'] != '') ? $guests['adults'] : 0;
        $children = ($guests['children'] != '') ? $guests['children'] : 0;

        // facilities data decode
        $facility_list = json_decode($_GET['facility_list'],true);

        $count_rooms=0;
        $output="";

        $settings_q="SELECT * FROM `settings` WHERE `id_setting`=1";
        $settings_r=mysqli_fetch_assoc(mysqli_query($con,$settings_q));

        $room_res = select("SELECT * FROM `rooms` WHERE `adult` >= ? AND `children` >= ? AND `status`=? AND `removed`=? ", [$adults,$children,1, 0], 'iiii');

        while ($room_data = mysqli_fetch_assoc($room_res)) {

            if($chk_avail['checkin'] != '' && $chk_avail['checkout'] != '')
            {
                $tb_query = "SELECT COUNT(*) AS `total_bookings` FROM `booking_order` 
                WHERE booking_status=? AND room_id=?
                AND check_out > ? AND check_in < ?";

                $values = ['booked',$room_data['id'], $chk_avail['checkin'], $chk_avail['checkout']];
                $tb_fetch = mysqli_fetch_assoc(select($tb_query, $values, 'siss'));


                if (($room_data['quantity'] - $tb_fetch['total_bookings']) == 0) {
                   continue;
                }  
            }

             // get facilities of room

             $fac_count=0;

             $fac_q = mysqli_query($con, "SELECT f.name ,f.id FROM `facilities` f
             INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id
             WHERE rfac.room_id = '$room_data[id]' ");

            $facilities_data = "";
            if ($fac_q) { // Kiểm tra xem truy vấn có thành công không
                while ($fac_row = mysqli_fetch_assoc($fac_q)) {

                    if (in_array($fac_row['id'], $facility_list['facilities'])) {
                        $fac_count++;
                    }

                    $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>$fac_row[name]</span>";
                }
            }

            if (count($facility_list['facilities']) != $fac_count) {
                continue;
            }


            // get features of room
            $fea_q = mysqli_query($con, "SELECT f.name FROM `features` f
                    INNER JOIN `room_features` rfea ON f.id = rfea.features_id
                    WHERE rfea.room_id = '$room_data[id]' ");

            $features_data = "";
            while ($fea_row = mysqli_fetch_assoc($fea_q)) {
                $features_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>$fea_row[name]</span>";
            }

           

            // get thumbnail of image
            $room_thumb = ROOMS_IMG_PATH . "thumb.png";
            $thumb_q = mysqli_query($con, "SELECT image FROM `room_images`
                                            WHERE `room_id` = '$room_data[id]'
                                            AND `thumb` = '1' ");

            if (mysqli_num_rows($thumb_q) > 0) {
                $thumb_res = mysqli_fetch_assoc($thumb_q);
                $room_thumb = ROOMS_IMG_PATH . $thumb_res['image'];
            }
            
            // Lấy thêm 2 ảnh khác nếu có
            $other_img_q = mysqli_query($con, "SELECT image FROM `room_images`
                                            WHERE `room_id` = '$room_data[id]'
                                            AND `thumb` = '0'
                                            LIMIT 2");
            
            $other_imgs = array();
            if (mysqli_num_rows($other_img_q) > 0) {
                while($other_img = mysqli_fetch_assoc($other_img_q)) {
                    $other_imgs[] = ROOMS_IMG_PATH . $other_img['image'];
                }
            }

            $book_btn = "";

            if (!$settings_r['shutdown']) {
                $login = 0;
                if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
                    $login = 1;
                }
                $book_btn = "<button onclick='checkLoginToBook($login,$room_data[id])' class='btn btn-book w-100 mb-2'><i class='bi bi-calendar-check me-1'></i>Đặt Ngay</button>";
            }
            // print romm
        
            $output .= "
                <div class='room-card' style='height: auto'>
                    <div class='row g-0'>
                        <div class='col-md-3'>
                            <div class='room-gallery'>
                                <div class='room-card-img main-img'>
                                    <span class='room-badge " . ($room_data['id'] % 3 == 0 ? 'hot' : ($room_data['id'] % 3 == 1 ? 'new' : 'sale')) . "'>" . 
                                    ($room_data['id'] % 3 == 0 ? '<i class="bi bi-fire"></i> Hot' : ($room_data['id'] % 3 == 1 ? '<i class="bi bi-stars"></i> New' : '<i class="bi bi-tag-fill"></i> Sale')) . "</span>
                                    <img src='$room_thumb' class='img-fluid' alt='$room_data[name]'>
                                    <div class='room-overlay'>
                                        <h5 class='text-white'>$room_data[name]</h5>
                                    </div>
                                </div>";
            
            if(count($other_imgs) > 0) {
                $output .= "<div class='room-small-imgs'>";
                foreach($other_imgs as $img) {
                    $output .= "<div class='room-small-img'><img src='$img' alt='$room_data[name]'></div>";
                }
                $output .= "</div>";
            }
            
            $output .= "
                            </div>
                        </div>
                        <div class='col-md-6 p-3'>
                            <h4 class='mb-2 fw-bold'>$room_data[name]</h4>
                            
                            <div class='d-flex flex-wrap gap-2'>
                                <div class='room-features'>
                                    <h6><i class='bi bi-star me-1'></i>Tính Năng</h6>
                                    <div>
                                        $features_data
                                    </div>
                                </div>
                                
                                <div class='room-features'>
                                    <h6><i class='bi bi-house-gear me-1'></i>Tiện Nghi</h6>
                                    <div>
                                        $facilities_data
                                    </div>
                                </div>
                                
                                <div class='room-features'>
                                    <h6><i class='bi bi-people'></i>Khách</h6>
                                    <div>
                                        <span class='feature-badge'>
                                            <i class='bi bi-person'></i>$room_data[adult] Người lớn
                                        </span>
                                        <span class='feature-badge'>
                                            <i class='bi bi-person-heart'></i>$room_data[children] Trẻ em
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-3 p-3 d-flex flex-column align-items-center justify-content-center border-start'>
                            <div class='room-price'>
                                " . number_format($room_data['price'], 0, ',', '.') . " VND
                            </div>
                            <p class='text-muted small mb-2'>mỗi đêm</p>
                            $book_btn
                            <a href='room_details.php?id=$room_data[id]' class='btn btn-details w-100 mt-2'><i class='bi bi-info-circle me-1'></i>Chi Tiết</a>
                        </div>
                    </div>
                </div>
            ";
            $count_rooms++;
        }

        if ($count_rooms > 0) {
            echo $output;
        } else {
            echo "<h3 class='text-center text-danger'>Không có phòng để hiển thị!</h3>";
        }
    }

?>