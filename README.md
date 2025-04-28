# Hệ Thống Đặt Phòng Khách Sạn

Một hệ thống quản lý đặt phòng khách sạn toàn diện được xây dựng bằng PHP và MySQL, cho phép người dùng duyệt, đặt và thanh toán phòng khách sạn trực tuyến.

## Tính Năng

- **Quản Lý Người Dùng**: Đăng ký, đăng nhập, xác minh email và khôi phục mật khẩu
- **Duyệt Phòng**: Xem các loại phòng, chi tiết, đặc điểm và tiện nghi
- **Quản Lý Đặt Phòng**: Kiểm tra tình trạng phòng trống, đặt phòng và xem lịch sử đặt phòng
- **Nhiều Phương Thức Thanh Toán**: Tích hợp VNPay và PayPal
- **Hệ Thống Chat**: Hỗ trợ khách hàng qua hệ thống chat tích hợp
- **Đánh Giá và Nhận Xét**: Người dùng có thể đánh giá và nhận xét về kỳ nghỉ của họ
- **Thiết Kế Responsive**: Giao diện thân thiện với thiết bị di động
- **Bảng Quản Trị Admin**: Hệ thống quản lý backend đầy đủ

## Công Nghệ Sử Dụng

- **Backend**: PHP, MySQL
- **Frontend**: HTML, CSS, JavaScript, Bootstrap
- **Xử Lý Thanh Toán**: VNPay, PayPal API
- **Tạo PDF**: Thư viện mPDF
- **Dịch Vụ Email**: SMTP cho thông báo qua email

## Cài Đặt

1. Clone repository
2. Import cấu trúc cơ sở dữ liệu
3. Cấu hình kết nối cơ sở dữ liệu trong `inc/db_config.php`
4. Thiết lập cấu hình email cho chức năng khôi phục mật khẩu
5. Cấu hình thông tin cổng thanh toán

## Cấu Hình

Cập nhật các tệp sau với cài đặt cụ thể của bạn:

- `inc/db_config.php`: Chi tiết kết nối cơ sở dữ liệu
- `inc/constants.php`: URL trang web và các hằng số khác
- Thông tin đăng nhập cổng thanh toán trong các tệp liên quan

## Cấu Trúc Dự Án

- `/admin`: Bảng quản trị cho việc quản lý phòng, đặt phòng, người dùng, v.v.
- `/ajax`: Xử lý AJAX cho các yêu cầu bất đồng bộ
- `/css`: Các tệp stylesheet
- `/inc`: Các tệp include và cấu hình
- `/js`: Các tệp JavaScript
- `/images`: Thư mục chứa hình ảnh
- `/vendor`: Các thư viện Composer

## Giải Thích Tính Năng Chính

### Quy Trình Đặt Phòng
1. Người dùng kiểm tra tình trạng phòng trống bằng cách chọn ngày và số lượng khách
2. Các phòng có sẵn được hiển thị dựa trên tiêu chí
3. Người dùng chọn phòng và tiến hành đặt
4. Thanh toán được xử lý qua VNPay hoặc PayPal
5. Xác nhận được gửi qua email

### Khôi Phục Mật Khẩu
Hệ thống bao gồm quy trình khôi phục mật khẩu an toàn:
1. Người dùng yêu cầu đặt lại mật khẩu
2. Một mã thông báo có thời hạn được tạo và gửi qua email
3. Người dùng nhấp vào liên kết và đặt mật khẩu mới

### Khả Năng Quản Trị
- Quản lý phòng, tính năng và tiện nghi
- Xử lý đặt phòng và thanh toán
- Xem báo cáo và thống kê
- Quản lý tài khoản người dùng

## Tác Giả

Phát triển bởi Vân Nhàn 