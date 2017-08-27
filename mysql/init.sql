-- MySQL dump 10.13  Distrib 5.7.18-16, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: restapi
-- ------------------------------------------------------
-- Server version	5.7.18-16

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
/*!50717 SELECT COUNT(*) INTO @rocksdb_has_p_s_session_variables FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'performance_schema' AND TABLE_NAME = 'session_variables' */;
/*!50717 SET @rocksdb_get_is_supported = IF (@rocksdb_has_p_s_session_variables, 'SELECT COUNT(*) INTO @rocksdb_is_supported FROM performance_schema.session_variables WHERE VARIABLE_NAME=\'rocksdb_bulk_load\'', 'SELECT 0') */;
/*!50717 PREPARE s FROM @rocksdb_get_is_supported */;
/*!50717 EXECUTE s */;
/*!50717 DEALLOCATE PREPARE s */;
/*!50717 SET @rocksdb_enable_bulk_load = IF (@rocksdb_is_supported, 'SET SESSION rocksdb_bulk_load = 1', 'SET @rocksdb_dummy_bulk_load = 0') */;
/*!50717 PREPARE s FROM @rocksdb_enable_bulk_load */;
/*!50717 EXECUTE s */;
/*!50717 DEALLOCATE PREPARE s */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `address` varchar(255) CHARACTER SET utf8 NOT NULL,
  `postal_code` varchar(5) CHARACTER SET utf8 NOT NULL,
  `city` varchar(255) CHARACTER SET utf8 NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES 
(1,'Jan Kowalski','Garncarska 1/1','30115','Kraków','+48111222333','jan@example.com','2017-08-26 10:13:21',NULL),
(2,'Krzysztof Nowak','Krupnicza 2/2','30115','Kraków','+48222333444','krzysztof@nowak.com','2017-08-26 10:18:10',NULL),
(3,'Anna Nowak','Krupnicza 2/2','30115','Kraków','+48333444555','anna@nowak.com','2017-08-26 10:20:15',NULL),
(4,'Piotr Iksiński','Długa 3/3','31123','Kraków','+48444555666','piotr@example.com','2017-08-26 10:21:37',NULL),
(5,'Maria Nowakowska','Garncarska 2/3','30115','Kraków','+48555666777','maria@email.com','2017-08-26 10:24:19',NULL),
(6,'Jan Lewandowski','Boulevard Gambetta 43','53531','Grenoble','+49111222333','jan@grenoble.fr','2017-08-26 10:25:16',NULL),
(7,'Joanna Wójcik','Rue Brocherie','54323','Grenoble','','joanna@grenoble.fr','2017-08-26 11:06:06',NULL),
(8,'Johan Dąbrowski','Regeringsgatan 23','98234','Sztokholm','+403321234324','johan@sztokholm.se','2017-08-26 14:29:07','2017-08-26 14:29:29'),
(9,'Maciek Kamiński','Brunkebergstorg 1','98234','Sztokholm','','maciek@example.com','2017-08-26 14:29:07','2017-08-26 14:29:29'),
(10,'Jan Nowak','Garncarska 1/2','30115','Kraków','+48111222333','jan2@example.com','2017-08-26 10:13:21',NULL),
(11,'Krzysztof Kowalski','Krupnicza 2/3','30115','Kraków','+48222333444','krzysztof2@nowak.com','2017-08-26 10:18:10',NULL),
(12,'Anna Iksińska','Krupnicza 2/3','30115','Kraków','+48333444555','anna2@nowak.com','2017-08-26 10:20:15',NULL),
(13,'Piotr Iksiński','Długa 3/4','31123','Kraków','+48444555666','piotr2@example.com','2017-08-26 10:21:37',NULL),
(14,'Ula Nowakowska','Garncarska 2/5','30115','Kraków','+48555666777','ula@email.com','2017-08-26 10:24:19',NULL),
(15,'Jan Lewandowski','Boulevard Gambetta','53531','Grenoble','+49111222333','jan2@grenoble.fr','2017-08-26 10:25:16',NULL),
(16,'Johan Wójcik','Rue Brocherie 12','54323','Grenoble','','joanna2@grenoble.fr','2017-08-26 11:06:06',NULL),
(17,'Maciek Dąbrowski','Regeringsgatan 23','98234','Sztokholm','+403321234324','maciek112@sztokholm.se','2017-08-26 14:29:07','2017-08-26 14:29:29'),
(18,'Jan Kamiński','Brunkebergstorg 1','98234','Sztokholm','','jan2343@example.com','2017-08-26 14:29:07','2017-08-26 14:29:29');

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!50112 SET @disable_bulk_load = IF (@is_rocksdb_supported, 'SET SESSION rocksdb_bulk_load = @old_rocksdb_bulk_load', 'SET @dummy_rocksdb_bulk_load = 0') */;
/*!50112 PREPARE s FROM @disable_bulk_load */;
/*!50112 EXECUTE s */;
/*!50112 DEALLOCATE PREPARE s */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-08-26 21:49:01

