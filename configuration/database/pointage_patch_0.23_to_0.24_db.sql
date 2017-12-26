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


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `test`
--


-- TRUNCATE TABLE `cegid_requetes`;


--
-- Contenu de la table `requetes`
--
--

INSERT INTO `cegid_requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`) VALUES
('CHECK_PRIX_VENTE', 'CHECK_PRIX_VENTE', '', 'SELECT 	PROJECT_ID, NAME, STATUS, PRIX_VENTE,	CA, (PRIX_VENTE - CA) as DIFF\r\nFROM (${PRIX_VENTE}) pv\r\nWHERE (PRIX_VENTE - CA)>6\r\n  OR (PRIX_VENTE - CA)<-6'),
('PRIX_VENTE', 'PRIX Vente et CA', 'Prix de vente et CA', 'SELECT cpc.PROJECT_ID, cp.NAME, cp.STATUS, cp.PRIX_VENTE, sum(cpc.uo*cpc.cout) as CA\r\nFROM cegid_project_cout cpc, cegid_project cp\r\nWHERE \r\n  cp.CEGID = cpc.PROJECT_ID\r\nGROUP BY cpc.PROJECT_ID');





 




  
UPDATE `version` SET `DATE` = '2017-12-26 00:00:00', `value` = '0.24.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.23_vers_0.24', '101', '2017-12-26 00:00:00', 'udate user table', '0.24.0');


COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
