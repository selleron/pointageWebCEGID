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


INSERT INTO `cegid_requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`) VALUES ('REQUETE_PROJECTS', 'REQUETE_PROJECTS',      'Selection all projects',      'select CEGID, NAME, STATUS, TYPE, VISIBLE from cegid_project');
INSERT INTO `cegid_requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`) VALUES ('ARCHIVE_PROJECTS', 'ARCHIVE_PROJECTS',      'Archivage des projets clos',  'UPDATE `cegid_project` SET `VISIBLE`=\'Archive\' WHERE STATUS LIKE \'Clos\' OR STATUS LIKE \'Clos AT\'');
INSERT INTO `cegid_requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`) VALUES ('UNARCHIVE_PROJECTS', 'UNARCHIVE_PROJECTS', 'Dearchivage des projets clos', 'UPDATE `cegid_project` SET `VISIBLE`=\'Visible\' WHERE 1');


UPDATE `version` SET `DATE` = '2018-01-05 00:00:00', `value` = '0.27.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.26_vers_0.27', '101', '2018-01-05 00:00:00', 'visible/archive', '0.27.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.27.7_vers_0.1.27.11', '200', '2018-01-05 00:00:00', 'page archives', '0.1.27.11');
UPDATE `version` SET `DATE` = '2018-01-05 00:00:00', `description` = 'version fichier php minimal', `value` = '0.1.27.11' WHERE `version`.`id` = 'php';


COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
