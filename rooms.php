<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/link.php') ?>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <title><?php echo $settings_r['site_title'] ?> - Phòng</title>
</head>

<body class="bg-light">

    <?php 
        require('inc/header.php') ;
        
        $checkin_default="";
        $checkout_default="";
        $adult_default="";
        $children_default="";

        if(isset($_GET['check_availability']))
        {
            $frm_data = filteration($_GET);

            $checkin_default = $frm_data['checkin'];
            $checkout_default = $frm_data['checkout'];
            $adult_default = $frm_data['adult'];
            $children_default = $frm_data['children'];
        }
    ?>

    <!-- Banner Section -->
    <div class="room-banner">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="text-white fw-bold mb-3">Trải Nghiệm Phòng Sang Trọng</h1>
                    <p class="text-white lead mb-4">Khám phá không gian nghỉ dưỡng tuyệt vời với đầy đủ tiện nghi hiện đại</p>
                    <div class="banner-features">
                        <span><i class="bi bi-check2-circle"></i> Phòng đẹp</span>
                        <span><i class="bi bi-star-fill"></i> Dịch vụ 5 sao</span>
                        <span><i class="bi bi-shield-check"></i> An toàn</span>
                        <span><i class="bi bi-heart-fill"></i> Tiện nghi</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="wave-shape"></div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Filters Section -->
            <div class="col-lg-3 col-md-12 mb-4">
                <div class="filter-card">
                    <div class="filter-card-header">
                        <h5><i class="bi bi-funnel me-2"></i>Bộ Lọc Tìm Kiếm</h5>
                    </div>
                    <div class="filter-card-body">
                        <!-- Check Availability Filter -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0"><i class="bi bi-calendar-check me-2"></i>Kiểm Tra Phòng Trống</h6>
                                <button id="chk_avail_btn" onclick="chk_avail_clear()" class="filter-btn d-none">
                                    <i class="bi bi-x-circle me-1"></i>Xóa
                                </button>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Check-in</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                    <input type="date" class="form-control shadow-none" value="<?php echo $checkin_default ?>" id="checkin" onchange="chk_avail_filter()">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Check-out</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar3-week"></i></span>
                                    <input type="date" class="form-control shadow-none" value="<?php echo $checkout_default ?>" id="checkout" onchange="chk_avail_filter()">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        
                        <!-- Guests Filter -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0"><i class="bi bi-people me-2"></i>Khách Hàng</h6>
                                <button id="guests_btn" onclick="guests_clear()" class="filter-btn d-none">
                                    <i class="bi bi-x-circle me-1"></i>Xóa
                                </button>
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label">Người Lớn</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="number" min="1" id="adults" value="<?php echo $adult_default ?>" oninput="guests_filter()" class="form-control shadow-none">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Trẻ Em</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person-heart"></i></span>
                                        <input type="number" min="0" id="children" value="<?php echo $children_default ?>" oninput="guests_filter()" class="form-control shadow-none">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        
                        <!-- Facilities Filter -->
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0"><i class="bi bi-check-circle me-2"></i>Tiện Nghi</h6>
                                <button id="facilities_btn" onclick="facilities_clear()" class="filter-btn d-none">
                                    <i class="bi bi-x-circle me-1"></i>Xóa
                                </button>
                            </div>
                            <div class="facilities-list">
                                <?php
                                    $facilities_q = selectAll('facilities');
                                    while($row = mysqli_fetch_assoc($facilities_q))
                                    {
                                        echo<<<facilities
                                            <div class="form-check mb-2">
                                                <input type="checkbox" onclick="fetch_rooms()" name="facilities" value="$row[id]" class="form-check-input shadow-none" id="$row[id]">
                                                <label class="form-check-label" for="$row[id]">$row[name]</label>
                                            </div>
                                        facilities;
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rooms Display Section -->
            <div class="col-lg-9 col-md-12" id="rooms-data">
                <!-- Rooms will be displayed here via AJAX -->
            </div>
        </div>
    </div>

    <script>
        let room_data = document.getElementById('rooms-data');
        let checkin = document.getElementById('checkin');
        let checkout = document.getElementById('checkout');
        let chk_avail_btn = document.getElementById('chk_avail_btn');

        let adults = document.getElementById('adults');
        let children = document.getElementById('children');
        let guests_btn = document.getElementById('guests_btn');
        let facilities_btn = document.getElementById('facilities_btn');

        function fetch_rooms()
        {
            let chk_avail = JSON.stringify({
                checkin: checkin.value,
                checkout: checkout.value
            });

            let guests = JSON.stringify({
                adults: adults.value,
                children: children.value
            });

            let facility_list = {"facilities":[]};

            let get_facilities = document.querySelectorAll('[name="facilities"]:checked');
            if(get_facilities.length>0)
            {
                get_facilities.forEach((facility)=>{
                    facility_list.facilities.push(facility.value);
                });
                facilities_btn.classList.remove('d-none');
            } else {
                facilities_btn.classList.add('d-none');
            }

            facility_list = JSON.stringify(facility_list);

            let xhr = new XMLHttpRequest();
            xhr.open("GET", "ajax/rooms.php?fetch_rooms&chk_avail="+chk_avail+"&guests="+guests+"&facility_list="+facility_list, true);

            xhr.onprogress = function(){
                room_data.innerHTML = `
                    <div class="d-flex justify-content-center align-items-center" style="height: 40vh;">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `;
            }
            
            xhr.onload = function(){
                room_data.innerHTML = this.responseText;
            }

            xhr.send();
        }

        function chk_avail_filter() {
            if (checkin.value != '' && checkout.value != '') {
                fetch_rooms();
                chk_avail_btn.classList.remove('d-none');
            }
        }

        function chk_avail_clear() {
            checkin.value = '';
            checkout.value = '';
            chk_avail_btn.classList.add('d-none');
            fetch_rooms();
        }

        function guests_filter() {
            if(adults.value > 0 || children.value > 0) {
                fetch_rooms();
                guests_btn.classList.remove('d-none');
            }
        }

        function guests_clear() {
            adults.value = '';
            children.value = '';
            guests_btn.classList.add('d-none');
            fetch_rooms();
        }
                        
        function facilities_clear() {
            let get_facilities = document.querySelectorAll('[name="facilities"]:checked');

            get_facilities.forEach((facility) => {
                facility.checked = false;
            });

            facilities_btn.classList.add('d-none');
            fetch_rooms();
        }

        window.onload = function() {
            fetch_rooms();
        };
    </script>

    <?php require('inc/footer.php') ?>

</body>
</html>