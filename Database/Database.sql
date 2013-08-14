-- MySQL dump 10.13  Distrib 5.6.11, for Win32 (x86)
--
-- Host: 127.0.0.1    Database: d0f0bbda4303c49268209e2ef4b26e8cb
-- ------------------------------------------------------
-- Server version	5.5.31-0ubuntu0.12.04.2

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
-- Table structure for table `Category`
--

DROP TABLE IF EXISTS `Category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Category` (
  `idCategory` int(11) NOT NULL AUTO_INCREMENT,
  `categoryType` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`idCategory`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Category`
--

LOCK TABLES `Category` WRITE;
/*!40000 ALTER TABLE `Category` DISABLE KEYS */;
INSERT INTO `Category` VALUES (1,'Apps','Books'),(2,'Apps','Finance'),(3,'Apps','Navigation & Travel'),(4,'Apps','Social'),(5,'Apps','Business'),(6,'Apps','Health & Fitness'),(7,'Apps','News & Magazines'),(8,'Apps','Sports'),(9,'Apps','Education & Reference'),(10,'Apps','Lifestyle'),(11,'Apps','Photo & Video'),(12,'Apps','Utilities'),(13,'Apps','Entertainment'),(14,'Apps','Music & Audio'),(15,'Apps','Productivity'),(16,'Apps','Weather'),(17,'Apps','Other'),(18,'Games','Action'),(19,'Games','Children\'s Games'),(20,'Games','Space'),(21,'Games','Arcade'),(22,'Games','Combat'),(23,'Games','Sports'),(24,'Games','Board Games'),(25,'Games','Movies & TV'),(26,'Games','Strategy'),(27,'Games','Cards'),(28,'Games','Puzzles'),(29,'Games','Other');
/*!40000 ALTER TABLE `Category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Comment`
--

DROP TABLE IF EXISTS `Comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Comment` (
  `idComment` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `content` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`idComment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Comment`
--

LOCK TABLES `Comment` WRITE;
/*!40000 ALTER TABLE `Comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `Comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Developer`
--

DROP TABLE IF EXISTS `Developer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Developer` (
  `idDeveloper` int(11) NOT NULL AUTO_INCREMENT,
  `vendorId` int(11) DEFAULT NULL,
  `twitter` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `github` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lindekin` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`idDeveloper`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Developer`
--

LOCK TABLES `Developer` WRITE;
/*!40000 ALTER TABLE `Developer` DISABLE KEYS */;
/*!40000 ALTER TABLE `Developer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Developer_Idea`
--

DROP TABLE IF EXISTS `Developer_Idea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Developer_Idea` (
  `idIdea` int(11) NOT NULL,
  `idDeveloper` int(11) NOT NULL,
  `progress` int(11) DEFAULT NULL,
  `appId` int(11) DEFAULT NULL,
  KEY `fk_Developer_Idea_Idea1_idx` (`idIdea`),
  KEY `fk_Developer_Idea_Developer1_idx` (`idDeveloper`),
  CONSTRAINT `fk_Developer_Idea_Idea1` FOREIGN KEY (`idIdea`) REFERENCES `Idea` (`idIdea`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Developer_Idea_Developer1` FOREIGN KEY (`idDeveloper`) REFERENCES `Developer` (`idDeveloper`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Developer_Idea`
--

LOCK TABLES `Developer_Idea` WRITE;
/*!40000 ALTER TABLE `Developer_Idea` DISABLE KEYS */;
/*!40000 ALTER TABLE `Developer_Idea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Idea`
--

DROP TABLE IF EXISTS `Idea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Idea` (
  `idIdea` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) NOT NULL,
  `idCategory` int(11) NOT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `votes` int(11) DEFAULT NULL,
  `reportNumber` int(11) DEFAULT NULL,
  `isTaken` tinyint(1) DEFAULT NULL,
  `voteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`idIdea`),
  KEY `fk_Idea_User1_idx` (`idUser`),
  KEY `fk_Idea_Category1_idx` (`idCategory`),
  CONSTRAINT `fk_Idea_Category1` FOREIGN KEY (`idCategory`) REFERENCES `Category` (`idCategory`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Idea_User1` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Idea`
--

LOCK TABLES `Idea` WRITE;
/*!40000 ALTER TABLE `Idea` DISABLE KEYS */;
/*!40000 ALTER TABLE `Idea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Idea_Comment`
--

DROP TABLE IF EXISTS `Idea_Comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Idea_Comment` (
  `idIdea` int(11) NOT NULL,
  `idComment` int(11) NOT NULL,
  KEY `fk_Idea_Comment_Idea1_idx` (`idIdea`),
  KEY `fk_Idea_Comment_Comment1_idx` (`idComment`),
  CONSTRAINT `fk_Idea_Comment_Idea1` FOREIGN KEY (`idIdea`) REFERENCES `Idea` (`idIdea`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Idea_Comment_Comment1` FOREIGN KEY (`idComment`) REFERENCES `Comment` (`idComment`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Idea_Comment`
--

LOCK TABLES `Idea_Comment` WRITE;
/*!40000 ALTER TABLE `Idea_Comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `Idea_Comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `idDeveloper` int(11) NOT NULL,
  `firstName` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastName` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `salt` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `photoURL` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `coins` int(11) DEFAULT NULL,
  `isDeveloper` tinyint(1) DEFAULT NULL,
  `isAdmin` tinyint(1) DEFAULT NULL,
  `isBanned` tinyint(1) DEFAULT NULL,
  `sharedSecret` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`idUser`),
  KEY `fk_User_Developer1_idx` (`idDeveloper`),
  CONSTRAINT `fk_User_Developer1` FOREIGN KEY (`idDeveloper`) REFERENCES `Developer` (`idDeveloper`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User_Comment`
--

DROP TABLE IF EXISTS `User_Comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User_Comment` (
  `idComment` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  KEY `fk_User_Comment_Comment1_idx` (`idComment`),
  KEY `fk_User_Comment_User1_idx` (`idUser`),
  CONSTRAINT `fk_User_Comment_Comment1` FOREIGN KEY (`idComment`) REFERENCES `Comment` (`idComment`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_Comment_User1` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User_Comment`
--

LOCK TABLES `User_Comment` WRITE;
/*!40000 ALTER TABLE `User_Comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `User_Comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VotedIdeas`
--

DROP TABLE IF EXISTS `VotedIdeas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VotedIdeas` (
  `idIdea` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  KEY `idUser_idx` (`idUser`),
  KEY `idIdea` (`idIdea`),
  CONSTRAINT `idIdea` FOREIGN KEY (`idIdea`) REFERENCES `Idea` (`idIdea`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `idUser` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VotedIdeas`
--

LOCK TABLES `VotedIdeas` WRITE;
/*!40000 ALTER TABLE `VotedIdeas` DISABLE KEYS */;
/*!40000 ALTER TABLE `VotedIdeas` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-08-14 15:18:38
