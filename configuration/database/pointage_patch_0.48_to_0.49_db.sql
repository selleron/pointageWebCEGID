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


ALTER TABLE `cegid_status_evolution` 
CHANGE `STATUS` `STATUS` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;


UPDATE `cegid_requetes` SET `SQL_REQUEST` = 'SELECT cpc.ID, cp.DATE, cp.PROJECT_ID, p.NAME, p.STATUS, cpc.PROFIL_ID, cpc.commentaire, cpc.COUT, cpc.UO as UO_possible, \r\nsum(cp.UO) as UO_consomme, (cpc.UO - sum(cp.UO)) as UO_restant \r\nFROM (${ALL_CEGID_POINTAGE}) cp, cegid_project_cout cpc, cegid_project p \r\nWHERE \r\n  year(cp.DATE)=${year} \r\n  AND cp.PROJECT_ID = cpc.PROJECT_ID \r\n  AND cp.PROFIL = cpc.PROFIL_ID \r\n  AND year(cp.DATE) = year(cpc.DATE) \r\n  AND p.CEGID = cp.PROJECT_ID\r\nGROUP BY PROJECT_ID, PROFIL_ID' WHERE `cegid_requetes`.`ID` = 'UO_RESTANT';


--
-- Declaration Modification
--
  
UPDATE `version` SET `DATE` = now(), `value` = '0.49.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.47_vers_0.49', '101', now(), 'bug cloture', '0.48.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.42.0 vers_0.1.42.01', '200', now(), 'bug cloture', '0.1.42.1');
UPDATE `version` SET `DATE` = now(), `description` = 'version fichier php minimal', `value` = '0.1.42.01' WHERE `version`.`id` = 'php';


COMMIT;


