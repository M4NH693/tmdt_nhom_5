-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 31, 2026 at 02:51 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `address_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `receiver_name` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `district` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `ward` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `street_detail` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `author_id` int UNSIGNED NOT NULL,
  `author_name` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `avatar_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`author_id`, `author_name`, `bio`, `avatar_url`, `created_at`, `updated_at`) VALUES
(1, 'Dale Carnegie', 'Tác giả người Mỹ, nổi tiếng với các sách về kỹ năng giao tiếp', NULL, '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(2, 'Paulo Coelho', 'Nhà văn Brazil, tác giả của Nhà Giả Kim', NULL, '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(3, 'Nguyễn Nhật Ánh', 'Nhà văn Việt Nam nổi tiếng với các tác phẩm dành cho thiếu nhi', NULL, '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(4, 'Yuval Noah Harari', 'Giáo sư sử học tại Đại học Hebrew', NULL, '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(5, 'Robert C. Martin', 'Kỹ sư phần mềm, tác giả của Clean Code', NULL, '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(6, 'Napoleon Hill', 'Tác giả người Mỹ, chuyên về thành công cá nhân', NULL, '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(7, 'Tô Hoài', 'Nhà văn Việt Nam, tác giả Dế Mèn Phiêu Lưu Ký', NULL, '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(8, 'Daniel Kahneman', 'Nhà tâm lý học, Nobel Kinh tế 2002', NULL, '2026-03-16 23:17:48', '2026-03-16 23:17:48');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int UNSIGNED NOT NULL,
  `title` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `slug` varchar(350) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isbn` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publisher_id` int UNSIGNED DEFAULT NULL,
  `category_id` int UNSIGNED DEFAULT NULL,
  `publication_year` smallint UNSIGNED DEFAULT NULL,
  `pages` smallint UNSIGNED DEFAULT NULL,
  `language` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Tiếng Việt',
  `weight_grams` smallint UNSIGNED DEFAULT NULL COMMENT 'Trọng lượng (gram)',
  `dimensions` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kích thước VD: 20x15x2 cm',
  `price` decimal(12,2) NOT NULL COMMENT 'Giá bán (VNĐ)',
  `stock_quantity` int UNSIGNED NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `cover_image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `avg_rating` decimal(2,1) NOT NULL DEFAULT '0.0' COMMENT 'Điểm đánh giá trung bình (0-5)',
  `total_sold` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `slug`, `isbn`, `publisher_id`, `category_id`, `publication_year`, `pages`, `language`, `weight_grams`, `dimensions`, `price`, `stock_quantity`, `description`, `cover_image`, `is_active`, `avg_rating`, `total_sold`, `created_at`, `updated_at`) VALUES
(1, 'Đắc Nhân Tâm', 'dac-nhan-tam-1774964285', '978-604-1-12345', 1, 4, 2020, 320, 'Tiếng Việt', NULL, NULL, 89000.00, 10, 'Đắc nhân tâm là cuốn sách nổi tiếng nhất, bán chạy nhất của Dale Carnegie. Được xuất bản lần đầu vào năm 1936, đến nay sách đã được dịch ra hầu hết các thứ tiếng trên thế giới.', '/images/books/69b91c7b7da5e_1773739131.jpg', 1, 5.0, 2503, '2026-03-16 23:17:48', '2026-03-31 20:38:05'),
(2, 'Nhà Giả Kim', 'nha-gia-kim', '978-604-1-12346', 4, 1, 2019, 228, 'Tiếng Việt', NULL, NULL, 75000.00, 200, 'Tiểu thuyết Nhà giả kim của Paulo Coelho như một câu chuyện cổ tích giản dị, nhân ái, giàu chất thơ, thấm đẫm những minh triết huyền bí.', NULL, 1, 4.5, 1800, '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(3, 'Sapiens: Lược sử loài người', 'sapiens-luoc-su-loai-nguoi', '978-604-1-12347', 4, 3, 2021, 560, 'Tiếng Việt', NULL, NULL, 199000.00, 80, 'Sapiens is a book by Yuval Noah Harari, published in 2011. It surveys the entire length of human history, from the evolution of Homo sapiens.', NULL, 1, 4.7, 950, '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(4, 'Clean Code', 'clean-code', '978-604-1-12348', 3, 7, 2018, 464, 'Tiếng Việt', NULL, NULL, 299000.00, 60, 'Cuốn sách hướng dẫn viết code sạch, rõ ràng và dễ bảo trì. Một cuốn sách kinh điển cho lập trình viên.', NULL, 1, 5.0, 700, '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(5, 'Tôi Tài Giỏi, Bạn Cũng Thế', 'toi-tai-gioi-ban-cung-the', '978-604-1-12349', 1, 4, 2020, 276, 'Tiếng Việt', NULL, NULL, 110000.00, 120, 'Cuốn sách giúp bạn khám phá tiềm năng bản thân và phát triển kỹ năng.', NULL, 1, 4.3, 1500, '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(6, 'Dế Mèn Phiêu Lưu Ký', 'de-men-phieu-luu-ky-1774946549', '978-604-1-12350', 2, 5, 2019, 180, 'Tiếng Việt', NULL, NULL, 45000.00, 300, 'Tác phẩm kinh điển của nhà văn Tô Hoài dành cho thiếu nhi.', '/images/books/69cb88f5e818e_1774946549.jpg', 1, 5.0, 3000, '2026-03-16 23:17:48', '2026-03-31 15:42:29'),
(7, 'Tư Duy Nhanh & Chậm', 'tu-duy-nhanh-cham', '978-604-1-12351', 4, 3, 2021, 624, 'Tiếng Việt', NULL, NULL, 159000.00, 90, 'Cuốn sách kinh điển về tâm lý học hành vi của Daniel Kahneman.', NULL, 1, 4.7, 800, '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(8, 'Think and Grow Rich', 'think-and-grow-rich', '978-604-1-12352', 3, 4, 2020, 320, 'Tiếng Việt', NULL, NULL, 95000.00, 100, 'Nghĩ giàu làm giàu - cuốn sách kinh điển của Napoleon Hill.', NULL, 1, 4.4, 1200, '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(9, 'Cho Tôi Xin Một Vé Đi Tuổi Thơ', 'cho-toi-xin-mot-ve-di-tuoi-tho-1774946835', '978-604-1-12353', 1, 1, 2018, 216, 'Tiếng Việt', NULL, NULL, 68000.00, 180, 'Tác phẩm nổi tiếng của Nguyễn Nhật Ánh, đưa bạn trở về tuổi thơ.', '/images/books/69cb8a13248c0_1774946835.jpg', 1, 4.6, 2200, '2026-03-16 23:17:48', '2026-03-31 15:47:15'),
(10, 'Mắt Biếc', 'mat-biec-1774946726', '978-604-1-12354', 1, 1, 2019, 280, 'Tiếng Việt', NULL, NULL, 78000.00, 159, 'Câu chuyện tình yêu trong sáng của Nguyễn Nhật Ánh.', '/images/books/69cb89a683a41_1774946726.jpg', 1, 5.0, 2801, '2026-03-16 23:17:48', '2026-03-31 15:45:26'),
(11, 'Homo Deus', 'homo-deus', '978-604-1-12355', 4, 3, 2022, 480, 'Tiếng Việt', NULL, NULL, 189000.00, 70, 'Lược sử tương lai - tiếp nối Sapiens của Yuval Noah Harari.', NULL, 1, 4.5, 500, '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(12, 'English Grammar In Use', 'english-grammar-in-use', '978-604-1-12356', 5, 6, 2021, 380, 'Song ngữ', NULL, NULL, 185000.00, 100, 'Sách ngữ pháp tiếng Anh kinh điển, phù hợp cho người tự học.', NULL, 1, 4.6, 900, '2026-03-16 23:17:48', '2026-03-16 23:17:48');

-- --------------------------------------------------------

--
-- Table structure for table `book_authors`
--

CREATE TABLE `book_authors` (
  `book_id` int UNSIGNED NOT NULL,
  `author_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `book_authors`
--

INSERT INTO `book_authors` (`book_id`, `author_id`) VALUES
(1, 1),
(5, 1),
(2, 2),
(9, 3),
(10, 3),
(3, 4),
(11, 4),
(4, 5),
(12, 5),
(8, 6),
(6, 7),
(7, 8);

-- --------------------------------------------------------

--
-- Table structure for table `book_images`
--

CREATE TABLE `book_images` (
  `image_id` int UNSIGNED NOT NULL,
  `book_id` int UNSIGNED NOT NULL,
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `book_images`
--

INSERT INTO `book_images` (`image_id`, `book_id`, `image_url`, `sort_order`, `created_at`) VALUES
(1, 1, '/images/books/69b91cbd1b307_1773739197_0.jpg', 1, '2026-03-17 16:19:57'),
(2, 1, '/images/books/69b91cbd1b6c0_1773739197_1.jfif', 2, '2026-03-17 16:19:57'),
(3, 1, '/images/books/69b93e7d380cc_1773747837_0.jpg', 3, '2026-03-17 18:43:57'),
(4, 1, '/images/books/69bb44b543ceb_1773880501_0.png', 4, '2026-03-19 07:35:01');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `book_id` int UNSIGNED NOT NULL,
  `quantity` smallint UNSIGNED NOT NULL DEFAULT '1',
  `added_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int UNSIGNED NOT NULL,
  `category_name` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL COMMENT 'NULL = danh mục gốc',
  `description` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `slug`, `parent_id`, `description`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Văn học', 'van-hoc-1774946216', NULL, 'Sách văn học Việt Nam và thế giới', '/images/categories/69cb87a8e456b_1774946216.jpg', 1, '2026-03-16 23:17:47', '2026-03-31 15:36:56'),
(2, 'Kinh tế', 'kinh-te-1774946163', NULL, 'Sách kinh tế, kinh doanh, tài chính', '/images/categories/69cb877357b7d_1774946163.jpg', 1, '2026-03-16 23:17:47', '2026-03-31 15:36:03'),
(3, 'Khoa học', 'khoa-hoc-1774946157', NULL, 'Sách khoa học tự nhiên và xã hội', '/images/categories/69cb876d0a931_1774946157.jpg', 1, '2026-03-16 23:17:47', '2026-03-31 15:35:57'),
(4, 'Kỹ năng sống', 'ky-nang-song-1774946173', NULL, 'Sách phát triển bản thân, kỹ năng', '/images/categories/69cb877dca654_1774946173.jpg', 1, '2026-03-16 23:17:47', '2026-03-31 15:36:13'),
(5, 'Thiếu nhi', 'thieu-nhi-1774946210', NULL, 'Sách dành cho thiếu nhi, truyện tranh', '/images/categories/69cb87a24dc9e_1774946210.jpeg', 1, '2026-03-16 23:17:47', '2026-03-31 15:36:50'),
(6, 'Ngoại ngữ', 'ngoai-ngu-1774946204', NULL, 'Sách học ngoại ngữ, từ điển', '/images/categories/69cb879c0dc61_1774946204.jpg', 1, '2026-03-16 23:17:47', '2026-03-31 15:36:44'),
(7, 'Công nghệ', 'cong-nghe-1774946149', NULL, 'Sách công nghệ thông tin, lập trình', '/images/categories/69cb8765c3e7d_1774946149.jpg', 1, '2026-03-16 23:17:47', '2026-03-31 15:35:49'),
(8, 'Lịch sử', 'lich-su-1774946182', NULL, 'Sách lịch sử Việt Nam và thế giới', '/images/categories/69cb878611a46_1774946182.jpg', 1, '2026-03-16 23:17:47', '2026-03-31 15:36:22'),
(9, 'Light novel', 'light-novel-1774946195', NULL, '', '/images/categories/69cb8793ba055_1774946195.jpg', 1, '2026-03-31 15:31:01', '2026-03-31 15:36:35');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int UNSIGNED NOT NULL,
  `order_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã đơn hàng hiển thị (VD: ORD-20260316-001)',
  `user_id` int UNSIGNED NOT NULL,
  `receiver_name` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `receiver_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_address` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `subtotal` decimal(14,2) NOT NULL COMMENT 'Tổng tiền hàng',
  `shipping_fee` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(14,2) NOT NULL COMMENT 'Tổng thanh toán = subtotal + shipping_fee',
  `payment_method` enum('cod','bank_transfer','e_wallet','credit_card') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cod',
  `payment_status` enum('unpaid','paid','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `order_status` enum('pending','confirmed','shipping','delivered','cancelled','returned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `note` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `ordered_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `confirmed_at` datetime DEFAULT NULL,
  `shipped_at` datetime DEFAULT NULL,
  `delivered_at` datetime DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_code`, `user_id`, `receiver_name`, `receiver_phone`, `shipping_address`, `subtotal`, `shipping_fee`, `total_amount`, `payment_method`, `payment_status`, `order_status`, `note`, `ordered_at`, `confirmed_at`, `shipped_at`, `delivered_at`, `cancelled_at`, `updated_at`) VALUES
(1, 'ORD-20260301-001', 2, 'Nguyễn Văn A', '0912345678', '123 Nguyễn Huệ, Q.1, TP.HCM', 164000.00, 30000.00, 194000.00, 'cod', 'paid', 'delivered', NULL, '2026-01-15 10:30:00', NULL, NULL, NULL, NULL, '2026-03-16 23:17:48'),
(2, 'ORD-20260302-001', 3, 'Trần Thị B', '0923456789', '456 Trần Hưng Đạo, Q.5, TP.HCM', 299000.00, 30000.00, 329000.00, 'bank_transfer', 'paid', 'delivered', NULL, '2026-01-20 14:00:00', NULL, NULL, NULL, NULL, '2026-03-16 23:17:48'),
(3, 'ORD-20260303-001', 4, 'Lê Văn C', '0934567890', '789 Lê Lợi, Q.1, TP.HCM', 388000.00, 30000.00, 418000.00, 'cod', 'paid', 'delivered', NULL, '2026-02-05 09:15:00', NULL, NULL, NULL, NULL, '2026-03-16 23:17:48'),
(5, 'ORD-20260305-001', 3, 'Trần Thị B', '0923456789', '456 Trần Hưng Đạo, Q.5, TP.HCM', 247000.00, 30000.00, 277000.00, 'cod', 'paid', 'delivered', NULL, '2026-03-01 11:20:00', NULL, NULL, NULL, NULL, '2026-03-16 23:17:48'),
(6, 'ORD-20260306-001', 4, 'Lê Văn C', '0934567890', '789 Lê Lợi, Q.1, TP.HCM', 159000.00, 30000.00, 189000.00, 'credit_card', 'unpaid', 'pending', NULL, '2026-03-15 08:30:00', NULL, NULL, NULL, NULL, '2026-03-16 23:17:48'),
(7, 'ORD-20260307-001', 2, 'Nguyễn Văn A', '0912345678', '123 Nguyễn Huệ, Q.1, TP.HCM', 185000.00, 30000.00, 215000.00, 'cod', 'unpaid', 'confirmed', NULL, '2026-03-16 10:00:00', '2026-03-17 08:43:17', NULL, NULL, NULL, '2026-03-17 15:43:17'),
(8, 'ORD-20260317-887', 5, 'M4NH', '0344555068', 'số 66, đông ngạc, bắc từ liêm', 89000.00, 30000.00, 119000.00, 'bank_transfer', 'unpaid', 'pending', '', '2026-03-17 15:40:47', NULL, NULL, NULL, NULL, '2026-03-17 15:40:47'),
(9, 'ORD-20260317-491', 6, 'HARDING', '0344555068', 'số 66, phường đông ngạc, quận bắc từ liêm', 89000.00, 30000.00, 119000.00, 'cod', 'unpaid', 'shipping', '', '2026-03-17 16:43:24', NULL, '2026-03-17 09:43:56', NULL, NULL, '2026-03-17 16:43:56'),
(10, 'ORD-20260317-833', 5, 'M4NH', '0344555068', 'số 66, học viện cảnh sát nhân dân, bắc từ liêm', 89000.00, 30000.00, 119000.00, 'cod', 'unpaid', 'delivered', 'giao vào giờ hành chính nhé', '2026-03-17 18:46:50', NULL, NULL, '2026-03-17 11:47:27', NULL, '2026-03-17 18:47:27'),
(11, 'ORD-20260317-725', 5, 'M4NH', '0344555068', 'số 66, bắc từ liêm', 78000.00, 30000.00, 108000.00, 'cod', 'unpaid', 'delivered', '', '2026-03-17 21:03:37', NULL, NULL, '2026-03-17 14:04:18', NULL, '2026-03-17 21:04:18'),
(12, 'ORD-20260317-258', 5, 'M4NH', '0344555068', 'số 66, cổ nhuế 2, bắc từ liêm', 89000.00, 30000.00, 119000.00, 'cod', 'unpaid', 'delivered', 'lồn', '2026-03-17 21:17:06', NULL, NULL, '2026-03-19 01:51:16', NULL, '2026-03-19 08:51:16'),
(13, 'ORD-20260319-562', 5, 'M4NH', '0344555068', 'số 66, đường phú diễn', 314000.00, 0.00, 314000.00, 'cod', 'unpaid', 'pending', '', '2026-03-19 09:13:50', NULL, NULL, NULL, NULL, '2026-03-19 09:13:50');

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `trg_orders_after_update_status` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
    -- Khi đơn hàng được giao thành công
    IF OLD.order_status != 'delivered' AND NEW.order_status = 'delivered' THEN
        UPDATE books b
        INNER JOIN order_items oi ON oi.book_id = b.book_id
        SET b.total_sold = b.total_sold + oi.quantity,
            b.stock_quantity = GREATEST(b.stock_quantity - oi.quantity, 0)
        WHERE oi.order_id = NEW.order_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED NOT NULL,
  `book_id` int UNSIGNED NOT NULL,
  `quantity` smallint UNSIGNED NOT NULL,
  `unit_price` decimal(12,2) NOT NULL COMMENT 'Giá tại thời điểm mua',
  `total_price` decimal(14,2) NOT NULL COMMENT 'quantity x unit_price'
) ;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `book_id`, `quantity`, `unit_price`, `total_price`) VALUES
(1, 1, 1, 1, 89000.00, 89000.00),
(2, 1, 2, 1, 75000.00, 75000.00),
(3, 2, 4, 1, 299000.00, 299000.00),
(4, 3, 3, 1, 199000.00, 199000.00),
(5, 3, 7, 1, 159000.00, 159000.00),
(6, 3, 6, 1, 45000.00, 45000.00),
(8, 5, 1, 1, 89000.00, 89000.00),
(9, 5, 7, 1, 159000.00, 159000.00),
(10, 6, 7, 1, 159000.00, 159000.00),
(11, 7, 12, 1, 185000.00, 185000.00),
(12, 8, 1, 1, 89000.00, 89000.00),
(13, 9, 1, 1, 89000.00, 89000.00),
(14, 10, 1, 1, 89000.00, 89000.00),
(15, 11, 10, 1, 78000.00, 78000.00),
(16, 12, 1, 1, 89000.00, 89000.00),
(17, 13, 9, 2, 68000.00, 136000.00),
(18, 13, 1, 2, 89000.00, 178000.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_status_history`
--

CREATE TABLE `order_status_history` (
  `history_id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED NOT NULL,
  `old_status` enum('pending','confirmed','shipping','delivered','cancelled','returned') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_status` enum('pending','confirmed','shipping','delivered','cancelled','returned') COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by` int UNSIGNED DEFAULT NULL COMMENT 'user_id của người thay đổi (admin)',
  `note` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `changed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `publishers`
--

CREATE TABLE `publishers` (
  `publisher_id` int UNSIGNED NOT NULL,
  `publisher_name` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `address` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `publishers`
--

INSERT INTO `publishers` (`publisher_id`, `publisher_name`, `address`, `phone`, `email`, `website`, `created_at`, `updated_at`) VALUES
(1, 'NXB Trẻ', 'TP. Hồ Chí Minh', '028-39316289', 'info@nxbtre.com.vn', NULL, '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(2, 'NXB Kim Đồng', 'Hà Nội', '024-39434730', 'info@nxbkimdong.com.vn', NULL, '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(3, 'NXB Tổng hợp TP.HCM', 'TP. Hồ Chí Minh', '028-38256804', 'info@nxbhcm.com.vn', NULL, '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(4, 'NXB Thế Giới', 'Hà Nội', '024-38253841', 'info@thegioipublishers.vn', NULL, '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(5, 'NXB Lao Động', 'Hà Nội', '024-38515380', 'info@nxblaodong.com.vn', NULL, '2026-03-16 23:17:48', '2026-03-16 23:17:48');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int UNSIGNED NOT NULL,
  `book_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL COMMENT '1-5 sao',
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `book_id`, `user_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 5, 'Sách rất hay, đã thay đổi cách tôi giao tiếp!', '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(2, 1, 3, 5, 'Cuốn sách kinh điển, ai cũng nên đọc.', '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(3, 2, 2, 4, 'Câu chuyện truyền cảm hứng, rất đáng đọc.', '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(4, 2, 4, 5, 'Tuyệt vời! Đọc đi đọc lại nhiều lần.', '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(5, 6, 3, 5, 'Tuổi thơ của bao thế hệ, sách quá hay.', '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(6, 10, 2, 5, 'Mắt Biếc hay lắm, cảm động vô cùng.', '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(7, 4, 4, 5, 'Must-read cho mọi lập trình viên!', '2026-03-16 23:17:48', '2026-03-16 23:17:48'),
(8, 1, 5, 5, 'sach rat hay va thu vi', '2026-03-20 16:50:50', '2026-03-20 16:50:50');

--
-- Triggers `reviews`
--
DELIMITER $$
CREATE TRIGGER `trg_reviews_after_delete` AFTER DELETE ON `reviews` FOR EACH ROW BEGIN
    UPDATE books
    SET avg_rating = (
        SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE book_id = OLD.book_id
    )
    WHERE book_id = OLD.book_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_reviews_after_insert` AFTER INSERT ON `reviews` FOR EACH ROW BEGIN
    UPDATE books
    SET avg_rating = (
        SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE book_id = NEW.book_id
    )
    WHERE book_id = NEW.book_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_reviews_after_update` AFTER UPDATE ON `reviews` FOR EACH ROW BEGIN
    UPDATE books
    SET avg_rating = (
        SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE book_id = NEW.book_id
    )
    WHERE book_id = NEW.book_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mật khẩu đã được hash (bcrypt/argon2)',
  `full_name` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('customer','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = hoạt động, 0 = bị khóa',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password_hash`, `full_name`, `phone`, `avatar_url`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin@bookstore.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', '0901234567', NULL, 'admin', 1, '2026-03-16 23:17:47', '2026-03-16 23:17:47'),
(2, 'nguyenvana@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguyễn Văn A', '0912345678', NULL, 'customer', 1, '2026-03-16 23:17:47', '2026-03-20 00:42:33'),
(3, 'tranthib@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Trần Thị B', '0923456789', NULL, 'customer', 1, '2026-03-16 23:17:47', '2026-03-20 00:42:35'),
(4, 'levanc@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lê Văn C', '0934567890', NULL, 'customer', 1, '2026-03-16 23:17:47', '2026-03-20 00:42:38'),
(5, 'vmanhsaber119@gmail.com', '$2y$10$E8MOEGCzoYAVSP.elt..I.s5WsbdaLXslr8HZraUfAgDWlkyyiAAi', 'M4NH', NULL, '/images/avatars/avatar_5_1773736277.jpg', 'admin', 1, '2026-03-16 23:18:32', '2026-03-17 15:31:17'),
(6, 'decu1@gmail.com', '$2y$10$8QoqtVh7X/zH5w68AH9mFO/e27ahzue.U14kwXiu0DpXbjqXfs16O', 'HARDING', NULL, '/images/avatars/avatar_6_1773740562.JPG', 'customer', 1, '2026-03-17 16:42:17', '2026-03-17 16:42:42');

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_book_details`
-- (See below for the actual view)
--
CREATE TABLE `vw_book_details` (
`authors` text
,`avg_rating` decimal(2,1)
,`book_id` int unsigned
,`category_name` varchar(150)
,`cover_image` varchar(500)
,`is_active` tinyint(1)
,`isbn` varchar(20)
,`price` decimal(12,2)
,`publisher_name` varchar(200)
,`slug` varchar(350)
,`stock_quantity` int unsigned
,`title` varchar(300)
,`total_sold` int unsigned
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_monthly_revenue`
-- (See below for the actual view)
--
CREATE TABLE `vw_monthly_revenue` (
`cancelled_orders` decimal(23,0)
,`delivered_orders` decimal(23,0)
,`order_month` int
,`order_year` year
,`revenue` decimal(36,2)
,`total_orders` bigint
);

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `wishlist_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `book_id` int UNSIGNED NOT NULL,
  `added_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `idx_addresses_user` (`user_id`);

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`author_id`),
  ADD KEY `idx_authors_name` (`author_name`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD UNIQUE KEY `uq_books_slug` (`slug`),
  ADD UNIQUE KEY `uq_books_isbn` (`isbn`),
  ADD KEY `idx_books_category` (`category_id`),
  ADD KEY `idx_books_publisher` (`publisher_id`),
  ADD KEY `idx_books_price` (`price`),
  ADD KEY `idx_books_active_sold` (`is_active`,`total_sold` DESC);

--
-- Indexes for table `book_authors`
--
ALTER TABLE `book_authors`
  ADD PRIMARY KEY (`book_id`,`author_id`),
  ADD KEY `idx_book_authors_author` (`author_id`);

--
-- Indexes for table `book_images`
--
ALTER TABLE `book_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `idx_book_images_book` (`book_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD UNIQUE KEY `uq_cart_user_book` (`user_id`,`book_id`),
  ADD KEY `idx_cart_book` (`book_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `uq_categories_slug` (`slug`),
  ADD KEY `idx_categories_parent` (`parent_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `uq_orders_code` (`order_code`),
  ADD KEY `idx_orders_user` (`user_id`),
  ADD KEY `idx_orders_status` (`order_status`),
  ADD KEY `idx_orders_payment` (`payment_status`),
  ADD KEY `idx_orders_date` (`ordered_at`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `idx_oi_order` (`order_id`),
  ADD KEY `idx_oi_book` (`book_id`);

--
-- Indexes for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `idx_osh_order` (`order_id`),
  ADD KEY `fk_osh_user` (`changed_by`);

--
-- Indexes for table `publishers`
--
ALTER TABLE `publishers`
  ADD PRIMARY KEY (`publisher_id`),
  ADD UNIQUE KEY `uq_publishers_name` (`publisher_name`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD UNIQUE KEY `uq_reviews_user_book` (`user_id`,`book_id`),
  ADD KEY `idx_reviews_book` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `uq_users_email` (`email`),
  ADD KEY `idx_users_role` (`role`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD UNIQUE KEY `uq_wishlist_user_book` (`user_id`,`book_id`),
  ADD KEY `idx_wishlist_book` (`book_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `address_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `author_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `book_images`
--
ALTER TABLE `book_images`
  MODIFY `image_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_status_history`
--
ALTER TABLE `order_status_history`
  MODIFY `history_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `publishers`
--
ALTER TABLE `publishers`
  MODIFY `publisher_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `wishlist_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Structure for view `vw_book_details`
--
DROP TABLE IF EXISTS `vw_book_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_book_details`  AS SELECT `b`.`book_id` AS `book_id`, `b`.`title` AS `title`, `b`.`slug` AS `slug`, `b`.`isbn` AS `isbn`, `b`.`price` AS `price`, `b`.`stock_quantity` AS `stock_quantity`, `b`.`avg_rating` AS `avg_rating`, `b`.`total_sold` AS `total_sold`, `b`.`cover_image` AS `cover_image`, `b`.`is_active` AS `is_active`, `c`.`category_name` AS `category_name`, `p`.`publisher_name` AS `publisher_name`, group_concat(`a`.`author_name` order by `a`.`author_name` ASC separator ', ') AS `authors` FROM ((((`books` `b` left join `categories` `c` on((`c`.`category_id` = `b`.`category_id`))) left join `publishers` `p` on((`p`.`publisher_id` = `b`.`publisher_id`))) left join `book_authors` `ba` on((`ba`.`book_id` = `b`.`book_id`))) left join `authors` `a` on((`a`.`author_id` = `ba`.`author_id`))) GROUP BY `b`.`book_id` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_monthly_revenue`
--
DROP TABLE IF EXISTS `vw_monthly_revenue`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_monthly_revenue`  AS SELECT year(`orders`.`ordered_at`) AS `order_year`, month(`orders`.`ordered_at`) AS `order_month`, count(0) AS `total_orders`, sum((case when (`orders`.`order_status` = 'delivered') then 1 else 0 end)) AS `delivered_orders`, sum((case when (`orders`.`order_status` = 'cancelled') then 1 else 0 end)) AS `cancelled_orders`, sum((case when (`orders`.`order_status` = 'delivered') then `orders`.`total_amount` else 0 end)) AS `revenue` FROM `orders` GROUP BY year(`orders`.`ordered_at`), month(`orders`.`ordered_at`) ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `fk_addresses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_books_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_books_publisher` FOREIGN KEY (`publisher_id`) REFERENCES `publishers` (`publisher_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `book_authors`
--
ALTER TABLE `book_authors`
  ADD CONSTRAINT `fk_ba_author` FOREIGN KEY (`author_id`) REFERENCES `authors` (`author_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ba_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `book_images`
--
ALTER TABLE `book_images`
  ADD CONSTRAINT `fk_book_images_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_oi_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_oi_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `fk_osh_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_osh_user` FOREIGN KEY (`changed_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `fk_wishlist_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_wishlist_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
