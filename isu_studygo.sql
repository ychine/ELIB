-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: isu_studygo
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `UID` bigint(20) unsigned NOT NULL,
  `First_Name` varchar(255) NOT NULL,
  `Last_Name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`UID`),
  CONSTRAINT `admin_uid_foreign` FOREIGN KEY (`UID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (4,'Me','pinaka admin shesh','2025-10-27 12:39:35','2025-11-22 08:23:49'),(26,'Rich','Benitz','2025-11-04 12:01:53','2025-11-04 12:01:53');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` bigint(20) unsigned DEFAULT NULL,
  `description` text DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_created_at_index` (`user_id`,`created_at`),
  KEY `audit_logs_action_created_at_index` (`action`,`created_at`),
  KEY `audit_logs_model_type_model_id_index` (`model_type`,`model_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=259 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,21,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 07:42:45','2025-11-22 07:42:45'),(2,21,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 07:42:54','2025-11-22 07:42:54'),(3,21,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 07:43:57','2025-11-22 07:43:57'),(4,21,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 07:51:29','2025-11-22 07:51:29'),(5,21,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 07:51:45','2025-11-22 07:51:45'),(6,21,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36 Edg/142.0.0.0','2025-11-22 07:52:05','2025-11-22 07:52:05'),(7,21,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36 Edg/142.0.0.0','2025-11-22 07:52:38','2025-11-22 07:52:38'),(8,21,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36 Edg/142.0.0.0','2025-11-22 07:53:26','2025-11-22 07:53:26'),(9,21,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 07:56:44','2025-11-22 07:56:44'),(10,21,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 07:56:59','2025-11-22 07:56:59'),(11,21,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36 Edg/142.0.0.0','2025-11-22 07:57:23','2025-11-22 07:57:23'),(12,21,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36 Edg/142.0.0.0','2025-11-22 07:58:05','2025-11-22 07:58:05'),(13,6,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 07:58:45','2025-11-22 07:58:45'),(14,6,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 07:59:36','2025-11-22 07:59:36'),(15,6,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36 Edg/142.0.0.0','2025-11-22 08:00:01','2025-11-22 08:00:01'),(16,6,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:05:20','2025-11-22 08:05:20'),(17,6,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:05:54','2025-11-22 08:05:54'),(18,6,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36 Edg/142.0.0.0','2025-11-22 08:06:49','2025-11-22 08:06:49'),(19,6,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:07:36','2025-11-22 08:07:36'),(20,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:07:42','2025-11-22 08:07:42'),(21,4,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:23:42','2025-11-22 08:23:42'),(22,4,'profile_update',NULL,NULL,'Updated profile information',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:23:49','2025-11-22 08:23:49'),(23,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:24:47','2025-11-22 08:24:47'),(24,21,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:25:00','2025-11-22 08:25:00'),(25,21,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:26:49','2025-11-22 08:26:49'),(26,21,'profile_update',NULL,NULL,'Updated profile information',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36 Edg/142.0.0.0','2025-11-22 08:27:55','2025-11-22 08:27:55'),(27,21,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36 Edg/142.0.0.0','2025-11-22 08:28:57','2025-11-22 08:28:57'),(28,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36 Edg/142.0.0.0','2025-11-22 08:29:05','2025-11-22 08:29:05'),(29,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:30:52','2025-11-22 08:30:52'),(30,21,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:31:11','2025-11-22 08:31:11'),(31,21,'profile_update',NULL,NULL,'Updated profile information',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:31:59','2025-11-22 08:31:59'),(32,21,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:36:46','2025-11-22 08:36:46'),(33,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:37:06','2025-11-22 08:37:06'),(34,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:38:15','2025-11-22 08:38:15'),(35,21,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:38:21','2025-11-22 08:38:21'),(36,21,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:38:38','2025-11-22 08:38:38'),(37,21,'profile_update',NULL,NULL,'Updated profile information',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:38:42','2025-11-22 08:38:42'),(38,21,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36 Edg/142.0.0.0','2025-11-22 08:42:09','2025-11-22 08:42:09'),(39,21,'profile_update',NULL,NULL,'Updated profile information',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36 Edg/142.0.0.0','2025-11-22 08:42:12','2025-11-22 08:42:12'),(40,21,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:43:19','2025-11-22 08:43:19'),(41,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:43:27','2025-11-22 08:43:27'),(42,27,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:44:25','2025-11-22 08:44:25'),(43,27,'profile_update',NULL,NULL,'Updated profile information',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:44:29','2025-11-22 08:44:29'),(44,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:47:00','2025-11-22 08:47:00'),(45,21,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:47:14','2025-11-22 08:47:14'),(46,21,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:47:50','2025-11-22 08:47:50'),(47,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:47:59','2025-11-22 08:47:59'),(48,4,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:49:16','2025-11-22 08:49:16'),(49,4,'profile_update',NULL,NULL,'Updated profile information',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:49:21','2025-11-22 08:49:21'),(50,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:51:23','2025-11-22 08:51:23'),(51,6,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 08:51:40','2025-11-22 08:51:40'),(52,6,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 09:22:34','2025-11-22 09:22:34'),(53,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 09:22:40','2025-11-22 09:22:40'),(54,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 09:23:09','2025-11-22 09:23:09'),(55,21,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 09:23:15','2025-11-22 09:23:15'),(56,21,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 09:28:02','2025-11-22 09:28:02'),(57,21,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 09:28:42','2025-11-22 09:28:42'),(58,21,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 09:33:23','2025-11-22 09:33:23'),(59,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 09:33:30','2025-11-22 09:33:30'),(60,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 09:36:04','2025-11-22 09:36:04'),(61,29,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-22 09:37:08','2025-11-22 09:37:08'),(62,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 09:53:30','2025-11-22 09:53:30'),(63,21,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 09:56:48','2025-11-22 09:56:48'),(64,21,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-22 10:31:20','2025-11-22 10:31:20'),(65,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-22 21:03:16','2025-11-22 21:03:16'),(66,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-22 21:05:35','2025-11-22 21:05:35'),(67,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-22 21:05:44','2025-11-22 21:05:44'),(68,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-22 21:08:05','2025-11-22 21:08:05'),(69,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-22 21:08:12','2025-11-22 21:08:12'),(70,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:03:21','2025-11-23 01:03:21'),(71,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:06:11','2025-11-23 01:06:11'),(72,21,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:06:16','2025-11-23 01:06:16'),(73,21,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:06:49','2025-11-23 01:06:49'),(74,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:08:16','2025-11-23 01:08:16'),(75,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:18:57','2025-11-23 01:18:57'),(76,6,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:19:14','2025-11-23 01:19:14'),(77,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:19:30','2025-11-23 01:19:30'),(78,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:20:30','2025-11-23 01:20:30'),(79,21,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:20:38','2025-11-23 01:20:38'),(80,21,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:22:12','2025-11-23 01:22:12'),(81,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:22:34','2025-11-23 01:22:34'),(82,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:29:39','2025-11-23 01:29:39'),(83,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:29:48','2025-11-23 01:29:48'),(84,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:39:42','2025-11-23 01:39:42'),(85,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:41:45','2025-11-23 01:41:45'),(86,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:51:25','2025-11-23 01:51:25'),(87,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:52:47','2025-11-23 01:52:47'),(88,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:54:29','2025-11-23 01:54:29'),(89,21,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:54:42','2025-11-23 01:54:42'),(90,21,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:55:42','2025-11-23 01:55:42'),(91,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 01:55:47','2025-11-23 01:55:47'),(92,27,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 02:10:55','2025-11-23 02:10:55'),(93,27,'profile_update',NULL,NULL,'Updated profile information',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 02:11:41','2025-11-23 02:11:41'),(94,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 02:17:27','2025-11-23 02:17:27'),(95,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 02:17:35','2025-11-23 02:17:35'),(96,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 02:17:57','2025-11-23 02:17:57'),(97,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 02:18:08','2025-11-23 02:18:08'),(98,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 02:18:16','2025-11-23 02:18:16'),(99,21,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 02:18:22','2025-11-23 02:18:22'),(100,21,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 02:20:05','2025-11-23 02:20:05'),(101,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 02:20:17','2025-11-23 02:20:17'),(102,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 02:32:13','2025-11-23 02:32:13'),(103,6,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 02:32:23','2025-11-23 02:32:23'),(104,6,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 02:55:37','2025-11-23 02:55:37'),(105,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 02:55:45','2025-11-23 02:55:45'),(106,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 02:57:32','2025-11-23 02:57:32'),(107,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 02:57:46','2025-11-23 02:57:46'),(108,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 03:03:44','2025-11-23 03:03:44'),(109,6,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 03:03:49','2025-11-23 03:03:49'),(110,6,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 03:04:27','2025-11-23 03:04:27'),(111,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 03:04:34','2025-11-23 03:04:34'),(112,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 03:07:50','2025-11-23 03:07:50'),(113,6,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 03:08:45','2025-11-23 03:08:45'),(114,6,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 03:09:37','2025-11-23 03:09:37'),(115,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 03:09:43','2025-11-23 03:09:43'),(116,6,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 03:10:57','2025-11-23 03:10:57'),(117,27,'community_resource_upload','App\\Models\\Resource',22,'Uploaded community resource: RIZAL REVIEWER',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 05:08:12','2025-11-23 05:08:12'),(118,6,'approve_community_upload','App\\Models\\Resource',22,'Approved community upload: RIZAL REVIEWER',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 05:17:21','2025-11-23 05:17:21'),(119,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 05:45:18','2025-11-23 05:45:18'),(120,29,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 05:45:45','2025-11-23 05:45:45'),(121,29,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 05:48:47','2025-11-23 05:48:47'),(122,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 05:48:55','2025-11-23 05:48:55'),(123,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 06:26:50','2025-11-23 06:26:50'),(124,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 06:26:59','2025-11-23 06:26:59'),(125,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 06:28:20','2025-11-23 06:28:20'),(126,6,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 06:28:29','2025-11-23 06:28:29'),(127,6,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 06:33:06','2025-11-23 06:33:06'),(128,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 06:33:15','2025-11-23 06:33:15'),(129,6,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 06:58:42','2025-11-23 06:58:42'),(130,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 07:05:43','2025-11-23 07:05:43'),(131,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 07:08:55','2025-11-23 07:08:55'),(132,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 07:13:20','2025-11-23 07:13:20'),(133,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 07:13:29','2025-11-23 07:13:29'),(134,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 07:53:28','2025-11-23 07:53:28'),(135,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 07:53:37','2025-11-23 07:53:37'),(136,31,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 07:57:38','2025-11-23 07:57:38'),(137,31,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 07:58:13','2025-11-23 07:58:13'),(138,31,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 08:01:41','2025-11-23 08:01:41'),(139,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 08:01:51','2025-11-23 08:01:51'),(140,27,'profile_update',NULL,NULL,'Updated profile information',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','2025-11-23 08:02:50','2025-11-23 08:02:50'),(141,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 10:10:25','2025-11-23 10:10:25'),(142,6,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 10:10:37','2025-11-23 10:10:37'),(143,6,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 10:12:19','2025-11-23 10:12:19'),(144,29,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 10:12:27','2025-11-23 10:12:27'),(145,29,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 10:12:40','2025-11-23 10:12:40'),(146,31,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 10:13:08','2025-11-23 10:13:08'),(147,31,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 10:18:30','2025-11-23 10:18:30'),(148,6,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 10:18:41','2025-11-23 10:18:41'),(149,6,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 10:46:06','2025-11-23 10:46:06'),(150,21,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 10:46:34','2025-11-23 10:46:34'),(151,21,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 10:47:30','2025-11-23 10:47:30'),(152,21,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 10:50:48','2025-11-23 10:50:48'),(153,21,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 10:51:07','2025-11-23 10:51:07'),(154,6,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 10:51:13','2025-11-23 10:51:13'),(155,6,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 10:51:56','2025-11-23 10:51:56'),(156,21,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 10:54:49','2025-11-23 10:54:49'),(157,21,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:03:16','2025-11-23 11:03:16'),(158,29,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:03:28','2025-11-23 11:03:28'),(159,29,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:04:08','2025-11-23 11:04:08'),(160,32,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:07:24','2025-11-23 11:07:24'),(161,32,'community_resource_upload','App\\Models\\Resource',23,'Uploaded community resource: SUS upload',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:12:10','2025-11-23 11:12:10'),(162,32,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:12:24','2025-11-23 11:12:24'),(163,6,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:13:08','2025-11-23 11:13:08'),(164,6,'approve_community_upload','App\\Models\\Resource',23,'Approved community upload: SUS upload',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:13:43','2025-11-23 11:13:43'),(165,6,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:13:48','2025-11-23 11:13:48'),(166,32,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:14:00','2025-11-23 11:14:00'),(167,32,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:14:13','2025-11-23 11:14:13'),(168,6,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:14:21','2025-11-23 11:14:21'),(169,6,'resource_flag','App\\Models\\Resource',23,'Flagged resource: SUS upload',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:14:49','2025-11-23 11:14:49'),(170,6,'resource_flag','App\\Models\\Resource',23,'Flagged resource: SUS upload',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:21:33','2025-11-23 11:21:33'),(171,6,'resource_flag','App\\Models\\Resource',23,'Flagged resource: SUS upload',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:21:52','2025-11-23 11:21:52'),(172,6,'resource_flag','App\\Models\\Resource',23,'Flagged resource: SUS upload',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:25:12','2025-11-23 11:25:12'),(173,6,'resource_flag','App\\Models\\Resource',23,'Flagged resource: SUS upload',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:26:39','2025-11-23 11:26:39'),(174,6,'resource_flag','App\\Models\\Resource',23,'Flagged resource: SUS upload',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:29:18','2025-11-23 11:29:18'),(175,6,'resource_flag','App\\Models\\Resource',23,'Flagged resource: SUS upload',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:33:57','2025-11-23 11:33:57'),(176,6,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:34:04','2025-11-23 11:34:04'),(177,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:34:14','2025-11-23 11:34:14'),(178,4,'admin_mark_false_alarm','App\\Models\\ResourceReport',7,'Marked report as false alarm for resource: SUS upload',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:38:49','2025-11-23 11:38:49'),(179,4,'admin_mark_false_alarm','App\\Models\\ResourceReport',6,'Marked report as false alarm for resource: SUS upload',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:38:52','2025-11-23 11:38:52'),(180,4,'admin_mark_false_alarm','App\\Models\\ResourceReport',5,'Marked report as false alarm for resource: SUS upload',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:38:54','2025-11-23 11:38:54'),(181,4,'admin_mark_false_alarm','App\\Models\\ResourceReport',4,'Marked report as false alarm for resource: SUS upload',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:38:58','2025-11-23 11:38:58'),(182,4,'admin_mark_false_alarm','App\\Models\\ResourceReport',3,'Marked report as false alarm for resource: SUS upload',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:39:00','2025-11-23 11:39:00'),(183,4,'admin_mark_false_alarm','App\\Models\\ResourceReport',2,'Marked report as false alarm for resource: SUS upload',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:39:05','2025-11-23 11:39:05'),(184,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:39:13','2025-11-23 11:39:13'),(185,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:39:18','2025-11-23 11:39:18'),(186,27,'resource_report','App\\Models\\Resource',23,'Reported resource: SUS upload',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:39:35','2025-11-23 11:39:35'),(187,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:39:43','2025-11-23 11:39:43'),(188,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:39:49','2025-11-23 11:39:49'),(189,4,'admin_ban_user','App\\Models\\User',32,'Banned user: benitezpurp@gmail.com',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:40:27','2025-11-23 11:40:27'),(190,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:40:33','2025-11-23 11:40:33'),(191,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:40:47','2025-11-23 11:40:47'),(192,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:41:02','2025-11-23 11:41:02'),(193,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 11:51:41','2025-11-23 11:51:41'),(194,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 12:13:54','2025-11-23 12:13:54'),(195,4,'admin_ban_user','App\\Models\\User',32,'Banned user: benitezpurp@gmail.com',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 12:14:52','2025-11-23 12:14:52'),(196,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 12:24:15','2025-11-23 12:24:15'),(197,31,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 12:25:44','2025-11-23 12:25:44'),(198,31,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 12:26:04','2025-11-23 12:26:04'),(199,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 12:26:11','2025-11-23 12:26:11'),(200,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 12:26:35','2025-11-23 12:26:35'),(201,25,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 12:26:47','2025-11-23 12:26:47'),(202,25,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 12:31:41','2025-11-23 12:31:41'),(203,6,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 12:32:23','2025-11-23 12:32:23'),(204,6,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 12:43:40','2025-11-23 12:43:40'),(205,25,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 12:44:14','2025-11-23 12:44:14'),(206,25,'community_resource_upload','App\\Models\\Resource',24,'Uploaded community resource: Configuring  VLAN and trunking',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 12:45:26','2025-11-23 12:45:26'),(207,25,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 12:45:36','2025-11-23 12:45:36'),(208,6,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 12:45:44','2025-11-23 12:45:44'),(209,6,'approve_community_upload','App\\Models\\Resource',24,'Approved community upload: Configuring  VLAN and trunking',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 12:46:07','2025-11-23 12:46:07'),(210,6,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 12:46:19','2025-11-23 12:46:19'),(211,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 12:46:24','2025-11-23 12:46:24'),(212,25,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 20:43:10','2025-11-23 20:43:10'),(213,25,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 20:45:38','2025-11-23 20:45:38'),(214,25,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 20:46:51','2025-11-23 20:46:51'),(215,31,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 20:49:26','2025-11-23 20:49:26'),(216,31,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 20:50:19','2025-11-23 20:50:19'),(217,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 20:50:24','2025-11-23 20:50:24'),(218,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 20:55:01','2025-11-23 20:55:01'),(219,27,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 20:55:58','2025-11-23 20:55:58'),(220,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 20:56:45','2025-11-23 20:56:45'),(221,4,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 20:56:51','2025-11-23 20:56:51'),(222,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 20:58:16','2025-11-23 20:58:16'),(223,31,'login',NULL,NULL,'User logged in',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 21:01:16','2025-11-23 21:01:16'),(224,21,'login',NULL,NULL,'User logged in',NULL,NULL,'192.168.1.7','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36','2025-11-23 23:22:10','2025-11-23 23:22:10'),(225,31,'login',NULL,NULL,'User logged in',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 23:23:52','2025-11-23 23:23:52'),(226,21,'login',NULL,NULL,'User logged in',NULL,NULL,'192.168.1.7','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36','2025-11-23 23:26:19','2025-11-23 23:26:19'),(227,31,'logout',NULL,NULL,'User logged out',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 23:50:02','2025-11-23 23:50:02'),(228,4,'login',NULL,NULL,'User logged in',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-23 23:50:28','2025-11-23 23:50:28'),(229,21,'profile_picture_update',NULL,NULL,'Updated profile picture',NULL,NULL,'192.168.1.7','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36','2025-11-24 00:23:27','2025-11-24 00:23:27'),(230,21,'logout',NULL,NULL,'User logged out',NULL,NULL,'192.168.1.7','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36','2025-11-24 01:02:27','2025-11-24 01:02:27'),(231,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 01:05:05','2025-11-24 01:05:05'),(232,4,'login',NULL,NULL,'User logged in',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 04:43:28','2025-11-24 04:43:28'),(233,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 04:45:58','2025-11-24 04:45:58'),(234,27,'login',NULL,NULL,'User logged in',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 04:46:22','2025-11-24 04:46:22'),(235,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 04:48:39','2025-11-24 04:48:39'),(236,6,'login',NULL,NULL,'User logged in',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 04:48:54','2025-11-24 04:48:54'),(237,6,'logout',NULL,NULL,'User logged out',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 04:49:22','2025-11-24 04:49:22'),(238,4,'login',NULL,NULL,'User logged in',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 04:49:29','2025-11-24 04:49:29'),(239,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 04:49:34','2025-11-24 04:49:34'),(240,27,'login',NULL,NULL,'User logged in',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 04:49:40','2025-11-24 04:49:40'),(241,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 05:05:38','2025-11-24 05:05:38'),(242,4,'login',NULL,NULL,'User logged in',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 05:06:27','2025-11-24 05:06:27'),(243,4,'profile_update',NULL,NULL,'Updated profile information',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 05:07:48','2025-11-24 05:07:48'),(244,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 05:08:04','2025-11-24 05:08:04'),(245,4,'login',NULL,NULL,'User logged in',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 05:08:39','2025-11-24 05:08:39'),(246,4,'logout',NULL,NULL,'User logged out',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 05:08:47','2025-11-24 05:08:47'),(247,27,'login',NULL,NULL,'User logged in',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 05:08:57','2025-11-24 05:08:57'),(248,27,'profile_update',NULL,NULL,'Updated profile information',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 05:09:35','2025-11-24 05:09:35'),(249,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 05:22:24','2025-11-24 05:22:24'),(250,6,'login',NULL,NULL,'User logged in',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 05:26:08','2025-11-24 05:26:08'),(251,6,'profile_update',NULL,NULL,'Updated profile information',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 05:26:24','2025-11-24 05:26:24'),(252,6,'logout',NULL,NULL,'User logged out',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 05:26:42','2025-11-24 05:26:42'),(253,31,'login',NULL,NULL,'User logged in',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 05:33:55','2025-11-24 05:33:55'),(254,31,'logout',NULL,NULL,'User logged out',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 05:34:13','2025-11-24 05:34:13'),(255,31,'login',NULL,NULL,'User logged in',NULL,NULL,'192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 05:39:06','2025-11-24 05:39:06'),(256,27,'login',NULL,NULL,'User logged in',NULL,NULL,'192.168.1.129','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 06:37:38','2025-11-24 06:37:38'),(257,27,'logout',NULL,NULL,'User logged out',NULL,NULL,'192.168.1.129','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','2025-11-24 06:39:05','2025-11-24 06:39:05'),(258,27,'login',NULL,NULL,'User logged in',NULL,NULL,'192.168.1.129','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-11-24 06:39:24','2025-11-24 06:39:24');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authors`
--

DROP TABLE IF EXISTS `authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `authors_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authors`
--

LOCK TABLES `authors` WRITE;
/*!40000 ALTER TABLE `authors` DISABLE KEYS */;
INSERT INTO `authors` VALUES (1,'tutorialspoint','2025-11-09 15:55:22','2025-11-09 15:55:22'),(2,'Rex Hogan','2025-11-09 15:55:22','2025-11-09 15:55:22'),(3,'Mike O’Leary','2025-11-09 15:55:22','2025-11-09 15:55:22'),(4,'Anthony De Barros','2025-11-09 15:55:22','2025-11-09 15:55:22'),(8,'Adeel Javed','2025-11-09 08:24:08','2025-11-09 08:24:08'),(9,'Sammie Bae','2025-11-10 01:35:23','2025-11-10 01:35:23'),(10,'John W. Creswell','2025-11-10 03:55:06','2025-11-10 03:55:06'),(11,'Thomas Mailund','2025-11-14 08:20:16','2025-11-14 08:20:16'),(12,'Paul Trott','2025-11-14 08:25:33','2025-11-14 08:25:33'),(13,'Philip Conrod','2025-11-14 08:39:49','2025-11-14 08:39:49'),(14,'Kai Hwang and Min Chen','2025-11-14 17:22:53','2025-11-14 17:22:53'),(15,'Hans Petter Langtangen','2025-11-14 17:27:11','2025-11-14 17:27:11'),(16,'Cory Althoff','2025-11-14 17:34:01','2025-11-14 17:34:01'),(17,'Judith L. Gersting','2025-11-14 17:37:44','2025-11-14 17:37:44'),(18,'R. Balakrishnan','2025-11-14 17:51:04','2025-11-14 17:51:04'),(19,'Sriraman Sridharan','2025-11-14 17:51:04','2025-11-14 17:51:04'),(20,'Walter Goralski','2025-11-14 20:55:41','2025-11-14 20:55:41'),(21,'Narasimha Karumanchi','2025-11-14 22:46:26','2025-11-14 22:46:26'),(22,'Kai Hwang','2025-11-17 09:50:26','2025-11-17 09:50:26'),(23,'Min Chen','2025-11-17 09:50:26','2025-11-17 09:50:26'),(24,'Jeffrey T. Barton','2025-11-18 04:25:22','2025-11-18 04:25:22'),(25,'Dowoxi','2025-11-23 05:08:12','2025-11-23 05:08:12'),(26,'bawal','2025-11-23 11:12:10','2025-11-23 11:12:10'),(27,'Justin Bieber','2025-11-23 12:45:26','2025-11-23 12:45:26');
/*!40000 ALTER TABLE `authors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `borrow_history`
--

DROP TABLE IF EXISTS `borrow_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `borrow_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `borrower_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `resource_id` bigint(20) unsigned NOT NULL,
  `action_by` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `rejection_reason` text DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `return_date` datetime DEFAULT NULL,
  `returned_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `borrow_history_borrower_id_foreign` (`borrower_id`),
  KEY `borrow_history_user_id_foreign` (`user_id`),
  KEY `borrow_history_resource_id_foreign` (`resource_id`),
  KEY `borrow_history_action_by_foreign` (`action_by`),
  CONSTRAINT `borrow_history_action_by_foreign` FOREIGN KEY (`action_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `borrow_history_borrower_id_foreign` FOREIGN KEY (`borrower_id`) REFERENCES `borrower` (`Borrower_ID`) ON DELETE CASCADE,
  CONSTRAINT `borrow_history_resource_id_foreign` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`Resource_ID`) ON DELETE CASCADE,
  CONSTRAINT `borrow_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `borrow_history`
--

LOCK TABLES `borrow_history` WRITE;
/*!40000 ALTER TABLE `borrow_history` DISABLE KEYS */;
INSERT INTO `borrow_history` VALUES (1,27,31,22,NULL,'requested',NULL,NULL,'2025-11-29 02:13:00',NULL,'2025-11-23 10:13:46','2025-11-23 10:13:46'),(4,26,27,14,6,'approved',NULL,'2025-11-24 12:49:17','2025-11-29 00:00:00',NULL,'2025-11-24 04:49:17','2025-11-24 04:49:17');
/*!40000 ALTER TABLE `borrow_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `borrower`
--

DROP TABLE IF EXISTS `borrower`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `borrower` (
  `Borrower_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `UID` bigint(20) unsigned NOT NULL,
  `resource_id` bigint(20) unsigned NOT NULL,
  `Approved_By` bigint(20) unsigned DEFAULT NULL,
  `Approved_Date` datetime DEFAULT NULL,
  `Return_Date` datetime DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `isReturned` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Borrower_ID`),
  KEY `borrower_uid_foreign` (`UID`),
  KEY `borrower_approved_by_foreign` (`Approved_By`),
  KEY `borrower_resource_id_foreign` (`resource_id`),
  CONSTRAINT `borrower_approved_by_foreign` FOREIGN KEY (`Approved_By`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `borrower_resource_id_foreign` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`Resource_ID`) ON DELETE CASCADE,
  CONSTRAINT `borrower_uid_foreign` FOREIGN KEY (`UID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `borrower`
--

LOCK TABLES `borrower` WRITE;
/*!40000 ALTER TABLE `borrower` DISABLE KEYS */;
INSERT INTO `borrower` VALUES (1,27,1,6,'2025-11-15 00:00:00',NULL,NULL,0,'2025-11-09 11:27:41','2025-11-15 00:16:09'),(2,27,17,6,'2025-11-15 00:00:00',NULL,NULL,0,'2025-11-14 17:53:21','2025-11-15 00:32:35'),(3,27,8,6,'2025-11-15 00:00:00',NULL,NULL,0,'2025-11-14 20:50:20','2025-11-15 00:32:23'),(4,27,18,6,'2025-11-15 00:00:00',NULL,NULL,0,'2025-11-14 21:02:16','2025-11-15 00:22:18'),(13,29,17,6,'2025-11-17 00:00:00',NULL,NULL,0,'2025-11-16 00:29:46','2025-11-16 19:08:03'),(14,30,19,6,'2025-11-17 00:00:00',NULL,NULL,0,'2025-11-16 19:13:08','2025-11-16 19:13:36'),(15,30,18,6,'2025-11-17 00:00:00',NULL,NULL,0,'2025-11-16 22:25:37','2025-11-16 22:25:48'),(18,29,20,6,'2025-11-17 00:00:00','2025-11-17 00:00:00',NULL,1,'2025-11-17 09:50:43','2025-11-17 11:19:41'),(19,29,20,NULL,NULL,NULL,NULL,0,'2025-11-17 11:19:56','2025-11-17 11:19:56'),(20,27,20,6,'2025-11-18 00:00:00','2025-11-23 00:00:00',NULL,1,'2025-11-18 03:06:09','2025-11-23 02:26:20'),(26,27,14,6,'2025-11-24 12:49:17','2025-11-29 00:00:00',NULL,0,'2025-11-23 03:14:21','2025-11-24 04:49:17'),(27,31,22,NULL,NULL,'2025-11-29 02:13:00',NULL,0,'2025-11-23 10:13:46','2025-11-23 10:13:46');
/*!40000 ALTER TABLE `borrower` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `borrowers`
--

DROP TABLE IF EXISTS `borrowers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `borrowers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `borrowers`
--

LOCK TABLES `borrowers` WRITE;
/*!40000 ALTER TABLE `borrowers` DISABLE KEYS */;
/*!40000 ALTER TABLE `borrowers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campus`
--

DROP TABLE IF EXISTS `campus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campus` (
  `Campus_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Campus_Name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Campus_ID`),
  UNIQUE KEY `campus_campus_name_unique` (`Campus_Name`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campus`
--

LOCK TABLES `campus` WRITE;
/*!40000 ALTER TABLE `campus` DISABLE KEYS */;
INSERT INTO `campus` VALUES (2,'Santiago','2025-10-27 04:02:44','2025-10-27 04:02:44');
/*!40000 ALTER TABLE `campus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courses_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,'BSIT','Bachelor of Science in Information Technology','2025-11-23 06:56:32','2025-11-23 06:56:32'),(2,'BSA','Bachelor of Science in Agriculture','2025-11-23 06:56:32','2025-11-23 06:56:32'),(3,'BSLEA','Bachelor of Science in Law Enforcement Administration','2025-11-23 06:56:32','2025-11-23 06:56:32');
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faculty`
--

DROP TABLE IF EXISTS `faculty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faculty` (
  `Faculty_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `UID` bigint(20) unsigned NOT NULL,
  `First_Name` varchar(255) NOT NULL,
  `Last_Name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Faculty_ID`),
  KEY `faculty_uid_foreign` (`UID`),
  CONSTRAINT `faculty_uid_foreign` FOREIGN KEY (`UID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faculty`
--

LOCK TABLES `faculty` WRITE;
/*!40000 ALTER TABLE `faculty` DISABLE KEYS */;
INSERT INTO `faculty` VALUES (4,25,'Justin','Bieber','2025-11-01 09:21:32','2025-11-01 09:21:32'),(5,28,'Mlepnos','Gitar','2025-11-01 17:53:49','2025-11-01 17:53:49');
/*!40000 ALTER TABLE `faculty` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `librarian`
--

DROP TABLE IF EXISTS `librarian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `librarian` (
  `UID` bigint(20) unsigned NOT NULL,
  `First_Name` varchar(255) NOT NULL,
  `Last_Name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `position_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`UID`),
  KEY `librarian_position_id_foreign` (`position_id`),
  CONSTRAINT `librarian_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `librarian_positions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `librarian_uid_foreign` FOREIGN KEY (`UID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `librarian`
--

LOCK TABLES `librarian` WRITE;
/*!40000 ALTER TABLE `librarian` DISABLE KEYS */;
INSERT INTO `librarian` VALUES (6,'Richelle','Benitez','2025-10-27 04:52:29','2025-11-24 05:26:24',2),(21,'Batutay','Sabaola','2025-11-01 08:47:56','2025-11-23 10:51:53',3);
/*!40000 ALTER TABLE `librarian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `librarian_positions`
--

DROP TABLE IF EXISTS `librarian_positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `librarian_positions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '{"add": false, "archive": false, "delete": false}' CHECK (json_valid(`permissions`)),
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `librarian_positions_created_by_foreign` (`created_by`),
  CONSTRAINT `librarian_positions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `librarian_positions`
--

LOCK TABLES `librarian_positions` WRITE;
/*!40000 ALTER TABLE `librarian_positions` DISABLE KEYS */;
INSERT INTO `librarian_positions` VALUES (1,'Librarian','{\"add\": false, \"archive\": false, \"delete\": false, \"edit\": false}',4,'2025-11-04 08:00:16','2025-11-04 08:00:16'),(2,'University Librarian','{\"add\": true, \"archive\": true, \"delete\": true, \"edit\": true}',4,'2025-11-04 08:04:06','2025-11-04 08:04:06'),(3,'Thesis Section Librarian','{\"add\":true,\"edit\":true,\"archive\":true,\"delete\":false}',4,'2025-11-04 11:53:01','2025-11-04 11:53:01');
/*!40000 ALTER TABLE `librarian_positions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_10_16_174846_add_role_to_users_table',2),(5,'2025_10_25_082040_create_campus_table',3),(6,'2025_10_25_082342_update_users_table_add_campus_id',3),(7,'2025_10_25_082416_create_faculty_table',3),(8,'2025_10_25_082429_create_admin_table',3),(9,'2025_10_25_082448_create_resources_table',3),(10,'2025_10_25_082501_create_borrower_table',3),(11,'2025_10_27_111752_add_is_approved_to_users_table',4),(12,'2025_10_27_121707_add_unique_constraint_to_campus_name',5),(13,'2025_10_27_122913_create_librarian_table',6),(14,'2025_10_27_122958_remove_name_from_users_table',7),(17,'2025_10_27_124127_create_student_table',8),(19,'2025_11_01_134003_create_verify_codes_table',9),(21,'2025_11_04_154238_create_librarian_positions_table',10),(22,'2025_11_04_154239_add_position_id_to_librarian_table',10),(23,'2025_11_04_154240_add_status_author_to_resources_and_university_librarian_to_users_table',10),(24,'2025_11_04_160336_drop_is_university_librarian_from_users_table',11),(25,'2025_11_06_161015_alter_resources_table_description_column',12),(26,'2025_11_09_102908_add_publish_date_components_to_resources_table',13),(27,'2025_11_09_104822_drop_publish_date_from_resources_table',14),(28,'2025_11_09_141319_add_tracking_columns_to_resources_table',15),(29,'2025_11_09_141359_create_resource_views_table',15),(30,'2025_11_21_141347_create_sessions_table',16),(31,'2025_11_22_153043_add_profile_picture_to_users_table',17),(32,'2025_11_22_153048_add_last_login_to_users_table',18),(33,'2025_11_22_153051_create_audit_logs_table',19),(34,'2025_11_09_104729_make_publish_date_nullable_in_resources_table',20),(35,'2025_11_09_183614_create_tags_table',99),(36,'2025_11_22_172113_remove_extra_campuses',100),(37,'2025_11_23_093646_add_rejection_reason_to_borrower_table',101),(38,'2025_11_23_112233_create_borrow_history_table',102),(39,'2025_11_23_112313_change_approved_and_return_dates_to_datetime_in_borrower_table',103),(40,'2025_11_23_124800_add_community_upload_fields_to_resources_table',104),(41,'2025_11_23_124806_add_student_id_to_student_table',104),(42,'2025_11_23_124807_create_resource_reports_table',104),(43,'2025_11_23_125338_remove_is_community_upload_from_resources_table',105),(44,'2025_11_23_125655_add_approval_status_to_resources_table',106),(45,'2025_11_09_183741_create_ratings_table',107),(46,'2025_11_09_184549_create_borrowers_table',107),(47,'2025_11_23_111927_change_borrower_dates_to_datetime',107),(48,'2025_11_23_144555_create_courses_table',107),(49,'2025_11_23_144611_add_course_and_student_number_to_students_table',107),(50,'2025_11_23_190010_add_is_banned_to_users_table',108),(51,'2025_11_23_190132_add_flagged_by_librarian_to_resource_reports_table',108);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ratings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ratings_resource_id_user_id_unique` (`resource_id`,`user_id`),
  KEY `ratings_user_id_foreign` (`user_id`),
  CONSTRAINT `ratings_resource_id_foreign` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`Resource_ID`) ON DELETE CASCADE,
  CONSTRAINT `ratings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ratings`
--

LOCK TABLES `ratings` WRITE;
/*!40000 ALTER TABLE `ratings` DISABLE KEYS */;
INSERT INTO `ratings` VALUES (1,13,27,4,'2025-11-22 06:27:50','2025-11-22 06:27:50');
/*!40000 ALTER TABLE `ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resource_authors`
--

DROP TABLE IF EXISTS `resource_authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resource_authors` (
  `resource_id` bigint(20) unsigned NOT NULL,
  `author_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`resource_id`,`author_id`),
  KEY `resource_authors_author_id_foreign` (`author_id`),
  CONSTRAINT `resource_authors_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `resource_authors_resource_id_foreign` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`Resource_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resource_authors`
--

LOCK TABLES `resource_authors` WRITE;
/*!40000 ALTER TABLE `resource_authors` DISABLE KEYS */;
INSERT INTO `resource_authors` VALUES (1,1,NULL,NULL),(2,2,NULL,NULL),(3,3,NULL,NULL),(4,4,NULL,NULL),(5,8,NULL,NULL),(7,9,NULL,NULL),(8,10,NULL,NULL),(9,11,NULL,NULL),(10,12,NULL,NULL),(11,13,NULL,NULL),(13,15,NULL,NULL),(14,16,NULL,NULL),(16,17,NULL,NULL),(17,18,NULL,NULL),(17,19,NULL,NULL),(18,20,NULL,NULL),(19,21,NULL,NULL),(20,22,NULL,NULL),(20,23,NULL,NULL),(21,24,NULL,NULL),(22,25,NULL,NULL),(23,26,NULL,NULL);
/*!40000 ALTER TABLE `resource_authors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resource_reports`
--

DROP TABLE IF EXISTS `resource_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resource_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` bigint(20) unsigned NOT NULL,
  `reported_by` bigint(20) unsigned NOT NULL,
  `reason` enum('copyright_violation','abuse','inappropriate_content','spam','other') NOT NULL DEFAULT 'other',
  `description` text DEFAULT NULL,
  `status` enum('pending','reviewed','resolved','dismissed') NOT NULL DEFAULT 'pending',
  `flagged_by_librarian` tinyint(1) NOT NULL DEFAULT 0,
  `reviewed_by` bigint(20) unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_reports_resource_id_foreign` (`resource_id`),
  KEY `resource_reports_reported_by_foreign` (`reported_by`),
  KEY `resource_reports_reviewed_by_foreign` (`reviewed_by`),
  CONSTRAINT `resource_reports_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `resource_reports_resource_id_foreign` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`Resource_ID`) ON DELETE CASCADE,
  CONSTRAINT `resource_reports_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resource_reports`
--

LOCK TABLES `resource_reports` WRITE;
/*!40000 ALTER TABLE `resource_reports` DISABLE KEYS */;
INSERT INTO `resource_reports` VALUES (1,23,6,'inappropriate_content','Suspicious content','resolved',1,4,'2025-11-23 12:14:52','User banned by admin','2025-11-23 11:14:49','2025-11-23 12:14:52'),(2,23,6,'inappropriate_content','Suspish','resolved',1,4,'2025-11-23 11:39:05','Marked as false alarm by admin','2025-11-23 11:21:33','2025-11-23 11:39:05'),(3,23,6,'inappropriate_content','Suspish','resolved',1,4,'2025-11-23 11:39:00','Marked as false alarm by admin','2025-11-23 11:21:52','2025-11-23 11:39:00'),(4,23,6,'inappropriate_content','Suspish','resolved',1,4,'2025-11-23 11:38:58','Marked as false alarm by admin','2025-11-23 11:25:12','2025-11-23 11:38:58'),(5,23,6,'inappropriate_content','Suspish','resolved',1,4,'2025-11-23 11:38:54','Marked as false alarm by admin','2025-11-23 11:26:39','2025-11-23 11:38:54'),(6,23,6,'inappropriate_content','Suspish','resolved',1,4,'2025-11-23 11:38:52','Marked as false alarm by admin','2025-11-23 11:29:18','2025-11-23 11:38:52'),(7,23,6,'inappropriate_content','Suspish','resolved',1,4,'2025-11-23 11:38:49','Marked as false alarm by admin','2025-11-23 11:33:57','2025-11-23 11:38:49'),(8,23,27,'inappropriate_content','may cheat daw poh','resolved',0,4,'2025-11-23 11:40:27','User banned by admin','2025-11-23 11:39:35','2025-11-23 11:40:27');
/*!40000 ALTER TABLE `resource_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resource_tags`
--

DROP TABLE IF EXISTS `resource_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resource_tags` (
  `resource_id` bigint(20) unsigned NOT NULL,
  `tag_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`resource_id`,`tag_id`),
  KEY `resource_tags_tag_id_foreign` (`tag_id`),
  CONSTRAINT `resource_tags_resource_id_foreign` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`Resource_ID`) ON DELETE CASCADE,
  CONSTRAINT `resource_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resource_tags`
--

LOCK TABLES `resource_tags` WRITE;
/*!40000 ALTER TABLE `resource_tags` DISABLE KEYS */;
INSERT INTO `resource_tags` VALUES (7,3,NULL,NULL),(7,4,NULL,NULL),(7,5,NULL,NULL),(8,1,NULL,NULL),(8,2,NULL,NULL),(18,11,NULL,NULL),(18,12,NULL,NULL),(18,13,NULL,NULL),(19,4,NULL,NULL),(20,8,NULL,NULL),(20,9,NULL,NULL),(20,10,NULL,NULL),(21,7,NULL,NULL),(22,14,NULL,NULL),(22,15,NULL,NULL),(23,16,NULL,NULL);
/*!40000 ALTER TABLE `resource_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resource_views`
--

DROP TABLE IF EXISTS `resource_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resource_views` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `resource_views_resource_id_user_id_unique` (`resource_id`,`user_id`),
  KEY `resource_views_user_id_foreign` (`user_id`),
  CONSTRAINT `resource_views_resource_id_foreign` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`Resource_ID`) ON DELETE CASCADE,
  CONSTRAINT `resource_views_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resource_views`
--

LOCK TABLES `resource_views` WRITE;
/*!40000 ALTER TABLE `resource_views` DISABLE KEYS */;
INSERT INTO `resource_views` VALUES (1,4,27,'2025-11-09 07:14:32','2025-11-09 07:14:27','2025-11-09 07:14:32'),(2,5,27,'2025-11-09 08:29:33','2025-11-09 08:29:33','2025-11-09 08:29:33');
/*!40000 ALTER TABLE `resource_views` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resources`
--

DROP TABLE IF EXISTS `resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resources` (
  `Resource_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Resource_Name` varchar(255) NOT NULL,
  `File_Path` varchar(255) NOT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `Type` enum('Featured','Community Uploads') NOT NULL,
  `publish_year` int(11) DEFAULT NULL,
  `publish_month` int(11) DEFAULT NULL,
  `publish_day` int(11) DEFAULT NULL,
  `Uploaded_By` bigint(20) unsigned NOT NULL,
  `owner_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `Description` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Available',
  `approval_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'approved',
  `views` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Resource_ID`),
  KEY `resources_uploaded_by_foreign` (`Uploaded_By`),
  KEY `resources_owner_id_foreign` (`owner_id`),
  CONSTRAINT `resources_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `resources_uploaded_by_foreign` FOREIGN KEY (`Uploaded_By`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resources`
--

LOCK TABLES `resources` WRITE;
/*!40000 ALTER TABLE `resources` DISABLE KEYS */;
INSERT INTO `resources` VALUES (1,'Excel PivotTables','resources/1tbJHWeT8zNs3xme4IlFFklFylS4DbsPNBCZj9bv.pdf','thumbnails/1.jpg','Featured',2016,NULL,NULL,6,NULL,'2025-11-06 07:09:17','2025-11-23 02:33:55','PivotTable is an extremely powerful tool that you can use to slice and dice data. In this tutorial, you will learn these PivotTable features in detail along with examples. By the time\r\nyou complete this tutorial, you will have sufficient knowledge on PivotTable features that can get you started with exploring, analyzing, and reporting data based on the requirements.','Unavailable','approved',0),(2,'A Practical Guide to Database Design','resources/g6HrKyEKWh6qzzVBLea59tKFehM0PCR19iPa3m4l.pdf','thumbnails/2.jpg','Featured',2018,NULL,NULL,6,NULL,'2025-11-06 08:13:33','2025-11-20 13:24:27','This is a book intended for those who are involved in the design or development of a database system or application. It begins by focusing on how to create a logical data model where data are stored where it belongs. Next, data usage is reviewed to transform the logical model into a physical data model that will satisfy user performance requirements. Finally, it describes how to use various software tools to create user interfaces to review and update data in a database.','Available','approved',2),(3,'Cyber Operations: Building, Defending, and Attacking Modern Computer Networks','resources/4N8fCFP8ENhwcTRu6A88YJrZh2THp3rQ53seKPfg.pdf','thumbnails/3.jpg','Featured',2019,NULL,NULL,6,NULL,'2025-11-06 08:25:40','2025-11-14 08:37:35','Know how to set up, defend, and attack computer networks with this revised and expanded second edition.\r\n\r\nYou will learn to configure your network from the ground up, beginning with developing your own private virtual test environment, then setting up your own DNS server and AD infrastructure. You will continue with more advanced network services, web servers, and database servers and you will end by building your own web applications servers, including WordPress and Joomla!. Systems from 2011 through 2017 are covered, including Windows 7, Windows 8, Windows 10, Windows Server 2012, and Windows Server 2016 as well as a range of Linux distributions, including Ubuntu, CentOS, Mint, and OpenSUSE.','Available','approved',0),(4,'Practical SQL_ A Beginner’s Guide to Storytelling with Data','resources/Gr7KfjB0zy6AFk2sl2uxB23RvdfV3ivpcgWNU7FM.pdf','thumbnails/4.jpg','Featured',2018,5,2,6,NULL,'2025-11-09 03:02:48','2025-11-14 08:37:36','Practical SQL is an approachable and fast-paced guide to SQL (Structured Query Language), the standard programming language for defining, organizing, and exploring data in relational databases. The book focuses on using SQL to find the story your data tells, with the popular open-source database PostgreSQL and the pgAdmin interface as its primary tools.\r\n\r\nYou’ll first cover the fundamentals of databases and the SQL language, then build skills by analyzing data from the U.S. Census and other federal and state government agencies. With exercises and real-world examples in each chapter, this book will teach even those who have never programmed before all the tools necessary to build powerful databases and access information quickly and efficiently.\r\n\r\nYou’ll learn how to:\r\n- Create databases and related tables using your own data\r\n- Define the right data types for your information\r\n- Aggregate, sort, and filter data to find patterns\r\n- Use basic math and advanced statistical functions\r\n- Identify errors in data and clean them up\r\n- Import and export data using delimited text files\r\n- Write queries for geographic information systems (GIS)\r\n- Create advanced queries and automate tasks\r\n\r\nLearning SQL doesn’t have to be dry and complicated. Practical SQL delivers clear examples with an easy-to-follow approach to teach you the tools you need to build and manage your own databases.\r\n\r\nThis book uses PostgreSQL, but the SQL syntax is applicable to many database applications, including Microsoft SQL Server and MySQL.','Available','approved',3),(5,'Building Arduino Projects for the Internet of Things','resources/Xd3cPc3aI5PlGtf5lN44IQk2ToxhKFgRLVXEBiz7.pdf','thumbnails/5.jpg','Featured',2016,6,9,6,NULL,'2025-11-09 08:24:08','2025-11-14 08:37:36','Gain a strong foundation of Arduino-based device development, from which you can go in any direction according to your specific development needs and desires. You\'ll build Arduino-powered devices for everyday use, and then connect those devices to the Internet.\r\n\r\nYou\'ll be introduced to the building blocks of IoT, and then deploy those principles to by building a variety of useful projects. Projects in the books gradually introduce the reader to key topics such as internet connectivity with Arduino, common IoT protocols, custom web visualization, and Android apps that receive sensor data on-demand and in realtime. IoT device enthusiasts of all ages will want this book by their side when developing Android-based devices.\r\n\r\nIf you\'re one of the many who have decided to build your own Arduino-powered devices for IoT applications, then Building Arduino Projects for the Internet of Things is exactly what you need. This book is your singleresource--a guidebook for the eager-to-learn Arduino enthusiast--that teaches logically, methodically, and practically how the Arduino works and what you can build with it.','Available','approved',1),(7,'JavaScript Data Structures and Algorithms_ An Introduction to Understanding and Implementing Core Data Structure and Algorithm Fundamentals','resources/Yp9qFTNAfG3pn7mlcOmkf5zoC7yIpzS6MTicAzzU.pdf','thumbnails/7.jpg','Featured',2019,1,NULL,6,NULL,'2025-11-10 01:13:37','2025-11-23 21:06:29','Explore data structures and algorithm concepts and their relation to everyday JavaScript development. A basic understanding of these ideas is essential to any JavaScript developer wishing to analyze and build great software solutions.\r\n\r\nYou\'ll discover how to implement data structures such as hash tables, linked lists, stacks, queues, trees, and graphs. You\'ll also learn how a URL shortener, such as bit.ly, is developed and what is happening to the data as a PDF is uploaded to a webpage. This book covers the practical applications of data structures and algorithms to encryption, searching, sorting, and pattern matching.\r\n\r\nIt is crucial for JavaScript developers to understand how data structures work and how to design algorithms. This book and the accompanying code provide that essential foundation for doing so. With JavaScript Data Structures and Algorithms you canstart developing your knowledge and applying it to your JavaScript projects today.','Available','approved',7),(8,'Research Design  Qualitative, Quantitative, and Mixed Methods Approaches','resources/Zi76iwoYXAG4O6g72lCVhadDe1w3mgbIjaEU92sS.pdf','thumbnails/8.jpg','Featured',2014,NULL,NULL,6,NULL,'2025-11-10 03:55:04','2025-11-18 04:43:09','The book Research Design: Qualitative, Quantitative and Mixed Methods Approaches by Creswell (2014) covers three approaches-qualitative, quantitative and mixed methods. This educational book is informative and illustrative and is equally beneficial for students, teachers and researchers. Readers should have basic knowledge of research for better understanding of this book. There are two parts of the book. Part 1 (chapter 1-4) consists of steps for developing research proposal and part II (chapter 5-10) explains how to develop a research proposal or write a research report. A summary is given at the end of every chapter that helps the reader to recapitulate the ideas. Moreover, writing exercises and suggested readings at the end of every chapter are useful for the readers. Chapter 1 opens with-definition of research approaches and the author gives his opinion that selection of a research approach is based on the nature of the research problem, researchers\' experience and the audience of the study. The author defines qualitative, quantitative and mixed methods research. A distinction is made between quantitative and qualitative research approaches. The author believes that interest in qualitative research increased in the latter half of the 20th century. The worldviews, Fraenkel, Wallen and Hyun (2012) and Onwuegbuzie and Leech (2005) call them paradigms, have been explained. Sometimes, the use of language becomes too philosophical and technical. This is probably because the author had to explain some technical terms.','Available','approved',9),(9,'Functional Data Structures in R_ Advanced Statistical Programming in R ( PDFDrive )','resources/QeZevNC2i2SAHOZIEyMcI0lOfL3RjiBVuA1glCZe.pdf','thumbnails/9.jpg','Featured',2017,NULL,NULL,6,NULL,'2025-11-14 08:20:16','2025-11-18 04:43:07','Get an introduction to functional data structures using R and write more effective code and gain performance for your programs. This book teaches you workarounds because data in functional languages is not mutable: for example you’ll learn how to change variable-value bindings by modifying environments, which can be exploited to emulate pointers and implement traditional data structures. You’ll also see how, by abandoning traditional data structures, you can manipulate structures by building new versions rather than modifying them. You’ll discover how these so-called functional data structures are different from the traditional data structures you might know, but are worth understanding to do serious algorithmic programming in a functional language such as R.\r\n\r\n\r\nBy the end of Functional Data Structures in R, you’ll understand the choices to make in order to most effectively work with data structures when you cannot modify the data itself. These techniques are especially applicable for algorithmic development important in big data, finance, and other data science applications.','Available','approved',4),(10,'Innovation Management and New Product Development ( PDFDrive )','resources/BzppRKRi2EGHO3TGxhWPN1tiojEEJilwXUxa4Uvr.pdf','thumbnails/10.jpg','Featured',2005,NULL,NULL,6,NULL,'2025-11-14 08:25:33','2025-11-18 04:43:04','About Innovation Management And New Product Development\r\nThe subject of innovation management is often treated as a series of separate specialisms, rather than an integrated task. The main aim of this book, however, is to bring together the areas of innovation management and new product development and to keep a strong emphasis on innovation as a management process. Written in an accessible style, this third edition brings a change in structure to clearly set out three key areas for the student: Innovation management, managing technology and knowledge and new product development. This book will be suitable for undergraduates and postgraduates on a wide range of courses from marketing, strategic management, business studies and engineering.','Available','approved',5),(11,'Visual C# and Databases_ A Step-By-Step Database Programming Tutorial ( PDFDrive )','resources/rr40XCERbkPDmifB1QziB99yH0xUvipahYyXWjdi.pdf','thumbnails/11.jpg','Featured',2017,NULL,NULL,6,NULL,'2025-11-14 08:39:48','2025-11-18 04:43:02','About Visual C# and Databases: A Step-By-Step Database Programming Tutorial','Available','approved',1),(13,'A Primer on Scientific Programming with Python ( PDFDrive )','resources/ipa2EhJcysaU9Hk1M6hGu4tcCan5DIcwiXtfH2vb.pdf','thumbnails/13.jpg','Featured',2016,NULL,NULL,6,NULL,'2025-11-14 17:27:10','2025-11-15 00:33:39','The book serves as a first introduction to computer programming of scientific applications, using the high-level Python language. The exposition is example and problem-oriented, where the applications are taken from mathematics, numerical calculus, statistics, physics, biology and finance. The book teaches \"Matlab-style\" and procedural programming as well as object-oriented programming. High school mathematics is a required background and it is advantageous to study classical and numerical one-variable calculus in parallel with reading this book. Besides learning how to program computers, the reader will also learn how to solve mathematical problems, arising in various branches of science and engineering, with the aid of numerical methods and programming. By blending programming, mathematics and scientific applications, the book lays a solid foundation for practicing computational science.','Available','approved',3),(14,'The Self-Taught Programmer_ The Definitive Guide to Programming Professionally ( PDFDrive )','resources/42himZVUJnzemuGUVCMQ38LbMU68LkNxAoeNXzTU.pdf','thumbnails/14.jpg','Featured',2017,NULL,NULL,6,NULL,'2025-11-14 17:34:00','2025-11-23 02:07:05','One of the best software design books of all time\' - BookAuthorityCory Althoff is a self-taught programmer. After a year of self-study, he learned to program well enough to land a job as a software engineer II at eBay.','Available','approved',4),(16,'Mathematical structures for computer science _ discrete mathematics and its applications ( PDFDrive )','resources/OnmDXMJ1Q1RzYW7wdr2BdMe8DxLUQj8RIOzUTakD.pdf','thumbnails/16.jpg','Featured',1982,NULL,NULL,6,NULL,'2025-11-14 17:37:48','2025-11-16 00:08:27','Designed for computer science majors, Mathematical Structures for Computer Science offers a pedagogically rich and intuitive introduction to discrete mathematical structures. The book is both accessible and comprehensive, and the first three editions have been very popular among students and professors for over 16 years.','Available','approved',4),(17,'Foundations of Discrete Mathematics with Algorithms and Programming ( PDFDrive )','resources/5VfMl2ixXit5S8HsR8LevxWfSJALr9gxiBzLy0jD.pdf','thumbnails/17.jpg','Featured',2019,NULL,NULL,6,NULL,'2025-11-14 17:51:04','2025-11-16 00:29:44','Discrete Mathematics has permeated the whole of mathematics so much so it has now come to be taught even at the high school level. This book presents the basics of Discrete Mathematics and its applications to day-to-day problems in several areas. This book is intended for undergraduate students of Computer Science, Mathematics and Engineering. A number of examples have been given to enhance the understanding of concepts. The programming languages used are Pascal and C.','Available','approved',5),(18,'The Illustrated Network_ How TCP_IP Works in a Modern Network ( PDFDrive )','resources/gOqGzIfbDfeNI973klWJZmt1kUK7NcEn6XG83HKm.pdf','thumbnails/18.jpg','Featured',2017,NULL,NULL,6,NULL,'2025-11-14 20:55:40','2025-11-23 02:12:36','In 1994, W. Richard Stevens and Addison-Wesley published a networking classic: TCP/IP Illustrated. The model for that book was a brilliant, unfettered approach to networking concepts that has proven itself over time to be popular with readers of beginning to intermediate networking knowledge.','Available','approved',10),(19,'Data Structures and Algorithms Made Easy_ Data Structures and Algorithmic Puzzles ( PDFDrive )','resources/cZXzWXbjKS4EBYFXQgX5wzLNQRSskAmy1iVSEX4J.pdf','thumbnails/19.jpg','Featured',2021,NULL,NULL,6,NULL,'2025-11-14 22:46:25','2025-11-21 16:35:14','Data Structures and Algorithms Made Easy is a comprehensive guide that simplifies the complex topics of data structures and algorithms. It provides clear explanations and practical examples to help readers master these fundamental concepts.','Available','approved',11),(20,'Big-Data Analytics for Cloud, IoT and Cognitive Computing','resources/y9f3HKn4WJCQFtWSBJbME6qMVDxpx5Mhiz7XzG3e.pdf','thumbnails/20.jpg','Featured',2017,3,NULL,6,NULL,'2025-11-17 09:50:25','2025-11-23 05:04:33','The main goal of this book is to spur the development of effective big-data computing operations on smart clouds that are fully supported by IoT sensing, machine learning and analytics systems. To that end, the authors draw upon their original research and proven track record in the field to describe a practical approach integrating big-data theories, cloud design principles, Internet of Things (IoT) sensing, machine learning, data analytics and Hadoop and Spark programming.','Available','approved',14),(21,'Models for Life_ An Introduction to Discrete Mathematical Modeling with Microsoft Office Excel Set ( PDFDrive )','resources/alK7dVuDzxM7UgKKZg5D2GWzKE3CAiS6cJUj3f6r.pdf','thumbnails/21.jpg','Featured',2016,1,NULL,6,NULL,'2025-11-18 04:25:20','2025-11-23 02:16:12','With a focus on mathematical models based on real and current data, Models for Life: An Introduction to Discrete Mathematical Modeling with Microsoft® Office Excel® guides readers in the solution of relevant, practical problems by introducing both mathematical and Excel techniques.\r\n\r\nThe book begins with a step-by-step introduction to discrete dynamical systems, which are mathematical models that describe how a quantity changes from one point in time to the next. Readers are taken through the process, language, and notation required for the construction of such models as well as their implementation in Excel. The book examines single-compartment models in contexts such as population growth, personal finance, and body weight and provides an introduction to more advanced, multi-compartment models via applications in many areas, including military combat, infectious disease epidemics, and ranking methods.','Available','approved',22),(22,'RIZAL REVIEWER','resources/vL3KfA9u9rHAQKwqiXjaVMsOKO1niFEXEaIv6ikk.pdf','thumbnails/22.jpg','Community Uploads',2025,11,23,27,27,'2025-11-23 05:08:11','2025-11-24 05:13:57','Review kau jan','Available','approved',22),(23,'SUS upload','resources/KW13iZPTS6xiObM20rz9aCA7YM8MwsYfvTo2xQX4.pdf','thumbnails/23.jpg','Community Uploads',2025,11,24,32,32,'2025-11-23 11:12:09','2025-11-23 11:39:36','May cheating answers hehe','Available','approved',18);
/*!40000 ALTER TABLE `resources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('LVNGnou0ERIluf7YjnPjMKv3gwjXeL3QYQdIxZHu',NULL,'192.168.1.129','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMVAyR041SXhOVDZZalJBazJJSFRMQ2p4YjI3SHFRZTltYnpGeE1YaCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xOTIuMTY4LjEuMTI5OjgwMDAvc2lnbmluIjt9fQ==',1763995145),('nXgjk42k0BK1TT1kEALSPmezeeb0RqCV0nD4LOaG',27,'192.168.1.129','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiM0ZMWHNTY2VHVHlVaEhadnQxS1NsNmZUY1pkUUdkYVE3VGs3MFlnMSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovLzE5Mi4xNjguMS4xMjk6ODAwMC9ob21lVXNlciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwOi8vMTkyLjE2OC4xLjEyOTo4MDAwL3NpZ25pbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI3O30=',1763995184);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student` (
  `UID` bigint(20) unsigned NOT NULL,
  `First_Name` varchar(255) NOT NULL,
  `Last_Name` varchar(255) NOT NULL,
  `Student_ID` varchar(255) DEFAULT NULL,
  `course_id` bigint(20) unsigned DEFAULT NULL,
  `student_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`UID`),
  KEY `student_course_id_foreign` (`course_id`),
  CONSTRAINT `student_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `student_uid_foreign` FOREIGN KEY (`UID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES (7,'Bembem','B',NULL,3,'23-001','2025-11-04 08:27:26','2025-11-23 06:56:32'),(26,'Rich','Benitz',NULL,1,'23-002','2025-11-04 08:26:57','2025-11-23 06:56:32'),(27,'Angela','Sapaula',NULL,1,'23-003','2025-11-04 08:26:30','2025-11-24 05:09:35'),(29,'Patotoy','Nakasandal',NULL,3,'23-004','2025-11-16 00:29:33','2025-11-23 06:56:32'),(30,'Nexus','Ignacio',NULL,2,'23-005','2025-11-16 19:12:47','2025-11-23 06:56:32'),(31,'Rich','Ben',NULL,1,'23-157','2025-11-23 07:57:21','2025-11-23 07:57:21'),(32,'Shady','Mf',NULL,3,'23-069','2025-11-23 11:07:09','2025-11-23 11:07:09');
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'it-223','2025-11-10 09:28:51','2025-11-10 09:28:51'),(2,'research','2025-11-10 09:28:51','2025-11-10 09:28:51'),(3,'javascript','2025-11-10 09:42:05','2025-11-10 09:42:05'),(4,'dsa','2025-11-10 09:42:05','2025-11-10 09:42:05'),(5,'it-211','2025-11-10 09:42:05','2025-11-10 09:42:05'),(6,'a','2025-11-10 10:02:04','2025-11-10 10:02:04'),(7,'discrete-math','2025-11-22 09:02:13','2025-11-22 09:02:13'),(8,'data-analysis','2025-11-22 09:08:38','2025-11-22 09:08:38'),(9,'iot','2025-11-22 09:08:38','2025-11-22 09:08:38'),(10,'cognitive-computing','2025-11-22 09:08:38','2025-11-22 09:08:38'),(11,'tcp-ip','2025-11-23 06:01:17','2025-11-23 06:01:17'),(12,'ip','2025-11-23 06:01:17','2025-11-23 06:01:17'),(13,'networking','2025-11-23 06:01:17','2025-11-23 06:01:17'),(14,'rizal','2025-11-23 06:02:05','2025-11-23 06:02:05'),(15,'reviewer','2025-11-23 06:02:05','2025-11-23 06:02:05'),(16,'answers','2025-11-23 11:12:10','2025-11-23 11:12:10');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Campus_ID` bigint(20) unsigned DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `is_online` tinyint(1) NOT NULL DEFAULT 0,
  `profile_picture` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'student',
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `is_banned` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_campus_id_foreign` (`Campus_ID`),
  CONSTRAINT `users_campus_id_foreign` FOREIGN KEY (`Campus_ID`) REFERENCES `campus` (`Campus_ID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (4,2,'isustudygo@gmail.com','2025-11-01 13:36:58','2025-11-24 05:08:39',0,'profile-pictures/dVppGJFwQzWBwqyx5phHaNiSR2FKoPPYEE0x6Jyv.png','$2y$12$S.Lyrb5COekjTv.MBw3kquOr.EpcigH9Lbo4AJ4E.B2QhLGnN8aJK',NULL,'2025-10-16 10:01:15','2025-11-24 05:08:47','admin',1,0),(6,2,'richellebenitez03@gmail.com','2025-11-04 22:06:53','2025-11-24 05:26:08',0,'profile-pictures/DmHz6fdGWxBsA1CmKohwAQpbgOh5hATqTkUfSXLB.png','$2y$12$6zrnHQ3FNJmZHRDmSZlc2euyx79ZEGfw6EV6ZiVdRbP18pRvM04mS',NULL,'2025-10-27 04:52:29','2025-11-24 05:26:42','librarian',1,0),(7,2,'bembemychine@gmail.com','2025-11-04 22:08:02',NULL,0,NULL,'$2y$12$SD7.5jsvgUZ6ZEAjs/bEseDX4Tskw406Ipru41dEjB4YGmZKteN.G',NULL,'2025-10-27 05:05:48','2025-10-27 05:05:48','student',1,0),(21,NULL,'hynrszm@gmail.com','2025-11-01 08:48:37','2025-11-23 23:26:19',0,'profile-pictures/lPvpxZrD1KJTVqRDcYvgWBYd4RRMeTAosXRPqbTH.jpg','$2y$12$fZzA6VjSVsHHjdg0L3MC7ubBDWLMdi./6RUEhvFOzcFSxdILHSy.i',NULL,'2025-11-01 08:47:56','2025-11-24 01:02:27','librarian',1,0),(25,2,'patotoybemchin@gmail.com','2025-11-01 09:21:32','2025-11-23 20:43:10',0,'profile-pictures/3SN8WpS48P1YmFPIQdbN9hwUnUI7KRUDk6ugbWc3.png','$2y$12$u6iXNepCX5njFKPUUOrqXuiu6hyRSM7EtVMfqYcfhObDdjual0IHK',NULL,'2025-11-01 09:21:32','2025-11-23 20:46:51','faculty',1,0),(26,2,'benitez_richelledorothy@plpasig.edu.ph','2025-11-01 11:53:00',NULL,0,NULL,'$2y$12$n1Q2gbJVUoSzLwB/xKYuZeoxkaZbm04BXUkUs3NeMblVX0Lo6diN6',NULL,'2025-11-01 11:53:00','2025-11-04 12:01:53','admin',1,0),(27,2,'angelasapaula@gmail.com','2025-11-01 17:41:48','2025-11-24 06:39:24',1,'profile-pictures/RE6M1MwNqtZAAZorcADprO7v9jD9ulllPnxWUw6L.jpg','$2y$12$ZZ3aLJXfbmVUg3FJjJqIUOTCkERyLU//sag6O2Fjloiyc05VeElEC',NULL,'2025-11-01 17:41:48','2025-11-24 06:39:24','student',1,0),(28,NULL,'mlepnossilentclay@gmail.com','2025-11-01 17:53:49',NULL,0,NULL,'$2y$12$5Yk2xPp.KqMdOEvM/dn54ONKAZKP5J2mhFDsvMmBu6yhdSl/HsSFa',NULL,'2025-11-01 17:53:49','2025-11-06 05:01:32','faculty',1,0),(29,2,'aukokayvivian@gmail.com','2025-11-16 00:29:33','2025-11-23 11:03:28',0,NULL,'$2y$12$FWsjzscCRXlQtdopxPCEU.2AKppk/3Jw1eRFeGej5hyt62Y9mH126',NULL,'2025-11-16 00:29:33','2025-11-23 11:04:08','student',1,0),(30,2,'nexusignacio24@gmail.com','2025-11-16 19:12:47',NULL,0,NULL,'$2y$12$U/zZJtFZZC0KeT/G9jBfSusxVRgZu55ClXxyvfLgHlUhWbFQq2H7e',NULL,'2025-11-16 19:12:47','2025-11-16 19:12:47','student',1,0),(31,2,'benitezrivera22@gmail.com','2025-11-23 07:57:21','2025-11-24 05:39:06',1,'profile-pictures/Ifoet80umDq4Twd7HrbQuFWKLSc7kgs2Lwg1mKvQ.jpg','$2y$12$iNRNTV4YBBDU5d6nsjmbvug2oELjY8v4vnckbEzFoc0Nqmay137bO',NULL,'2025-11-23 07:57:21','2025-11-24 05:39:06','student',1,0),(32,2,'benitezpurp@gmail.com','2025-11-23 11:07:09','2025-11-23 11:14:00',0,NULL,'$2y$12$ld4b7hRytfh7qerMSoc.pu34SuHm/qXa6DlG1y97TDdu9f9G6zOsq',NULL,'2025-11-23 11:07:09','2025-11-23 11:40:27','student',1,1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verify_codes`
--

DROP TABLE IF EXISTS `verify_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `verify_codes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `code` varchar(6) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `verify_codes_user_id_code_index` (`user_id`,`code`),
  CONSTRAINT `verify_codes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verify_codes`
--

LOCK TABLES `verify_codes` WRITE;
/*!40000 ALTER TABLE `verify_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `verify_codes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-24 22:39:58
