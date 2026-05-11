-- ================================================================
-- DATABASE: cosmetics_shop
-- Website Bán Mỹ Phẩm - Full Schema
-- ================================================================

CREATE DATABASE IF NOT EXISTS `cosmetics_shop`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `cosmetics_shop`;

-- ----------------------------------------------------------------
-- Bảng: categories (Danh mục)
-- ----------------------------------------------------------------
CREATE TABLE `categories` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(150) NOT NULL,
  `slug`        VARCHAR(150) NOT NULL UNIQUE,
  `description` TEXT,
  `image`       VARCHAR(255),
  `parent_id`   INT UNSIGNED DEFAULT NULL,
  `sort_order`  INT DEFAULT 0,
  `is_active`   TINYINT(1) DEFAULT 1,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_slug` (`slug`),
  KEY `idx_parent` (`parent_id`),
  FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------
-- Bảng: brands (Thương hiệu)
-- ----------------------------------------------------------------
CREATE TABLE `brands` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(150) NOT NULL,
  `slug`        VARCHAR(150) NOT NULL UNIQUE,
  `description` TEXT,
  `logo`        VARCHAR(255),
  `website`     VARCHAR(255),
  `is_active`   TINYINT(1) DEFAULT 1,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------
-- Bảng: products (Sản phẩm)
-- ----------------------------------------------------------------
CREATE TABLE `products` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id`   INT UNSIGNED NOT NULL,
  `brand_id`      INT UNSIGNED DEFAULT NULL,
  `name`          VARCHAR(255) NOT NULL,
  `slug`          VARCHAR(255) NOT NULL UNIQUE,
  `sku`           VARCHAR(100) UNIQUE,
  `description`   TEXT,
  `ingredients`   TEXT,
  `how_to_use`    TEXT,
  `price`         DECIMAL(12,2) NOT NULL DEFAULT 0,
  `sale_price`    DECIMAL(12,2) DEFAULT NULL,
  `stock`         INT DEFAULT 0,
  `weight`        DECIMAL(8,2) DEFAULT NULL COMMENT 'gram',
  `thumbnail`     VARCHAR(255),
  `is_featured`   TINYINT(1) DEFAULT 0,
  `is_new`        TINYINT(1) DEFAULT 1,
  `is_active`     TINYINT(1) DEFAULT 1,
  `views`         INT DEFAULT 0,
  `sold`          INT DEFAULT 0,
  `rating`        DECIMAL(3,2) DEFAULT 0,
  `review_count`  INT DEFAULT 0,
  `meta_title`    VARCHAR(255),
  `meta_desc`     TEXT,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_slug` (`slug`),
  KEY `idx_category` (`category_id`),
  KEY `idx_brand` (`brand_id`),
  KEY `idx_featured` (`is_featured`),
  KEY `idx_active` (`is_active`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`brand_id`) REFERENCES `brands`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------
-- Bảng: product_images (Ảnh sản phẩm)
-- ----------------------------------------------------------------
CREATE TABLE `product_images` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT UNSIGNED NOT NULL,
  `image`      VARCHAR(255) NOT NULL,
  `alt`        VARCHAR(255),
  `sort_order` INT DEFAULT 0,
  `is_main`    TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_product` (`product_id`),
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------
-- Bảng: users (Khách hàng)
-- ----------------------------------------------------------------
CREATE TABLE `users` (
  `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`           VARCHAR(150) NOT NULL,
  `email`          VARCHAR(191) NOT NULL UNIQUE,
  `password`       VARCHAR(255) NOT NULL,
  `phone`          VARCHAR(20),
  `avatar`         VARCHAR(255),
  `gender`         ENUM('male','female','other') DEFAULT NULL,
  `birthday`       DATE DEFAULT NULL,
  `address`        TEXT,
  `city`           VARCHAR(100),
  `district`       VARCHAR(100),
  `ward`           VARCHAR(100),
  `is_active`      TINYINT(1) DEFAULT 1,
  `email_verified` TINYINT(1) DEFAULT 0,
  `reset_token`    VARCHAR(255) DEFAULT NULL,
  `reset_expires`  DATETIME DEFAULT NULL,
  `last_login`     DATETIME DEFAULT NULL,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------
-- Bảng: admins
-- ----------------------------------------------------------------
CREATE TABLE `admins` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`       VARCHAR(150) NOT NULL,
  `email`      VARCHAR(191) NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,
  `role`       ENUM('superadmin','admin','staff') DEFAULT 'admin',
  `avatar`     VARCHAR(255),
  `is_active`  TINYINT(1) DEFAULT 1,
  `last_login` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------
-- Bảng: carts (Giỏ hàng)
-- ----------------------------------------------------------------
CREATE TABLE `carts` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`    INT UNSIGNED DEFAULT NULL,
  `session_id` VARCHAR(255) DEFAULT NULL,
  `product_id` INT UNSIGNED NOT NULL,
  `quantity`   INT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_user` (`user_id`),
  KEY `idx_session` (`session_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------
-- Bảng: coupons (Mã giảm giá)
-- ----------------------------------------------------------------
CREATE TABLE `coupons` (
  `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `code`           VARCHAR(50) NOT NULL UNIQUE,
  `name`           VARCHAR(150),
  `type`           ENUM('percent','fixed') DEFAULT 'percent',
  `value`          DECIMAL(10,2) NOT NULL,
  `min_order`      DECIMAL(12,2) DEFAULT 0,
  `max_discount`   DECIMAL(12,2) DEFAULT NULL,
  `used_count`     INT DEFAULT 0,
  `max_use`        INT DEFAULT NULL,
  `user_id`        INT UNSIGNED DEFAULT NULL COMMENT 'NULL = all users',
  `starts_at`      DATETIME DEFAULT NULL,
  `expires_at`     DATETIME DEFAULT NULL,
  `is_active`      TINYINT(1) DEFAULT 1,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_code` (`code`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------
-- Bảng: orders (Đơn hàng)
-- ----------------------------------------------------------------
CREATE TABLE `orders` (
  `id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_code`      VARCHAR(30) NOT NULL UNIQUE,
  `user_id`         INT UNSIGNED DEFAULT NULL,
  `coupon_id`       INT UNSIGNED DEFAULT NULL,
  `name`            VARCHAR(150) NOT NULL,
  `email`           VARCHAR(191) NOT NULL,
  `phone`           VARCHAR(20) NOT NULL,
  `address`         TEXT NOT NULL,
  `city`            VARCHAR(100),
  `district`        VARCHAR(100),
  `ward`            VARCHAR(100),
  `note`            TEXT,
  `subtotal`        DECIMAL(12,2) NOT NULL DEFAULT 0,
  `discount`        DECIMAL(12,2) DEFAULT 0,
  `shipping_fee`    DECIMAL(10,2) DEFAULT 0,
  `total`           DECIMAL(12,2) NOT NULL DEFAULT 0,
  `payment_method`  ENUM('cod','bank','momo') DEFAULT 'cod',
  `payment_status`  ENUM('pending','paid','failed','refunded') DEFAULT 'pending',
  `status`          ENUM('pending','confirmed','processing','shipping','delivered','cancelled') DEFAULT 'pending',
  `cancel_reason`   TEXT DEFAULT NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_order_code` (`order_code`),
  KEY `idx_user` (`user_id`),
  KEY `idx_status` (`status`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`coupon_id`) REFERENCES `coupons`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------
-- Bảng: order_items
-- ----------------------------------------------------------------
CREATE TABLE `order_items` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_id`    INT UNSIGNED NOT NULL,
  `product_id`  INT UNSIGNED DEFAULT NULL,
  `name`        VARCHAR(255) NOT NULL,
  `thumbnail`   VARCHAR(255),
  `price`       DECIMAL(12,2) NOT NULL,
  `quantity`    INT NOT NULL DEFAULT 1,
  `subtotal`    DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------
-- Bảng: reviews (Đánh giá)
-- ----------------------------------------------------------------
CREATE TABLE `reviews` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT UNSIGNED NOT NULL,
  `user_id`    INT UNSIGNED NOT NULL,
  `order_id`   INT UNSIGNED DEFAULT NULL,
  `rating`     TINYINT NOT NULL DEFAULT 5,
  `title`      VARCHAR(255),
  `content`    TEXT,
  `images`     TEXT COMMENT 'JSON array of image paths',
  `is_active`  TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_product` (`product_id`),
  KEY `idx_user` (`user_id`),
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------
-- Bảng: wishlists
-- ----------------------------------------------------------------
CREATE TABLE `wishlists` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`    INT UNSIGNED NOT NULL,
  `product_id` INT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_user_product` (`user_id`,`product_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------
-- Bảng: banners
-- ----------------------------------------------------------------
CREATE TABLE `banners` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title`      VARCHAR(255),
  `subtitle`   VARCHAR(255),
  `image`      VARCHAR(255) NOT NULL,
  `link`       VARCHAR(255),
  `position`   ENUM('hero','promo','sidebar') DEFAULT 'hero',
  `sort_order` INT DEFAULT 0,
  `is_active`  TINYINT(1) DEFAULT 1,
  `starts_at`  DATETIME DEFAULT NULL,
  `ends_at`    DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------
-- Bảng: settings
-- ----------------------------------------------------------------
CREATE TABLE `settings` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key`        VARCHAR(100) NOT NULL UNIQUE,
  `value`      TEXT,
  `group`      VARCHAR(50) DEFAULT 'general',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- DỮ LIỆU MẪU
-- ================================================================

-- Admin mặc định (password: Admin@123)
INSERT INTO `admins` (`name`, `email`, `password`, `role`) VALUES
('Super Admin', 'admin@beautyshop.vn', '$2y$10$oSCI664XvRxalsSM10zK7Owa7go1.tRGUK9o4IDWpAsF42tmpkco.', 'superadmin');

-- Cài đặt website
INSERT INTO `settings` (`key`, `value`, `group`) VALUES
('site_name', 'LUXE Beauty', 'general'),
('site_tagline', 'Vẻ đẹp đích thực từ thiên nhiên', 'general'),
('site_email', 'contact@beautyshop.vn', 'general'),
('site_phone', '1800 6868', 'general'),
('site_address', '123 Nguyễn Huệ, Quận 1, TP.HCM', 'general'),
('shipping_fee', '30000', 'shipping'),
('free_shipping_over', '500000', 'shipping'),
('currency', 'VND', 'general'),
('facebook_url', 'https://facebook.com/luxebeauty', 'social'),
('instagram_url', 'https://instagram.com/luxebeauty', 'social');

-- Danh mục
INSERT INTO `categories` (`name`, `slug`, `description`, `sort_order`) VALUES
('Chăm Sóc Da Mặt', 'cham-soc-da-mat', 'Sản phẩm chăm sóc da mặt cao cấp', 1),
('Son Môi', 'son-moi', 'Son môi đa dạng màu sắc', 2),
('Mắt', 'mat', 'Mascara, kẻ mắt, phấn mắt', 3),
('Nền & Che Khuyết Điểm', 'nen-che-khuyet-diem', 'Kem nền, phấn phủ, che khuyết điểm', 4),
('Nước Hoa', 'nuoc-hoa', 'Nước hoa cao cấp', 5),
('Chăm Sóc Tóc', 'cham-soc-toc', 'Dầu gội, dầu xả, serum tóc', 6),
('Chăm Sóc Cơ Thể', 'cham-soc-co-the', 'Kem dưỡng thể, sữa tắm', 7),
('Chống Nắng', 'chong-nang', 'Kem chống nắng mọi loại da', 8);

-- Thương hiệu
INSERT INTO `brands` (`name`, `slug`, `website`) VALUES
('L\'Oréal Paris', 'loreal-paris', 'https://loreal-paris.vn'),
('Innisfree', 'innisfree', 'https://innisfree.vn'),
('The Ordinary', 'the-ordinary', 'https://theordinary.com'),
('Laneige', 'laneige', 'https://laneige.com'),
('Maybelline', 'maybelline', 'https://maybelline.vn'),
('SK-II', 'skii', 'https://skii.com'),
('Cetaphil', 'cetaphil', 'https://cetaphil.vn'),
('La Roche-Posay', 'la-roche-posay', 'https://laroche-posay.vn');

-- Sản phẩm mẫu
INSERT INTO `products` (`category_id`,`brand_id`,`name`,`slug`,`sku`,`description`,`price`,`sale_price`,`stock`,`is_featured`,`rating`,`review_count`) VALUES
(1,2,'Serum Dưỡng Trắng Innisfree Jeju Orchid','serum-duong-trang-innisfree-jeju-orchid','INF-SER-001','Serum dưỡng trắng chiết xuất hoa lan Jeju, cải thiện đều màu da và giảm thâm nám hiệu quả.',450000,359000,100,1,4.8,124),
(1,3,'The Ordinary Niacinamide 10% + Zinc 1%','the-ordinary-niacinamide','ORD-NIA-001','Serum niacinamide nồng độ cao giúp thu nhỏ lỗ chân lông, kiểm soát dầu và cải thiện kết cấu da.',280000,210000,200,1,4.7,98),
(2,1,'Son Môi L\'Oréal Paris Color Riche','son-moi-loreal-color-riche','LOR-LIP-001','Son lì với 40 sắc màu, dưỡng ẩm và bền màu suốt 8 tiếng.',180000,145000,150,1,4.5,67),
(4,5,'Kem Nền Maybelline Fit Me Matte','kem-nen-maybelline-fit-me','MAY-FND-001','Kem nền lì kiểm soát dầu suốt ngày, 40 tông màu phù hợp mọi loại da.',220000,185000,80,0,4.4,45),
(8,8,'Kem Chống Nắng La Roche-Posay SPF50+','kem-chong-nang-la-roche-posay','LRP-SUN-001','Kem chống nắng dành cho da nhạy cảm, SPF50+ PA++++, không gây bết rít.',485000,420000,120,1,4.9,203),
(1,6,'SK-II Facial Treatment Essence','skii-facial-treatment-essence','SKI-ESS-001','Nước thần SK-II nổi tiếng thế giới với 90% Pitera giúp da sáng mịn và trẻ hóa.',2800000,2450000,30,1,4.9,312),
(1,4,'Laneige Cream Skin Toner & Moisturizer','laneige-cream-skin','LAN-CRE-001','Toner kem dưỡng ẩm 2-in-1 với chiết xuất trà trắng, cấp ẩm sâu và làm mềm da.',680000,580000,75,1,4.7,156),
(1,7,'Cetaphil Gentle Skin Cleanser','cetaphil-gentle-cleanser','CET-CLN-001','Sữa rửa mặt dịu nhẹ cho da nhạy cảm, không xà phòng, không cồn.',185000,155000,200,0,4.6,289);

-- Banners mẫu
INSERT INTO `banners` (`title`,`subtitle`,`image`,`link`,`position`,`sort_order`,`is_active`) VALUES
('Bộ Sưu Tập Mùa Hè 2025','Rạng rỡ từng khoảnh khắc với màu sắc tươi tắn','/assets/images/banners/banner1.jpg','/products','hero',1,1),
('Sale Đến 50% Skincare','Ưu đãi có hạn - Đừng bỏ lỡ!','/assets/images/banners/banner2.jpg','/products?category=cham-soc-da-mat','hero',2,1),
('Hàng Mới Về Mỗi Ngày','Khám phá ngay những sản phẩm hot nhất','/assets/images/banners/banner3.jpg','/products?sort=newest','hero',3,1);

-- Mã giảm giá mẫu
INSERT INTO `coupons` (`code`,`name`,`type`,`value`,`min_order`,`max_use`,`is_active`) VALUES
('WELCOME10','Chào mừng thành viên mới','percent',10,200000,1000,1),
('SALE50K','Giảm 50K đơn từ 500K','fixed',50000,500000,500,1),
('VIP20','VIP giảm 20%','percent',20,1000000,100,1);
