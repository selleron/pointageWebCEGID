-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Sam 05 Août 2017 à 14:15
-- Version du serveur :  10.1.23-MariaDB-9+deb9u1
-- Version de PHP :  5.6.30-0+deb8u1

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


--
-- Base de données :  `test`
--

-- --------------------------------------------------------

/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `evol_status_user` AFTER UPDATE ON `cegid_user` FOR EACH ROW begin

IF NEW.STATUS <> OLD.STATUS THEN 
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.STATUS, "cegid_user.status.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, OLD.STATUS, "cegid_user_project.status.old") ;

  END IF;

  IF NEW.SOCIETE <> OLD.SOCIETE THEN  
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.SOCIETE, "cegid_user_project.societe.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, OLD.SOCIETE, "cegid_user_project.societe.old") ;
  END IF; 

  IF NEW.DEPART <> OLD.DEPART THEN  
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) 	VALUES (NEW.ID, NEW.DEPART, "cegid_user_project.depart.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) 	VALUES (NEW.ID, OLD.DEPART, "cegid_user_project.depart.old") ;
  END IF;

 IF NEW.ARRIVEE <> OLD.ARRIVEE THEN  
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.ARRIVEE, "cegid_user_project.arrivee.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, OLD.ARRIVEE, "cegid_user_project.arrivee.old") ;
  END IF;

 IF NEW.EMAIL1 <> OLD.EMAIL1 THEN  
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.EMAIL1, "cegid_user_project.email1.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, OLD.EMAIL1, "cegid_user_project.email1.old") ;
  END IF;

  IF NEW.EMAIL2 <> OLD.EMAIL2 THEN  
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.EMAIL2, "cegid_user_project.email2.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, OLD.EMAIL2, "cegid_user_project.email2.old") ;
  END IF;

  IF NEW.TEL1 <> OLD.TEL1 THEN  
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.TEL1, "cegid_user_project.tel1.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, OLD.TEL1, "cegid_user_project.tel1.old") ;
  END IF;

  

  IF NEW.TEL2 <> OLD.TEL2 THEN  
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.TEL2, "cegid_user_project.tel2.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, OLD.TEL2, "cegid_user_project.tel2.old") ;
  END IF;

  IF NEW.GROUPE <> OLD.GROUPE THEN  
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.GROUPE, "cegid_user_project.groupe.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, OLD.GROUPE, "cegid_user_project.groupe.old") ;
  END IF;

    IF NEW.TEAM <> OLD.TEAM THEN  
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.TEAM, "cegid_user_project.team.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, OLD.TEAM, "cegid_user_project.team.old") ;
  END IF;

END */;;

/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `insert_status_user` AFTER INSERT ON `cegid_user` FOR EACH ROW begin
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.STATUS, "cegid_user.status.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.SOCIETE, "cegid_user_project.societe.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.DEPART, "cegid_user_project.depart.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.ARRIVEE, "cegid_user_project.arrivee.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.EMAIL1, "cegid_user_project.email1.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.EMAIL2, "cegid_user_project.email2.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.TEL1, "cegid_user_project.tel1.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.TEL2, "cegid_user_project.tel2.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.GROUPE, "cegid_user_project.groupe.new") ;
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.TEAM, "cegid_user_project.team.new") ;
END */;;



DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

 

--
-- Declaration Modification
--
  
UPDATE `version` SET `DATE` = now(), `value` = '0.44.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.43_vers_0.44', '101', now(), 'add history cegid_user', '0.44.0');

-- INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.35.2 vers_0.1.39.0', '200', now(), 'table users modif champs', '0.1.39.0');
-- UPDATE `version` SET `DATE` = now(), `description` = 'version fichier php minimal', `value` = '0.1.39.0' WHERE `version`.`id` = 'php';


COMMIT;


