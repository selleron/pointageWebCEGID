-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 25 juin 2017 à 17:31
-- Version du serveur :  5.5.55-0+deb8u1
-- Version de PHP :  5.6.30-0+deb8u1

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `test`
--

-- --------------------------------------------------------

DROP TABLE IF EXISTS `cegid_pointage_previsionnel_history`;


--
-- Structure de la table `cegid_pointage_previsionnel_history`
--

CREATE TABLE `cegid_pointage_previsionnel_history` (
  `HISTORY` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `HISTORY_ACTION` varchar(255) NOT NULL DEFAULT 'COMPLET',
  `PROJECT_ID` varchar(10) NOT NULL,
  `DATE` date NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `PROFIL` varchar(10) NOT NULL,
  `UO` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `cegid_pointage_previsionnel_history`
--
ALTER TABLE `cegid_pointage_previsionnel_history`
  ADD KEY `USER_ID` (`USER_ID`),
  ADD KEY `PROFIL` (`PROFIL`),
  ADD KEY `PROJECT_ID` (`PROJECT_ID`);



UPDATE `version` SET `DATE` = '2017-07-01 00:00:00', `value` = '0.14.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.13_vers_0.14', '101', '2017-07-01 00:00:00', 'add table historique previsionnel', '0.14.0');


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
