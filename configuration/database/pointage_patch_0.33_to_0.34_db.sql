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

ALTER TABLE `cegid_user` 
  ADD `EMAIL1` VARCHAR(100) NULL AFTER `DEPART`, 
  ADD `EMAIL2` VARCHAR(100) NULL AFTER `EMAIL1`, 
  ADD `TEL1` VARCHAR(20) NULL AFTER `EMAIL2`, 
  ADD `TEL2` VARCHAR(20) NULL AFTER `TEL1`, 
  ADD `GROUPE` VARCHAR(30) NULL AFTER `TEL2`;


--
-- Declaration Modification
--
  
UPDATE `version` SET `DATE` = '2018-10-27 00:00:00', `value` = '0.34.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.33_vers_0.34', '101', '2018-10-27 00:00:00', 'modification table user et devis', '0.34.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.28.15 vers_0.1.28.16', '200', '2018-10-27 00:00:00', 'pris en compte evolution user et devis', '0.1.28.16');
UPDATE `version` SET `DATE` = '2018-10-27 00:00:00', `description` = 'version fichier php minimal', `value` = '0.1.28.16' WHERE `version`.`id` = 'php';


COMMIT;


