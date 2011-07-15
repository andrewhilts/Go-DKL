-- MySQL dump 10.13  Distrib 5.1.54, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: thesis
-- ------------------------------------------------------
-- Server version	5.1.54-1ubuntu4

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
-- Table structure for table `claim_class_types`
--

DROP TABLE IF EXISTS `claim_class_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `claim_class_types` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `claim_classes`
--

DROP TABLE IF EXISTS `claim_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `claim_classes` (
  `claim_id` varchar(102) CHARACTER SET latin1 DEFAULT NULL,
  `class_type` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `claim_classes2`
--

DROP TABLE IF EXISTS `claim_classes2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `claim_classes2` (
  `claim_id` varchar(102) CHARACTER SET latin1 DEFAULT NULL,
  `class_type` varchar(50) CHARACTER SET latin1 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `claim_types`
--

DROP TABLE IF EXISTS `claim_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `claim_types` (
  `type` varchar(50) NOT NULL DEFAULT '',
  `description` text,
  PRIMARY KEY (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `claims`
--

DROP TABLE IF EXISTS `claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `claims` (
  `claim_id` varchar(102) NOT NULL,
  `source_claim` varchar(2) NOT NULL,
  `publication` varchar(100) NOT NULL,
  `claim_type` varchar(50) NOT NULL,
  PRIMARY KEY (`claim_id`),
  KEY `claim_type` (`claim_type`),
  CONSTRAINT `claims_ibfk_1` FOREIGN KEY (`claim_type`) REFERENCES `claim_types` (`type`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contexts`
--

DROP TABLE IF EXISTS `contexts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contexts` (
  `context-id` int(10) NOT NULL AUTO_INCREMENT,
  `kind` varchar(80) NOT NULL,
  `scale` varchar(80) DEFAULT NULL,
  `topic` varchar(100) DEFAULT NULL,
  `pubid` int(10) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `description` text,
  `system class` varchar(80) NOT NULL,
  PRIMARY KEY (`context-id`),
  KEY `pubid` (`pubid`),
  KEY `system class` (`system class`),
  CONSTRAINT `contexts_ibfk_1` FOREIGN KEY (`pubid`) REFERENCES `publications` (`publication-id`),
  CONSTRAINT `contexts_ibfk_2` FOREIGN KEY (`system class`) REFERENCES `system-classes` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contribution-types`
--

DROP TABLE IF EXISTS `contribution-types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contribution-types` (
  `type` varchar(20) NOT NULL DEFAULT '',
  `description` text,
  PRIMARY KEY (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `node-types`
--

DROP TABLE IF EXISTS `node-types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `node-types` (
  `name` varchar(80) NOT NULL,
  `description` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nodes`
--

DROP TABLE IF EXISTS `nodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nodes` (
  `name` varchar(80) DEFAULT NULL,
  `type` varchar(80) DEFAULT NULL,
  `description` text,
  `id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=547 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nodes_old`
--

DROP TABLE IF EXISTS `nodes_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nodes_old` (
  `name` varchar(80) NOT NULL,
  `type` varchar(80) NOT NULL,
  `description` text,
  `id` int(10) DEFAULT NULL,
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pgclg`
--

DROP TABLE IF EXISTS `pgclg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pgclg` (
  `goal_class` varchar(50) NOT NULL DEFAULT '',
  `project_goal_id` int(10) NOT NULL DEFAULT '0',
  `library_goal_id` int(10) NOT NULL DEFAULT '0',
  `rationale` text,
  `checked` int(1) DEFAULT NULL,
  PRIMARY KEY (`goal_class`,`project_goal_id`,`library_goal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pgclg_old`
--

DROP TABLE IF EXISTS `pgclg_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pgclg_old` (
  `goal_class` varchar(50) NOT NULL DEFAULT '',
  `project_goal_id` int(10) NOT NULL DEFAULT '0',
  `library_goal_id` int(10) NOT NULL DEFAULT '0',
  `rationale` text,
  `checked` int(1) DEFAULT NULL,
  PRIMARY KEY (`goal_class`,`project_goal_id`,`library_goal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `poop`
--

DROP TABLE IF EXISTS `poop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poop` (
  `test` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`test`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `poop2`
--

DROP TABLE IF EXISTS `poop2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poop2` (
  `library_goal` varchar(80) NOT NULL DEFAULT '',
  `goal_class` varchar(50) NOT NULL DEFAULT '',
  `project_goal_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`library_goal`,`goal_class`,`project_goal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `project_goal_class_library_goal`
--

DROP TABLE IF EXISTS `project_goal_class_library_goal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_goal_class_library_goal` (
  `goal_class` int(10) NOT NULL DEFAULT '0',
  `project_goal_id` int(10) NOT NULL DEFAULT '0',
  `library_goal_id` int(10) NOT NULL DEFAULT '0',
  `rationale` text,
  `checked` int(1) DEFAULT NULL,
  PRIMARY KEY (`goal_class`,`project_goal_id`,`library_goal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `project_goal_class_library_goals`
--

DROP TABLE IF EXISTS `project_goal_class_library_goals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_goal_class_library_goals` (
  `library_goal` varchar(80) NOT NULL DEFAULT '',
  `project_goal` varchar(100) NOT NULL DEFAULT '',
  `goal_class` varchar(50) DEFAULT NULL,
  `project_goal_id` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `project_goal_classes`
--

DROP TABLE IF EXISTS `project_goal_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_goal_classes` (
  `goal_class` varchar(50) NOT NULL DEFAULT '',
  `project_goal_id` int(10) NOT NULL DEFAULT '0',
  `goal_class_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`project_goal_id`,`goal_class_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `project_goals`
--

DROP TABLE IF EXISTS `project_goals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_goals` (
  `project_name` varchar(100) NOT NULL DEFAULT '',
  `goal_name` varchar(100) DEFAULT NULL,
  `id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `name` varchar(100) DEFAULT NULL,
  `context` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `projmod_nodes`
--

DROP TABLE IF EXISTS `projmod_nodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projmod_nodes` (
  `id` int(10) NOT NULL DEFAULT '0',
  `projmod_id` int(10) NOT NULL DEFAULT '0',
  `checked` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`,`projmod_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `projmod_pcg`
--

DROP TABLE IF EXISTS `projmod_pcg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projmod_pcg` (
  `pg` int(10) NOT NULL DEFAULT '0',
  `gc` int(10) NOT NULL DEFAULT '0',
  `lg` int(10) NOT NULL DEFAULT '0',
  `checked` int(1) DEFAULT NULL,
  `projmod_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pg`,`gc`,`lg`,`projmod_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `projmod_rels`
--

DROP TABLE IF EXISTS `projmod_rels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projmod_rels` (
  `parent` int(10) NOT NULL DEFAULT '0',
  `contrib` varchar(20) NOT NULL DEFAULT '',
  `child` int(10) NOT NULL DEFAULT '0',
  `checked` int(1) DEFAULT NULL,
  `contrib_lib` varchar(20) NOT NULL DEFAULT '',
  `claim_lib` varchar(102) NOT NULL DEFAULT '',
  `projmod_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`parent`,`child`,`projmod_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `projmods`
--

DROP TABLE IF EXISTS `projmods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projmods` (
  `projmod_id` int(10) NOT NULL AUTO_INCREMENT,
  `projmod_name` varchar(102) DEFAULT NULL,
  `project_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`projmod_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `proto_design_nodes`
--

DROP TABLE IF EXISTS `proto_design_nodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proto_design_nodes` (
  `proto_design` varchar(100) DEFAULT NULL,
  `node` varchar(80) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `proto_designs`
--

DROP TABLE IF EXISTS `proto_designs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proto_designs` (
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` text,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `publications`
--

DROP TABLE IF EXISTS `publications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publications` (
  `publication-id` int(10) NOT NULL AUTO_INCREMENT,
  `author` varchar(80) NOT NULL,
  `yearpub` varchar(80) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text,
  `context` varchar(80) DEFAULT NULL,
  `system_class` varchar(80) DEFAULT NULL,
  `url` varchar(400) DEFAULT NULL,
  `venue` varchar(200) DEFAULT NULL,
  `authors_full` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`publication-id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `r2`
--

DROP TABLE IF EXISTS `r2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `r2` (
  `relationship_id` int(10) NOT NULL AUTO_INCREMENT,
  `child` int(10) DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `parent` int(10) DEFAULT NULL,
  `context` varchar(50) NOT NULL,
  `description` text,
  `claim_id` varchar(102) NOT NULL,
  PRIMARY KEY (`relationship_id`),
  KEY `parent` (`parent`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `r22`
--

DROP TABLE IF EXISTS `r22`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `r22` (
  `relationship_id` int(10) NOT NULL AUTO_INCREMENT,
  `child` int(10) DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `parent` int(10) DEFAULT NULL,
  `context` varchar(50) NOT NULL,
  `description` text,
  `claim_id` varchar(102) NOT NULL,
  PRIMARY KEY (`relationship_id`),
  KEY `parent` (`parent`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `relationships`
--

DROP TABLE IF EXISTS `relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `relationships` (
  `child` int(10) NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL,
  `parent` int(10) NOT NULL DEFAULT '0',
  `context` varchar(50) NOT NULL,
  `description` text,
  `claim_id` varchar(102) NOT NULL,
  PRIMARY KEY (`parent`,`child`,`type`,`claim_id`),
  KEY `parent` (`parent`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `relationships2`
--

DROP TABLE IF EXISTS `relationships2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `relationships2` (
  `child` int(10) NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL,
  `parent` int(10) NOT NULL DEFAULT '0',
  `context` varchar(50) NOT NULL,
  `description` text,
  `claim_id` varchar(102) NOT NULL,
  PRIMARY KEY (`parent`,`child`,`type`,`claim_id`),
  KEY `parent` (`parent`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `relationships_old`
--

DROP TABLE IF EXISTS `relationships_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `relationships_old` (
  `relationship-id` int(10) NOT NULL AUTO_INCREMENT,
  `child` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  `parent` varchar(50) NOT NULL,
  `context` varchar(50) NOT NULL,
  `description` text,
  `claim_id` varchar(102) NOT NULL,
  PRIMARY KEY (`relationship-id`),
  KEY `parent` (`parent`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `relationships_old2`
--

DROP TABLE IF EXISTS `relationships_old2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `relationships_old2` (
  `relationship_id` int(10) NOT NULL AUTO_INCREMENT,
  `child` int(10) DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `parent` int(10) DEFAULT NULL,
  `context` varchar(50) NOT NULL,
  `description` text,
  `claim_id` varchar(102) NOT NULL,
  PRIMARY KEY (`relationship_id`),
  KEY `parent` (`parent`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stated_goals`
--

DROP TABLE IF EXISTS `stated_goals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stated_goals` (
  `name` varchar(100) DEFAULT NULL,
  `description` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `system-classes`
--

DROP TABLE IF EXISTS `system-classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system-classes` (
  `name` varchar(80) NOT NULL,
  `description` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-07-14 21:30:50
