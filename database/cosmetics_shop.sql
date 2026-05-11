-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th5 11, 2026 lúc 06:56 AM
-- Phiên bản máy phục vụ: 9.1.0
-- Phiên bản PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `cosmetics_shop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin','staff') DEFAULT 'admin',
  `avatar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `role`, `avatar`, `is_active`, `last_login`, `created_at`) VALUES
(1, 'Super Admin', 'admin@beautyshop.vn', '$2y$10$oSCI664XvRxalsSM10zK7Owa7go1.tRGUK9o4IDWpAsF42tmpkco.', 'superadmin', NULL, 1, '2026-05-10 21:58:29', '2026-05-10 08:09:14');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banners`
--

DROP TABLE IF EXISTS `banners`;
CREATE TABLE IF NOT EXISTS `banners` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `position` enum('hero','promo','sidebar') DEFAULT 'hero',
  `sort_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `banners`
--

INSERT INTO `banners` (`id`, `title`, `subtitle`, `image`, `link`, `position`, `sort_order`, `is_active`, `starts_at`, `ends_at`, `created_at`) VALUES
(1, 'Bộ Sưu Tập Mùa Hè 2025', 'Rạng rỡ từng khoảnh khắc với màu sắc tươi tắn', 'banners/banner1.jpg', '/products', 'hero', 1, 1, NULL, NULL, '2026-05-10 08:09:15'),
(2, 'Sale Đến 50% Skincare', 'Ưu đãi có hạn - Đừng bỏ lỡ!', 'banners/banner2.jpg', '/products?category=cham-soc-da-mat', 'hero', 2, 1, NULL, NULL, '2026-05-10 08:09:15'),
(3, 'Hàng Mới Về Mỗi Ngày', 'Khám phá ngay những sản phẩm hot nhất', 'banners/banner3.jpg', '/products?sort=newest', 'hero', 3, 1, NULL, NULL, '2026-05-10 08:09:15'),
(5, 'Thoải Mái Mua Sắm', 'HieuIdol', 'banners/img_6a015b0aa8b2a1.91191523.jpeg', '', 'hero', 4, 1, NULL, NULL, '2026-05-11 04:28:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brands`
--

DROP TABLE IF EXISTS `brands`;
CREATE TABLE IF NOT EXISTS `brands` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text,
  `logo` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `description`, `logo`, `website`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'L\'Oréal Paris', 'loreal-paris', NULL, NULL, 'https://loreal-paris.vn', 1, '2026-05-10 08:09:15', '2026-05-10 08:09:15'),
(2, 'Innisfree', 'innisfree', NULL, NULL, 'https://innisfree.vn', 1, '2026-05-10 08:09:15', '2026-05-10 08:09:15'),
(3, 'The Ordinary', 'the-ordinary', NULL, NULL, 'https://theordinary.com', 1, '2026-05-10 08:09:15', '2026-05-10 08:09:15'),
(4, 'Laneige', 'laneige', NULL, NULL, 'https://laneige.com', 1, '2026-05-10 08:09:15', '2026-05-10 08:09:15'),
(5, 'Maybelline', 'maybelline', NULL, NULL, 'https://maybelline.vn', 1, '2026-05-10 08:09:15', '2026-05-10 08:09:15'),
(6, 'SK-II', 'skii', NULL, NULL, 'https://skii.com', 1, '2026-05-10 08:09:15', '2026-05-10 08:09:15'),
(7, 'Cetaphil', 'cetaphil', NULL, NULL, 'https://cetaphil.vn', 1, '2026-05-10 08:09:15', '2026-05-10 08:09:15'),
(8, 'La Roche-Posay', 'la-roche-posay', NULL, NULL, 'https://laroche-posay.vn', 1, '2026-05-10 08:09:15', '2026-05-10 08:09:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `carts`
--

DROP TABLE IF EXISTS `carts`;
CREATE TABLE IF NOT EXISTS `carts` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_session` (`session_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_slug` (`slug`),
  KEY `idx_parent` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Chăm Sóc Da Mặt', 'cham-soc-da-mat', 'Sản phẩm chăm sóc da mặt cao cấp', NULL, NULL, 1, 1, '2026-05-10 08:09:15', '2026-05-10 08:09:15'),
(2, 'Son Môi', 'son-moi', 'Son môi đa dạng màu sắc', NULL, NULL, 2, 1, '2026-05-10 08:09:15', '2026-05-10 08:09:15'),
(3, 'Mắt', 'mat', 'Mascara, kẻ mắt, phấn mắt', NULL, NULL, 3, 1, '2026-05-10 08:09:15', '2026-05-10 08:09:15'),
(4, 'Nền & Che Khuyết Điểm', 'nen-che-khuyet-diem', 'Kem nền, phấn phủ, che khuyết điểm', NULL, NULL, 4, 1, '2026-05-10 08:09:15', '2026-05-10 08:09:15'),
(5, 'Nước Hoa', 'nuoc-hoa', 'Nước hoa cao cấp', NULL, NULL, 5, 1, '2026-05-10 08:09:15', '2026-05-10 08:09:15'),
(6, 'Chăm Sóc Tóc', 'cham-soc-toc', 'Dầu gội, dầu xả, serum tóc', NULL, NULL, 6, 1, '2026-05-10 08:09:15', '2026-05-10 08:09:15'),
(9, 'Dầu Gội Đầu', 'dau-gi-au', 'Gội sạch gầu', NULL, NULL, 7, 1, '2026-05-11 03:31:43', '2026-05-11 03:31:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `type` enum('percent','fixed') DEFAULT 'percent',
  `value` decimal(10,2) NOT NULL,
  `min_order` decimal(12,2) DEFAULT '0.00',
  `max_discount` decimal(12,2) DEFAULT NULL,
  `used_count` int DEFAULT '0',
  `max_use` int DEFAULT NULL,
  `user_id` int UNSIGNED DEFAULT NULL COMMENT 'NULL = all users',
  `starts_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `idx_code` (`code`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `name`, `type`, `value`, `min_order`, `max_discount`, `used_count`, `max_use`, `user_id`, `starts_at`, `expires_at`, `is_active`, `created_at`) VALUES
(1, 'WELCOME10', 'Chào mừng thành viên mới', 'percent', 10.00, 200000.00, NULL, 0, 1000, NULL, NULL, NULL, 1, '2026-05-10 08:09:15'),
(2, 'SALE50K', 'Giảm 50K đơn từ 500K', 'fixed', 50000.00, 500000.00, NULL, 0, 500, NULL, NULL, NULL, 1, '2026-05-10 08:09:15'),
(3, 'VIP20', 'VIP giảm 20%', 'percent', 20.00, 1000000.00, NULL, 0, 100, NULL, NULL, NULL, 1, '2026-05-10 08:09:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_code` varchar(30) NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `coupon_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(191) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `note` text,
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(12,2) DEFAULT '0.00',
  `shipping_fee` decimal(10,2) DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_method` enum('cod','bank','momo') DEFAULT 'cod',
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `status` enum('pending','confirmed','processing','shipping','delivered','cancelled') DEFAULT 'pending',
  `cancel_reason` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_code` (`order_code`),
  KEY `idx_order_code` (`order_code`),
  KEY `idx_user` (`user_id`),
  KEY `idx_status` (`status`),
  KEY `coupon_id` (`coupon_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `user_id`, `coupon_id`, `name`, `email`, `phone`, `address`, `city`, `district`, `ward`, `note`, `subtotal`, `discount`, `shipping_fee`, `total`, `payment_method`, `payment_status`, `status`, `cancel_reason`, `created_at`, `updated_at`) VALUES
(7, 'ORD-E763EC-260510', 2, NULL, 'Nguyễn Ngọc Bảo Trân', 'baotran1234@gmail.com', '012345678', 'TPHCM', 'TPHCM', 'Quận 8', NULL, 'Giao nhanh giúp mình', 359000.00, 0.00, 30000.00, 389000.00, 'cod', 'pending', 'delivered', '', '2026-05-10 14:57:50', '2026-05-11 03:06:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `subtotal` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `name`, `thumbnail`, `price`, `quantity`, `subtotal`) VALUES
(6, 7, 1, 'Serum Dưỡng Trắng Innisfree Jeju Orchid', 'products/innisfree.png', 359000.00, 1, 359000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int UNSIGNED NOT NULL,
  `brand_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `description` text,
  `ingredients` text,
  `how_to_use` text,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `sale_price` decimal(12,2) DEFAULT NULL,
  `stock` int DEFAULT '0',
  `weight` decimal(8,2) DEFAULT NULL COMMENT 'gram',
  `thumbnail` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `is_new` tinyint(1) DEFAULT '1',
  `is_active` tinyint(1) DEFAULT '1',
  `views` int DEFAULT '0',
  `sold` int DEFAULT '0',
  `rating` decimal(3,2) DEFAULT '0.00',
  `review_count` int DEFAULT '0',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_desc` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `sku` (`sku`),
  KEY `idx_slug` (`slug`),
  KEY `idx_category` (`category_id`),
  KEY `idx_brand` (`brand_id`),
  KEY `idx_featured` (`is_featured`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `name`, `slug`, `sku`, `description`, `ingredients`, `how_to_use`, `price`, `sale_price`, `stock`, `weight`, `thumbnail`, `is_featured`, `is_new`, `is_active`, `views`, `sold`, `rating`, `review_count`, `meta_title`, `meta_desc`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Serum Dưỡng Trắng Innisfree Jeju Orchid', 'serum-duong-trang-innisfree-jeju-orchid', 'INF-SER-001', 'Serum dưỡng trắng chiết xuất hoa lan Jeju, cải thiện đều màu da và giảm thâm nám hiệu quả.', 'Chiết suất từ 100% thiên nhiên', 'Xài vào buổi tối sau khi đi ngủ', 450000.00, 359000.00, 99, NULL, 'products/innisfree.png', 1, 1, 1, 6, 1, 4.80, 124, NULL, NULL, '2026-05-10 08:09:15', '2026-05-10 14:57:50'),
(2, 1, 3, 'The Ordinary Niacinamide 10% + Zinc 1%', 'the-ordinary-niacinamide', 'ORD-NIA-001', 'Serum niacinamide nồng độ cao giúp thu nhỏ lỗ chân lông, kiểm soát dầu và cải thiện kết cấu da.', NULL, NULL, 280000.00, 210000.00, 200, NULL, 'products/ordinary.png', 1, 1, 1, 2, 0, 4.70, 98, NULL, NULL, '2026-05-10 08:09:15', '2026-05-10 13:46:58'),
(3, 2, 1, 'Son Môi L\'Oréal Paris Color Riche', 'son-moi-loreal-color-riche', 'LOR-LIP-001', 'Son lì với 40 sắc màu, dưỡng ẩm và bền màu suốt 8 tiếng.', NULL, NULL, 180000.00, 145000.00, 150, NULL, 'products/loreal.png', 1, 1, 1, 0, 0, 4.50, 67, NULL, NULL, '2026-05-10 08:09:15', '2026-05-10 09:17:40'),
(4, 4, 5, 'Kem Nền Maybelline Fit Me Matte', 'kem-nen-maybelline-fit-me', 'MAY-FND-001', 'Kem nền lì kiểm soát dầu suốt ngày, 40 tông màu phù hợp mọi loại da.', NULL, NULL, 220000.00, 185000.00, 80, NULL, 'products/maybelline.png', 0, 1, 1, 0, 0, 4.40, 45, NULL, NULL, '2026-05-10 08:09:15', '2026-05-10 09:17:40'),
(6, 1, 6, 'SK-II Facial Treatment Essence', 'skii-facial-treatment-essence', 'SKI-ESS-001', 'Nước thần SK-II nổi tiếng thế giới với 90% Pitera giúp da sáng mịn và trẻ hóa.', NULL, NULL, 2800000.00, 2450000.00, 30, NULL, 'products/skii.png', 1, 1, 1, 1, 0, 4.90, 312, NULL, NULL, '2026-05-10 08:09:15', '2026-05-10 13:47:00'),
(9, 9, 7, 'Dầu Gội Đầu', 'dau-gi-au', 'SP001', 'Gội sạch tóc', '100% thiên nhiên', 'Gội 1 ngày 1 lần', 10000000.00, 8000000.00, 1000000, NULL, 'products/img_6a015ae48dd5f8.50082753.jpeg', 1, 1, 1, 0, 0, 0.00, 0, NULL, NULL, '2026-05-11 03:39:46', '2026-05-11 04:41:14');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_images`
--

DROP TABLE IF EXISTS `product_images`;
CREATE TABLE IF NOT EXISTS `product_images` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `is_main` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED DEFAULT NULL,
  `rating` tinyint NOT NULL DEFAULT '5',
  `title` varchar(255) DEFAULT NULL,
  `content` text,
  `images` text COMMENT 'JSON array of image paths',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_product` (`product_id`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text,
  `group` varchar(50) DEFAULT 'general',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `group`, `updated_at`) VALUES
(1, 'site_name', 'LUXE Beauty', 'general', '2026-05-10 08:09:14'),
(2, 'site_tagline', 'Vẻ đẹp đích thực từ thiên nhiên', 'general', '2026-05-10 08:09:14'),
(3, 'site_email', 'contact@beautyshop.vn', 'general', '2026-05-10 08:09:14'),
(4, 'site_phone', '1800 6868', 'general', '2026-05-10 08:09:14'),
(5, 'site_address', '123 Nguyễn Huệ, Quận 1, TP.HCM', 'general', '2026-05-10 08:09:14'),
(6, 'shipping_fee', '30000', 'shipping', '2026-05-10 08:09:14'),
(7, 'free_shipping_over', '500000', 'shipping', '2026-05-10 08:09:14'),
(8, 'currency', 'VND', 'general', '2026-05-10 08:09:14'),
(9, 'facebook_url', 'https://facebook.com/luxebeauty', 'social', '2026-05-10 08:09:14'),
(10, 'instagram_url', 'https://instagram.com/luxebeauty', 'social', '2026-05-10 08:09:14');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `address` text,
  `city` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `email_verified` tinyint(1) DEFAULT '0',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `avatar`, `gender`, `birthday`, `address`, `city`, `district`, `ward`, `is_active`, `email_verified`, `reset_token`, `reset_expires`, `last_login`, `created_at`, `updated_at`) VALUES
(2, 'Nguyễn Ngọc Bảo Trân', 'baotran1234@gmail.com', '$2y$12$QbKybe8eyvcNsJ2E0q1Nfe6Kffs0jKCtWlqmyQBrG2rc3lQZC1oMi', '012345678', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, '2026-05-10 21:23:58', '2026-05-10 10:54:54', '2026-05-10 14:23:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE IF NOT EXISTS `wishlists` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_user_product` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `coupons`
--
ALTER TABLE `coupons`
  ADD CONSTRAINT `coupons_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
