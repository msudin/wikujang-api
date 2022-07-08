-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Waktu pembuatan: 25 Apr 2022 pada 06.39
-- Versi server: 5.7.34
-- Versi PHP: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wikujang_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `address`
--

CREATE TABLE `address` (
  `address_id` varchar(255) NOT NULL,
  `subdistrict_id` int(11) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `address_detail` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `updated_at` varchar(255) NOT NULL,
  `deleted_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `email`, `username`, `fullname`, `password`) VALUES
(3, 'udin0141@gmail.com', 'udindb', 'Muhammad Sholehuddin', 'dGVzdDEyMzQ='),
(4, 'udin0142@gmail.com', 'udin', 'Muhammad Sholehuddin', 'dGVzdDEyMzQ=');

-- --------------------------------------------------------

--
-- Struktur dari tabel `district`
--

CREATE TABLE `district` (
  `district_id` int(11) NOT NULL,
  `subdistrict_id` int(11) DEFAULT NULL,
  `district_name` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `updated_at` varchar(255) NOT NULL,
  `deleted_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `district`
--

INSERT INTO `district` (`district_id`, `subdistrict_id`, `district_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(9, 350803, 'Jarit', '2022-03-18 15:49:43', '2022-03-18 15:49:43', ''),
(10, 350803, 'Jugosari', '2022-03-18 15:49:43', '2022-03-18 15:49:43', ''),
(11, 350803, 'Kloposawit', '2022-03-18 15:49:43', '2022-03-18 15:49:43', ''),
(12, 350803, 'Penanggal', '2022-03-18 15:49:43', '2022-03-18 15:49:43', ''),
(13, 350813, 'Dadapan', '2022-03-18 15:49:43', '2022-03-18 15:49:43', ''),
(14, 350813, 'Gucialit', '2022-03-18 15:49:43', '2022-03-18 15:49:43', ''),
(15, 350813, 'Jeruk', '2022-03-18 15:49:43', '2022-03-18 15:49:43', ''),
(16, 350813, 'Kenongo', '2022-03-18 15:49:43', '2022-03-18 15:49:43', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `file`
--

CREATE TABLE `file` (
  `file_id` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `file`
--

INSERT INTO `file` (`file_id`, `type`, `file_name`, `created_at`) VALUES
('6234473629fc8', 'profile', '6234473629f33-1647593270.png', '2022-03-18 15:47:50'),
('62352c335ea20', 'profile', '62352c335e97a-1647651891.png', '2022-03-19 08:04:51'),
('62352dc69adcc', 'product', '62352dc69ad5c-1647652294.png', '2022-03-19 08:11:34'),
('62352fbb7d0ad', 'warung', '62352fbb7d03b-1647652795.png', '2022-03-19 08:19:55'),
('624eb83dce63a', 'product', '624eb83dcde43-1649326141.jpg', '2022-04-07 17:09:01'),
('6263c7ff8ef0a', 'warung', '6263c7ff8dd50-1650706431.jpg', '2022-04-23 16:33:51'),
('6264accd177c7', 'product', '6264accd17258-1650765005.jpg', '2022-04-24 08:50:05'),
('6264c74c234cf', 'warung', '6264c74c22598-1650771788.jpg', '2022-04-24 10:43:08'),
('6264c7f387fdc', 'warung', '6264c7f385723-1650771955.jpg', '2022-04-24 10:45:55'),
('6264c843ada24', 'warung', '6264c843ad519-1650772035.jpg', '2022-04-24 10:47:15'),
('6264c8838a18d', 'warung', '6264c88389bc3-1650772099.jpg', '2022-04-24 10:48:19'),
('6264c8de39905', 'warung', '6264c8de392e7-1650772190.jpg', '2022-04-24 10:49:50'),
('6264c90746ef3', 'warung', '6264c907464ec-1650772231.jpg', '2022-04-24 10:50:31'),
('6264c92f959b5', 'warung', '6264c92f94553-1650772271.jpg', '2022-04-24 10:51:11'),
('6264c93c28d12', 'warung', '6264c93c28948-1650772284.jpg', '2022-04-24 10:51:24'),
('6264ca0585b5f', 'warung', '6264ca0585500-1650772485.jpg', '2022-04-24 10:54:45'),
('626562805e1f4', 'warung', '626562805d81d1650811520.jpg', '2022-04-24 21:45:20'),
('6265636c2629c', 'warung', '6265636c25d221650811756.jpg', '2022-04-24 21:49:16'),
('626564dc692ef', 'warung', '626564dc68ec21650812124.jpg', '2022-04-24 21:55:24'),
('62662af45727f', 'warung', '62662af4553581650862836.jpg', '2022-04-25 12:00:36'),
('626637da395e5', 'product', '626637da322551650866138.jpg', '2022-04-25 12:55:38'),
('62663a95796b4', 'product', '62663a95788691650866837.jpg', '2022-04-25 13:07:17'),
('62663b0369a8a', 'product', '62663b03691191650866947.jpg', '2022-04-25 13:09:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `product`
--

CREATE TABLE `product` (
  `product_id` varchar(255) NOT NULL,
  `warung_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `price` int(11) DEFAULT NULL,
  `image_id` varchar(255) NOT NULL,
  `rating` float DEFAULT NULL,
  `discount_percentage` int(11) DEFAULT NULL,
  `discount_amount` int(11) DEFAULT NULL,
  `likes` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT NULL,
  `created_at` varchar(255) NOT NULL,
  `updated_at` varchar(255) NOT NULL,
  `deleted_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `subdistrict`
--

CREATE TABLE `subdistrict` (
  `subdistrict_id` int(11) NOT NULL,
  `subdistrict_name` varchar(255) NOT NULL,
  `postal_code` int(11) DEFAULT NULL,
  `created_at` varchar(255) NOT NULL,
  `updated_at` varchar(255) NOT NULL,
  `deleted_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `subdistrict`
--

INSERT INTO `subdistrict` (`subdistrict_id`, `subdistrict_name`, `postal_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(350803, 'Candi Puro', 67373, '2022-03-18 15:49:35', '2022-03-18 15:49:35', ''),
(350813, 'Gucialit', 67353, '2022-03-18 15:49:35', '2022-03-18 15:49:35', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `token`
--

CREATE TABLE `token` (
  `token_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `access_token` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `expired_at` varchar(255) NOT NULL,
  `deleted_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `birthdate` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `address_id` int(11) DEFAULT NULL,
  `image_id` varchar(255) DEFAULT NULL,
  `active` varchar(10) NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `updated_at` varchar(255) NOT NULL,
  `deleted_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `warung`
--

CREATE TABLE `warung` (
  `warung_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `is_open` varchar(10) NOT NULL,
  `open_time` varchar(10) NOT NULL,
  `closed_time` varchar(10) NOT NULL,
  `rating` float NOT NULL,
  `image_id` varchar(255) NOT NULL,
  `address_id` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `updated_at` varchar(255) NOT NULL,
  `deleted_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`);

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `district`
--
ALTER TABLE `district`
  ADD PRIMARY KEY (`district_id`);

--
-- Indeks untuk tabel `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`file_id`);

--
-- Indeks untuk tabel `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indeks untuk tabel `subdistrict`
--
ALTER TABLE `subdistrict`
  ADD PRIMARY KEY (`subdistrict_id`);

--
-- Indeks untuk tabel `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`token_id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indeks untuk tabel `warung`
--
ALTER TABLE `warung`
  ADD PRIMARY KEY (`warung_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `district`
--
ALTER TABLE `district`
  MODIFY `district_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `token`
--
ALTER TABLE `token`
  MODIFY `token_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
