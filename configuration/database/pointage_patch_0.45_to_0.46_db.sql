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
-- Structure de la table `cegid_commande_prestataire`
--

DROP TABLE `cegid_commande_prestataire`;

CREATE TABLE `cegid_commande_prestataire` (
  `ID` varchar(15) NOT NULL,
  `USER_ID` int(10) NOT NULL,
  `SOCIETE` varchar(30) DEFAULT NULL,
  `GROUPE` varchar(20) DEFAULT NULL,
  `STATUS` varchar(15) DEFAULT NULL,
  `COMMANDE` varchar(20) DEFAULT NULL,
  `DEBUT` date NOT NULL,
  `FIN` date NOT NULL,
  `TARIF_ACHAT` double NOT NULL,
  `TARIF_VENTE` double NOT NULL,
  `UO` double NOT NULL DEFAULT '0',
  `COUT` double NOT NULL DEFAULT '0',
  `VISIBLE` varchar(15) NOT NULL DEFAULT 'Visible',
  `COMMENTAIRE` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables d�charg�es
--

--
-- Index pour la table `cegid_commande_prestataire`
--
ALTER TABLE `cegid_commande_prestataire`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `STATUS` (`STATUS`),
  ADD KEY `VISIBLE` (`VISIBLE`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- Contraintes pour les tables d�charg�es
--

--
-- Contraintes pour la table `cegid_commande_prestataire`
--
ALTER TABLE `cegid_commande_prestataire`
  ADD CONSTRAINT `cegid_commande_prestataire_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `cegid_user` (`ID`),
  ADD CONSTRAINT `cegid_commande_prestataire_ibfk_2` FOREIGN KEY (`STATUS`) REFERENCES `cegid_status_cegid` (`ID`),
  ADD CONSTRAINT `cegid_commande_prestataire_ibfk_3` FOREIGN KEY (`VISIBLE`) REFERENCES `cegid_status_visible` (`ID`);




--
-- Declaration Modification
--
  
UPDATE `version` SET `DATE` = now(), `value` = '0.46.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.45_vers_0.46', '101', now(), 'add table commande prestataires', '0.46.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.39.0 vers_0.1.41.11', '200', now(), 'table users modif champs', '0.1.39.0');
UPDATE `version` SET `DATE` = now(), `description` = 'version fichier php minimal', `value` = '0.1.41.11' WHERE `version`.`id` = 'php';


COMMIT;


