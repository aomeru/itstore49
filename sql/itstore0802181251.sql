-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: itapp
-- ------------------------------------------------------
-- Server version	5.7.20-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `allocations`
--

DROP TABLE IF EXISTS `allocations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `allocations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `inventory_id` int(10) unsigned NOT NULL,
  `added_by` int(10) unsigned NOT NULL,
  `approval` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `allocations_inventory_id_unique` (`inventory_id`),
  KEY `allocations_user_id_foreign` (`user_id`),
  KEY `allocations_added_by_foreign` (`added_by`),
  CONSTRAINT `allocations_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`),
  CONSTRAINT `allocations_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`),
  CONSTRAINT `allocations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=666 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `allocations`
--

LOCK TABLES `allocations` WRITE;
/*!40000 ALTER TABLE `allocations` DISABLE KEYS */;
INSERT INTO `allocations` VALUES (654,56986,1,57484,NULL,'2017-12-18 13:33:21','2017-12-18 13:33:21'),(658,56986,2,57484,NULL,'2017-12-19 09:10:42','2017-12-19 09:10:42'),(659,57532,5,57484,NULL,'2017-12-19 09:22:26','2017-12-19 09:22:26'),(660,57492,4,57484,NULL,'2017-12-19 09:22:56','2017-12-19 09:22:56'),(661,57349,7,57484,NULL,'2017-12-19 11:09:36','2017-12-19 11:09:36'),(662,57505,8,57484,NULL,'2017-12-19 11:15:57','2017-12-19 11:15:57'),(663,57644,9,57484,NULL,'2018-01-10 16:40:02','2018-01-10 16:40:02'),(664,57433,10,57484,NULL,'2018-01-10 16:40:49','2018-01-10 16:40:49'),(665,57353,6,57484,NULL,'2018-01-15 16:33:21','2018-01-15 16:33:21');
/*!40000 ALTER TABLE `allocations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `task_id` int(10) unsigned NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','reassigned','escalated','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `autogen` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_user_id_foreign` (`user_id`),
  KEY `comments_task_id_foreign` (`task_id`),
  CONSTRAINT `comments_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`),
  CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45694 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_title_unique` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=64600 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (64589,'Strategy & Planning','2017-11-28 14:15:03','2017-11-28 14:15:03'),(64590,'Corporate Affairs','2017-11-28 14:15:03','2017-12-05 16:56:29'),(64591,'HSSEQ','2017-11-28 14:15:03','2017-11-28 14:15:03'),(64592,'Corporate Services','2017-11-28 14:15:03','2017-12-05 16:47:13'),(64593,'Commercial','2017-11-28 14:15:03','2017-11-28 14:15:03'),(64594,'Technical','2017-11-28 14:15:03','2017-11-28 14:15:03'),(64595,'Operations','2017-11-28 14:15:03','2017-11-28 14:15:03'),(64596,'Joint Venture','2017-11-28 14:15:03','2017-11-28 14:15:03'),(64597,'Legal','2017-11-28 14:15:03','2017-11-28 14:15:03'),(64598,'Finance','2017-11-28 14:15:03','2017-11-28 14:15:03'),(64599,'Other','2017-12-11 15:38:19','2017-12-11 15:38:19');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ilogs`
--

DROP TABLE IF EXISTS `ilogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ilogs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `inventory_id` int(10) unsigned NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ilogs_user_id_foreign` (`user_id`),
  KEY `ilogs_inventory_id_foreign` (`inventory_id`),
  CONSTRAINT `ilogs_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`),
  CONSTRAINT `ilogs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ilogs`
--

LOCK TABLES `ilogs` WRITE;
/*!40000 ALTER TABLE `ilogs` DISABLE KEYS */;
/*!40000 ALTER TABLE `ilogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventories`
--

DROP TABLE IF EXISTS `inventories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `purchase_id` int(10) unsigned DEFAULT NULL,
  `serial_no` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventories_serial_no_unique` (`serial_no`),
  KEY `inventories_user_id_foreign` (`user_id`),
  KEY `inventories_item_id_foreign` (`item_id`),
  KEY `inventories_purchase_id_foreign` (`purchase_id`),
  CONSTRAINT `inventories_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `inventories_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`),
  CONSTRAINT `inventories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventories`
--

LOCK TABLES `inventories` WRITE;
/*!40000 ALTER TABLE `inventories` DISABLE KEYS */;
INSERT INTO `inventories` VALUES (1,56986,2548,NULL,'7CH710KQSR','2017-12-13 15:25:18','2017-12-13 15:51:47'),(2,56986,2545,NULL,'3CM733096K','2017-12-13 15:53:17','2018-02-06 12:53:31'),(4,57484,2549,NULL,'5CG7350C7Q','2017-12-19 09:12:32','2017-12-19 09:12:32'),(5,57484,2549,NULL,'5CG7350C5F','2017-12-19 09:14:51','2017-12-19 09:14:51'),(6,57484,2550,NULL,'VNB8K3L94W','2017-12-19 09:47:32','2017-12-19 09:47:32'),(7,57484,2546,NULL,'5CD7230GM9','2017-12-19 11:09:23','2017-12-19 11:09:23'),(8,57484,2546,NULL,'5CD7230GND','2017-12-19 11:15:36','2017-12-19 11:15:36'),(9,57484,2567,NULL,'5CD7383LQ7','2018-01-10 16:38:42','2018-01-10 16:38:42'),(10,57484,2549,NULL,'5CG7350C71','2018-01-10 16:40:34','2018-01-10 16:40:34');
/*!40000 ALTER TABLE `inventories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `processor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descrip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `items_title_unique` (`title`),
  KEY `items_user_id_foreign` (`user_id`),
  CONSTRAINT `items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2569 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (2545,56986,'27es Display Screen','Monitor','','','2017-12-07 15:03:51','2017-12-07 15:03:51'),(2546,56986,'HP Probook 440 G3 i3','Laptop','Core i3','HP Probook 440 G3 core i3','2017-12-07 15:18:58','2017-12-07 20:31:48'),(2547,56986,'HP Probook 440 G3 i5','Laptop','Core i5','HP Probook 440 G3 core i5','2017-12-07 15:19:49','2017-12-07 15:19:49'),(2548,56986,'HP Wireless Classic Keyboard & Mouse','Wireless Keyboard & Mouse','','Wireless Keyboard & Mouse','2017-12-11 09:52:11','2017-12-20 07:20:14'),(2549,57484,'HP Probook 640 G3 i5','Laptop','Core i5','HP Probook 640 G3 i5','2017-12-19 09:11:37','2017-12-19 09:11:37'),(2550,57484,'Color Laser Jet Pro MFP M477w','Printer','None','Laser Jet Printer','2017-12-19 09:45:38','2017-12-20 07:15:02'),(2551,57484,'Mobile Work Station','Workstation','','Mobile work station','2017-12-20 07:15:47','2017-12-20 07:15:47'),(2552,57484,'Mobile Work Station Bag','Accessories','','Mobile work station bag','2017-12-20 07:16:11','2017-12-20 07:16:11'),(2553,57484,'Hp Ultra Slim Docking Station','Docking Station','','Hp ultra slim docking station','2017-12-20 07:16:31','2017-12-20 07:16:31'),(2554,57484,'HP Z27n Monitor Display','Monitor','','Hp Z27n Monitor Display','2017-12-20 07:17:03','2017-12-20 07:17:13'),(2555,57484,'Kensington Laptop Lock','Accessories','','','2017-12-20 07:17:25','2017-12-20 07:17:25'),(2556,57484,'HP Wireless Keyboard','Keyboard','','Hp wireless keyboard','2017-12-20 07:18:05','2017-12-20 07:18:05'),(2557,57484,'HP Probook 640 G3 I7','Laptop','Core i7','HP Probook 640 G3 I7','2017-12-20 07:18:34','2017-12-20 07:21:28'),(2559,57484,'HP Z Book 14 GA','Laptop','Core I7','HP Z book 14 GA','2017-12-20 07:22:50','2017-12-20 07:22:50'),(2560,57484,'HP Laserjet Enterprise 700 Colour','Printer','','Hp laserjet enterprise 700 colour printer','2017-12-20 07:23:46','2017-12-20 07:23:46'),(2561,57484,'Laptop Lock','Accessories','','Laptop lock','2017-12-20 07:24:01','2017-12-20 07:24:01'),(2562,57484,'HP Plotter Paper','Accessories','','Hp plotter paper','2017-12-20 07:24:41','2017-12-20 07:24:41'),(2563,57484,'Laptop Bag','Accessories','','Laptop bag','2017-12-20 07:24:58','2017-12-20 07:24:58'),(2564,57484,'HP Dual Notebook Stand','Stand','','HP dual notebook stand','2017-12-20 07:25:41','2017-12-20 07:25:41'),(2565,57484,'Docking Station Complete','Docking Station','','Docking station complete','2017-12-20 07:26:16','2017-12-20 07:26:16'),(2566,57484,'Docking Station Accessories','Accessories','','Docking station accessories','2017-12-20 07:26:39','2017-12-20 07:26:39'),(2567,57484,'HP Probook 440 G4 I5','Laptop','Core I5','HP Probook 440 G4 i5','2018-01-10 16:36:43','2018-01-10 16:36:43'),(2568,57484,'HP Spectre','Laptop','Core I5','HP Spectre','2018-01-15 16:36:48','2018-01-15 16:36:48');
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `page_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrip` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `logs_user_id_foreign` (`user_id`),
  CONSTRAINT `logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1677 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2017_03_12_103734_create_roles_table',1),(2,'2017_03_13_175311_create_pages_table',1),(3,'2017_11_27_204433_create_departments_table',1),(4,'2017_03_13_175320_create_permissions_table',2),(5,'2017_11_27_204444_create_units_table',2),(6,'2014_10_12_000000_create_users_table',3),(7,'2017_11_28_145335_create_logs_table',4),(8,'2017_12_07_081323_create_items_table',5),(9,'2017_12_07_082100_create_inventories_table',6),(10,'2017_12_07_093648_updateAuto',7),(11,'2017_12_13_161537_create_allocations_table',8),(12,'2017_12_18_105653_modify_allocation',9),(13,'2017_12_18_125955_modify_allocation_02',10),(14,'2018_01_03_122555_create_tasks_table',11),(15,'2018_01_03_122925_create_comments_table',11),(16,'2018_01_03_125336_updateComment',12),(17,'2018_01_03_161110_updateTask',13),(18,'2018_01_08_154235_updatecom',14),(19,'2018_01_18_100325_create_purchases_table',15),(20,'2018_01_18_100414_create_plogs_table',15),(21,'2018_01_18_100423_create_ilogs_table',15),(22,'2018_01_19_101228_updateTables',16),(23,'2018_01_19_151838_updateTables01',17),(24,'2018_01_23_120011_updateAutoInc01',18),(25,'2018_01_23_124137_UpdatePurchase',19);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` int(11) NOT NULL DEFAULT '0',
  `slug` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('page','subpage') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'page',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=524 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (512,'Pages & Permissions',0,'pages-and-permissions','files-o','page','2017-03-15 09:14:45','2017-03-15 09:33:09'),(513,'Permissions',512,'permissions','lock','subpage','2017-03-15 09:25:33','2017-12-01 07:25:10'),(514,'Pages',512,'pages','folder-open','subpage','2017-03-15 09:33:47','2017-12-01 07:24:54'),(515,'Users',0,'users','users','page','2017-03-15 11:30:42','2017-03-15 11:30:42'),(516,'Departments & Units',0,'departments-and-units','th-large','page','2017-12-01 19:29:00','2017-12-01 19:29:00'),(517,'Items',519,'items','sitemap','subpage','2017-12-01 19:31:44','2017-12-07 08:02:28'),(518,'Inventory',519,'inventory','th','subpage','2017-12-01 19:32:32','2017-12-07 08:02:13'),(519,'Inventory & Items',0,'inventory-and-items','square-o','page','2017-12-07 08:01:52','2017-12-07 08:01:52'),(520,'Allocation',0,'allocation','check-circle','page','2017-12-13 16:32:30','2017-12-13 16:32:30'),(521,'Tasks',0,'tasks','tasks','page','2018-01-03 16:50:06','2018-01-04 10:05:49'),(522,'Logs',0,'logs','barcode','page','2018-01-10 14:25:36','2018-01-10 14:25:36'),(523,'Purchase Order',0,'purchase-order','usd','page','2018-01-22 17:06:51','2018-01-23 12:41:00');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `page_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permissions_role_id_foreign` (`role_id`),
  KEY `permissions_page_id_foreign` (`page_id`),
  CONSTRAINT `permissions_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6699 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (6658,1525,512,'2017-12-01 07:26:13','2017-12-01 07:26:13'),(6659,1525,513,'2017-12-01 07:26:13','2017-12-01 07:26:13'),(6660,1525,514,'2017-12-01 07:26:13','2017-12-01 07:26:13'),(6661,1525,515,'2017-12-01 07:26:13','2017-12-01 07:26:13'),(6662,1526,515,'2017-12-01 07:26:52','2017-12-01 07:26:52'),(6663,1527,515,'2017-12-01 07:26:52','2017-12-01 07:26:52'),(6665,1525,516,'2017-12-01 19:29:00','2017-12-01 19:29:00'),(6666,1525,517,'2017-12-01 19:31:44','2017-12-01 19:31:44'),(6667,1525,518,'2017-12-01 19:32:33','2017-12-01 19:32:33'),(6668,1525,519,'2017-12-07 08:01:52','2017-12-07 08:01:52'),(6669,1528,516,'2017-12-12 15:12:03','2017-12-12 15:12:03'),(6670,1528,517,'2017-12-12 15:12:03','2017-12-12 15:12:03'),(6671,1528,518,'2017-12-12 15:12:03','2017-12-12 15:12:03'),(6672,1528,519,'2017-12-12 15:12:03','2017-12-12 15:12:03'),(6673,1526,516,'2017-12-12 15:12:36','2017-12-12 15:12:36'),(6674,1526,517,'2017-12-12 15:12:36','2017-12-12 15:12:36'),(6675,1526,518,'2017-12-12 15:12:37','2017-12-12 15:12:37'),(6676,1526,519,'2017-12-12 15:12:37','2017-12-12 15:12:37'),(6677,1525,520,'2017-12-13 16:32:30','2017-12-13 16:32:30'),(6678,1527,518,'2017-12-13 16:33:06','2017-12-13 16:33:06'),(6679,1527,519,'2017-12-13 16:33:06','2017-12-13 16:33:06'),(6680,1527,520,'2017-12-13 16:33:06','2017-12-13 16:33:06'),(6681,1526,520,'2017-12-13 16:36:18','2017-12-13 16:36:18'),(6682,1525,521,'2018-01-03 16:50:06','2018-01-03 16:50:06'),(6683,1526,521,'2018-01-04 15:04:15','2018-01-04 15:04:15'),(6684,1528,521,'2018-01-04 15:04:27','2018-01-04 15:04:27'),(6685,1527,521,'2018-01-04 15:04:44','2018-01-04 15:04:44'),(6686,1525,522,'2018-01-10 14:25:36','2018-01-10 14:25:36'),(6687,1527,516,'2018-01-19 13:40:42','2018-01-19 13:40:42'),(6688,1527,517,'2018-01-19 13:40:42','2018-01-19 13:40:42'),(6689,1528,515,'2018-01-19 13:40:42','2018-01-19 13:40:42'),(6690,1528,520,'2018-01-19 13:40:42','2018-01-19 13:40:42'),(6691,1532,515,'2018-01-19 13:40:42','2018-01-19 13:40:42'),(6692,1532,516,'2018-01-19 13:40:42','2018-01-19 13:40:42'),(6693,1532,517,'2018-01-19 13:40:42','2018-01-19 13:40:42'),(6694,1532,518,'2018-01-19 13:40:42','2018-01-19 13:40:42'),(6695,1532,519,'2018-01-19 13:40:42','2018-01-19 13:40:42'),(6696,1532,520,'2018-01-19 13:40:42','2018-01-19 13:40:42'),(6697,1532,521,'2018-01-19 13:40:42','2018-01-19 13:40:42'),(6698,1525,523,'2018-01-22 17:06:51','2018-01-22 17:06:51');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plogs`
--

DROP TABLE IF EXISTS `plogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plogs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `purchase_id` int(10) unsigned NOT NULL,
  `inventory_id` int(10) unsigned DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plogs_user_id_foreign` (`user_id`),
  KEY `plogs_purchase_id_foreign` (`purchase_id`),
  KEY `plogs_inventory_id_foreign` (`inventory_id`),
  CONSTRAINT `plogs_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`),
  CONSTRAINT `plogs_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`),
  CONSTRAINT `plogs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25563 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plogs`
--

LOCK TABLES `plogs` WRITE;
/*!40000 ALTER TABLE `plogs` DISABLE KEYS */;
/*!40000 ALTER TABLE `plogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchases` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `po` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inv` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `po_at` timestamp NULL DEFAULT NULL,
  `dn_at` timestamp NULL DEFAULT NULL,
  `inv_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchases_user_id_foreign` (`user_id`),
  CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68663 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchases`
--

LOCK TABLES `purchases` WRITE;
/*!40000 ALTER TABLE `purchases` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1533 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1525,'Developer','2017-11-28 14:09:57','2017-11-28 14:09:57'),(1526,'Administrator','2017-11-28 14:09:57','2017-11-28 14:09:57'),(1527,'Manager','2017-11-28 14:09:57','2017-11-28 14:09:57'),(1528,'Editor','2017-11-28 14:09:57','2017-11-28 14:09:57'),(1529,'Staff','2017-11-28 14:09:57','2017-11-28 14:09:57'),(1530,'Department','2017-12-10 23:00:00','2017-12-10 23:00:00'),(1531,'Organization','2017-12-10 23:00:00','2017-12-10 23:00:00'),(1532,'Supervisor','2017-12-10 23:00:00','2017-12-10 23:00:00');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `inventory_id` int(10) unsigned DEFAULT NULL,
  `assigned_by` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('opened','unresolved','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'opened',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `client_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tasks_user_id_foreign` (`user_id`),
  KEY `tasks_inventory_id_foreign` (`inventory_id`),
  KEY `tasks_assigned_by_foreign` (`assigned_by`),
  KEY `tasks_client_id_foreign` (`client_id`),
  CONSTRAINT `tasks_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`),
  CONSTRAINT `tasks_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`),
  CONSTRAINT `tasks_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`),
  CONSTRAINT `tasks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68661 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `units_title_unique` (`title`),
  KEY `units_department_id_foreign` (`department_id`),
  CONSTRAINT `units_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES (1,'IT',64592,'2017-12-06 12:11:53','2017-12-06 12:11:53'),(2,'Human Resources',64592,'2017-12-06 12:12:44','2017-12-06 12:12:44'),(4,'Community & State Government\'s Relations',64590,'2017-12-06 12:18:34','2017-12-06 20:13:19'),(5,'Drilling',64594,'2017-12-06 13:50:43','2017-12-06 13:50:43'),(6,'Other',64599,'2017-12-11 15:38:31','2017-12-11 15:38:31'),(7,'Organization',64599,'2017-12-11 15:52:22','2017-12-11 15:52:22'),(8,'Partner',64599,'2017-12-11 15:52:32','2017-12-11 15:52:32'),(9,'HSSEQ',64591,'2017-12-18 12:24:05','2017-12-18 12:24:05'),(10,'Joint Venture',64596,'2017-12-18 12:24:20','2017-12-18 12:24:20'),(11,'Commercial',64593,'2017-12-18 12:25:02','2017-12-18 12:25:02'),(12,'Finance',64598,'2017-12-18 12:25:18','2017-12-18 12:25:18'),(13,'Technical',64594,'2017-12-18 12:26:01','2017-12-18 12:26:01'),(14,'Corporate Services',64592,'2017-12-18 12:26:15','2017-12-18 12:26:15'),(15,'Corporate Affairs',64590,'2017-12-18 12:26:23','2017-12-18 12:26:23'),(16,'Legal',64597,'2017-12-18 12:26:40','2017-12-18 12:26:40'),(17,'Operations',64595,'2017-12-18 12:26:50','2017-12-18 12:26:50'),(18,'Strategy & Planning',64589,'2017-12-18 12:27:01','2017-12-18 12:27:01'),(19,'Admin',64592,'2017-12-18 12:31:34','2017-12-18 12:31:34'),(20,'Field',64595,'2017-12-19 15:53:29','2017-12-19 15:53:29'),(21,'Facilities',64595,'2017-12-19 15:53:41','2017-12-19 15:53:41'),(22,'Production',64595,'2017-12-19 15:54:02','2017-12-19 15:54:02'),(23,'Subsurface',64594,'2017-12-19 15:54:16','2017-12-19 15:54:16'),(24,'Corporate Engineering',64594,'2017-12-19 15:54:44','2017-12-19 15:56:42'),(25,'Governance, Risk & Assurance',64593,'2017-12-19 16:05:10','2017-12-19 16:05:10'),(26,'Contracts & Procurement',64593,'2017-12-19 16:05:28','2017-12-19 16:05:28'),(27,'Logistics',64593,'2017-12-19 16:05:41','2017-12-19 16:05:41'),(28,'Health & Safety',64591,'2017-12-19 16:06:00','2017-12-19 16:06:00'),(29,'Environment',64591,'2017-12-19 16:06:39','2017-12-19 16:06:39'),(30,'Security',64591,'2017-12-19 16:06:53','2017-12-19 16:06:53'),(31,'Corporate Communications & Public Relations',64590,'2018-01-09 09:27:38','2018-01-09 09:27:38'),(32,'Corporate Social Responsibility',64590,'2018-01-09 09:28:36','2018-01-09 09:28:36'),(33,'Brand Management',64590,'2018-01-09 09:29:12','2018-01-09 09:29:12');
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `unit_id` int(10) unsigned DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('male','female') COLLATE utf8mb4_unicode_ci NOT NULL,
  `staff_id` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('inactive','active','blocked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_staff_id_unique` (`staff_id`),
  KEY `users_role_id_foreign` (`role_id`),
  KEY `users_unit_id_foreign` (`unit_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `users_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57645 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (56986,1528,1,'aomeru@salvicpetroleum.com','aomeru','$2y$10$R9McLYa6XNfYZkF2ZYFcEeFibs8CJMpfXXpcjciwQL.8/UKG7E/MC','Akpoteheri','Omeru','male','G17090167','active','FcS6pBclRih5CIofrOlyCpolgxFQmu157snBo41AyUbNGo0eqZ5t2C38HxCZ','2017-11-28 15:19:17','2018-01-19 10:41:33'),(57313,1529,NULL,'ABCOrjiako@salvicpetroleum.com','abcorjiako','','ABC','Orjiako','male',NULL,'inactive',NULL,'2017-11-28 15:19:17','2018-01-19 10:42:30'),(57314,1529,NULL,'AOnyejakwuzi@salvicpetroleum.com','aonyejakwuzi','','Adamma','Onyejakwuzi','male',NULL,'inactive',NULL,'2017-11-28 15:19:17','2018-01-19 10:42:30'),(57315,1529,NULL,'AOgunsade@salvicpetroleum.com','aogunsade','','Adedolapo','Ogunsade','male',NULL,'inactive',NULL,'2017-11-28 15:19:17','2018-01-19 10:42:30'),(57316,1529,NULL,'ABamigboye@salvicpetroleum.com','abamigboye','','Adegbemile','Bamigboye','male',NULL,'inactive',NULL,'2017-11-28 15:19:17','2018-01-19 10:42:30'),(57317,1529,NULL,'AOyebanji-Umaigba@salvicpetroleum.com','aoyebanji-umaigba','','Adejoke','Oyebanji-Umaigba','male',NULL,'inactive',NULL,'2017-11-28 15:19:17','2018-01-19 10:42:30'),(57318,1529,NULL,'AAdepitan@salvicpetroleum.com','aadepitan','','Adekunle','Adepitan','male',NULL,'inactive',NULL,'2017-11-28 15:19:17','2018-01-19 10:42:30'),(57319,1529,NULL,'AAmusa@salvicpetroleum.com','aamusa','','Ademola','Amusa','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:30'),(57320,1529,NULL,'AOkebiorun@salvicpetroleum.com','aokebiorun','','Adesanya','Okebiorun','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:30'),(57321,1529,NULL,'ABadmus@salvicpetroleum.com','abadmus','','Adetola','Badmus','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:30'),(57322,1528,1,'AAlaba@salvicpetroleum.com','aalaba','','Adetunji','Alaba','male','17080147','inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:30'),(57323,1529,NULL,'AEzeoguine@salvicpetroleum.com','aezeoguine','','Akudo','Ezeoguine','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:30'),(57324,1529,NULL,'AMokolo@salvicpetroleum.com','amokolo','','Alex','Mokolo','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:30'),(57325,1529,NULL,'AEzurum@salvicpetroleum.com','aezurum','','Alexander','Ezurum','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:30'),(57326,1529,NULL,'AEke@salvicpetroleum.com','aeke','','Amaechi','Eke','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57327,1529,NULL,'AAmadiokoro@salvicpetroleum.com','aamadiokoro','','Amaka','Amadiokoro','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57328,1529,NULL,'ANwokeji@salvicpetroleum.com','anwokeji','','Amaka','Nwokeji','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57329,1529,NULL,'AOkudu@salvicpetroleum.com','aokudu','','Ambrose','Okudu','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57330,1529,NULL,'AUzoma@salvicpetroleum.com','auzoma','','Ambrose','Uzoma','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57331,1529,NULL,'AChukwuma@salvicpetroleum.com','achukwuma','','Amechi','Chukwuma','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57332,1529,NULL,'AUsman@salvicpetroleum.com','ausman','','Amina Cynthia','Usman','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57333,1529,NULL,'AOnokpise@salvicpetroleum.com','aonokpise','','Amos','Onokpise','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57334,1529,NULL,'AOrioma@salvicpetroleum.com','aorioma','','Andrew','Orioma','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57335,1529,NULL,'ANwachukwu@salvicpetroleum.com','anwachukwu','','Angel','Nwachukwu','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57336,1529,NULL,'ABassey@salvicpetroleum.com','abassey','','Anthony','Bassey','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57337,1529,NULL,'ABisong@salvicpetroleum.com','abisong','','Anthony','Bisong','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57338,1529,NULL,'AIduh@salvicpetroleum.com','aiduh','','Anthony Okwudili','Iduh','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57339,1529,NULL,'AUku@salvicpetroleum.com','auku','','Aselemi','Uku','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57340,1529,NULL,'ANyakno-Abasi@salvicpetroleum.com','anyakno-abasi','','Asuquo','Nyakno-Abasi','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57341,1529,NULL,'AAdumein@salvicpetroleum.com','aadumein','','Austine','Adumein','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57342,1529,NULL,'AMuhammad@salvicpetroleum.com','amuhammad','','Auwal','Muhammad','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57343,1529,NULL,'AShote@salvicpetroleum.com','ashote','','Ayobiyi','Shote','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57344,1529,NULL,'AAkinola@salvicpetroleum.com','aakinola','','Ayokunle','Akinola','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57345,1529,NULL,'ACookey@salvicpetroleum.com','acookey','','Azunna','Cookey','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57346,1529,NULL,'BOdumuyiwa@salvicpetroleum.com','bodumuyiwa','','Babafemi','Odumuyiwa','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57347,1529,NULL,'baml@salvicpetroleum.com','baml','','BAML','Gulfstream','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:41:33'),(57348,1529,NULL,'BOlugbile@salvicpetroleum.com','bolugbile','','Bankole','Olugbile','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57349,1529,9,'BChukwudulue@salvicpetroleum.com','bchukwudulue','','Barnabas','Chukwudulue','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57350,1529,NULL,'BEhighibe@salvicpetroleum.com','behighibe','','Becky','Ehighibe','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57351,1529,NULL,'BIbebuchi@salvicpetroleum.com','bibebuchi','','Ben','Ibebuchi','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57352,1529,NULL,'BOnyekaonwu@salvicpetroleum.com','bonyekaonwu','','Benedict','Onyekaonwu','male',NULL,'inactive',NULL,'2017-11-28 15:19:18','2018-01-19 10:42:31'),(57353,1529,NULL,'BOseji@salvicpetroleum.com','boseji','','Benson','Oseji','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:31'),(57354,1529,NULL,'BEbigbo@salvicpetroleum.com','bebigbo','','Bibian','Ebigbo','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:31'),(57355,1529,6,'bids@salvicpetroleum.com','bids','','bids','bids','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:41:33'),(57356,1529,NULL,'CUdeoha@salvicpetroleum.com','cudeoha','','Caleb','Udeoha','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:31'),(57357,1529,NULL,'CAkahomhen@salvicpetroleum.com','cakahomhen','','Celestine','Akahomhen','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:31'),(57358,1529,NULL,'CUgwu@salvicpetroleum.com','cugwu','','Celestine','Ugwu','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:31'),(57359,1529,NULL,'CLoader@salvicpetroleum.com','cloader','','Celine','Loader','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:31'),(57360,1529,NULL,'COparaocha@salvicpetroleum.com','coparaocha','','Charity','Oparaocha','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:31'),(57361,1529,NULL,'CEzeoke@salvicpetroleum.com','cezeoke','','Charles','Ezeoke','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:31'),(57362,1529,NULL,'CIbiok@salvicpetroleum.com','cibiok','','Charles','Ibiok','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:31'),(57363,1529,NULL,'CNdukwu@salvicpetroleum.com','cndukwu','','Charles','Ndukwu','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:31'),(57364,1529,NULL,'CAnukam@salvicpetroleum.com','canukam','','Chetachi','Anukam','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:31'),(57365,1529,NULL,'CAkukwe@salvicpetroleum.com','cakukwe','','Chibu','Akukwe','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:31'),(57366,1529,NULL,'CIbewuike@salvicpetroleum.com','cibewuike','','Chibueze','Ibewuike','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:31'),(57367,1529,NULL,'CNwanze@salvicpetroleum.com','cnwanze','','Chidera','Nwanze','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:31'),(57368,1529,NULL,'CChukwueke@salvicpetroleum.com','cchukwueke','','Chidi','Chukwueke','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:31'),(57369,1529,NULL,'COrazulike@salvicpetroleum.com','corazulike','','Chidi','Orazulike','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:31'),(57370,1529,NULL,'CWigwe@salvicpetroleum.com','cwigwe','','Chidi','Wigwe','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:31'),(57371,1529,NULL,'Cogu@salvicpetroleum.com','cogu','','Chigozie','Ogu','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:32'),(57372,1529,NULL,'CMaduakor@salvicpetroleum.com','cmaduakor','','Chijioke','Maduakor','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:32'),(57373,1529,NULL,'CAkah@salvicpetroleum.com','cakah','','Chimsom','Akah','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:32'),(57374,1529,NULL,'CObiagazie@salvicpetroleum.com','cobiagazie','','Chinaza','Obiagazie','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:32'),(57375,1529,NULL,'CUdeolisa@salvicpetroleum.com','cudeolisa','','Chinedu Godson','Udeolisa','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:32'),(57376,1529,NULL,'CNwajiobi@salvicpetroleum.com','cnwajiobi','','Chinedu','Nwajiobi','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:32'),(57377,1529,NULL,'CNwosu@salvicpetroleum.com','cnwosu','','Chinedu','Nwosu','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:32'),(57378,1529,NULL,'COdumodu@salvicpetroleum.com','codumodu','','Chinedu','Odumodu','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:32'),(57379,1529,NULL,'CAgbasiere@salvicpetroleum.com','cagbasiere','','Chinenye','Agbasiere','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:32'),(57380,1529,NULL,'CObi@salvicpetroleum.com','cobi','','Chinenye','Obi','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:32'),(57381,1529,NULL,'CObiefule@salvicpetroleum.com','cobiefule','','Chinenye','Obiefule','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:32'),(57382,1528,1,'CUkaigwe@salvicpetroleum.com','cukaigwe','','Chinenye','Ukaigwe','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:32'),(57383,1529,NULL,'COjimba@salvicpetroleum.com','cojimba','','Chinonso','Ojimba','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:32'),(57384,1529,NULL,'CAkpuru@salvicpetroleum.com','cakpuru','','Chinwe','Akpuru','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:32'),(57385,1529,NULL,'COwualah@salvicpetroleum.com','cowualah','','Chinyere','Owualah','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:32'),(57386,1529,NULL,'CIbekwe@salvicpetroleum.com','cibekwe','','Chioma','Ibekwe','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:32'),(57387,1529,NULL,'CNjoku@salvicpetroleum.com','cnjoku','','Chisom','Njoku','male',NULL,'inactive',NULL,'2017-11-28 15:19:19','2018-01-19 10:42:32'),(57388,1529,NULL,'CEbokam@salvicpetroleum.com','cebokam','','Chizoba','Ebokam','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57389,1529,NULL,'CIheobi@salvicpetroleum.com','ciheobi','','Christopher','Iheobi','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57390,1529,NULL,'COfoluwa@salvicpetroleum.com','cofoluwa','','Christopher','Ofoluwa','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57391,1529,NULL,'COkoye@salvicpetroleum.com','cokoye','','Christopher','Okoye','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57392,1529,NULL,'COnwuakpa@salvicpetroleum.com','conwuakpa','','Chukwuebuka','Onwuakpa','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57393,1529,NULL,'CIwunwah@salvicpetroleum.com','ciwunwah','','Chukwuemeka','Iwunwah','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57394,1529,NULL,'CUmechukwu@salvicpetroleum.com','cumechukwu','','Chukwuka','Umechukwu','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57395,1529,NULL,'CEmelisi@salvicpetroleum.com','cemelisi','','Chukwuma','Emelisi','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57396,1529,NULL,'CNwachukwu@salvicpetroleum.com','cnwachukwu','','Chukwuma Ikenna','Nwachukwu','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57397,1529,NULL,'CAmadi@salvicpetroleum.com','camadi','','Collins','Amadi','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57398,1529,NULL,'CIyalla@salvicpetroleum.com','ciyalla','','Collins','Iyalla','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57399,1529,14,'corporateservices@salvicpetroleum.com','corporateservices','','Corporate','Services','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:41:33'),(57400,1529,NULL,'DIruobe@salvicpetroleum.com','diruobe','','Dada','Iruobe','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57401,1529,NULL,'DUmukoro@salvicpetroleum.com','dumukoro','','Daniel','Umukoro','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57402,1529,NULL,'DFatimilehin@salvicpetroleum.com','dfatimilehin','','David','Fatimilehin','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57403,1529,NULL,'DIgbemo@salvicpetroleum.com','digbemo','','David','Igbemo','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57404,1529,NULL,'DOkenwa@salvicpetroleum.com','dokenwa','','David','Okenwa','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57405,1529,NULL,'DIkpa@salvicpetroleum.com','dikpa','','DEBORAH AZA-ERE','IKPA','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57406,1529,NULL,'DUmege@salvicpetroleum.com','dumege','','Declan','Umege','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57407,1529,NULL,'DFianka@salvicpetroleum.com','dfianka','','Diamond','Fianka','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57408,1529,NULL,'DOhwo@salvicpetroleum.com','dohwo','','Duke','Ohwo','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57409,1529,NULL,'DynamicsERP@salvicpetroleum.com','dynamicserp','','Dynamics','ERP','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57410,1529,NULL,'EUgboma@salvicpetroleum.com','eugboma','','Ebele','Ugboma','male',NULL,'inactive',NULL,'2017-11-28 15:19:20','2018-01-19 10:42:32'),(57411,1529,NULL,'EAjayi@salvicpetroleum.com','eajayi','','Ebenezer','Ajayi','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57412,1529,NULL,'EAgbasiere@salvicpetroleum.com','eagbasiere','','Ebuka','Agbasiere','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57413,1529,NULL,'EEzie@salvicpetroleum.com','eezie','','Edmund','Ezie','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57414,1529,NULL,'EOyewusi@salvicpetroleum.com','eoyewusi','','Efe','Oyewusi','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57415,1529,NULL,'EDomingo@salvicpetroleum.com','edomingo','','Efize','Domingo','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57416,1526,1,'ENta@salvicpetroleum.com','enta','$2y$10$hHQlGLbqlT75QQQHIAdxR.12CCoKf1HrGj7dRirD785HgdwNgctbm','Ekaete','Nta','female',NULL,'active',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57417,1529,NULL,'EUtake@salvicpetroleum.com','eutake','','Elfreda','Utake','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57418,1529,NULL,'EOko@salvicpetroleum.com','eoko','','Elizabeth','Oko','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57419,1529,NULL,'EOokerenta@salvicpetroleum.com','eookerenta','','Emenike','Onyegeme-Okerenta','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57420,1529,NULL,'EEjemai@salvicpetroleum.com','eejemai','','Emmanuel','Ejemai','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57421,1529,NULL,'EJaja@salvicpetroleum.com','ejaja','','Emmanuel','Jaja','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57422,1529,NULL,'EMbonu@salvicpetroleum.com','embonu','','Emmanuel','Mbonu','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57423,1529,NULL,'EUdofia@salvicpetroleum.com','eudofia','','Emmanuel','Udofia','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57424,1529,NULL,'EOvedje@salvicpetroleum.com','eovedje','','Emojevwe','Ovedje','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57425,1529,NULL,'EEbi@salvicpetroleum.com','eebi','','Ernest','Ebi','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57426,1529,NULL,'EObikwere@salvicpetroleum.com','eobikwere','','Ernest','Obikwere','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57427,1529,NULL,'EAnenih@salvicpetroleum.com','eanenih','','Esosa','Anenih','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57428,1530,NULL,'Ethics_Compliance@salvicpetroleum.com','ethics_compliance','','Ethics','Compliance','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57429,1529,NULL,'EAgagwo@salvicpetroleum.com','eagagwo','','Eugene','Agagwo','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57430,1529,NULL,'EOkoli@salvicpetroleum.com','eokoli','','Eugene','Okoli','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57431,1529,NULL,'EOkiemute@salvicpetroleum.com','eokiemute','','Evovo','Okiemute','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57432,1529,NULL,'EUzah@salvicpetroleum.com','euzah','','Ewere','Uzah','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57433,1529,31,'FJabai@salvicpetroleum.com','fjabai','','Faith','Jabai','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57434,1529,NULL,'FOtite@salvicpetroleum.com','fotite','','Favour','Otite','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57435,1529,NULL,'FIdehen@salvicpetroleum.com','fidehen','','Festus','Idehen','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57436,1529,NULL,'FOshodin@salvicpetroleum.com','foshodin','','Festus','Oshodin','male',NULL,'inactive',NULL,'2017-11-28 15:19:21','2018-01-19 10:42:32'),(57437,1529,NULL,'FOnichabor@salvicpetroleum.com','fonichabor','','Fidel','Onichabor','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57438,1529,NULL,'FFolly@salvicpetroleum.com','ffolly','','Francis','Folly','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57439,1529,NULL,'FObaremi@salvicpetroleum.com','fobaremi','','Francis','Obaremi','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57440,1529,NULL,'FObinatu@salvicpetroleum.com','fobinatu','','Francisca','Obinatu','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57441,1529,NULL,'FAzichoba@salvicpetroleum.com','fazichoba','','Frank','Azichoba','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57442,1529,NULL,'FOsifo@salvicpetroleum.com','fosifo','','Fred','Osifo','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57443,1529,NULL,'FEtim@salvicpetroleum.com','fetim','','Fredrick','Etim','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57444,1529,NULL,'FDoukade@salvicpetroleum.com','fdoukade','','Fufeyin','Doukade','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57445,1529,NULL,'FFufeyin@salvicpetroleum.com','ffufeyin','','Funkakpo','Fufeyin','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57446,1529,NULL,'GNyam@salvicpetroleum.com','gnyam','','Garba','Nyam','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57447,1529,NULL,'GEwhrudjakpo@salvicpetroleum.com','gewhrudjakpo','','Genetik','Ewhrudjakpo','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57448,1529,NULL,'Glencore@salvicpetroleum.com','glencore','','Glencore','Gulfstream','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57449,1529,NULL,'GNsofor@salvicpetroleum.com','gnsofor','','Gogo','Nsofor','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57450,1529,NULL,'GAdio@salvicpetroleum.com','gadio','','Goriola','Adio','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57451,1529,NULL,'GEnyinnaya@salvicpetroleum.com','genyinnaya','','Greg-Chido','Enyinnaya','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57452,1529,NULL,'GAmanze@salvicpetroleum.com','gamanze','','Greg','Amanze','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57453,1529,NULL,'HUsman@salvicpetroleum.com','husman','','Hashim','Usman','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57454,1529,NULL,'HOrjiako@salvicpetroleum.com','horjiako','','Henrietta','Orjiako','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57455,1529,NULL,'HMmeje@salvicpetroleum.com','hmmeje','','Henry','Mmeje','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57456,1529,NULL,'HOkee@salvicpetroleum.com','hokee','','Henry','Okee','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57457,1529,NULL,'HEOSL@salvicpetroleum.com','heosl','','HEOSL','','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57458,1529,NULL,'HOil@salvicpetroleum.com','hoil','','Heritage','Oil','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57459,1530,9,'HSSEQNotifications@salvicpetroleum.com','hsseqnotifications','','HSSEQ','Notification','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57460,1529,NULL,'HAnozie@salvicpetroleum.com','hanozie','','Humphrey','Anozie','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57461,1529,NULL,'IAjieh@salvicpetroleum.com','iajieh','','Ifeanyi','Ajieh','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57462,1529,NULL,'IAkonu@salvicpetroleum.com','iakonu','','Ifeanyi','Akonu','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57463,1529,NULL,'IAkpuru@salvicpetroleum.com','iakpuru','','Ifeanyi','Akpuru','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57464,1529,NULL,'IIkueze@salvicpetroleum.com','iikueze','','Ifeanyi','Ikueze','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57465,1529,NULL,'IOnwuneme@salvicpetroleum.com','ionwuneme','','Ifeanyi','Onwuneme','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57466,1529,NULL,'IUzoeto@salvicpetroleum.com','iuzoeto','','Ifeanyi','Uzoeto','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57467,1529,NULL,'IOdiokpu@salvicpetroleum.com','iodiokpu','','Ifeanyichukwu','Odiokpu','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57468,1529,NULL,'IOnyedi@salvicpetroleum.com','ionyedi','','Ifeyinwa','Onyedi','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57469,1529,NULL,'IEgbuna@salvicpetroleum.com','iegbuna','','Ifunanya','Egbuna','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57470,1529,NULL,'IEkwueme@salvicpetroleum.com','iekwueme','','Ifunanya','Ekwueme','male',NULL,'inactive',NULL,'2017-11-28 15:19:22','2018-01-19 10:42:32'),(57471,1529,NULL,'IUkah@salvicpetroleum.com','iukah','','Iheanyichi','Ukah','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57472,1529,NULL,'IOkonkwo-Eje@salvicpetroleum.com','iokonkwo-eje','','Ijeamaka','Okonkwo-Eje','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57473,1529,NULL,'IOnyeador@salvicpetroleum.com','ionyeador','','Ijeoma','Onyeador','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57474,1529,NULL,'IEgboh@salvicpetroleum.com','iegboh','','Ike','Egboh','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57475,1529,NULL,'IOkafor@salvicpetroleum.com','iokafor','','Ikemefuna','Okafor','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57476,1529,NULL,'INtomchukwu@salvicpetroleum.com','intomchukwu','','Ikenna','Ntomchukwu','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57477,1529,NULL,'IMmuoh@salvicpetroleum.com','immuoh','','Innocent','Mmuoh','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57478,1529,NULL,'IObi@salvicpetroleum.com','iobi','','Innocent','Obi','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57479,1529,NULL,'BInubiwon@salvicpetroleum.com','binubiwon','','Inubiwon','Blessing','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57480,1529,NULL,'PInyeke@salvicpetroleum.com','pinyeke','','Inyeke','Osborno Peter','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57481,1529,NULL,'ILucky@salvicpetroleum.com','ilucky','','Irivike','Lucky','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57482,1529,NULL,'IOlaleru@salvicpetroleum.com','iolaleru','','Israel','Olaleru','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57483,1528,1,'ITrequest@salvicpetroleum.com','itrequest','','IT','Request','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57484,1525,1,'itsupport@salvicpetroleum.com','itsupport','$2y$10$Anfrcsjk5pu4Ts1YwkhVq.lR2tG0RYcET.Vl8feex9Wh9HekaCBG2','IT','Support','male',NULL,'active','DmUfv8AYirb2YvFSfQCuRXSWAzQHTdcugE2HLIOewnlgndw6q4mUbCmWQBNY','2017-11-28 15:19:23','2018-01-19 10:41:33'),(57485,1529,NULL,'IWest@salvicpetroleum.com','iwest','','Ivan','West','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57486,1529,NULL,'JParker@salvicpetroleum.com','jparker','','Jason','Parker','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57487,1529,NULL,'JOharisi@salvicpetroleum.com','joharisi','','Jeremiah','Oharisi','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57488,1529,NULL,'JDObaseki@salvicpetroleum.com','jdobaseki','','Jerry','Dawnson-Obaseki','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57489,1529,NULL,'JAnyigbo@salvicpetroleum.com','janyigbo','','Joel','Anyigbo','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57490,1529,NULL,'JOnu@salvicpetroleum.com','jonu','','John Fredrick','Onu','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57491,1529,NULL,'JIkomi@salvicpetroleum.com','jikomi','','John','Ikomi','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57492,1528,1,'JIkwuka@salvicpetroleum.com','jikwuka','$2y$10$DvooAwsnHGZ7Arw1UDFRT.FXeqd6ui8ge3/rhWT7vMaiIBDwe9vnm','John','Ikwuka','male',NULL,'active','u8XN2Zk8KzZlpDNaIvJBwJ2tsWPPcw1p2NZOKFxv5nwVJZXeC5mEVD7Kc4Ur','2017-11-28 15:19:23','2018-01-19 10:42:32'),(57493,1529,NULL,'JOkoisama@salvicpetroleum.com','jokoisama','','Joseph','Okoisama','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57494,1529,NULL,'JIbeh@salvicpetroleum.com','jibeh','','Juliet','Ibeh','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57495,1529,NULL,'JUtomhin@salvicpetroleum.com','jutomhin','','Julius','Utomhin','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57496,1529,NULL,'JKanife@salvicpetroleum.com','jkanife','','Justin','Kanife','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57497,1529,NULL,'KKamanu@salvicpetroleum.com','kkamanu','','Kelechi','Kamanu','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57498,1529,NULL,'KIlobi@salvicpetroleum.com','kilobi','','Kelechi','Ilobi','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57499,1529,NULL,'KOnuoha@salvicpetroleum.com','konuoha','','Kelechi','Onuoha','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57500,1529,NULL,'KMadueke@salvicpetroleum.com','kmadueke','','Kennedy Obinna','Madueke','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57501,1529,NULL,'KOsuzoka@salvicpetroleum.com','kosuzoka','','Kennedy','Osuzoka','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57502,1529,NULL,'KOgwo@salvicpetroleum.com','kogwo','','Kenneth','Ogwo','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57503,1529,NULL,'KAnegimo@salvicpetroleum.com','kanegimo','','Kenneth','Anegimo','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57504,1529,NULL,'KEzeoke@salvicpetroleum.com','kezeoke','','Kenneth','Ezeoke','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57505,1529,9,'KOkolie@salvicpetroleum.com','kokolie','','Kenneth','Okolie','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57506,1529,NULL,'KOsuoha@salvicpetroleum.com','kosuoha','','Kenneth','Osuoha','male',NULL,'inactive',NULL,'2017-11-28 15:19:23','2018-01-19 10:42:32'),(57507,1529,NULL,'KEnegesi@salvicpetroleum.com','kenegesi','','Kingsley','Enegesi','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57508,1529,NULL,'KNkue-Leyira@salvicpetroleum.com','knkue-leyira','','Kirika','Nkue-Leyira','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57509,1529,NULL,'KAcholonu@salvicpetroleum.com','kacholonu','','Kodizie','Acholonu','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57510,1529,NULL,'KPoku-Amanfo@salvicpetroleum.com','kpoku-amanfo','','Kwabena','Poku-Amanfo','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57511,1529,NULL,'LFatayi-Williams@salvicpetroleum.com','lfatayi-williams','','Lauretta','Fatayi-Williams','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57512,1529,NULL,'LOvrawah@salvicpetroleum.com','lovrawah','','Leonard','Ovrawah','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57513,1529,NULL,'LEzidiegwu@salvicpetroleum.com','lezidiegwu','','Levi Chukwudi','Ezidiegwu','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57514,1529,NULL,'LOkorobasi@salvicpetroleum.com','lokorobasi','','Levi','Okorobasi','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57515,1529,NULL,'MEzeogu@salvicpetroleum.com','mezeogu','','Macaulay','Ezeogu','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57516,1529,NULL,'MOkolie@salvicpetroleum.com','mokolie','','Marcel','Okolie','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57517,1529,NULL,'MFatayiWilliams@salvicpetroleum.com','mfatayiwilliams','','Marie','FatayiWilliams','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57518,1529,NULL,'MediaComm@salvicpetroleum.com','mediacomm','','Media','Comm','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57519,1529,NULL,'MOkurude@salvicpetroleum.com','mokurude','','Michael','Okurude','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57520,1529,NULL,'MOparah@salvicpetroleum.com','moparah','','Miracle','Oparah','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57521,1529,NULL,'MHayatuden@salvicpetroleum.com','mhayatuden','','Mohamed','Hayatuden','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57522,1529,NULL,'MOyenekan@salvicpetroleum.com','moyenekan','','Mopelola','Oyenekan','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57523,1529,NULL,'MNwamba@salvicpetroleum.com','mnwamba','','Morgan','Nwamba','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57524,1529,NULL,'MOlise@salvicpetroleum.com','molise','','Moses','Olise','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57525,1529,NULL,'MSaadu@salvicpetroleum.com','msaadu','','Musa','Saadu','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57526,1529,NULL,'NLazson@salvicpetroleum.com','nlazson','','Nchekwube','Lazson','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57527,1529,NULL,'NUnanka@salvicpetroleum.com','nunanka','','Nduka','Unanka','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57528,1529,NULL,'NOkolo@salvicpetroleum.com','nokolo','','Nnadozie','Okolo','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57529,1529,NULL,'OEkweozor@salvicpetroleum.com','oekweozor','','Obi','Ekweozor','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57530,1529,NULL,'OOnyemeh@salvicpetroleum.com','oonyemeh','','Obiajulu Nnaya','Onyemeh','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57531,1529,NULL,'OObiajulu@salvicpetroleum.com','oobiajulu','','Obieze','Obiajulu','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57532,1528,1,'OAkahara@salvicpetroleum.com','oakahara','','Obinna','Akahara','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57533,1529,NULL,'OAmadi@salvicpetroleum.com','oamadi','','Obinna','Amadi','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57534,1529,NULL,'OEzeagu@salvicpetroleum.com','oezeagu','','Obinna','Ezeagu','male',NULL,'inactive',NULL,'2017-11-28 15:19:24','2018-01-19 10:42:32'),(57535,1529,NULL,'OUyaemesi@salvicpetroleum.com','ouyaemesi','','Obumneme','Uyaemesi','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57536,1529,NULL,'FOnovo@salvicpetroleum.com','fonovo','','Odinaka','Onovo','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57537,1529,NULL,'OUmoren@salvicpetroleum.com','oumoren','','Odudu','Umoren','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57538,1529,NULL,'OEke@salvicpetroleum.com','oeke','','Ogburu','Eke','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57539,1529,NULL,'OOkolo@salvicpetroleum.com','ookolo','','Ogechukwu','Okolo','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57540,1529,NULL,'ONgana@salvicpetroleum.com','ongana','','Okechukwu Collins','Ngana','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57541,1529,NULL,'OHenry@salvicpetroleum.com','ohenry','','Okee','Henry','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57542,1529,NULL,'OEkeocha@salvicpetroleum.com','oekeocha','','Okey','Ekeocha','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57543,1529,NULL,'OOkpon@salvicpetroleum.com','ookpon','','Okpon','Okpon','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57544,1529,NULL,'OOdedeyi@salvicpetroleum.com','oodedeyi','','Olalekan','Odedeyi','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57545,1529,NULL,'OAdeoye@salvicpetroleum.com','oadeoye','','Olanike','Adeoye','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57546,1529,NULL,'OlaniwunAjayi@salvicpetroleum.com','olaniwunajayi','','OlaniwunAjayi','Gulfstream','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57547,1529,NULL,'OEmeka-Oboti@salvicpetroleum.com','oemeka-oboti','','Oluchi','Emeka-Oboti','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57548,1529,NULL,'OOyebanji@salvicpetroleum.com','ooyebanji','','Olusegun','Oyebanji','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57549,1529,NULL,'OOlorunyomi@salvicpetroleum.com','oolorunyomi','','Oluseyi','Olorunyomi','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57550,1529,NULL,'OOlominu@salvicpetroleum.com','oolominu','','Oluwafemi','Olominu','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57551,1529,NULL,'OAluko@salvicpetroleum.com','oaluko','','Oluwakemi','Aluko','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57552,1529,NULL,'OAjiborisha@salvicpetroleum.com','oajiborisha','','Oluwatope','Ajiborisha','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57553,1529,NULL,'oml30facilities@salvicpetroleum.com','oml30facilities','','OML30Facilities','','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:41:34'),(57554,1529,NULL,'OBeckles@salvicpetroleum.com','obeckles','','Onyinyechi','Beckles','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57555,1529,NULL,'OIkoko@salvicpetroleum.com','oikoko','','Ovie','Ikoko','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57556,1529,NULL,'OOdukale@salvicpetroleum.com','oodukale','','Oye','Odukale','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57557,1531,6,'ParkViewConferenceRoom@salvicpetroleum.com','parkviewconferenceroom','','Park-View','Conference-Room','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57558,1529,NULL,'PNdu@salvicpetroleum.com','pndu','','Paul','Ndu','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57559,1529,NULL,'PAigba@salvicpetroleum.com','paigba','','Peter','Aigba','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57560,1529,NULL,'PMogbolu@salvicpetroleum.com','pmogbolu','','Peter','Mogbolu','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57561,1529,NULL,'PChukwu@salvicpetroleum.com','pchukwu','','Phil','Chukwu','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57562,1529,NULL,'PAisueni@salvicpetroleum.com','paisueni','','Philip','Aisueni','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57563,1529,NULL,'PIsah@salvicpetroleum.com','pisah','','Phoebe','Isah','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57564,1530,11,'prcommercial@salvicpetroleum.com','prcommercial','','PR','Commercial','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:41:34'),(57565,1529,NULL,'PEnwere@salvicpetroleum.com','penwere','','Priscilla','Enwere','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57566,1529,NULL,'POgbennia@salvicpetroleum.com','pogbennia','','Promise Amarachi','Ogbennia','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57567,1529,NULL,'pwc@salvicpetroleum.com','pwc','','PWC','Gulfstream','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:41:34'),(57568,1529,NULL,'ORadharani@salvicpetroleum.com','oradharani','','Radharani','Okpu','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57569,1529,NULL,'RAkaeze@salvicpetroleum.com','rakaeze','','Ralph','Akaeze','male',NULL,'inactive',NULL,'2017-11-28 15:19:25','2018-01-19 10:42:32'),(57570,1529,NULL,'RUruvwewhu@salvicpetroleum.com','ruruvwewhu','','Ramzey','Uruvwewhu','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57571,1529,NULL,'ROlutola@salvicpetroleum.com','rolutola','','Raphael','Olutola','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57572,1529,NULL,'RIbe@salvicpetroleum.com','ribe','','Remigius','Ibe','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57573,1529,NULL,'RIzibili@salvicpetroleum.com','rizibili','','Ronald','Izibili','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57574,1529,NULL,'RLawuyi@salvicpetroleum.com','rlawuyi','','Rotimi','Lawuyi','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57575,1529,NULL,'RMejule@salvicpetroleum.com','rmejule','','Rotimi','Mejule','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57576,1530,9,'SafetyAlertsandLFI@salvicpetroleum.com','safetyalertsandlfi','','SafetyAlerts','LFI','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57577,1530,15,'salviccorporateaffairs@salvicpetroleum.com','salviccorporateaffairs','','Corporate','Affairs','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:41:34'),(57578,1529,NULL,'salvicdd@salvicpetroleum.com','salvicdd','','Salvic','DD','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:41:34'),(57579,1529,NULL,'SalvicDD-HR@salvicpetroleum.com','salvicdd-hr','','Salvic DD','HR','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57580,1529,NULL,'SalvicDD-LegalOA@salvicpetroleum.com','salvicdd-legaloa','','Salvic DD','LegalOA','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57581,1529,NULL,'SalvicDD-LegalWS@salvicpetroleum.com','salvicdd-legalws','','Salvic DD','LegalWS','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57582,1529,NULL,'SalvicDD-PWC@salvicpetroleum.com','salvicdd-pwc','','Salvic','DD PWC','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57583,1529,NULL,'SalvicDD-Technical@salvicpetroleum.com','salvicdd-technical','','Salvic DD','Technical','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57584,1530,2,'hr@salvicpetroleum.com','hr','','Human','Resources','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:41:34'),(57585,1529,NULL,'support@salvicpetroleum.onmicrosoft.com','support','','Charles','Igweze','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:41:34'),(57586,1529,NULL,'SPrinter@salvicpetroleum.com','sprinter','','Salvic','Printer','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57587,1529,NULL,'SPrinters@salvicpetroleum.com','sprinters','','Salvic','Printers','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57588,1530,9,'security@salvicpetroleum.com','security','','Security','Hsseq','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:41:34'),(57589,1529,NULL,'SAnimashaun@salvicpetroleum.com','sanimashaun','','Samad','Animashaun','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57590,1529,NULL,'SAgada@salvicpetroleum.com','sagada','','Sampson','Agada','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57591,1529,NULL,'SAladekomo@salvicpetroleum.com','saladekomo','','Segun','Aladekomo','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57592,1529,NULL,'SharePointAdmin@salvicpetroleum.com','sharepointadmin','','SharePoint','Admin','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57593,1529,NULL,'Shell@salvicpetroleum.com','shell','','Shell','Gulfstream','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57594,1529,NULL,'SMajithia@salvicpetroleum.com','smajithia','','Shree','Majithia','male',NULL,'inactive',NULL,'2017-11-28 15:19:26','2018-01-19 10:42:32'),(57595,1529,NULL,'SOkorieh@salvicpetroleum.com','sokorieh','','Silas','Okorieh','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:32'),(57596,1529,NULL,'SChilaka@salvicpetroleum.com','schilaka','','Simeon','Chilaka','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:32'),(57597,1529,NULL,'SUkpaka@salvicpetroleum.com','sukpaka','','Simon','Ukpaka','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:32'),(57598,1529,NULL,'SAdobor@salvicpetroleum.com','sadobor','','Solomon','Adobor','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:32'),(57599,1529,NULL,'SAliu@salvicpetroleum.com','saliu','','Solomon','Aliu','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:32'),(57600,1529,NULL,'SEjowhomu@salvicpetroleum.com','sejowhomu','','Solomon','Arharhire','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:32'),(57601,1529,NULL,'SMamza@salvicpetroleum.com','smamza','','Solomon','Mamza','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:32'),(57602,1529,NULL,'SBanye@salvicpetroleum.com','sbanye','','Sophie','Banye','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:32'),(57603,1528,1,'SPeter@salvicpetroleum.com','speter','','Stanley','Peter','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:32'),(57604,1529,NULL,'SIbrighademor@salvicpetroleum.com','sibrighademor','','Stephen','Ibrighademor','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:32'),(57605,1529,NULL,'SKoko@salvicpetroleum.com','skoko','','Stephen','Koko','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:32'),(57606,1529,NULL,'SHaliru@salvicpetroleum.com','shaliru','','Suleiman','Haliru','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57607,1529,NULL,'SEghuvwakpor@salvicpetroleum.com','seghuvwakpor','','Sunday','Eghuvwakpor','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57608,1529,NULL,'SAghanwa@salvicpetroleum.com','saghanwa','','Sunny','Aghanwa','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57609,1529,NULL,'TEkiyor-Katimi@salvicpetroleum.com','tekiyor-katimi','','Theophilus','Ekiyor-Katimi','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57610,1529,NULL,'TIgani@salvicpetroleum.com','tigani','','Theophilus','Igani','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57611,1529,NULL,'TAbidde@salvicpetroleum.com','tabidde','','Timothy','Abbide','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57612,1529,NULL,'TOmopo@salvicpetroleum.com','tomopo','','Titilope','Omopo','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57613,1529,NULL,'TEchesi@salvicpetroleum.com','techesi','','Tochukwu','Echesi','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57614,1529,NULL,'TIkpeama@salvicpetroleum.com','tikpeama','','Tochukwu','Ikpeama','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57615,1529,NULL,'TOrakwue@salvicpetroleum.com','torakwue','','Tochukwu','Orakwue','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57616,1529,NULL,'TJohnny@salvicpetroleum.com','tjohnny','','Tonbebe','Johnny','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57617,1529,NULL,'UAfam-Anadu@salvicpetroleum.com','uafam-anadu','','Uche','Afam-Anadu','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57618,1529,NULL,'UAttah@salvicpetroleum.com','uattah','','Uche','Attah','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57619,1529,NULL,'UAnajemba@salvicpetroleum.com','uanajemba','','Uche','Lotanna-Anajemba','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57620,1529,NULL,'UKalu@salvicpetroleum.com','ukalu','','Uchechi Grace','Kalu','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57621,1529,NULL,'UNwankwo@salvicpetroleum.com','unwankwo','','Uchechi','Nwankwo','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57622,1529,NULL,'UEkemezie@salvicpetroleum.com','uekemezie','','Uchechukwu','Ekemezie','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57623,1529,NULL,'UOrekyeh@salvicpetroleum.com','uorekyeh','','Uchenna','Orekyeh','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57624,1529,NULL,'UUmoh@salvicpetroleum.com','uumoh','','Uduak','Umoh','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57625,1529,NULL,'UNwokolo@salvicpetroleum.com','unwokolo','','Ugochi Julia','Nwokolo','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57626,1529,NULL,'UYahaya@salvicpetroleum.com','uyahaya','','Umar','Yahaya','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57627,1529,NULL,'UHashim@salvicpetroleum.com','uhashim','','Usman','Hashim','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57628,1529,NULL,'UOkoroafor@salvicpetroleum.com','uokoroafor','','Uzoma','Okoroafor','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57629,1529,NULL,'VNsofor@salvicpetroleum.com','vnsofor','','Vanessa','Nsofor','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57630,1529,NULL,'VOnwughalu@salvicpetroleum.com','vonwughalu','','Vanessa','Onwughalu','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57631,1529,NULL,'VKii@salvicpetroleum.com','vkii','','Victor','Kii','male',NULL,'inactive',NULL,'2017-11-28 15:19:27','2018-01-19 10:42:33'),(57632,1529,NULL,'VOkonji@salvicpetroleum.com','vokonji','','Victor','Okonji','male',NULL,'inactive',NULL,'2017-11-28 15:19:28','2018-01-19 10:42:33'),(57633,1529,NULL,'Vitol@salvicpetroleum.com','vitol','','VITOL','Gulfstream','male',NULL,'inactive',NULL,'2017-11-28 15:19:28','2018-01-19 10:42:33'),(57634,1529,NULL,'WOnyeanya@salvicpetroleum.com','wonyeanya','','Wealth','Onyeanya','male',NULL,'inactive',NULL,'2017-11-28 15:19:28','2018-01-19 10:42:33'),(57635,1529,NULL,'Winston@salvicpetroleum.com','winston','','Winston','Gulfstream','male',NULL,'inactive',NULL,'2017-11-28 15:19:28','2018-01-19 10:42:33'),(57636,1529,NULL,'WWobisike@salvicpetroleum.com','wwobisike','','Wobo','Wobisike','male',NULL,'inactive',NULL,'2017-11-28 15:19:28','2018-01-19 10:42:33'),(57637,1529,NULL,'YOmorogbe@salvicpetroleum.com','yomorogbe','','Yinka','Omorogbe','male',NULL,'inactive',NULL,'2017-11-28 15:19:28','2018-01-19 10:42:33'),(57638,1529,NULL,'YAmaefule@salvicpetroleum.com','yamaefule','','Yolanda','Amaefule','male',NULL,'inactive',NULL,'2017-11-28 15:19:28','2018-01-19 10:42:33'),(57642,1531,7,'abbey@court.com','abbey',NULL,'Abbey','Court','male',NULL,'inactive',NULL,'2017-12-12 10:19:00','2018-01-19 10:41:34'),(57643,1530,1,'itunit@salvicpetroleum.com','itunit',NULL,'IT','Unit','male',NULL,'inactive',NULL,'2017-12-19 09:48:52','2018-01-19 10:41:34'),(57644,1529,32,'pmembu@salvicpetroleum.com','pmembu',NULL,'Paul','Membu','male','18010300','inactive',NULL,'2018-01-10 16:35:55','2018-01-19 10:41:34');
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

-- Dump completed on 2018-02-08 12:52:01
