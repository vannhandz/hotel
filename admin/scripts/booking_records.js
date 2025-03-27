let currentFilter = 'all';

function get_bookings(search = '', page = 1) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/booking_records.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        let data = JSON.parse(this.responseText);

        // Update table data and pagination
        document.getElementById('table_data').innerHTML = data.table_data;
        document.getElementById('table-pagination').innerHTML = data.pagination;
        
        // Check if we have empty results
        if (data.table_data.includes('Không tìm thấy dữ liệu!')) {
            document.querySelector('.booking-empty-state').classList.remove('d-none');
            document.querySelector('.booking-table-container').classList.add('d-none');
        } else {
            document.querySelector('.booking-empty-state').classList.add('d-none');
            document.querySelector('.booking-table-container').classList.remove('d-none');
        }
        
        // Format elements after loading data
        setTimeout(() => {
            formatBookingElements();
            
            // Apply current filter if not 'all'
            if (currentFilter !== 'all') {
                filterBookings(currentFilter, false);
            }
        }, 100);
    };

    xhr.send('get_bookings&search=' + search + '&page=' + page);
}

function change_page(page) {
    get_bookings(document.getElementById('search_input').value, page);
}

function download(id) {
    window.location.href = 'generate_pdf.php?gen_pdf&id='+id;
}

function formatBookingElements() {
    // Định dạng trạng thái đặt phòng
    document.querySelectorAll('[data-booking-status]').forEach(elem => {
        let status = elem.getAttribute('data-booking-status');
        elem.classList.add('booking-status');
        if(status === 'booked') {
            elem.classList.add('booked');
        } else if(status === 'cancelled') {
            elem.classList.add('cancelled');
        } else if(status === 'payment-pending') {
            elem.classList.add('payment-pending');
        } else if(status === 'refunded') {
            elem.classList.add('refunded');
        }
    });
    
    // Định dạng nút download
    document.querySelectorAll('.download-pdf').forEach(elem => {
        elem.classList.add('download-btn');
    });
    
    // Định dạng phân trang
    document.querySelectorAll('#table-pagination .page-item').forEach(elem => {
        if(elem.classList.contains('active')) {
            elem.querySelector('.page-link').classList.add('active');
        }
    });
    
    // Thêm màu sắc xen kẽ cho hàng
    document.querySelectorAll('#table_data tr').forEach((row, index) => {
        if (index % 2 === 1) {
            row.style.backgroundColor = 'rgba(0, 0, 0, 0.015)';
        }
    });
}

function filterBookings(filter, updateButton = true) {
    currentFilter = filter;
    
    // Chuyển trạng thái active button nếu được yêu cầu
    if (updateButton) {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`.filter-btn[onclick*="'${filter}'"]`).classList.add('active');
    }
    
    // Hiển thị/ẩn hàng dựa trên bộ lọc
    if (filter === 'all') {
        document.querySelectorAll('#table_data tr').forEach(row => {
            row.style.display = '';
        });
    } else {
        document.querySelectorAll('#table_data tr').forEach(row => {
            const statusElem = row.querySelector('[data-booking-status]');
            if (statusElem && statusElem.getAttribute('data-booking-status') === filter) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Kiểm tra nếu không có hàng nào hiển thị
    let visibleRows = 0;
    document.querySelectorAll('#table_data tr').forEach(row => {
        if (row.style.display !== 'none') {
            visibleRows++;
        }
    });
    
    if (visibleRows === 0) {
        document.querySelector('.booking-empty-state').classList.remove('d-none');
        document.querySelector('.booking-table-container').classList.add('d-none');
    } else {
        document.querySelector('.booking-empty-state').classList.add('d-none');
        document.querySelector('.booking-table-container').classList.remove('d-none');
    }
}

window.onload = function() {
    get_bookings(); // Load bookings khi trang web được tải
}
