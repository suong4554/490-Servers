-- MySQL dump 10.13  Distrib 5.7.25, for Linux (x86_64)
--
-- Host: localhost    Database: testdb
-- ------------------------------------------------------
-- Server version	5.7.25-0ubuntu0.18.04.2

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
-- Table structure for table `chats`
--

DROP TABLE IF EXISTS `chats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chats` (
  `ChatId` int(11) NOT NULL AUTO_INCREMENT,
  `ChatUsername` varchar(50) NOT NULL,
  `ChatGameId` int(11) NOT NULL,
  `ChatText` varchar(50) NOT NULL,
  PRIMARY KEY (`ChatId`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chats`
--

LOCK TABLES `chats` WRITE;
/*!40000 ALTER TABLE `chats` DISABLE KEYS */;
INSERT INTO `chats` VALUES (25,'OMEGAMAN',0,'wtf\n'),(26,'OMEGAMAN',0,'wtf\n'),(27,'OMEGAMAN',0,'asda\n'),(28,'OMEGAMAN',0,'asdas\n'),(29,'OMEGAMAN',0,'asdas\n'),(30,'OMEGAMAN',0,'asda\n'),(31,'OMEGAMAN',0,'asdas\n'),(32,'OMEGAMAN',0,'asda\n'),(33,'megaman',0,'wo shi\n'),(34,'megaman',0,'MEGAMAN\n'),(35,'megaman',0,'\'OP\'\n'),(36,'shitman',0,'HELLO THERE\n'),(37,'shitman',0,'\'SHITS WORKING AGAIN\'\n'),(38,'shitman',0,'FINALLY\n'),(39,'test',0,'test\n');
/*!40000 ALTER TABLE `chats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matches`
--

DROP TABLE IF EXISTS `matches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matches` (
  `Username` varchar(255) NOT NULL,
  `Looking` int(11) DEFAULT NULL,
  `turn` int(11) DEFAULT NULL,
  `MatchId` int(11) DEFAULT NULL,
  `currentTurn` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  PRIMARY KEY (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matches`
--

LOCK TABLES `matches` WRITE;
/*!40000 ALTER TABLE `matches` DISABLE KEYS */;
INSERT INTO `matches` VALUES ('',1,NULL,NULL,1,NULL),('test',1,NULL,NULL,1,NULL);
/*!40000 ALTER TABLE `matches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `playerHistory`
--

DROP TABLE IF EXISTS `playerHistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `playerHistory` (
  `GameId` int(6) NOT NULL AUTO_INCREMENT,
  `playerOneUser` varchar(30) NOT NULL,
  `playerTwoUser` varchar(30) NOT NULL,
  `winner` varchar(30) NOT NULL,
  `playerOneScore` int(30) NOT NULL,
  `playerTwoScore` int(30) NOT NULL,
  `turnsUsed` time DEFAULT NULL,
  `gameDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`GameId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `playerHistory`
--

LOCK TABLES `playerHistory` WRITE;
/*!40000 ALTER TABLE `playerHistory` DISABLE KEYS */;
INSERT INTO `playerHistory` VALUES (1,'test','Joel','test',4,0,'00:00:01','2019-03-14 23:38:34'),(2,'test','Joel','test',12,0,'00:00:01','2019-03-14 23:39:37');
/*!40000 ALTER TABLE `playerHistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userTable`
--

DROP TABLE IF EXISTS `userTable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userTable` (
  `UserId` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  PRIMARY KEY (`UserId`,`Username`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userTable`
--

LOCK TABLES `userTable` WRITE;
/*!40000 ALTER TABLE `userTable` DISABLE KEYS */;
INSERT INTO `userTable` VALUES (1,'billy','billy@iLove490.com','217102c28bc2cf954064528619acc45d23b3ce2a'),(2,'joel','joel@mailinator.com','045145e6beaa0e5c8d57759eebe4bf6495479fe9'),(3,'edwin','edwin@gmail.com','njit'),(4,'tim','tim@gmail.com','njit'),(5,'sam','sam@gmail.com','njit'),(6,'ugh','ugh@gmail.com','njit'),(7,'ughh','ughh@gmail.com','njit'),(8,'ughhhh','ughhhh@gmail.com','njit'),(9,'test','test@gmail.com','njit');
/*!40000 ALTER TABLE `userTable` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-26  0:31:33
