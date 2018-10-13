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


ALTER TABLE `cegid_frais_mission` CHANGE `COUT` `FRAIS` DOUBLE NOT NULL;



--
-- Declaration Modification
--
  
UPDATE `version` SET `DATE` = '2018-10-13 00:00:00', `value` = '0.32.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.31_vers_0.32', '101', '2018-10-13 00:00:00', 'modification table frais COUT -> FRAIS', '0.32.0');

-- INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.27.19 vers_0.1.28.00', '200', '2018-10-06 00:00:00', 'ajout fiche de frais dans les pages projets', '0.1.28.00');
-- UPDATE `version` SET `DATE` = '2018-10-06 00:00:00', `description` = 'version fichier php minimal', `value` = '0.1.28.00' WHERE `version`.`id` = 'php';


COMMIT;


