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


ALTER TABLE `cegid_user` ADD `SOCIETE` VARCHAR(30) NULL AFTER `PROFIL`;
ALTER TABLE `cegid_user` ADD `STATUS` VARCHAR(15) NULL AFTER `SOCIETE`;
ALTER TABLE `cegid_user` ADD `ARRIVEE` DATE NULL AFTER `STATUS`;
ALTER TABLE `cegid_user` ADD `DEPART` DATE NULL AFTER `ARRIVEE`;

ALTER TABLE `cegid_user` ADD FOREIGN KEY (`STATUS`) REFERENCES `cegid_status_cegid`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;




  
UPDATE `version` SET `DATE` = '2017-10-322 00:00:00', `value` = '0.21.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.20_vers_0.21', '101', '2017-10-22 00:00:00', 'udate user table', '0.21.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.23.10_vers_0.1.24.0', '200', '2017-10-22 00:00:00', 'modification user', '0.1.24.0');
UPDATE `version` SET `DATE` = '2017-10-22 00:00:00', `description` = 'version fichier php minimal', `value` = '0.1.24.0' WHERE `version`.`id` = 'php';

COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
