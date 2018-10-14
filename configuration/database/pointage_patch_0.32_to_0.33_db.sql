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

ALTER TABLE `cegid_frais_mission` CHANGE `COMMENTAIRE` `TITRE` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `cegid_frais_mission` ADD `COMMENTAIRE` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `TITRE`;

ALTER TABLE `cegid_frais_mission` MODIFY COLUMN `TITRE` VARCHAR(150) AFTER `PROJECT_ID`;

ALTER TABLE `cegid_frais_mission` ADD `FRAIS_EN_LOCAL` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `TITRE`;

--
-- Declaration Modification
--
  
UPDATE `version` SET `DATE` = '2018-10-14 00:00:00', `value` = '0.33.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.32_vers_0.33', '101', '2018-10-14 00:00:00', 'modification table frais Titre FraisLocal', '0.33.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.28.00 vers_0.1.28.12', '200', '2018-10-16 00:00:00', 'pris en compte evolution frais', '0.1.28.12');
UPDATE `version` SET `DATE` = '2018-10-06 00:00:00', `description` = 'version fichier php minimal', `value` = '0.1.28.00' WHERE `version`.`id` = 'php';


COMMIT;


