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
INSERT INTO `admin` VALUES (4,'Me pinaka admin','shesh','2025-10-27 12:39:35','2025-11-04 12:01:32'),(26,'Rich','Benitz','2025-11-04 12:01:53','2025-11-04 12:01:53');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authors`
--

LOCK TABLES `authors` WRITE;
/*!40000 ALTER TABLE `authors` DISABLE KEYS */;
INSERT INTO `authors` VALUES (1,'tutorialspoint','2025-11-09 15:55:22','2025-11-09 15:55:22'),(2,'Rex Hogan','2025-11-09 15:55:22','2025-11-09 15:55:22'),(3,'Mike O’Leary','2025-11-09 15:55:22','2025-11-09 15:55:22'),(4,'Anthony De Barros','2025-11-09 15:55:22','2025-11-09 15:55:22'),(8,'Adeel Javed','2025-11-09 08:24:08','2025-11-09 08:24:08'),(9,'Sammie Bae','2025-11-10 01:35:23','2025-11-10 01:35:23'),(10,'John W. Creswell','2025-11-10 03:55:06','2025-11-10 03:55:06');
/*!40000 ALTER TABLE `authors` ENABLE KEYS */;
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
  `Approved_Date` date DEFAULT NULL,
  `Return_Date` date DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `borrower`
--

LOCK TABLES `borrower` WRITE;
/*!40000 ALTER TABLE `borrower` DISABLE KEYS */;
INSERT INTO `borrower` VALUES (1,27,1,NULL,NULL,NULL,0,'2025-11-09 11:27:41','2025-11-09 11:27:41');
/*!40000 ALTER TABLE `borrower` ENABLE KEYS */;
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
INSERT INTO `campus` VALUES (1,'Echague','2025-10-27 04:02:44','2025-10-27 04:02:44'),(2,'Santiago','2025-10-27 04:02:44','2025-10-27 04:02:44'),(5,'Cauayan','2025-11-01 09:58:12','2025-11-01 09:58:12'),(6,'Cabagan','2025-11-01 09:58:12','2025-11-01 09:58:12'),(7,'Ilagan','2025-11-01 09:58:12','2025-11-01 09:58:12'),(9,'Angadanan','2025-11-01 09:58:12','2025-11-01 09:58:12'),(10,'Roxas','2025-11-01 09:58:12','2025-11-01 09:58:12'),(11,'Jones','2025-11-01 09:58:12','2025-11-01 09:58:12'),(12,'Palanan','2025-11-01 09:58:12','2025-11-01 09:58:12'),(13,'San Mateo','2025-11-01 09:58:12','2025-11-01 09:58:12'),(14,'San Mariano','2025-11-01 09:58:12','2025-11-01 09:58:12');
/*!40000 ALTER TABLE `campus` ENABLE KEYS */;
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
INSERT INTO `librarian` VALUES (6,'Richelle Dorothy','Benitez','2025-10-27 04:52:29','2025-11-04 09:29:39',2),(21,'Batutay','Sapaula','2025-11-01 08:47:56','2025-11-04 11:56:48',3);
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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_10_16_174846_add_role_to_users_table',2),(5,'2025_10_25_082040_create_campus_table',3),(6,'2025_10_25_082342_update_users_table_add_campus_id',3),(7,'2025_10_25_082416_create_faculty_table',3),(8,'2025_10_25_082429_create_admin_table',3),(9,'2025_10_25_082448_create_resources_table',3),(10,'2025_10_25_082501_create_borrower_table',3),(11,'2025_10_27_111752_add_is_approved_to_users_table',4),(12,'2025_10_27_121707_add_unique_constraint_to_campus_name',5),(13,'2025_10_27_122913_create_librarian_table',6),(14,'2025_10_27_122958_remove_name_from_users_table',7),(17,'2025_10_27_124127_create_student_table',8),(19,'2025_11_01_134003_create_verify_codes_table',9),(21,'2025_11_04_154238_create_librarian_positions_table',10),(22,'2025_11_04_154239_add_position_id_to_librarian_table',10),(23,'2025_11_04_154240_add_status_author_to_resources_and_university_librarian_to_users_table',10),(24,'2025_11_04_160336_drop_is_university_librarian_from_users_table',11),(25,'2025_11_06_161015_alter_resources_table_description_column',12),(26,'2025_11_09_102908_add_publish_date_components_to_resources_table',13),(27,'2025_11_09_104822_drop_publish_date_from_resources_table',14),(28,'2025_11_09_141319_add_tracking_columns_to_resources_table',15),(29,'2025_11_09_141359_create_resource_views_table',15);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ratings`
--

LOCK TABLES `ratings` WRITE;
/*!40000 ALTER TABLE `ratings` DISABLE KEYS */;
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
INSERT INTO `resource_authors` VALUES (1,1,NULL,NULL),(2,2,NULL,NULL),(3,3,NULL,NULL),(4,4,NULL,NULL),(5,8,NULL,NULL),(7,9,NULL,NULL),(8,10,NULL,NULL);
/*!40000 ALTER TABLE `resource_authors` ENABLE KEYS */;
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
INSERT INTO `resource_tags` VALUES (7,3,NULL,NULL),(7,4,NULL,NULL),(7,5,NULL,NULL),(8,1,NULL,NULL),(8,2,NULL,NULL);
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `Description` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Available',
  `views` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Resource_ID`),
  KEY `resources_uploaded_by_foreign` (`Uploaded_By`),
  CONSTRAINT `resources_uploaded_by_foreign` FOREIGN KEY (`Uploaded_By`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resources`
--

LOCK TABLES `resources` WRITE;
/*!40000 ALTER TABLE `resources` DISABLE KEYS */;
INSERT INTO `resources` VALUES (1,'Excel PivotTables','resources/1tbJHWeT8zNs3xme4IlFFklFylS4DbsPNBCZj9bv.pdf','thumbnails/1.jpg','Featured',2016,NULL,NULL,6,'2025-11-06 07:09:17','2025-11-10 06:32:33','PivotTable is an extremely powerful tool that you can use to slice and dice data. In this tutorial, you will learn these PivotTable features in detail along with examples. By the time\r\nyou complete this tutorial, you will have sufficient knowledge on PivotTable features that can get you started with exploring, analyzing, and reporting data based on the requirements.','Unavailable',0),(2,'A Practical Guide to Database Design','resources/g6HrKyEKWh6qzzVBLea59tKFehM0PCR19iPa3m4l.pdf','thumbnails/2.jpg','Featured',2018,NULL,NULL,6,'2025-11-06 08:13:33','2025-11-10 10:30:07','This is a book intended for those who are involved in the design or development of a database system or application. It begins by focusing on how to create a logical data model where data are stored where it belongs. Next, data usage is reviewed to transform the logical model into a physical data model that will satisfy user performance requirements. Finally, it describes how to use various software tools to create user interfaces to review and update data in a database.','Available',1),(3,'Cyber Operations: Building, Defending, and Attacking Modern Computer Networks','resources/4N8fCFP8ENhwcTRu6A88YJrZh2THp3rQ53seKPfg.pdf','thumbnails/3.jpg','Featured',2019,NULL,NULL,6,'2025-11-06 08:25:40','2025-11-10 00:53:47','Know how to set up, defend, and attack computer networks with this revised and expanded second edition.\r\n\r\nYou will learn to configure your network from the ground up, beginning with developing your own private virtual test environment, then setting up your own DNS server and AD infrastructure. You will continue with more advanced network services, web servers, and database servers and you will end by building your own web applications servers, including WordPress and Joomla!. Systems from 2011 through 2017 are covered, including Windows 7, Windows 8, Windows 10, Windows Server 2012, and Windows Server 2016 as well as a range of Linux distributions, including Ubuntu, CentOS, Mint, and OpenSUSE.','Available',0),(4,'Practical SQL_ A Beginner’s Guide to Storytelling with Data','resources/Gr7KfjB0zy6AFk2sl2uxB23RvdfV3ivpcgWNU7FM.pdf','thumbnails/4.jpg','Featured',2018,5,2,6,'2025-11-09 03:02:48','2025-11-10 10:30:03','Practical SQL is an approachable and fast-paced guide to SQL (Structured Query Language), the standard programming language for defining, organizing, and exploring data in relational databases. The book focuses on using SQL to find the story your data tells, with the popular open-source database PostgreSQL and the pgAdmin interface as its primary tools.\r\n\r\nYou’ll first cover the fundamentals of databases and the SQL language, then build skills by analyzing data from the U.S. Census and other federal and state government agencies. With exercises and real-world examples in each chapter, this book will teach even those who have never programmed before all the tools necessary to build powerful databases and access information quickly and efficiently.\r\n\r\nYou’ll learn how to:\r\n- Create databases and related tables using your own data\r\n- Define the right data types for your information\r\n- Aggregate, sort, and filter data to find patterns\r\n- Use basic math and advanced statistical functions\r\n- Identify errors in data and clean them up\r\n- Import and export data using delimited text files\r\n- Write queries for geographic information systems (GIS)\r\n- Create advanced queries and automate tasks\r\n\r\nLearning SQL doesn’t have to be dry and complicated. Practical SQL delivers clear examples with an easy-to-follow approach to teach you the tools you need to build and manage your own databases.\r\n\r\nThis book uses PostgreSQL, but the SQL syntax is applicable to many database applications, including Microsoft SQL Server and MySQL.','Available',3),(5,'Building Arduino Projects for the Internet of Things','resources/Xd3cPc3aI5PlGtf5lN44IQk2ToxhKFgRLVXEBiz7.pdf','thumbnails/5.jpg','Featured',2016,6,9,6,'2025-11-09 08:24:08','2025-11-10 00:53:48','Gain a strong foundation of Arduino-based device development, from which you can go in any direction according to your specific development needs and desires. You\'ll build Arduino-powered devices for everyday use, and then connect those devices to the Internet.\r\n\r\nYou\'ll be introduced to the building blocks of IoT, and then deploy those principles to by building a variety of useful projects. Projects in the books gradually introduce the reader to key topics such as internet connectivity with Arduino, common IoT protocols, custom web visualization, and Android apps that receive sensor data on-demand and in realtime. IoT device enthusiasts of all ages will want this book by their side when developing Android-based devices.\r\n\r\nIf you\'re one of the many who have decided to build your own Arduino-powered devices for IoT applications, then Building Arduino Projects for the Internet of Things is exactly what you need. This book is your singleresource--a guidebook for the eager-to-learn Arduino enthusiast--that teaches logically, methodically, and practically how the Arduino works and what you can build with it.','Available',1),(7,'JavaScript Data Structures and Algorithms_ An Introduction to Understanding and Implementing Core Data Structure and Algorithm Fundamentals','resources/n2AE03FQgGwIoPRxhPhleQKLr1toj21euUak4MCM.pdf','thumbnails/7.jpg','Featured',2019,1,NULL,6,'2025-11-10 01:13:37','2025-11-10 10:02:53','Explore data structures and algorithm concepts and their relation to everyday JavaScript development. A basic understanding of these ideas is essential to any JavaScript developer wishing to analyze and build great software solutions.\r\n\r\nYou\'ll discover how to implement data structures such as hash tables, linked lists, stacks, queues, trees, and graphs. You\'ll also learn how a URL shortener, such as bit.ly, is developed and what is happening to the data as a PDF is uploaded to a webpage. This book covers the practical applications of data structures and algorithms to encryption, searching, sorting, and pattern matching.\r\n\r\nIt is crucial for JavaScript developers to understand how data structures work and how to design algorithms. This book and the accompanying code provide that essential foundation for doing so. With JavaScript Data Structures and Algorithms you canstart developing your knowledge and applying it to your JavaScript projects today.','Available',2),(8,'Research Design  Qualitative, Quantitative, and Mixed Methods Approaches','resources/E2YwZGXt3GoP3XIXHryh5pEX41cTnqnrTQ4opTEW.pdf','thumbnails/8.jpg','Featured',2014,NULL,NULL,6,'2025-11-10 03:55:04','2025-11-10 10:02:57','The book Research Design: Qualitative, Quantitative and Mixed Methods Approaches by Creswell (2014) covers three approaches-qualitative, quantitative and mixed methods. This educational book is informative and illustrative and is equally beneficial for students, teachers and researchers. Readers should have basic knowledge of research for better understanding of this book. There are two parts of the book. Part 1 (chapter 1-4) consists of steps for developing research proposal and part II (chapter 5-10) explains how to develop a research proposal or write a research report. A summary is given at the end of every chapter that helps the reader to recapitulate the ideas. Moreover, writing exercises and suggested readings at the end of every chapter are useful for the readers. Chapter 1 opens with-definition of research approaches and the author gives his opinion that selection of a research approach is based on the nature of the research problem, researchers\' experience and the audience of the study. The author defines qualitative, quantitative and mixed methods research. A distinction is made between quantitative and qualitative research approaches. The author believes that interest in qualitative research increased in the latter half of the 20th century. The worldviews, Fraenkel, Wallen and Hyun (2012) and Onwuegbuzie and Leech (2005) call them paradigms, have been explained. Sometimes, the use of language becomes too philosophical and technical. This is probably because the author had to explain some technical terms.','Available',4);
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
INSERT INTO `sessions` VALUES ('eYtFtHgNyyGKL0TCLPKTyOKf5LGXvHSvN6ZxCE8a',25,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','YTo4OntzOjY6Il90b2tlbiI7czo0MDoiV1p2d3N0TzV6dnNZQlV3Z2pIVjdNdXdSZzBCTFJvY0RSRXFlbkptSyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lVXNlciI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI1O3M6MTE6InZpZXdlZF83XzI1IjtiOjE7czoxMToidmlld2VkXzhfMjUiO2I6MTtzOjExOiJ2aWV3ZWRfNF8yNSI7YjoxO3M6MTE6InZpZXdlZF8yXzI1IjtiOjE7fQ==',1762799407),('YDXV1RXppTfjRVRoQcC1gv4vZ3q5cRTbB6VWgmY8',6,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNEJTcmhpT1puUWRXMXBCSG1WOHg4dUJJdnNManlhYmxLbThuMGFsQSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZXNvdXJjZS1tYW5hZ2VtZW50Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Njt9',1762799362);
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`UID`),
  CONSTRAINT `student_uid_foreign` FOREIGN KEY (`UID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES (7,'Bembem','B','2025-11-04 08:27:26','2025-11-04 08:27:26'),(26,'Rich','Benitz','2025-11-04 08:26:57','2025-11-04 10:56:18'),(27,'Anjila','sbaola','2025-11-04 08:26:30','2025-11-04 08:26:30');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'it-223','2025-11-10 09:28:51','2025-11-10 09:28:51'),(2,'research','2025-11-10 09:28:51','2025-11-10 09:28:51'),(3,'javascript','2025-11-10 09:42:05','2025-11-10 09:42:05'),(4,'dsa','2025-11-10 09:42:05','2025-11-10 09:42:05'),(5,'it-211','2025-11-10 09:42:05','2025-11-10 09:42:05'),(6,'a','2025-11-10 10:02:04','2025-11-10 10:02:04');
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
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'student',
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_campus_id_foreign` (`Campus_ID`),
  CONSTRAINT `users_campus_id_foreign` FOREIGN KEY (`Campus_ID`) REFERENCES `campus` (`Campus_ID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (4,2,'admin@example.com','2025-11-01 13:36:58','$2y$12$ZFWnI/Wm/yHnYKwkl3f7uOK9UTE.x04IY1ZVzH9tO3Z9gMOA2ZgQe',NULL,'2025-10-16 10:01:15','2025-11-01 05:07:00','admin',1),(6,2,'richellebenitez03@gmail.com','2025-11-04 22:06:53','$2y$12$6zrnHQ3FNJmZHRDmSZlc2euyx79ZEGfw6EV6ZiVdRbP18pRvM04mS',NULL,'2025-10-27 04:52:29','2025-10-27 05:55:55','librarian',1),(7,2,'bembemychine@gmail.com','2025-11-04 22:08:02','$2y$12$SD7.5jsvgUZ6ZEAjs/bEseDX4Tskw406Ipru41dEjB4YGmZKteN.G',NULL,'2025-10-27 05:05:48','2025-10-27 05:05:48','student',1),(21,1,'hynrszm@gmail.com','2025-11-01 08:48:37','$2y$12$fZzA6VjSVsHHjdg0L3MC7ubBDWLMdi./6RUEhvFOzcFSxdILHSy.i',NULL,'2025-11-01 08:47:56','2025-11-01 08:55:29','librarian',1),(25,2,'patotoybemchin@gmail.com','2025-11-01 09:21:32','$2y$12$u6iXNepCX5njFKPUUOrqXuiu6hyRSM7EtVMfqYcfhObDdjual0IHK',NULL,'2025-11-01 09:21:32','2025-11-01 09:21:43','faculty',1),(26,2,'benitez_richelledorothy@plpasig.edu.ph','2025-11-01 11:53:00','$2y$12$n1Q2gbJVUoSzLwB/xKYuZeoxkaZbm04BXUkUs3NeMblVX0Lo6diN6',NULL,'2025-11-01 11:53:00','2025-11-04 12:01:53','admin',1),(27,2,'angelasapaula@gmail.com','2025-11-01 17:41:48','$2y$12$ZZ3aLJXfbmVUg3FJjJqIUOTCkERyLU//sag6O2Fjloiyc05VeElEC',NULL,'2025-11-01 17:41:48','2025-11-01 17:41:48','student',1),(28,1,'mlepnossilentclay@gmail.com','2025-11-01 17:53:49','$2y$12$5Yk2xPp.KqMdOEvM/dn54ONKAZKP5J2mhFDsvMmBu6yhdSl/HsSFa',NULL,'2025-11-01 17:53:49','2025-11-06 05:01:32','faculty',1);
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

-- Dump completed on 2025-11-11  2:32:32
