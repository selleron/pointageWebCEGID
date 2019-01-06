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

CREATE TABLE `cegid_proposition` (
  `ID` varchar(15) NOT NULL DEFAULT 'DP17XXX',
  `PRIX_VENTE` double ,
  `REUSSITE` double,
  `COMMENTAIRE` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




--
-- Index pour la table `cegid_proposition`
--
-- ALTER TABLE `cegid_proposition`   ADD KEY `ID` (`ID`);

ALTER TABLE `cegid_proposition` ADD PRIMARY KEY(`ID`);   
  
  
--
-- Contraintes pour les tables d�charg�es
--

--
-- Contraintes pour la table `cegid_proposition`
--
ALTER TABLE `cegid_proposition`
  ADD CONSTRAINT `cegid_proposition_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `cegid_devis_project` (`ID`);



  
 
--
-- Declaration Modification
--
  
UPDATE `version` SET `DATE` = '2018-12-29 00:00:00', `value` = '0.37.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.36_vers_0.37', '101', '2018-12-29 00:00:00', 'modification table user', '0.37.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.29 vers_0.1.30.00', '200', '2018-12-29 00:00:00', 'pris en compte suivi_proposition', '0.1.29.00');
UPDATE `version` SET `DATE` = '2018-12-29 00:00:00', `description` = 'version fichier php minimal', `value` = '0.1.30.00' WHERE `version`.`id` = 'php';


COMMIT;


