-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 18 Okt 2026 pada 08.43
-- Versi Server: 10.1.10-MariaDB
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_komik`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `chapter`
--

CREATE TABLE `chapter` (
  `id_chapter` int(11) NOT NULL,
  `id_komik` int(11) NOT NULL,
  `nomor_chapter` int(11) NOT NULL,
  `judul_chapter` varchar(100) DEFAULT '',
  `tanggal_rilis` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `chapter`
--

INSERT INTO `chapter` (`id_chapter`, `id_komik`, `nomor_chapter`, `judul_chapter`, `tanggal_rilis`) VALUES
(1, 1, 1, 'Awal Pertemuan', '2026-10-18 05:16:56'),
(2, 1, 2, 'Kekuatan Baru', '2026-10-18 05:16:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `halaman_komik`
--

CREATE TABLE `halaman_komik` (
  `id_halaman` int(11) NOT NULL,
  `id_chapter` int(11) NOT NULL,
  `nomor_halaman` int(11) NOT NULL,
  `url_gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `halaman_komik`
--

INSERT INTO `halaman_komik` (`id_halaman`, `id_chapter`, `nomor_halaman`, `url_gambar`) VALUES
(1, 1, 1, 'https://i.pinimg.com/736x/60/81/50/6081501cc88e46b69be37378907a130d.jpg'),
(2, 1, 2, 'https://i.pinimg.com/736x/11/2b/87/112b875a54e2893e0bf1151a8c840338.jpg'),
(3, 1, 3, 'https://i.pinimg.com/736x/c8/96/0d/c8960d3ee850a620fd6f125e441b2213.jpg'),
(4, 2, 1, 'https://i.pinimg.com/736x/60/81/50/6081501cc88e46b69be37378907a130d.jpg'),
(5, 2, 2, 'https://placehold.co/800x1200/1e1e1e/bb86fc?text=Sunna+x+-+Ch.2+Hal.2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `komik`
--

CREATE TABLE `komik` (
  `id_komik` int(11) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `author` varchar(100) NOT NULL,
  `sumber` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT 'Ongoing',
  `cover` varchar(255) NOT NULL,
  `sinopsis` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `komik`
--

INSERT INTO `komik` (`id_komik`, `judul`, `author`, `sumber`, `status`, `cover`, `sinopsis`) VALUES
(1, 'Sunna x', 'Cyse', 'Asura', 'Ongoing', 'assets/Coversunna.jpeg', 'Sunna X adalah gadis malang yang diselamatkan raja suki.'),
(2, 'Mybini', 'Unknown', 'SUKI', 'Ongoing', 'assets/imgwa.jpeg', 'Admin ternyata seorang SUKI'),
(3, 'Omniscient Readers Viewpoint', 'Asura', 'Asura', 'Ongoing', 'https://placehold.co/300x450/2c2c2c/bb86fc?text=ORV', 'Sinopsis komik Omniscient Readers Viewpoint.'),
(4, 'Jujutsu Kaisen', 'Gege Akutami', 'MangaPlus', 'Ongoing', 'https://placehold.co/300x450/2c2c2c/bb86fc?text=Jujutsu+Kaisen', 'Kisah pertarungan para penyihir jujutsu melawan kutukan.'),
(5, 'asa made sugoi', 'clesta', 'SUKI', 'Completed', 'assets/1792300263_WhatsApp Image 2026-10-14 at 13.31.35 (1).jpeg', 'cicak cicak didinding ');

-- --------------------------------------------------------

--
-- Struktur dari tabel `library`
--

CREATE TABLE `library` (
  `id_library` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `judul_komik` varchar(150) NOT NULL,
  `sumber` varchar(50) NOT NULL,
  `cover` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `library`
--

INSERT INTO `library` (`id_library`, `id_user`, `judul_komik`, `sumber`, `cover`) VALUES
(3, 2, 'Mybini', 'Asura', 'assets/imgwa.jpeg'),
(10, 2, 'Sunna x', 'Asura', 'assets/Coversunna.jpeg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_panggilan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama_panggilan`) VALUES
(5, 'admin', '$2y$10$lvn6.xquUKOIWNYGN0w3VuPbpWJE2I71dPrNRPq6tyo8G/nVyLLyS', 'Administrator');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chapter`
--
ALTER TABLE `chapter`
  ADD PRIMARY KEY (`id_chapter`),
  ADD KEY `id_komik` (`id_komik`);

--
-- Indexes for table `halaman_komik`
--
ALTER TABLE `halaman_komik`
  ADD PRIMARY KEY (`id_halaman`),
  ADD KEY `id_chapter` (`id_chapter`);

--
-- Indexes for table `komik`
--
ALTER TABLE `komik`
  ADD PRIMARY KEY (`id_komik`);

--
-- Indexes for table `library`
--
ALTER TABLE `library`
  ADD PRIMARY KEY (`id_library`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chapter`
--
ALTER TABLE `chapter`
  MODIFY `id_chapter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `halaman_komik`
--
ALTER TABLE `halaman_komik`
  MODIFY `id_halaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `komik`
--
ALTER TABLE `komik`
  MODIFY `id_komik` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `library`
--
ALTER TABLE `library`
  MODIFY `id_library` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `chapter`
--
ALTER TABLE `chapter`
  ADD CONSTRAINT `chapter_ibfk_1` FOREIGN KEY (`id_komik`) REFERENCES `komik` (`id_komik`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `halaman_komik`
--
ALTER TABLE `halaman_komik`
  ADD CONSTRAINT `halaman_komik_ibfk_1` FOREIGN KEY (`id_chapter`) REFERENCES `chapter` (`id_chapter`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
