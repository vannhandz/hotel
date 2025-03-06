function get_bookings(search = '', page = 1) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/booking_records.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        let data = JSON.parse(this.responseText);

        // Nếu không có dữ liệu, hiển thị thông báo
        if (data.table_data && data.table_data.trim() === "Không tìm thấy dữ liệu!") {
            document.getElementById('table_data').innerHTML = data.table_data;
        } else {
            document.getElementById('table_data').innerHTML = data.table_data;
        }

        document.getElementById('table-pagination').innerHTML = data.pagination;
    };

    xhr.send('get_bookings&search=' + search + '&page=' + page);
}

function change_page(page) {
    get_bookings(document.getElementById('search_input').value, page);
}

function download(id) {
    window.location.href = 'generate_pdf.php?gen_pdf&id='+id;
}
window.onload = function() {
    get_bookings(); // Load bookings khi trang web được tải
}
