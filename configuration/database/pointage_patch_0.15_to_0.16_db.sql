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


--
-- Structure de la table `cegid_status_project`
--

CREATE TABLE `cegid_status_project` (
  `ID` varchar(15) NOT NULL,
  `NAME` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

ALTER TABLE `cegid_status_project`  ADD PRIMARY KEY (`ID`);
ALTER TABLE `cegid_status_project` ADD `ORDRE` INT NOT NULL AFTER `NAME`;

--
-- Déchargement des données de la table `cegid_status_project`
--

INSERT INTO `cegid_status_project` (`ID`, `NAME`) VALUES
('Prevision', 'Prevision'),
('En cours', 'En cours'),
('En recette', 'En recette'),
('En garantie', 'En garantie'),
('Clos', 'Clos'),
('Archive', 'Archive'),
('Annule', 'Annulé');



---
--- table devis
---
CREATE TABLE `cegid_status_devis` (
  `ID` varchar(15) NOT NULL,
  `NAME` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

ALTER TABLE `cegid_status_devis`  ADD PRIMARY KEY (`ID`);
ALTER TABLE `cegid_status_devis` ADD `ORDRE` INT NOT NULL AFTER `NAME`;

INSERT INTO `cegid_status_devis` (`ID`, `NAME`) VALUES
('A faire', 'A faire'),
('En redaction', 'En redaction'),
('A valider', 'A valider'),
('En validation', 'En validation'),
('Envoye', 'Evoye'),
('En signature', 'En signature'),
('Clos', 'Clos'),
('Annule', 'Annulé');


CREATE TABLE `cegid_devis_project` (
  `ID` varchar(15) NOT NULL DEFAULT 'DP17XXX';,
  `NAME` varchar(100) NOT NULL,
  `VERSION` varchar(15) NOT NULL,
  `STATUS` varchar(15) NOT NULL,
  `COMMENTAIRE` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

ALTER TABLE `cegid_devis_project`  ADD PRIMARY KEY (`ID`);
ALTER TABLE `cegid_devis_project` ADD FOREIGN KEY (`STATUS`) REFERENCES `cegid_status_devis`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;


---
--- table project
---

ALTER TABLE `cegid_project` CHANGE `NAME` `NAME` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `cegid_project` ADD `COMMENTAIRE` TEXT NOT NULL AFTER `FIN_GARANTIE`;
ALTER TABLE `cegid_project` ADD `STATUS` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Prevision' AFTER `FIN_GARANTIE`;
ALTER TABLE `cegid_project` ADD FOREIGN KEY (`STATUS`) REFERENCES `cegid_status_project`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;


-- --------------------------------------------------------


UPDATE `version` SET `DATE` = '2017-08-13 00:00:00', `value` = '0.16.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.15_vers_0.16', '101', '2017-08-13 00:00:00', 'udate project table', '0.16.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.10.2_vers_0.1.21.0', '200', '2017-08-13 00:00:00', 'modification gestion projet', '0.1.21.0');
UPDATE `version` SET `DATE` = '2017-08-16 00:00:00', `description` = 'version fichier php minimal', `value` = '0.1.21.0' WHERE `version`.`id` = 'php';

COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
