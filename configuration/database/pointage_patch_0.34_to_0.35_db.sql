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


DROP TRIGGER IF EXISTS `insert_status_devis`;
DROP TRIGGER IF EXISTS `insert_status_project`;


DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `insert_status_devis` AFTER INSERT ON `cegid_devis_project` FOR EACH ROW begin
INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, "new", "cegid_devis_project");
INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.STATUS_DEVIS, "cegid_devis_project.status_devis");
INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.STATUS_CEGID, "cegid_devis_project.status_cegid");
INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.STATUS_COMMANDE, "cegid_devis_project.status_commande");
end */;;
DELIMITER ;


DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `insert_status_project` AFTER INSERT ON `cegid_project` FOR EACH ROW begin
  INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.CEGID, "new" , "cegid_project");
  INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.CEGID, NEW.STATUS, "cegid_project.status");
 end */;;

DELIMITER ;


ALTER TABLE `cegid_status_evolution` CHANGE `ORIGIN` `ORIGIN` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

UPDATE `cegid_status_evolution` SET `ORIGIN` = "cegid_devis_project.status_devis" 
WHERE `ORIGIN` like "cegid_devis_project";

UPDATE `cegid_status_evolution` SET `ORIGIN` = "cegid_devis_project.status_devis" 
WHERE `ORIGIN` like "cegid_devis_project.status_dev";



--
-- Declaration Modification
--
  
UPDATE `version` SET `DATE` = '2018-10-31 00:00:00', `value` = '0.35.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.34_vers_0.35', '101', '2018-10-31 00:00:00', 'modification trigger trace status', '0.35.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.28.20 vers_0.1.28.24', '200', '2018-10-31 00:00:00', 'page suvi propositions', '0.1.28.24');
UPDATE `version` SET `DATE` = '2018-10-31 00:00:00', `description` = 'version fichier php minimal', `value` = '0.1.28.24' WHERE `version`.`id` = 'php';


COMMIT;


