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



ALTER TABLE `cegid_project` CHANGE `DEBUT` `DEBUT` DATE NULL;
ALTER TABLE `cegid_project` CHANGE `FIN` `FIN` DATE NULL;
ALTER TABLE `cegid_project` CHANGE `FIN_GARANTIE` `FIN_GARANTIE` DATE NULL;
ALTER TABLE `cegid_project` CHANGE `PRIX_VENTE`   `PRIX_VENTE` double NULL;
 

--
-- Declaration Modification
--
  
UPDATE `version` SET `DATE` = now(), `value` = '0.41.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.40_vers_0.41', '101', now(), 'modification table projet : date NULL', '0.41.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.32. vers_0.1.35.2', '200', now(), 'table projet champs NULL possible', '0.1.35.2');
UPDATE `version` SET `DATE` = now(), `description` = 'version fichier php minimal', `value` = '0.1.35.2' WHERE `version`.`id` = 'php';


COMMIT;


