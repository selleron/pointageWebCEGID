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
  ADD `CEGID_ID` VARCHAR(10) NULL AFTER `GROUPE`;


 
--
-- Declaration Modification
--
  
UPDATE `version` SET `DATE` = '2018-12-02 00:00:00', `value` = '0.36.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.35_vers_0.36', '101', '2018-12-02 00:00:00', 'modification table user', '0.36.0');

-- INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.28.15 vers_0.1.28.18', '200', '2018-10-27 00:00:00', 'pris en compte evolution user et devis', '0.1.28.16');
UPDATE `version` SET `DATE` = '2018-12-02 00:00:00', `description` = 'version fichier php minimal', `value` = '0.1.28.26' WHERE `version`.`id` = 'php';


COMMIT;


