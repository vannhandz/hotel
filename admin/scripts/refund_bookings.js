function get_bookings(search=''){
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/refund_bookings.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function(){
        document.getElementById('table_data').innerHTML = this.responseText;
        
        // Format refund elements after data loads
        setTimeout(() => {
            formatRefundElements();
        }, 100);
    }

    xhr.send('get_bookings&search='+search);
}

function formatRefundElements() {
    // Add alternating row colors for better readability
    const rows = document.querySelectorAll('#table_data tr');
    rows.forEach((row, index) => {
        if (index % 2 === 1) {
            row.style.backgroundColor = 'rgba(0, 0, 0, 0.015)';
        }
    });
    
    // Add hover effect to all refund buttons
    document.querySelectorAll('.refund-btn').forEach(btn => {
        btn.classList.add('refund-action-btn');
        btn.classList.add('refund-approve-btn');
        
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

function refund_booking(id)
{
    if(confirm("Xác nhận hoàn tiền cho đơn đặt phòng này?"))
    {
        let data = new FormData();
        data.append('booking_id', id);
        data.append('refund_booking', '');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/refund_bookings.php", true);

        xhr.onload = function()
        {
            if(this.responseText == 1) {
                alert('success', 'Đã hoàn tiền thành công');
                get_bookings();
            }
            else{
                alert('error', 'Hoàn tiền thất bại! Vui lòng thử lại sau.');
            }
        }
        xhr.send(data);
    }
}

window.onload = function () {
    get_bookings();
}