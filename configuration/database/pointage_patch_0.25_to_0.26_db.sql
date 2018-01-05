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

ALTER TABLE `cegid_project` ADD `VISIBLE` VARCHAR(15) NOT NULL DEFAULT 'Visible' AFTER `COMMENTAIRE`;
ALTER TABLE `cegid_project` ADD FOREIGN KEY (`VISIBLE`) REFERENCES `cegid_status_visible`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `cegid_devis_project` ADD `VISIBLE` VARCHAR(15) NOT NULL DEFAULT 'Visible' AFTER `COMMENTAIRE`;
ALTER TABLE `cegid_devis_project` ADD FOREIGN KEY (`VISIBLE`) REFERENCES `cegid_status_visible`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `cegid_user` ADD `VISIBLE` VARCHAR(15) NOT NULL DEFAULT 'Visible' AFTER `DEPART`;
ALTER TABLE `cegid_user` ADD FOREIGN KEY (`VISIBLE`) REFERENCES `cegid_status_visible`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;


UPDATE `version` SET `DATE` = '2018-01-04 00:00:00', `value` = '0.26.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.25_vers_0.26', '101', '2018-01-04 00:00:00', 'visible/archive', '0.26.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.27.7_vers_0.1.27.9', '200', '2018-01-04 00:00:00', 'preparation archives', '0.1.27.9');
UPDATE `version` SET `DATE` = '2018-01-04 00:00:00', `description` = 'version fichier php minimal', `value` = '0.1.27.9' WHERE `version`.`id` = 'php';


COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
