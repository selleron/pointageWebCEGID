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


INSERT INTO `cegid_requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`, `REQUEST_PARAM`) VALUES
('HISTORIQUE_COUT', 'LISTE HISTORIQUE_COUT', 'liste les différentes dates de backup des couts.\r\nPermet si besoin une retauration apres l action cloture.', 'SELECT distinct HISTORY, HISTORY_ACTION, count(HISTORY)\r\nFROM cegid_project_cout_history\r\nWHERE \r\n 1\r\nGROUP BY HISTORY', '');



ALTER TABLE `cegid_status_evolution` 
CHANGE `STATUS` `STATUS` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;



--
-- Declaration Modification
--
  
UPDATE `version` SET `DATE` = now(), `value` = '0.48.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.47_vers_0.48', '101', now(), 'add liste history cout', '0.48.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.41.11 vers_0.1.42.00', '200', now(), 'add liste history cout', '0.1.42.0');
UPDATE `version` SET `DATE` = now(), `description` = 'version fichier php minimal', `value` = '0.1.42.00' WHERE `version`.`id` = 'php';


COMMIT;



