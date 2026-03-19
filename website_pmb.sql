-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: website_pmb
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','0192023a7bbd73250516f069df18b500','2026-02-04 00:03:51');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `daftar_ulang`
--

DROP TABLE IF EXISTS `daftar_ulang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `daftar_ulang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `nama_lengkap` varchar(150) DEFAULT NULL,
  `nik` varchar(25) DEFAULT NULL,
  `nama_ortu` varchar(100) DEFAULT NULL,
  `no_hp_ortu` varchar(20) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `asal_sekolah` varchar(200) DEFAULT NULL,
  `alamat` text,
  `prodi` varchar(100) DEFAULT NULL,
  `ijazah` varchar(255) DEFAULT NULL,
  `kk` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daftar_ulang`
--

LOCK TABLES `daftar_ulang` WRITE;
/*!40000 ALTER TABLE `daftar_ulang` DISABLE KEYS */;
INSERT INTO `daftar_ulang` VALUES (1,1,NULL,NULL,'liona',NULL,'0812345',NULL,NULL,NULL,NULL,'cipinang','Computer Science',NULL,NULL,'2026-02-03 04:51:01',NULL),(2,5,NULL,NULL,'oliv',NULL,'081212',NULL,NULL,NULL,NULL,'muara','Computer Science',NULL,NULL,'2026-02-03 05:32:57',NULL),(3,6,NULL,NULL,'dina',NULL,'08177',NULL,NULL,NULL,NULL,'cip','Computer Science',NULL,NULL,'2026-02-03 06:00:55',NULL),(4,7,NULL,NULL,'susi',NULL,'08121212',NULL,NULL,NULL,NULL,'jalan jendral','Computer Science',NULL,NULL,'2026-02-03 14:34:13',NULL),(6,11,NULL,NULL,'susi',NULL,'081212',NULL,NULL,NULL,NULL,'jalanan','Computer Science',NULL,NULL,'2026-02-04 07:49:00',NULL),(7,14,NULL,NULL,'byudi',NULL,'087888786879',NULL,NULL,NULL,NULL,'hgfjhgcffhnv','International Relations',NULL,NULL,'2026-02-11 06:53:45',NULL),(8,16,'Liona','312113121412','Jessly','0888 6789 1231','0899 5566 7867',NULL,NULL,NULL,NULL,'jalan jalannnn','Computer Science','1771572835_ijazah.jpg','1771572835_kk.jpg','2026-02-20 07:33:55',NULL),(9,19,'Veren','312113121111','Joshua','0891 1234 8976','0899 1122 1234',NULL,NULL,NULL,NULL,'bandung cihuyy','Computer Science','1771606324_ijazah.jpg','1771606324_kk.jpg','2026-02-20 16:52:04',NULL),(10,19,'Veren','312113121111','Joshua','0891 1234 8976','0899 1122 1234',NULL,NULL,NULL,NULL,'bandung cihuyy','Computer Science','1771606713_ijazah.jpg','1771606713_kk.jpg','2026-02-20 16:58:33',NULL),(11,19,'Veren','312113121111','Joshua','0891 1234 8976','0899 1122 1234',NULL,NULL,NULL,NULL,'bandung cihuyy','Computer Science','1771607002_ijazah.jpg','1771607002_kk.jpg','2026-02-20 17:03:22',''),(12,21,'Song Yan','312112348899','Xu xin','0812 4532 4325','0812 1245 1231',NULL,NULL,NULL,NULL,'Shanghai, China','International Relations','1771910991_ijazah.jpg','1771910991_kk.jpg','2026-02-24 05:29:51',NULL),(13,22,'Olivia Putri Nainggolan','312121125643','Sophia','0877 6655 6789','0866 1234 5678','Jakarta','2001-12-12','Perempuan','SMK Negeri 65 Jakarta','Jalan pengangsaan, Cibinong,  Singapore','Computer Science','1771985449_ijazah.jpg','1771985449_kk.jpg','2026-02-25 02:10:49','1771985449_foto.jpg'),(14,23,'Sang Zhi','NIK12131415','Veren','0877 5678 4356','0812 1243 5678','Jerman','2007-11-12','Perempuan','BPK Penabur Internasional','Newly, Jerman','International Relations','1771988919_ijazah.jpg','1771988919_kk.jpg','2026-02-25 03:08:39','1771988919_foto.jpg'),(15,24,'Ethan Alexander Vaughn','NIK123123','Robert William Vaughn','0822 3344 5566','0822 1100 9988','China','2006-12-08','Laki-laki','Bright Horizon International School','Jl. Permata Biru No. 7, Bekasi, Jawa Barat','Economics','1771999193_ijazah.jpg','1771999193_kk.jpg','2026-02-25 05:59:53','1771999193_foto.jpg'),(16,25,'Ahmad','21239032821931823','Robert William Vaughn','23232323232','0821123123123','China','2006-12-08','Laki-laki','Bright Horizon International School','PRUMPUNG','Computer Science','1772081618_ijazah.jpg','1772081618_kk.jpg','2026-02-26 04:53:38','1772081618_foto.jpg');
/*!40000 ALTER TABLE `daftar_ulang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jawaban`
--

DROP TABLE IF EXISTS `jawaban`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jawaban` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `soal_id` int NOT NULL,
  `jawaban` char(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`soal_id`),
  UNIQUE KEY `uniq_jawaban` (`user_id`,`soal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jawaban`
--

LOCK TABLES `jawaban` WRITE;
/*!40000 ALTER TABLE `jawaban` DISABLE KEYS */;
INSERT INTO `jawaban` VALUES (1,1,1,'C','2026-02-03 01:34:07'),(2,1,2,'B','2026-02-03 01:34:17'),(3,1,3,'D','2026-02-03 01:34:20'),(4,3,1,'B','2026-02-03 05:29:39'),(5,3,2,'B','2026-02-03 05:29:43'),(6,3,3,'D','2026-02-03 05:29:46'),(7,5,1,'C','2026-02-03 05:32:02'),(8,5,2,'B','2026-02-03 05:32:05'),(9,5,3,'D','2026-02-03 05:32:08'),(10,6,1,'C','2026-02-03 06:00:10'),(11,6,2,'B','2026-02-03 06:00:24'),(12,6,3,'D','2026-02-03 06:00:28'),(13,7,1,'C','2026-02-03 14:33:13'),(14,7,2,'B','2026-02-03 14:33:17'),(15,7,3,'D','2026-02-03 14:33:21'),(16,9,1,'C','2026-02-04 04:11:01'),(17,9,2,'B','2026-02-04 04:11:04'),(18,9,3,'D','2026-02-04 04:11:07'),(19,9,4,'C','2026-02-04 04:11:11'),(20,9,5,'A','2026-02-04 04:11:17'),(21,9,6,'B','2026-02-04 04:11:22'),(22,10,1,'B','2026-02-04 05:46:38'),(23,10,2,'B','2026-02-04 05:46:45'),(24,10,3,'D','2026-02-04 05:46:48'),(25,10,4,'B','2026-02-04 05:46:52'),(26,10,5,'C','2026-02-04 05:46:56'),(27,10,6,'B','2026-02-04 05:47:00'),(28,8,6,'B','2026-02-04 05:58:40'),(29,8,1,'C','2026-02-04 05:58:46'),(30,8,2,'B','2026-02-04 05:58:49'),(31,8,3,'D','2026-02-04 05:58:51'),(32,8,4,'B','2026-02-04 05:58:55'),(33,8,5,'D','2026-02-04 05:58:58'),(34,11,1,'C','2026-02-04 07:47:16'),(35,11,2,'B','2026-02-04 07:47:19'),(36,11,3,'D','2026-02-04 07:47:22'),(37,11,4,'B','2026-02-04 07:47:32'),(38,11,5,'C','2026-02-04 07:47:43'),(39,11,6,'B','2026-02-04 07:47:49'),(40,12,1,'D','2026-02-11 06:44:59'),(41,12,2,'B','2026-02-11 06:45:03'),(42,13,3,'D','2026-02-11 06:47:44'),(43,13,4,'B','2026-02-11 06:47:48'),(44,13,5,'C','2026-02-11 06:47:53'),(45,13,6,'B','2026-02-11 06:48:01'),(46,14,1,'C','2026-02-11 06:52:36'),(47,14,2,'B','2026-02-11 06:52:42'),(48,14,3,'D','2026-02-11 06:52:46'),(49,14,4,'B','2026-02-11 06:53:00'),(50,14,5,'C','2026-02-11 06:53:08'),(51,14,6,'B','2026-02-11 06:53:11'),(52,15,1,'C','2026-02-20 05:11:43'),(53,15,2,'B','2026-02-20 05:11:47'),(54,15,3,'D','2026-02-20 05:50:41'),(55,15,4,'B','2026-02-20 05:50:41'),(56,15,5,'C','2026-02-20 05:50:41'),(57,15,6,'B','2026-02-20 05:50:41'),(58,16,1,'C','2026-02-20 06:23:14'),(59,16,2,'B','2026-02-20 06:23:14'),(60,16,3,'D','2026-02-20 06:23:14'),(61,16,4,'B','2026-02-20 06:23:14'),(62,16,5,'C','2026-02-20 06:23:14'),(63,16,6,'B','2026-02-20 06:23:14'),(64,17,1,'C','2026-02-20 06:38:52'),(65,17,2,'B','2026-02-20 06:38:52'),(66,17,3,'B','2026-02-20 06:38:52'),(67,17,4,'A','2026-02-20 06:38:52'),(68,17,5,'A','2026-02-20 06:38:52'),(69,17,6,'A','2026-02-20 06:38:52'),(70,18,1,'C','2026-02-20 16:48:33'),(71,18,2,'B','2026-02-20 16:48:33'),(72,18,3,'D','2026-02-20 16:48:33'),(73,18,4,'B','2026-02-20 16:48:33'),(74,18,5,'D','2026-02-20 16:48:33'),(75,18,6,'D','2026-02-20 16:48:33'),(76,19,1,'C','2026-02-20 16:50:14'),(77,19,2,'B','2026-02-20 16:50:14'),(78,19,3,'D','2026-02-20 16:50:14'),(79,19,4,'B','2026-02-20 16:50:14'),(80,19,5,'C','2026-02-20 16:50:14'),(81,19,6,'B','2026-02-20 16:50:14'),(82,20,1,'C','2026-02-23 16:06:48'),(83,20,2,'B','2026-02-23 16:06:48'),(84,20,3,'D','2026-02-23 16:06:48'),(85,20,4,'B','2026-02-23 16:06:48'),(86,20,5,'C','2026-02-23 16:06:48'),(87,20,6,'B','2026-02-23 16:06:48'),(88,21,1,'C','2026-02-23 17:03:28'),(89,21,2,'B','2026-02-23 17:03:28'),(90,21,3,'B','2026-02-23 17:03:28'),(91,21,4,'B','2026-02-23 17:03:28'),(92,21,5,'C','2026-02-23 17:03:28'),(93,21,6,'B','2026-02-23 17:03:28'),(94,22,1,'C','2026-02-25 02:03:16'),(95,22,2,'B','2026-02-25 02:03:16'),(96,22,3,'D','2026-02-25 02:03:16'),(97,22,4,'B','2026-02-25 02:03:16'),(98,22,5,'C','2026-02-25 02:03:16'),(99,22,6,'B','2026-02-25 02:03:16'),(100,23,1,'C','2026-02-25 02:58:13'),(101,23,2,'B','2026-02-25 02:58:13'),(102,23,3,'D','2026-02-25 02:58:13'),(103,23,4,'B','2026-02-25 02:58:13'),(104,23,5,'C','2026-02-25 02:58:13'),(105,23,6,'B','2026-02-25 02:58:13'),(106,24,1,'C','2026-02-25 05:51:43'),(107,24,2,'B','2026-02-25 05:51:43'),(108,24,3,'D','2026-02-25 05:51:43'),(109,24,4,'B','2026-02-25 05:51:43'),(110,24,5,'C','2026-02-25 05:51:43'),(111,24,6,'B','2026-02-25 05:51:43'),(112,25,1,'C','2026-02-26 04:52:13'),(113,25,2,'B','2026-02-26 04:52:13'),(114,25,3,'D','2026-02-26 04:52:13'),(115,25,4,'B','2026-02-26 04:52:13'),(116,25,5,'C','2026-02-26 04:52:13'),(117,25,6,'C','2026-02-26 04:52:13'),(118,25,11,'A','2026-02-26 04:52:13');
/*!40000 ALTER TABLE `jawaban` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `soal`
--

DROP TABLE IF EXISTS `soal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `soal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pertanyaan` text NOT NULL,
  `opsi_a` varchar(255) NOT NULL,
  `opsi_b` varchar(255) NOT NULL,
  `opsi_c` varchar(255) NOT NULL,
  `opsi_d` varchar(255) NOT NULL,
  `jawaban` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `soal`
--

LOCK TABLES `soal` WRITE;
/*!40000 ALTER TABLE `soal` DISABLE KEYS */;
INSERT INTO `soal` VALUES (1,'Benua terkecil di dunia adalah?','Afrika','Eropa','Australia','Antartika','C'),(2,'Tahun berapa Indonesia merdeka?','1942','1945','1949','1950','B'),(3,'Siapakah penulis novel \"Harry Potter\"?','J.R.R. Tolkien','Stephen King','Agatha Christie','J.K. Rowling','D'),(4,'Apa nama unsur dengan simbol kimia \"O\"?','Osmium','Oksigen','Oganesson','Opium','B'),(5,'Siapa presiden pertama Republik Indonesia?','Soeharto','Megawati Soekarnoputri','Soekarno','B.J. Habibie','C'),(6,'Negara mana yang terkenal dengan sebutan \"Negeri Matahari Terbit\"?','Korea Selatan','Jepang','Tiongkok','India','B'),(11,'Bahasa pemrograman yang umum digunakan untuk membuat halaman web adalah?','HTML','Microsoft Word','Excel','PowerPoint','A');
/*!40000 ALTER TABLE `soal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nomor_tes` varchar(50) NOT NULL,
  `status_tes` enum('belum','sudah') DEFAULT 'belum',
  `nilai_tes` int DEFAULT NULL,
  `status_kelulusan` enum('lulus','tidak lulus') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `nim` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nomor_tes` (`nomor_tes`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (6,'viola','viola@gmail.com','$2y$10$kD/GmSFeQPd1GsAuR5IM3uUrydPGqT.GsBrF13BlE1WgMHI6PDNBS','PMB-2026-0006','sudah',100,'lulus','2026-02-03 05:59:50','2026CO0001'),(7,'alex','alex@gmail.com','$2y$10$mXwmhenxQPgdStZx1oX/W.NzqCVl3ptoUo8XMI23YVIEyRttLED3S','PMB-2026-0007','sudah',100,'lulus','2026-02-03 14:32:33','2026CO0002'),(8,'cinta','cinta@gmail.com','$2y$10$SPUudnYhHMhaaGzThHYN6eZZ.TiyfoqkQZcwInJdocLxxFeKiCuDG','PMB-2026-0008','sudah',83,'lulus','2026-02-04 03:23:51',NULL),(9,'drina','drina@gmail.com','$2y$10$OmeVEnrkrnVnVshPEqMJ7.qgcIWghQ/vRDjhsP8wqQM7CT2VIj11K','PMB-2026-0009','sudah',67,'tidak lulus','2026-02-04 04:10:23',NULL),(10,'ulan','ulan@gmail.com','$2y$10$/e5bKM7ARaeBOkfxU4ZMZeiR1jtUYBj9R47iJw9ngAGM9A6Dn5z12','PMB-2026-0010','sudah',83,'lulus','2026-02-04 05:45:29','2026IN0003'),(11,'olip','olip@gmail.com','$2y$10$B6ch4Iq2qfrSRKhexLbFCOH36GFkaLjToZ6B7ou7mGIs01WGpZKNC','PMB-2026-0011','sudah',100,'lulus','2026-02-04 07:46:13','2026CO0004'),(12,'Anya Natalia','Anya@gmail.com','$2y$10$/Xedgf7VIgo1Xhq9KMY8GeEyOC9GKd/48HG5ULzddca1ct9mnKkUS','PMB-2026-0012','belum',NULL,NULL,'2026-02-11 02:09:12',NULL),(13,'syciuihu','sycuii@gmail.com','$2y$10$GoYwkj.qPBMKwEcavUM2x.2lGjXa1VklLKABj4zoWEkQsdQVpcsWm','PMB-2026-0013','sudah',67,'tidak lulus','2026-02-11 06:46:51',NULL),(14,'nori','suvci@gmail.com','$2y$10$CpDwxCuAswEIzAUaDM/pj.KoQiMI.b4gkd3PZbg5p7jX/n7ZctfRK','PMB-2026-0014','sudah',100,'lulus','2026-02-11 06:49:30','2026IN0004'),(15,'Alexander','alexander@gmail.com','$2y$10$Cz7WMJFgsgVJhXb4BaGIQe.BzXi717kIp0KBYanihRi8edqyb2zJ2','PMB-2026-0015','sudah',100,'lulus','2026-02-20 04:26:01',NULL),(16,'liona','liona@gmail.com','$2y$10$v1Q/q2qxCjdVtOpWZ5om7uoF3qlt7.m/VvlYGQHISwUxqLPTLObBe','PMB-2026-0016','sudah',100,'lulus','2026-02-20 06:22:24','2026CO0005'),(17,'Lola Tifany','lola@gmail.com','$2y$10$XDl1BcVTQzoWSE1WjApSXeX4vBTvlGPM.PzTaeSmoQgI2prpayApC','PMB-2026-0017','sudah',33,'tidak lulus','2026-02-20 06:38:15',NULL),(18,'Jessy','jessy@gmail.com','$2y$10$vNhv1tEy3k/CJKomXUPg9.OzLhadSvXVofGut/POS21cyd8vM1bxK','PMB-2026-0018','sudah',67,'tidak lulus','2026-02-20 16:46:34',NULL),(19,'Veren','veren@gmail.com','$2y$10$QJvx1vPrMkldw2UdIFFNneg1mSZvllUP6OO2DNw/Ddv6DmmEWgbSS','PMB-2026-0019','sudah',100,'lulus','2026-02-20 16:49:29','2026CO0005'),(20,'Irene','irene@gmail.com','$2y$10$6.5ASfweG0Fijn6mVkYaieSsnQqY5MvOUhWOm2lvlUDXivf9VcQX.','PMB-2026-0020','sudah',100,'lulus','2026-02-23 16:06:01',NULL),(21,'Song Yan','songyan@gmail.com','$2y$10$Jiw442B9iwXnwYilBrvRCuGY.op7.iAB65s.Ej0H4ta.BMB78iAXK','PMB-2026-0021','sudah',83,'lulus','2026-02-23 16:32:33','2026IN0005'),(22,'Olivia Putri','oliviaputri@gmail.com','$2y$10$gYUgevkXHN5MKDlnHB1X9u9U2RYyrvzG4qnxz1U4dfd8CqxNoIslG','PMB-2026-0022','sudah',100,'lulus','2026-02-25 02:01:34','2026CO0006'),(23,'Sang Zhi','sangzhi@gmail.com','$2y$10$24ipbjJUJX.xU/w.hCewB.M670HFZMme2FmDV4f8BBDZeOriQqCTe','PMB-2026-0023','sudah',100,'lulus','2026-02-25 02:56:46','2026IN0006'),(24,'Ethan Alexander Vaughn','ethan@gmail.com','$2y$10$V51F7CG.xtiKuqQaoL1HluVH6aMadepQlvRMnkZukO960GKrWT5HC','PMB-2026-0024','sudah',100,'lulus','2026-02-25 05:48:48','2026EC0007'),(25,'Ahmad','ahmad@gmail.com','$2y$10$y12ttJGDE8rk6pGW/XGF5ulbKOOxqZeOTUMeQZuEvsvOXo1k7FhXq','PMB-2026-0025','sudah',86,'lulus','2026-02-26 04:49:33','2026CO0007');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-19 23:26:57