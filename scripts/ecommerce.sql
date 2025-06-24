-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2025 at 12:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `first_name`, `last_name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(10, 'super', 'admin', 'casandra101916@gmail.com', '$2y$12$ssRcNeXOB8Rtzv6xiqNyeuz4FNaYN.eAxhlfJOaUgCRdLB6Y.OFem', 'nUdoBKl8psjMAB55YSpEWevDvZa6IbZEum6Tiu3HzsQn3AhjV5AIyISFdFzD', '2025-04-22 13:24:47', '2025-06-17 04:26:39', 'super_admin'),
(14, 'kirk', 'gozon', 'kirkgozon19@gmail.com', '$2y$12$IFXaTqlxOLfBBiZy9vacQuRzCn9zeWUGUNd/18BA5HXr3HwAqhmV.', NULL, '2025-06-17 09:59:07', '2025-06-17 09:59:07', 'admin'),
(15, 'julian', 'mistica', 'jjmistica@student.apc.edu.ph', '$2y$12$HNqgIOcrJmAmOldZaqZKyeSojjp64g.lQdeLZ4mMEIVPYzjSQPydS', NULL, '2025-06-17 17:10:03', '2025-06-17 17:10:03', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_reset_tokens`
--

CREATE TABLE `admin_password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('queensrebo_cache_5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1748090512),
('queensrebo_cache_5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1748090512;', 1748090512);

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL,
  `keywords` text NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `keywords`, `answer`, `created_at`, `updated_at`) VALUES
(1, 'Store Hours', 'store hours,opening hours,what time do you open,hours of operation', 'Our store is open from 9 AM to 6 PM, Monday to Saturday.', '2025-06-16 05:20:43', '2025-06-16 05:26:04'),
(2, 'location', 'where,address,located,location', 'asia pacific college', '2025-06-17 17:05:47', '2025-06-17 20:07:14'),
(3, 'shipping', 'shipping,delivery,do you ship,courier', 'We offer nationwide shipping via J&T and LBC.', '2025-06-17 20:09:25', '2025-06-17 20:09:25');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_04_16_101232_create_admins_table', 1),
(5, '2025_04_16_101709_create_products_table', 1),
(6, '2025_04_16_101717_create_orders_table', 1),
(7, '2025_04_16_101731_create_order_items_table', 1),
(8, '2025_04_24_184819_add_order_number_to_orders_table', 2),
(9, '2025_04_24_190058_make_order_number_unique', 3),
(10, '2025_05_06_014744_add_shipping_fields_to_orders_table', 4),
(11, '2025_05_07_021310_add_category_to_products_table', 5),
(12, '2025_05_25_011947_create_order_histories_table', 6),
(13, '2025_05_28_082835_create_password_reset_tokens_table', 7),
(14, '2025_06_04_030407_create_admin_password_reset_tokens_table', 8),
(15, '2025_06_13_124217_create_faqs_table', 9),
(16, '2025_06_17_034645_add_role_to_admins_table', 10),
(17, '2025_06_17_055222_remove_email_verified_at_from_admins_table', 11),
(18, '2025_06_17_124550_add_sku_to_products_table', 12),
(19, '2025_06_17_170642_update_name_to_first_name_and_add_last_name_to_users_table', 13),
(20, '2025_06_17_175440_update_admins_table_name_to_first_last', 14),
(21, '2025_06_18_022523_add_sku_and_product_image_to_order_items_table', 15);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_status` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `paypal_order_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `recipient_name` varchar(255) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `total_amount`, `order_status`, `payment_status`, `paypal_order_id`, `created_at`, `updated_at`, `recipient_name`, `shipping_address`) VALUES
(22, '68196FBAE43F2', 45, 6105.00, 'paid', 'completed', '2AL29324WA423163D', '2025-05-05 18:11:07', '2025-05-06 15:26:01', 'casper gozon', 'kiran, 671, manila, BILIRAN, 1007, PH'),
(23, '681A9E5FB2CA7', 45, 1000.00, 'paid', 'completed', '1R242276BU5952144', '2025-05-06 15:42:23', '2025-05-15 03:43:15', 'clarenz gozon', 'kiran residences, 671, manila, METRO MANILA, 1007, PH'),
(24, '681AB837DDF2B', 45, 500.00, 'paid', 'completed', '513526819Y9007426', '2025-05-06 17:32:39', '2025-05-06 17:32:39', 'casper gozon', 'kiran residences, 671, manila, MANILA, 1007, PH'),
(25, '6825407D51BB7', 47, 125.00, 'paid', 'completed', '3J180607N57479344', '2025-05-14 17:16:45', '2025-05-14 17:16:45', 'John Doe', '1 Main St, San Jose, CA, 95131, US'),
(26, '682540F7BF4EF', 47, 125.00, 'paid', 'completed', '03364338T1374221F', '2025-05-14 17:18:47', '2025-05-14 17:18:47', 'John Doe', '1 Main St, San Jose, CA, 95131, US'),
(27, '68271E756B2B8', 45, 1110.00, 'preparing', 'completed', '1140929922760871B', '2025-05-16 03:16:05', '2025-05-24 17:57:08', 'John Doe', '1 Main St, San Jose, CA, 95131, US'),
(28, '683DADD65BA0D', 45, 35597.00, 'paid', 'completed', '11E44621PY5939628', '2025-06-02 05:57:42', '2025-06-02 05:57:42', 'casper gozon', 'pcs residences,calixto dyco st. paco manila, 671, manila, MANILA, 1007, PH'),
(29, '683DAFC5195D0', 45, 1000.00, 'paid', 'completed', '8Y637854V59145824', '2025-06-02 06:05:57', '2025-06-02 06:05:57', 'John Doe', '1 Main St, San Jose, CA, 95131, US'),
(30, '683DB0E30BD97', 45, 1000.00, 'paid', 'completed', '5LR250959D051581H', '2025-06-02 06:10:43', '2025-06-02 06:10:43', 'kirk clarenz gozon', 'manila, 681, manila, BOHOL, 1000, PH'),
(31, '683DB21A3D6C6', 45, 1000.00, 'preparing', 'completed', '8KP08037T6855630M', '2025-06-02 06:15:54', '2025-06-03 18:59:36', 'kirk clarenz gozon', 'erwerwr, 123, manila, BOHOL, 1000, PH'),
(33, '683FF3368BA27', 58, 12600.00, 'paid', 'completed', '5NH4327141023913T', '2025-06-03 23:18:14', '2025-06-03 23:18:14', 'John Doe', '1 Main St, San Jose, CA, 95131, US'),
(34, '6840306A0BB4C', 61, 4000.00, 'preparing', 'completed', '8WH69382WU1344008', '2025-06-04 03:39:22', '2025-06-04 03:40:20', 'john doe', 'test add, 671, manila, METRO MANILA, 1007, PH'),
(35, '6851AFD152C18', 45, 1150.00, 'paid', 'completed', '3DC29752D48874126', '2025-06-17 10:11:29', '2025-06-17 10:11:29', 'John Doe', '1 Main St, San Jose, CA, 95131, US'),
(36, '685241D445801', 45, 3000.00, 'paid', 'completed', '8XT78997XJ102500N', '2025-06-17 20:34:28', '2025-06-17 20:34:28', 'John Doe', '1 Main St, San Jose, CA, 95131, US'),
(37, '685242909B85F', 45, 350.00, 'paid', 'completed', '7X274384HL034034A', '2025-06-17 20:37:36', '2025-06-17 20:37:36', 'John Doe', '1 Main St, San Jose, CA, 95131, US'),
(38, '685243FFBE68E', 45, 300.00, 'paid', 'completed', '8P266466B6243864U', '2025-06-17 20:43:43', '2025-06-17 20:43:43', 'John Doe', '1 Main St, San Jose, CA, 95131, US'),
(39, '685262C1DA339', 45, 300.00, 'paid', 'completed', '3RB424351V1557442', '2025-06-17 22:54:57', '2025-06-17 22:54:57', 'John Doe', '1 Main St, San Jose, CA, 95131, US');

-- --------------------------------------------------------

--
-- Table structure for table `order_histories`
--

CREATE TABLE `order_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_histories`
--

INSERT INTO `order_histories` (`id`, `order_id`, `status`, `message`, `created_at`, `updated_at`) VALUES
(1, 27, 'preparing', 'Status changed from paid to preparing.', '2025-05-24 17:57:08', '2025-05-24 17:57:08'),
(2, 31, 'preparing', 'Status changed from paid to preparing.', '2025-06-03 18:59:36', '2025-06-03 18:59:36'),
(4, 34, 'preparing', 'Status changed from paid to preparing.', '2025-06-04 03:40:20', '2025-06-04 03:40:20');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `sku`, `product_image`, `quantity`, `unit_price`, `created_at`, `updated_at`) VALUES
(24, 22, 11, '352E', 'YSU5JPYB', 'products/ge4xPuI5lRQNEmx4aXI8uKP8yqKaEvc9pwPfF6nr.png', 3, 160.00, '2025-05-05 18:11:07', '2025-05-05 18:11:07'),
(25, 22, 12, '203215', 'MUMXER0Q', 'products/wwBLXQTFQik4m2JClrebSN76CEx6bfcjsollu5Da.png', 5, 125.00, '2025-05-05 18:11:07', '2025-05-05 18:11:07'),
(26, 22, 13, 'wall', 'AZBG22OI', 'products/iXn2CIFIQIz87aCdKcHbtVTpRtNNL8zZnUQ14Quc.jpg', 5, 1000.00, '2025-05-05 18:11:07', '2025-05-05 18:11:07'),
(27, 23, 13, 'wall', 'AZBG22OI', 'products/iXn2CIFIQIz87aCdKcHbtVTpRtNNL8zZnUQ14Quc.jpg', 1, 1000.00, '2025-05-06 15:42:23', '2025-05-06 15:42:23'),
(28, 24, 12, '203215', 'MUMXER0Q', 'products/wwBLXQTFQik4m2JClrebSN76CEx6bfcjsollu5Da.png', 4, 125.00, '2025-05-06 17:32:39', '2025-05-06 17:32:39'),
(29, 25, 12, '203215', 'MUMXER0Q', 'products/wwBLXQTFQik4m2JClrebSN76CEx6bfcjsollu5Da.png', 1, 125.00, '2025-05-14 17:16:45', '2025-05-14 17:16:45'),
(30, 26, 12, '203215', 'MUMXER0Q', 'products/wwBLXQTFQik4m2JClrebSN76CEx6bfcjsollu5Da.png', 1, 125.00, '2025-05-14 17:18:47', '2025-05-14 17:18:47'),
(31, 27, 5, 'TT08', 'N8OLFRYT', 'products/iXn2CIFIQIz87aCdKcHbtVTpRtNNL8zZnUQ14Quc.jpg', 10, 111.00, '2025-05-16 03:16:05', '2025-05-16 03:16:05'),
(32, 28, 5, 'TT08', 'N8OLFRYT', 'products/iXn2CIFIQIz87aCdKcHbtVTpRtNNL8zZnUQ14Quc.jpg', 2, 111.00, '2025-06-02 05:57:42', '2025-06-02 05:57:42'),
(33, 28, 12, '203215', 'MUMXER0Q', 'products/wwBLXQTFQik4m2JClrebSN76CEx6bfcjsollu5Da.png', 283, 125.00, '2025-06-02 05:57:42', '2025-06-02 05:57:42'),
(34, 29, 13, 'wall', 'AZBG22OI', 'products/iXn2CIFIQIz87aCdKcHbtVTpRtNNL8zZnUQ14Quc.jpg', 1, 1000.00, '2025-06-02 06:05:57', '2025-06-02 06:05:57'),
(35, 30, 13, 'wall', 'AZBG22OI', 'products/iXn2CIFIQIz87aCdKcHbtVTpRtNNL8zZnUQ14Quc.jpg', 1, 1000.00, '2025-06-02 06:10:43', '2025-06-02 06:10:43'),
(36, 31, 13, 'wall', 'AZBG22OI', 'products/iXn2CIFIQIz87aCdKcHbtVTpRtNNL8zZnUQ14Quc.jpg', 1, 1000.00, '2025-06-02 06:15:54', '2025-06-02 06:15:54'),
(37, 32, 19, 'WPC INDOOR PANEL SQUARED GROOVED', 'YYOTUG3H', 'products/vv68icPax5qVju2XWdMdwAXxcPvUTaiNTo8G3GzA.png', 2, 850.00, '2025-06-03 21:33:10', '2025-06-03 21:33:10'),
(39, 33, 7, 'HS3301', 'J7DPN78I', 'products/uBLlxjmg7hUjgGFjeUx4iDJeS35QbcYyGy6ITWm1.png', 40, 115.00, '2025-06-03 23:18:14', '2025-06-03 23:18:14'),
(40, 33, 11, '352E', 'YSU5JPYB', 'products/ge4xPuI5lRQNEmx4aXI8uKP8yqKaEvc9pwPfF6nr.png', 50, 160.00, '2025-06-03 23:18:14', '2025-06-03 23:18:14'),
(41, 34, 19, 'WPC INDOOR PANEL SQUARED GROOVED', 'YYOTUG3H', 'products/vv68icPax5qVju2XWdMdwAXxcPvUTaiNTo8G3GzA.png', 2, 850.00, '2025-06-04 03:39:22', '2025-06-04 03:39:22'),
(42, 34, 20, 'WPC INDOOR PANEL SQUARE BIG GROOVED', 'C1BLMI9W', 'products/r1w0XIgX7JYWNt2DuelgFf8A7IQLzUwqB6I5jxrk.png', 2, 1150.00, '2025-06-04 03:39:22', '2025-06-04 03:39:22'),
(43, 35, 20, 'WPC INDOOR PANEL SQUARE BIG GROOVED', 'C1BLMI9W', 'products/r1w0XIgX7JYWNt2DuelgFf8A7IQLzUwqB6I5jxrk.png', 1, 1150.00, '2025-06-17 10:11:29', '2025-06-17 10:11:29'),
(44, 36, 7, 'Stone Polymer Composite (SPC) Flooring', 'J7DPN78I', 'products/uBLlxjmg7hUjgGFjeUx4iDJeS35QbcYyGy6ITWm1.png', 10, 300.00, '2025-06-17 20:34:28', '2025-06-17 20:34:28'),
(45, 37, 25, 'BRICK TILE', 'CRLZB0P9', 'products/ICU8Qt3nW4rrltx5D6gkKRDtlloh7RJjOM5A4pmJ.png', 1, 350.00, '2025-06-17 20:37:36', '2025-06-17 20:37:36'),
(46, 38, 24, 'Acara Beige', 'LDDLKWZS', 'products/y7XmzYAz6LZt9U244ueYqnmxKMolUEIbaN8evnQc.jpg', 1, 300.00, '2025-06-17 20:43:43', '2025-06-17 20:43:43'),
(47, 39, 24, 'Acara Beige', 'LDDLKWZS', 'products/y7XmzYAz6LZt9U244ueYqnmxKMolUEIbaN8evnQc.jpg', 1, 300.00, '2025-06-17 22:54:57', '2025-06-17 22:54:57');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `date_created` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `sku`, `price`, `image`, `description`, `quantity`, `date_created`, `created_at`, `updated_at`, `category`) VALUES
(2, 'Wassernison Bidet Complete Set Polished Chrome Nbcs-92Pc', 'UMWXPNWE', 600.00, 'products/8SW413s4YlelcvMlwaKvbdz7qHyIvlaPl3Fmmd60.png', 'ABS plastic head and body\r\nSuper Strong water spray\r\n\r\nPolished chrome finish\r\nWith hose and mount\r\nStainless steel hose\r\n120cm hose\r\n\r\nPackaging Dimension: 17x6x26', 149, NULL, '2025-04-22 09:20:57', '2025-06-17 04:48:32', 'Sanitary Wares'),
(3, 'Ivora White Tile', 'GHOBAKXN', 350.00, 'products/3JUCYiDKZLcNgXI8l6wd3v6vE28EZO2IJ39pkZZ7.jpg', 'Sold per piece\r\nGlazed Ceramic Tile\r\nSize: 595mm x 1195mm', 100, NULL, '2025-04-22 09:25:33', '2025-06-17 16:20:21', 'Tiles'),
(4, 'Carrara Mist Tile', 'LJ6EBMQG', 449.00, 'products/Q9XEfMibuc6nU5q3UImBKNYWdXfVinYhaa9ODfuj.jpg', 'Sold per piece\r\nGlazed Ceramic Tile\r\nSize: 595mm x 1195mm', 70, NULL, '2025-04-22 09:27:40', '2025-06-17 16:20:33', 'Tiles'),
(5, 'Tile Leveling Pliers', 'N8OLFRYT', 111.00, 'products/iXn2CIFIQIz87aCdKcHbtVTpRtNNL8zZnUQ14Quc.jpg', 'Tile Leveling System Pliers (Tongs)', 50, NULL, '2025-04-22 09:29:37', '2025-06-17 16:14:35', 'Tools, Tile Spacers & Levelers'),
(7, 'Stone Polymer Composite (SPC) Flooring', 'J7DPN78I', 300.00, 'products/uBLlxjmg7hUjgGFjeUx4iDJeS35QbcYyGy6ITWm1.png', 'Sold per piece\r\nStone Polymer Composite (SPC) Vinyl\r\nSize: 180mm x 1220mm', 70, NULL, '2025-04-22 09:34:40', '2025-06-17 20:34:28', 'Vinyl'),
(8, 'WPC BAMBOO CHARCOAL VEENER - STONE SERIES', 'FVZ6PB99', 449.00, 'products/JdyWnUgCFioyFJTigbNWpXLPSoigGQfrAfcBwHZf.png', 'Sold per piece\r\nDesign: Nero Maquina\r\nSize: 8mm x 1200mm x 2.90m', 0, NULL, '2025-04-22 09:36:49', '2025-06-17 16:21:19', 'Tiles'),
(9, 'Tile Grout SK-7', '7FIXZYQ8', 150.00, 'products/gQfZRzoxTZBw0KmZBeC97Ke3gykDvwMWTe3fwK2y.jpg', 'High-quality cement-based grout for ceramic, porcelain, and stole tiles. Easy to apply, water-resistant, and ideal for both wall and floor use. Suitable for indoor and outdoor applications\r\nSize: 2kg pack', 10, NULL, '2025-04-22 09:39:55', '2025-06-17 15:52:21', 'Tile Adhesive, Grout & Epoxy'),
(10, 'Adhesive Sealant', '0IZZRD0Z', 150.00, 'products/AxDdFwrq2nVhdFbgf4LXxn1dcgVxOHbAs0xzk2aG.png', 'Multi-purpose, high-strength sealant ideal for bonding and sealing joints, gaps, and cracks. Flexible, waterproof, and suitable for use on tiles, glass, metal, wood and concrete.\r\nSize: 300ml tube', 80, NULL, '2025-04-22 09:41:41', '2025-06-17 16:32:35', 'Tile Adhesive, Grout & Epoxy'),
(11, 'Urey Brick Wall Cladding Panel', 'YSU5JPYB', 448.00, 'products/ge4xPuI5lRQNEmx4aXI8uKP8yqKaEvc9pwPfF6nr.png', 'PARAMETIC WALL CLADDING PANEL\r\nSold for piece\r\nMaterial: Polyurethane\r\nUsage: Interior and Exterior Wall Decoration\r\nDimension: 25mm x 600mm x .120m', 400, NULL, '2025-04-22 09:42:53', '2025-06-17 16:21:05', 'WPC Panels & Wall Cladding'),
(12, 'Flowing Line Wall Panel', 'MUMXER0Q', 125.00, 'products/wwBLXQTFQik4m2JClrebSN76CEx6bfcjsollu5Da.png', 'PARAMETIC WALL CLADDING PANEL\r\nSold for piece\r\nMaterial: Polyurethane\r\nUsage: Interior and Exterior Wall Decoration\r\nDimension: 30mm x 600mm x 1.20m', 0, NULL, '2025-04-22 09:43:58', '2025-06-17 16:14:03', 'Tiles'),
(17, 'PVC Laminate Wall and Ceiling Panel', 'A1OTYTNM', 400.00, 'products/5leV11vcL5lkqd2VRBy2lV6J8HS2fYq6cPrdnUJp.png', 'Dimension:   8mm x 180mm x 2.97m\r\nCoverage Area in Pc/Sqm.:  1.87', 499, NULL, '2025-06-03 18:22:54', '2025-06-17 04:48:32', 'WPC Panels & Wall Cladding'),
(18, 'WPC INDOOR PANEL SMALL GROOVED', 'VNL23SK9', 650.00, 'products/uJk4BZA2GxHhciu0XnffuvHDecGGPESfEzq2nYYP.png', 'Dimension:   9mm x 155mm x 2.90m', 800, NULL, '2025-06-03 18:25:01', '2025-06-17 04:48:32', 'WPC Panels & Wall Cladding'),
(19, 'WPC INDOOR PANEL SQUARED GROOVED', 'YYOTUG3H', 850.00, 'products/vv68icPax5qVju2XWdMdwAXxcPvUTaiNTo8G3GzA.png', 'Sold per piece\r\nDimension: 24mm x 169mm x 2.90m', 996, NULL, '2025-06-03 18:28:18', '2025-06-17 16:23:41', 'WPC Panels & Wall Cladding'),
(20, 'WPC INDOOR PANEL SQUARE BIG GROOVED', 'C1BLMI9W', 600.00, 'products/r1w0XIgX7JYWNt2DuelgFf8A7IQLzUwqB6I5jxrk.png', 'Sold per piece\r\nDimension:   25mm x 203mm x 2.90m', 696, NULL, '2025-06-03 18:29:43', '2025-06-17 16:24:39', 'WPC Panels & Wall Cladding'),
(24, 'Acara Beige', 'LDDLKWZS', 300.00, 'products/y7XmzYAz6LZt9U244ueYqnmxKMolUEIbaN8evnQc.jpg', 'Sold per piece\r\nGlazed Ceramic Tile\r\nSize: 600mm x 600mm', 198, NULL, '2025-06-04 03:36:53', '2025-06-17 22:54:57', 'Tiles'),
(25, 'BRICK TILE', 'CRLZB0P9', 350.00, 'products/ICU8Qt3nW4rrltx5D6gkKRDtlloh7RJjOM5A4pmJ.png', 'Sold per piece\r\nDIMENSION: 595mm x 1180mm\r\nColor: Fog White', 199, NULL, '2025-06-17 14:38:54', '2025-06-17 20:37:36', 'Tiles');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(45, 'kirk clarenz ', 'gozon', 'kirkgozon19@gmail.com', '2025-04-30 00:20:33', '$2y$12$55ETYpyyscJtXcN9t1c07.b5/aapogaRNxYfE8liMTkp0hiFpzmvm', 'q7Kth2FaLsfHMKQmWvBOTH7pMGDS1A8RAyNrBYlnlJBXrimMXJcmRW7YNMFH', '2025-04-30 00:20:00', '2025-06-17 04:39:28'),
(47, 'test test', 'test1', 'lol@gmail.com', '2025-05-14 17:15:16', '$2y$12$sH5MJkxK6k73doneXL3R3Oiq0YJKsF6vYrJaa8VsIugkOWNm3WKsa', NULL, '2025-05-14 17:14:33', '2025-05-14 17:15:16'),
(48, 'test', 'test2', 'test@gmail.com', NULL, '$2y$12$pXD6YEtIuThL9BEyYZvXyuiuzFLH/5Mo786YZJIGXbDrp7B2PJ2yW', NULL, '2025-05-24 04:27:45', '2025-05-24 04:27:45'),
(49, 'julian', 'test3', 'kakdakad@gmail.com', NULL, '$2y$12$9i87we8sV0etgqmZHXa.2.fNM6tVSFJMom/m6NPvR7Bc4Zc.BYjru', NULL, '2025-06-02 05:19:32', '2025-06-02 05:19:32'),
(50, 'rex', 'test4', 'kadadadadada@gmail.com', NULL, '$2y$12$gTkFj6HCl0pW6DhO1EBH9OCf2CumwHaWFStoO3iXtWdakI7jjdbay', NULL, '2025-06-02 05:27:21', '2025-06-02 05:27:21'),
(51, 'REX', 'test 5', 'julian.mistica11@gmail.com', NULL, '$2y$12$RuSMjBRZ88wWOsW7b.UJKuuC0PxLC0JO5kVUETu48cBNylaRolZpe', NULL, '2025-06-02 05:28:46', '2025-06-02 05:28:46'),
(58, 'kurt sauquillo', 'sauquillo', 'ksauquillo@gmail.com', '2025-06-03 22:54:54', '$2y$12$ovxhGwvLN.89GNYkJ6gOweAMPAkHIEiR00j8MvU/ZSFgVWxtS2vti', 'ElgEVeWEFnBRDk8EJh1ALLAqUCTRht61geiOT3dJoFtOo7qXy8LhPZVO9epR', '2025-06-03 22:54:27', '2025-06-03 22:57:39'),
(61, 'john', 'doe', 'arongozon@gmail.com', '2025-06-04 03:35:01', '$2y$12$OTiR.E5id81BL2EgvIATHOZ291KQqFsLl9yBWkeeBqsDp2KWatUDW', NULL, '2025-06-04 03:34:46', '2025-06-04 03:35:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `admin_password_reset_tokens`
--
ALTER TABLE `admin_password_reset_tokens`
  ADD KEY `admin_password_reset_tokens_email_index` (`email`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`);

--
-- Indexes for table `order_histories`
--
ALTER TABLE `order_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_histories_order_id_foreign` (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD KEY `password_reset_tokens_email_index` (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `order_histories`
--
ALTER TABLE `order_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_histories`
--
ALTER TABLE `order_histories`
  ADD CONSTRAINT `order_histories_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
