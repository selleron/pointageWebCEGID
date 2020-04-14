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

ALTER TABLE `requetes` 
   ADD `VISIBLE` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Visible' AFTER `REQUEST_PARAM`;

ALTER TABLE `cegid_requetes` 
   ADD `VISIBLE` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Visible' AFTER `REQUEST_PARAM`;


  
UPDATE `version` SET `DATE` = now(), `value` = '0.50' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.49._vers_0.50', '101', now(), 'update structure table requestes', '0.50.0');


INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.43.01 vers_0.1.43.02', '200', now(), 'update structure table requestes', '0.1.43.02');
UPDATE `version` SET `DATE` = now(), `description` = 'version fichier php minimal', `value` = '0.1.43.02' WHERE `version`.`id` = 'php';


ALTER TABLE `cegid_frais_mission` CHANGE `STATUS_ID` `STATUS_ID` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'UNDEF';

UPDATE `version` SET `DATE` = now(), `value` = '0.51' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.50._vers_0.51', '101', now(), 'update cegid frais', '0.51.0');

ALTER TABLE `files` ADD `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP AFTER `version`

UPDATE `version` SET `DATE` = now(), `value` = '0.52' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.51._vers_0.52', '101', now(), 'update table files', '0.52.0');

COMMIT;


