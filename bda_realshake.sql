-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: localhost    Database: real_shake
-- ------------------------------------------------------
-- Server version	5.7.11

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
-- Table structure for table `baixa_acesso`
--

DROP TABLE IF EXISTS `baixa_acesso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `baixa_acesso` (
  `ID_BAIXA_ACESSO` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data_hora_baixa_acesso` datetime DEFAULT NULL,
  `ID_CLIENTE` int(10) unsigned NOT NULL,
  `ID_TIPO_ACESSO` int(10) unsigned NOT NULL,
  `QTDE_ACESSO` int(11) NOT NULL,
  PRIMARY KEY (`ID_BAIXA_ACESSO`),
  KEY `ID_CLIENTE` (`ID_CLIENTE`),
  KEY `ID_TIPO_ACESSO` (`ID_TIPO_ACESSO`)
) ENGINE=MyISAM AUTO_INCREMENT=315 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `baixa_acesso`
--

LOCK TABLES `baixa_acesso` WRITE;
/*!40000 ALTER TABLE `baixa_acesso` DISABLE KEYS */;
INSERT INTO `baixa_acesso` VALUES (1,'2017-05-29 17:29:27',1,3,1),(2,'2017-05-29 17:29:27',1,3,1),(3,'2017-05-29 17:29:27',1,3,1),(4,'2017-05-29 17:29:27',1,3,1),(5,'2017-05-29 17:29:27',1,3,1),(6,'2017-05-29 17:29:27',1,3,1),(7,'2017-05-29 17:29:27',1,3,1),(8,'2017-05-29 17:29:27',1,3,1),(9,'2017-05-29 17:29:27',1,3,1),(10,'2017-05-29 17:29:27',1,3,1),(11,'2017-05-29 17:29:27',1,3,1),(12,'2017-05-29 17:29:27',11,3,1),(13,'2017-05-29 17:29:27',11,3,1),(14,'2017-05-29 17:29:27',11,3,1),(15,'2017-05-29 17:29:27',11,3,1),(16,'2017-05-29 17:29:27',11,3,1),(17,'2017-05-29 17:29:27',11,3,1),(18,'2017-05-29 17:29:27',12,3,1),(19,'2017-05-29 17:29:27',12,3,1),(20,'2017-05-29 17:29:27',12,3,1),(21,'2017-05-29 17:29:27',12,3,1),(22,'2017-05-29 17:29:27',12,3,1),(23,'2017-05-29 17:29:27',12,3,1),(24,'2017-05-29 17:29:27',12,3,1),(25,'2017-05-29 17:29:27',12,3,1),(26,'2017-05-29 17:29:27',3,3,1),(27,'2017-05-29 17:29:27',3,3,1),(28,'2017-05-29 17:29:27',3,3,1),(29,'2017-05-29 17:29:27',3,3,1),(30,'2017-05-29 17:29:27',3,3,1),(31,'2017-05-29 17:29:27',3,3,1),(32,'2017-05-29 17:29:27',7,3,1),(33,'2017-05-29 17:29:27',7,3,1),(34,'2017-05-29 17:29:27',16,3,1),(35,'2017-05-29 17:29:27',18,3,1),(36,'2017-05-29 17:29:27',18,3,1),(37,'2017-05-29 17:29:27',18,3,1),(38,'2017-05-29 17:29:27',18,3,1),(39,'2017-05-29 17:29:27',18,3,1),(40,'2017-05-29 17:29:27',18,3,1),(41,'2017-05-29 17:29:27',18,3,1),(42,'2017-05-29 17:29:27',18,3,1),(43,'2017-05-29 17:29:27',18,3,1),(44,'2017-05-29 17:29:27',18,3,1),(45,'2017-05-29 17:29:27',18,3,1),(46,'2017-05-29 17:29:27',18,3,1),(47,'2017-05-29 17:29:27',22,3,1),(48,'2017-05-29 17:29:27',22,3,1),(49,'2017-05-29 17:29:27',22,3,1),(50,'2017-05-29 17:29:27',24,3,1),(51,'2017-05-29 17:29:27',24,3,1),(52,'2017-05-29 17:29:27',24,3,1),(53,'2017-05-29 17:29:27',24,3,1),(54,'2017-05-29 17:29:27',24,3,1),(55,'2017-05-29 17:29:27',24,3,1),(56,'2017-05-29 17:29:27',19,3,1),(57,'2017-05-29 17:29:27',19,3,1),(58,'2017-05-29 17:29:27',19,3,1),(59,'2017-05-29 17:29:27',19,3,1),(60,'2017-05-29 17:29:27',19,3,1),(61,'2017-05-29 17:29:27',5,3,1),(62,'2017-05-29 17:29:27',5,3,1),(63,'2017-05-29 17:29:27',5,3,1),(64,'2017-05-29 17:29:27',5,3,1),(314,'2017-08-18 12:46:30',9,3,1),(313,'2017-08-18 12:45:35',9,3,1),(312,'2017-08-18 12:45:15',9,3,2),(311,'2017-08-18 12:34:35',9,3,2),(310,'2017-08-18 12:33:09',28,3,1),(70,'2017-05-29 17:29:27',25,3,1),(71,'2017-05-29 17:29:27',25,3,1),(76,'2017-06-29 00:37:34',18,1,8),(77,'2017-06-29 00:38:05',18,2,1),(79,'2017-07-13 19:58:05',18,1,1),(80,'2017-07-13 19:58:21',5,3,1),(81,'2017-07-14 20:12:36',3,3,1),(82,'2017-07-13 20:43:34',21,3,1),(83,'2017-07-17 20:28:55',18,2,1),(84,'2017-08-04 00:05:57',1,3,1),(85,'2017-08-04 00:06:12',18,1,1),(86,'2017-08-04 00:06:57',18,2,1),(87,'2017-08-04 00:07:02',18,2,1),(88,'2017-08-04 00:07:19',18,2,1),(89,'2017-08-04 00:23:09',18,2,1),(90,'2017-08-05 18:18:46',23,1,3),(91,'2017-08-05 18:18:59',23,1,1),(92,'2017-08-05 18:19:03',23,1,1),(93,'2017-08-05 18:24:32',3,3,1),(94,'2017-08-05 18:24:48',3,3,1),(95,'2017-08-05 18:25:03',19,3,1),(97,'2017-08-06 23:01:40',12,1,1),(98,'2016-05-29 17:29:27',1,3,1),(99,'2015-05-29 17:29:27',1,3,1),(100,'2016-05-20 17:29:27',1,3,1);
/*!40000 ALTER TABLE `baixa_acesso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cliente` (
  `ID_CLIENTE` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NOME` varchar(100) NOT NULL,
  `CELULAR` varchar(50) DEFAULT NULL,
  `DATA_ANIVERSARIO` date NOT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `DATA_INICIO` date NOT NULL,
  `ORIGEM` varchar(50) DEFAULT NULL,
  `SEXO` tinyint(2) DEFAULT NULL COMMENT '0 = masculino, 1 = feminino',
  PRIMARY KEY (`ID_CLIENTE`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (1,'Caique Fialho Macedo','(11)99107-0211','2017-05-03','caiquemacedof@yahoo.com.br','2017-06-01','2',0),(2,'Roberto Pereira de Macedo','(11)99107-0211','2017-12-08','roberto@yahoo.com.br','2017-06-01','2',0),(3,'Angêlica dos Santos Cardoso','(11)99107-0211','2017-09-09','angelica@yahoo.com.br','2017-06-01','2',1),(4,'Matheus Pirês Henrique','(11)99107-0211','2017-10-12','matheus@yahoo.com.br','2017-06-01','2',0),(5,'Afonso de souza','(11)99107-0211','2017-12-08','afonso@yahoo.com.br','2017-06-01','2',0),(6,'Guilherme dos Santos Fialho','(11)99107-0211','2017-11-29','guilerme@yahoo.com.br','2017-06-01','2',0),(7,'Pedro Henrique','(11)99107-0211','2017-07-14','pedro@yahoo.com.br','2017-06-01','2',0),(8,'Willian Pedreira','(11)99107-0211','2017-12-15','willian@yahoo.com.br','2017-06-01','2',0),(9,'Felipe França','(11)99107-0211','1995-12-14','felipe@yahoo.com.br','2017-06-01','2',0),(10,'Kelly Caciatori','(11)99107-0211','1834-06-02','kelly@yahoo.com.br','2017-06-01','2',1),(11,'Vinicius Penha Pita','(11)99107-0211','1890-05-03','vicniuspenha@yahoo.com.br','2017-06-01','2',0),(12,'Eduardo Nishida','(11)99107-0211','1999-01-09','edu.nishida@yahoo.com.br','2017-06-01','2',0),(13,'Eweerton diaz','(11)99107-0211','1968-12-18','eweerton@yahoo.com.br','2017-06-01','2',0),(14,'Samuel Aroldo','(11)99107-0211','1980-03-17','samuel@yahoo.com.br','2017-06-01','2',0),(15,'Bruna Santos Meira Pinto','(11)99107-0211','1996-09-04','bruncasantos@yahoo.com.br','2017-06-01','2',1),(16,'Franciele Almeida','(11)99107-0211','1987-06-12','franciele33@yahoo.com.br','2017-06-01','2',1),(17,'Vinicius Almeida','(11)99107-0211','1996-09-04','vinicius_almeida@yahoo.com.br','2017-06-01','2',0),(18,'Adriano Verdeira Penha','(11)99107-0211','1995-12-14','adriano22@yahoo.com.br','2017-06-01','2',0),(19,'Angelica da silvia','(11)99107-0211','1994-04-22','angelica1326@yahoo.com.br','2017-06-01','2',1),(20,'João Carlos','(11)99107-0211','1964-05-23','joao_carlos01@yahoo.com.br','2017-06-01','2',0),(21,'Julio Cesar Araujo dos Santos Junior','(11)99107-0211','1978-08-28','juliocesar.13@yahoo.com.br','2017-06-01','2',0),(22,'Ronaldo Pita','(11)99107-0211','1964-05-23','ronaldo.jogador@yahoo.com.br','2017-06-01','2',0),(23,'Alok Bulster','(11)99107-0211','1968-12-18','alok_buster@yahoo.com.br','2017-06-01','2',0),(24,'David Guetta','(11)99107-0211','2017-05-03','david_guetta@yahoo.com.br','2017-06-01','2',0),(25,'Giulia Fialho Macedo','(11)99107-0211','1998-07-28','giulia@yahoo.com.br','2017-06-01','2',1),(26,'Fabrizio Caciatori','(11)99107-0211','1877-12-27','fabrizio_caci@yahoo.com.br','2017-06-01','2',0),(27,'Bruno dos Santos','(11)99107-0211','1955-02-23','bruna_santos@yahoo.com.br','2017-06-01','2',0),(28,'Cleusa Fialho','(11)99107-0211','1995-12-14','fialho_cleusa@yahoo.com.br','2017-06-01','2',1),(31,'vanderson','11997612631','2000-06-06','vanderson@yahoo.com.br','2017-05-31','1',0),(32,'Douglas dos Santos','11951920633','1987-06-12','douglasteste@gmail.com','2017-08-05','3',0),(33,'maria das graças santos','11951920633','1995-12-14','maricadam@hotmail.com','2017-08-07','4',1);
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `datahora` datetime DEFAULT NULL,
  `descricao` text,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=202 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log`
--

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
INSERT INTO `log` VALUES (1,1,'2017-03-10 21:35:05','Usuário do ID: 1, com o nome: admin logou no sistema.'),(2,1,'2017-03-10 21:35:10','Usuário do ID: 1, com o nome: admin logou no sistema.'),(3,1,'2017-03-10 21:42:46','Usuário do ID: 1, com o nome: admin logou no sistema.'),(4,1,'2017-03-10 22:06:08','Usuário do ID: 1, com o nome: admin logou no sistema.'),(5,1,'2017-03-10 22:17:45','Usuário do ID: 1, com o nome: admin logou no sistema.'),(6,1,'2017-03-10 23:00:09','Usuário do ID: 1, com o nome: admin logou no sistema.'),(7,1,'2017-03-10 23:18:49','Usuário do ID: 1, com o nome: admin logou no sistema.'),(8,1,'2017-03-10 23:24:56','Usuário do ID: 1, com o nome: admin logou no sistema.'),(9,1,'2017-03-10 23:31:28','Usuário do ID: 1, com o nome: admin logou no sistema.'),(10,1,'2017-03-10 23:33:43','Usuário do ID: 1, com o nome: admin logou no sistema.'),(11,1,'2017-03-10 23:36:27','Usuário do ID: 1, com o nome: admin logou no sistema.'),(12,1,'2017-03-11 00:22:20','Usuário do ID: 1, com o nome: admin logou no sistema.'),(13,1,'2017-03-11 00:45:29','Usuário do ID: 1, com o nome: admin logou no sistema.'),(14,1,'2017-03-11 00:45:40','Usuário do ID: 1, com o nome: admin logou no sistema.'),(15,1,'2017-03-11 00:47:32','Usuário do ID: 1, com o nome: admin logou no sistema.'),(16,1,'2017-03-11 00:47:35','Usuário do ID: 1, com o nome: admin logou no sistema.'),(17,1,'2017-03-11 00:47:38','Usuário do ID: 1, com o nome: admin logou no sistema.'),(18,1,'2017-03-11 00:48:25','Usuário do ID: 1, com o nome: admin logou no sistema.'),(19,1,'2017-03-11 00:48:54','Usuário do ID: 1, com o nome: admin logou no sistema.'),(20,1,'2017-03-11 00:49:45','Usuário do ID: 1, com o nome: admin logou no sistema.'),(21,1,'2017-03-11 00:52:06','Usuário do ID: 1, com o nome: admin logou no sistema.'),(22,1,'2017-03-11 00:52:13','Usuário do ID: 1, com o nome: admin logou no sistema.'),(23,1,'2017-03-11 00:53:35','Usuário do ID: 1, com o nome: admin logou no sistema.'),(24,1,'2017-03-11 00:53:47','Usuário do ID: 1, com o nome: admin logou no sistema.'),(25,1,'2017-03-11 00:54:37','Usuário do ID: 1, com o nome: admin logou no sistema.'),(26,1,'2017-03-11 00:54:46','Usuário do ID: 1, com o nome: admin logou no sistema.'),(27,1,'2017-03-11 00:54:56','Usuário do ID: 1, com o nome: admin logou no sistema.'),(28,1,'2017-03-11 00:57:14','Usuário do ID: 1, com o nome: admin logou no sistema.'),(29,1,'2017-03-11 00:57:49','Usuário do ID: 1, com o nome: admin logou no sistema.'),(30,1,'2017-03-11 00:57:58','Usuário do ID: 1, com o nome: admin logou no sistema.'),(31,1,'2017-03-11 00:58:24','Usuário do ID: 1, com o nome: admin logou no sistema.'),(32,1,'2017-03-11 01:00:15','Usuário do ID: 1, com o nome: admin logou no sistema.'),(33,1,'2017-03-11 01:00:27','Usuário do ID: 1, com o nome: admin logou no sistema.'),(34,1,'2017-03-11 01:02:09','Usuário do ID: 1, com o nome: admin logou no sistema.'),(35,1,'2017-03-11 01:02:37','Usuário do ID: 1, com o nome: admin logou no sistema.'),(36,1,'2017-03-11 01:02:56','Usuário do ID: 1, com o nome: admin logou no sistema.'),(37,1,'2017-03-11 01:03:16','Usuário do ID: 1, com o nome: admin logou no sistema.'),(38,1,'2017-03-11 01:11:32','Usuário do ID: 1, com o nome: admin logou no sistema.'),(39,1,'2017-03-11 01:11:40','Usuário do ID: 1, com o nome: admin logou no sistema.'),(40,1,'2017-03-11 10:32:07','Usuário do ID: 1, com o nome: admin logou no sistema.'),(41,1,'2017-03-11 10:32:13','Usuário do ID: 1, com o nome: admin logou no sistema.'),(42,1,'2017-03-11 10:32:17','Usuário do ID: 1, com o nome: admin logou no sistema.'),(43,1,'2017-03-11 10:34:47','Usuário do ID: 1, com o nome: admin logou no sistema.'),(44,1,'2017-03-11 10:34:52','Usuário do ID: 1, com o nome: admin logou no sistema.'),(45,1,'2017-03-11 10:41:31','Usuário do ID: 1, com o nome: admin logou no sistema.'),(46,1,'2017-03-11 10:41:48','Usuário do ID: 1, com o nome: admin logou no sistema.'),(47,1,'2017-03-11 10:52:18','Usuário do ID: 1, com o nome: admin logou no sistema.'),(48,1,'2017-03-11 13:00:15','Usuário do ID: 1, com o nome: admin logou no sistema.'),(49,1,'2017-03-11 13:00:21','Usuário do ID: 1, com o nome: admin logou no sistema.'),(50,1,'2017-03-11 13:45:20','Usuário do ID: 1, com o nome: admin logou no sistema.'),(51,1,'2017-03-11 13:53:05','Usuário do ID: 1, com o nome: admin logou no sistema.'),(52,1,'2017-03-11 14:10:42','Usuário do ID: 1, com o nome: admin logou no sistema.'),(53,1,'2017-03-11 14:27:41','Usuário do ID: 1, com o nome: admin logou no sistema.'),(54,2,'2017-03-11 15:22:50','Usuário do ID: 2, com o nome: admin2 logou no sistema.'),(55,4,'2017-03-11 15:23:11','Usuário do ID: 4, com o nome: admin3 logou no sistema.'),(56,4,'2017-03-11 15:27:07','ID: 4 Criou uma nova conta de usuário.'),(57,4,'2017-03-11 15:30:28','ID: 4 - Criou uma nova conta de usuário.'),(58,5,'2017-03-11 15:33:44','Usuário do ID: 5, com o nome: admin5 logou no sistema.'),(59,5,'2017-03-11 15:35:42','ID: 5 - Criou uma nova conta de usuário.'),(60,5,'2017-03-11 15:36:11','ID: 5 - Criou uma nova conta de usuário.'),(61,5,'2017-03-11 15:38:47','ID: 5 - Criou uma nova conta de usuário.'),(62,10,'2017-03-11 15:38:51','Usuário do ID: 10, com o nome: asdas logou no sistema.'),(63,10,'2017-03-11 15:41:39','Usuário do ID: 10, com o nome: asdas logou no sistema.'),(64,1,'2017-03-11 15:53:54','Usuário do ID: 1, com o nome: admin logou no sistema.'),(65,1,'2017-03-11 16:00:02','Usuário do ID: 1, com o nome: admin logou no sistema.'),(66,1,'2017-03-12 12:08:45','Usuário do ID: 1, com o nome: admin logou no sistema.'),(67,1,'2017-03-12 13:23:07','ID: 1 - Criou uma nova conta de usuário.'),(68,11,'2017-03-12 13:23:16','Usuário do ID: 11, com o nome: admin logou no sistema.'),(69,11,'2017-03-12 13:28:31','Usuário do ID: 11, com o nome: admin logou no sistema.'),(70,1,'2017-03-12 16:18:33','Usuário do ID: 1, com o nome: admin logou no sistema.'),(71,1,'2017-03-12 16:18:35','Usuário do ID: 1, com o nome: admin logou no sistema.'),(72,1,'2017-03-14 18:31:33','Usuário do ID: 1, com o nome: admin logou no sistema.'),(73,1,'2017-03-14 18:35:23','ID: 1 - Criou uma nova conta de usuário.'),(74,12,'2017-03-14 18:36:38','Usuário do ID: 12, com o nome: asd logou no sistema.'),(75,1,'2017-03-14 18:45:26','Usuário do ID: 1, com o nome: admin logou no sistema.'),(76,1,'2017-03-14 18:53:15','Usuário do ID: 1, com o nome: admin logou no sistema.'),(77,1,'2017-03-14 19:33:05','Usuário do ID: 1, com o nome: admin logou no sistema.'),(78,1,'2017-03-14 19:44:37','ID: 1 - Criou uma nova conta de usuário.'),(79,1,'2017-03-14 20:01:04','Usuário do ID: 1, com o nome: admin logou no sistema.'),(80,1,'2017-03-14 20:09:25','ID: 1 - Criou uma nova conta de usuário.'),(81,14,'2017-03-14 20:09:30','Usuário do ID: 14, com o nome: caicao logou no sistema.'),(82,14,'2017-03-14 20:09:38','Usuário do ID: 14, com o nome: caicao logou no sistema.'),(83,1,'2017-03-14 20:09:58','Usuário do ID: 1, com o nome: admin logou no sistema.'),(84,1,'2017-03-14 20:10:05','Usuário do ID: 1, com o nome: admin logou no sistema.'),(85,1,'2017-03-14 20:10:11','Usuário do ID: 1, com o nome: admin logou no sistema.'),(86,1,'2017-03-14 20:14:54','ID: 1 - Criou uma nova conta de usuário.'),(87,15,'2017-03-14 20:14:58','Usuário do ID: 15, com o nome: vinicius logou no sistema.'),(88,15,'2017-03-14 20:15:04','Usuário do ID: 15, com o nome: vinicius logou no sistema.'),(89,15,'2017-03-14 20:15:08','Usuário do ID: 15, com o nome: vinicius logou no sistema.'),(90,15,'2017-03-14 20:15:25','Usuário do ID: 15, com o nome: vinicius logou no sistema.'),(91,15,'2017-03-14 20:15:47','Usuário do ID: 15, com o nome: vinicius logou no sistema.'),(92,15,'2017-03-14 20:19:17','Usuário do ID: 15, com o nome: vinicius logou no sistema.'),(93,15,'2017-03-14 20:20:20','Usuário do ID: 15, com o nome: vinicius logou no sistema.'),(94,15,'2017-03-14 20:32:38','Usuário do ID: 15, com o nome: vinicius logou no sistema.'),(95,1,'2017-03-14 20:40:15','Usuário do ID: 1, com o nome: admin logou no sistema.'),(96,1,'2017-03-17 00:13:08','Usuário do ID: 1, com o nome: admin logou no sistema.'),(97,1,'2017-03-17 00:44:59','Usuário do ID: 1, com o nome: admin logou no sistema.'),(98,1,'2017-03-17 19:38:57','Usuário do ID: 1, com o nome: admin logou no sistema.'),(99,1,'2017-03-17 19:41:17','Usuário do ID: 1, com o nome: admin logou no sistema.'),(100,1,'2017-03-17 20:20:39','Usuário do ID: 1, com o nome: admin logou no sistema.'),(101,1,'2017-03-17 21:03:06','ID: 1 - Criou uma nova conta de usuário.'),(102,16,'2017-03-17 21:03:18','Usuário do ID: 16, com o nome: eduardo logou no sistema.'),(103,1,'2017-03-17 21:03:26','Usuário do ID: 1, com o nome: admin logou no sistema.'),(104,1,'2017-03-17 21:29:12','Usuário do ID: 1, com o nome: admin logou no sistema.'),(105,1,'2017-03-17 22:45:14','Usuário do ID: 1, com o nome: admin logou no sistema.'),(106,1,'2017-03-17 22:56:14','Usuário do ID: 1, com o nome: admin logou no sistema.'),(107,1,'2017-03-17 23:04:14','Usuário do ID: 1, com o nome: admin logou no sistema.'),(108,1,'2017-03-17 23:04:29','Usuário do ID: 1, com o nome: admin logou no sistema.'),(109,1,'2017-03-17 23:04:41','Usuário do ID: 1, com o nome: admin logou no sistema.'),(110,1,'2017-03-17 23:08:00','Usuário do ID: 1, com o nome: admin logou no sistema.'),(111,1,'2017-03-17 23:32:20','Usuário do ID: 1, com o nome: admin logou no sistema.'),(112,1,'2017-03-18 00:28:34','Usuário do ID: 1, com o nome: admin logou no sistema.'),(113,1,'2017-03-18 10:57:34','Usuário do ID: 1, com o nome: admin logou no sistema.'),(114,1,'2017-03-18 10:59:18','Usuário do ID: 1, com o nome: admin logou no sistema.'),(115,1,'2017-03-19 23:14:22','Usuário do ID: 1, com o nome: admin logou no sistema.'),(116,1,'2017-03-19 23:17:47','Usuário do ID: 1, com o nome: admin logou no sistema.'),(117,1,'2017-03-20 20:42:45','Usuário do ID: 1, com o nome: admin logou no sistema.'),(118,1,'2017-03-23 21:33:35','Usuário do ID: 1, com o nome: admin logou no sistema.'),(119,1,'2017-03-24 23:36:53','Usuário do ID: 1, com o nome: admin logou no sistema.'),(120,1,'2017-03-25 00:40:44','Usuário do ID: 1, com o nome: admin logou no sistema.'),(121,1,'2017-03-25 00:57:49','Usuário do ID: 1, com o nome: admin logou no sistema.'),(122,1,'2017-03-25 00:57:54','Usuário do ID: 1, com o nome: admin logou no sistema.'),(123,1,'2017-03-25 00:58:02','Usuário do ID: 1, com o nome: admin logou no sistema.'),(124,1,'2017-03-25 12:08:28','Usuário do ID: 1, com o nome: admin logou no sistema.'),(125,1,'2017-03-25 12:08:30','Usuário do ID: 1, com o nome: admin logou no sistema.'),(126,1,'2017-03-25 12:13:04','Usuário do ID: 1, com o nome: admin logou no sistema.'),(127,1,'2017-03-25 12:15:35','Usuário do ID: 1, com o nome: admin logou no sistema.'),(128,1,'2017-03-25 12:19:07','Usuário do ID: 1, com o nome: admin logou no sistema.'),(129,1,'2017-03-25 12:46:58','Usuário do ID: 1, com o nome: admin logou no sistema.'),(130,1,'2017-03-25 13:27:23','Usuário do ID: 1, com o nome: admin logou no sistema.'),(131,1,'2017-03-25 14:19:14','Usuário do ID: 1, com o nome: admin logou no sistema.'),(132,1,'2017-03-25 15:39:45','Usuário do ID: 1, com o nome: admin logou no sistema.'),(133,1,'2017-03-25 15:39:47','Usuário do ID: 1, com o nome: admin logou no sistema.'),(134,1,'2017-03-25 15:47:29','Usuário do ID: 1, com o nome: admin logou no sistema.'),(135,1,'2017-03-25 17:06:16','Usuário do ID: 1, com o nome: admin logou no sistema.'),(136,1,'2017-03-25 17:26:49','Usuário do ID: 1, com o nome: admin logou no sistema.'),(137,1,'2017-03-25 17:29:50','Usuário do ID: 1, com o nome: admin logou no sistema.'),(138,1,'2017-03-25 17:29:58','ID: 1 - Criou uma nova conta de usuário.'),(139,17,'2017-03-25 17:30:04','Usuário do ID: 17, com o nome: roberto logou no sistema.'),(140,1,'2017-03-25 17:51:35','Usuário do ID: 1, com o nome: admin logou no sistema.'),(141,1,'2017-03-25 17:52:11','Usuário do ID: 1, com o nome: admin logou no sistema.'),(142,1,'2017-03-25 18:26:25','Usuário do ID: 1, com o nome: admin logou no sistema.'),(143,1,'2017-03-25 19:39:18','Usuário do ID: 1, com o nome: admin logou no sistema.'),(144,1,'2017-03-25 19:48:50','Usuário do ID: 1, com o nome: admin logou no sistema.'),(145,1,'2017-03-25 19:50:52','Usuário do ID: 1, com o nome: admin logou no sistema.'),(146,1,'2017-03-25 19:54:59','Usuário do ID: 1, com o nome: admin logou no sistema.'),(147,1,'2017-03-25 23:20:01','ID: 1 - Criou uma nova conta de usuário.'),(148,18,'2017-03-25 23:20:10','Usuário do ID: 18, com o nome: caique logou no sistema.'),(149,1,'2017-03-26 10:10:08','Usuário do ID: 1, com o nome: admin logou no sistema.'),(150,1,'2017-03-26 23:19:51','Usuário do ID: 1, com o nome: admin logou no sistema.'),(151,1,'2017-03-27 09:04:34','Usuário do ID: 1, com o nome: admin logou no sistema.'),(152,1,'2017-03-27 20:28:52','Usuário do ID: 1, com o nome: admin logou no sistema.'),(153,1,'2017-03-27 20:52:31','ID: 1 - Criou uma nova conta de usuário.'),(154,1,'2017-03-27 20:53:47','ID: 1 - Criou uma nova conta de usuário.'),(155,1,'2017-03-27 20:53:52','ID: 1 - Criou uma nova conta de usuário.'),(156,1,'2017-03-27 21:43:46','Usuário do ID: 1, com o nome: admin logou no sistema.'),(157,1,'2017-03-28 18:29:45','Usuário do ID: 1, com o nome: admin logou no sistema.'),(158,1,'2017-03-28 18:34:41','Usuário do ID: 1, com o nome: admin logou no sistema.'),(159,1,'2017-03-28 20:42:43','Usuário do ID: 1, com o nome: admin logou no sistema.'),(160,1,'2017-03-28 21:33:14','Usuário do ID: 1, com o nome: admin logou no sistema.'),(161,1,'2017-03-29 00:44:58','Usuário do ID: 1, com o nome: admin logou no sistema.'),(162,1,'2017-03-29 12:53:19','Usuário do ID: 1, com o nome: admin logou no sistema.'),(163,1,'2017-03-29 13:02:00','Usuário do ID: 1, com o nome: admin logou no sistema.'),(164,1,'2017-03-29 17:49:44','Usuário do ID: 1, com o nome: admin logou no sistema.'),(165,1,'2017-03-29 17:51:27','ID: 1 - Criou uma nova conta de usuário.'),(166,22,'2017-03-29 17:51:37','Usuário do ID: 22, com o nome: admin logou no sistema.'),(167,1,'2017-03-29 20:11:01','Usuário do ID: 1, com o nome: admin logou no sistema.'),(168,1,'2017-03-30 21:33:16','Usuário do ID: 1, com o nome: admin logou no sistema.'),(169,1,'2017-03-31 00:39:47','Usuário do ID: 1, com o nome: admin logou no sistema.'),(170,1,'2017-03-31 20:17:46','Usuário do ID: 1, com o nome: admin logou no sistema.'),(171,1,'2017-04-01 12:02:42','Usuário do ID: 1, com o nome: admin logou no sistema.'),(172,1,'2017-04-01 14:20:15','Usuário do ID: 1, com o nome: admin logou no sistema.'),(173,1,'2017-04-01 18:29:07','Usuário do ID: 1, com o nome: admin logou no sistema.'),(174,1,'2017-04-01 20:34:18','Usuário do ID: 1, com o nome: admin logou no sistema.'),(175,1,'2017-04-01 21:27:05','Usuário do ID: 1, com o nome: admin logou no sistema.'),(176,1,'2017-04-01 23:15:31','Usuário do ID: 1, com o nome: admin logou no sistema.'),(177,1,'2017-04-04 21:54:16','Usuário do ID: 1, com o nome: admin logou no sistema.'),(178,1,'2017-04-06 00:20:15','Usuário do ID: 1, com o nome: admin logou no sistema.'),(179,1,'2017-04-06 20:18:54','Usuário do ID: 1, com o nome: admin logou no sistema.'),(180,1,'2017-04-07 00:08:50','Usuário do ID: 1, com o nome: admin logou no sistema.'),(181,1,'2017-04-07 00:08:56','Usuário do ID: 1, com o nome: admin logou no sistema.'),(182,1,'2017-04-07 23:22:20','Usuário do ID: 1, com o nome: admin logou no sistema.'),(183,1,'2017-04-08 03:35:33','Usuário do ID: 1, com o nome: admin logou no sistema.'),(184,1,'2017-04-08 12:27:16','Usuário do ID: 1, com o nome: admin logou no sistema.'),(185,1,'2017-04-09 18:55:03','Usuário do ID: 1, com o nome: admin logou no sistema.'),(186,1,'2017-04-09 23:52:27','Usuário do ID: 1, com o nome: admin logou no sistema.'),(187,1,'2017-04-18 21:21:09','Usuário do ID: 1, com o nome: admin logou no sistema.'),(188,1,'2017-04-19 23:44:14','Usuário do ID: 1, com o nome: admin logou no sistema.'),(189,1,'2017-04-23 12:36:46','Usuário do ID: 1, com o nome: admin logou no sistema.'),(190,1,'2017-04-23 14:48:25','Usuário do ID: 1, com o nome: admin logou no sistema.'),(191,1,'2017-04-23 23:10:02','Usuário do ID: 1, com o nome: admin logou no sistema.'),(192,1,'2017-05-12 00:36:12','Usuário do ID: 1, com o nome: admin logou no sistema.'),(193,1,'2017-05-12 18:55:49','Usuário do ID: 1, com o nome: admin logou no sistema.'),(194,1,'2017-05-12 19:09:14','Usuário do ID: 1, com o nome: admin logou no sistema.'),(195,1,'2017-05-12 19:37:55','Usuário do ID: 1, com o nome: admin logou no sistema.'),(196,1,'2017-05-12 19:38:38','Usuário do ID: 1, com o nome: admin logou no sistema.'),(197,1,'2017-05-14 23:57:31','Usuário do ID: 1, com o nome: admin logou no sistema.'),(198,1,'2017-05-18 19:08:03','Usuário do ID: 1, com o nome: admin logou no sistema.'),(199,1,'2017-05-20 01:46:26','Usuário do ID: 1, com o nome: admin logou no sistema.'),(200,1,'2017-05-25 00:49:16','Usuário do ID: 1, com o nome: admin logou no sistema.'),(201,1,'2017-05-25 01:11:06','Usuário do ID: 1, com o nome: admin logou no sistema.');
/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_acesso`
--

DROP TABLE IF EXISTS `tipo_acesso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_acesso` (
  `ID_TIPO_ACESSO` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NOME` varchar(30) DEFAULT NULL,
  `VALOR_TIPO_ACESSO` double NOT NULL,
  PRIMARY KEY (`ID_TIPO_ACESSO`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_acesso`
--

LOCK TABLES `tipo_acesso` WRITE;
/*!40000 ALTER TABLE `tipo_acesso` DISABLE KEYS */;
INSERT INTO `tipo_acesso` VALUES (1,'Shake',15),(2,'Sopa',16),(3,'NutriSoup',17);
/*!40000 ALTER TABLE `tipo_acesso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_contato`
--

DROP TABLE IF EXISTS `tipo_contato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_contato` (
  `ID_TIPO_CONTATO` int(11) NOT NULL AUTO_INCREMENT,
  `NOME` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`ID_TIPO_CONTATO`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_contato`
--

LOCK TABLES `tipo_contato` WRITE;
/*!40000 ALTER TABLE `tipo_contato` DISABLE KEYS */;
INSERT INTO `tipo_contato` VALUES (1,'Indicação'),(2,'Panfleto'),(3,'Facebook'),(4,'WhatsApp'),(5,'Google'),(6,'Nenhum');
/*!40000 ALTER TABLE `tipo_contato` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(200) NOT NULL,
  `senha` varchar(200) NOT NULL,
  `tipo` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'admin','202cb962ac59075b964b07152d234b70',1),(2,'admin2','c84258e9c39059a89ab77d846ddab909',1),(3,'admin2','c84258e9c39059a89ab77d846ddab909',1),(4,'admin3','32cacb2f994f6b42183a1300d9a3e8d6',1),(5,'admin5','26a91342190d515231d7238b0c5438e1',1),(6,'admin5','26a91342190d515231d7238b0c5438e1',1),(7,'admin4','fc1ebc848e31e0a68e868432225e3c82',1),(8,'admin6','fc1ebc848e31e0a68e868432225e3c82',1),(9,'admin9','8762eb814817cc8dcbb3fb5c5fcd52e0',1),(10,'asdas','0aa1ea9a5a04b78d4581dd6d17742627',1),(11,'admin','0192023a7bbd73250516f069df18b500',1),(12,'asd','bfd59291e825b5f2bbf1eb76569f8fe7',1),(13,'asd','7815696ecbf1c96e6894b779456d330e',1),(14,'caicao','8dae2a10a30ab1879b737521878dd392',1),(15,'vinicius','7fa81ff5e6a88a34ca2392240268c68f',1),(16,'eduardo','202cb962ac59075b964b07152d234b70',1),(17,'roberto','c1bfc188dba59d2681648aa0e6ca8c8e',1),(18,'caique','0192023a7bbd73250516f069df18b500',1),(19,'admin1','d60e277bcd48679aa87347200a4be3f3',1),(20,'admin','eccbc87e4b5ce2fe28308fd9f2a7baf3',1),(21,'3','eccbc87e4b5ce2fe28308fd9f2a7baf3',1),(22,'admin','c84258e9c39059a89ab77d846ddab909',1);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venda_acesso`
--

DROP TABLE IF EXISTS `venda_acesso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `venda_acesso` (
  `ID_VENDA_ACESSO` int(11) NOT NULL AUTO_INCREMENT,
  `DATA_VENDA` datetime NOT NULL,
  `ID_TIPO_ACESSO` int(11) DEFAULT NULL,
  `QTDE_ACESSO` int(11) DEFAULT NULL,
  `ID_CLIENTE` int(11) DEFAULT NULL,
  `valor_venda_acesso` double NOT NULL,
  PRIMARY KEY (`ID_VENDA_ACESSO`),
  KEY `ID_TIPO_ACESSO` (`ID_TIPO_ACESSO`),
  KEY `ID_CLIENTE` (`ID_CLIENTE`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venda_acesso`
--

LOCK TABLES `venda_acesso` WRITE;
/*!40000 ALTER TABLE `venda_acesso` DISABLE KEYS */;
INSERT INTO `venda_acesso` VALUES (1,'2017-05-29 17:29:09',3,100,1,1700),(2,'2017-06-01 18:03:33',3,10,12,170),(14,'2017-08-18 12:33:47',3,2,28,34),(4,'2017-05-31 19:47:10',1,15,18,225),(5,'2017-05-31 19:47:29',2,9,18,144),(6,'2017-06-29 00:37:21',1,2,18,30),(7,'2017-07-13 20:43:26',3,2,21,34),(8,'2017-08-04 00:23:45',3,90,18,1530),(9,'2017-08-05 18:18:38',1,12,23,180),(10,'2017-08-05 18:24:28',3,10,3,170),(11,'2017-08-05 18:24:58',3,10,19,170),(12,'2017-08-06 22:46:42',1,2,12,30),(13,'2017-08-06 22:46:53',1,2,12,30),(15,'2017-08-18 12:35:17',3,3,9,51),(16,'2017-08-18 12:45:54',3,2,9,34),(17,'2017-08-18 12:46:44',3,1,9,17);
/*!40000 ALTER TABLE `venda_acesso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'real_shake'
--

--
-- Dumping routines for database 'real_shake'
--
/*!50003 DROP PROCEDURE IF EXISTS `consumoAcesso` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consumoAcesso`(IN cliente_id INT, IN tipo_acesso_id INT)
BEGIN
	
    SELECT 
    (
        SELECT IFNULL(SUM(qtde_acesso), 0)
		FROM baixa_acesso
		WHERE id_cliente = cliente_id AND id_tipo_acesso = tipo_acesso_id
	) AS consumido,
    
    (
		SELECT IFNULL(SUM(qtde_acesso), 0)
		FROM venda_acesso
		where id_cliente = cliente_id AND id_tipo_acesso = tipo_acesso_id
	) AS total;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `frequencia_pessoa` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `frequencia_pessoa`()
BEGIN
CREATE TEMPORARY TABLE IF NOT EXISTS agrupado_por_data_cli AS
(
	SELECT
		data_hora_baixa_acesso
	FROM baixa_acesso 
	group by id_cliente,
    DATE_FORMAT(data_hora_baixa_acesso,'%d/%m/%Y')
);

select
	DATE_FORMAT(data_hora_baixa_acesso,'%d/%m/%Y') as data_periodo,
	count(data_hora_baixa_acesso) as frequencia_do_dia
from agrupado_por_data_cli
group by DATE_FORMAT(data_hora_baixa_acesso,'%d/%m/%Y')
order by data_hora_baixa_acesso;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `maioresFrequencias` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `maioresFrequencias`(IN data_inicial VARCHAR(30), IN data_final VARCHAR(30), IN limite INT)
BEGIN

DECLARE cliente_id INT;
DECLARE i INT DEFAULT 1;
DECLARE registro INT DEFAULT 0;

DROP TABLE IF EXISTS frequencias;
CREATE TEMPORARY TABLE IF NOT EXISTS frequencias
(
	id_cliente  int,
    nome 		varchar(256),
    frequencia  int
);	
    
    /*----------------------------------------------------------------------------------
	---- Pega o total de frequencia ID por ID e insere na tabela frequencia (temporária)
    ------------------------------------------------------------------------------------
    */
    
    
	WHILE ((SELECT id_cliente FROM cliente ORDER BY id_cliente LIMIT registro, 1) != 0) DO
		
        SET cliente_id = (SELECT id_cliente FROM cliente ORDER BY id_cliente LIMIT registro, 1);
        
        INSERT INTO frequencias (id_cliente, nome, frequencia)
			SELECT
				cliente_id,
				cli.nome,
				COUNT(*)
			FROM baixa_acesso as ba
            INNER JOIN cliente as cli
            ON ba.id_cliente = cli.id_cliente
            
			WHERE ba.id_cliente = cliente_id
            AND ba.data_hora_baixa_acesso
            BETWEEN data_inicial AND data_final
            
            GROUP BY ba.id_cliente;
		
        SET registro = registro + 1;
		SET i = i + 1;
    END WHILE;

SELECT * FROM frequencias
ORDER BY frequencia DESC
LIMIT limite;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-08-18 20:02:22
