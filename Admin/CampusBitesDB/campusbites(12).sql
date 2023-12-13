-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 16, 2023 at 01:44 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `campusbites`
--

-- --------------------------------------------------------

--
-- Table structure for table `cod_payment`
--

DROP TABLE IF EXISTS `cod_payment`;
CREATE TABLE IF NOT EXISTS `cod_payment` (
  `COD_PAYMENT_ID` int NOT NULL AUTO_INCREMENT,
  `ONLINE_ORDER_ID` int DEFAULT NULL,
  `COD_AMOUNT` decimal(15,2) DEFAULT NULL,
  `COD_PAYMENT_DATE` date DEFAULT NULL,
  PRIMARY KEY (`COD_PAYMENT_ID`),
  KEY `ONLINE_ORDER_ID` (`ONLINE_ORDER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deduction`
--

DROP TABLE IF EXISTS `deduction`;
CREATE TABLE IF NOT EXISTS `deduction` (
  `DEDUC_ID` int NOT NULL AUTO_INCREMENT,
  `DEDUC_NAME` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `DEDUC_AMOUNT` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`DEDUC_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `deduction`
--

INSERT INTO `deduction` (`DEDUC_ID`, `DEDUC_NAME`, `DEDUC_AMOUNT`) VALUES
(1, 'SSS', '30.00'),
(2, 'PagIBIG', '250.00'),
(3, 'PhilHealth', '300.00');

-- --------------------------------------------------------

--
-- Table structure for table `emp_deduction`
--

DROP TABLE IF EXISTS `emp_deduction`;
CREATE TABLE IF NOT EXISTS `emp_deduction` (
  `EMP_DEDUCT_ID` int NOT NULL AUTO_INCREMENT,
  `EMP_JOB_ID` int DEFAULT NULL,
  `DEDUC_ID` int DEFAULT NULL,
  PRIMARY KEY (`EMP_DEDUCT_ID`),
  KEY `EMP_JOB_ID` (`EMP_JOB_ID`),
  KEY `DEDUC_ID` (`DEDUC_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emp_dtr`
--

DROP TABLE IF EXISTS `emp_dtr`;
CREATE TABLE IF NOT EXISTS `emp_dtr` (
  `EMP_DTR_ID` int NOT NULL AUTO_INCREMENT,
  `EMPLOYEE_ID` int DEFAULT NULL,
  `EMP_DTR_DATE` date DEFAULT NULL,
  `EMP_DTR_TIME_IN` time DEFAULT NULL,
  `EMP_DTR_TIME_OUT` time DEFAULT NULL,
  `EMP_DTR_STATUS` enum('Present','Absent') DEFAULT NULL,
  PRIMARY KEY (`EMP_DTR_ID`),
  KEY `EMP_PERSON_ID` (`EMPLOYEE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emp_job_info`
--

DROP TABLE IF EXISTS `emp_job_info`;
CREATE TABLE IF NOT EXISTS `emp_job_info` (
  `EMP_JOB_ID` int NOT NULL AUTO_INCREMENT,
  `EMP_POSITION` varchar(100) NOT NULL,
  `EMP_BASIC_SALARY` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`EMP_JOB_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `emp_job_info`
--

INSERT INTO `emp_job_info` (`EMP_JOB_ID`, `EMP_POSITION`, `EMP_BASIC_SALARY`) VALUES
(1, 'Courier', '25.00'),
(2, 'Cashier', '150.00'),
(3, 'Server', '200.00'),
(4, 'Manager', '500.00'),
(5, 'Cook', '250.00'),
(6, 'Assistant Cook', '150.00');

-- --------------------------------------------------------

--
-- Table structure for table `emp_payroll`
--

DROP TABLE IF EXISTS `emp_payroll`;
CREATE TABLE IF NOT EXISTS `emp_payroll` (
  `EMP_PAYROLL_ID` int NOT NULL AUTO_INCREMENT,
  `EMPLOYEE_ID` int DEFAULT NULL,
  `EMP_DTR_ID` int DEFAULT NULL,
  `EMP_JOB_ID` int NOT NULL,
  `EMP_DEDUC_ID` int DEFAULT NULL,
  `EMP_GROSS_SALARY` decimal(15,2) DEFAULT NULL,
  `EMP_TOTAL_DEDUCTION` decimal(10,2) DEFAULT NULL,
  `EMP_NET_SALARY` decimal(10,2) DEFAULT NULL,
  `PAYROLL_DATE` datetime NOT NULL,
  PRIMARY KEY (`EMP_PAYROLL_ID`),
  KEY `EMPLOYEE_ID` (`EMPLOYEE_ID`),
  KEY `EMP_DTR_ID` (`EMP_DTR_ID`),
  KEY `EMP_DEDUC_ID` (`EMP_DEDUC_ID`),
  KEY `EMP_JOB_ID` (`EMP_JOB_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ol_cart`
--

DROP TABLE IF EXISTS `ol_cart`;
CREATE TABLE IF NOT EXISTS `ol_cart` (
  `OL_CART_ID` int NOT NULL AUTO_INCREMENT,
  `CUSTOMER_ID` int DEFAULT NULL,
  `CART_DATE_CREATED` date DEFAULT NULL,
  `CART_TOTAL` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`OL_CART_ID`),
  KEY `CUSTOMER_ID` (`CUSTOMER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ol_order_status`
--

DROP TABLE IF EXISTS `ol_order_status`;
CREATE TABLE IF NOT EXISTS `ol_order_status` (
  `ORDER_STATUS_ID` int NOT NULL,
  `STATUS_NAME` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`ORDER_STATUS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ol_order_status`
--

INSERT INTO `ol_order_status` (`ORDER_STATUS_ID`, `STATUS_NAME`) VALUES
(1, 'To Pick Up'),
(2, 'Shipping'),
(3, 'Completed'),
(4, 'Cancelled'),
(5, 'Returned');

-- --------------------------------------------------------

--
-- Table structure for table `online_cart_item`
--

DROP TABLE IF EXISTS `online_cart_item`;
CREATE TABLE IF NOT EXISTS `online_cart_item` (
  `OL_CART_ITEM_ID` int NOT NULL AUTO_INCREMENT,
  `OL_CART_ID` int DEFAULT NULL,
  `PROD_ID` int DEFAULT NULL,
  `OL_PROD_QUANTITY` int DEFAULT NULL,
  `OL_SUBTOTAL` decimal(10,2) NOT NULL,
  PRIMARY KEY (`OL_CART_ITEM_ID`),
  KEY `CART_ID` (`OL_CART_ID`),
  KEY `PROD_ID` (`PROD_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_order`
--

DROP TABLE IF EXISTS `online_order`;
CREATE TABLE IF NOT EXISTS `online_order` (
  `ONLINE_ORDER_ID` int NOT NULL AUTO_INCREMENT,
  `OL_CART_ID` int DEFAULT NULL,
  `ORDER_STATUS_ID` int DEFAULT NULL,
  `COURIER_ID` int DEFAULT NULL,
  `SHIPPING_FEE` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`ONLINE_ORDER_ID`),
  KEY `CART_ID` (`OL_CART_ID`),
  KEY `COURIER_ID` (`COURIER_ID`),
  KEY `ORDER_STATUS_ID` (`ORDER_STATUS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `onsite_payment`
--

DROP TABLE IF EXISTS `onsite_payment`;
CREATE TABLE IF NOT EXISTS `onsite_payment` (
  `ONSITE_PAYMENT_ID` int NOT NULL AUTO_INCREMENT,
  `ONSITE_ORDER_ID` int DEFAULT NULL,
  `AMOUNT` decimal(10,2) DEFAULT NULL,
  `PAYMENT_DATE` date DEFAULT NULL,
  PRIMARY KEY (`ONSITE_PAYMENT_ID`),
  KEY `ONSITE_ORDER_ID` (`ONSITE_ORDER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `PERMISSION_ID` int NOT NULL,
  `PERMISSION_NAME` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`PERMISSION_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`PERMISSION_ID`, `PERMISSION_NAME`) VALUES
(1, 'View'),
(2, 'Write'),
(3, 'Disable'),
(4, 'Update'),
(5, 'Create Manager Account');

-- --------------------------------------------------------

--
-- Table structure for table `pos_cart`
--

DROP TABLE IF EXISTS `pos_cart`;
CREATE TABLE IF NOT EXISTS `pos_cart` (
  `POS_CART_ID` int NOT NULL,
  `POS_CART_DATE_CREATED` date NOT NULL,
  `POS_CART_TOTAL` decimal(10,2) NOT NULL,
  PRIMARY KEY (`POS_CART_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_cart_item`
--

DROP TABLE IF EXISTS `pos_cart_item`;
CREATE TABLE IF NOT EXISTS `pos_cart_item` (
  `POS_CART_ITEM_ID` int NOT NULL AUTO_INCREMENT,
  `POS_CART_ID` int DEFAULT NULL,
  `PROD_ID` int DEFAULT NULL,
  `POS_PROD_QUANTITY` int DEFAULT NULL,
  `POS_SUBTOTAL` decimal(10,2) NOT NULL,
  PRIMARY KEY (`POS_CART_ITEM_ID`),
  KEY `fk_pos_cart_item_pos_cart` (`POS_CART_ID`),
  KEY `fk_prod_id` (`PROD_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_order`
--

DROP TABLE IF EXISTS `pos_order`;
CREATE TABLE IF NOT EXISTS `pos_order` (
  `ONSITE_ORDER_ID` int NOT NULL AUTO_INCREMENT,
  `USER_ID` int DEFAULT NULL,
  `POS_CART_ID` int NOT NULL,
  `RECEIVED_AMOUNT` decimal(10,2) DEFAULT NULL,
  `CHANGE_AMOUNT` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`ONSITE_ORDER_ID`),
  KEY `EMPLOYEE_ID` (`USER_ID`),
  KEY `FK_POS_CART` (`POS_CART_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `PROD_ID` int NOT NULL AUTO_INCREMENT,
  `PROD_NAME` varchar(50) NOT NULL,
  `PROD_DESC` varchar(250) DEFAULT NULL,
  `PROD_CAPITAL_PRICE` double(10,2) NOT NULL,
  `PROD_SELLING_PRICE` decimal(10,2) DEFAULT NULL,
  `PROD_STATUS` enum('Available','Unavailable','Disabled') DEFAULT NULL,
  `PROD_SOLD` int DEFAULT NULL,
  `PROD_SALES` decimal(10,2) NOT NULL,
  `PROD_TOTAL_QUANTITY` int DEFAULT NULL,
  `PROD_REMAINING_QUANTITY` int NOT NULL,
  `CATEGORY_ID` int DEFAULT NULL,
  `PROD_DATE_ADD` date NOT NULL,
  `PROD_TIME_ADDED` time NOT NULL,
  `PRODPIC` varchar(255) NOT NULL,
  PRIMARY KEY (`PROD_ID`),
  KEY `CATEGORY_ID` (`CATEGORY_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=23455 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`PROD_ID`, `PROD_NAME`, `PROD_DESC`, `PROD_CAPITAL_PRICE`, `PROD_SELLING_PRICE`, `PROD_STATUS`, `PROD_SOLD`, `PROD_SALES`, `PROD_TOTAL_QUANTITY`, `PROD_REMAINING_QUANTITY`, `CATEGORY_ID`, `PROD_DATE_ADD`, `PROD_TIME_ADDED`, `PRODPIC`) VALUES
(1, 'Adobo', 'Magnolia Healthea is chuchuchu', 15.00, '32.00', 'Available', 48, '100.00', 52, 4, 2, '2023-10-30', '07:41:52', 'adobo.jpg'),
(2, 'Hotcake', 'Served warm and often accompanied by butter, syrup, or your favorite spread, Filipino hotcakes are a comforting and delicious way to start the day. The simplicity of the recipe and the familiar taste make them a beloved choice for breakfast or merien', 3.00, '5.00', 'Available', 27, '135.00', 35, 8, 3, '2023-11-15', '14:01:48', 'hq720.jpg'),
(3, 'Knorr Sinigang Mix', 'Sinigang is chuchuchu', 45.00, '60.00', 'Available', 15, '420.00', 18, 3, 1, '2023-11-02', '03:19:54', 'sinigang.jpg'),
(4, 'Banana Cue', 'Banana cue is often found in local markets and street corners, and it iss a favorite merienda or dessert option for those with a sweet tooth. The contrast between the soft, warm banana and the sweet, crispy caramel coating makes it a delightful and s', 7.00, '10.00', 'Available', 20, '200.00', 35, 15, 1, '2023-11-15', '14:29:17', 'Banana-Cue-2-scaled.jpg'),
(5, 'Bulaloaf', 'Bulalo is a well-known Filipino dish, a beef shank and marrow bone soup often flavored with corn on the cob, plantains, and various vegetables.', 4.00, '10.00', 'Available', 8, '80.00', 18, 10, 1, '2023-11-15', '14:32:10', 'bulaloaf.jpg'),
(6, 'Coca Cola', 'The popular carbonated soft drink. Coca-Cola is widely consumed in the Philippines and is a well-known and easily recognizable beverage. It is available in various sizes and is a common choice to accompany meals or to quench thirst.', 12.00, '15.00', 'Available', 16, '240.00', 32, 16, 2, '2023-11-15', '14:40:28', '5_2023-08-02_22-31-52.jpg'),
(7, 'Lumpia', 'chuchu', 3.00, '5.00', 'Available', 28, '140.00', 35, 7, 1, '2023-11-15', '15:11:06', 'lumpia.jpg'),
(9, 'Cassava Cake', 'chuchu', 3.00, '5.00', 'Available', 12, '60.00', 20, 8, 3, '2023-11-15', '15:20:47', 'Cassava-cake-on-a-board.jpg'),
(10, 'Suman', 'chuchu', 8.00, '10.00', 'Available', 16, '160.00', 20, 4, 4, '2023-11-15', '15:19:13', 'suman.jpg'),
(15, 'Cobra', 'chuchu', 18.00, '25.00', 'Available', 4, '100.00', 10, 6, 2, '2023-11-15', '15:16:33', 'Cobra-Energy-Drink-350ml-Main.png'),
(21, 'BBQ', 'chuchu', 30.00, '55.00', 'Available', 5, '275.00', 53, 48, 3, '2023-11-02', '10:37:02', 'pork-barbecue-yummy-kitchen-500x500.jpg'),
(30, 'Secret', 'chuchu', 30.00, '35.00', 'Disabled', 42, '280.00', 46, 4, 1, '2023-10-28', '07:48:48', 'eva_garcia.jpg'),
(91, 'Arf', 'chuchu', 5.00, '10.00', 'Available', 10, '100.00', 45, 35, 1, '2023-11-16', '01:46:36', '387561929_853846589652054_3682857786864953990_n.jpg'),
(201, 'Roasted Chicken', 'chuchu', 45.00, '67.00', 'Available', 5, '335.00', 15, 10, 1, '2023-11-16', '01:39:50', 'checken.jpg'),
(213, 'Burger', 'cuchuchuhcu', 232.00, '300.00', 'Disabled', 5, '1500.00', 18, 13, 2, '2023-11-04', '02:33:29', 'burger.jpg');

--
-- Triggers `product`
--
DROP TRIGGER IF EXISTS `calculate_sales_before_update`;
DELIMITER $$
CREATE TRIGGER `calculate_sales_before_update` BEFORE UPDATE ON `product` FOR EACH ROW BEGIN
    SET NEW.PROD_SALES = NEW.PROD_SELLING_PRICE * NEW.PROD_SOLD;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `tr_update_remaining_quantity`;
DELIMITER $$
CREATE TRIGGER `tr_update_remaining_quantity` BEFORE UPDATE ON `product` FOR EACH ROW BEGIN
    IF NEW.PROD_SOLD IS NOT NULL THEN
        SET NEW.PROD_REMAINING_QUANTITY = NEW.PROD_TOTAL_QUANTITY - NEW.PROD_SOLD;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `prod_category`
--

DROP TABLE IF EXISTS `prod_category`;
CREATE TABLE IF NOT EXISTS `prod_category` (
  `CATEGORY_ID` int NOT NULL AUTO_INCREMENT,
  `CATEGORY_NAME` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`CATEGORY_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `prod_category`
--

INSERT INTO `prod_category` (`CATEGORY_ID`, `CATEGORY_NAME`) VALUES
(1, 'Food'),
(2, 'Beverages'),
(3, 'Snacks'),
(4, 'Desserts');

-- --------------------------------------------------------

--
-- Table structure for table `prod_replenishment`
--

DROP TABLE IF EXISTS `prod_replenishment`;
CREATE TABLE IF NOT EXISTS `prod_replenishment` (
  `PROD_REPLENISH_ID` int NOT NULL AUTO_INCREMENT,
  `PROD_ID` int DEFAULT NULL,
  `REPLENISH_DATE` date DEFAULT NULL,
  `REPLENISH_TIME` time NOT NULL,
  `REPLENISH_QUANTITY` int DEFAULT NULL,
  `TOTAL_BEFORE_REPLENISH` int NOT NULL,
  `TOTAL_AFTER_REPLENISH` int NOT NULL,
  `PROD_PRICE` decimal(10,2) NOT NULL,
  `PROD_NEW_PRICE` decimal(10,2) NOT NULL,
  PRIMARY KEY (`PROD_REPLENISH_ID`),
  KEY `PROD_ID` (`PROD_ID`),
  KEY `fk_product` (`PROD_PRICE`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `prod_replenishment`
--

INSERT INTO `prod_replenishment` (`PROD_REPLENISH_ID`, `PROD_ID`, `REPLENISH_DATE`, `REPLENISH_TIME`, `REPLENISH_QUANTITY`, `TOTAL_BEFORE_REPLENISH`, `TOTAL_AFTER_REPLENISH`, `PROD_PRICE`, `PROD_NEW_PRICE`) VALUES
(26, 28, '2023-11-06', '00:00:00', 5, 59, 64, '0.00', '0.00'),
(27, 31, '2023-11-06', '00:00:00', 3, 15, 18, '0.00', '0.00'),
(28, 31, '2023-11-06', '00:00:00', 3, 0, 0, '0.00', '0.00'),
(29, 31, '2023-11-06', '00:00:00', 1, 0, 0, '0.00', '0.00'),
(30, 25, '2023-11-06', '00:00:00', 1, 4, 0, '0.00', '0.00'),
(31, 2, '2023-11-06', '00:00:00', 1, 49, 50, '0.00', '0.00'),
(32, 2, '2023-11-06', '00:00:00', 1, 50, 51, '0.00', '51.00'),
(33, 213, '2023-11-06', '00:00:00', 1, 14, 15, '0.00', '300.00'),
(34, 213, '2023-11-06', '00:00:00', 3, 15, 18, '300.00', '0.00'),
(35, 22, '2023-11-06', '00:00:00', 5, 5, 10, '8.00', '10.00'),
(36, 25, '2023-11-06', '00:00:00', 1, 5, 6, '60.00', '0.00'),
(37, 2, '2023-11-06', '00:00:00', 4, 51, 55, '51.00', '55.00'),
(38, 2, '2023-11-06', '00:00:12', 3, 55, 58, '55.00', '50.00'),
(39, 2, '2023-11-06', '00:00:20', 3, 61, 64, '50.00', '45.00'),
(40, 21, '2023-11-06', '00:00:20', 1, 22, 23, '40.00', '0.00'),
(41, 21, '2023-11-06', '00:00:20', 3, 25, 28, '40.00', '45.00'),
(42, 21, '2023-11-06', '00:00:20', 1, 28, 29, '45.00', '0.00'),
(43, 21, '2023-11-06', '00:00:20', 2, 29, 31, '45.00', '0.00'),
(44, 2, '2023-11-06', '00:00:20', 5, 64, 69, '45.00', '0.00'),
(45, 2, '2023-11-06', '00:00:20', 5, 69, 74, '45.00', '0.00'),
(46, 1, '2023-11-07', '00:00:07', 4, 21, 25, '20.00', '0.00'),
(47, 1, '2023-11-07', '00:00:07', 1, 25, 26, '20.00', '20.00'),
(48, 1, '2023-11-07', '00:00:07', 4, 26, 30, '20.00', '0.00'),
(49, 1, '2023-11-07', '00:00:07', 3, 30, 33, '20.00', '20.00'),
(50, 1, '2023-11-07', '00:00:07', 3, 33, 36, '20.00', '25.00'),
(51, 1, '2023-11-07', '00:00:07', 1, 36, 37, '25.00', '25.00'),
(52, 1, '2023-11-07', '00:00:07', 1, 37, 38, '25.00', '25.00'),
(53, 1, '2023-11-07', '00:00:00', 4, 38, 42, '25.00', '25.00'),
(54, 21, '2023-11-07', '00:00:08', 1, 31, 32, '45.00', '45.00'),
(55, 21, '2023-11-07', '00:00:08', 1, 31, 32, '45.00', '45.00'),
(56, 21, '2023-11-07', '00:00:08', 1, 33, 34, '45.00', '45.00'),
(57, 21, '2023-11-07', '00:00:08', 1, 34, 35, '45.00', '45.00'),
(58, 21, '2023-11-07', '00:00:08', 5, 35, 40, '45.00', '45.00'),
(59, 1, '2023-11-07', '00:00:08', 2, 42, 44, '25.00', '25.00'),
(60, 21, '2023-11-07', '00:00:08', 1, 40, 41, '45.00', '45.00'),
(61, 21, '2023-11-07', '00:00:08', 1, 41, 42, '45.00', '45.00'),
(62, 21, '2023-11-07', '00:00:08', 1, 42, 43, '45.00', '45.00'),
(63, 21, '2023-11-07', '00:00:08', 1, 43, 44, '45.00', '45.00'),
(64, 21, '2023-11-07', '00:00:08', 1, 44, 45, '45.00', '45.00'),
(65, 21, '2023-11-07', '00:00:08', 1, 45, 46, '45.00', '45.00'),
(66, 32, '2023-11-07', '00:00:08', 4, 10, 14, '15.00', '15.00'),
(67, 32, '2023-11-07', '00:00:08', 1, 14, 15, '15.00', '15.00'),
(68, 32, '2023-11-07', '00:00:09', 1, 15, 16, '15.00', '15.00'),
(69, 32, '2023-11-07', '00:00:09', 0, 16, 16, '15.00', '15.00'),
(70, 32, '2023-11-07', '00:00:09', 2, 16, 18, '15.00', '15.00'),
(71, 32, '2023-11-07', '00:00:09', 0, 18, 18, '15.00', '15.00'),
(72, 32, '2023-11-07', '00:00:09', 0, 18, 18, '15.00', '15.00'),
(73, 32, '2023-11-07', '00:00:09', 0, 18, 18, '15.00', '15.00'),
(74, 32, '2023-11-07', '00:00:09', 0, 18, 18, '15.00', '15.00'),
(75, 32, '2023-11-07', '00:00:09', 0, 18, 18, '15.00', '15.00'),
(76, 32, '2023-11-07', '00:00:09', 0, 18, 18, '15.00', '15.00'),
(77, 32, '2023-11-07', '00:00:09', 0, 18, 18, '15.00', '15.00'),
(78, 32, '2023-11-07', '00:00:09', 0, 18, 18, '15.00', '15.00'),
(79, 27, '2023-11-07', '00:00:11', 0, 20, 20, '20.00', '20.00'),
(80, 1, '2023-11-08', '00:00:08', 0, 44, 44, '25.00', '25.00'),
(81, 1, '2023-11-08', '00:00:08', 0, 44, 44, '25.00', '25.00'),
(82, 1, '2023-11-08', '00:00:08', 0, 44, 44, '25.00', '25.00'),
(83, 32, '2023-11-08', '00:00:08', 0, 18, 18, '15.00', '15.00'),
(84, 27, '2023-11-08', '00:00:08', 5, 20, 25, '20.00', '25.00'),
(85, 1, '2023-11-08', '00:00:08', 0, 44, 44, '25.00', '25.00'),
(86, 1, '2023-11-08', '00:00:09', 5, 44, 49, '25.00', '25.00'),
(87, 30, '2023-11-08', '00:00:18', 5, 41, 46, '35.00', '35.00'),
(88, 3, '2023-11-08', '00:00:18', 5, 13, 18, '60.00', '60.00'),
(89, 3, '2023-11-08', '00:00:18', 0, 18, 18, '60.00', '60.00'),
(90, 21, '2023-11-08', '00:00:18', 3, 46, 49, '45.00', '45.00'),
(91, 21, '2023-11-08', '00:00:18', 4, 49, 53, '50.00', '55.00'),
(92, 1, '2023-11-08', '00:00:18', 2, 49, 51, '25.00', '32.00'),
(93, 1, '2023-11-08', '00:00:18', 1, 51, 52, '32.00', '32.00'),
(94, 23, '2023-11-10', '00:00:09', 4, 20, 24, '14.00', '14.00'),
(95, 23454, '2023-11-10', '00:00:10', 5, 5, 10, '60.00', '60.00'),
(96, 23454, '2023-11-10', '00:00:10', 3, 10, 13, '60.00', '65.00'),
(97, 2, '2023-11-11', '00:00:11', 2, 74, 76, '45.00', '45.00'),
(98, 6, '2023-11-15', '00:00:22', 2, 30, 32, '15.00', '15.00'),
(99, 201, '2023-11-16', '00:00:09', 5, 10, 15, '67.00', '67.00');

--
-- Triggers `prod_replenishment`
--
DROP TRIGGER IF EXISTS `update_total_after_replenish`;
DELIMITER $$
CREATE TRIGGER `update_total_after_replenish` BEFORE INSERT ON `prod_replenishment` FOR EACH ROW BEGIN
  SET NEW.TOTAL_AFTER_REPLENISH = NEW.TOTAL_BEFORE_REPLENISH + NEW.REPLENISH_QUANTITY;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `ROLE_CODE` varchar(50) NOT NULL,
  `ROLE_NAME` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ROLE_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`ROLE_CODE`, `ROLE_NAME`) VALUES
('adm', 'Admin'),
('adm_manager', 'Manager'),
('cour', 'Courier'),
('cstmr', 'Customer'),
('emp_asst_cook', 'Assistant Cook'),
('emp_cook', 'Cook'),
('emp_cshr', 'Cashier'),
('emp_srvr', 'Server');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `ROLE_CODE` varchar(50) NOT NULL,
  `PERMISSION_ID` int NOT NULL,
  PRIMARY KEY (`ROLE_CODE`,`PERMISSION_ID`),
  KEY `PERMISSION_ID` (`PERMISSION_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`ROLE_CODE`, `PERMISSION_ID`) VALUES
('adm', 1),
('adm_manager', 1),
('cour', 1),
('emp_asst_cook', 1),
('emp_cook', 1),
('emp_cshr', 1),
('emp_srvr', 1),
('adm', 2),
('adm_manager', 2),
('emp_asst_cook', 2),
('emp_cook', 2),
('emp_cshr', 2),
('emp_srvr', 2),
('adm', 3),
('adm_manager', 3),
('adm', 4),
('adm_manager', 4),
('cour', 4),
('emp_cshr', 4),
('adm', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `USER_ID` int NOT NULL AUTO_INCREMENT,
  `U_EMAIL` varchar(100) DEFAULT NULL,
  `U_USER_NAME` varchar(100) DEFAULT NULL,
  `U_PASSWORD` varchar(100) DEFAULT NULL,
  `U_FIRST_NAME` varchar(100) DEFAULT NULL,
  `U_MIDDLE_NAME` varchar(100) DEFAULT NULL,
  `U_LAST_NAME` varchar(100) DEFAULT NULL,
  `U_SUFFIX` enum('Jr','Sr','II','III','IV') DEFAULT NULL,
  `U_GENDER` enum('Male','Female','Others') DEFAULT NULL,
  `U_PHONE_NUMBER` varchar(15) DEFAULT NULL,
  `U_CAMPUS_AREA` varchar(100) DEFAULT NULL,
  `U_STATUS` enum('Active','Deactivated') DEFAULT NULL,
  `U_PICTURE` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`USER_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`USER_ID`, `U_EMAIL`, `U_USER_NAME`, `U_PASSWORD`, `U_FIRST_NAME`, `U_MIDDLE_NAME`, `U_LAST_NAME`, `U_SUFFIX`, `U_GENDER`, `U_PHONE_NUMBER`, `U_CAMPUS_AREA`, `U_STATUS`, `U_PICTURE`) VALUES
(1, 'ljcorcullo00102@usep.edu.ph', 'Admin', '$2y$10$PkB7MTeClIm7UEj5.fOdoOY3Dm66ava4JuIIVfAKMw9Jo.B9b93ti', 'Lowell', 'Jay', 'Orcullo', NULL, 'Male', '+639664885750', NULL, 'Active', '803233.png'),
(2, 'test@gmail.com', 'test', NULL, 'Lowell Jay ', 'Custodio', 'Orcullo', 'Jr', 'Male', '+654789452155', 'test', 'Active', '58485538b772315a9e4dd5d9.png'),
(3, 'marvin@usep.edu.ph', 'eee', NULL, 'Marvin', 'Snatchy', 'Estolloso', NULL, 'Male', '43342423234', 'test', 'Active', '378028999_693949095972424_1504144495886162736_n(1).jpg'),
(4, 'kenne@usep.edu.ph', 'John', NULL, 'Kenne', 'Wayne', 'Peñas', NULL, 'Others', '2523534', '', 'Active', '400193074_6623807331049952_3951809053367789013_n.jpg'),
(5, 'Lowell', 'Lowell', NULL, 'Lowell', 'Lowell', 'Lowell', NULL, 'Female', '43432', 'Lowell', 'Active', '338918229_735775774695678_2825541897661050372_n.jpg'),
(6, 'nelmarjim@usep.edu.ph', '', NULL, 'Nelmarjim', 'Sumilhig', 'Luna', 'Jr', 'Male', '78945612', '', 'Active', '387561929_853846589652054_3682857786864953990_n.jpg'),
(7, 'lowell@gmail.com', NULL, NULL, 'Lowell', 'Jay', 'Orcullo', '', 'Male', '39403294', NULL, 'Active', 'WIN_20230914_15_39_44_Pro.jpg'),
(8, 'michael@usep.edu.ph', NULL, NULL, 'Michael', 'Cruda', 'Labastida', '', 'Male', '09588746412', NULL, 'Active', 'cute27.png'),
(9, 'michael@usep.edu.ph', NULL, NULL, 'Michael', 'Cruda', 'Labastida', '', 'Male', '09546565648', NULL, 'Active', '394028226_585503643702060_5697616524347968626_n.jpg'),
(10, 'kisaiah@usep.edu.ph', 'Kisaiah', '$2y$10$PkB7MTeClIm7UEj5.fOdoOY3Dm66ava4JuIIVfAKMw9Jo.B9b93ti', 'Kisaiah Grace', 'Isaac', 'Torrenueva', '', 'Female', '09845651237', NULL, 'Active', '400473088_1033365131276254_1190953076275760773_n.jpg'),
(11, 'user1@email.com', 'user1', NULL, 'John', 'A', 'Doe', '', 'Male', '123-456-7890', 'Campus_A', 'Active', 'michael_smith.jpg'),
(12, 'user2@email.com', 'user2', NULL, 'Jane', 'B', 'Smith', NULL, 'Female', '987-654-3210', 'Campus_B', 'Deactivated', '338918229_735775774695678_2825541897661050372_n.jpg'),
(13, 'another_user@email.com', 'cool_user', NULL, 'Alex', NULL, 'Johnson', NULL, 'Male', '555-123-7890', 'Campus_C', 'Active', '387561929_853846589652054_3682857786864953990_n.jpg'),
(14, 'marvin@usep.edu.ph', 'Marvin', '$2y$10$PkB7MTeClIm7UEj5.fOdoOY3Dm66ava4JuIIVfAKMw9Jo.B9b93ti', 'Marvin', 'Johnfritz', 'Estolloso', '', 'Male', '12345689874', NULL, 'Active', 'pic.JPG');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE IF NOT EXISTS `user_roles` (
  `ROLE_CODE` varchar(50) NOT NULL,
  `USER_ID` int NOT NULL,
  PRIMARY KEY (`ROLE_CODE`,`USER_ID`),
  KEY `USER_ID` (`USER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`ROLE_CODE`, `USER_ID`) VALUES
('adm', 1),
('emp_cshr', 2),
('emp_srvr', 3),
('cstmr', 4),
('cour', 5),
('emp_asst_cook', 6),
('emp_cshr', 7),
('emp_cook', 8),
('cour', 9),
('adm_manager', 10),
('emp_srvr', 11),
('emp_srvr', 12),
('emp_cook', 13),
('adm_manager', 14);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vwpayroll_list`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vwpayroll_list`;
CREATE TABLE IF NOT EXISTS `vwpayroll_list` (
`ROLE_NAME` varchar(50)
,`U_FIRST_NAME` varchar(100)
,`U_LAST_NAME` varchar(100)
,`U_MIDDLE_NAME` varchar(100)
,`U_PICTURE` varchar(200)
,`USER_ID` int
);

-- --------------------------------------------------------

--
-- Structure for view `vwpayroll_list`
--
DROP TABLE IF EXISTS `vwpayroll_list`;

DROP VIEW IF EXISTS `vwpayroll_list`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vwpayroll_list`  AS SELECT `users`.`USER_ID` AS `USER_ID`, `users`.`U_FIRST_NAME` AS `U_FIRST_NAME`, `users`.`U_MIDDLE_NAME` AS `U_MIDDLE_NAME`, `users`.`U_LAST_NAME` AS `U_LAST_NAME`, `users`.`U_PICTURE` AS `U_PICTURE`, `roles`.`ROLE_NAME` AS `ROLE_NAME` FROM ((`users` join `user_roles` on((`users`.`USER_ID` = `user_roles`.`USER_ID`))) join `roles` on((`user_roles`.`ROLE_CODE` = `roles`.`ROLE_CODE`))) WHERE (`roles`.`ROLE_NAME` not in ('Customer','Admin','Courier'))  ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
