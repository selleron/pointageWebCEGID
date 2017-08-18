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


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `test`
--


CREATE TABLE `cegid_status_evolution` (
  `REFERENCE` varchar(50) NOT NULL,
  `DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `STATUS` varchar(30) NOT NULL,
  `ORIGIN` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;



DELIMITER $$
CREATE TRIGGER `evol_status_devis` AFTER UPDATE ON `cegid_devis_project` FOR EACH ROW IF NEW.STATUS <> OLD.STATUS THEN  
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.STATUS, "cegid_devis_project") ;
			END IF
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insert_status_devis` AFTER INSERT ON `cegid_devis_project` FOR EACH ROW INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.STATUS, "cegid_devis_project")
$$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER `insert_status_project` AFTER INSERT ON `cegid_project` FOR EACH ROW INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.CEGID, NEW.STATUS, "cegid_project")
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_status_project` AFTER UPDATE ON `cegid_project` FOR EACH ROW INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.CEGID, NEW.STATUS, "cegid_project")
$$
DELIMITER ;

 
CREATE TABLE `cegid_file` (
  `REFERENCE` varchar(50) NOT NULL,
  `FILE` int(11) NOT NULL,
  `VERSION` int(11) DEFAULT NULL,
  `COMMENTAIRE` varchar(400) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables d�charg�es
--

--
-- Index pour la table `cegid_file`
--

ALTER TABLE `cegid_file`
  ADD KEY `FILE` (`FILE`),
  ADD KEY `REFERENCE` (`REFERENCE`);


ALTER TABLE `cegid_file`
  ADD CONSTRAINT `cegid_file_ibfk_1` FOREIGN KEY (`FILE`) REFERENCES `files` (`id`);

 

UPDATE `version` SET `DATE` = '2017-08-18 00:00:00', `value` = '0.18.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.17_vers_0.18', '101', '2017-08-13 00:00:00', 'udate project table', '0.18.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.22.5_vers_0.1.22.6', '200', '2017-08-18 00:00:00', 'modification devis', '0.1.22.6');
UPDATE `version` SET `DATE` = '2017-08-18 00:00:00', `description` = 'version fichier php minimal', `value` = '0.1.22.6' WHERE `version`.`id` = 'php';

COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
