function get_bookings(search=''){
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/refund_bookings.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function(){
        document.getElementById('table_data').innerHTML = this.responseText;
    }

    xhr.send('get_bookings&search='+search);
}



function refund_booking(id)
{
    if(confirm("Hoàn tiền cho phòng này?"))
    {
        let data = new FormData();
        data.append('booking_id', id);
        data.append('refund_booking', '');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/refund_bookings.php", true);

        xhr.onload = function()
        {
            if(this.responseText == 1) {
                alert('success', 'Hoàn tiền thành công');
                get_bookings();
            }
            else{
                alert('error', 'Máy chủ ngừng hoạt động!');
            }
        }
        xhr.send(data);
    }
}





window.onload = function () {
    get_bookings();
}