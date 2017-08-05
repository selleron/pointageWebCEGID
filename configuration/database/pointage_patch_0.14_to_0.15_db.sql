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

-- --------------------------------------------------------


ALTER TABLE `cegid_pointage_previsionnel` ADD FOREIGN KEY (`PROJECT_ID`) REFERENCES `cegid_project`(`CEGID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `cegid_pointage_previsionnel` ADD FOREIGN KEY (`USER_ID`) REFERENCES `cegid_user`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `cegid_pointage_previsionnel` ADD FOREIGN KEY (`PROFIL`) REFERENCES `cegid_profil`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `cegid_pointage_previsionnel_history` ADD FOREIGN KEY (`PROJECT_ID`) REFERENCES `cegid_project`(`CEGID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `cegid_pointage_previsionnel_history` ADD FOREIGN KEY (`USER_ID`) REFERENCES `cegid_user`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `cegid_pointage_previsionnel_history` ADD FOREIGN KEY (`PROFIL`) REFERENCES `cegid_profil`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;


--
-- Structure de la table `cegid_pointage_voulu`
--

DROP TABLE IF EXISTS `cegid_pointage_voulu`;
CREATE TABLE `cegid_pointage_voulu` (
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
-- Index pour la table `cegid_pointage_voulu`
--
ALTER TABLE `cegid_pointage_voulu`
  ADD PRIMARY KEY (`PROJECT_ID`,`DATE`,`USER_ID`,`PROFIL`),
  ADD KEY `USER_ID` (`USER_ID`),
  ADD KEY `PROFIL` (`PROFIL`),
  ADD KEY `PROJECT_ID` (`PROJECT_ID`);

ALTER TABLE `cegid_pointage_voulu` ADD FOREIGN KEY (`PROJECT_ID`) REFERENCES `cegid_project`(`CEGID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `cegid_pointage_voulu` ADD FOREIGN KEY (`USER_ID`) REFERENCES `cegid_user`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `cegid_pointage_voulu` ADD FOREIGN KEY (`PROFIL`) REFERENCES `cegid_profil`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;



UPDATE `version` SET `DATE` = '2017-08-05 00:00:00', `value` = '0.15.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.14_vers_0.15', '101', '2017-08-05 00:00:00', 'add table pointage voulu', '0.15.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.10.2_vers_0.1.20.0', '200', '2017-08-05 00:00:00', 'ajout gestion pointage voulu (global user/project/profil)', '0.1.20.0');
UPDATE `version` SET `DATE` = '2017-08-05 00:00:00', `description` = 'version fichier php minimal', `value` = '0.1.20.0' WHERE `version`.`id` = 'php';

COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
