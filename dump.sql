-- MySQL dump 10.13  Distrib 8.0.28, for Linux (x86_64)
--
-- Host: localhost    Database: materials
-- ------------------------------------------------------
-- Server version	8.0.28-0ubuntu0.20.04.3

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
-- Table structure for table `manufacturers`
--

DROP TABLE IF EXISTS `manufacturers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `manufacturers` (
  `id` int NOT NULL,
  `name` varchar(128) NOT NULL,
  `country` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `manufacturers_name_key` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manufacturers`
--

LOCK TABLES `manufacturers` WRITE;
/*!40000 ALTER TABLE `manufacturers` DISABLE KEYS */;
INSERT INTO `manufacturers` VALUES (0,'Saint-Gobain','France'),(1,'CRH','Ireland'),(2,'Daikin Industries','Japan'),(3,'Heidelberg Cement','Germany'),(4,'LafargeHolcim','Switzerland'),(5,'Wolseley','Switzerland'),(6,'China National Building','China'),(7,'Asahi Glass','Japan'),(8,'Lixil Group','Japan'),(9,'Masco','USA'),(10,'BBMG','China'),(11,'Decra','Russia');
/*!40000 ALTER TABLE `manufacturers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(128) NOT NULL,
  `price` double NOT NULL,
  `release_date` date NOT NULL,
  `manufacturer_id` int NOT NULL,
  `vendor_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_name_key` (`name`),
  KEY `products_manufacturer_id_fkey` (`manufacturer_id`),
  KEY `products_vendor_id_fkey` (`vendor_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (0,'Cement',1000,'2021-04-09',0,0),(1,'Bricks',2500,'2021-07-17',11,9),(2,'Jackhammer',15000,'2019-04-12',6,1),(3,'Plaster',494,'2021-05-20',4,4),(4,'Profile pipe',502,'2020-03-02',11,10),(5,'Hammer',1000,'2020-10-23',6,0),(6,'Electrical fireplace',17601,'2021-06-15',0,0),(7,'Concrete mixer',11756,'2020-09-23',2,8),(8,'Protective helmet',142,'2020-08-18',5,8),(9,'Drywall',472,'2020-08-14',11,3);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(128) NOT NULL,
  `login` varchar(128) NOT NULL,
  `hash` varchar(128) NOT NULL,
  `user_level` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  CONSTRAINT `users_chk_1` CHECK ((`user_level` >= 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (0,'ivan','ivan','djfkd/6Oq8CFU',2),(1,'administrator','admin','djwMXXcrMMxYg',1),(2,'user','user','djRbUFYWryyOY',2);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendors` (
  `id` int NOT NULL,
  `name` varchar(128) NOT NULL,
  `country` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vendors_name_key` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendors`
--

LOCK TABLES `vendors` WRITE;
/*!40000 ALTER TABLE `vendors` DISABLE KEYS */;
INSERT INTO `vendors` VALUES (0,'Synergo-stroy','Russia'),(1,'Stroy-service','Russia'),(2,'Sibir-Opt','Russia'),(3,'Snab-torg','Russia'),(4,'LeroyMerlin','France'),(5,'Vulcanium','Mexico'),(6,'MFY','USA'),(7,'MaterialExpress','India'),(8,'InsightStone','France'),(9,'Stroy-Mir','Russia'),(10,'Stroy-Komplekt','Russia');
/*!40000 ALTER TABLE `vendors` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-03-08 16:43:33
