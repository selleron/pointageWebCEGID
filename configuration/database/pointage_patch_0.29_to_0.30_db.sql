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


-- UPDATE `cegid_requetes` 
--  SET `SQL_REQUEST` = 'SELECT \r\n cp.GROUPE, cara.project_id, cara.NAME, cp.type, cara.status, cara.uo_possible, cara.CA_possible,\r\n cara.uo_consomme_reel, cara.consomme_total_reel, cara.CA_Realise_reel,\r\n cara.uo_consomme_prev, cara.consomme_total_prev, cara.CA_Realise_prev,\r\n cp.PRIX_VENTE, cp.DEBUT, cp.FIN, cp.FIN_GARANTIE\r\nFROM (${CA Responsable Affaires}) cara, cegid_project cp\r\nWHERE\r\n cara.project_id = cp.CEGID\r\n' 
--  WHERE `cegid_requetes`.`ID` = 'Responsable Affaires';

UPDATE `cegid_requetes` 
  SET `SQL_REQUEST` = 'SELECT \r\n cp.GROUPE, cara.project_id, cara.NAME, cp.type, cara.status, cara.uo_possible, cara.CA_possible,\r\n cara.uo_consomme_reel, cara.consomme_total_reel, cara.CA_Realise_reel,\r\n cara.uo_consomme_prev, cara.consomme_total_prev, cara.CA_Realise_prev,\r\n cp.PRIX_VENTE, cp.DEBUT, cp.FIN, cp.FIN_GARANTIE\r\nFROM (${CA Responsable Affaires}) cara, cegid_project cp\r\nWHERE\r\n cara.project_id = cp.CEGID And\r\n not (cara.status like \"Annule\" and cara.uo_consomme_reel<=0 )\r\n' 
  WHERE `cegid_requetes`.`ID` = 'Responsable Affaires';
  

UPDATE `version` SET `DATE` = '2018-05-18 00:00:00', `value` = '0.30.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.29_vers_0.30', '101', '2018-05-18 00:00:00', 'correction requestes Resp. Affaires', '0.30.0');



COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
