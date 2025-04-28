-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 28, 2025 lúc 04:56 AM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `hotel`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin_cred`
--

CREATE TABLE `admin_cred` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(150) NOT NULL,
  `admin_pass` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin_cred`
--

INSERT INTO `admin_cred` (`admin_id`, `admin_name`, `admin_pass`) VALUES
(1, 'admin123', 'admin123');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking_order`
--

CREATE TABLE `booking_order` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `number_of_nights` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `total_amount` decimal(15,0) NOT NULL,
  `payment_id` varchar(100) NOT NULL,
  `payer_id` varchar(100) NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `invoice_id` varchar(100) NOT NULL,
  `booking_date` datetime NOT NULL DEFAULT current_timestamp(),
  `booking_status` varchar(100) NOT NULL DEFAULT 'booked',
  `room_no` varchar(100) DEFAULT NULL,
  `arrival` int(11) NOT NULL DEFAULT 0,
  `refund` int(11) DEFAULT NULL,
  `rate_review` int(11) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `booking_order`
--

INSERT INTO `booking_order` (`booking_id`, `user_id`, `room_id`, `room_name`, `check_in`, `check_out`, `number_of_nights`, `price`, `total_amount`, `payment_id`, `payer_id`, `transaction_id`, `invoice_id`, `booking_date`, `booking_status`, `room_no`, `arrival`, `refund`, `rate_review`, `payment_method`, `payment_date`) VALUES
(114, 7, 19, 'Phòng Đôi', '2025-04-01', '2025-04-11', 10, 650000, 6500000, 'PAYID-M7WABMQ753506995V835072L', 'SZL47H9J7UEJY', '0HN0092221572191C', '67ec00ae35657', '2025-04-01 22:05:47', 'booked', '8', 1, NULL, 1, 'paypal', NULL),
(115, 7, 21, 'Phòng Trăng Mật', '2025-04-01', '2025-04-11', 10, 1399000, 13990000, 'PAYID-M7WAHFY2J25546533792792G', 'SZL47H9J7UEJY', '3P6210755S194714A', '67ec039459820', '2025-04-01 22:17:51', 'booked', '6', 1, NULL, 1, 'paypal', NULL),
(116, 5, 13, 'Phòng Sang Trọng', '2025-04-02', '2025-04-06', 4, 850000, 3400000, '', '', '14886635', 'ORD_e178e6_1743582611', '2025-04-02 00:00:00', 'booked', '5', 1, NULL, 0, 'vnpay', NULL),
(117, 5, 14, 'Phòng Đặc Biệt', '2025-04-02', '2025-04-05', 3, 1500000, 4500000, 'PAYID-M7WPL3A83R05296MT710493V', 'SZL47H9J7UEJY', '3B398087EU964354K', '67ecf5e9a5d85', '2025-04-02 15:32:04', 'booked', '7', 1, NULL, 0, 'paypal', NULL),
(118, 5, 21, 'Phòng Trăng Mật', '2025-04-02', '2025-04-06', 4, 1399000, 5596000, '', '', '14886639', 'ORD_5df700_1743582830', '2025-04-02 00:00:00', 'booked', '1', 1, NULL, 0, 'vnpay', NULL),
(119, 5, 19, 'Phòng Đôi', '2025-04-02', '2025-04-06', 4, 650000, 2600000, '', '', '14886647', 'ORD_49aa02_1743582957', '2025-04-02 00:00:00', 'booked', '3', 1, NULL, 0, 'vnpay', NULL),
(120, 5, 19, 'Phòng Đôi', '2025-04-02', '2025-04-04', 2, 650000, 1300000, 'PAYID-M7WSEUY0TH89453AA031242S', 'SZL47H9J7UEJY', '0WC10683ED1203147', '67ed224f1c040', '2025-04-02 18:41:26', 'booked', '4', 1, NULL, 1, 'paypal', NULL),
(121, 5, 19, 'Phòng Đôi', '2025-04-02', '2025-04-04', 2, 650000, 1300000, '', '', '14887177', 'ORD_5e9087_1743594110', '2025-04-02 00:00:00', 'booked', '2', 1, NULL, 1, 'vnpay', NULL),
(122, 5, 19, 'Phòng Đôi', '2025-04-02', '2025-04-04', 2, 650000, 1300000, '', '', '14887181', 'ORD_20c3a4_1743594332', '2025-04-02 00:00:00', 'booked', '3', 1, NULL, 1, 'vnpay', NULL),
(123, 5, 19, 'Phòng Đôi', '2025-04-02', '2025-04-03', 1, 650000, 650000, '', '', '14887191', 'ORD_da9a6a_1743594693', '2025-04-02 00:00:00', 'booked', '1', 1, NULL, 1, 'vnpay', NULL),
(124, 7, 20, 'Biệt Thự Biển', '2025-04-03', '2025-04-05', 2, 3250000, 6500000, '', '', '14889389', 'ORD_aec4c8_1743690437', '2025-04-03 00:00:00', 'booked', '5', 1, NULL, 1, 'vnpay', NULL),
(125, 31, 18, 'Phòng Cao Cấp', '2025-04-03', '2025-04-13', 10, 750000, 7500000, 'PAYID-M7XJW5I2TP91100RC271830B', 'SZL47H9J7UEJY', '32N42547LY799841R', '67ee9b71d555f', '2025-04-03 21:30:36', 'booked', '8', 1, NULL, 1, 'paypal', NULL),
(126, 31, 19, 'Phòng Đôi', '2025-04-04', '2025-04-06', 2, 650000, 1300000, 'PAYID-M7X5RAI57U79748RS915182S', 'SZL47H9J7UEJY', '5PP04753S2107133L', '67efd87d3f874', '2025-04-04 20:03:38', 'booked', '8', 1, NULL, 0, 'paypal', NULL),
(127, 31, 19, 'Phòng Đôi', '2025-04-11', '2025-04-13', 2, 650000, 1300000, 'PAYID-M74NKHA7A909882FF390205M', 'SZL47H9J7UEJY', '9VP28937M37695010', '67f8d51952537', '2025-04-11 15:39:13', 'refunded', NULL, 0, 1, NULL, 'paypal', NULL),
(128, 31, 13, 'Phòng Sang Trọng', '2025-04-25', '2025-04-26', 1, 850000, 850000, '', '', '14925931', 'ORD_19f3dc_1745555652', '2025-04-25 00:00:00', 'booked', 'b12', 1, NULL, 0, 'vnpay', NULL),
(129, 31, 13, 'Phòng Sang Trọng', '2025-04-25', '2025-04-26', 1, 850000, 850000, 'PAYID-NAFRQ7A31X65561UD740835F', 'SZL47H9J7UEJY', '2XC44932C9229062A', '680b187a5fa72', '2025-04-25 12:08:45', 'cancelled', NULL, 0, 0, NULL, 'paypal', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `carousel`
--

CREATE TABLE `carousel` (
  `sr_no` int(11) NOT NULL,
  `image` varchar(150) NOT NULL,
  `id_admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `carousel`
--

INSERT INTO `carousel` (`sr_no`, `image`, `id_admin`) VALUES
(5, 'IMG_77047.jpg', 1),
(8, 'IMG_49950.jpg', 1),
(9, 'IMG_65381.jpg', 1),
(10, 'IMG_91571.jpg', 1),
(11, 'IMG_14642.jpg', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contact_details`
--

CREATE TABLE `contact_details` (
  `id_contact` int(11) NOT NULL,
  `address` varchar(50) NOT NULL,
  `gmap` varchar(100) NOT NULL,
  `pn1` varchar(20) NOT NULL,
  `pn2` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fb` varchar(100) NOT NULL,
  `insta` varchar(100) NOT NULL,
  `tw` varchar(100) NOT NULL,
  `iframe` varchar(300) NOT NULL,
  `id_admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `contact_details`
--

INSERT INTO `contact_details` (`id_contact`, `address`, `gmap`, `pn1`, `pn2`, `email`, `fb`, `insta`, `tw`, `iframe`, `id_admin`) VALUES
(1, 'Hanami Hotel', 'https://maps.app.goo.gl/XbwfZjM4SxWmAtsHA', '762605901', '', 'HanamiHotel@gmail.com', 'https://www.facebook.com/', 'https://www.instagram.com/', 'https://www.x.com/', 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d72772.29619904184!2d108.24243700000001!3d16.048608!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3142176372388b9f:0x8b97363057db4165!2sHanami Hotel Danang!5e1!3m2!1svi!2sus!4v1737357823013!5m2!1svi!2sus', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `facilities`
--

CREATE TABLE `facilities` (
  `id` int(11) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `facilities`
--

INSERT INTO `facilities` (`id`, `icon`, `name`, `description`) VALUES
(6, 'IMG_88959.svg', 'Wifi', 'Truy cập internet tốc độ cao miễn phí trong toàn bộ khách sạn.'),
(8, 'IMG_51069.svg', 'Tivi', 'Tivi màn hình phẳng với nhiều kênh truyền hình cáp và vệ tinh.'),
(9, 'IMG_97865.svg', 'Máy điều hòa', 'Máy điều hòa nhiệt độ hiện đại, có thể điều chỉnh nhiệt độ theo ý muốn.'),
(10, 'IMG_80068.svg', 'Spa', 'Dịch vụ spa chuyên nghiệp với nhiều liệu pháp thư giãn và làm đẹp.'),
(11, 'IMG_83886.svg', 'Máy sưởi phòng', 'Máy sưởi phòng ấm áp, đảm bảo nhiệt độ thoải mái trong mùa đông.'),
(12, 'IMG_73572.svg', 'Bình nước nóng', 'Bình nước nóng cung cấp nước nóng liên tục cho phòng tắm.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `features`
--

CREATE TABLE `features` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `features`
--

INSERT INTO `features` (`id`, `name`) VALUES
(10, 'Khu vực ăn uống'),
(11, 'Phòng giặt ủi'),
(12, 'Nhà để xe'),
(13, 'Hồ bơi'),
(14, 'Phòng tập gym'),
(15, 'Khu vui chơi');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `rating_review`
--

CREATE TABLE `rating_review` (
  `sr_no` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` varchar(200) NOT NULL,
  `seen` int(11) NOT NULL DEFAULT 0,
  `datentime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `rating_review`
--

INSERT INTO `rating_review` (`sr_no`, `booking_id`, `room_id`, `user_id`, `rating`, `review`, `seen`, `datentime`) VALUES
(11, 114, 19, 7, 5, 'Phòng khách sạn sạch sẽ, tiện nghi hiện đại và có tầm nhìn đẹp. Nhân viên thân thiện và luôn sẵn sàng giúp đỡ. Tôi sẽ quay lại lần sau!', 0, '2025-04-03 21:23:00'),
(12, 115, 21, 7, 5, 'Chất lượng dịch vụ rất tốt, từ lễ tân đến nhân viên dọn phòng đều chuyên nghiệp. Giường êm ái, phòng rộng rãi và đầy đủ tiện nghi. Rất đáng giá tiền!', 0, '2025-04-03 21:23:14'),
(13, 123, 19, 5, 5, 'Tôi thích cách bài trí của phòng – hiện đại nhưng vẫn ấm cúng. Nhà tắm sạch sẽ, vòi sen nước mạnh, mọi thứ đều hoàn hảo. Sẽ giới thiệu cho bạn bè!', 0, '2025-04-03 21:24:51'),
(14, 122, 19, 5, 5, 'Tôi đã có một kỳ nghỉ tuyệt vời tại đây. Phòng thoáng mát, có ban công nhìn ra biển, bữa sáng ngon và đa dạng. Nhất định sẽ quay lại', 0, '2025-04-03 21:25:01'),
(15, 121, 19, 5, 5, 'Vị trí khách sạn thuận tiện, gần trung tâm và các điểm tham quan. Phòng yên tĩnh, giường ngủ thoải mái, nhân viên thân thiện. Rất đáng để trải nghiệm!', 0, '2025-04-03 21:25:10'),
(16, 120, 19, 5, 5, 'Mọi thứ đều hoàn hảo từ lúc check-in đến lúc check-out. Nhân viên nhiệt tình, phòng sạch đẹp, nội thất sang trọng. Rất đáng để trải nghiệm', 0, '2025-04-03 21:25:59'),
(17, 124, 20, 7, 5, 'Khách sạn nằm ở vị trí yên tĩnh, rất thích hợp để nghỉ ngơi. Phòng có cách âm tốt, không bị làm phiền. Giường cực kỳ thoải mái, ngủ rất ngon', 1, '2025-04-03 21:29:35'),
(18, 125, 18, 31, 5, 'Khách sạn ngay trung tâm, thuận tiện đi lại. Nhân viên luôn niềm nở, hỗ trợ nhiệt tình. Phòng được dọn dẹp hàng ngày, rất sạch sẽ và thơm tho', 1, '2025-04-03 21:31:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `area` varchar(150) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `adult` int(11) NOT NULL,
  `children` int(11) NOT NULL,
  `description` varchar(350) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `removed` int(11) NOT NULL DEFAULT 0,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `area`, `price`, `quantity`, `adult`, `children`, `description`, `status`, `removed`, `admin_id`) VALUES
(13, 'Phòng Sang Trọng', '40', 850000, 10, 4, 3, 'Phòng rộng rãi với view thành phố, giường king size, minibar và bàn làm việc. Phù hợp cho cặp đôi hoặc gia đình nhỏ.', 1, 0, 0),
(14, 'Phòng Đặc Biệt', '55', 1500000, 5, 2, 2, 'Phòng đặc biệt sang trọng với phòng khách riêng biệt, bồn tắm jacuzzi và ban công riêng. View biển tuyệt đẹp.', 1, 0, 0),
(15, 'Phòng Tiêu Chuẩn', '25', 599000, 15, 2, 1, 'Phòng tiêu chuẩn thoải mái với đầy đủ tiện nghi cơ bản, giường đôi và phòng tắm riêng.', 1, 0, 0),
(16, 'Phòng Gia Đình', '45', 1250000, 8, 4, 2, 'Phòng gia đình rộng rãi với 2 giường lớn, phù hợp cho gia đình 4-6 người, có khu vực sinh hoạt chung.', 1, 0, 0),
(17, 'Phòng Thương Gia', '40', 1200000, 6, 2, 1, 'Phòng hạng sang với không gian làm việc riêng, tầm nhìn panorama và quyền truy cập khu vực riêng.', 1, 0, 0),
(18, 'Phòng Cao Cấp', '30', 750000, 12, 2, 1, 'Phòng tiện nghi với không gian rộng hơn phòng tiêu chuẩn, có ban công riêng và tầm nhìn đẹp.', 1, 0, 0),
(19, 'Phòng Đôi', '28', 650000, 8, 2, 1, 'Phòng với hai giường đơn, phù hợp cho bạn bè hoặc đồng nghiệp, đầy đủ tiện nghi cơ bản.', 1, 0, 0),
(20, 'Biệt Thự Biển', '120', 3250000, 2, 6, 4, 'Biệt thự riêng biệt với 3 phòng ngủ, hồ bơi riêng, bếp và phòng khách rộng rãi, view biển tuyệt đẹp.', 1, 0, 0),
(21, 'Phòng Trăng Mật', '50', 1399000, 3, 2, 1, 'Phòng dành cho cặp đôi với không gian lãng mạn, bồn tắm hoa hồng, rượu vang chào mừng và view thành phố.', 1, 0, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_facilities`
--

CREATE TABLE `room_facilities` (
  `sr_no` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `facilities_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `room_facilities`
--

INSERT INTO `room_facilities` (`sr_no`, `room_id`, `facilities_id`) VALUES
(46, 4, 8),
(47, 4, 9),
(48, 4, 10),
(49, 5, 6),
(50, 5, 10),
(51, 5, 12),
(58, 12, 8),
(59, 12, 9),
(60, 12, 11),
(61, 12, 12),
(68, 3, 9),
(69, 3, 10),
(70, 3, 11),
(71, 3, 12),
(75, 14, 9),
(76, 14, 10),
(77, 14, 12),
(78, 15, 9),
(79, 15, 11),
(80, 15, 12),
(81, 16, 6),
(82, 16, 8),
(83, 16, 11),
(84, 16, 12),
(85, 17, 6),
(86, 17, 8),
(87, 17, 9),
(88, 17, 12),
(89, 18, 6),
(90, 18, 8),
(91, 18, 9),
(92, 18, 10),
(93, 18, 11),
(94, 19, 6),
(95, 19, 9),
(96, 19, 10),
(97, 19, 11),
(98, 20, 6),
(99, 20, 10),
(100, 20, 11),
(101, 20, 12),
(102, 21, 8),
(103, 21, 10),
(104, 21, 11),
(112, 13, 6),
(113, 13, 11),
(114, 13, 12);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_features`
--

CREATE TABLE `room_features` (
  `sr_no` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `features_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `room_features`
--

INSERT INTO `room_features` (`sr_no`, `room_id`, `features_id`) VALUES
(42, 4, 6),
(43, 4, 7),
(44, 4, 8),
(45, 5, 6),
(46, 5, 7),
(47, 5, 8),
(54, 12, 6),
(55, 12, 7),
(59, 3, 6),
(60, 3, 7),
(64, 14, 10),
(65, 14, 11),
(66, 14, 13),
(67, 14, 15),
(68, 15, 11),
(69, 15, 12),
(70, 15, 15),
(71, 16, 10),
(72, 16, 13),
(73, 16, 14),
(74, 16, 15),
(75, 17, 12),
(76, 17, 13),
(77, 17, 15),
(78, 18, 12),
(79, 18, 14),
(80, 18, 15),
(81, 19, 11),
(82, 19, 12),
(83, 19, 15),
(84, 20, 10),
(85, 20, 12),
(86, 20, 15),
(87, 21, 12),
(88, 21, 13),
(89, 21, 14),
(90, 21, 15),
(99, 13, 11),
(100, 13, 12),
(101, 13, 15);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_images`
--

CREATE TABLE `room_images` (
  `sr_no` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `image` varchar(150) NOT NULL,
  `thumb` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `room_images`
--

INSERT INTO `room_images` (`sr_no`, `room_id`, `image`, `thumb`) VALUES
(33, 13, 'IMG_15100.jpg', 1),
(34, 13, 'IMG_91119.jpg', 0),
(35, 13, 'IMG_68914.jpg', 0),
(36, 13, 'IMG_40486.jpg', 0),
(38, 14, 'IMG_32398.jpg', 0),
(39, 14, 'IMG_18860.jpg', 1),
(40, 14, 'IMG_63463.jpg', 0),
(41, 14, 'IMG_79868.jpg', 0),
(42, 15, 'IMG_38638.jpg', 1),
(43, 15, 'IMG_96727.jpg', 0),
(44, 15, 'IMG_97818.jpg', 0),
(45, 15, 'IMG_51700.jpg', 0),
(46, 16, 'IMG_43590.jpg', 0),
(47, 16, 'IMG_93200.jpg', 1),
(48, 16, 'IMG_59074.jpg', 0),
(49, 17, 'IMG_42278.jpg', 1),
(50, 17, 'IMG_23313.jpg', 0),
(51, 17, 'IMG_78441.jpg', 0),
(52, 17, 'IMG_78760.jpg', 0),
(53, 18, 'IMG_90863.jpg', 0),
(54, 18, 'IMG_93655.jpg', 1),
(55, 18, 'IMG_54455.jpg', 0),
(56, 18, 'IMG_94396.jpg', 0),
(57, 19, 'IMG_46998.jpg', 1),
(58, 19, 'IMG_78580.jpg', 0),
(59, 19, 'IMG_27496.jpg', 0),
(60, 19, 'IMG_82918.jpg', 0),
(61, 20, 'IMG_97361.jpg', 1),
(62, 20, 'IMG_29203.jpg', 0),
(63, 20, 'IMG_90537.jpg', 0),
(64, 20, 'IMG_19709.jpg', 0),
(65, 21, 'IMG_18668.jpg', 1),
(66, 21, 'IMG_38899.jpg', 0),
(67, 21, 'IMG_40877.jpg', 0),
(68, 21, 'IMG_74622.jpg', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `settings`
--

CREATE TABLE `settings` (
  `id_setting` int(11) NOT NULL,
  `site_title` varchar(50) NOT NULL,
  `site_about` varchar(500) NOT NULL,
  `shutdown` tinyint(1) NOT NULL,
  `id_admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `settings`
--

INSERT INTO `settings` (`id_setting`, `site_title`, `site_about`, `shutdown`, `id_admin`) VALUES
(1, 'HOTEL', 'HOTEL Là nền tảng đặt phòng khách sạn trực tuyến hàng đầu, giúp bạn dễ dàng tìm kisếm và đặt chỗ nghỉ ngơi hoàn hảo cho kỳ nghỉ của mình. Với hàng ngàn khách sạn, resort và nhà nghỉ trên khắp thế giới, chúng tôi cam kết mang đến cho bạn những lựa chọn', 0, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `team_details`
--

CREATE TABLE `team_details` (
  `id_team` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `picture` varchar(150) NOT NULL,
  `id_admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `team_details`
--

INSERT INTO `team_details` (`id_team`, `name`, `picture`, `id_admin`) VALUES
(13, 'Đào Thị Thảo', 'IMG_93849.jpg', 1),
(14, 'Nguyễn Thị An', 'IMG_93686.jpg', 1),
(15, 'Bùi Thị Hồng', 'IMG_17763.jpg', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_cred`
--

CREATE TABLE `user_cred` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phonenum` varchar(100) NOT NULL,
  `dob` date DEFAULT NULL,
  `password` varchar(200) NOT NULL,
  `is_verified` int(11) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 1,
  `datentime` datetime NOT NULL DEFAULT current_timestamp(),
  `token` varchar(255) DEFAULT NULL,
  `t_expire` datetime DEFAULT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_cred`
--

INSERT INTO `user_cred` (`id`, `name`, `email`, `phonenum`, `dob`, `password`, `is_verified`, `status`, `datentime`, `token`, `t_expire`, `admin_id`) VALUES
(5, 'Văn A', 'vannhan102003@gmail.com', '233413', '2001-06-29', '$2y$10$Wu6/R5zdGGQ0DQjhwy34H.TrbPjLewFvDd3pnzY3Jgd48ewC1HWEq', 1, 1, '2025-02-24 16:23:55', NULL, NULL, 0),
(6, 'vannhan', 'vannhan1@gmail.com', '23432423', NULL, '$2y$10$xqryNsln1OierrdtPbC8E.ONnS181W7i7u9NYAelv1Vf1MT0n.0KW', 1, 1, '2025-02-24 16:27:42', NULL, NULL, 0),
(7, 'Văn C', 'nhan2@gmail.com', '076260590', '2025-04-03', '$2y$10$V5NIzYnjFHbH1siAuCjZAOXoTK5HwgXLJZsO6RKk.bNXsAuJyOoSC', 1, 1, '2025-02-24 16:29:02', NULL, NULL, 0),
(25, 'văn tuấn', 'tuan@gmail.com', '032658794', NULL, '$2y$10$SNEsvE/oueHV/vVkxichG.7Q2eFFQehv15SaqIb8x.Or2q4vFKZ0i', 1, 1, '2025-03-26 16:02:44', NULL, NULL, 0),
(31, 'Văn B', 'bin03102003@gmail.com', '01254873', '2025-04-03', '$2y$10$hxO8iBvojQA0jSiwF0T4/.RQRsRSfYZLIg4V/abfWQKOCAdd366rS', 1, 1, '2025-04-02 18:20:36', 'd105c8352f2cacf50343e9b557749c3c9643440bc464bfbf54b9f86a9dbf510e', '2025-04-15 11:24:23', 0),
(33, 'Văn D', 'nhan03102003@gmail.com', '078456787', '2025-04-03', '$2y$10$6fs4EamVq9SqAkQUi/oQ8u4C5I4yT7.46ZeUz/T3bJGp.s/ELjgKW', 1, 1, '2025-04-03 21:00:29', 'cdfe3810198337b07e4c7cc17c45ac45ad7acd1ad04a06d427f5053f5dd5961a', '2025-04-10 21:09:45', 0),
(35, 'bin', 'bin324@gmail.com', '4354543', NULL, '$2y$10$0fojgSfhmfqr74TiA5R5benx.AtiqMZTFecLC.rzw4SYsndr0cMIm', 0, 1, '2025-04-08 15:58:47', '7f99a900db6eb9db11d728c43521c7d3', '2025-04-09 10:58:47', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_queries`
--

CREATE TABLE `user_queries` (
  `sr_no` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` varchar(500) NOT NULL,
  `datentime` datetime NOT NULL DEFAULT current_timestamp(),
  `seen` tinyint(4) NOT NULL DEFAULT 0,
  `id_admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_queries`
--

INSERT INTO `user_queries` (`sr_no`, `name`, `email`, `subject`, `message`, `datentime`, `seen`, `id_admin`) VALUES
(18, 'nhan03102003@gmail.com', 'nhan03102003@gmail.com', 'nhan03102003@gmail.com', 'nhan03102003@gmail.com', '2025-04-08 14:50:53', 1, 0),
(19, 'nhan03102003@gmail.com', 'nhan03102003@gmail.com', 'nhan03102003@gmail.com', 'nhan03102003@gmail.com', '2025-04-08 14:51:26', 1, 0),
(20, 'test', 'test@gmail.com', 'test', 'test', '2025-04-10 20:37:10', 0, 0),
(21, 'test@gmail.com', 'test@gmail.com', 'test@gmail.com', 'test@gmail.com', '2025-04-10 20:56:18', 0, 0),
(22, 'test@gmail.com', 'test@gmail.com', 'test@gmail.com', 'test@gmail.com', '2025-04-10 20:56:29', 0, 0),
(23, 'test@gmail.com', 'test@gmail.com', 'test@gmail.com', 'test@gmail.com', '2025-04-12 20:14:06', 0, 0);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin_cred`
--
ALTER TABLE `admin_cred`
  ADD PRIMARY KEY (`admin_id`);

--
-- Chỉ mục cho bảng `booking_order`
--
ALTER TABLE `booking_order`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Chỉ mục cho bảng `carousel`
--
ALTER TABLE `carousel`
  ADD PRIMARY KEY (`sr_no`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Chỉ mục cho bảng `contact_details`
--
ALTER TABLE `contact_details`
  ADD PRIMARY KEY (`id_contact`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Chỉ mục cho bảng `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `rating_review`
--
ALTER TABLE `rating_review`
  ADD PRIMARY KEY (`sr_no`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Chỉ mục cho bảng `room_facilities`
--
ALTER TABLE `room_facilities`
  ADD PRIMARY KEY (`sr_no`),
  ADD KEY `facilities` (`facilities_id`),
  ADD KEY `room id` (`room_id`);

--
-- Chỉ mục cho bảng `room_features`
--
ALTER TABLE `room_features`
  ADD PRIMARY KEY (`sr_no`),
  ADD KEY `features` (`features_id`),
  ADD KEY `rm id` (`room_id`);

--
-- Chỉ mục cho bảng `room_images`
--
ALTER TABLE `room_images`
  ADD PRIMARY KEY (`sr_no`),
  ADD KEY `room_id` (`room_id`);

--
-- Chỉ mục cho bảng `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id_setting`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Chỉ mục cho bảng `team_details`
--
ALTER TABLE `team_details`
  ADD PRIMARY KEY (`id_team`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Chỉ mục cho bảng `user_cred`
--
ALTER TABLE `user_cred`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `user_queries`
--
ALTER TABLE `user_queries`
  ADD PRIMARY KEY (`sr_no`),
  ADD KEY `id_admin` (`id_admin`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin_cred`
--
ALTER TABLE `admin_cred`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `booking_order`
--
ALTER TABLE `booking_order`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT cho bảng `carousel`
--
ALTER TABLE `carousel`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `contact_details`
--
ALTER TABLE `contact_details`
  MODIFY `id_contact` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `features`
--
ALTER TABLE `features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `rating_review`
--
ALTER TABLE `rating_review`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `room_facilities`
--
ALTER TABLE `room_facilities`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT cho bảng `room_features`
--
ALTER TABLE `room_features`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT cho bảng `room_images`
--
ALTER TABLE `room_images`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT cho bảng `settings`
--
ALTER TABLE `settings`
  MODIFY `id_setting` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `team_details`
--
ALTER TABLE `team_details`
  MODIFY `id_team` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `user_cred`
--
ALTER TABLE `user_cred`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT cho bảng `user_queries`
--
ALTER TABLE `user_queries`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `admin_cred`
--
ALTER TABLE `admin_cred`
  ADD CONSTRAINT `admin_cred_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `user_queries` (`id_admin`),
  ADD CONSTRAINT `admin_cred_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `carousel` (`id_admin`),
  ADD CONSTRAINT `admin_cred_ibfk_3` FOREIGN KEY (`admin_id`) REFERENCES `settings` (`id_admin`),
  ADD CONSTRAINT `admin_cred_ibfk_4` FOREIGN KEY (`admin_id`) REFERENCES `contact_details` (`id_admin`),
  ADD CONSTRAINT `admin_cred_ibfk_5` FOREIGN KEY (`admin_id`) REFERENCES `team_details` (`id_admin`),
  ADD CONSTRAINT `admin_cred_ibfk_6` FOREIGN KEY (`admin_id`) REFERENCES `rooms` (`admin_id`),
  ADD CONSTRAINT `admin_cred_ibfk_7` FOREIGN KEY (`admin_id`) REFERENCES `user_cred` (`admin_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `booking_order`
--
ALTER TABLE `booking_order`
  ADD CONSTRAINT `booking_order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_cred` (`id`),
  ADD CONSTRAINT `booking_order_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Các ràng buộc cho bảng `rating_review`
--
ALTER TABLE `rating_review`
  ADD CONSTRAINT `rating_review_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking_order` (`booking_id`),
  ADD CONSTRAINT `rating_review_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `rating_review_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user_cred` (`id`);

--
-- Các ràng buộc cho bảng `room_facilities`
--
ALTER TABLE `room_facilities`
  ADD CONSTRAINT `facilities` FOREIGN KEY (`facilities_id`) REFERENCES `facilities` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `room id` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `room_features`
--
ALTER TABLE `room_features`
  ADD CONSTRAINT `features` FOREIGN KEY (`features_id`) REFERENCES `features` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `rm id` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `room_images`
--
ALTER TABLE `room_images`
  ADD CONSTRAINT `room_images_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
