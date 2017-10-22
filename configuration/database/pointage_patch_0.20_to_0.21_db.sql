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

DROP TRIGGER `evol_status_devis`;
DROP TRIGGER `insert_status_devis`;
ALTER TABLE `cegid_devis_project` CHANGE `STATUS` `STATUS_DEVIS` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;


ALTER TABLE `cegid_devis_project` ADD `STATUS_COMMANDE` VARCHAR(15)  NULL AFTER `MODIFICATION`;
ALTER TABLE `cegid_devis_project` ADD `STATUS_CEGID` VARCHAR(15) NULL AFTER `CEGID`;
UPDATE `cegid_devis_project` SET `STATUS_DEVIS` = 'A/R Signe', `STATUS_COMMANDE` = 'A/R Signe', `STATUS_CEGID` = 'Cree' WHERE 1;
ALTER TABLE `cegid_devis_project` CHANGE `STATUS_COMMANDE` `STATUS_COMMANDE` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A/R Signe';
ALTER TABLE `cegid_devis_project` CHANGE `STATUS_CEGID` `STATUS_CEGID` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Cree';


--
-- D�clencheurs `cegid_devis_project`
--

DELIMITER $$
CREATE TRIGGER `insert_status_devis` AFTER INSERT ON `cegid_devis_project` 
FOR EACH ROW
begin
INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.STATUS_DEVIS, "cegid_devis_project.status_devis");
INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.STATUS_CEGID, "cegid_devis_project.status_cegid");
INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.STATUS_COMMANDE, "cegid_devis_project.status_commande");
end
$$
DELIMITER ;



DELIMITER $$
CREATE TRIGGER `evol_status_devis` AFTER UPDATE ON `cegid_devis_project` 
FOR EACH ROW
begin
  IF NEW.STATUS_DEVIS <> OLD.STATUS_DEVIS THEN  
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.STATUS_DEVIS, "cegid_devis_project.status_devis") ;
  END IF;
  IF NEW.STATUS_CEGID <> OLD.STATUS_CEGID THEN  
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.STATUS_CEGID, "cegid_devis_project.status_cegid") ;
  END IF;
  IF NEW.STATUS_COMMANDE <> OLD.STATUS_COMMANDE THEN  
			INSERT INTO `cegid_status_evolution`(`REFERENCE`, `STATUS`, `ORIGIN`) VALUES (NEW.ID, NEW.STATUS_COMMANDE, "cegid_devis_project.status_commmande") ;
  END IF;
end
$$
DELIMITER ;



CREATE TABLE `cegid_status_commande` (
  `ID` varchar(15) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `ORDRE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `cegid_status_commande` (`ID`, `NAME`, `ORDRE`) VALUES
('Neant', 'Neant', 0),
('En signature', 'En signature', 1),
('Recu', 'Recu', 2),
('A/R Signe', 'A/R Signe', 3),
('Annule', 'Annule', 4);

ALTER TABLE `cegid_status_commande`
  ADD PRIMARY KEY (`ID`);

CREATE TABLE `cegid_status_cegid` (
  `ID` varchar(15) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `ORDRE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cegid_status_cegid` (`ID`, `NAME`, `ORDRE`) VALUES
('A faire', 'A faire', 1),
('Demande', 'Demande', 2),
('Cree', 'Cree', 3),
('Annule', 'Annule', 4);
INSERT INTO `cegid_status_cegid` (`ID`, `NAME`, `ORDRE`) VALUES ('Neant', 'Neant', '0');

ALTER TABLE `cegid_status_cegid`
  ADD PRIMARY KEY (`ID`);

 
ALTER TABLE `cegid_devis_project` ADD FOREIGN KEY (`STATUS_CEGID`) REFERENCES `cegid_status_cegid`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT; 
ALTER TABLE `cegid_devis_project` ADD FOREIGN KEY (`STATUS_COMMANDE`) REFERENCES `cegid_status_commande`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;


INSERT INTO `cegid_status_devis` (`ID`, `NAME`, `ORDRE`) VALUES ('Accepte', 'Accepte', '7');
INSERT INTO `cegid_status_devis` (`ID`, `NAME`, `ORDRE`) VALUES ('Valide', 'Valie', '5');
UPDATE `cegid_devis_project` SET `STATUS_DEVIS` = 'Accepte' WHERE `cegid_devis_project`.`STATUS_DEVIS` = 'A/R Signe';

  
UPDATE `version` SET `DATE` = '2017-08-30 00:00:00', `value` = '0.19.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.18_vers_0.19', '101', '2017-08-30 00:00:00', 'udate devis table', '0.19.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.22.6_vers_0.1.23.3', '200', '2017-08-30 00:00:00', 'modification devis', '0.1.23.3');
UPDATE `version` SET `DATE` = '2017-08-30 00:00:00', `description` = 'version fichier php minimal', `value` = '0.1.23.3' WHERE `version`.`id` = 'php';

COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
