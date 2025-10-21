-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2025 at 12:14 AM
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
-- Database: `myhoteldb`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `bookingId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `roomId` int(11) NOT NULL,
  `checkIn` date NOT NULL,
  `checkOut` date NOT NULL,
  `totalNights` int(11) GENERATED ALWAYS AS (to_days(`checkOut`) - to_days(`checkIn`)) STORED,
  `totalPrice` decimal(10,2) NOT NULL,
  `status` enum('upcoming','staying','checked_out') NOT NULL DEFAULT 'upcoming',
  `bookingDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`bookingId`, `userId`, `roomId`, `checkIn`, `checkOut`, `totalPrice`, `status`, `bookingDate`) VALUES
(10, 16, 34, '2025-10-15', '2025-10-16', 96709.00, 'checked_out', '2025-10-15 06:06:30'),
(11, 16, 27, '2025-10-15', '2025-10-16', 11016.00, 'checked_out', '2025-10-15 08:03:00'),
(17, 16, 28, '2025-10-01', '2025-10-02', 14756.00, 'checked_out', '2025-10-15 09:23:34'),
(19, 16, 26, '2025-10-17', '2025-10-18', 4692.47, 'checked_out', '2025-10-15 10:13:00'),
(20, 16, 26, '2025-10-15', '2025-10-18', 14077.41, 'checked_out', '2025-10-15 10:14:05'),
(21, 16, 26, '2025-10-15', '2025-10-17', 9384.94, 'checked_out', '2025-10-15 10:14:48'),
(22, 16, 27, '2025-10-11', '2025-10-13', 999.00, 'checked_out', '2025-10-15 10:56:00'),
(24, 16, 28, '2025-10-16', '2025-10-17', 495040.00, 'checked_out', '2025-10-15 19:22:00'),
(25, 18, 26, '2025-10-16', '2025-10-18', 9384.94, 'checked_out', '2025-10-16 08:39:10'),
(27, 18, 29, '2025-10-16', '2025-10-25', 68544.00, 'staying', '2025-10-16 09:15:06'),
(28, 18, 29, '2025-10-16', '2025-10-17', 7616.00, 'checked_out', '2025-10-16 09:15:44'),
(29, 18, 29, '2025-10-16', '2025-10-17', 7616.00, 'checked_out', '2025-10-16 09:16:09'),
(30, 16, 28, '2025-10-21', '2025-10-23', 14144.00, 'staying', '2025-10-21 10:14:35'),
(31, 18, 26, '2025-10-21', '2025-10-22', 4692.47, 'staying', '2025-10-21 11:34:45'),
(32, 18, 28, '2025-10-21', '2025-10-22', 7072.00, 'staying', '2025-10-21 11:35:08'),
(33, 18, 26, '2025-10-21', '2025-10-22', 4692.47, 'staying', '2025-10-21 11:56:37'),
(34, 16, 27, '2025-10-21', '2025-10-22', 5508.00, 'staying', '2025-10-21 13:11:03'),
(35, 16, 27, '2025-10-22', '2025-10-23', 5508.00, 'staying', '2025-10-21 20:59:27');

-- --------------------------------------------------------

--
-- Table structure for table `hoteltheme`
--

CREATE TABLE `hoteltheme` (
  `themeId` int(11) NOT NULL,
  `hotelName` varchar(100) NOT NULL,
  `colorPrimary` varchar(7) NOT NULL,
  `colorSecondary` varchar(7) NOT NULL,
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hoteltheme`
--

INSERT INTO `hoteltheme` (`themeId`, `hotelName`, `colorPrimary`, `colorSecondary`, `updatedAt`) VALUES
(1, 'Centara Grand', '#9c1b3f', '#9c1b3f', '2025-10-21 13:13:11');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `roomId` int(11) NOT NULL,
  `roomName` varchar(100) NOT NULL,
  `roomDetail` text DEFAULT NULL,
  `roomPrice` decimal(10,2) NOT NULL,
  `roomCount` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`roomId`, `roomName`, `roomDetail`, `roomPrice`, `roomCount`) VALUES
(26, 'ห้องซูพีเรียร์', 'ห้องซูพีเรียร์อันแสนอบอุ่นเสมือนบ้าน มาพร้อมวิวเส้นขอบฟ้าที่สวยงามของกรุงเทพฯ พื้นที่ใช้สอยขนาด 32 ตร.ม. เตียงคิงไซส์หรูหรา โต๊ะทำงานที่สะดวกสบาย ห้องน้ำโปร่งสบายพร้อมบริเวณอาบน้ำกรุกระจก คุณสามารถเชื่อมต่อกับสถานที่ทำงานและเมืองได้อย่างง่ายดายจากที่แห่งนี้', 4692.47, 10),
(27, 'ห้องดีลักซ์', 'ห้องดีลักซ์ได้รับการออกแบบโดยคำนึงถึงความสะดวกสบายของคุณ และตกแต่งอย่างมีรสนิยมในโทนสีครีมผสมม่วงรอยัล ห้องพักขนาด 36 ตร.ม. มาพร้อมหน้าต่างสูงจากพื้นจรดเพดาน เพื่อเปิดรับทิวทัศน์เส้นขอบฟ้าของกรุงเทพฯ ที่สวยงาม คุณสามารถตื่นขึ้นมาบนเตียงขนาดคิงไซส์อันหรูหรา พร้อมเริ่มต้นวันใหม่ด้วยการแช่ตัวในอ่างอาบน้ำเพื่อความผ่อนคลาย', 5508.00, 10),
(28, 'พรีเมี่ยมแฟมิลี่', 'พักผ่อนเหยียดกายในห้องขนาดใหญ่ บนเตียงขนาดคิงไซส์ที่มีให้ 2 เตียง ห้องแฟมิลี่พรีเมี่ยมขนาด 54 ตร.ม. ได้รับการออกแบบมาเพื่อเพิ่มความสะดวกสบายพร้อมเทคโนโลยีอันแสนชาญฉลาด นอกจากพื้นที่ใช้สอยที่โปร่งสบายเหมาะสำหรับครอบครัวแล้ว ห้องนี้ยังเหมาะสำหรับการพักผ่อนในเมืองที่ยอดเยี่ยมสำหรับกลุ่มเพื่อนอีกด้วย ใช้เวลาอย่างมีคุณภาพร่วมกันในพื้นที่เลานจ์ที่มองเห็นเส้นขอบฟ้าของกรุงเทพฯ ที่สวยงาม', 7072.00, 5),
(29, 'คลับซูพีเรียร์', 'พักผ่อนอย่างมีสไตล์และสะดวกสบายในห้องซูพีเรียร์คลับของเรา ตกแต่งด้วยหน้าต่างสูงจากพื้นจรดเพดานช่วยถ่ายทอดพลังงานของเมืองเข้าสู่ในห้องได้เป็นอย่างดี ทัศนียภาพเส้นขอบฟ้าแบบพาโนรามาของกรุงเทพฯ รอบด้าน พร้อมพื้นที่ใช้สอยขนาด 32 ตร.ม. เตียงคิงไซส์หรูหรา พื้นที่โต๊ะทำงาน และห้องน้ำโปร่งสบายพร้อมฝักบัว', 7616.00, 3),
(30, 'คลับดีลักซ์', 'เข้าพักในคลับดีลักซ์ของเรา แล้วดื่มด่ำไปกับทิวทัศน์มุมกว้างของกรุงเทพฯ หน้าต่างสูงจากพื้นจรดเพดานไม่เพียงช่วยแต่เปิดรับแสงธรรมชาติเข้ามาเท่านั้น แต่ยังช่วยเปิดรับทิวทัศน์ของเมืองที่สวยงามให้กับพื้นที่ใช้สอยขนาด 39 ตร.ม. ห้องนี้อีกด้วย คุณจะได้รับสิทธิพิเศษในการเริ่มต้นวันใหม่ด้วยฝักบัวอาบน้ำแบบเรนชาวเวอร์ เพลิดเพลินกับรายการบนสมาร์ททีวี และเอนกายพักผ่อนบนเตียงขนาดคิงไซส์ในยามค่ำคืน', 8160.00, 0),
(31, 'ห้องจูเนียร์สวีท', 'ให้รางวัลตัวเองด้วยห้องสวีทที่มีสไตล์ ตั้งอยู่เหนือตัวเมืองกรุงเทพฯ ห้องสวีทจูเนียร์ของเราตกแต่งด้วยหน้าต่างสูงจากพื้นจรดเพดาน เปิดรับทิวทัศน์ที่สวยงามของเมือง นั่งชมภาพยนตร์บนสมาร์ททีวี นั่งแช่ตัวในมินิบาร์ หรือนอนเล่นบนเตียงขนาดคิงไซส์ ห้องกว้างขวางด้วยพื้นที่ใช้สอยขนาด 54 ตร.ม. เพื่อความสะดวกสบายและการผ่อนคลาย', 8415.00, 1),
(32, 'คลับสวีท', 'ผ่อนคลาย สูดอากาศบริสุทธิ์ และเพลิดเพลินไปกับพื้นที่พักผ่อนสุดพิเศษทั้งหมดที่เรามีให้ในห้องคลับสวีท ห้องสวีทขนาด 67 ตร.ม. ได้รับการออกแบบอย่างพิถีพิถันและทันสมัย ตกแต่งด้วยโทนสีเทาหินและสีครีม บวกกับการตกแต่งด้วยโทนสีเมทัลลิก พื้นที่โปร่งโล่งพร้อมพื้นที่ห้องนั่งเล่นขนาดใหญ่ ห้องนอนแยกเป็นสัดส่วน และห้องน้ำกว้างขวางพร้อมอ่างอาบน้ำขนาดใหญ่ที่มองเห็นเส้นขอบฟ้าของเมือง', 10200.00, 1),
(33, 'เอ็กเซ็กคิวทีฟสวีท', 'พาครอบครัวและเพื่อนๆ มาพักผ่อนและเพลิดเพลินในห้องเอ็กเซ็กคิวทีฟสวีทของเรา คุณสามารถให้ความบันเทิงกับแขกและผ่อนคลายในพื้นที่เลานจ์ที่กว้างขวาง พร้อมด้วยห้องนั่งเล่นที่โปร่งสบาย ตกแต่งด้วยไม้สีอ่อนและโทนสีเทาเงาในพื้นที่ขนาด 110 ตร.ม. ให้คุณได้พักผ่อนใจกลางเมืองที่มีรสนิยมแห่งนี้ คุณสามารถเริ่มต้นวันใหม่ด้วยการแช่ตัวในอ่างอาบน้ำและผ่อนคลายในยามเย็นพร้อมชื่นชมเส้นขอบฟ้าที่สวยงามได้จากที่นี่', 14756.00, 1),
(34, 'รอยัลสวีท', 'ห้องรอยัลสวีทที่กว้างขวางสวยงามเป็นนิยามใหม่ของการใช้ชีวิตในเมือง พื้นที่กว้างขวางขนาด 387 ตารางเมตร พร้อมทิวทัศน์เส้นขอบฟ้าของเมืองกรุงเทพฯ ที่เป็นเอกลักษณ์ ด้วยการผสมผสานของไม้สีเข้มและโทนสีอบอุ่น พื้นที่นั่งเล่นที่หรูหรานี้ประกอบด้วยโต๊ะรับประทานอาหารแบบยาวสำหรับ 8 ท่านและโซฟานุ่ม ห้องนอนทั้ง 4 ห้องได้รับการตกแต่งอย่างหรูหราพร้อมห้องน้ำส่วนตัวที่โปร่งสบาย', 96709.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `roomservices`
--

CREATE TABLE `roomservices` (
  `serviceId` int(11) NOT NULL,
  `roomId` int(11) NOT NULL,
  `serviceName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roomservices`
--

INSERT INTO `roomservices` (`serviceId`, `roomId`, `serviceName`) VALUES
(133, 27, 'ผู้ใหญ่สูงสุด 3 คน'),
(134, 27, 'มินิบาร์'),
(135, 27, 'เครื่องชงกาแฟ/ชา'),
(136, 27, 'เตียงเสริม'),
(137, 28, 'จำนวนผู้ใหญ่สูงสุด 4 คน'),
(138, 28, 'วิวเมือง'),
(139, 28, 'ฝักบัวและอ่างอาบน้ำ'),
(140, 28, 'สมาร์ททีวี'),
(141, 29, 'ผู้ใหญ่สูงสุด 2 คน'),
(142, 29, 'วิวเมือง'),
(143, 29, 'วิดีโอเกมในห้องพัก'),
(144, 29, 'พื้นที่สำหรับทำงาน'),
(145, 30, 'ผู้ใหญ่สูงสุด 2 คน'),
(146, 30, 'วิวเมือง'),
(147, 30, 'ฝักบัวและอ่างอาบน้ำ'),
(148, 30, 'WiFi ฟรี'),
(149, 30, 'สมาร์ททีวี'),
(150, 31, 'ผู้ใหญ่สูงสุด 2 คน'),
(151, 31, 'วิวเมือง'),
(152, 31, 'ฝักบัวและอ่างอาบน้ำ'),
(153, 31, 'WiFi ฟรี'),
(154, 31, 'เครื่องปรับอากาศ'),
(155, 31, 'ชุดคลุมอาบน้ำ'),
(156, 32, 'ผู้ใหญ่สูงสุด 2 คน'),
(157, 32, 'วิวเมือง'),
(158, 32, 'สมาร์ททีวี'),
(159, 32, 'ฝักบัวและอ่างอาบน้ำ'),
(160, 32, 'WiFi ฟรี'),
(161, 33, 'ผู้ใหญ่สูงสุด 4 คน'),
(162, 33, 'วิวเมือง'),
(163, 33, 'สมาร์ททีวี'),
(164, 33, 'ฝักบัวและอ่างอาบน้ำ'),
(165, 33, 'เครื่องปรับอากาศ'),
(166, 33, 'WiFi ฟรี'),
(167, 34, 'ผู้ใหญ่สูงสุด 7 คน'),
(168, 34, 'ขนาดห้อง 387 ตร.ม.'),
(169, 34, 'วิวเมือง'),
(170, 34, 'ฝักบัวและอ่างอาบน้ำ'),
(171, 34, 'ฟังก์ชั่น \"ห้ามรบกวน\" แบบอิเล็กทรอนิกส์'),
(185, 26, 'ผู้ใหญ่สูงสุด 2 คน'),
(186, 26, 'วิวเมือง'),
(187, 26, 'ตู้เซฟในห้องพัก'),
(188, 26, 'ชุดโกนหนวด'),
(189, 26, 'วิดีโอเกมในห้องพัก'),
(190, 26, 'สมาร์ททีวี'),
(191, 26, 'โซฟา');

-- --------------------------------------------------------

--
-- Table structure for table `roomsimages`
--

CREATE TABLE `roomsimages` (
  `rimgId` int(11) NOT NULL,
  `roomId` int(11) NOT NULL,
  `rimgPath` varchar(255) NOT NULL,
  `rimgShow` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roomsimages`
--

INSERT INTO `roomsimages` (`rimgId`, `roomId`, `rimgPath`, `rimgShow`) VALUES
(120, 26, '20251015_063644_68ef24dc66c71.jpeg', 1),
(121, 26, '20251015_063728_68ef25084b3ce.jpeg', 0),
(122, 26, '20251015_063738_68ef251271320.jpeg', 0),
(123, 26, '20251015_063809_68ef25319202f.jpeg', 0),
(124, 27, '20251015_064402_68ef2692039cf.jpeg', 1),
(125, 27, '20251015_064407_68ef2697ba817.jpeg', 0),
(126, 27, '20251015_064413_68ef269d69f32.jpeg', 0),
(127, 27, '20251015_064418_68ef26a2c91a8.jpeg', 0),
(128, 28, '20251015_065218_68ef28821f5e2.jpeg', 1),
(129, 29, '20251015_065605_68ef296503fb6.jpeg', 1),
(130, 29, '20251015_065611_68ef296bf0941.jpeg', 0),
(131, 29, '20251015_065621_68ef297591fd7.jpeg', 0),
(132, 29, '20251015_065630_68ef297edb06b.jpeg', 0),
(133, 29, '20251015_065639_68ef298789589.jpeg', 0),
(134, 30, '20251015_065915_68ef2a234217f.jpeg', 1),
(135, 30, '20251015_065921_68ef2a29e8fa6.jpeg', 0),
(136, 30, '20251015_065928_68ef2a3080e57.jpeg', 0),
(137, 30, '20251015_065935_68ef2a3752f4b.jpeg', 0),
(138, 30, '20251015_065942_68ef2a3e285de.jpeg', 0),
(139, 30, '20251015_065950_68ef2a4605df3.jpeg', 0),
(140, 31, '20251015_070514_68ef2b8adf4b1.jpeg', 0),
(141, 31, '20251015_070529_68ef2b99c3a2e.jpeg', 0),
(142, 31, '20251015_070542_68ef2ba6377df.jpeg', 0),
(143, 31, '20251015_070551_68ef2baf7e542.jpeg', 0),
(144, 32, '20251015_070854_68ef2c66125b2.jpeg', 0),
(145, 32, '20251015_070907_68ef2c731ca58.jpeg', 0),
(146, 32, '20251015_070917_68ef2c7d26ec1.jpeg', 0),
(147, 32, '20251015_070927_68ef2c8705b6c.jpeg', 0),
(148, 32, '20251015_070935_68ef2c8f35190.jpeg', 0),
(149, 33, '20251015_071300_68ef2d5c9383a.jpeg', 0),
(150, 33, '20251015_071309_68ef2d6519c7f.jpeg', 0),
(151, 33, '20251015_071320_68ef2d7083d75.jpeg', 0),
(152, 33, '20251015_071338_68ef2d82058c3.jpeg', 0),
(153, 33, '20251015_071345_68ef2d895ef12.jpeg', 0),
(154, 34, '20251015_071824_68ef2ea0aa025.jpeg', 0),
(155, 34, '20251015_071831_68ef2ea7bf5f7.jpeg', 0),
(156, 34, '20251015_071838_68ef2eaed2d76.jpeg', 0),
(157, 34, '20251015_071847_68ef2eb70704f.jpeg', 0),
(158, 34, '20251015_071856_68ef2ec0e315d.jpeg', 0),
(159, 34, '20251015_071906_68ef2eca57d79.jpeg', 0),
(160, 34, '20251015_071914_68ef2ed247098.jpeg', 0),
(161, 34, '20251015_071921_68ef2ed9b81ab.jpeg', 0),
(162, 34, '20251015_071929_68ef2ee1301be.jpeg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `userName` varchar(120) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `userName`, `email`, `password_hash`, `role`, `created_at`) VALUES
(7, 'MuuuLoveCode', 'mrjittakorn@gmail.com', '$2y$10$YBB0y0Xo5WlHL0F1kg8N3.trZxKQPpE1re4VEH8vBFSA89q5FYdpO', 'customer', '2025-10-06 14:34:58'),
(16, 'admin', 'admin@gmail.com', '$2y$10$0Km75p5KYsLE7mG0Fzx30.gDVbcnDxEqmDAnHfVBsuc6kPO.5ZYmO', 'admin', '2025-10-09 20:19:16'),
(17, 'Anupat', 'Anupat@gmail.com', '$2y$10$irZNS2vGZ9GOuSCQjXQyeO7QCsFoVkAVHA3InZiCFexuzLvfUa3Mu', 'customer', '2025-10-09 21:42:00'),
(18, 'MuuuEiei', 'MuuuEiei@gmail.com', '$2y$10$FvSC9mYurGG382vaeJpSOuvW9dTzASEBWNqnAMaJ5H9euVnrd3Fmm', 'customer', '2025-10-13 10:21:30'),
(19, 'PokPone', 's66122250088@ssru.ac.th', '$2y$10$4vRcJQ8bUY.MC/Iv4lglUeGYsjPPPYqe9WrcCUAMlIpBQn3gIAZOK', 'admin', '2025-10-14 09:45:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`bookingId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `roomId` (`roomId`);

--
-- Indexes for table `hoteltheme`
--
ALTER TABLE `hoteltheme`
  ADD PRIMARY KEY (`themeId`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`roomId`);

--
-- Indexes for table `roomservices`
--
ALTER TABLE `roomservices`
  ADD PRIMARY KEY (`serviceId`),
  ADD KEY `roomId` (`roomId`);

--
-- Indexes for table `roomsimages`
--
ALTER TABLE `roomsimages`
  ADD PRIMARY KEY (`rimgId`),
  ADD KEY `rimgRoomID` (`roomId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `uq_users_username` (`userName`),
  ADD UNIQUE KEY `uq_users_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `bookingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `hoteltheme`
--
ALTER TABLE `hoteltheme`
  MODIFY `themeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `roomId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `roomservices`
--
ALTER TABLE `roomservices`
  MODIFY `serviceId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `roomsimages`
--
ALTER TABLE `roomsimages`
  MODIFY `rimgId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`roomId`) REFERENCES `rooms` (`roomId`) ON DELETE CASCADE;

--
-- Constraints for table `roomservices`
--
ALTER TABLE `roomservices`
  ADD CONSTRAINT `roomservices_ibfk_1` FOREIGN KEY (`roomId`) REFERENCES `rooms` (`roomId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `roomsimages`
--
ALTER TABLE `roomsimages`
  ADD CONSTRAINT `roomsimages_ibfk_1` FOREIGN KEY (`roomId`) REFERENCES `rooms` (`roomId`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `ev_update_booking_status` ON SCHEDULE EVERY 1 DAY STARTS '2025-10-16 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE bookings
  SET status = CASE
    WHEN CURDATE() < checkIn THEN 'upcoming'
    WHEN CURDATE() >= checkOut THEN 'checked_out'
    ELSE 'staying'
  END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
