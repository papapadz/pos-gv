-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 04, 2019 at 12:00 PM
-- Server version: 10.2.17-MariaDB
-- PHP Version: 7.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u443170815_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_beginning_balances`
--

CREATE TABLE `tbl_beginning_balances` (
  `beginning_balance_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `balance` double NOT NULL,
  `cash_count` double NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_beginning_balances`
--

INSERT INTO `tbl_beginning_balances` (`beginning_balance_id`, `user_id`, `balance`, `cash_count`, `is_active`, `created_at`, `updated_at`) VALUES
(9, 1, 500, 0, 1, '2019-02-12 13:48:14', '2019-02-12 13:48:14'),
(10, 1, 500, 0, 1, '2019-02-19 11:17:34', '2019-02-19 11:17:34'),
(11, 1, 0, 0, 1, '2019-02-20 14:23:56', '2019-02-20 14:23:56');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_credits`
--

CREATE TABLE `tbl_credits` (
  `credit_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `contact_no` varchar(50) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `date_paid` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expenses`
--

CREATE TABLE `tbl_expenses` (
  `expense_id` int(11) NOT NULL,
  `expense_name` varchar(100) NOT NULL,
  `expense_category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_expenses`
--

INSERT INTO `tbl_expenses` (`expense_id`, `expense_name`, `expense_category_id`) VALUES
(1, 'Rent', 6),
(2, 'Basil', 10),
(3, 'Spotify', 8),
(4, 'Bacon', 1),
(5, 'Cream of Mushroom', 1),
(6, 'Bacon', 1),
(7, 'All Purpose Cream', 1),
(8, 'Onion', 1),
(9, 'Full Cream', 1),
(10, 'Mushroom', 1),
(11, 'Black Pepper', 1),
(12, 'Thyme', 1),
(13, 'Fried Garlic', 1),
(14, 'Salt', 1),
(15, 'Pasta', 1),
(16, 'Parmesan', 1),
(17, 'Smoked Fish', 1),
(18, 'Longganisa ', 1),
(19, 'Diced Tomato', 1),
(20, 'Pesto', 1),
(21, 'Black Olives', 1),
(22, 'Butter', 1),
(23, 'Patis', 1),
(24, 'Olive Oil', 1),
(25, 'Cherry Tomato', 1),
(26, 'French Beans', 1),
(27, 'Spanish Sardined', 1),
(28, 'Peanuts', 1),
(29, 'Red Bell pepper ', 1),
(30, 'Shallots', 1),
(31, 'Chicken', 1),
(32, 'Fresh Garlic', 1),
(33, 'Sun Dried Tomato', 1),
(34, 'Wheat Bread', 1),
(35, 'Tuna', 1),
(36, 'Mayonnaise ', 1),
(37, 'Sliced Cheese', 1),
(38, 'Pick-Nik', 1),
(39, 'Tomato', 1),
(40, 'Lettuce ', 1),
(41, 'Cucumber', 1),
(42, 'Corn Kernel', 1),
(43, 'Red Cabbage', 1),
(44, 'Carrots', 1),
(45, 'Orange', 1),
(46, 'Sesame Seeds ', 1),
(47, 'Gamet ', 1),
(48, 'Honey', 1),
(49, 'Calamansi', 1),
(50, 'Honey Lemon Powder', 1),
(51, 'Nutella ', 1),
(52, 'Banana', 1),
(53, 'Butter', 1),
(54, 'Lemon Grass Concentrate ', 1),
(55, 'Vinegar', 1),
(56, 'Mango', 1),
(57, 'Brown Sugar', 1),
(58, 'Blue Pea Flower', 1),
(59, 'Kulot ', 1),
(60, 'Kulot ', 1),
(61, 'Kulot ', 1),
(62, 'Turmeric Powder', 1),
(63, 'Mulberry Jam', 1),
(64, 'Washed Sugar', 1),
(65, 'Flax Seeds', 1),
(66, 'Bugnay Wine', 1),
(67, 'Cream Style Corn', 1),
(68, 'Granola Mix', 1),
(69, 'Chia Seeds', 1),
(70, 'Cinnamon ', 1),
(71, 'Cayenne Pepper', 1),
(72, 'Apple Cider', 1),
(73, 'Condense Milk', 1),
(74, 'Kalingga Coffee', 1),
(75, 'Rubusta Coffee', 1),
(76, 'Instant Coffee Powder', 1),
(77, 'Peppermint', 1),
(78, 'Hazel Nut', 1),
(79, 'Salted Caramel', 1),
(80, 'Vanilla Ice Cream', 1),
(81, 'Crushed Grahams', 1),
(82, 'Tostillas', 1),
(83, 'Vegetable Oil', 1),
(84, 'Kimchi', 1),
(85, 'Brown Rice', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expense_categories`
--

CREATE TABLE `tbl_expense_categories` (
  `expense_category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_expense_categories`
--

INSERT INTO `tbl_expense_categories` (`expense_category_id`, `category_name`) VALUES
(1, 'Cost of Sales'),
(2, 'Marketing and Ads'),
(3, 'Salaries and Benefits'),
(4, 'Direct Operating Expenses'),
(5, 'General and Administrative Cost'),
(6, 'Occupancy Cost'),
(7, 'Repairs and Maintenance'),
(8, 'Music and Entertainment'),
(9, 'Utilities'),
(10, 'Panzian');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expense_reports`
--

CREATE TABLE `tbl_expense_reports` (
  `expense_report_id` int(11) NOT NULL,
  `expense_name_id` int(11) NOT NULL,
  `expense_amount` double NOT NULL,
  `payment_type` int(11) NOT NULL COMMENT '0 - cash, 1 - n-cash, 2 - AP',
  `report_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_extra_charges`
--

CREATE TABLE `tbl_extra_charges` (
  `extra_charge_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `charge_name` varchar(200) NOT NULL,
  `charge_amount` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_extra_charges`
--

INSERT INTO `tbl_extra_charges` (`extra_charge_id`, `transaction_id`, `charge_name`, `charge_amount`) VALUES
(1, 13, 'T.o box', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_green_perks`
--

CREATE TABLE `tbl_green_perks` (
  `perk_id` int(11) NOT NULL,
  `card_num` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `contact_num` varchar(25) NOT NULL,
  `birthday` date NOT NULL,
  `total_points` double NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE `tbl_products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(30) NOT NULL,
  `product_category` int(11) NOT NULL DEFAULT 1,
  `unit_price_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `img_file` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`product_id`, `product_name`, `product_category`, `unit_price_id`, `quantity`, `img_file`, `created_at`, `updated_at`) VALUES
(16, 'Patapat', 1, 5, 0, '16.jpg', '2019-02-12 13:51:10', '2019-02-19 11:18:20'),
(17, 'Kabigan Refreshment', 2, 6, 0, '17.jpg', '2019-02-20 13:50:24', '2019-02-20 15:42:16'),
(18, 'Badoc Island Coolers', 2, 7, 0, '18.jpg', '2019-02-20 13:59:43', '2019-02-20 15:41:52'),
(19, 'Anuplig Refreshment', 2, 8, 0, '19.jpg', '2019-02-20 14:00:33', '2019-02-20 15:40:48'),
(20, 'Avis Refreshment', 2, 9, 0, '20.jpg', '2019-02-20 14:01:04', '2019-02-20 15:41:30'),
(21, 'Mulberry Banana Milkshake', 2, 10, 0, '21.jpg', '2019-02-20 14:01:39', '2019-02-20 15:42:43'),
(22, 'Iced Coffee', 2, 11, 0, NULL, '2019-02-20 14:01:58', '2019-02-20 14:01:58'),
(23, 'Calle Crisologo Classic', 3, 12, 0, NULL, '2019-02-20 14:03:12', '2019-02-20 14:03:12'),
(24, 'Calle Crisologo Supreme', 3, 13, 0, '24.jpg', '2019-02-20 14:03:48', '2019-02-20 15:47:16'),
(25, 'Kapurpurawan', 1, 14, 0, '25.jpg', '2019-02-20 14:04:23', '2019-02-20 15:44:02'),
(26, 'La Vigen', 1, 15, 0, '26.jpg', '2019-02-20 14:04:47', '2019-02-20 15:44:36'),
(27, 'Cape Bojeador', 1, 16, 0, '27.jpg', '2019-02-20 14:05:12', '2019-02-20 15:43:26'),
(28, 'Pasaleng', 1, 17, 0, '28.jpg', '2019-02-20 14:05:31', '2019-02-20 15:45:34'),
(29, 'Adams', 3, 18, 0, '29.jpg', '2019-02-20 14:05:45', '2019-02-20 15:46:51'),
(31, 'Food Itenerary 1', 6, 20, 0, NULL, '2019-02-20 14:23:23', '2019-02-20 14:23:23'),
(32, 'Rice Coffee (300g)', 5, 21, 0, NULL, '2019-02-20 16:03:23', '2019-02-20 16:03:49'),
(33, 'Rice Coffee (400g)', 5, 22, 0, NULL, '2019-02-20 16:11:46', '2019-02-20 16:11:46'),
(34, 'Rice Coffee 3in1', 5, 23, 0, NULL, '2019-02-20 16:12:22', '2019-02-20 16:12:22'),
(35, 'Honey', 5, 24, 0, NULL, '2019-02-20 16:12:45', '2019-02-20 16:12:45'),
(36, 'Brown Rice', 5, 25, 0, NULL, '2019-02-20 16:13:39', '2019-02-20 16:13:39'),
(37, 'Soy Bean Coffee', 5, 26, 0, NULL, '2019-02-20 16:14:30', '2019-02-20 16:14:30'),
(38, 'Granola Bars', 5, 27, 0, NULL, '2019-02-20 16:15:18', '2019-02-20 16:15:18'),
(39, 'Granola Mix', 5, 28, 0, NULL, '2019-02-20 16:15:37', '2019-02-20 16:15:37'),
(40, 'Cookies', 5, 29, 0, NULL, '2019-02-20 16:15:57', '2019-02-20 16:15:57'),
(41, 'Multigrain Buns', 5, 30, 0, NULL, '2019-02-20 16:16:41', '2019-02-20 16:16:41'),
(42, 'Mulberry Jam', 5, 31, 0, NULL, '2019-02-20 16:17:03', '2019-02-20 16:17:03'),
(43, 'Lemon Grass Concentrate', 5, 32, 0, NULL, '2019-02-20 16:17:32', '2019-02-20 16:17:32'),
(44, 'Mulberry Plant', 5, 33, 0, NULL, '2019-02-20 16:17:51', '2019-02-20 16:17:51'),
(45, 'Chia Seeds', 5, 34, 0, NULL, '2019-02-20 16:18:27', '2019-02-20 16:18:27'),
(46, 'Flax Seeds', 5, 35, 0, NULL, '2019-02-20 16:18:51', '2019-02-20 16:18:51'),
(47, 'Flax Seeds Jar', 5, 36, 0, NULL, '2019-02-20 16:19:31', '2019-02-20 16:19:31'),
(48, 'Almond Milk', 5, 37, 0, NULL, '2019-02-20 16:20:04', '2019-02-20 16:20:04'),
(49, 'Food Itinerary 2', 6, 38, 0, NULL, '2019-02-20 16:27:12', '2019-02-20 16:27:12'),
(50, 'Food Itinerary 3', 6, 39, 0, NULL, '2019-02-20 16:27:40', '2019-02-20 16:28:52'),
(51, 'Food Itinerary 4', 6, 40, 0, NULL, '2019-02-20 16:28:12', '2019-02-20 16:28:12'),
(52, 'Food Itinerary 5', 6, 41, 0, NULL, '2019-02-20 16:29:18', '2019-02-20 16:29:18'),
(53, 'Saud Mulberry Sundae', 4, 42, 0, NULL, '2019-02-20 16:32:48', '2019-02-20 16:32:48'),
(54, 'Saud Mulberry Sundae', 4, 43, 0, NULL, '2019-02-20 16:34:09', '2019-02-20 16:34:09');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_categories`
--

CREATE TABLE `tbl_product_categories` (
  `category_id` int(11) NOT NULL,
  `category` varchar(30) NOT NULL,
  `description` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_product_categories`
--

INSERT INTO `tbl_product_categories` (`category_id`, `category`, `description`, `created_at`, `updated_at`) VALUES
(1, 'PASTA', '', '2018-12-17 15:19:49', '2018-12-17 15:19:49'),
(2, 'DRINKS', 'Anuplig', '2018-12-17 15:19:49', '2019-02-20 13:48:56'),
(3, 'SANDWICHES', '', '2018-12-17 15:19:49', '2018-12-17 15:19:49'),
(4, 'DESSERTS', '', '2019-02-20 16:24:40', '2019-02-20 16:24:40'),
(5, 'MERCATO', '', '2019-01-13 18:05:24', '2019-01-13 18:05:24'),
(6, 'FOOD ITINERARY', '', '2019-01-19 22:21:36', '2019-01-19 22:21:36'),
(7, 'SALADS', '', '2019-02-20 14:10:09', '2019-02-20 14:10:56');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_prices`
--

CREATE TABLE `tbl_product_prices` (
  `price_id` int(11) NOT NULL,
  `unit_price` double NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_product_prices`
--

INSERT INTO `tbl_product_prices` (`price_id`, `unit_price`, `created_at`, `updated_at`) VALUES
(5, 145, '2019-02-12 13:51:10', '2019-02-12 13:51:10'),
(6, 70, '2019-02-20 13:50:24', '2019-02-20 13:50:24'),
(7, 70, '2019-02-20 13:59:43', '2019-02-20 13:59:43'),
(8, 80, '2019-02-20 14:00:33', '2019-02-20 14:00:33'),
(9, 80, '2019-02-20 14:01:04', '2019-02-20 14:01:04'),
(10, 105, '2019-02-20 14:01:39', '2019-02-20 14:01:39'),
(11, 90, '2019-02-20 14:01:58', '2019-02-20 14:01:58'),
(12, 100, '2019-02-20 14:03:12', '2019-02-20 14:03:12'),
(13, 150, '2019-02-20 14:03:48', '2019-02-20 14:03:48'),
(14, 145, '2019-02-20 14:04:23', '2019-02-20 14:04:23'),
(15, 165, '2019-02-20 14:04:47', '2019-02-20 14:04:47'),
(16, 165, '2019-02-20 14:05:12', '2019-02-20 14:05:12'),
(17, 175, '2019-02-20 14:05:31', '2019-02-20 14:05:31'),
(18, 100, '2019-02-20 14:05:45', '2019-02-20 14:05:45'),
(19, 100, '2019-02-20 14:05:59', '2019-02-20 14:05:59'),
(20, 245, '2019-02-20 14:23:23', '2019-02-20 14:23:23'),
(21, 100, '2019-02-20 16:03:23', '2019-02-20 16:03:23'),
(22, 150, '2019-02-20 16:11:46', '2019-02-20 16:11:46'),
(23, 200, '2019-02-20 16:12:22', '2019-02-20 16:12:22'),
(24, 275, '2019-02-20 16:12:45', '2019-02-20 16:12:45'),
(25, 75, '2019-02-20 16:13:39', '2019-02-20 16:13:39'),
(26, 150, '2019-02-20 16:14:30', '2019-02-20 16:14:30'),
(27, 55, '2019-02-20 16:15:18', '2019-02-20 16:15:18'),
(28, 55, '2019-02-20 16:15:37', '2019-02-20 16:15:37'),
(29, 55, '2019-02-20 16:15:57', '2019-02-20 16:15:57'),
(30, 30, '2019-02-20 16:16:41', '2019-02-20 16:16:41'),
(31, 160, '2019-02-20 16:17:03', '2019-02-20 16:17:03'),
(32, 210, '2019-02-20 16:17:32', '2019-02-20 16:17:32'),
(33, 100, '2019-02-20 16:17:51', '2019-02-20 16:17:51'),
(34, 25, '2019-02-20 16:18:27', '2019-02-20 16:18:27'),
(35, 25, '2019-02-20 16:18:51', '2019-02-20 16:18:51'),
(36, 250, '2019-02-20 16:19:31', '2019-02-20 16:19:31'),
(37, 175, '2019-02-20 16:20:04', '2019-02-20 16:20:04'),
(38, 245, '2019-02-20 16:27:12', '2019-02-20 16:27:12'),
(39, 165, '2019-02-20 16:27:40', '2019-02-20 16:27:40'),
(40, 165, '2019-02-20 16:28:12', '2019-02-20 16:28:12'),
(41, 275, '2019-02-20 16:29:18', '2019-02-20 16:29:18'),
(42, 100, '2019-02-20 16:32:48', '2019-02-20 16:32:48'),
(43, 100, '2019-02-20 16:34:09', '2019-02-20 16:34:09');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_promos`
--

CREATE TABLE `tbl_promos` (
  `promo_id` int(11) NOT NULL,
  `promo_name` varchar(100) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_promos`
--

INSERT INTO `tbl_promos` (`promo_id`, `promo_name`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 'Senior Citizen Discount', 1, '2019-02-12 00:00:00', '2019-02-12 05:19:54'),
(5, 'Special Offers', 1, '2019-02-12 00:00:00', '2019-02-12 05:19:54');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sales`
--

CREATE TABLE `tbl_sales` (
  `sales_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `promo_id` int(11) NOT NULL DEFAULT 0,
  `discount_amount` double DEFAULT 0,
  `price_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_sales`
--

INSERT INTO `tbl_sales` (`sales_id`, `product_id`, `qty`, `transaction_id`, `promo_id`, `discount_amount`, `price_id`) VALUES
(20, 16, 1, 13, 0, 0, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transactions`
--

CREATE TABLE `tbl_transactions` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `perk_id` int(11) NOT NULL DEFAULT 0,
  `is_paid` int(11) NOT NULL DEFAULT 0 COMMENT '0 - not yet paid, 1 - paid, 2 - credit/payable, 3 - cancelled',
  `cash_tendered` double NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_transactions`
--

INSERT INTO `tbl_transactions` (`transaction_id`, `user_id`, `perk_id`, `is_paid`, `cash_tendered`, `created_at`, `updated_at`) VALUES
(13, 1, 0, 0, 0, '2019-02-12 13:52:15', '2019-02-12 13:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `birthdate` date NOT NULL,
  `user_level` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `username`, `password`, `first_name`, `last_name`, `birthdate`, `user_level`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$0YzaO2p3G8fABkFW0EYdouIXKM0HWWjq.ecDbTrbwtK17DWgQTT8O', 'Admin', 'Admin', '1991-01-01', 1, '2019-01-09 06:16:16', '2019-01-21 19:08:41'),
(4, 'cashier', '$2y$10$mEWL8nM6xeXhMJCkBsxtAeoUGdJZoi3iyvnRb4xvoKAe9kWxFWdG.', 'Cashier', 'Cashier', '1991-01-01', 0, '2019-01-21 19:05:27', '2019-01-21 19:09:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_beginning_balances`
--
ALTER TABLE `tbl_beginning_balances`
  ADD PRIMARY KEY (`beginning_balance_id`);

--
-- Indexes for table `tbl_credits`
--
ALTER TABLE `tbl_credits`
  ADD PRIMARY KEY (`credit_id`);

--
-- Indexes for table `tbl_expenses`
--
ALTER TABLE `tbl_expenses`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `tbl_expense_categories`
--
ALTER TABLE `tbl_expense_categories`
  ADD PRIMARY KEY (`expense_category_id`);

--
-- Indexes for table `tbl_expense_reports`
--
ALTER TABLE `tbl_expense_reports`
  ADD PRIMARY KEY (`expense_report_id`);

--
-- Indexes for table `tbl_extra_charges`
--
ALTER TABLE `tbl_extra_charges`
  ADD PRIMARY KEY (`extra_charge_id`);

--
-- Indexes for table `tbl_green_perks`
--
ALTER TABLE `tbl_green_perks`
  ADD PRIMARY KEY (`perk_id`);

--
-- Indexes for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `tbl_product_categories`
--
ALTER TABLE `tbl_product_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tbl_product_prices`
--
ALTER TABLE `tbl_product_prices`
  ADD PRIMARY KEY (`price_id`);

--
-- Indexes for table `tbl_promos`
--
ALTER TABLE `tbl_promos`
  ADD PRIMARY KEY (`promo_id`);

--
-- Indexes for table `tbl_sales`
--
ALTER TABLE `tbl_sales`
  ADD PRIMARY KEY (`sales_id`);

--
-- Indexes for table `tbl_transactions`
--
ALTER TABLE `tbl_transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_beginning_balances`
--
ALTER TABLE `tbl_beginning_balances`
  MODIFY `beginning_balance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_credits`
--
ALTER TABLE `tbl_credits`
  MODIFY `credit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_expenses`
--
ALTER TABLE `tbl_expenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `tbl_expense_categories`
--
ALTER TABLE `tbl_expense_categories`
  MODIFY `expense_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_expense_reports`
--
ALTER TABLE `tbl_expense_reports`
  MODIFY `expense_report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_extra_charges`
--
ALTER TABLE `tbl_extra_charges`
  MODIFY `extra_charge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_green_perks`
--
ALTER TABLE `tbl_green_perks`
  MODIFY `perk_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `tbl_product_categories`
--
ALTER TABLE `tbl_product_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_product_prices`
--
ALTER TABLE `tbl_product_prices`
  MODIFY `price_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `tbl_promos`
--
ALTER TABLE `tbl_promos`
  MODIFY `promo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_sales`
--
ALTER TABLE `tbl_sales`
  MODIFY `sales_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_transactions`
--
ALTER TABLE `tbl_transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
