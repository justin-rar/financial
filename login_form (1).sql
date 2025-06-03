-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Jun 2025 pada 17.02
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `login_form`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_account`
--

CREATE TABLE `tb_account` (
  `account_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verify_token` varchar(255) NOT NULL,
  `verify_status` int(11) NOT NULL,
  `reset_pascode` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_account`
--

INSERT INTO `tb_account` (`account_id`, `username`, `email`, `password`, `verify_token`, `verify_status`, `reset_pascode`) VALUES
(20, 'admin', 'gustinmr7@gmail.com', '$2y$10$0OUW8skIfkZMsjXzoNLSJ.qIMCrMsKAVo42LgtTw7duMXMe0z7J76', '9883378fe6e4c8db6f0db7e316473fdb', 0, 'c7ac3ee5db34ac8'),
(31, 'ara', 'justinrasyid09@gmail.com', '$2y$10$FrVuzcF5HfmHzyKNta8jiObRnF4RdO.rh5hEzYymTp.VG4riC1Q8.', '51db1fc3abbe296db85d0b9b41a42218', 1, 'VP9PP5'),
(32, 'daniel', 'maldinidanielroby@gmail.com', '$2y$10$fe5qQl/WyJGKnjKlMLWgAexMJnKQ6Y8dpx.dVRKTfq2wEumOaBvO2', 'e0240234b4820a316c457ea5c1a68c6c', 0, '0'),
(33, 'vania', 'vania06putrii@gmail.com', '$2y$10$pu8LnB.1Lkglkmbevp0ZauSV22sgarC5mWsfY8/ubItx5QJoQyxuC', '353431ecfb43a7a8276bece4e742decb', 0, '0'),
(34, 'holi', 'naftalimargareta@gmail.com', '$2y$10$XI6xIbFt.ntkA/ZbiDODTORczC2985.KQj6ZpOfKcog/D1Dd0OuGq', '9c12073c6b23ff562ea9a3144e74e018', 0, '0'),
(35, 'ngentot', 'naftalimargaretaa@gmail.com', '$2y$10$jCHPXAOHi/9CI1RKs0Zb8epQKo16OuBsAWyll4gKVNMGgh/3p7l/O', '431ba4ef0189c81b2b5c7e5887a4bf77', 0, '0'),
(36, 'gilang', 'gprasetyo621@gmail.com', '$2y$10$jWkoecPp6bonbPHvCMqYC.BQ/hLeDvAaxboo0P/3QJVwqdZAYCWe6', '1019310a866513fd754af6ed3d79fb9a', 1, '0');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `date` date NOT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `type`, `amount`, `category`, `date`, `note`) VALUES
(12, 20, 'income', 1000000.00, 'gaji', '2025-05-20', ''),
(13, 31, 'expense', 33000.00, 'makanan', '2025-05-22', 'miexue solo enak'),
(15, 31, 'income', 11111.00, 'qqq', '2025-05-25', '111'),
(16, 31, 'expense', 60000000.00, 'Pendidikan', '2025-05-26', 'bootcamp');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_account`
--
ALTER TABLE `tb_account`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_account`
--
ALTER TABLE `tb_account`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tb_account` (`account_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
