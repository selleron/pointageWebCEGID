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


ALTER TABLE `cegid_devis_project` ADD `CEGID` VARCHAR(10)  NULL AFTER `MODIFICATION`;
ALTER TABLE `cegid_devis_project` ADD `NUXEO` VARCHAR(400)  NULL AFTER `CEGID`;
ALTER TABLE `cegid_devis_project` CHANGE `COMMENTAIRE` `COMMENTAIRE` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
ALTER TABLE `cegid_devis_project` ADD FOREIGN KEY (`CEGID`) REFERENCES `cegid_project`(`CEGID`) ON DELETE RESTRICT ON UPDATE RESTRICT;


UPDATE `version` SET `DATE` = '2017-08-17 00:00:00', `value` = '0.17.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.16_vers_0.17', '101', '2017-08-13 00:00:00', 'udate project table', '0.17.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.22.2_vers_0.1.22.5', '200', '2017-08-17 00:00:00', 'modification devis', '0.1.22.5');
UPDATE `version` SET `DATE` = '2017-08-17 00:00:00', `description` = 'version fichier php minimal', `value` = '0.1.22.5' WHERE `version`.`id` = 'php';

COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
