# Hệ Thống Đặt Phòng Khách Sạn



Một hệ thống quản lý đặt phòng khách sạn toàn diện được xây dựng bằng PHP và MySQL, cho phép người dùng duyệt, đặt và thanh toán phòng khách sạn trực tuyến.

## 📌 Repository

- **GitHub**: [https://github.com/vannhandz/hotel.git](https://github.com/vannhandz/hotel.git)
- **Ngôn ngữ**: PHP (65.0%), CSS (27.4%), JavaScript (6.4%), Hack (1.2%)

## ✨ Tính Năng

- **Quản Lý Người Dùng**: Đăng ký, đăng nhập, xác minh email và khôi phục mật khẩu
- **Duyệt Phòng**: Xem các loại phòng, chi tiết, đặc điểm và tiện nghi
- **Quản Lý Đặt Phòng**: Kiểm tra tình trạng phòng trống, đặt phòng và xem lịch sử đặt phòng
- **Nhiều Phương Thức Thanh Toán**: Tích hợp VNPay và PayPal
- **Hệ Thống Chat**: Hỗ trợ khách hàng qua hệ thống chat tích hợp
- **Đánh Giá và Nhận Xét**: Người dùng có thể đánh giá và nhận xét về kỳ nghỉ của họ
- **Thiết Kế Responsive**: Giao diện thân thiện với thiết bị di động
- **Bảng Quản Trị Admin**: Hệ thống quản lý backend đầy đủ

## 🛠️ Công Nghệ Sử Dụng

- **Backend**: PHP 7.4+, MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Xử Lý Thanh Toán**: 
  - VNPay API cho thanh toán nội địa
  - PayPal REST API SDK cho thanh toán quốc tế
- **Tạo PDF**: Thư viện mPDF 8.2+ để xuất hóa đơn và báo cáo
- **Dịch Vụ Email**: 
  - SMTP (Simple Mail Transfer Protocol) để gửi email thông báo
  - Hỗ trợ kết nối SMTP với Gmail, Outlook hoặc các dịch vụ email khác
  - Sử dụng để gửi xác minh đăng ký, khôi phục mật khẩu 

## 🚀 Cài Đặt

1. Clone repository:
   ```bash
   git clone https://github.com/vannhandz/hotel.git
   ```

2. Cài đặt các gói phụ thuộc qua Composer:
   ```bash
   composer install
   ```

3. Import cơ sở dữ liệu:
   - Tạo một database mới trong MySQL
   - Import file `hotel.sql` vào database đó:
   ```bash
   mysql -u username -p your_database_name < hotel.sql
   ```
   - Hoặc sử dụng phpMyAdmin để import file SQL


## 📂 Cấu Trúc Dự Án

- `/admin`: Bảng quản trị cho việc quản lý phòng, đặt phòng, người dùng, v.v.
- `/ajax`: Xử lý AJAX cho các yêu cầu bất đồng bộ
- `/css`: Các tệp stylesheet
- `/inc`: Các tệp include và cấu hình
- `/js`: Các tệp JavaScript
- `/images`: Thư mục chứa hình ảnh
- `/vendor`: Các thư viện Composer

## 🔍 Giải Thích Tính Năng Chính

### Quy Trình Đặt Phòng

1. Người dùng kiểm tra tình trạng phòng trống bằng cách chọn ngày và số lượng khách
2. Các phòng có sẵn được hiển thị dựa trên tiêu chí
3. Người dùng chọn phòng và tiến hành đặt
4. Thanh toán được xử lý qua VNPay hoặc PayPal
5. Xác nhận được gửi qua email với chi tiết đơn đặt phòng

### Khôi Phục Mật Khẩu

Hệ thống bao gồm quy trình khôi phục mật khẩu an toàn:

1. Người dùng yêu cầu đặt lại mật khẩu
2. Một mã thông báo có thời hạn (30 phút) được tạo và gửi qua email
3. Người dùng nhấp vào liên kết và đặt mật khẩu mới
4. Hệ thống xác thực tính hợp lệ của token trước khi cho phép đặt lại mật khẩu

### Khả Năng Quản Trị

- **Quản lý phòng**: Thêm, sửa, xóa phòng và quản lý các tiện nghi
- **Quản lý đặt phòng**: Xem, xác nhận, hủy đơn đặt phòng
- **Báo cáo doanh thu**: Xem thống kê doanh thu theo ngày, tháng, năm
- **Quản lý người dùng**: Xem và quản lý tài khoản người dùng
- **Cấu hình trang web**: Tùy chỉnh thông tin và hình ảnh hiển thị trên trang web

## 🚦 Trạng thái dự án

Dự án đang được phát triển và hoàn thiện. Các tính năng mới sẽ được cập nhật thường xuyên.

## 👨‍💻 Tác Giả

Phát triển bởi Vân Nhân

