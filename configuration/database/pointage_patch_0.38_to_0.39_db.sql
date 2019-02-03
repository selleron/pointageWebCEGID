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
-- Structure de la table `cegid_pointage_import`
--

CREATE TABLE `cegid_pointage_import` (
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
-- Index pour la table `cegid_pointage_import`
--
ALTER TABLE `cegid_pointage_import`
  ADD PRIMARY KEY (`PROJECT_ID`,`DATE`,`USER_ID`,`PROFIL`),
  ADD KEY `USER_ID` (`USER_ID`),
  ADD KEY `PROFIL` (`PROFIL`),
  ADD KEY `PROJECT_ID` (`PROJECT_ID`);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `cegid_pointage_import`
--
ALTER TABLE `cegid_pointage_import`
  ADD CONSTRAINT `cegid_pointage_import_ibfk_1` FOREIGN KEY (`PROJECT_ID`) REFERENCES `cegid_project` (`CEGID`),
  ADD CONSTRAINT `cegid_pointage_import_ibfk_2` FOREIGN KEY (`USER_ID`)    REFERENCES `cegid_user`    (`ID`),
  ADD CONSTRAINT `cegid_pointage_import_ibfk_3` FOREIGN KEY (`PROFIL`)     REFERENCES `cegid_profil`  (`ID`),
  ADD CONSTRAINT `cegid_pointage_import_ibfk_4` FOREIGN KEY (`PROJECT_ID`) REFERENCES `cegid_project` (`CEGID`),
  ADD CONSTRAINT `cegid_pointage_import_ibfk_5` FOREIGN KEY (`USER_ID`)    REFERENCES `cegid_user`    (`ID`);





--
-- Declaration Modification
--
  
UPDATE `version` SET `DATE` = now(), `value` = '0.39.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.38_vers_0.39', '101', now(), 'modification table user', '0.39.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.31.0 vers_0.1.32.0', '200', now(), 'pris en compte suivi_proposition', '0.1.32.00');
UPDATE `version` SET `DATE` = now(), `description` = 'version fichier php minimal', `value` = '0.1.32.0' WHERE `version`.`id` = 'php';


COMMIT;


