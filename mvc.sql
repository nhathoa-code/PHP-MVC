-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 14, 2024 lúc 03:30 PM
-- Phiên bản máy phục vụ: 10.4.27-MariaDB
-- Phiên bản PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `mvc`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `cat_name` varchar(50) NOT NULL,
  `cat_slug` varchar(50) NOT NULL,
  `cat_image` varchar(100) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `cat_name`, `cat_slug`, `cat_image`, `parent_id`) VALUES
(14, 'Trang phục nam', 'Trang-phuc-nam', 'images/cat/4550182707403_01_1260.jpg', NULL),
(17, 'Trang phục nữ', 'Trang-phuc-nu', 'images/cat/4550182792454_01_1260.jpg', NULL),
(24, 'Trang phục trẻ em', 'Trang-phuc-tre-em', 'images/cat/4550182648287_1260.jpg', NULL),
(100, 'Giày', 'Giay', 'images/cat/4550583074944_400.jpg', NULL),
(107, 'Đồ thun', 'Do-thun', NULL, 14),
(108, 'Quần nam', 'Quan-nam', NULL, 14),
(109, 'Áo sơ mi nam', 'Ao-so-mi-nam', NULL, 14),
(114, 'Áo khoác nam', 'Ao-khoac-nam', NULL, 14),
(118, 'Phụ kiện', 'phu-kien', 'images/cat/4550583190804_400.jpg', NULL),
(119, 'Mũ nón', 'mu-non', NULL, 118),
(120, 'Giày sneaker', 'giay-sneaker', NULL, 100),
(121, 'Đồ thun', 'do-thun', NULL, 17),
(122, 'Quần & Vấy nữ', 'quan-vay-nu', NULL, 17),
(123, 'Áo khoác nữ', 'ao-khoac-nu', NULL, 17),
(124, 'Trẻ em', 'tre-em', NULL, 24),
(125, 'Em bé', 'em-be', NULL, 24);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `amount` int(11) NOT NULL,
  `minimum_spend` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `coupon_usage` int(11) NOT NULL,
  `coupon_used` int(11) DEFAULT 0,
  `per_user` tinyint(4) NOT NULL
) ;

--
-- Đang đổ dữ liệu cho bảng `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `amount`, `minimum_spend`, `start_time`, `end_time`, `coupon_usage`, `coupon_used`, `per_user`) VALUES
(1, 'VNHCP111', 100000, 500000, '2024-03-05 20:24:00', '2024-03-16 20:24:00', 20, 0, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupon_usage`
--

CREATE TABLE `coupon_usage` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `email_queue`
--

CREATE TABLE `email_queue` (
  `id` int(11) NOT NULL,
  `to_email` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `email_queue`
--

INSERT INTO `email_queue` (`id`, `to_email`, `subject`, `message`, `sent_at`) VALUES
(18, 'nhathoa530@gmail.com', 'VNH - Xác nhận đơn hàng', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n    <meta charset=\"UTF-8\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n    <title>Document</title>\r\n    <style>\r\n\r\n        #container{\r\n            width: 50%;\r\n        }\r\n\r\n        *{\r\n            font-family: Arial, Helvetica, sans-serif;\r\n            color: black;\r\n        }\r\n\r\n        #confirm{\r\n            background-color: #f6f6f6;\r\n            padding: 20px 80px;\r\n            width: fit-content;\r\n            margin: 20px auto;\r\n            text-align: center;\r\n        }\r\n        @media screen and (max-width: 992px) {\r\n           #container{\r\n                width: 100% !important;\r\n           }\r\n        }\r\n\r\n        \r\n    </style>\r\n</head>\r\n<body>\r\n    <div id=\"container\" style=\"width:50%;margin:0 auto\">\r\n        <div id=\"logo\" style=\"text-align:center\">\r\n            <!-- <img style=\"width: 80px;\" src=\"\" alt=\"\"> -->\r\n            <img style=\"width: 80px;\" src=\"https://vnhfashion.shop/client_assets/images/logo.png\" alt=\"\">\r\n        </div>\r\n        <div id=\"thank\">\r\n            <div style=\"text-align:center;margin-top:10px\">Cám ơn <span style=\"font-weight: 600;\">Nhật Hòa</span> đã mua hàng tại website của chúng tôi</div>\r\n        </div>\r\n        <div id=\"confirm\">\r\n            <!-- <img src=\"\" alt=\"\"> -->\r\n            <img src=\"https://ci3.googleusercontent.com/meips/ADKq_NayVTu3_FLF4gEvYg24-nxk0T6DbqtThdJMygcB4sg-Qh5SjC5UbJVfO8IEX3QbbjSzx7i21P3AG6Ou5LBucgsiWn2emwubSru_EOW7CwwZsqxLTqfppd2i-hr6-z9v1XBsKvFEADVIjkt56kwPeH7a9Ozm6WQsv4iANTTvqJ356-4O5TWj2VvmvpVqP0qXAYFqpKA2QMDzXBaNVts=s0-d-e1-ft#https://www.acfc.com.vn/static/version1709711284/frontend/Acfc/mobile/vi_VN/Magento_Email/images/acfc-email-images/order-confirmed.png\" alt=\"\">\r\n            <div style=\"margin:5px 0\">VNH xác nhận đơn hàng</div>\r\n            <div style=\"font-weight: 600;\">#0703575556</div>\r\n        </div>\r\n        <div id=\"order\">\r\n            <div style=\"text-align:center;margin-bottom:10px\">THÔNG TIN ĐƠN HÀNG</div>\r\n            <table>\r\n                <tbody>\r\n                    <tr>\r\n                        <td style=\"width:50%;vertical-align:top\">\r\n                            <div style=\"font-weight:600;margin-bottom:10px\">Thông tin giao hàng</div>\r\n                            <div style=\"font-size: 14px;color:black\">\r\n                                <div>Nhật Hòa | 0911222333</div>\r\n                                <div>\r\n                                    <br />\n<b>Warning</b>:  Array to string conversion in <b>C:\\xampp\\htdocs\\mvc\\views\\client\\template\\confirm_order.php</b> on line <b>59</b><br />\nArray                                \r\n                                </div>\r\n                            </div>\r\n                        </td>\r\n                        <td style=\"vertical-align: top;\">\r\n                            <div style=\"font-weight:600;margin-bottom:10px\">Phương thức thanh toán</div>\r\n                            <div style=\"font-size: 14px;\">\r\n                                Thanh toán khi nhận hàng                            </div>\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <div style=\"text-align:center;margin:20px;clear:both\">\r\n                <a href=\"http://localhost/mvc/public/order/track?order_id=0703575556\" style=\"background-color: #000;color:#f6f6f6;border:none;padding:7.5px 15px;text-decoration:none\">Kiểm tra đơn hàng</a>\r\n            </div>\r\n        </div>\r\n    </div>\r\n   \r\n</body>\r\n</html>', NULL),
(19, 'nhathoa530@gmail.com', 'Xác thực tài khoản', '<a href=\'http://localhost/mvc/public/auth/email/verify?token=3b57e081661fa6d24274960d0a132d6de39115e04a38a009e8fc872932f69201\'>Vui lòng click vào đây để xác thực tài khoản</a>', NULL),
(20, 'test@gmail.com', 'VNH - Xác nhận đơn hàng', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n    <meta charset=\"UTF-8\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n    <title>Document</title>\r\n    <style>\r\n\r\n        #container{\r\n            width: 50%;\r\n        }\r\n\r\n        *{\r\n            font-family: Arial, Helvetica, sans-serif;\r\n            color: black;\r\n        }\r\n\r\n        #confirm{\r\n            background-color: #f6f6f6;\r\n            padding: 20px 80px;\r\n            width: fit-content;\r\n            margin: 20px auto;\r\n            text-align: center;\r\n        }\r\n        @media screen and (max-width: 992px) {\r\n           #container{\r\n                width: 100% !important;\r\n           }\r\n        }\r\n\r\n        \r\n    </style>\r\n</head>\r\n<body>\r\n    <div id=\"container\" style=\"width:50%;margin:0 auto\">\r\n        <div id=\"logo\" style=\"text-align:center\">\r\n            <!-- <img style=\"width: 80px;\" src=\"\" alt=\"\"> -->\r\n            <img style=\"width: 80px;\" src=\"https://vnhfashion.shop/client_assets/images/logo.png\" alt=\"\">\r\n        </div>\r\n        <div id=\"thank\">\r\n            <div style=\"text-align:center;margin-top:10px\">Cám ơn <span style=\"font-weight: 600;\">Nhật Hòa</span> đã mua hàng tại website của chúng tôi</div>\r\n        </div>\r\n        <div id=\"confirm\">\r\n            <!-- <img src=\"\" alt=\"\"> -->\r\n            <img src=\"https://ci3.googleusercontent.com/meips/ADKq_NayVTu3_FLF4gEvYg24-nxk0T6DbqtThdJMygcB4sg-Qh5SjC5UbJVfO8IEX3QbbjSzx7i21P3AG6Ou5LBucgsiWn2emwubSru_EOW7CwwZsqxLTqfppd2i-hr6-z9v1XBsKvFEADVIjkt56kwPeH7a9Ozm6WQsv4iANTTvqJ356-4O5TWj2VvmvpVqP0qXAYFqpKA2QMDzXBaNVts=s0-d-e1-ft#https://www.acfc.com.vn/static/version1709711284/frontend/Acfc/mobile/vi_VN/Magento_Email/images/acfc-email-images/order-confirmed.png\" alt=\"\">\r\n            <div style=\"margin:5px 0\">VNH xác nhận đơn hàng</div>\r\n            <div style=\"font-weight: 600;\">#0803010711</div>\r\n        </div>\r\n        <div id=\"order\">\r\n            <div style=\"text-align:center;margin-bottom:10px\">THÔNG TIN ĐƠN HÀNG</div>\r\n            <table>\r\n                <tbody>\r\n                    <tr>\r\n                        <td style=\"width:50%;vertical-align:top\">\r\n                            <div style=\"font-weight:600;margin-bottom:10px\">Thông tin giao hàng</div>\r\n                            <div style=\"font-size: 14px;color:black\">\r\n                                <div>Nhật Hòa | 0911222333</div>\r\n                                <div>\r\n                                    123 Liên khu 5-6, Phường Bình Trị Đông, Quận Bình Tân Update, Hồ Chí Minh                                \r\n                                </div>\r\n                            </div>\r\n                        </td>\r\n                        <td style=\"vertical-align: top;\">\r\n                            <div style=\"font-weight:600;margin-bottom:10px\">Phương thức thanh toán</div>\r\n                            <div style=\"font-size: 14px;\">\r\n                                Thanh toán khi nhận hàng                            </div>\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <div style=\"text-align:center;margin:20px;clear:both\">\r\n                <a href=\"http://localhost/mvc/public/order/track?order_id=0803010711\" style=\"background-color: #000;color:#f6f6f6;border:none;padding:7.5px 15px;text-decoration:none\">Kiểm tra đơn hàng</a>\r\n            </div>\r\n        </div>\r\n    </div>\r\n   \r\n</body>\r\n</html>', NULL),
(21, 'test@gmail.com', 'VNH - Xác nhận đơn hàng', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n    <meta charset=\"UTF-8\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n    <title>Document</title>\r\n    <style>\r\n\r\n        #container{\r\n            width: 50%;\r\n        }\r\n\r\n        *{\r\n            font-family: Arial, Helvetica, sans-serif;\r\n            color: black;\r\n        }\r\n\r\n        #confirm{\r\n            background-color: #f6f6f6;\r\n            padding: 20px 80px;\r\n            width: fit-content;\r\n            margin: 20px auto;\r\n            text-align: center;\r\n        }\r\n        @media screen and (max-width: 992px) {\r\n           #container{\r\n                width: 100% !important;\r\n           }\r\n        }\r\n\r\n        \r\n    </style>\r\n</head>\r\n<body>\r\n    <div id=\"container\" style=\"width:50%;margin:0 auto\">\r\n        <div id=\"logo\" style=\"text-align:center\">\r\n            <!-- <img style=\"width: 80px;\" src=\"\" alt=\"\"> -->\r\n            <img style=\"width: 80px;\" src=\"https://vnhfashion.shop/client_assets/images/logo.png\" alt=\"\">\r\n        </div>\r\n        <div id=\"thank\">\r\n            <div style=\"text-align:center;margin-top:10px\">Cám ơn <span style=\"font-weight: 600;\">Nhật Hòa</span> đã mua hàng tại website của chúng tôi</div>\r\n        </div>\r\n        <div id=\"confirm\">\r\n            <!-- <img src=\"\" alt=\"\"> -->\r\n            <img src=\"https://ci3.googleusercontent.com/meips/ADKq_NayVTu3_FLF4gEvYg24-nxk0T6DbqtThdJMygcB4sg-Qh5SjC5UbJVfO8IEX3QbbjSzx7i21P3AG6Ou5LBucgsiWn2emwubSru_EOW7CwwZsqxLTqfppd2i-hr6-z9v1XBsKvFEADVIjkt56kwPeH7a9Ozm6WQsv4iANTTvqJ356-4O5TWj2VvmvpVqP0qXAYFqpKA2QMDzXBaNVts=s0-d-e1-ft#https://www.acfc.com.vn/static/version1709711284/frontend/Acfc/mobile/vi_VN/Magento_Email/images/acfc-email-images/order-confirmed.png\" alt=\"\">\r\n            <div style=\"margin:5px 0\">VNH xác nhận đơn hàng</div>\r\n            <div style=\"font-weight: 600;\">#0803576421</div>\r\n        </div>\r\n        <div id=\"order\">\r\n            <div style=\"text-align:center;margin-bottom:10px\">THÔNG TIN ĐƠN HÀNG</div>\r\n            <table>\r\n                <tbody>\r\n                    <tr>\r\n                        <td style=\"width:50%;vertical-align:top\">\r\n                            <div style=\"font-weight:600;margin-bottom:10px\">Thông tin giao hàng</div>\r\n                            <div style=\"font-size: 14px;color:black\">\r\n                                <div>Nhật Hòa | 0911222333</div>\r\n                                <div>\r\n                                    123 Liên khu 5-6, Phường Bình Trị Đông, Quận Bình Tân Update, Hồ Chí Minh                                \r\n                                </div>\r\n                            </div>\r\n                        </td>\r\n                        <td style=\"vertical-align: top;\">\r\n                            <div style=\"font-weight:600;margin-bottom:10px\">Phương thức thanh toán</div>\r\n                            <div style=\"font-size: 14px;\">\r\n                                Thanh toán khi nhận hàng                            </div>\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <div style=\"text-align:center;margin:20px;clear:both\">\r\n                <a href=\"http://localhost/mvc/public/order/track?order_id=0803576421\" style=\"background-color: #000;color:#f6f6f6;border:none;padding:7.5px 15px;text-decoration:none\">Kiểm tra đơn hàng</a>\r\n            </div>\r\n        </div>\r\n    </div>\r\n   \r\n</body>\r\n</html>', NULL),
(22, 'test@gmail.com', 'VNH - Xác nhận đơn hàng', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n    <meta charset=\"UTF-8\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n    <title>Document</title>\r\n    <style>\r\n\r\n        #container{\r\n            width: 50%;\r\n        }\r\n\r\n        *{\r\n            font-family: Arial, Helvetica, sans-serif;\r\n            color: black;\r\n        }\r\n\r\n        #confirm{\r\n            background-color: #f6f6f6;\r\n            padding: 20px 80px;\r\n            width: fit-content;\r\n            margin: 20px auto;\r\n            text-align: center;\r\n        }\r\n        @media screen and (max-width: 992px) {\r\n           #container{\r\n                width: 100% !important;\r\n           }\r\n        }\r\n\r\n        \r\n    </style>\r\n</head>\r\n<body>\r\n    <div id=\"container\" style=\"width:50%;margin:0 auto\">\r\n        <div id=\"logo\" style=\"text-align:center\">\r\n            <!-- <img style=\"width: 80px;\" src=\"\" alt=\"\"> -->\r\n            <img style=\"width: 80px;\" src=\"https://vnhfashion.shop/client_assets/images/logo.png\" alt=\"\">\r\n        </div>\r\n        <div id=\"thank\">\r\n            <div style=\"text-align:center;margin-top:10px\">Cám ơn <span style=\"font-weight: 600;\">Nhật Hòa</span> đã mua hàng tại website của chúng tôi</div>\r\n        </div>\r\n        <div id=\"confirm\">\r\n            <!-- <img src=\"\" alt=\"\"> -->\r\n            <img src=\"https://ci3.googleusercontent.com/meips/ADKq_NayVTu3_FLF4gEvYg24-nxk0T6DbqtThdJMygcB4sg-Qh5SjC5UbJVfO8IEX3QbbjSzx7i21P3AG6Ou5LBucgsiWn2emwubSru_EOW7CwwZsqxLTqfppd2i-hr6-z9v1XBsKvFEADVIjkt56kwPeH7a9Ozm6WQsv4iANTTvqJ356-4O5TWj2VvmvpVqP0qXAYFqpKA2QMDzXBaNVts=s0-d-e1-ft#https://www.acfc.com.vn/static/version1709711284/frontend/Acfc/mobile/vi_VN/Magento_Email/images/acfc-email-images/order-confirmed.png\" alt=\"\">\r\n            <div style=\"margin:5px 0\">VNH xác nhận đơn hàng</div>\r\n            <div style=\"font-weight: 600;\">#0803475400</div>\r\n        </div>\r\n        <div id=\"order\">\r\n            <div style=\"text-align:center;margin-bottom:10px\">THÔNG TIN ĐƠN HÀNG</div>\r\n            <table>\r\n                <tbody>\r\n                    <tr>\r\n                        <td style=\"width:50%;vertical-align:top\">\r\n                            <div style=\"font-weight:600;margin-bottom:10px\">Thông tin giao hàng</div>\r\n                            <div style=\"font-size: 14px;color:black\">\r\n                                <div>Nhật Hòa | 0911222333</div>\r\n                                <div>\r\n                                    123 Liên khu 5-6, Phường Bình Trị Đông, Quận Bình Tân Update, Hồ Chí Minh                                \r\n                                </div>\r\n                            </div>\r\n                        </td>\r\n                        <td style=\"vertical-align: top;\">\r\n                            <div style=\"font-weight:600;margin-bottom:10px\">Phương thức thanh toán</div>\r\n                            <div style=\"font-size: 14px;\">\r\n                                Thanh toán khi nhận hàng                            </div>\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <div style=\"text-align:center;margin:20px;clear:both\">\r\n                <a href=\"http://localhost/mvc/public/order/track?order_id=0803475400\" style=\"background-color: #000;color:#f6f6f6;border:none;padding:7.5px 15px;text-decoration:none\">Kiểm tra đơn hàng</a>\r\n            </div>\r\n        </div>\r\n    </div>\r\n   \r\n</body>\r\n</html>', NULL),
(23, 'test@gmail.com', 'VNH - Xác nhận đơn hàng', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n    <meta charset=\"UTF-8\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n    <title>Document</title>\r\n    <style>\r\n\r\n        #container{\r\n            width: 50%;\r\n        }\r\n\r\n        *{\r\n            font-family: Arial, Helvetica, sans-serif;\r\n            color: black;\r\n        }\r\n\r\n        #confirm{\r\n            background-color: #f6f6f6;\r\n            padding: 20px 80px;\r\n            width: fit-content;\r\n            margin: 20px auto;\r\n            text-align: center;\r\n        }\r\n        @media screen and (max-width: 992px) {\r\n           #container{\r\n                width: 100% !important;\r\n           }\r\n        }\r\n\r\n        \r\n    </style>\r\n</head>\r\n<body>\r\n    <div id=\"container\" style=\"width:50%;margin:0 auto\">\r\n        <div id=\"logo\" style=\"text-align:center\">\r\n            <!-- <img style=\"width: 80px;\" src=\"\" alt=\"\"> -->\r\n            <img style=\"width: 80px;\" src=\"https://vnhfashion.shop/client_assets/images/logo.png\" alt=\"\">\r\n        </div>\r\n        <div id=\"thank\">\r\n            <div style=\"text-align:center;margin-top:10px\">Cám ơn <span style=\"font-weight: 600;\">Nhật Hòa</span> đã mua hàng tại website của chúng tôi</div>\r\n        </div>\r\n        <div id=\"confirm\">\r\n            <!-- <img src=\"\" alt=\"\"> -->\r\n            <img src=\"https://ci3.googleusercontent.com/meips/ADKq_NayVTu3_FLF4gEvYg24-nxk0T6DbqtThdJMygcB4sg-Qh5SjC5UbJVfO8IEX3QbbjSzx7i21P3AG6Ou5LBucgsiWn2emwubSru_EOW7CwwZsqxLTqfppd2i-hr6-z9v1XBsKvFEADVIjkt56kwPeH7a9Ozm6WQsv4iANTTvqJ356-4O5TWj2VvmvpVqP0qXAYFqpKA2QMDzXBaNVts=s0-d-e1-ft#https://www.acfc.com.vn/static/version1709711284/frontend/Acfc/mobile/vi_VN/Magento_Email/images/acfc-email-images/order-confirmed.png\" alt=\"\">\r\n            <div style=\"margin:5px 0\">VNH xác nhận đơn hàng</div>\r\n            <div style=\"font-weight: 600;\">#1303177954</div>\r\n        </div>\r\n        <div id=\"order\">\r\n            <div style=\"text-align:center;margin-bottom:10px\">THÔNG TIN ĐƠN HÀNG</div>\r\n            <table>\r\n                <tbody>\r\n                    <tr>\r\n                        <td style=\"width:50%;vertical-align:top\">\r\n                            <div style=\"font-weight:600;margin-bottom:10px\">Thông tin giao hàng</div>\r\n                            <div style=\"font-size: 14px;color:black\">\r\n                                <div>Nhật Hòa | 0911222333</div>\r\n                                <div>\r\n                                    123 Liên khu 5-6, Phường Bình Trị Đông, Quận Bình Tân Update, Hồ Chí Minh                                \r\n                                </div>\r\n                            </div>\r\n                        </td>\r\n                        <td style=\"vertical-align: top;\">\r\n                            <div style=\"font-weight:600;margin-bottom:10px\">Phương thức thanh toán</div>\r\n                            <div style=\"font-size: 14px;\">\r\n                                Thanh toán khi nhận hàng                            </div>\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <div style=\"text-align:center;margin:20px;clear:both\">\r\n                <a href=\"http://localhost/mvc/public/order/track?order_id=1303177954\" style=\"background-color: #000;color:#f6f6f6;border:none;padding:7.5px 15px;text-decoration:none\">Kiểm tra đơn hàng</a>\r\n            </div>\r\n        </div>\r\n    </div>\r\n   \r\n</body>\r\n</html>', NULL),
(24, 'test@gmail.com', 'VNH - Xác nhận đơn hàng', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n    <meta charset=\"UTF-8\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n    <title>Document</title>\r\n    <style>\r\n\r\n        #container{\r\n            width: 50%;\r\n        }\r\n\r\n        *{\r\n            font-family: Arial, Helvetica, sans-serif;\r\n            color: black;\r\n        }\r\n\r\n        #confirm{\r\n            background-color: #f6f6f6;\r\n            padding: 20px 80px;\r\n            width: fit-content;\r\n            margin: 20px auto;\r\n            text-align: center;\r\n        }\r\n        @media screen and (max-width: 992px) {\r\n           #container{\r\n                width: 100% !important;\r\n           }\r\n        }\r\n\r\n        \r\n    </style>\r\n</head>\r\n<body>\r\n    <div id=\"container\" style=\"width:50%;margin:0 auto\">\r\n        <div id=\"logo\" style=\"text-align:center\">\r\n            <!-- <img style=\"width: 80px;\" src=\"\" alt=\"\"> -->\r\n            <img style=\"width: 80px;\" src=\"https://vnhfashion.shop/client_assets/images/logo.png\" alt=\"\">\r\n        </div>\r\n        <div id=\"thank\">\r\n            <div style=\"text-align:center;margin-top:10px\">Cám ơn <span style=\"font-weight: 600;\">Nhật Hòa</span> đã mua hàng tại website của chúng tôi</div>\r\n        </div>\r\n        <div id=\"confirm\">\r\n            <!-- <img src=\"\" alt=\"\"> -->\r\n            <img src=\"https://ci3.googleusercontent.com/meips/ADKq_NayVTu3_FLF4gEvYg24-nxk0T6DbqtThdJMygcB4sg-Qh5SjC5UbJVfO8IEX3QbbjSzx7i21P3AG6Ou5LBucgsiWn2emwubSru_EOW7CwwZsqxLTqfppd2i-hr6-z9v1XBsKvFEADVIjkt56kwPeH7a9Ozm6WQsv4iANTTvqJ356-4O5TWj2VvmvpVqP0qXAYFqpKA2QMDzXBaNVts=s0-d-e1-ft#https://www.acfc.com.vn/static/version1709711284/frontend/Acfc/mobile/vi_VN/Magento_Email/images/acfc-email-images/order-confirmed.png\" alt=\"\">\r\n            <div style=\"margin:5px 0\">VNH xác nhận đơn hàng</div>\r\n            <div style=\"font-weight: 600;\">#1303458245</div>\r\n        </div>\r\n        <div id=\"order\">\r\n            <div style=\"text-align:center;margin-bottom:10px\">THÔNG TIN ĐƠN HÀNG</div>\r\n            <table>\r\n                <tbody>\r\n                    <tr>\r\n                        <td style=\"width:50%;vertical-align:top\">\r\n                            <div style=\"font-weight:600;margin-bottom:10px\">Thông tin giao hàng</div>\r\n                            <div style=\"font-size: 14px;color:black\">\r\n                                <div>Nhật Hòa | 0911222333</div>\r\n                                <div>\r\n                                    123 Liên khu 5-6, Phường Bình Trị Đông, Quận Bình Tân Update, Hồ Chí Minh                                \r\n                                </div>\r\n                            </div>\r\n                        </td>\r\n                        <td style=\"vertical-align: top;\">\r\n                            <div style=\"font-weight:600;margin-bottom:10px\">Phương thức thanh toán</div>\r\n                            <div style=\"font-size: 14px;\">\r\n                                Thanh toán khi nhận hàng                            </div>\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <div style=\"text-align:center;margin:20px;clear:both\">\r\n                <a href=\"http://localhost/mvc/public/order/track?order_id=1303458245\" style=\"background-color: #000;color:#f6f6f6;border:none;padding:7.5px 15px;text-decoration:none\">Kiểm tra đơn hàng</a>\r\n            </div>\r\n        </div>\r\n    </div>\r\n   \r\n</body>\r\n</html>', NULL),
(25, 'test@gmail.com', 'VNH - Xác nhận đơn hàng', '&lt;!DOCTYPE html&gt;\r\n&lt;html lang=\"en\"&gt;\r\n&lt;head&gt;\r\n    &lt;meta charset=\"UTF-8\"&gt;\r\n    &lt;meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"&gt;\r\n    &lt;title&gt;Document&lt;/title&gt;\r\n    &lt;style&gt;\r\n\r\n        #container{\r\n            width: 50%;\r\n        }\r\n\r\n        *{\r\n            font-family: Arial, Helvetica, sans-serif;\r\n            color: black;\r\n        }\r\n\r\n        #confirm{\r\n            background-color: #f6f6f6;\r\n            padding: 20px 80px;\r\n            width: fit-content;\r\n            margin: 20px auto;\r\n            text-align: center;\r\n        }\r\n        @media screen and (max-width: 992px) {\r\n           #container{\r\n                width: 100% !important;\r\n           }\r\n        }\r\n\r\n        \r\n    &lt;/style&gt;\r\n&lt;/head&gt;\r\n&lt;body&gt;\r\n    &lt;div id=\"container\" style=\"width:50%;margin:0 auto\"&gt;\r\n        &lt;div id=\"logo\" style=\"text-align:center\"&gt;\r\n            &lt;!-- &lt;img style=\"width: 80px;\" src=\"\" alt=\"\"&gt; --&gt;\r\n            &lt;img style=\"width: 80px;\" src=\"https://vnhfashion.shop/client_assets/images/logo.png\" alt=\"\"&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"thank\"&gt;\r\n            &lt;div style=\"text-align:center;margin-top:10px\"&gt;Cám ơn &lt;span style=\"font-weight: 600;\"&gt;Nhật Hòa&lt;/span&gt; đã mua hàng tại website của chúng tôi&lt;/div&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"confirm\"&gt;\r\n            &lt;!-- &lt;img src=\"\" alt=\"\"&gt; --&gt;\r\n            &lt;img src=\"https://ci3.googleusercontent.com/meips/ADKq_NayVTu3_FLF4gEvYg24-nxk0T6DbqtThdJMygcB4sg-Qh5SjC5UbJVfO8IEX3QbbjSzx7i21P3AG6Ou5LBucgsiWn2emwubSru_EOW7CwwZsqxLTqfppd2i-hr6-z9v1XBsKvFEADVIjkt56kwPeH7a9Ozm6WQsv4iANTTvqJ356-4O5TWj2VvmvpVqP0qXAYFqpKA2QMDzXBaNVts=s0-d-e1-ft#https://www.acfc.com.vn/static/version1709711284/frontend/Acfc/mobile/vi_VN/Magento_Email/images/acfc-email-images/order-confirmed.png\" alt=\"\"&gt;\r\n            &lt;div style=\"margin:5px 0\"&gt;VNH xác nhận đơn hàng&lt;/div&gt;\r\n            &lt;div style=\"font-weight: 600;\"&gt;#1303735269&lt;/div&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"order\"&gt;\r\n            &lt;div style=\"text-align:center;margin-bottom:10px\"&gt;THÔNG TIN ĐƠN HÀNG&lt;/div&gt;\r\n            &lt;table&gt;\r\n                &lt;tbody&gt;\r\n                    &lt;tr&gt;\r\n                        &lt;td style=\"width:50%;vertical-align:top\"&gt;\r\n                            &lt;div style=\"font-weight:600;margin-bottom:10px\"&gt;Thông tin giao hàng&lt;/div&gt;\r\n                            &lt;div style=\"font-size: 14px;color:black\"&gt;\r\n                                &lt;div&gt;Nhật Hòa | 0911222333&lt;/div&gt;\r\n                                &lt;div&gt;\r\n                                    123 Liên khu 5-6, Phường Bình Trị Đông, Quận Bình Tân Update, Hồ Chí Minh                                \r\n                                &lt;/div&gt;\r\n                            &lt;/div&gt;\r\n                        &lt;/td&gt;\r\n                        &lt;td style=\"vertical-align: top;\"&gt;\r\n                            &lt;div style=\"font-weight:600;margin-bottom:10px\"&gt;Phương thức thanh toán&lt;/div&gt;\r\n                            &lt;div style=\"font-size: 14px;\"&gt;\r\n                                Thanh toán khi nhận hàng                            &lt;/div&gt;\r\n                        &lt;/td&gt;\r\n                    &lt;/tr&gt;\r\n                &lt;/tbody&gt;\r\n            &lt;/table&gt;\r\n            &lt;div style=\"text-align:center;margin:20px;clear:both\"&gt;\r\n                &lt;a href=\"http://localhost/mvc/public/order/track?order_id=1303735269\" style=\"background-color: #000;color:#f6f6f6;border:none;padding:7.5px 15px;text-decoration:none\"&gt;Kiểm tra đơn hàng&lt;/a&gt;\r\n            &lt;/div&gt;\r\n        &lt;/div&gt;\r\n    &lt;/div&gt;\r\n   \r\n&lt;/body&gt;\r\n&lt;/html&gt;', NULL),
(26, 'test@gmail.com', 'VNH - Xác nhận đơn hàng', '&lt;!DOCTYPE html&gt;\r\n&lt;html lang=\"en\"&gt;\r\n&lt;head&gt;\r\n    &lt;meta charset=\"UTF-8\"&gt;\r\n    &lt;meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"&gt;\r\n    &lt;title&gt;Document&lt;/title&gt;\r\n    &lt;style&gt;\r\n\r\n        #container{\r\n            width: 50%;\r\n        }\r\n\r\n        *{\r\n            font-family: Arial, Helvetica, sans-serif;\r\n            color: black;\r\n        }\r\n\r\n        #confirm{\r\n            background-color: #f6f6f6;\r\n            padding: 20px 80px;\r\n            width: fit-content;\r\n            margin: 20px auto;\r\n            text-align: center;\r\n        }\r\n        @media screen and (max-width: 992px) {\r\n           #container{\r\n                width: 100% !important;\r\n           }\r\n        }\r\n\r\n        \r\n    &lt;/style&gt;\r\n&lt;/head&gt;\r\n&lt;body&gt;\r\n    &lt;div id=\"container\" style=\"width:50%;margin:0 auto\"&gt;\r\n        &lt;div id=\"logo\" style=\"text-align:center\"&gt;\r\n            &lt;!-- &lt;img style=\"width: 80px;\" src=\"\" alt=\"\"&gt; --&gt;\r\n            &lt;img style=\"width: 80px;\" src=\"https://vnhfashion.shop/client_assets/images/logo.png\" alt=\"\"&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"thank\"&gt;\r\n            &lt;div style=\"text-align:center;margin-top:10px\"&gt;Cám ơn &lt;span style=\"font-weight: 600;\"&gt;&lt;h1&gt;hello&lt;/h1&gt;&lt;/span&gt; đã mua hàng tại website của chúng tôi&lt;/div&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"confirm\"&gt;\r\n            &lt;!-- &lt;img src=\"\" alt=\"\"&gt; --&gt;\r\n            &lt;img src=\"https://ci3.googleusercontent.com/meips/ADKq_NayVTu3_FLF4gEvYg24-nxk0T6DbqtThdJMygcB4sg-Qh5SjC5UbJVfO8IEX3QbbjSzx7i21P3AG6Ou5LBucgsiWn2emwubSru_EOW7CwwZsqxLTqfppd2i-hr6-z9v1XBsKvFEADVIjkt56kwPeH7a9Ozm6WQsv4iANTTvqJ356-4O5TWj2VvmvpVqP0qXAYFqpKA2QMDzXBaNVts=s0-d-e1-ft#https://www.acfc.com.vn/static/version1709711284/frontend/Acfc/mobile/vi_VN/Magento_Email/images/acfc-email-images/order-confirmed.png\" alt=\"\"&gt;\r\n            &lt;div style=\"margin:5px 0\"&gt;VNH xác nhận đơn hàng&lt;/div&gt;\r\n            &lt;div style=\"font-weight: 600;\"&gt;#1303152919&lt;/div&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"order\"&gt;\r\n            &lt;div style=\"text-align:center;margin-bottom:10px\"&gt;THÔNG TIN ĐƠN HÀNG&lt;/div&gt;\r\n            &lt;table&gt;\r\n                &lt;tbody&gt;\r\n                    &lt;tr&gt;\r\n                        &lt;td style=\"width:50%;vertical-align:top\"&gt;\r\n                            &lt;div style=\"font-weight:600;margin-bottom:10px\"&gt;Thông tin giao hàng&lt;/div&gt;\r\n                            &lt;div style=\"font-size: 14px;color:black\"&gt;\r\n                                &lt;div&gt;&lt;h1&gt;hello&lt;/h1&gt; | 0911222333&lt;/div&gt;\r\n                                &lt;div&gt;\r\n                                    123 Liên khu 5-6, Phường Bình Trị Đông, Quận Bình Tân, Hồ Chí Minh                                \r\n                                &lt;/div&gt;\r\n                            &lt;/div&gt;\r\n                        &lt;/td&gt;\r\n                        &lt;td style=\"vertical-align: top;\"&gt;\r\n                            &lt;div style=\"font-weight:600;margin-bottom:10px\"&gt;Phương thức thanh toán&lt;/div&gt;\r\n                            &lt;div style=\"font-size: 14px;\"&gt;\r\n                                Thanh toán khi nhận hàng                            &lt;/div&gt;\r\n                        &lt;/td&gt;\r\n                    &lt;/tr&gt;\r\n                &lt;/tbody&gt;\r\n            &lt;/table&gt;\r\n            &lt;div style=\"text-align:center;margin:20px;clear:both\"&gt;\r\n                &lt;a href=\"http://localhost/mvc/public/order/track?order_id=1303152919\" style=\"background-color: #000;color:#f6f6f6;border:none;padding:7.5px 15px;text-decoration:none\"&gt;Kiểm tra đơn hàng&lt;/a&gt;\r\n            &lt;/div&gt;\r\n        &lt;/div&gt;\r\n    &lt;/div&gt;\r\n   \r\n&lt;/body&gt;\r\n&lt;/html&gt;', NULL),
(27, 'test@gmail.com', 'VNH - Hoàn thành đơn hàng', '&lt;!DOCTYPE html&gt;\r\n&lt;html lang=\"en\"&gt;\r\n&lt;head&gt;\r\n    &lt;meta charset=\"UTF-8\"&gt;\r\n    &lt;meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"&gt;\r\n    &lt;title&gt;Document&lt;/title&gt;\r\n    &lt;style&gt;\r\n\r\n        #container{\r\n            width: 50%;\r\n        }\r\n\r\n        *{\r\n            font-family: Arial, Helvetica, sans-serif;\r\n            color: black;\r\n        }\r\n\r\n        #confirm{\r\n            background-color: #f6f6f6;\r\n            padding: 20px 80px;\r\n            width: fit-content;\r\n            margin: 20px auto;\r\n            text-align: center;\r\n        }\r\n        @media screen and (max-width: 992px) {\r\n           #container{\r\n                width: 100% !important;\r\n           }\r\n        }\r\n\r\n        \r\n    &lt;/style&gt;\r\n&lt;/head&gt;\r\n&lt;body&gt;\r\n    &lt;div id=\"container\" style=\"width:50%;margin:0 auto\"&gt;\r\n        &lt;div id=\"logo\" style=\"text-align:center\"&gt;\r\n            &lt;!-- &lt;img style=\"width: 80px;\" src=\"\" alt=\"\"&gt; --&gt;\r\n            &lt;img style=\"width: 80px;\" src=\"https://vnhfashion.shop/client_assets/images/logo.png\" alt=\"\"&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"thank\"&gt;\r\n            &lt;div style=\"text-align:center;margin-top:10px\"&gt;Cám ơn &lt;span style=\"font-weight: 600;\"&gt;&amp;lt;h1&amp;gt;hello&amp;lt;/h1&amp;gt;&lt;/span&gt; đã mua hàng tại website của chúng tôi&lt;/div&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"confirm\"&gt;\r\n            &lt;!-- &lt;img src=\"\" alt=\"\"&gt; --&gt;\r\n            &lt;img src=\"https://ci3.googleusercontent.com/meips/ADKq_NayVTu3_FLF4gEvYg24-nxk0T6DbqtThdJMygcB4sg-Qh5SjC5UbJVfO8IEX3QbbjSzx7i21P3AG6Ou5LBucgsiWn2emwubSru_EOW7CwwZsqxLTqfppd2i-hr6-z9v1XBsKvFEADVIjkt56kwPeH7a9Ozm6WQsv4iANTTvqJ356-4O5TWj2VvmvpVqP0qXAYFqpKA2QMDzXBaNVts=s0-d-e1-ft#https://www.acfc.com.vn/static/version1709711284/frontend/Acfc/mobile/vi_VN/Magento_Email/images/acfc-email-images/order-confirmed.png\" alt=\"\"&gt;\r\n            &lt;div style=\"margin:5px 0\"&gt;VNH Hoàn thành đơn hàng&lt;/div&gt;\r\n            &lt;div style=\"font-weight: 600;\"&gt;#1303152919&lt;/div&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"order\"&gt;\r\n            &lt;div style=\"text-align:center;margin-bottom:10px\"&gt;THÔNG TIN ĐƠN HÀNG&lt;/div&gt;\r\n            &lt;table&gt;\r\n                &lt;tbody&gt;\r\n                    &lt;tr&gt;\r\n                        &lt;td style=\"width:50%;vertical-align:top\"&gt;\r\n                            &lt;div style=\"font-weight:600;margin-bottom:10px\"&gt;Thông tin giao hàng&lt;/div&gt;\r\n                            &lt;div style=\"font-size: 14px;color:black\"&gt;\r\n                                &lt;div&gt;&amp;lt;h1&amp;gt;hello&amp;lt;/h1&amp;gt; | 0911222333&lt;/div&gt;\r\n                                &lt;div&gt;\r\n                                    123 Liên khu 5-6, Phường Bình Trị Đông, Quận Bình Tân, Hồ Chí Minh                                 \r\n                                &lt;/div&gt;\r\n                            &lt;/div&gt;\r\n                        &lt;/td&gt;\r\n                        &lt;td style=\"vertical-align: top;\"&gt;\r\n                            &lt;div style=\"font-weight:600;margin-bottom:10px\"&gt;Phương thức thanh toán&lt;/div&gt;\r\n                            &lt;div style=\"font-size: 14px;\"&gt;\r\n                                cod                            &lt;/div&gt;\r\n                        &lt;/td&gt;\r\n                    &lt;/tr&gt;\r\n                &lt;/tbody&gt;\r\n            &lt;/table&gt;\r\n            &lt;div style=\"text-align:center;margin:20px;clear:both\"&gt;\r\n                &lt;a href=\"http://localhost/mvc/public/order/track?order_id=1303152919\" style=\"background-color: #000;color:#f6f6f6;border:none;padding:7.5px 15px;text-decoration:none\"&gt;Kiểm tra đơn hàng&lt;/a&gt;\r\n            &lt;/div&gt;\r\n        &lt;/div&gt;\r\n    &lt;/div&gt;\r\n&lt;/body&gt;\r\n&lt;/html&gt;', NULL),
(28, 'test@gmail.com', 'VNH - Xác nhận đơn hàng', '&lt;!DOCTYPE html&gt;\r\n&lt;html lang=\"en\"&gt;\r\n&lt;head&gt;\r\n    &lt;meta charset=\"UTF-8\"&gt;\r\n    &lt;meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"&gt;\r\n    &lt;title&gt;Document&lt;/title&gt;\r\n    &lt;style&gt;\r\n\r\n        #container{\r\n            width: 50%;\r\n        }\r\n\r\n        *{\r\n            font-family: Arial, Helvetica, sans-serif;\r\n            color: black;\r\n        }\r\n\r\n        #confirm{\r\n            background-color: #f6f6f6;\r\n            padding: 20px 80px;\r\n            width: fit-content;\r\n            margin: 20px auto;\r\n            text-align: center;\r\n        }\r\n        @media screen and (max-width: 992px) {\r\n           #container{\r\n                width: 100% !important;\r\n           }\r\n        }\r\n\r\n        \r\n    &lt;/style&gt;\r\n&lt;/head&gt;\r\n&lt;body&gt;\r\n    &lt;div id=\"container\" style=\"width:50%;margin:0 auto\"&gt;\r\n        &lt;div id=\"logo\" style=\"text-align:center\"&gt;\r\n            &lt;!-- &lt;img style=\"width: 80px;\" src=\"\" alt=\"\"&gt; --&gt;\r\n            &lt;img style=\"width: 80px;\" src=\"https://vnhfashion.shop/client_assets/images/logo.png\" alt=\"\"&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"thank\"&gt;\r\n            &lt;div style=\"text-align:center;margin-top:10px\"&gt;Cám ơn &lt;span style=\"font-weight: 600;\"&gt;&lt;h1&gt;hello&lt;/h1&gt;&lt;/span&gt; đã mua hàng tại website của chúng tôi&lt;/div&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"confirm\"&gt;\r\n            &lt;!-- &lt;img src=\"\" alt=\"\"&gt; --&gt;\r\n            &lt;img src=\"https://ci3.googleusercontent.com/meips/ADKq_NayVTu3_FLF4gEvYg24-nxk0T6DbqtThdJMygcB4sg-Qh5SjC5UbJVfO8IEX3QbbjSzx7i21P3AG6Ou5LBucgsiWn2emwubSru_EOW7CwwZsqxLTqfppd2i-hr6-z9v1XBsKvFEADVIjkt56kwPeH7a9Ozm6WQsv4iANTTvqJ356-4O5TWj2VvmvpVqP0qXAYFqpKA2QMDzXBaNVts=s0-d-e1-ft#https://www.acfc.com.vn/static/version1709711284/frontend/Acfc/mobile/vi_VN/Magento_Email/images/acfc-email-images/order-confirmed.png\" alt=\"\"&gt;\r\n            &lt;div style=\"margin:5px 0\"&gt;VNH xác nhận đơn hàng&lt;/div&gt;\r\n            &lt;div style=\"font-weight: 600;\"&gt;#1303173234&lt;/div&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"order\"&gt;\r\n            &lt;div style=\"text-align:center;margin-bottom:10px\"&gt;THÔNG TIN ĐƠN HÀNG&lt;/div&gt;\r\n            &lt;table&gt;\r\n                &lt;tbody&gt;\r\n                    &lt;tr&gt;\r\n                        &lt;td style=\"width:50%;vertical-align:top\"&gt;\r\n                            &lt;div style=\"font-weight:600;margin-bottom:10px\"&gt;Thông tin giao hàng&lt;/div&gt;\r\n                            &lt;div style=\"font-size: 14px;color:black\"&gt;\r\n                                &lt;div&gt;&lt;h1&gt;hello&lt;/h1&gt; | 0911222333&lt;/div&gt;\r\n                                &lt;div&gt;\r\n                                    123 Liên khu 5-6, Phường Bình Trị Đông, Quận Bình Tân, Hồ Chí Minh                                \r\n                                &lt;/div&gt;\r\n                            &lt;/div&gt;\r\n                        &lt;/td&gt;\r\n                        &lt;td style=\"vertical-align: top;\"&gt;\r\n                            &lt;div style=\"font-weight:600;margin-bottom:10px\"&gt;Phương thức thanh toán&lt;/div&gt;\r\n                            &lt;div style=\"font-size: 14px;\"&gt;\r\n                                Thanh toán khi nhận hàng                            &lt;/div&gt;\r\n                        &lt;/td&gt;\r\n                    &lt;/tr&gt;\r\n                &lt;/tbody&gt;\r\n            &lt;/table&gt;\r\n            &lt;div style=\"text-align:center;margin:20px;clear:both\"&gt;\r\n                &lt;a href=\"http://localhost/mvc/public/order/track?order_id=1303173234\" style=\"background-color: #000;color:#f6f6f6;border:none;padding:7.5px 15px;text-decoration:none\"&gt;Kiểm tra đơn hàng&lt;/a&gt;\r\n            &lt;/div&gt;\r\n        &lt;/div&gt;\r\n    &lt;/div&gt;\r\n   \r\n&lt;/body&gt;\r\n&lt;/html&gt;', NULL),
(29, 'test@gmail.com', 'VNH - Hoàn thành đơn hàng', '&lt;!DOCTYPE html&gt;\r\n&lt;html lang=\"en\"&gt;\r\n&lt;head&gt;\r\n    &lt;meta charset=\"UTF-8\"&gt;\r\n    &lt;meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"&gt;\r\n    &lt;title&gt;Document&lt;/title&gt;\r\n    &lt;style&gt;\r\n\r\n        #container{\r\n            width: 50%;\r\n        }\r\n\r\n        *{\r\n            font-family: Arial, Helvetica, sans-serif;\r\n            color: black;\r\n        }\r\n\r\n        #confirm{\r\n            background-color: #f6f6f6;\r\n            padding: 20px 80px;\r\n            width: fit-content;\r\n            margin: 20px auto;\r\n            text-align: center;\r\n        }\r\n        @media screen and (max-width: 992px) {\r\n           #container{\r\n                width: 100% !important;\r\n           }\r\n        }\r\n\r\n        \r\n    &lt;/style&gt;\r\n&lt;/head&gt;\r\n&lt;body&gt;\r\n    &lt;div id=\"container\" style=\"width:50%;margin:0 auto\"&gt;\r\n        &lt;div id=\"logo\" style=\"text-align:center\"&gt;\r\n            &lt;!-- &lt;img style=\"width: 80px;\" src=\"\" alt=\"\"&gt; --&gt;\r\n            &lt;img style=\"width: 80px;\" src=\"https://vnhfashion.shop/client_assets/images/logo.png\" alt=\"\"&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"thank\"&gt;\r\n            &lt;div style=\"text-align:center;margin-top:10px\"&gt;Cám ơn &lt;span style=\"font-weight: 600;\"&gt;&amp;lt;h1&amp;gt;hello&amp;lt;/h1&amp;gt;&lt;/span&gt; đã mua hàng tại website của chúng tôi&lt;/div&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"confirm\"&gt;\r\n            &lt;!-- &lt;img src=\"\" alt=\"\"&gt; --&gt;\r\n            &lt;img src=\"https://ci3.googleusercontent.com/meips/ADKq_NayVTu3_FLF4gEvYg24-nxk0T6DbqtThdJMygcB4sg-Qh5SjC5UbJVfO8IEX3QbbjSzx7i21P3AG6Ou5LBucgsiWn2emwubSru_EOW7CwwZsqxLTqfppd2i-hr6-z9v1XBsKvFEADVIjkt56kwPeH7a9Ozm6WQsv4iANTTvqJ356-4O5TWj2VvmvpVqP0qXAYFqpKA2QMDzXBaNVts=s0-d-e1-ft#https://www.acfc.com.vn/static/version1709711284/frontend/Acfc/mobile/vi_VN/Magento_Email/images/acfc-email-images/order-confirmed.png\" alt=\"\"&gt;\r\n            &lt;div style=\"margin:5px 0\"&gt;VNH Hoàn thành đơn hàng&lt;/div&gt;\r\n            &lt;div style=\"font-weight: 600;\"&gt;#1303173234&lt;/div&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"order\"&gt;\r\n            &lt;div style=\"text-align:center;margin-bottom:10px\"&gt;THÔNG TIN ĐƠN HÀNG&lt;/div&gt;\r\n            &lt;table&gt;\r\n                &lt;tbody&gt;\r\n                    &lt;tr&gt;\r\n                        &lt;td style=\"width:50%;vertical-align:top\"&gt;\r\n                            &lt;div style=\"font-weight:600;margin-bottom:10px\"&gt;Thông tin giao hàng&lt;/div&gt;\r\n                            &lt;div style=\"font-size: 14px;color:black\"&gt;\r\n                                &lt;div&gt;&amp;lt;h1&amp;gt;hello&amp;lt;/h1&amp;gt; | 0911222333&lt;/div&gt;\r\n                                &lt;div&gt;\r\n                                    123 Liên khu 5-6, Phường Bình Trị Đông, Quận Bình Tân, Hồ Chí Minh                                 \r\n                                &lt;/div&gt;\r\n                            &lt;/div&gt;\r\n                        &lt;/td&gt;\r\n                        &lt;td style=\"vertical-align: top;\"&gt;\r\n                            &lt;div style=\"font-weight:600;margin-bottom:10px\"&gt;Phương thức thanh toán&lt;/div&gt;\r\n                            &lt;div style=\"font-size: 14px;\"&gt;\r\n                                cod                            &lt;/div&gt;\r\n                        &lt;/td&gt;\r\n                    &lt;/tr&gt;\r\n                &lt;/tbody&gt;\r\n            &lt;/table&gt;\r\n            &lt;div style=\"text-align:center;margin:20px;clear:both\"&gt;\r\n                &lt;a href=\"http://localhost/mvc/public/order/track?order_id=1303173234\" style=\"background-color: #000;color:#f6f6f6;border:none;padding:7.5px 15px;text-decoration:none\"&gt;Kiểm tra đơn hàng&lt;/a&gt;\r\n            &lt;/div&gt;\r\n        &lt;/div&gt;\r\n    &lt;/div&gt;\r\n&lt;/body&gt;\r\n&lt;/html&gt;', NULL),
(30, 'test@gmail.com', 'VNH - Hoàn thành đơn hàng', '&lt;!DOCTYPE html&gt;\r\n&lt;html lang=\"en\"&gt;\r\n&lt;head&gt;\r\n    &lt;meta charset=\"UTF-8\"&gt;\r\n    &lt;meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"&gt;\r\n    &lt;title&gt;Document&lt;/title&gt;\r\n    &lt;style&gt;\r\n\r\n        #container{\r\n            width: 50%;\r\n        }\r\n\r\n        *{\r\n            font-family: Arial, Helvetica, sans-serif;\r\n            color: black;\r\n        }\r\n\r\n        #confirm{\r\n            background-color: #f6f6f6;\r\n            padding: 20px 80px;\r\n            width: fit-content;\r\n            margin: 20px auto;\r\n            text-align: center;\r\n        }\r\n        @media screen and (max-width: 992px) {\r\n           #container{\r\n                width: 100% !important;\r\n           }\r\n        }\r\n\r\n        \r\n    &lt;/style&gt;\r\n&lt;/head&gt;\r\n&lt;body&gt;\r\n    &lt;div id=\"container\" style=\"width:50%;margin:0 auto\"&gt;\r\n        &lt;div id=\"logo\" style=\"text-align:center\"&gt;\r\n            &lt;!-- &lt;img style=\"width: 80px;\" src=\"\" alt=\"\"&gt; --&gt;\r\n            &lt;img style=\"width: 80px;\" src=\"https://vnhfashion.shop/client_assets/images/logo.png\" alt=\"\"&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"thank\"&gt;\r\n            &lt;div style=\"text-align:center;margin-top:10px\"&gt;Cám ơn &lt;span style=\"font-weight: 600;\"&gt;&amp;lt;h1&amp;gt;hello&amp;lt;/h1&amp;gt;&lt;/span&gt; đã mua hàng tại website của chúng tôi&lt;/div&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"confirm\"&gt;\r\n            &lt;!-- &lt;img src=\"\" alt=\"\"&gt; --&gt;\r\n            &lt;img src=\"https://ci3.googleusercontent.com/meips/ADKq_NayVTu3_FLF4gEvYg24-nxk0T6DbqtThdJMygcB4sg-Qh5SjC5UbJVfO8IEX3QbbjSzx7i21P3AG6Ou5LBucgsiWn2emwubSru_EOW7CwwZsqxLTqfppd2i-hr6-z9v1XBsKvFEADVIjkt56kwPeH7a9Ozm6WQsv4iANTTvqJ356-4O5TWj2VvmvpVqP0qXAYFqpKA2QMDzXBaNVts=s0-d-e1-ft#https://www.acfc.com.vn/static/version1709711284/frontend/Acfc/mobile/vi_VN/Magento_Email/images/acfc-email-images/order-confirmed.png\" alt=\"\"&gt;\r\n            &lt;div style=\"margin:5px 0\"&gt;VNH Hoàn thành đơn hàng&lt;/div&gt;\r\n            &lt;div style=\"font-weight: 600;\"&gt;#1303173234&lt;/div&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"order\"&gt;\r\n            &lt;div style=\"text-align:center;margin-bottom:10px\"&gt;THÔNG TIN ĐƠN HÀNG&lt;/div&gt;\r\n            &lt;table&gt;\r\n                &lt;tbody&gt;\r\n                    &lt;tr&gt;\r\n                        &lt;td style=\"width:50%;vertical-align:top\"&gt;\r\n                            &lt;div style=\"font-weight:600;margin-bottom:10px\"&gt;Thông tin giao hàng&lt;/div&gt;\r\n                            &lt;div style=\"font-size: 14px;color:black\"&gt;\r\n                                &lt;div&gt;&amp;lt;h1&amp;gt;hello&amp;lt;/h1&amp;gt; | 0911222333&lt;/div&gt;\r\n                                &lt;div&gt;\r\n                                    123 Liên khu 5-6, Phường Bình Trị Đông, Quận Bình Tân, Hồ Chí Minh                                 \r\n                                &lt;/div&gt;\r\n                            &lt;/div&gt;\r\n                        &lt;/td&gt;\r\n                        &lt;td style=\"vertical-align: top;\"&gt;\r\n                            &lt;div style=\"font-weight:600;margin-bottom:10px\"&gt;Phương thức thanh toán&lt;/div&gt;\r\n                            &lt;div style=\"font-size: 14px;\"&gt;\r\n                                cod                            &lt;/div&gt;\r\n                        &lt;/td&gt;\r\n                    &lt;/tr&gt;\r\n                &lt;/tbody&gt;\r\n            &lt;/table&gt;\r\n            &lt;div style=\"text-align:center;margin:20px;clear:both\"&gt;\r\n                &lt;a href=\"http://localhost/mvc/public/order/track?order_id=1303173234\" style=\"background-color: #000;color:#f6f6f6;border:none;padding:7.5px 15px;text-decoration:none\"&gt;Kiểm tra đơn hàng&lt;/a&gt;\r\n            &lt;/div&gt;\r\n        &lt;/div&gt;\r\n    &lt;/div&gt;\r\n&lt;/body&gt;\r\n&lt;/html&gt;', NULL);
INSERT INTO `email_queue` (`id`, `to_email`, `subject`, `message`, `sent_at`) VALUES
(31, 'test@gmail.com', 'VNH - Xác nhận đơn hàng', '&lt;!DOCTYPE html&gt;\r\n&lt;html lang=\"en\"&gt;\r\n&lt;head&gt;\r\n    &lt;meta charset=\"UTF-8\"&gt;\r\n    &lt;meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"&gt;\r\n    &lt;title&gt;Document&lt;/title&gt;\r\n    &lt;style&gt;\r\n\r\n        #container{\r\n            width: 50%;\r\n        }\r\n\r\n        *{\r\n            font-family: Arial, Helvetica, sans-serif;\r\n            color: black;\r\n        }\r\n\r\n        #confirm{\r\n            background-color: #f6f6f6;\r\n            padding: 20px 80px;\r\n            width: fit-content;\r\n            margin: 20px auto;\r\n            text-align: center;\r\n        }\r\n        @media screen and (max-width: 992px) {\r\n           #container{\r\n                width: 100% !important;\r\n           }\r\n        }\r\n\r\n        \r\n    &lt;/style&gt;\r\n&lt;/head&gt;\r\n&lt;body&gt;\r\n    &lt;div id=\"container\" style=\"width:50%;margin:0 auto\"&gt;\r\n        &lt;div id=\"logo\" style=\"text-align:center\"&gt;\r\n            &lt;!-- &lt;img style=\"width: 80px;\" src=\"\" alt=\"\"&gt; --&gt;\r\n            &lt;img style=\"width: 80px;\" src=\"https://vnhfashion.shop/client_assets/images/logo.png\" alt=\"\"&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"thank\"&gt;\r\n            &lt;div style=\"text-align:center;margin-top:10px\"&gt;Cám ơn &lt;span style=\"font-weight: 600;\"&gt;&lt;h1&gt;hello&lt;/h1&gt;&lt;/span&gt; đã mua hàng tại website của chúng tôi&lt;/div&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"confirm\"&gt;\r\n            &lt;!-- &lt;img src=\"\" alt=\"\"&gt; --&gt;\r\n            &lt;img src=\"https://ci3.googleusercontent.com/meips/ADKq_NayVTu3_FLF4gEvYg24-nxk0T6DbqtThdJMygcB4sg-Qh5SjC5UbJVfO8IEX3QbbjSzx7i21P3AG6Ou5LBucgsiWn2emwubSru_EOW7CwwZsqxLTqfppd2i-hr6-z9v1XBsKvFEADVIjkt56kwPeH7a9Ozm6WQsv4iANTTvqJ356-4O5TWj2VvmvpVqP0qXAYFqpKA2QMDzXBaNVts=s0-d-e1-ft#https://www.acfc.com.vn/static/version1709711284/frontend/Acfc/mobile/vi_VN/Magento_Email/images/acfc-email-images/order-confirmed.png\" alt=\"\"&gt;\r\n            &lt;div style=\"margin:5px 0\"&gt;VNH xác nhận đơn hàng&lt;/div&gt;\r\n            &lt;div style=\"font-weight: 600;\"&gt;#1303884423&lt;/div&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"order\"&gt;\r\n            &lt;div style=\"text-align:center;margin-bottom:10px\"&gt;THÔNG TIN ĐƠN HÀNG&lt;/div&gt;\r\n            &lt;table&gt;\r\n                &lt;tbody&gt;\r\n                    &lt;tr&gt;\r\n                        &lt;td style=\"width:50%;vertical-align:top\"&gt;\r\n                            &lt;div style=\"font-weight:600;margin-bottom:10px\"&gt;Thông tin giao hàng&lt;/div&gt;\r\n                            &lt;div style=\"font-size: 14px;color:black\"&gt;\r\n                                &lt;div&gt;&lt;h1&gt;hello&lt;/h1&gt; | 0911222333&lt;/div&gt;\r\n                                &lt;div&gt;\r\n                                    123 Liên khu 5-6, Phường Bình Trị Đông, Quận Bình Tân, Hồ Chí Minh                                \r\n                                &lt;/div&gt;\r\n                            &lt;/div&gt;\r\n                        &lt;/td&gt;\r\n                        &lt;td style=\"vertical-align: top;\"&gt;\r\n                            &lt;div style=\"font-weight:600;margin-bottom:10px\"&gt;Phương thức thanh toán&lt;/div&gt;\r\n                            &lt;div style=\"font-size: 14px;\"&gt;\r\n                                Thanh toán khi nhận hàng                            &lt;/div&gt;\r\n                        &lt;/td&gt;\r\n                    &lt;/tr&gt;\r\n                &lt;/tbody&gt;\r\n            &lt;/table&gt;\r\n            &lt;div style=\"text-align:center;margin:20px;clear:both\"&gt;\r\n                &lt;a href=\"http://localhost/mvc/public/order/track?order_id=1303884423\" style=\"background-color: #000;color:#f6f6f6;border:none;padding:7.5px 15px;text-decoration:none\"&gt;Kiểm tra đơn hàng&lt;/a&gt;\r\n            &lt;/div&gt;\r\n        &lt;/div&gt;\r\n    &lt;/div&gt;\r\n   \r\n&lt;/body&gt;\r\n&lt;/html&gt;', NULL),
(32, 'test@gmail.com', 'VNH - Xác nhận đơn hàng', '&lt;!DOCTYPE html&gt;\r\n&lt;html lang=\"en\"&gt;\r\n&lt;head&gt;\r\n    &lt;meta charset=\"UTF-8\"&gt;\r\n    &lt;meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"&gt;\r\n    &lt;title&gt;Document&lt;/title&gt;\r\n    &lt;style&gt;\r\n\r\n        #container{\r\n            width: 50%;\r\n        }\r\n\r\n        *{\r\n            font-family: Arial, Helvetica, sans-serif;\r\n            color: black;\r\n        }\r\n\r\n        #confirm{\r\n            background-color: #f6f6f6;\r\n            padding: 20px 80px;\r\n            width: fit-content;\r\n            margin: 20px auto;\r\n            text-align: center;\r\n        }\r\n        @media screen and (max-width: 992px) {\r\n           #container{\r\n                width: 100% !important;\r\n           }\r\n        }\r\n\r\n        \r\n    &lt;/style&gt;\r\n&lt;/head&gt;\r\n&lt;body&gt;\r\n    &lt;div id=\"container\" style=\"width:50%;margin:0 auto\"&gt;\r\n        &lt;div id=\"logo\" style=\"text-align:center\"&gt;\r\n            &lt;!-- &lt;img style=\"width: 80px;\" src=\"\" alt=\"\"&gt; --&gt;\r\n            &lt;img style=\"width: 80px;\" src=\"https://vnhfashion.shop/client_assets/images/logo.png\" alt=\"\"&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"thank\"&gt;\r\n            &lt;div style=\"text-align:center;margin-top:10px\"&gt;Cám ơn &lt;span style=\"font-weight: 600;\"&gt;&lt;h1&gt;hello&lt;/h1&gt;&lt;/span&gt; đã mua hàng tại website của chúng tôi&lt;/div&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"confirm\"&gt;\r\n            &lt;!-- &lt;img src=\"\" alt=\"\"&gt; --&gt;\r\n            &lt;img src=\"https://ci3.googleusercontent.com/meips/ADKq_NayVTu3_FLF4gEvYg24-nxk0T6DbqtThdJMygcB4sg-Qh5SjC5UbJVfO8IEX3QbbjSzx7i21P3AG6Ou5LBucgsiWn2emwubSru_EOW7CwwZsqxLTqfppd2i-hr6-z9v1XBsKvFEADVIjkt56kwPeH7a9Ozm6WQsv4iANTTvqJ356-4O5TWj2VvmvpVqP0qXAYFqpKA2QMDzXBaNVts=s0-d-e1-ft#https://www.acfc.com.vn/static/version1709711284/frontend/Acfc/mobile/vi_VN/Magento_Email/images/acfc-email-images/order-confirmed.png\" alt=\"\"&gt;\r\n            &lt;div style=\"margin:5px 0\"&gt;VNH xác nhận đơn hàng&lt;/div&gt;\r\n            &lt;div style=\"font-weight: 600;\"&gt;#1303682088&lt;/div&gt;\r\n        &lt;/div&gt;\r\n        &lt;div id=\"order\"&gt;\r\n            &lt;div style=\"text-align:center;margin-bottom:10px\"&gt;THÔNG TIN ĐƠN HÀNG&lt;/div&gt;\r\n            &lt;table&gt;\r\n                &lt;tbody&gt;\r\n                    &lt;tr&gt;\r\n                        &lt;td style=\"width:50%;vertical-align:top\"&gt;\r\n                            &lt;div style=\"font-weight:600;margin-bottom:10px\"&gt;Thông tin giao hàng&lt;/div&gt;\r\n                            &lt;div style=\"font-size: 14px;color:black\"&gt;\r\n                                &lt;div&gt;&lt;h1&gt;hello&lt;/h1&gt; | 0911222333&lt;/div&gt;\r\n                                &lt;div&gt;\r\n                                    123 Liên khu 5-6, Phường Bình Trị Đông, Quận Bình Tân, Hồ Chí Minh                                \r\n                                &lt;/div&gt;\r\n                            &lt;/div&gt;\r\n                        &lt;/td&gt;\r\n                        &lt;td style=\"vertical-align: top;\"&gt;\r\n                            &lt;div style=\"font-weight:600;margin-bottom:10px\"&gt;Phương thức thanh toán&lt;/div&gt;\r\n                            &lt;div style=\"font-size: 14px;\"&gt;\r\n                                Thanh toán khi nhận hàng                            &lt;/div&gt;\r\n                        &lt;/td&gt;\r\n                    &lt;/tr&gt;\r\n                &lt;/tbody&gt;\r\n            &lt;/table&gt;\r\n            &lt;div style=\"text-align:center;margin:20px;clear:both\"&gt;\r\n                &lt;a href=\"http://localhost/mvc/public/order/track?order_id=1303682088\" style=\"background-color: #000;color:#f6f6f6;border:none;padding:7.5px 15px;text-decoration:none\"&gt;Kiểm tra đơn hàng&lt;/a&gt;\r\n            &lt;/div&gt;\r\n        &lt;/div&gt;\r\n    &lt;/div&gt;\r\n   \r\n&lt;/body&gt;\r\n&lt;/html&gt;', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` int(11) NOT NULL,
  `payment_method` varchar(10) NOT NULL,
  `shipping_fee` int(11) DEFAULT 0,
  `coupon` int(11) DEFAULT 0,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `address` text NOT NULL,
  `note` varchar(255) DEFAULT '',
  `created_at` datetime DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL,
  `paid_status` varchar(50) NOT NULL DEFAULT 'Chưa thanh toán'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `p_id` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `p_price` int(11) NOT NULL,
  `p_size` varchar(20) DEFAULT NULL,
  `p_color_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_meta`
--

CREATE TABLE `order_meta` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) DEFAULT NULL,
  `meta_key` varchar(50) NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `point_history`
--

CREATE TABLE `point_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `order_total` int(11) NOT NULL,
  `point` int(11) NOT NULL,
  `action` tinyint(4) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` varchar(50) NOT NULL,
  `p_name` varchar(100) NOT NULL,
  `p_price` int(11) NOT NULL,
  `p_stock` int(11) DEFAULT 0,
  `p_discount` int(11) DEFAULT 0,
  `p_desc` text DEFAULT NULL,
  `dir` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `p_name`, `p_price`, `p_stock`, `p_discount`, `p_desc`, `dir`, `created_at`) VALUES
('VNH0622171271', 'ÁO THUN VẢI JERSEY SỢI DÀY CỔ THUYỀN NỮ', 98000, 0, 0, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters', 'a17241f8-dd23-4795-ac04-bad67d1d9512', '2024-02-08 09:42:27'),
('VNH1017764464', 'ĐẦM CÓ THỂ TÙY CHỈNH ONE SIZE', 638000, 0, 0, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters', '9bcf4e99-ef92-42a9-8ed1-1ca980f5fbae', '2024-02-08 09:09:40'),
('VNH1909392947', 'ÁO THUN HEAVY WEIGHT TAY NGẮN', 392000, 0, 0, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters', '23e19d3c-2421-4647-a5c2-11f9aece97ee', '2024-01-19 12:40:54'),
('VNH2183707082', 'QUẦN CHINO EASY NAM', 686000, 0, 0, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters', 'e0a7bf1b-9c77-4c6c-b793-c13346faa0af', '2024-01-19 12:40:54'),
('VNH2662017547', 'ÁO THUN NỮ CỔ TRÒN TAY NGẮN', 244000, 0, 0, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters', '86d9336d-bfa0-4b73-bbe5-4838b3a5d91f', '2024-02-08 12:39:35'),
('VNH4101910444', 'ÁO KHOÁC DÁNG RỘNG VẢI DENIM PHA GÒN', 1472000, 0, 0, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters', '7b6e4cc2-dd6f-45fe-b894-105ead94690c', '2024-01-28 10:34:23'),
('VNH4196975566', 'GIÀY LƯỜI CHỐNG BÁM NƯỚC - LESS TIRING', 588000, 0, 0, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters', '9edfd442-a4d5-4002-9110-857b239f3a6f', '2024-01-28 10:12:42'),
('VNH4461350769', 'ÁO THUN VẢI JERSEY COTTON ẤN ĐỘ CỔ TRÒN NỮ', 98000, 0, 0, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters', '55c60338-17f2-4bbf-8758-f309a63ed734', '2024-02-09 01:26:26'),
('VNH4470692725', 'NÓN BUCKET FLANNEL', 490000, 0, 0, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters', 'fbb3c5ee-462a-4b5d-bf33-4ce836d7b65d', '2024-01-19 13:55:04'),
('VNH5098536643', 'NÓN LƯỠI TRAI FLANNEL', 392000, 0, 0, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters', '8cb738bb-fcb7-4163-89d0-f1bacb01d6cb', '2024-01-19 14:20:38'),
('VNH5352416517', 'NÓN BUCKET VẢI PHA GÒN', 588000, 2, 0, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters', 'efe301f8-8d23-47ec-8576-42edc79aa440', '2024-01-19 13:21:18'),
('VNH5601796261', 'NÓN BUCKET TỪ SỢI TÁI CHẾ', 686000, 0, 0, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters', 'ae52b49c-02c4-470e-9788-fb703f569251', '2024-01-19 13:29:44'),
('VNH6727664101', 'ÁO THUN NỮ CỔ TRÒN TAY LIỀN', 244000, 0, 0, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters', '51a6b26e-9cad-4b5c-bfd5-94c34a012501', '2024-02-08 12:16:38'),
('VNH7950999534', 'ÁO SƠ MI COTTON', 490000, 0, 0, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters', '5da370c1-4bc4-45e1-ad5d-9094570ab26b', '2024-02-23 01:28:40'),
('VNH8716254967', 'ÁO SƠ MI TAY DÀI VẢI XÔ PHA GÒN', 784000, 0, 0, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters', '071a62fa-5143-467a-ae3e-7a5b65b41cf8', '2024-02-23 06:54:39'),
('VNH8927579351', 'NÓN BUCKET VẢI PHA GÒN', 588000, 99, 0, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters', 'fffd0173-45ee-4a4b-8ce2-fc255a36a3c1', '2024-01-19 12:40:54'),
('VNH9265602440', 'ÁO TUNIC CÓ THỂ TÙY CHỈNH ONE SIZE', 299000, 0, 0, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters', '22e1c6ff-5819-4ab6-837d-bf618b77534c', '2024-02-08 09:23:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `p_id` varchar(50) NOT NULL,
  `cat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_categories`
--

INSERT INTO `product_categories` (`id`, `p_id`, `cat_id`) VALUES
(3, 'VNH2183707082', 14),
(4, 'VNH2183707082', 108),
(12, 'VNH1909392947', 14),
(13, 'VNH1909392947', 107),
(14, 'VNH8927579351', 118),
(15, 'VNH8927579351', 119),
(18, 'VNH5352416517', 118),
(19, 'VNH5352416517', 119),
(20, 'VNH5601796261', 118),
(21, 'VNH5601796261', 119),
(22, 'VNH4470692725', 118),
(23, 'VNH4470692725', 119),
(24, 'VNH5098536643', 118),
(25, 'VNH5098536643', 119),
(26, 'VNH4196975566', 100),
(27, 'VNH4196975566', 120),
(28, 'VNH4101910444', 14),
(29, 'VNH4101910444', 114),
(30, 'VNH1017764464', 17),
(31, 'VNH1017764464', 121),
(32, 'VNH9265602440', 17),
(33, 'VNH9265602440', 121),
(34, 'VNH0622171271', 17),
(35, 'VNH0622171271', 121),
(36, 'VNH6727664101', 17),
(37, 'VNH6727664101', 121),
(38, 'VNH2662017547', 17),
(39, 'VNH2662017547', 121),
(40, 'VNH4461350769', 17),
(41, 'VNH4461350769', 121),
(42, 'VNH8716254967', 14),
(43, 'VNH8716254967', 109),
(44, 'VNH7950999534', 14),
(45, 'VNH7950999534', 109);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_colors`
--

CREATE TABLE `product_colors` (
  `id` int(11) NOT NULL,
  `p_id` varchar(50) NOT NULL,
  `color_image` varchar(100) NOT NULL,
  `color_name` varchar(50) NOT NULL,
  `price` int(11) DEFAULT 0,
  `stock` int(11) DEFAULT 0,
  `gallery_dir` varchar(100) NOT NULL,
  `active` enum('true','false') DEFAULT 'true'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_colors`
--

INSERT INTO `product_colors` (`id`, `p_id`, `color_image`, `color_name`, `price`, `stock`, `gallery_dir`, `active`) VALUES
(13, 'VNH1909392947', 'images/products/23e19d3c-2421-4647-a5c2-11f9aece97ee/colors/4550583096601_99_95.jpg', 'Đen', 0, 0, '3bafb6b7-a49b-48cc-9421-4d2303cbb295', 'true'),
(14, 'VNH1909392947', 'images/products/23e19d3c-2421-4647-a5c2-11f9aece97ee/colors/4550583098346_99_95.jpg', 'Xám', 0, 0, 'fb44adcc-6d11-41a3-8ab5-4d16f508a7eb', 'true'),
(16, 'VNH1909392947', 'images/products/23e19d3c-2421-4647-a5c2-11f9aece97ee/colors/4550583102371_99_95.jpg', 'Xanh hải quân', 0, 0, 'bc862696-7d56-4846-909c-5bc193f259e2', 'true'),
(17, 'VNH1909392947', 'images/products/23e19d3c-2421-4647-a5c2-11f9aece97ee/colors/4550583095635_99_95.jpg', 'Be', 0, 0, 'bce0830d-d869-487f-bd7d-7978b7b7e4e0', 'true'),
(18, 'VNH2183707082', 'images/products/e0a7bf1b-9c77-4c6c-b793-c13346faa0af/colors/4550512637448_99_95.jpg', 'ĐEN', 0, 0, 'c99348d9-7f99-45c9-b147-bfb2105ad598', 'true'),
(19, 'VNH2183707082', 'images/products/e0a7bf1b-9c77-4c6c-b793-c13346faa0af/colors/4550512637509_99_95.jpg', 'BE', 0, 0, '6ee63524-334e-4b60-a7d2-2d96094cd010', 'true'),
(20, 'VNH2183707082', 'images/products/e0a7bf1b-9c77-4c6c-b793-c13346faa0af/colors/4550512637561_99_95.jpg', 'XANH LÁ KAKI', 0, 0, 'e22d9872-a1d6-4e2b-bcb0-8faa3622baed', 'true'),
(29, 'VNH5601796261', 'images/products/ae52b49c-02c4-470e-9788-fb703f569251/colors/4550583104603_99_95.jpg', 'Trắng thô', 686000, 100, '8df74685-4c2a-4e4e-8b7e-e51d942cb598', 'true'),
(30, 'VNH5601796261', 'images/products/ae52b49c-02c4-470e-9788-fb703f569251/colors/4550583104610_99_95.jpg', 'Be', 686000, 100, 'f7ec26f8-f844-455e-8fd3-c0be7724de24', 'true'),
(31, 'VNH4470692725', 'images/products/fbb3c5ee-462a-4b5d-bf33-4ce836d7b65d/colors/4550583063313_99_95.jpg', 'Đen', 490000, 100, '7b25bd85-1ded-4610-ac07-a8da92b22b5f', 'true'),
(32, 'VNH4470692725', 'images/products/fbb3c5ee-462a-4b5d-bf33-4ce836d7b65d/colors/4550583063320_99_95.jpg', 'Trắng thô', 490000, 100, 'fabcf8f5-06cd-4650-a901-f010ce8a8b8d', 'true'),
(33, 'VNH5098536643', 'images/products/8cb738bb-fcb7-4163-89d0-f1bacb01d6cb/colors/4550583063252_99_95.jpg', 'Đen', 392000, 100, '936dae18-bd11-47c6-b51f-7d16022fdb21', 'true'),
(34, 'VNH5098536643', 'images/products/8cb738bb-fcb7-4163-89d0-f1bacb01d6cb/colors/4550583063269_99_95.jpg', 'Trắng thô', 392000, 99, 'd2a5aa73-ee53-4b4f-b247-204d4093cf20', 'true'),
(35, 'VNH5098536643', 'images/products/8cb738bb-fcb7-4163-89d0-f1bacb01d6cb/colors/4550583063276_99_95.jpg', 'Be đậm', 392000, 100, 'c259bb4a-8c62-45b0-b5f8-453fe7e240dc', 'true'),
(36, 'VNH5098536643', 'images/products/8cb738bb-fcb7-4163-89d0-f1bacb01d6cb/colors/4550583063283_99_95.jpg', 'Xanh dương', 392000, 100, 'b49a0fd8-82da-47b5-a11b-f3884b2f191f', 'true'),
(37, 'VNH4196975566', 'images/products/9edfd442-a4d5-4002-9110-857b239f3a6f/colors/4550512717997_99_95.jpg', 'Đen', 0, 0, 'dbe9c820-d43d-473b-9fff-72b3bb29e57d', 'true'),
(38, 'VNH4196975566', 'images/products/9edfd442-a4d5-4002-9110-857b239f3a6f/colors/4550512717928_99_95.jpg', 'Trắng ngà', 0, 0, 'c7eb34e3-631e-447f-ba58-38009b3395bc', 'true'),
(39, 'VNH1017764464', 'images/products/9bcf4e99-ef92-42a9-8ed1-1ca980f5fbae/colors/4550344396148_99_95.jpg', 'Xám đậm', 538000, 1, '5563dab8-fcbd-4bb2-8b63-7e67ef4f5c2d', 'true'),
(40, 'VNH1017764464', 'images/products/9bcf4e99-ef92-42a9-8ed1-1ca980f5fbae/colors/4550344396155_99_95.jpg', 'Nâu moca', 638000, 99, '65a544fb-dc8f-44e4-b7ff-bd57d9edb6b9', 'true'),
(41, 'VNH9265602440', 'images/products/22e1c6ff-5819-4ab6-837d-bf618b77534c/colors/4550344396117_99_95.jpg', 'Trắng', 299000, 0, 'f806e4b8-35c9-4a50-bf9a-fed5f7d74ac2', 'true'),
(42, 'VNH9265602440', 'images/products/22e1c6ff-5819-4ab6-837d-bf618b77534c/colors/4550344396124_99_95.jpg', 'Xám đậm', 299000, 100, '218f70a8-176a-41cb-bc99-57e4ea44da2b', 'true'),
(43, 'VNH9265602440', 'images/products/22e1c6ff-5819-4ab6-837d-bf618b77534c/colors/4550344396131_99_95.jpg', 'Nâu moca', 299000, 100, '95184b2a-2a40-4886-b668-00c4b0359171', 'true'),
(44, 'VNH0622171271', 'images/products/a17241f8-dd23-4795-ac04-bad67d1d9512/colors/4550344382950_99_95.jpg', 'Be nhạt', 0, 0, '155ef19a-8ea3-40c1-aaf1-63584ee6cd2f', 'true'),
(45, 'VNH0622171271', 'images/products/a17241f8-dd23-4795-ac04-bad67d1d9512/colors/4550344382974_99_95.jpg', 'Nâu khói', 0, 0, 'c737fd50-9985-4b70-a855-795507806936', 'true'),
(46, 'VNH6727664101', 'images/products/51a6b26e-9cad-4b5c-bfd5-94c34a012501/colors/4550583780388_99_95.jpg', 'Trắng', 0, 0, 'cf3698d7-50e4-45b4-95af-1e737f51d02f', 'true'),
(47, 'VNH6727664101', 'images/products/51a6b26e-9cad-4b5c-bfd5-94c34a012501/colors/4550583780562_99_95.jpg', 'Đen', 0, 0, '8573cec1-b0be-4d28-9b4f-12da5f045e06', 'true'),
(48, 'VNH6727664101', 'images/products/51a6b26e-9cad-4b5c-bfd5-94c34a012501/colors/4550583780623_99_95.jpg', 'Hồng', 0, 0, 'fcbf2e91-c050-44f9-9c4d-f0d38f68e354', 'true'),
(49, 'VNH6727664101', 'images/products/51a6b26e-9cad-4b5c-bfd5-94c34a012501/colors/4550583780685_99_95.jpg', 'Xanh lá cây', 0, 0, '7039b342-5f56-4b53-b099-bcc111324db5', 'true'),
(50, 'VNH6727664101', 'images/products/51a6b26e-9cad-4b5c-bfd5-94c34a012501/colors/4550583780746_99_95.jpg', 'Xanh dương', 0, 0, 'da9222a6-cb60-4265-8990-c1071ef65492', 'true'),
(51, 'VNH2662017547', 'images/products/86d9336d-bfa0-4b73-bbe5-4838b3a5d91f/colors/4550583758820_99_95.jpg', 'Trắng', 0, 0, 'a8b2e90f-762d-46a7-ab96-7030c7875c51', 'true'),
(52, 'VNH2662017547', 'images/products/86d9336d-bfa0-4b73-bbe5-4838b3a5d91f/colors/4550583758882_99_95.jpg', 'Xám', 0, 0, 'a50f45d0-ad0f-40ab-856f-9fef5e281ee8', 'true'),
(53, 'VNH2662017547', 'images/products/86d9336d-bfa0-4b73-bbe5-4838b3a5d91f/colors/4550583759001_99_95.jpg', 'Hồng', 0, 0, '1f0ba407-8b87-4eae-b9a4-5d8d2aabb915', 'true'),
(54, 'VNH2662017547', 'images/products/86d9336d-bfa0-4b73-bbe5-4838b3a5d91f/colors/4550583759063_99_95.jpg', 'Đỏ tía', 0, 0, '7b2b12a7-fdaf-4626-b2c2-bdcb3e030e81', 'true'),
(55, 'VNH2662017547', 'images/products/86d9336d-bfa0-4b73-bbe5-4838b3a5d91f/colors/4550583759124_99_95.jpg', 'Vàng khói', 0, 0, 'a9ab3257-715d-40dc-bebf-5eb8114e9573', 'true'),
(56, 'VNH2662017547', 'images/products/86d9336d-bfa0-4b73-bbe5-4838b3a5d91f/colors/4550583759186_99_95.jpg', 'Xanh lá sáng', 0, 0, 'de6452aa-6d5e-4300-8da8-523a77f1eadd', 'true'),
(57, 'VNH2662017547', 'images/products/86d9336d-bfa0-4b73-bbe5-4838b3a5d91f/colors/4550583759247_99_95.jpg', 'Xanh dương nhạt', 0, 0, 'fee8cff9-959c-4955-966a-98a8d7f4fdd7', 'true'),
(58, 'VNH2662017547', 'images/products/86d9336d-bfa0-4b73-bbe5-4838b3a5d91f/colors/4550583759308_99_95.jpg', 'Nâu', 0, 0, '571cfa4d-b99f-40a2-b794-455583760298', 'true'),
(59, 'VNH4461350769', 'images/products/55c60338-17f2-4bbf-8758-f309a63ed734/colors/4550344381670_99_95.jpg', 'Sọc trắng', 0, 0, '205876a2-3a6f-4f4e-a04d-e9f6d49b1c0b', 'true'),
(60, 'VNH4461350769', 'images/products/55c60338-17f2-4bbf-8758-f309a63ed734/colors/4550344381731_99_95.jpg', 'Sọc xám bạc nhạt', 0, 0, '64f427d9-5790-4b92-b8a8-476f3b6cf2f8', 'true'),
(61, 'VNH4461350769', 'images/products/55c60338-17f2-4bbf-8758-f309a63ed734/colors/4550344381915_99_95.jpg', 'Sọc xanh hải quân sẫm', 0, 0, '682c5631-760b-4c0c-8410-b21b998cdec8', 'true'),
(62, 'VNH8716254967', 'images/products/071a62fa-5143-467a-ae3e-7a5b65b41cf8/colors/4550583653293_99_95.jpg', 'Xám', 0, 0, '9f73bdc8-18fe-4f81-9e1f-a155dd78863d', 'true'),
(63, 'VNH8716254967', 'images/products/071a62fa-5143-467a-ae3e-7a5b65b41cf8/colors/4550583653354_99_95.jpg', 'Vàng nhạt', 0, 0, '02f5078f-d8a0-4ca0-97a1-0b004907f37e', 'true'),
(64, 'VNH8716254967', 'images/products/071a62fa-5143-467a-ae3e-7a5b65b41cf8/colors/4550583653477_99_95.jpg', 'Xanh đậm', 0, 0, 'd6ec28ba-da2e-494d-aaf7-bea82d63ea6d', 'true'),
(65, 'VNH8716254967', 'images/products/071a62fa-5143-467a-ae3e-7a5b65b41cf8/colors/4550583653415_99_95.jpg', 'Xanh dương nhạt', 0, 0, '5e002405-b513-4816-9ea3-f7ed814192ff', 'true'),
(66, 'VNH8716254967', 'images/products/071a62fa-5143-467a-ae3e-7a5b65b41cf8/colors/4550583653538_99_95.jpg', 'Xanh lá sọc nhạt', 0, 0, '5f006c16-e175-44a5-8198-6bfb2a9487a1', 'true'),
(67, 'VNH8716254967', 'images/products/071a62fa-5143-467a-ae3e-7a5b65b41cf8/colors/4550583653590_99_95.jpg', 'Xanh dương sọc nhạt', 0, 0, '694f9742-7aa9-4a86-a6f5-f996f695f746', 'true'),
(68, 'VNH7950999534', 'images/products/5da370c1-4bc4-45e1-ad5d-9094570ab26b/colors/4550583281465_99_95.jpg', 'Nâu sọc vuông', 0, 0, 'af32229d-7421-4686-b201-1526b28f6db4', 'true'),
(69, 'VNH7950999534', 'images/products/5da370c1-4bc4-45e1-ad5d-9094570ab26b/colors/4550583281540_99_95.jpg', 'Xanh sọc', 0, 0, 'd919bcbf-ddc6-43fa-97fc-a66eaa4f7656', 'true'),
(70, 'VNH7950999534', 'images/products/5da370c1-4bc4-45e1-ad5d-9094570ab26b/colors/4550583282271_99_95.jpg', 'xanh nhạt sọc vuông', 0, 0, '9f03e10e-e4b5-4ec6-b6a7-959ac28b5e9a', 'true'),
(71, 'VNH7950999534', 'images/products/5da370c1-4bc4-45e1-ad5d-9094570ab26b/colors/4550583283360_99_95.jpg', 'Xanh', 0, 0, 'bba63e67-d025-4561-95d4-7f0a8e98694b', 'true'),
(72, 'VNH7950999534', 'images/products/5da370c1-4bc4-45e1-ad5d-9094570ab26b/colors/4550583283582_99_95.jpg', 'Xám', 0, 0, '0e73910b-94f5-41ba-9d27-2f615d1c46a1', 'true'),
(73, 'VNH7950999534', 'images/products/5da370c1-4bc4-45e1-ad5d-9094570ab26b/colors/4550583283674_99_95.jpg', 'Xanh nhạt', 0, 0, 'c52b01b4-1cd4-4529-a88a-3f167889f4c1', 'true'),
(74, 'VNH7950999534', 'images/products/5da370c1-4bc4-45e1-ad5d-9094570ab26b/colors/4550583283698_99_95.jpg', 'Nâu sọc', 0, 0, '5c82cd90-b088-47b4-b2ca-491006dac23f', 'true'),
(75, 'VNH7950999534', 'images/products/5da370c1-4bc4-45e1-ad5d-9094570ab26b/colors/4550583283865_99_95.jpg', 'Nâu', 0, 0, 'de7d6548-76bc-4966-98d9-3f87ba21e84e', 'true'),
(76, 'VNH7950999534', 'images/products/5da370c1-4bc4-45e1-ad5d-9094570ab26b/colors/4550583283926_99_95.jpg', 'Xám trắng', 0, 0, '1add7db3-daea-4810-b6e7-831b810b78a1', 'true'),
(77, 'VNH7950999534', 'images/products/5da370c1-4bc4-45e1-ad5d-9094570ab26b/colors/4550583284824_99_95.jpg', 'Đen', 0, 0, '5a967fef-1106-49b0-ad35-53a5068f09fa', 'true');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_colors_sizes`
--

CREATE TABLE `product_colors_sizes` (
  `id` int(11) NOT NULL,
  `p_id` varchar(50) NOT NULL,
  `color_id` int(11) NOT NULL,
  `size` varchar(50) NOT NULL,
  `price` int(11) DEFAULT 0,
  `stock` int(11) DEFAULT 0,
  `active` enum('true','false') DEFAULT 'true'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_colors_sizes`
--

INSERT INTO `product_colors_sizes` (`id`, `p_id`, `color_id`, `size`, `price`, `stock`, `active`) VALUES
(27, 'VNH1909392947', 13, 'XS', 392000, 2, 'true'),
(28, 'VNH1909392947', 13, 'S', 392000, 0, 'true'),
(29, 'VNH1909392947', 13, 'M', 392000, 0, 'true'),
(30, 'VNH1909392947', 14, 'XS', 100000, 100, 'true'),
(31, 'VNH1909392947', 14, 'S', 100000, 100, 'true'),
(32, 'VNH1909392947', 14, 'M', 100000, 0, 'true'),
(42, 'VNH1909392947', 13, 'L', 392000, 1, 'true'),
(43, 'VNH1909392947', 14, 'L', 100000, 99, 'true'),
(46, 'VNH1909392947', 13, 'XL', 392000, 0, 'true'),
(47, 'VNH1909392947', 13, 'XXL', 392000, 0, 'true'),
(48, 'VNH1909392947', 14, 'XL', 100000, 111, 'true'),
(49, 'VNH1909392947', 14, 'XXL', 100000, 1, 'true'),
(50, 'VNH1909392947', 16, 'XS', 392000, 100, 'true'),
(51, 'VNH1909392947', 16, 'S', 392000, 100, 'true'),
(52, 'VNH1909392947', 16, 'M', 392000, 96, 'true'),
(53, 'VNH1909392947', 16, 'L', 392000, 98, 'true'),
(54, 'VNH1909392947', 16, 'XL', 392000, 0, 'true'),
(55, 'VNH1909392947', 16, 'XXL', 392000, 100, 'true'),
(56, 'VNH1909392947', 17, 'XS', 392000, 4, 'true'),
(57, 'VNH1909392947', 17, 'S', 392000, 0, 'true'),
(58, 'VNH1909392947', 17, 'M', 392000, 100, 'true'),
(59, 'VNH1909392947', 17, 'L', 392000, 99, 'true'),
(60, 'VNH1909392947', 17, 'XL', 392000, 100, 'true'),
(61, 'VNH1909392947', 17, 'XXL', 392000, 100, 'true'),
(62, 'VNH2183707082', 18, 'XS', 686000, 100, 'true'),
(63, 'VNH2183707082', 18, 'S', 686000, 100, 'true'),
(64, 'VNH2183707082', 18, 'M', 686000, 99, 'true'),
(65, 'VNH2183707082', 18, 'L', 686000, 101, 'true'),
(66, 'VNH2183707082', 18, 'XL', 686000, 100, 'true'),
(67, 'VNH2183707082', 18, 'XXL', 686000, 100, 'true'),
(72, 'VNH2183707082', 19, 'XS', 686000, 100, 'true'),
(73, 'VNH2183707082', 19, 'S', 686000, 100, 'true'),
(74, 'VNH2183707082', 19, 'M', 686000, 100, 'true'),
(75, 'VNH2183707082', 19, 'L', 686000, 100, 'true'),
(76, 'VNH2183707082', 19, 'XL', 686000, 100, 'true'),
(77, 'VNH2183707082', 19, 'XXL', 686000, 100, 'true'),
(82, 'VNH2183707082', 20, 'XS', 686000, 100, 'true'),
(83, 'VNH2183707082', 20, 'S', 686000, 100, 'true'),
(84, 'VNH2183707082', 20, 'M', 686000, 100, 'true'),
(85, 'VNH2183707082', 20, 'L', 686000, 100, 'true'),
(86, 'VNH2183707082', 20, 'XL', 686000, 100, 'true'),
(87, 'VNH2183707082', 20, 'XXL', 686000, 100, 'true'),
(103, 'VNH4196975566', 37, '22.0cm', 588000, 100, 'true'),
(104, 'VNH4196975566', 37, '22.5cm', 588000, 100, 'true'),
(105, 'VNH4196975566', 37, '22.3cm', 588000, 97, 'true'),
(106, 'VNH4196975566', 37, '23.5cm', 588000, 100, 'true'),
(107, 'VNH4196975566', 37, '24.0cm', 588000, 100, 'true'),
(108, 'VNH4196975566', 37, '24.5cm', 588000, 44, 'true'),
(109, 'VNH4196975566', 37, '25.0cm', 588000, 0, 'true'),
(110, 'VNH4196975566', 38, '22.0cm', 588000, 100, 'true'),
(111, 'VNH4196975566', 38, '22.5cm', 588000, 100, 'true'),
(112, 'VNH4196975566', 38, '22.3cm', 588000, 100, 'true'),
(113, 'VNH4196975566', 38, '23.5cm', 588000, 100, 'true'),
(114, 'VNH4196975566', 38, '24.0cm', 588000, 100, 'true'),
(115, 'VNH4196975566', 38, '24.5cm', 588000, 100, 'true'),
(116, 'VNH4196975566', 38, '25.0cm', 588000, 100, 'true'),
(117, 'VNH0622171271', 44, 'M-L', 98000, 99, 'true'),
(118, 'VNH0622171271', 44, 'XS-S', 98000, 100, 'true'),
(119, 'VNH0622171271', 45, 'M-L', 98000, 100, 'true'),
(120, 'VNH0622171271', 45, 'XS-S', 98000, 100, 'true'),
(121, 'VNH6727664101', 46, 'XS', 244000, 100, 'true'),
(122, 'VNH6727664101', 46, 'S', 244000, 100, 'true'),
(123, 'VNH6727664101', 46, 'M', 244000, 100, 'true'),
(124, 'VNH6727664101', 46, 'L', 244000, 100, 'true'),
(125, 'VNH6727664101', 46, 'XL', 244000, 100, 'true'),
(126, 'VNH6727664101', 46, 'XXL', 244000, 100, 'true'),
(127, 'VNH6727664101', 47, 'XS', 244000, 100, 'true'),
(128, 'VNH6727664101', 47, 'S', 244000, 100, 'true'),
(129, 'VNH6727664101', 47, 'M', 244000, 100, 'true'),
(130, 'VNH6727664101', 47, 'L', 244000, 100, 'true'),
(131, 'VNH6727664101', 47, 'XL', 244000, 100, 'true'),
(132, 'VNH6727664101', 47, 'XXL', 244000, 100, 'true'),
(133, 'VNH6727664101', 48, 'XS', 244000, 100, 'true'),
(134, 'VNH6727664101', 48, 'S', 244000, 100, 'true'),
(135, 'VNH6727664101', 48, 'M', 244000, 100, 'true'),
(136, 'VNH6727664101', 48, 'L', 244000, 100, 'true'),
(137, 'VNH6727664101', 48, 'XL', 244000, 100, 'true'),
(138, 'VNH6727664101', 48, 'XXL', 244000, 100, 'true'),
(139, 'VNH6727664101', 49, 'XS', 244000, 100, 'true'),
(140, 'VNH6727664101', 49, 'S', 244000, 100, 'true'),
(141, 'VNH6727664101', 49, 'M', 244000, 100, 'true'),
(142, 'VNH6727664101', 49, 'L', 244000, 100, 'true'),
(143, 'VNH6727664101', 49, 'XL', 244000, 100, 'true'),
(144, 'VNH6727664101', 49, 'XXL', 244000, 100, 'true'),
(145, 'VNH6727664101', 50, 'XS', 244000, 100, 'true'),
(146, 'VNH6727664101', 50, 'S', 244000, 100, 'true'),
(147, 'VNH6727664101', 50, 'M', 244000, 100, 'true'),
(148, 'VNH6727664101', 50, 'L', 244000, 100, 'true'),
(149, 'VNH6727664101', 50, 'XL', 244000, 100, 'true'),
(150, 'VNH6727664101', 50, 'XXL', 244000, 100, 'true'),
(151, 'VNH2662017547', 51, 'XS', 244000, 100, 'true'),
(152, 'VNH2662017547', 51, 'S', 244000, 100, 'true'),
(153, 'VNH2662017547', 51, 'M', 244000, 100, 'true'),
(154, 'VNH2662017547', 51, 'L', 244000, 100, 'true'),
(155, 'VNH2662017547', 51, 'XL', 244000, 100, 'true'),
(156, 'VNH2662017547', 51, 'XXL', 244000, 100, 'true'),
(157, 'VNH2662017547', 52, 'XS', 244000, 100, 'true'),
(158, 'VNH2662017547', 52, 'S', 244000, 100, 'true'),
(159, 'VNH2662017547', 52, 'M', 244000, 100, 'true'),
(160, 'VNH2662017547', 52, 'L', 244000, 100, 'true'),
(161, 'VNH2662017547', 52, 'XL', 244000, 100, 'true'),
(162, 'VNH2662017547', 52, 'XXL', 244000, 100, 'true'),
(163, 'VNH2662017547', 53, 'XS', 244000, 100, 'true'),
(164, 'VNH2662017547', 53, 'S', 244000, 100, 'true'),
(165, 'VNH2662017547', 53, 'M', 244000, 100, 'true'),
(166, 'VNH2662017547', 53, 'L', 244000, 100, 'true'),
(167, 'VNH2662017547', 53, 'XL', 244000, 100, 'true'),
(168, 'VNH2662017547', 53, 'XXL', 244000, 100, 'true'),
(169, 'VNH2662017547', 54, 'XS', 244000, 100, 'true'),
(170, 'VNH2662017547', 54, 'S', 244000, 100, 'true'),
(171, 'VNH2662017547', 54, 'M', 244000, 100, 'true'),
(172, 'VNH2662017547', 54, 'L', 244000, 100, 'true'),
(173, 'VNH2662017547', 54, 'XL', 244000, 100, 'true'),
(174, 'VNH2662017547', 54, 'XXL', 244000, 100, 'true'),
(175, 'VNH2662017547', 55, 'XS', 244000, 100, 'true'),
(176, 'VNH2662017547', 55, 'S', 244000, 100, 'true'),
(177, 'VNH2662017547', 55, 'M', 244000, 100, 'true'),
(178, 'VNH2662017547', 55, 'L', 244000, 100, 'true'),
(179, 'VNH2662017547', 55, 'XL', 244000, 100, 'true'),
(180, 'VNH2662017547', 55, 'XXL', 244000, 100, 'true'),
(181, 'VNH2662017547', 56, 'XS', 244000, 100, 'true'),
(182, 'VNH2662017547', 56, 'S', 244000, 100, 'true'),
(183, 'VNH2662017547', 56, 'M', 244000, 100, 'true'),
(184, 'VNH2662017547', 56, 'L', 244000, 100, 'true'),
(185, 'VNH2662017547', 56, 'XL', 244000, 100, 'true'),
(186, 'VNH2662017547', 56, 'XXL', 244000, 100, 'true'),
(187, 'VNH2662017547', 57, 'XS', 244000, 100, 'true'),
(188, 'VNH2662017547', 57, 'S', 244000, 100, 'true'),
(189, 'VNH2662017547', 57, 'M', 244000, 100, 'true'),
(190, 'VNH2662017547', 57, 'L', 244000, 99, 'true'),
(191, 'VNH2662017547', 57, 'XL', 244000, 100, 'true'),
(192, 'VNH2662017547', 57, 'XXL', 244000, 100, 'true'),
(193, 'VNH2662017547', 58, 'XS', 244000, 100, 'true'),
(194, 'VNH2662017547', 58, 'S', 244000, 100, 'true'),
(195, 'VNH2662017547', 58, 'M', 244000, 100, 'true'),
(196, 'VNH2662017547', 58, 'L', 244000, 99, 'true'),
(197, 'VNH2662017547', 58, 'XL', 244000, 100, 'true'),
(198, 'VNH2662017547', 58, 'XXL', 244000, 100, 'true'),
(199, 'VNH4461350769', 59, 'XS', 98000, 100, 'true'),
(200, 'VNH4461350769', 59, 'S', 98000, 100, 'true'),
(201, 'VNH4461350769', 59, 'M', 98000, 100, 'true'),
(202, 'VNH4461350769', 59, 'L', 98000, 100, 'true'),
(203, 'VNH4461350769', 59, 'XL', 98000, 100, 'true'),
(204, 'VNH4461350769', 59, 'XXL', 98000, 100, 'true'),
(205, 'VNH4461350769', 60, 'XS', 98000, 100, 'true'),
(206, 'VNH4461350769', 60, 'S', 98000, 100, 'true'),
(207, 'VNH4461350769', 60, 'M', 98000, 100, 'true'),
(208, 'VNH4461350769', 60, 'L', 98000, 100, 'true'),
(209, 'VNH4461350769', 60, 'XL', 98000, 100, 'true'),
(210, 'VNH4461350769', 60, 'XXL', 98000, 100, 'true'),
(211, 'VNH4461350769', 61, 'XS', 98000, 100, 'true'),
(212, 'VNH4461350769', 61, 'S', 98000, 100, 'true'),
(213, 'VNH4461350769', 61, 'M', 98000, 99, 'true'),
(214, 'VNH4461350769', 61, 'L', 98000, 100, 'true'),
(215, 'VNH4461350769', 61, 'XL', 98000, 100, 'true'),
(216, 'VNH4461350769', 61, 'XXL', 98000, 100, 'true'),
(217, 'VNH8716254967', 62, 'S', 784000, 99, 'true'),
(218, 'VNH8716254967', 62, 'XS', 784000, 99, 'true'),
(219, 'VNH8716254967', 62, 'M', 784000, 99, 'true'),
(220, 'VNH8716254967', 62, 'L', 784000, 96, 'true'),
(221, 'VNH8716254967', 62, 'XL', 784000, 100, 'true'),
(222, 'VNH8716254967', 62, 'XXL', 784000, 100, 'true'),
(223, 'VNH8716254967', 63, 'S', 784000, 100, 'true'),
(224, 'VNH8716254967', 63, 'XS', 784000, 100, 'true'),
(225, 'VNH8716254967', 63, 'M', 784000, 100, 'true'),
(226, 'VNH8716254967', 63, 'L', 784000, 100, 'true'),
(227, 'VNH8716254967', 63, 'XL', 784000, 100, 'true'),
(228, 'VNH8716254967', 63, 'XXL', 784000, 100, 'true'),
(229, 'VNH8716254967', 64, 'S', 784000, 100, 'true'),
(230, 'VNH8716254967', 64, 'XS', 784000, 100, 'true'),
(231, 'VNH8716254967', 64, 'M', 784000, 100, 'true'),
(232, 'VNH8716254967', 64, 'L', 784000, 98, 'true'),
(233, 'VNH8716254967', 64, 'XL', 784000, 100, 'true'),
(234, 'VNH8716254967', 64, 'XXL', 784000, 100, 'true'),
(235, 'VNH8716254967', 65, 'S', 784000, 100, 'true'),
(236, 'VNH8716254967', 65, 'XS', 784000, 100, 'true'),
(237, 'VNH8716254967', 65, 'M', 784000, 100, 'true'),
(238, 'VNH8716254967', 65, 'L', 784000, 100, 'true'),
(239, 'VNH8716254967', 65, 'XL', 784000, 100, 'true'),
(240, 'VNH8716254967', 65, 'XXL', 784000, 100, 'true'),
(241, 'VNH8716254967', 66, 'S', 784000, 100, 'true'),
(242, 'VNH8716254967', 66, 'XS', 784000, 100, 'true'),
(243, 'VNH8716254967', 66, 'M', 784000, 100, 'true'),
(244, 'VNH8716254967', 66, 'L', 784000, 100, 'true'),
(245, 'VNH8716254967', 66, 'XL', 784000, 100, 'true'),
(246, 'VNH8716254967', 66, 'XXL', 784000, 100, 'true'),
(247, 'VNH8716254967', 67, 'S', 784000, 100, 'true'),
(248, 'VNH8716254967', 67, 'XS', 784000, 100, 'true'),
(249, 'VNH8716254967', 67, 'M', 784000, 100, 'true'),
(250, 'VNH8716254967', 67, 'L', 784000, 100, 'true'),
(251, 'VNH8716254967', 67, 'XL', 784000, 100, 'true'),
(252, 'VNH8716254967', 67, 'XXL', 784000, 100, 'true'),
(253, 'VNH7950999534', 68, 'XS', 490000, 100, 'true'),
(254, 'VNH7950999534', 68, 'S', 490000, 100, 'true'),
(255, 'VNH7950999534', 68, 'M', 490000, 100, 'true'),
(256, 'VNH7950999534', 68, 'L', 490000, 99, 'true'),
(257, 'VNH7950999534', 68, 'XL', 490000, 100, 'true'),
(258, 'VNH7950999534', 68, 'XXL', 490000, 99, 'true'),
(259, 'VNH7950999534', 69, 'XS', 490000, 100, 'true'),
(260, 'VNH7950999534', 69, 'S', 490000, 100, 'true'),
(261, 'VNH7950999534', 69, 'M', 490000, 100, 'true'),
(262, 'VNH7950999534', 69, 'L', 490000, 100, 'true'),
(263, 'VNH7950999534', 69, 'XL', 490000, 100, 'true'),
(264, 'VNH7950999534', 69, 'XXL', 490000, 100, 'true'),
(265, 'VNH7950999534', 70, 'XS', 490000, 100, 'true'),
(266, 'VNH7950999534', 70, 'S', 490000, 100, 'true'),
(267, 'VNH7950999534', 70, 'M', 490000, 100, 'true'),
(268, 'VNH7950999534', 70, 'L', 490000, 100, 'true'),
(269, 'VNH7950999534', 70, 'XL', 490000, 100, 'true'),
(270, 'VNH7950999534', 70, 'XXL', 490000, 100, 'true'),
(271, 'VNH7950999534', 71, 'XS', 490000, 100, 'true'),
(272, 'VNH7950999534', 71, 'S', 490000, 100, 'true'),
(273, 'VNH7950999534', 71, 'M', 490000, 100, 'true'),
(274, 'VNH7950999534', 71, 'L', 490000, 100, 'true'),
(275, 'VNH7950999534', 71, 'XL', 490000, 100, 'true'),
(276, 'VNH7950999534', 71, 'XXL', 490000, 100, 'true'),
(277, 'VNH7950999534', 72, 'XS', 490000, 100, 'true'),
(278, 'VNH7950999534', 72, 'S', 490000, 100, 'true'),
(279, 'VNH7950999534', 72, 'M', 490000, 100, 'true'),
(280, 'VNH7950999534', 72, 'L', 490000, 100, 'true'),
(281, 'VNH7950999534', 72, 'XL', 490000, 100, 'true'),
(282, 'VNH7950999534', 72, 'XXL', 490000, 100, 'true'),
(283, 'VNH7950999534', 73, 'XS', 490000, 100, 'true'),
(284, 'VNH7950999534', 73, 'S', 490000, 100, 'true'),
(285, 'VNH7950999534', 73, 'M', 490000, 100, 'true'),
(286, 'VNH7950999534', 73, 'L', 490000, 100, 'true'),
(287, 'VNH7950999534', 73, 'XL', 490000, 100, 'true'),
(288, 'VNH7950999534', 73, 'XXL', 490000, 100, 'true'),
(289, 'VNH7950999534', 74, 'XS', 490000, 100, 'true'),
(290, 'VNH7950999534', 74, 'S', 490000, 100, 'true'),
(291, 'VNH7950999534', 74, 'M', 490000, 100, 'true'),
(292, 'VNH7950999534', 74, 'L', 490000, 100, 'true'),
(293, 'VNH7950999534', 74, 'XL', 490000, 100, 'true'),
(294, 'VNH7950999534', 74, 'XXL', 490000, 100, 'true'),
(295, 'VNH7950999534', 75, 'XS', 490000, 100, 'true'),
(296, 'VNH7950999534', 75, 'S', 490000, 100, 'true'),
(297, 'VNH7950999534', 75, 'M', 490000, 100, 'true'),
(298, 'VNH7950999534', 75, 'L', 490000, 100, 'true'),
(299, 'VNH7950999534', 75, 'XL', 490000, 100, 'true'),
(300, 'VNH7950999534', 75, 'XXL', 490000, 100, 'true'),
(301, 'VNH7950999534', 76, 'XS', 490000, 100, 'true'),
(302, 'VNH7950999534', 76, 'S', 490000, 100, 'true'),
(303, 'VNH7950999534', 76, 'M', 490000, 100, 'true'),
(304, 'VNH7950999534', 76, 'L', 490000, 100, 'true'),
(305, 'VNH7950999534', 76, 'XL', 490000, 100, 'true'),
(306, 'VNH7950999534', 76, 'XXL', 490000, 100, 'true'),
(307, 'VNH7950999534', 77, 'XS', 490000, 100, 'true'),
(308, 'VNH7950999534', 77, 'S', 490000, 100, 'true'),
(309, 'VNH7950999534', 77, 'M', 490000, 100, 'true'),
(310, 'VNH7950999534', 77, 'L', 490000, 99, 'true'),
(311, 'VNH7950999534', 77, 'XL', 490000, 95, 'true'),
(312, 'VNH7950999534', 77, 'XXL', 490000, 100, 'true');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_sizes`
--

CREATE TABLE `product_sizes` (
  `id` int(11) NOT NULL,
  `p_id` varchar(50) NOT NULL,
  `size` varchar(50) NOT NULL,
  `price` int(11) DEFAULT 0,
  `stock` int(11) DEFAULT 0,
  `active` enum('true','false') DEFAULT 'true'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_sizes`
--

INSERT INTO `product_sizes` (`id`, `p_id`, `size`, `price`, `stock`, `active`) VALUES
(9, 'VNH4101910444', 'XS', 1200000, 100, 'true'),
(10, 'VNH4101910444', 'S', 1472000, 100, 'true'),
(11, 'VNH4101910444', 'M', 1472000, 0, 'true'),
(12, 'VNH4101910444', 'L', 1472000, 97, 'true'),
(13, 'VNH4101910444', 'XL', 1472000, 100, 'true'),
(14, 'VNH4101910444', 'XXL', 1472000, 99, 'true');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `login_key` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(250) NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `role` enum('user','admin','guest') DEFAULT 'user',
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `login_key`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `created_at`) VALUES
(1, 'Nhật Hòa', 'nhathoa', 'nhathoa512@gmail.com', NULL, '$2y$10$FZ234ttrKJwKW/pzGXi4DO2KdqgmZwNLpK6CQ.CRMIqfhWohYtVla', NULL, 'admin', '2024-03-03'),
(21, 'Test', 'test@gmail.com', 'test@gmail.com', '2024-03-08 15:29:27', '$2y$10$PVvZBCOwElNUttlCJcvTqODqrukgGXVtGxjSHwBZl.jNlJGxfzURm', NULL, 'user', '2024-03-08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_meta`
--

CREATE TABLE `user_meta` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `meta_key` varchar(50) NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_meta`
--

INSERT INTO `user_meta` (`id`, `user_id`, `meta_key`, `meta_value`) VALUES
(70, 21, 'point', '0'),
(71, 21, 'total_spend', '0'),
(72, 21, 'rank', 'member');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wish_list`
--

CREATE TABLE `wish_list` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `p_id` varchar(50) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `coupon_usage`
--
ALTER TABLE `coupon_usage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Chỉ mục cho bảng `email_queue`
--
ALTER TABLE `email_queue`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `p_id` (`p_id`),
  ADD KEY `p_color_id` (`p_color_id`);

--
-- Chỉ mục cho bảng `order_meta`
--
ALTER TABLE `order_meta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_index` (`email`);

--
-- Chỉ mục cho bảng `point_history`
--
ALTER TABLE `point_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `p_id` (`p_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Chỉ mục cho bảng `product_colors`
--
ALTER TABLE `product_colors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `p_id` (`p_id`);

--
-- Chỉ mục cho bảng `product_colors_sizes`
--
ALTER TABLE `product_colors_sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `p_id` (`p_id`),
  ADD KEY `color_id` (`color_id`);

--
-- Chỉ mục cho bảng `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `p_id` (`p_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login_key` (`login_key`);

--
-- Chỉ mục cho bảng `user_meta`
--
ALTER TABLE `user_meta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `wish_list`
--
ALTER TABLE `wish_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `p_id` (`p_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT cho bảng `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `coupon_usage`
--
ALTER TABLE `coupon_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `email_queue`
--
ALTER TABLE `email_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT cho bảng `order_meta`
--
ALTER TABLE `order_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `point_history`
--
ALTER TABLE `point_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT cho bảng `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT cho bảng `product_colors`
--
ALTER TABLE `product_colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT cho bảng `product_colors_sizes`
--
ALTER TABLE `product_colors_sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=340;

--
-- AUTO_INCREMENT cho bảng `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `user_meta`
--
ALTER TABLE `user_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT cho bảng `wish_list`
--
ALTER TABLE `wish_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `coupon_usage`
--
ALTER TABLE `coupon_usage`
  ADD CONSTRAINT `coupon_usage_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `coupon_usage_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `coupon_usage_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`p_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`p_color_id`) REFERENCES `product_colors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `order_meta`
--
ALTER TABLE `order_meta`
  ADD CONSTRAINT `order_meta_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `point_history`
--
ALTER TABLE `point_history`
  ADD CONSTRAINT `point_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `point_history_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_categories_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `product_colors`
--
ALTER TABLE `product_colors`
  ADD CONSTRAINT `product_colors_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `product_colors_sizes`
--
ALTER TABLE `product_colors_sizes`
  ADD CONSTRAINT `product_colors_sizes_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_colors_sizes_ibfk_2` FOREIGN KEY (`color_id`) REFERENCES `product_colors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD CONSTRAINT `product_sizes_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `user_meta`
--
ALTER TABLE `user_meta`
  ADD CONSTRAINT `user_meta_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `wish_list`
--
ALTER TABLE `wish_list`
  ADD CONSTRAINT `wish_list_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wish_list_ibfk_2` FOREIGN KEY (`p_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
