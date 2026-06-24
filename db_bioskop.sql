-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2026 at 08:04 AM
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
-- Database: `db_bioskop`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `showtime_id` int(11) NOT NULL,
  `tanggal_booking` datetime DEFAULT current_timestamp(),
  `status_booking` enum('pending','confirmed','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `showtime_id`, `tanggal_booking`, `status_booking`) VALUES
(34, 8, 9, '2026-06-24 12:17:27', 'confirmed'),
(35, 8, 9, '2026-06-24 12:35:26', 'confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `cinemas`
--

CREATE TABLE `cinemas` (
  `cinema_id` int(11) NOT NULL,
  `nama_bioskop` varchar(100) NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cinemas`
--

INSERT INTO `cinemas` (`cinema_id`, `nama_bioskop`, `lokasi`, `kota`) VALUES
(1, 'Tangerang XXI', 'SMS Mall', 'Tangerang'),
(2, 'Grand Indonesia CGV', 'Thamrin', 'Jakarta'),
(3, 'Cinepolis Lippo Mall Karawaci', 'Lippo Karawaci', 'Tangerang');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `movie_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `durasi` int(11) DEFAULT NULL,
  `rating_umur` varchar(10) DEFAULT NULL,
  `trailer_url` varchar(255) DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `status_tayang` enum('now_playing','upcoming') DEFAULT 'now_playing',
  `poster_url` varchar(255) DEFAULT NULL,
  `sinopsis` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movie_id`, `judul`, `durasi`, `rating_umur`, `trailer_url`, `genre`, `status_tayang`, `poster_url`, `sinopsis`) VALUES
(1, 'Colorful Stage! The Movie: A Miku Who Can\'t Sing', 110, 'R13+', 'https://youtu.be/4-iNByBzqIc?si=3IWYgetxLrox50NN', 'ANIMASI / SCI-FI / DRAMA / MUSIKAL', 'upcoming', 'https://a.storyblok.com/f/178900/640x906/1044cd2c42/gekijouban_project_sekai_kowareta_sekai_to_utaenai_miku_key_art2.jpg/m/filters:quality(95)format(webp)', 'Film panjang pertama yang diangkat dari rhythm game populer dengan lebih dari 39 juta unduhan di seluruh dunia!\n\nProject Sekai Colorful Stage! feat. Hatsune Miku (alias Project Sekai) adalah mobile game yang terkenal. Game aslinya berlatar di jalan bernama Shibuya, yang berpusat di sekitar musik dan subkultur, dan ruang misterius bernama Sekai yang mencerminkan keinginan terbesar orang-orang. Ceritanya berkisar tentang bagaimana penyanyi virtual seperti Hatsune Miku membantu para remaja menemukan keinginan terbesar mereka dan lagu-lagu mereka.\n\nSeri baru ini, yang diproduksi oleh studio animasi P.A. WORKS, menyajikan kisah yang sepenuhnya baru. Kisah ini menceritakan Hatsune Miku, yang belum pernah muncul dalam game, bertemu dengan karakter-karakter Project Sekai dan tumbuh bersama mereka.'),
(2, 'THE BLACK: Man & The Crimes', 165, 'D17+', NULL, 'KRIMINAL / DOKUMENTER', 'now_playing', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSN1hjVA9JwYKUoOgXhTvy8LGkCswEx4U8vPqfmI7Rihw&s=10', 'Kehidupan gelap mantan narapidana yang bertaubat karena jatuh cinta kepada seorang wanita.\r\n\r\nDia bersumpah akan menjadi manusia yang lebih baik demi merealisasikan cintanya kepada wanita tersebut. Namun, para mantan narapidana lain sangat membencinya, terutama para mafia! Buronan yang dulunya ingin ditangkap polisi, sekarang diburu oleh mafia, mantan rekan-rekannya sendiri\r\n'),
(3, 'Terraria: CALAMITY', 130, 'D17+', 'https://youtu.be/DW3EVvt57_A?si=Ga5HbVVy92mWOKxb', 'ACTION / DARK / FANTASY', 'now_playing', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT7I_hR6reO98fnBxUQSqVI04rdVM41ZQjW8_k2tpYR85iCn0v9f9LjXhuf&s=10', 'Di balik kabut alam semesta yang hancur, sebuah sejarah berdarah tersingkap: dari kejayaan Era Naga yang agung hingga kejatuhannya ke dalam kehampaan Era Dewa. Di tengah reruntuhan itu, bangkitlah Yharim, seorang penguasa tiran yang membawa api dendam untuk menghanguskan para dewa yang haus kekuasaan. Dalam dunia yang terkoyak oleh ambisi kosmik dan pengkhianatan abadi, perjalananmu bukan sekadar bertahan hidup, melainkan mengungkap tragedi di balik kehancuran sebuah dunia yang kini tertatih dalam sisa-sisa kekuatan gelap.\r\n\r\nKisah tentang keagungan yang runtuh dan harga yang harus dibayar saat manusia berani menantang takdir para dewa kini menunggumu. Apakah kau akan menjadi penyelamat di tanah yang terkutuk ini, atau sekadar menjadi saksi bu dari akhir sebuah semesta yang tertelan dalam kebencian?'),
(4, 'D15C0RD4NT H4RM0N1: Discordant Harmony', 155, 'SU', NULL, 'ANIMASI / FANTASI / SEMUA USIA', 'upcoming', 'https://wallpapercave.com/wp/wp14864812.webp', 'Prequel dari seri legenda My Little Pony: Friendship is Magic.\r\n\r\nMengisahkan zaman dahulu kala, sebelum Equistria menjadi harmoni, tanah ajaib ini dulunya dikuasai kekuatan CHAOS yang sering didengar sebagai, Discord'),
(5, 'No Game No Life: RESPAWNED', 180, 'R13+', NULL, 'Sci-Fi / Fantasy', 'upcoming', 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=600&q=80', 'Sang Player MVP telah kembali! Membawa kemenangan dan kedamaian baru yang harmonis. \r\n\r\nKetika para player lain ikut bersenang-senang, Boss Villain baru muncul dari bayangan, siap menghancurkan kejayaan Sang MVP');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `total_bayar` decimal(10,2) DEFAULT NULL,
  `status_pembayaran` enum('unpaid','paid','refunded') DEFAULT 'unpaid',
  `tanggal_bayar` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `booking_id`, `metode_pembayaran`, `bukti_pembayaran`, `total_bayar`, `status_pembayaran`, `tanggal_bayar`) VALUES
(31, 34, NULL, NULL, 60000.00, 'paid', '2026-06-24 12:17:31'),
(32, 35, NULL, NULL, 120000.00, 'paid', '2026-06-24 12:35:29');

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `seat_id` int(11) NOT NULL,
  `studio_id` int(11) NOT NULL,
  `baris` varchar(5) NOT NULL,
  `nomor_kursi` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`seat_id`, `studio_id`, `baris`, `nomor_kursi`) VALUES
(1, 1, 'A', '1'),
(2, 1, 'A', '2'),
(3, 1, 'A', '3'),
(4, 1, 'A', '4'),
(5, 1, 'A', '5'),
(6, 1, 'B', '1'),
(7, 1, 'B', '2'),
(8, 1, 'B', '3'),
(9, 1, 'B', '4'),
(10, 1, 'B', '5'),
(11, 1, 'C', '1'),
(12, 1, 'C', '2'),
(13, 1, 'C', '3'),
(14, 1, 'C', '4'),
(15, 1, 'C', '5'),
(16, 1, 'D', '1'),
(17, 1, 'D', '2'),
(18, 1, 'D', '3'),
(19, 1, 'D', '4'),
(20, 1, 'D', '5'),
(21, 2, 'A', '1'),
(22, 2, 'A', '2'),
(23, 2, 'A', '3'),
(24, 2, 'A', '4'),
(25, 2, 'A', '5'),
(26, 2, 'B', '1'),
(27, 2, 'B', '2'),
(28, 2, 'B', '3'),
(29, 2, 'B', '4'),
(30, 2, 'B', '5'),
(31, 2, 'C', '1'),
(32, 2, 'C', '2'),
(33, 2, 'C', '3'),
(34, 2, 'C', '4'),
(35, 2, 'C', '5'),
(36, 2, 'D', '1'),
(37, 2, 'D', '2'),
(38, 2, 'D', '3'),
(39, 2, 'D', '4'),
(40, 2, 'D', '5'),
(41, 3, 'A', '1'),
(42, 3, 'A', '2'),
(43, 3, 'A', '3'),
(44, 3, 'A', '4'),
(45, 3, 'A', '5'),
(46, 3, 'B', '1'),
(47, 3, 'B', '2'),
(48, 3, 'B', '3'),
(49, 3, 'B', '4'),
(50, 3, 'B', '5'),
(51, 3, 'C', '1'),
(52, 3, 'C', '2'),
(53, 3, 'C', '3'),
(54, 3, 'C', '4'),
(55, 3, 'C', '5'),
(56, 3, 'D', '1'),
(57, 3, 'D', '2'),
(58, 3, 'D', '3'),
(59, 3, 'D', '4'),
(60, 3, 'D', '5'),
(61, 6, 'A', '1'),
(62, 6, 'A', '2'),
(63, 6, 'A', '3'),
(64, 6, 'A', '4'),
(65, 6, 'A', '5'),
(66, 6, 'B', '1'),
(67, 6, 'B', '2'),
(68, 6, 'B', '3'),
(69, 6, 'B', '4'),
(70, 6, 'B', '5'),
(71, 6, 'C', '1'),
(72, 6, 'C', '2'),
(73, 6, 'C', '3'),
(74, 6, 'C', '4'),
(75, 6, 'C', '5'),
(76, 6, 'D', '1'),
(77, 6, 'D', '2'),
(78, 6, 'D', '3'),
(79, 6, 'D', '4'),
(80, 6, 'D', '5'),
(81, 7, 'A', '1'),
(82, 7, 'A', '2'),
(83, 7, 'A', '3'),
(84, 7, 'A', '4'),
(85, 7, 'A', '5'),
(86, 7, 'B', '1'),
(87, 7, 'B', '2'),
(88, 7, 'B', '3'),
(89, 7, 'B', '4'),
(90, 7, 'B', '5'),
(91, 7, 'C', '1'),
(92, 7, 'C', '2'),
(93, 7, 'C', '3'),
(94, 7, 'C', '4'),
(95, 7, 'C', '5'),
(96, 7, 'D', '1'),
(97, 7, 'D', '2'),
(98, 7, 'D', '3'),
(99, 7, 'D', '4'),
(100, 7, 'D', '5');

-- --------------------------------------------------------

--
-- Table structure for table `seat_availability`
--

CREATE TABLE `seat_availability` (
  `seat_availability_id` int(11) NOT NULL,
  `showtime_id` int(11) NOT NULL,
  `seat_id` int(11) NOT NULL,
  `status_kursi` enum('available','booked','sold') DEFAULT 'available',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seat_availability`
--

INSERT INTO `seat_availability` (`seat_availability_id`, `showtime_id`, `seat_id`, `status_kursi`, `updated_at`) VALUES
(161, 7, 21, 'available', '2026-06-21 12:09:49'),
(162, 7, 22, 'available', '2026-06-21 12:09:49'),
(163, 7, 23, 'available', '2026-06-21 12:09:49'),
(164, 7, 24, 'available', '2026-06-21 12:09:49'),
(165, 7, 25, 'available', '2026-06-21 12:09:49'),
(166, 7, 26, 'available', '2026-06-21 12:09:49'),
(167, 7, 27, 'available', '2026-06-21 12:09:49'),
(168, 7, 28, 'available', '2026-06-21 12:09:49'),
(169, 7, 29, 'available', '2026-06-21 12:09:49'),
(170, 7, 30, 'available', '2026-06-21 12:09:49'),
(171, 7, 31, 'available', '2026-06-21 12:09:49'),
(172, 7, 32, 'available', '2026-06-21 12:09:49'),
(173, 7, 33, 'available', '2026-06-21 12:09:49'),
(174, 7, 34, 'available', '2026-06-21 12:09:49'),
(175, 7, 35, 'available', '2026-06-21 12:09:49'),
(176, 7, 36, 'available', '2026-06-21 12:09:49'),
(177, 7, 37, 'available', '2026-06-21 12:09:49'),
(178, 7, 38, 'available', '2026-06-21 12:09:49'),
(179, 7, 39, 'available', '2026-06-21 12:09:49'),
(180, 7, 40, 'available', '2026-06-21 12:09:49'),
(181, 8, 21, 'available', '2026-06-21 12:09:49'),
(182, 8, 22, 'available', '2026-06-21 12:09:49'),
(183, 8, 23, 'available', '2026-06-21 12:09:49'),
(184, 8, 24, 'available', '2026-06-21 12:09:49'),
(185, 8, 25, 'available', '2026-06-21 12:09:49'),
(186, 8, 26, 'available', '2026-06-21 12:09:49'),
(187, 8, 27, 'available', '2026-06-21 12:09:49'),
(188, 8, 28, 'available', '2026-06-21 12:09:49'),
(189, 8, 29, 'available', '2026-06-21 12:09:49'),
(190, 8, 30, 'available', '2026-06-21 12:09:49'),
(191, 8, 31, 'available', '2026-06-21 12:09:49'),
(192, 8, 32, 'available', '2026-06-21 12:09:49'),
(193, 8, 33, 'available', '2026-06-21 12:09:49'),
(194, 8, 34, 'available', '2026-06-21 12:09:49'),
(195, 8, 35, 'available', '2026-06-21 12:09:49'),
(196, 8, 36, 'available', '2026-06-21 12:09:49'),
(197, 8, 37, 'available', '2026-06-21 12:09:49'),
(198, 8, 38, 'available', '2026-06-21 12:09:49'),
(199, 8, 39, 'available', '2026-06-21 12:09:49'),
(200, 8, 40, 'available', '2026-06-21 12:09:49'),
(201, 9, 81, 'available', '2026-06-21 12:09:49'),
(202, 9, 82, 'available', '2026-06-21 12:09:49'),
(203, 9, 83, 'booked', '2026-06-24 05:35:26'),
(204, 9, 84, 'booked', '2026-06-24 05:35:26'),
(205, 9, 85, 'available', '2026-06-21 12:09:49'),
(206, 9, 86, 'available', '2026-06-21 12:09:49'),
(207, 9, 87, 'available', '2026-06-21 12:09:49'),
(208, 9, 88, 'booked', '2026-06-24 05:17:27'),
(209, 9, 89, 'available', '2026-06-21 12:09:49'),
(210, 9, 90, 'available', '2026-06-21 12:09:49'),
(211, 9, 91, 'available', '2026-06-21 12:09:49'),
(212, 9, 92, 'available', '2026-06-21 12:09:49'),
(213, 9, 93, 'available', '2026-06-21 12:09:49'),
(214, 9, 94, 'available', '2026-06-21 12:09:49'),
(215, 9, 95, 'available', '2026-06-21 12:09:49'),
(216, 9, 96, 'available', '2026-06-21 12:09:49'),
(217, 9, 97, 'available', '2026-06-21 12:09:49'),
(218, 9, 98, 'available', '2026-06-21 12:09:49'),
(219, 9, 99, 'available', '2026-06-21 12:09:49'),
(220, 9, 100, 'available', '2026-06-21 12:09:49'),
(221, 10, 81, 'booked', '2026-06-21 13:08:21'),
(222, 10, 82, 'booked', '2026-06-21 13:08:21'),
(223, 10, 83, 'booked', '2026-06-21 13:08:21'),
(224, 10, 84, 'available', '2026-06-21 12:09:49'),
(225, 10, 85, 'available', '2026-06-21 12:09:49'),
(226, 10, 86, 'available', '2026-06-21 12:09:49'),
(227, 10, 87, 'available', '2026-06-21 12:09:49'),
(228, 10, 88, 'available', '2026-06-21 12:09:49'),
(229, 10, 89, 'available', '2026-06-21 12:09:49'),
(230, 10, 90, 'available', '2026-06-21 12:09:49'),
(231, 10, 91, 'available', '2026-06-21 12:09:49'),
(232, 10, 92, 'available', '2026-06-21 12:09:49'),
(233, 10, 93, 'available', '2026-06-21 12:09:49'),
(234, 10, 94, 'available', '2026-06-21 12:09:49'),
(235, 10, 95, 'available', '2026-06-21 12:09:49'),
(236, 10, 96, 'available', '2026-06-21 12:09:49'),
(237, 10, 97, 'available', '2026-06-21 12:09:49'),
(238, 10, 98, 'available', '2026-06-21 12:09:49'),
(239, 10, 99, 'available', '2026-06-21 12:09:49'),
(240, 10, 100, 'available', '2026-06-21 12:09:49');

-- --------------------------------------------------------

--
-- Table structure for table `showtimes`
--

CREATE TABLE `showtimes` (
  `showtime_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `studio_id` int(11) NOT NULL,
  `jam` time NOT NULL,
  `tanggal` date NOT NULL,
  `harga_tiket` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `showtimes`
--

INSERT INTO `showtimes` (`showtime_id`, `movie_id`, `studio_id`, `jam`, `tanggal`, `harga_tiket`) VALUES
(7, 2, 2, '13:00:00', '2026-06-22', 45000.00),
(8, 2, 2, '19:00:00', '2026-06-22', 55000.00),
(9, 3, 7, '15:30:00', '2026-06-22', 60000.00),
(10, 3, 7, '20:00:00', '2026-06-22', 65000.00);

-- --------------------------------------------------------

--
-- Table structure for table `studios`
--

CREATE TABLE `studios` (
  `studio_id` int(11) NOT NULL,
  `cinema_id` int(11) NOT NULL,
  `nama_studio` varchar(50) DEFAULT NULL,
  `kapasitas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studios`
--

INSERT INTO `studios` (`studio_id`, `cinema_id`, `nama_studio`, `kapasitas`) VALUES
(1, 1, 'Studio 1', 20),
(2, 1, 'Studio 2', 20),
(3, 1, 'Studio 3', 20),
(4, 2, 'Studio 1 CGV', 20),
(5, 2, 'Studio 2 CGV', 20),
(6, 3, 'Cinepolis VIP 1', 20),
(7, 3, 'Cinepolis Regular 2', 20);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `seat_id` int(11) NOT NULL,
  `showtime_id` int(11) NOT NULL,
  `kode_tiket` varchar(50) NOT NULL,
  `status` enum('active','refunded','used') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`ticket_id`, `booking_id`, `seat_id`, `showtime_id`, `kode_tiket`, `status`) VALUES
(66, 34, 88, 9, 'TKT17822782479566', 'active'),
(67, 35, 83, 9, 'TKT17822793262844', 'active'),
(68, 35, 84, 9, 'TKT17822793268161', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `nama`, `password_hash`, `email`, `no_hp`, `role`, `created_at`) VALUES
(1, 'User Test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'test@gmail.com', '081234567890', 'admin', '2026-04-14 15:21:19'),
(6, 'Enanan', '$2y$10$Y8NzBlDJgMQ9QGChoES9g.0Mku6NvDGIYtzHLXil4q2eegCF2VafW', 'enanan@gmail.com', '08123456789', 'user', '2026-06-21 13:52:26'),
(7, 'Raymond', '$2y$10$ndiU1a4hJTqucPhpf/DgSe/J.it/EpuaLMI7sSoiXmh5KnEYrTlpS', 'raymond@gmail.com', '081923151512', 'admin', '2026-06-24 05:07:40'),
(8, 'kiyosaka', '$2y$10$2CFJ9DmXGNDbMqUY5OP2e.zr4QvufdYv3rDCdAbrtN.YBI8oV7tcS', 'kiyosaka@gmail.com', '0812345678', 'user', '2026-06-24 05:09:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `showtime_id` (`showtime_id`);

--
-- Indexes for table `cinemas`
--
ALTER TABLE `cinemas`
  ADD PRIMARY KEY (`cinema_id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movie_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`seat_id`),
  ADD KEY `studio_id` (`studio_id`);

--
-- Indexes for table `seat_availability`
--
ALTER TABLE `seat_availability`
  ADD PRIMARY KEY (`seat_availability_id`),
  ADD KEY `showtime_id` (`showtime_id`),
  ADD KEY `seat_id` (`seat_id`);

--
-- Indexes for table `showtimes`
--
ALTER TABLE `showtimes`
  ADD PRIMARY KEY (`showtime_id`),
  ADD KEY `movie_id` (`movie_id`),
  ADD KEY `studio_id` (`studio_id`);

--
-- Indexes for table `studios`
--
ALTER TABLE `studios`
  ADD PRIMARY KEY (`studio_id`),
  ADD KEY `cinema_id` (`cinema_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD UNIQUE KEY `kode_tiket` (`kode_tiket`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `seat_id` (`seat_id`),
  ADD KEY `showtime_id` (`showtime_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `cinemas`
--
ALTER TABLE `cinemas`
  MODIFY `cinema_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `seat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `seat_availability`
--
ALTER TABLE `seat_availability`
  MODIFY `seat_availability_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=288;

--
-- AUTO_INCREMENT for table `showtimes`
--
ALTER TABLE `showtimes`
  MODIFY `showtime_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `studios`
--
ALTER TABLE `studios`
  MODIFY `studio_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`showtime_id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`studio_id`) ON DELETE CASCADE;

--
-- Constraints for table `seat_availability`
--
ALTER TABLE `seat_availability`
  ADD CONSTRAINT `seat_availability_ibfk_1` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`showtime_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `seat_availability_ibfk_2` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`seat_id`) ON DELETE CASCADE;

--
-- Constraints for table `showtimes`
--
ALTER TABLE `showtimes`
  ADD CONSTRAINT `showtimes_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `showtimes_ibfk_2` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`studio_id`) ON DELETE CASCADE;

--
-- Constraints for table `studios`
--
ALTER TABLE `studios`
  ADD CONSTRAINT `studios_ibfk_1` FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`cinema_id`) ON DELETE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`seat_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_ibfk_3` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`showtime_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
