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


--
-- Base de données :  `test`
--

-- --------------------------------------------------------

ALTER TABLE `cegid_user` ADD `TEAM` VARCHAR(30) NOT NULL AFTER `GROUPE`;
ALTER TABLE `cegid_user` ADD `LOCALISATION` VARCHAR(100) NOT NULL AFTER `TEAM`;



 

--
-- Declaration Modification
--
  
UPDATE `version` SET `DATE` = now(), `value` = '0.42.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.41_vers_0.42', '101', now(), 'modification table users : date NULL', '0.42.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.35.2 vers_0.1.39.0', '200', now(), 'table users modif champs', '0.1.39.0');
UPDATE `version` SET `DATE` = now(), `description` = 'version fichier php minimal', `value` = '0.1.39.0' WHERE `version`.`id` = 'php';


COMMIT;


