-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- G√©n√©r√© le :  Sam 05 Ao√ªt 2017 √† 14:15
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
-- Base de donn√ees :  `test`
--
CREATE TABLE `cegid_status_visible` (
  `ID` varchar(15) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `ORDRE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- DÈchargement des donnÈes de la table `cegid_status_visible`
--

INSERT INTO `cegid_status_visible` (`ID`, `NAME`, `ORDRE`) VALUES
('Visible', 'Visible', 1),
('Archive', 'Archive', 2);
--
-- Index pour les tables dÈchargÈes
--

--
-- Index pour la table `cegid_status_visible`
--
ALTER TABLE `cegid_status_visible`
  ADD PRIMARY KEY (`ID`);


 




  
UPDATE `version` SET `DATE` = '2018-1-01 00:00:00', `value` = '0.25.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.24_vers_0.25', '101', '2018-01-01 00:00:00', 'visible/archive', '0.25.0');


COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
