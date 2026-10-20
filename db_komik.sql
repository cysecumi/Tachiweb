/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.20-13.0.2-MariaDB, for Android (aarch64)
--
-- Host: 127.0.0.1    Database: db_komik
-- ------------------------------------------------------
-- Server version	13.0.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `chapter`
--

DROP TABLE IF EXISTS `chapter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `chapter` (
  `id_chapter` int(11) NOT NULL AUTO_INCREMENT,
  `id_komik` int(11) NOT NULL,
  `nomor_chapter` int(11) NOT NULL,
  `judul_chapter` varchar(100) DEFAULT '',
  `tanggal_rilis` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_chapter`),
  KEY `id_komik` (`id_komik`),
  CONSTRAINT `chapter_ibfk_1` FOREIGN KEY (`id_komik`) REFERENCES `komik` (`id_komik`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chapter`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `chapter` WRITE;
/*!40000 ALTER TABLE `chapter` DISABLE KEYS */;
INSERT INTO `chapter` VALUES
(1,1,1,'Awal Pertemuan','2026-10-18 05:16:56'),
(2,1,2,'Kekuatan Baru','2026-10-18 05:16:56'),
(3,3,1,'wewe','2026-10-18 15:41:50');
/*!40000 ALTER TABLE `chapter` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `halaman_komik`
--

DROP TABLE IF EXISTS `halaman_komik`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `halaman_komik` (
  `id_halaman` int(11) NOT NULL AUTO_INCREMENT,
  `id_chapter` int(11) NOT NULL,
  `nomor_halaman` int(11) NOT NULL,
  `url_gambar` varchar(255) NOT NULL,
  PRIMARY KEY (`id_halaman`),
  KEY `id_chapter` (`id_chapter`),
  CONSTRAINT `halaman_komik_ibfk_1` FOREIGN KEY (`id_chapter`) REFERENCES `chapter` (`id_chapter`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `halaman_komik`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `halaman_komik` WRITE;
/*!40000 ALTER TABLE `halaman_komik` DISABLE KEYS */;
INSERT INTO `halaman_komik` VALUES
(1,1,1,'https://i.pinimg.com/736x/60/81/50/6081501cc88e46b69be37378907a130d.jpg'),
(2,1,2,'https://i.pinimg.com/736x/11/2b/87/112b875a54e2893e0bf1151a8c840338.jpg'),
(3,1,3,'https://i.pinimg.com/736x/c8/96/0d/c8960d3ee850a620fd6f125e441b2213.jpg'),
(4,2,1,'https://i.pinimg.com/736x/60/81/50/6081501cc88e46b69be37378907a130d.jpg'),
(5,2,2,'https://placehold.co/800x1200/1e1e1e/bb86fc?text=Sunna+x+-+Ch.2+Hal.2'),
(6,3,1,'uploads/chapter/c3/001.jpg');
/*!40000 ALTER TABLE `halaman_komik` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `komik`
--

DROP TABLE IF EXISTS `komik`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `komik` (
  `id_komik` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(150) NOT NULL,
  `author` varchar(100) NOT NULL,
  `sumber` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT 'Ongoing',
  `cover` varchar(255) NOT NULL,
  `sinopsis` text NOT NULL,
  PRIMARY KEY (`id_komik`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `komik`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `komik` WRITE;
/*!40000 ALTER TABLE `komik` DISABLE KEYS */;
INSERT INTO `komik` VALUES
(1,'Sunna x','Cyse','Asura','Completed','assets/1792317708_48729.jpg','Sunna X adalah gadis malang yang diselamatkan raja suki.'),
(2,'Mybini','Unknown','SUKI','Ongoing','assets/imgwa.jpeg','Admin ternyata seorang SUKI'),
(3,'Omniscient Readers Viewpoint','Asura','Asura','Ongoing','https://placehold.co/300x450/2c2c2c/bb86fc?text=ORV','Sinopsis komik Omniscient Readers Viewpoint.'),
(4,'Jujutsu Kaisen','Gege Akutami','MangaPlus','Ongoing','https://placehold.co/300x450/2c2c2c/bb86fc?text=Jujutsu+Kaisen','Kisah pertarungan para penyihir jujutsu melawan kutukan.'),
(5,'asa made sugoi','clesta','SUKI','Completed','assets/1792300263_WhatsApp Image 2026-10-14 at 13.31.35 (1).jpeg','cicak cicak didinding ');
/*!40000 ALTER TABLE `komik` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `library`
--

DROP TABLE IF EXISTS `library`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `library` (
  `id_library` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `judul_komik` varchar(150) NOT NULL,
  `sumber` varchar(50) NOT NULL,
  `cover` varchar(255) NOT NULL,
  PRIMARY KEY (`id_library`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `library`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `library` WRITE;
/*!40000 ALTER TABLE `library` DISABLE KEYS */;
INSERT INTO `library` VALUES
(3,2,'Mybini','Asura','assets/imgwa.jpeg'),
(10,2,'Sunna x','Asura','assets/Coversunna.jpeg'),
(11,5,'Sunna x','Asura','assets/1792317708_48729.jpg'),
(12,7,'Mybini','SUKI','assets/imgwa.jpeg'),
(13,7,'Sunna x','Asura','assets/1792317708_48729.jpg');
/*!40000 ALTER TABLE `library` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_panggilan` varchar(100) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(5,'admin','$2y$10$lvn6.xquUKOIWNYGN0w3VuPbpWJE2I71dPrNRPq6tyo8G/nVyLLyS','Administrator'),
(6,'sunna','$2y$12$d5c277EERDbgWS158PmnTOPog08a8VKnqbqmbTeVK1/V7upVyySzu','una'),
(7,'cyse','$2y$12$lBCOZUhg5.0OdnWQA5KW4OMeqJq7PqpzoTuUhv5bzCLWyAxk1bH6y','cumi');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-10-18 23:48:08
