-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: prwb_2324_a03
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

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
-- Table structure for table `checklist_note_items`
--

DROP TABLE IF EXISTS `checklist_note_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checklist_note_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `checklist_note` int(11) NOT NULL,
  `content` varchar(256) NOT NULL,
  `checked` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `checklist_note` (`checklist_note`,`content`),
  KEY `fkchecklist_note_items_checklist_notes` (`checklist_note`),
  CONSTRAINT `fkchecklist_note_items_checklist_notes` FOREIGN KEY (`checklist_note`) REFERENCES `checklist_notes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checklist_note_items`
--

LOCK TABLES `checklist_note_items` WRITE;
/*!40000 ALTER TABLE `checklist_note_items` DISABLE KEYS */;
INSERT INTO `checklist_note_items` VALUES (1,24,'reblochon',0),(2,24,'crème fraiche',0),(3,24,'lardons',0),(9,24,'pommes de terre',0),(10,24,'oignons',0),(11,25,'Enoncé projet PRWB',1),(12,27,'PQ',1),(13,27,'Jus',1),(14,27,'Café',0),(15,27,'Biscuits',0),(16,29,'Examen BD01',0),(17,29,'Examen TGPR',1),(18,25,'Leçon 17 PRO2',0),(19,25,'Interro boucles PRM2',0),(20,24,'vin de savoie',0);
/*!40000 ALTER TABLE `checklist_note_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `checklist_notes`
--

DROP TABLE IF EXISTS `checklist_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checklist_notes` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_checklist_notes_notes` FOREIGN KEY (`id`) REFERENCES `notes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checklist_notes`
--

LOCK TABLES `checklist_notes` WRITE;
/*!40000 ALTER TABLE `checklist_notes` DISABLE KEYS */;
INSERT INTO `checklist_notes` VALUES (24),(25),(27),(29);
/*!40000 ALTER TABLE `checklist_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `note_labels`
--

DROP TABLE IF EXISTS `note_labels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `note_labels` (
  `note` int(11) NOT NULL,
  `label` varchar(32) NOT NULL,
  PRIMARY KEY (`note`,`label`),
  CONSTRAINT `fk_note_labels_users` FOREIGN KEY (`note`) REFERENCES `notes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `note_labels`
--

LOCK TABLES `note_labels` WRITE;
/*!40000 ALTER TABLE `note_labels` DISABLE KEYS */;
INSERT INTO `note_labels` VALUES (21,'Maison'),(21,'Priv&eacute;'),(22,'Travail'),(23,'Maison'),(23,'Priv&eacute;'),(23,'Travail'),(24,'Loisirs'),(24,'Maison'),(25,'Travail'),(27,'Loisirs'),(27,'Maison'),(27,'Travail'),(29,'Travail');
/*!40000 ALTER TABLE `note_labels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `note_shares`
--

DROP TABLE IF EXISTS `note_shares`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `note_shares` (
  `note` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `editor` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`note`,`user`),
  KEY `fk_note_shares_users` (`user`),
  CONSTRAINT `fk_note_shares_notes` FOREIGN KEY (`user`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_note_shares_users` FOREIGN KEY (`note`) REFERENCES `notes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `note_shares`
--

LOCK TABLES `note_shares` WRITE;
/*!40000 ALTER TABLE `note_shares` DISABLE KEYS */;
INSERT INTO `note_shares` VALUES (23,4,0),(25,2,1),(25,4,0),(26,1,0),(26,2,0),(27,1,1),(27,3,0),(28,1,0),(29,1,0);
/*!40000 ALTER TABLE `note_shares` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `owner` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `edited_at` datetime DEFAULT NULL,
  `pinned` tinyint(1) NOT NULL DEFAULT 0,
  `archived` tinyint(1) NOT NULL DEFAULT 0,
  `weight` double NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_title_owner` (`title`,`owner`),
  UNIQUE KEY `unique_weigh_owner` (`weight`,`owner`),
  KEY `fk_notes_users` (`owner`),
  CONSTRAINT `fk_notes_users` FOREIGN KEY (`owner`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notes`
--

LOCK TABLES `notes` WRITE;
/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
INSERT INTO `notes` VALUES (20,'Note archivée',1,'2023-10-11 11:51:37','2023-11-21 09:18:46',0,1,1),(21,'Code alarme',1,'2023-10-11 11:51:43','2023-11-20 21:33:47',1,0,7),(22,'Pensées',1,'2023-10-11 11:51:50','2023-11-21 09:17:15',1,0,5),(23,'Note avec un long texte',1,'2023-10-11 13:19:30','2023-11-20 21:36:02',0,0,2),(24,'Colruyt tartiflette',1,'2023-10-12 20:16:52','2023-11-20 21:37:12',1,0,4),(25,'Urgent',1,'2023-10-19 21:01:06','2023-11-20 21:34:34',1,0,6),(26,'Netflix password',4,'2023-11-06 23:03:42','2023-11-21 09:20:40',1,0,3),(27,'Courses',4,'2023-11-06 23:04:52','2023-11-21 09:19:34',0,0,2),(28,'Git clean',2,'2023-11-13 15:50:19',NULL,0,0,1),(29,'Prépa EPFC',2,'2023-11-13 16:08:37','2023-11-21 09:22:07',0,0,2),(30,'Note vide',1,'2023-11-20 18:42:04',NULL,0,0,3),(31,'Note archivée',4,'2023-11-21 09:21:07',NULL,0,1,1);
/*!40000 ALTER TABLE `notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `text_notes`
--

DROP TABLE IF EXISTS `text_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `text_notes` (
  `id` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_text_notes_notes` FOREIGN KEY (`id`) REFERENCES `notes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `text_notes`
--

LOCK TABLES `text_notes` WRITE;
/*!40000 ALTER TABLE `text_notes` DISABLE KEYS */;
INSERT INTO `text_notes` VALUES (20,NULL),(21,'1793'),(22,'La simplicité ne précède pas la complexité, elle la suit.'),(23,'pouet pouet Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Luctus accumsan tortor posuere ac ut consequat semper viverra. Viverra adipiscing at in tellus. Maecenas ultricies mi eget mauris pharetra et ultrices neque ornare. Nullam vehicula ipsum a arcu cursus vitae congue mauris rhoncus. Et netus et malesuada fames. Mauris sit amet massa vitae tortor condimentum lacinia quis. Ultrices dui sapien eget mi. Gravida neque convallis a cras semper auctor neque vitae tempus. Nulla facilisi cras fermentum odio eu feugiat pretium nibh ipsum.\r\n\r\nRisus feugiat in ante metus dictum at tempor commodo ullamcorper. Fermentum odio eu feugiat pretium nibh ipsum consequat nisl. Ultrices vitae auctor eu augue. Nunc non blandit massa enim nec dui nunc mattis enim. Dolor purus non enim praesent elementum facilisis leo vel fringilla. At consectetur lorem donec massa sapien faucibus et. Nunc scelerisque viverra mauris in aliquam sem fringilla ut morbi. Mattis vulputate enim nulla aliquet porttitor lacus luctus. Ultrices in iaculis nunc sed augue lacus viverra vitae. Velit dignissim sodales ut eu. Lectus nulla at volutpat diam ut venenatis tellus in metus. Nisl purus in mollis nunc sed id semper. Felis eget velit aliquet sagittis id consectetur purus. Nec ullamcorper sit amet risus nullam eget felis eget nunc. Facilisis magna etiam tempor orci.\r\n\r\nAdipiscing diam donec adipiscing tristique risus nec feugiat. Tellus integer feugiat scelerisque varius. Sit amet mattis vulputate enim nulla. Massa id neque aliquam vestibulum morbi blandit cursus risus. Eu non diam phasellus vestibulum lorem sed risus ultricies. Quis varius quam quisque id. Ante in nibh mauris cursus mattis molestie. Tristique risus nec feugiat in fermentum posuere. Posuere urna nec tincidunt praesent semper feugiat nibh. Magna sit amet purus gravida quis blandit. Ac odio tempor orci dapibus ultrices in iaculis nunc sed. Velit ut tortor pretium viverra suspendisse potenti nullam ac tortor. Non odio euismod lacinia at quis risus. Volutpat odio facilisis mauris sit.\r\n\r\nSed elementum tempus egestas sed sed risus pretium quam vulputate. Fringilla urna porttitor rhoncus dolor. Magna etiam tempor orci eu. Fusce id velit ut tortor pretium. Sed enim ut sem viverra. Dignissim enim sit amet venenatis urna cursus. Ut tristique et egestas quis ipsum suspendisse ultrices gravida. Aliquam vestibulum morbi blandit cursus risus at ultrices mi. Consectetur a erat nam at lectus urna duis. Volutpat lacus laoreet non curabitur gravida. Magna ac placerat vestibulum lectus mauris ultrices eros.\r\n\r\nNunc mi ipsum faucibus vitae aliquet nec ullamcorper. Imperdiet proin fermentum leo vel orci porta. Hendrerit dolor magna eget est lorem ipsum dolor. Scelerisque fermentum dui faucibus in ornare quam viverra orci sagittis. Scelerisque viverra mauris in aliquam sem fringilla ut morbi tincidunt. Metus dictum at tempor commodo ullamcorper a lacus. Hac habitasse platea dictumst vestibulum. Adipiscing elit pellentesque habitant morbi. Bibendum neque egestas congue quisque egestas diam in. Justo donec enim diam vulputate ut pharetra. Auctor eu augue ut lectus arcu bibendum at. Quis vel eros donec ac odio tempor orci dapibus ultrices. Quam viverra orci sagittis eu volutpat odio facilisis. Sed viverra tellus in hac habitasse platea dictumst vestibulum. Ut sem viverra aliquet eget sit amet tellus cras. Erat nam at lectus urna. Nunc eget lorem dolor sed viverra. In nulla posuere sollicitudin aliquam ultrices sagittis orci a.'),(26,'EPFC!!Password84'),(28,'git clean -xdf \r\ngit clean -xdfn pour faire une simulation'),(30,NULL),(31,'de Marc');
/*!40000 ALTER TABLE `text_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(256) NOT NULL,
  `hashed_password` varchar(512) NOT NULL,
  `full_name` varchar(256) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'boverhaegen@gmail.eu','56ce92d1de4f05017cf03d6cd514d6d1','Boris','user'),(2,'bepenelle@gmail.eu','56ce92d1de4f05017cf03d6cd514d6d1','Benoît','user'),(3,'xapigeolet@gmail.eu','56ce92d1de4f05017cf03d6cd514d6d1','Xavier','user'),(4,'mamichel@gmail.eu','56ce92d1de4f05017cf03d6cd514d6d1','Marc','user');
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

-- Dump completed on 2024-04-09 11:27:59
