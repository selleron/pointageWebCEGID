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

ALTER TABLE `requetes` ADD `REQUEST_PARAM` TEXT NULL AFTER `SQL_REQUEST`;

ALTER TABLE `cegid_requetes` ADD `REQUEST_PARAM` TEXT NULL AFTER `SQL_REQUEST`;



INSERT INTO `requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`, `REQUEST_PARAM`) VALUES('Reset_pointage_previsionnel_VESPA', 'Reset pointage previsionnel VESPA', 'Reset pointage previsionnel VESPA pour un intervalle donné', 'DELETE FROM `cegid_pointage_previsionnel` WHERE \r\n    `PROJECT_ID`>=\"P19900\"\r\n AND `PROJECT_ID`<=\"P19999\"\r\n AND `DATE` >= \"${date1}\"\r\n AND `DATE` <= \"${date2}\";\r\n', '<tr><td>date1</td><td><input type=\"text\" size=\"50\" name=\"date1\" value=\"2019-01-01\"></td></tr>\r\n<tr><td>date2</td><td><input type=\"text\" size=\"50\" name=\"date2\" value=\"2019-12-31\"></td></tr>');
INSERT INTO `requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`, `REQUEST_PARAM`) VALUES('Reset_pointage_VESPA', 'Reset pointage VESPA', 'Reset pointage VESPA pour un intervalle donne', 'DELETE FROM `cegid_pointage` WHERE \r\n    `PROJECT_ID`>=\"P19900\"\r\n AND `PROJECT_ID`<=\"P19999\"\r\n AND `DATE` >= \"${date1}\"\r\n AND `DATE` <= \"${date2}\";\r\n', '<tr><td>date1</td><td><input type=\"text\" size=\"50\" name=\"date1\" value=\"2019-01-01\"></td></tr>\r\n<tr><td>date2</td><td><input type=\"text\" size=\"50\" name=\"date2\" value=\"2019-12-31\"></td></tr>');

 
--
-- Declaration Modification
--
  
UPDATE `version` SET `DATE` = '2019-11-12 00:00:00', `value` = '0.38.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.37_vers_0.38', '101', '2019-11-12 00:00:00', 'modification table user', '0.38.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.30 vers_0.1.30.03', '200', '2019-11-12 00:00:00', 'pris en compte suivi_proposition', '0.1.30.03');
UPDATE `version` SET `DATE` = '2019-11-12 00:00:00', `description` = 'version fichier php minimal', `value` = '0.1.30.03' WHERE `version`.`id` = 'php';


COMMIT;


