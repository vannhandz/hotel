// Khởi tạo form assign room khi DOM đã sẵn sàng
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo biến form toàn cục
    window.assign_room_form = document.getElementById('assign_room_form');
    
    // Thêm event listener cho form nếu nó tồn tại
    if (window.assign_room_form) {
        window.assign_room_form.addEventListener('submit', handleAssignRoomSubmit);
    }
    
    // Tải dữ liệu ban đầu
    get_bookings('');
});

// Lấy danh sách đặt phòng
function get_bookings(search='') {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/new_bookings.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (this.status === 200) {
            let tableData = document.getElementById('table_data');
            if (tableData) {
                tableData.innerHTML = this.responseText;
                
                // Format các phần tử sau khi tải dữ liệu
                setTimeout(() => {
                    formatBookingElements();
                }, 100);
            }
        } else {
            console.error("Lỗi khi tải dữ liệu:", this.status);
        }
    };

    xhr.onerror = function() {
        console.error("Lỗi kết nối khi tải dữ liệu đặt phòng");
    };

    xhr.send('get_bookings&search='+search);
}

// Định dạng các phần tử trong bảng
function formatBookingElements() {
    // Màu nền xen kẽ cho các hàng
    const rows = document.querySelectorAll('#table_data tr');
    rows.forEach((row, index) => {
        if (index % 2 === 1) {
            row.style.backgroundColor = 'rgba(0, 0, 0, 0.015)';
        }
    });
    
    // Hiệu ứng hover cho các nút
    document.querySelectorAll('.booking-action').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

// Hàm gán booking ID vào form khi người dùng click vào nút chỉ định phòng
function assign_room(booking_id) {
    if (!window.assign_room_form) {
        window.assign_room_form = document.getElementById('assign_room_form');
    }
    
    if (window.assign_room_form) {
        window.assign_room_form.elements['booking_id'].value = booking_id;
    } else {
        console.error("Không tìm thấy form assign_room_form");
    }
}

// Xử lý sự kiện submit của form chỉ định phòng
function handleAssignRoomSubmit(e) {
    e.preventDefault();
  
    let data = new FormData(this);
    data.append('assign_room', '');
  
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/new_bookings.php", true);
  
    xhr.onload = function() {
        // Log phản hồi từ server để debug
        console.log("Server response:", this.responseText);
      
        // Đóng modal
        try {
            var myModal = document.getElementById('assign-room');
            if (myModal) {
                var modal = bootstrap.Modal.getInstance(myModal);
                if (modal) {
                    modal.hide();
                }
            }
        } catch (err) {
            console.error("Lỗi khi đóng modal:", err);
        }
      
        // Kiểm tra phản hồi
        let response = this.responseText.trim();
        
        if (response === '1') {
            // Thay thế alert() bằng hàm alert tùy chỉnh
            alert('success', 'Đã chỉ định phòng thành công!');
            if (window.assign_room_form) {
                window.assign_room_form.reset();
            }
            get_bookings('');
        } else {
            // Thay thế alert() bằng hàm alert tùy chỉnh
            alert('error', 'Chỉ định phòng thất bại! Vui lòng thử lại.');
        }
    };

    xhr.onerror = function() {
        // Thay thế alert() bằng hàm alert tùy chỉnh
        alert('error', 'Đã xảy ra lỗi kết nối! Vui lòng thử lại sau.');
    };

    xhr.send(data);
}

// Hàm hủy đặt phòng
function cancel_booking(booking_id) {
    if (confirm("Bạn có chắc chắn muốn hủy đơn đặt phòng này không?")) {
        let data = new FormData();
        data.append('booking_id', booking_id);
        data.append('cancel_booking', '');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/new_bookings.php", true);

        xhr.onload = function() {
            // Log phản hồi từ server để debug
            console.log("Server response (cancel):", this.responseText);
            
            let response = this.responseText.trim();
            
            if (response === '1') {
                // Thay thế alert() bằng hàm alert tùy chỉnh
                alert('success', 'Đã hủy đơn đặt phòng thành công!');
                get_bookings('');
            } else {
                // Thay thế alert() bằng hàm alert tùy chỉnh
                alert('error', 'Hủy đơn đặt phòng thất bại! Vui lòng thử lại.');
            }
        };

        xhr.onerror = function() {
            // Thay thế alert() bằng hàm alert tùy chỉnh
            alert('error', 'Đã xảy ra lỗi kết nối! Vui lòng thử lại sau.');
        };

        xhr.send(data);
    }
}

// Hàm xác nhận thanh toán
function confirm_payment(booking_id) {
    if (confirm("Xác nhận đã nhận được thanh toán cho đơn hàng này?")) {
        let data = new FormData();
        data.append('booking_id', booking_id);
        data.append('confirm_payment', '');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/new_bookings.php", true);

        xhr.onload = function() {
            // Log phản hồi từ server để debug
            console.log("Server response (payment):", this.responseText);
            
            let response = this.responseText.trim();
            
            if (response === '1') {
                // Thay thế alert() bằng hàm alert tùy chỉnh
                alert('success', 'Xác nhận thanh toán thành công!');
                get_bookings('');
            } else {
                // Thay thế alert() bằng hàm alert tùy chỉnh
                alert('error', 'Xác nhận thanh toán thất bại! Vui lòng thử lại.');
            }
        };

        xhr.onerror = function() {
            // Thay thế alert() bằng hàm alert tùy chỉnh
            alert('error', 'Đã xảy ra lỗi kết nối! Vui lòng thử lại sau.');
        };

        xhr.send(data);
    }
}